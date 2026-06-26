<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Salao;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class EventoController extends Controller
{
    public function home()
    {
        $saloes = collect();
        $avisos = collect();
        $proximosEventos = collect();

        if (Schema::hasTable('saloes')) {
            $saloes = Salao::where('ativo', true)
                ->withCount(['eventos' => fn ($query) => $query->where('status', '!=', Evento::STATUS_CANCELADO)])
                ->orderBy('nome')
                ->get();
        }

        if (Schema::hasTable('eventos')) {
            $avisos = Evento::with('salao')
                ->whereIn('status', [Evento::STATUS_CANCELADO, Evento::STATUS_ADIADO])
                ->latest('updated_at')
                ->limit(6)
                ->get();

            $proximosEventos = Evento::with('salao')
                ->where('date', '>=', today()->toDateString())
                ->whereNotIn('status', [Evento::STATUS_CANCELADO, Evento::STATUS_CANCELAMENTO_SOLICITADO])
                ->orderBy('date')
                ->orderBy('hora_inicio')
                ->limit(8)
                ->get();
        }

        return view('pages.home', compact('saloes', 'avisos', 'proximosEventos'));
    }

    public function index(Request $request)
    {
        if (! Schema::hasTable('eventos')) {
            return view('pages.eventos', ['eventos' => collect()]);
        }

        $query = Evento::with(['salao', 'user'])->orderBy('date')->orderBy('hora_inicio');

        if (! $request->user()?->isAdmin()) {
            $query->where('private', false)
                  ->where('status', Evento::STATUS_CONFIRMADO);
        }

        $eventos = $query->get();

        return view('pages.eventos', compact('eventos'));
    }

    public function create()
    {
        return view('form.cadastro', [
            'saloes' => Schema::hasTable('saloes')
                ? Salao::where('ativo', true)->orderBy('nome')->get()
                : collect(),
        ]);
    }

    public function store(Request $request)
    {
        if (! Schema::hasTable('eventos') || ! Schema::hasTable('saloes')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $validated = $this->validateEvent($request);
        $salao = Salao::findOrFail($validated['salao_id']);

        $conflito = $this->findConflito(
            $validated['salao_id'],
            $validated['date'],
            $validated['hora_inicio'],
            $validated['hora_fim']
        );

        if ($conflito) {
            return $this->redirectConflito($request, $conflito);
        }

        $validated['user_id'] = $request->user()->id;
        $validated['city'] = $salao->cidade;
        $validated['salon'] = $salao->nome;
        $validated['private'] = (bool) ($validated['private'] ?? false);
        $validated['items'] = $validated['items'] ?? [];
        $validated['status'] = Evento::STATUS_PENDENTE;
        $validated['image'] = $this->storeImage($request);
        $validated['hora'] = $validated['hora_inicio'];

        Evento::create($validated);

        return redirect('/meus-eventos')->with('success', 'Evento marcado com sucesso. A empresa ja pode acompanhar a reserva.');
    }

    public function myEvents(Request $request)
    {
        if (! Schema::hasTable('eventos')) {
            return view('pages.meus-eventos', ['eventos' => collect()]);
        }

        $eventos = $request->user()
            ->eventos()
            ->with('salao')
            ->latest('date')
            ->get();

        return view('pages.meus-eventos', compact('eventos'));
    }

    public function requestCancel(Request $request, int $id)
    {
        if (! Schema::hasTable('eventos')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $evento = Evento::findOrFail($id);
        $this->authorizeOwner($evento, $request);

        if (! in_array($evento->status, [Evento::STATUS_PENDENTE, Evento::STATUS_CONFIRMADO], true)) {
            return redirect('/meus-eventos')->with('error', 'Este evento ja nao pode receber pedido de cancelamento.');
        }

        $request->validate([
            'status_reason' => ['required', 'string', 'max:1000'],
        ]);

        $evento->update([
            'status' => Evento::STATUS_CANCELAMENTO_SOLICITADO,
            'status_reason' => $request->status_reason,
            'requested_at' => now(),
        ]);

        return redirect('/meus-eventos')->with('success', 'Pedido de cancelamento enviado para a empresa.');
    }

    public function requestPostpone(Request $request, int $id)
    {
        if (! Schema::hasTable('eventos') || ! Schema::hasTable('saloes')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $evento = Evento::findOrFail($id);
        $this->authorizeOwner($evento, $request);

        if (! in_array($evento->status, [Evento::STATUS_PENDENTE, Evento::STATUS_CONFIRMADO], true)) {
            return redirect('/meus-eventos')->with('error', 'Este evento ja nao pode receber pedido de adiamento.');
        }

        $validated = $request->validate([
            'requested_date' => ['required', 'date', 'after_or_equal:today'],
            'requested_hora_inicio' => ['required', 'date_format:H:i'],
            'requested_hora_fim' => ['required', 'date_format:H:i', 'after:requested_hora_inicio'],
            'status_reason' => ['required', 'string', 'max:1000'],
        ]);

        $conflito = $this->findConflito(
            $evento->salao_id,
            $validated['requested_date'],
            $validated['requested_hora_inicio'],
            $validated['requested_hora_fim'],
            $evento->id
        );

        if ($conflito) {
            return $this->redirectConflito($request, $conflito);
        }

        $evento->update([
            'status' => Evento::STATUS_ADIAMENTO_SOLICITADO,
            'status_reason' => $validated['status_reason'],
            'requested_date' => $validated['requested_date'],
            'requested_hora_inicio' => $validated['requested_hora_inicio'],
            'requested_hora_fim' => $validated['requested_hora_fim'],
            'requested_at' => now(),
        ]);

        return redirect('/meus-eventos')->with('success', 'Pedido de adiamento enviado para a empresa.');
    }

    public function destroy(Request $request, int $id)
    {
        if (! Schema::hasTable('eventos')) {
            abort(503, 'Base de dados indisponivel.');
        }

        if (! $request->user()?->isAdmin()) {
            abort(403);
        }

        Evento::findOrFail($id)->delete();

        return redirect('/admin')->with('success', 'Evento removido com sucesso.');
    }

    public function show(Request $request, int $id)
    {
        if (! Schema::hasTable('eventos')) {
            abort(404);
        }

        $evento = Evento::with(['salao', 'user'])->findOrFail($id);

        $user    = $request->user();
        $isAdmin = $user?->isAdmin();
        $isOwner = $user && $evento->user_id === $user->id;

        if (! $isAdmin && ! $isOwner) {
            abort(403, 'Acesso nao autorizado.');
        }

        return view('pages.mostrar', ['evento' => $evento]);
    }

    public function edit(Request $request, int $id)
    {
        if (! Schema::hasTable('eventos') || ! Schema::hasTable('saloes')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $evento = Evento::findOrFail($id);
        $this->authorizeManageEvent($evento, $request);

        return view('pages.editar', [
            'evento' => $evento,
            'saloes' => Salao::where('ativo', true)->orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        if (! Schema::hasTable('eventos') || ! Schema::hasTable('saloes')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $evento = Evento::findOrFail($id);
        $this->authorizeManageEvent($evento, $request);

        $validated = $this->validateEvent($request, $evento->id);
        $salao = Salao::findOrFail($validated['salao_id']);

        $conflito = $this->findConflito(
            $validated['salao_id'],
            $validated['date'],
            $validated['hora_inicio'],
            $validated['hora_fim'],
            $evento->id
        );

        if ($conflito) {
            return $this->redirectConflito($request, $conflito);
        }

        $validated['city'] = $salao->cidade;
        $validated['salon'] = $salao->nome;
        $validated['private'] = (bool) ($validated['private'] ?? false);
        $validated['items'] = $validated['items'] ?? [];
        $validated['hora'] = $validated['hora_inicio'];

        if ($image = $this->storeImage($request)) {
            $validated['image'] = $image;
        }

        $evento->update($validated);

        return redirect($request->user()->isAdmin() ? '/admin' : '/meus-eventos')
            ->with('success', 'Evento atualizado com sucesso.');
    }

    public function checkConflito(Request $request)
    {
        if (! Schema::hasTable('eventos') || ! Schema::hasTable('saloes')) {
            return response()->json(['conflito' => null]);
        }

        $validated = $request->validate([
            'salao_id' => ['required', 'exists:saloes,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fim' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'evento_id' => ['nullable', 'integer'],
        ]);

        $conflito = $this->findConflito(
            $validated['salao_id'],
            $validated['date'],
            $validated['hora_inicio'],
            $validated['hora_fim'],
            $validated['evento_id'] ?? null
        );

        return response()->json(['conflito' => $conflito?->only(['title', 'hora_inicio', 'hora_fim'])]);
    }

    public function adminDashboard()
    {
        if (! Schema::hasTable('eventos') || ! Schema::hasTable('saloes') || ! Schema::hasTable('users')) {
            return view('admin.dashboard', [
                'eventos' => collect(),
                'usuarios' => collect(),
                'saloes' => collect(),
                'totalEventos' => 0,
                'eventosPendentes' => 0,
                'eventosCancelados' => 0,
                'usuariosTotal' => 0,
            ]);
        }

        $eventos = Evento::with(['user', 'salao'])
            ->latest('date')
            ->get();

        return view('admin.dashboard', [
            'eventos' => $eventos,
            'usuarios' => User::withCount('eventos')->orderBy('name')->get(),
            'saloes' => Salao::withCount('eventos')->orderBy('nome')->get(),
            'totalEventos' => Evento::count(),
            'eventosPendentes' => Evento::whereIn('status', [
                Evento::STATUS_PENDENTE,
                Evento::STATUS_CANCELAMENTO_SOLICITADO,
                Evento::STATUS_ADIAMENTO_SOLICITADO,
            ])->count(),
            'eventosCancelados' => Evento::where('status', Evento::STATUS_CANCELADO)->count(),
            'usuariosTotal' => User::count(),
        ]);
    }

    public function adminUpdateEventStatus(Request $request, int $id)
    {
        if (! Schema::hasTable('eventos')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $evento = Evento::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', Rule::in([
                Evento::STATUS_PENDENTE,
                Evento::STATUS_CONFIRMADO,
                Evento::STATUS_CANCELADO,
                Evento::STATUS_ADIADO,
            ])],
            'status_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['status'] === Evento::STATUS_ADIADO && $evento->requested_date) {
            $conflito = $this->findConflito(
                $evento->salao_id,
                $evento->requested_date->format('Y-m-d'),
                $evento->requested_hora_inicio,
                $evento->requested_hora_fim,
                $evento->id
            );

            if ($conflito) {
                return $this->redirectConflito($request, $conflito);
            }

            $evento->date = $evento->requested_date;
            $evento->hora_inicio = $evento->requested_hora_inicio;
            $evento->hora_fim = $evento->requested_hora_fim;
        }

        $evento->status = $validated['status'];
        $evento->status_reason = $validated['status_reason'] ?? $evento->status_reason;
        $evento->requested_date = null;
        $evento->requested_hora_inicio = null;
        $evento->requested_hora_fim = null;
        $evento->requested_at = null;
        $evento->save();

        return redirect('/admin')->with('success', 'Estado do evento atualizado.');
    }

    public function adminStoreSalao(Request $request)
    {
        if (! Schema::hasTable('saloes')) {
            abort(503, 'Base de dados indisponivel.');
        }

        Salao::create($this->validateSalao($request));

        return redirect('/admin')->with('success', 'Salao cadastrado com sucesso.');
    }

    public function adminStoreUser(Request $request)
    {
        if (! Schema::hasTable('users')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect('/admin')->with('success', 'Utilizador criado com sucesso.');
    }

    public function adminUpdateSalao(Request $request, int $id)
    {
        if (! Schema::hasTable('saloes')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $salao = Salao::findOrFail($id);
        $data = $this->validateSalao($request);
        $data['ativo'] = $request->boolean('ativo');
        $salao->update($data);

        return redirect('/admin')->with('success', 'Salao atualizado com sucesso.');
    }

    public function adminDestroySalao(int $id)
    {
        if (! Schema::hasTable('saloes')) {
            abort(503, 'Base de dados indisponivel.');
        }

        $salao = Salao::withCount('eventos')->findOrFail($id);

        if ($salao->eventos_count > 0) {
            return redirect('/admin')->with('error', 'Nao e possivel apagar um salao com eventos vinculados.');
        }

        $salao->delete();

        return redirect('/admin')->with('success', 'Salao removido com sucesso.');
    }

    public function dashboardData()
    {
        if (! Schema::hasTable('eventos') || ! Schema::hasTable('saloes')) {
            return response()->json([
                'eventsPerMonth' => array_fill(0, 12, 0),
                'eventsPerWeek' => array_fill(0, 4, 0),
                'eventsThisMonth' => 0,
                'salonsActive' => 0,
                'privateCount' => 0,
                'publicCount' => 0,
                'pendingReservations' => 0,
                'upcomingCount' => 0,
                'eventsByCity' => collect(),
            ]);
        }

        $year = Carbon::now()->year;
        $months = collect(range(1, 12))->mapWithKeys(fn ($month) => [$month => 0])->toArray();

        $perMonth = Evento::selectRaw('MONTH(`date`) as month, count(*) as total')
            ->whereYear('date', $year)
            ->where('status', '!=', Evento::STATUS_CANCELADO)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        foreach ($perMonth as $month => $total) {
            $months[$month] = (int) $total;
        }

        $byCity = Evento::select('city', DB::raw('count(*) as total'))
            ->where('status', '!=', Evento::STATUS_CANCELADO)
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $startMonth = Carbon::now()->startOfMonth();
        $eventsPerWeek = [];

        for ($i = 0; $i < 4; $i++) {
            $start = (clone $startMonth)->addDays($i * 7)->startOfDay();
            $end = ($i < 3)
                ? (clone $start)->addDays(6)->endOfDay()
                : (clone $startMonth)->endOfMonth()->endOfDay();

            $eventsPerWeek[] = Evento::whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->where('status', '!=', Evento::STATUS_CANCELADO)
                ->count();
        }

        return response()->json([
            'eventsPerMonth' => array_values($months),
            'eventsPerWeek' => $eventsPerWeek,
            'eventsThisMonth' => (int) ($months[Carbon::now()->month] ?? 0),
            'salonsActive' => Salao::where('ativo', true)->count(),
            'privateCount' => Evento::where('private', true)->count(),
            'publicCount' => Evento::where('private', false)->count(),
            'pendingReservations' => Evento::whereIn('status', [
                Evento::STATUS_PENDENTE,
                Evento::STATUS_CANCELAMENTO_SOLICITADO,
                Evento::STATUS_ADIAMENTO_SOLICITADO,
            ])->count(),
            'upcomingCount' => Evento::whereBetween('date', [
                Carbon::now()->toDateString(),
                Carbon::now()->addDays(30)->toDateString(),
            ])->where('status', '!=', Evento::STATUS_CANCELADO)->count(),
            'eventsByCity' => $byCity,
        ]);
    }

    private function validateEvent(Request $request, ?int $ignoreEventId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'salao_id' => ['required', 'exists:saloes,id'],
            'private' => ['nullable', 'boolean'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fim' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'participantes' => ['required', 'integer', 'min:1'],
            'email' => ['required', 'email', 'max:255'],
            'tipo' => ['required', 'string', 'max:255'],
            'items' => ['nullable', 'array'],
            'items.*' => ['string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function validateSalao(Request $request): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:255'],
            'capacidade' => ['required', 'integer', 'min:1'],
            'descricao' => ['nullable', 'string'],
            'ativo' => ['nullable', 'boolean'],
        ]);
    }

    private function findConflito(int|string|null $salaoId, string $date, string $horaInicio, string $horaFim, ?int $ignoreEventId = null): ?Evento
    {
        return Evento::where('salao_id', $salaoId)
            ->where('date', $date)
            ->where('status', '!=', Evento::STATUS_CANCELADO)
            ->when($ignoreEventId, fn ($query) => $query->where('id', '!=', $ignoreEventId))
            ->where('hora_inicio', '<', $horaFim)
            ->where('hora_fim', '>', $horaInicio)
            ->first(['id', 'title', 'hora_inicio', 'hora_fim']);
    }

    private function redirectConflito(Request $request, Evento $conflito)
    {
        return redirect()->back()->withInput()->with(
            'error',
            "Conflito de horario. Ja existe o evento {$conflito->title} das {$conflito->hora_inicio} as {$conflito->hora_fim} neste salao."
        );
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $name = time().'_'.$file->getClientOriginalName();
        $destination = public_path('img/eventos');

        if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $name);

        return $name;
    }

    private function authorizeOwner(Evento $evento, Request $request): void
    {
        if ($evento->user_id !== $request->user()->id) {
            abort(403);
        }
    }

    private function authorizeManageEvent(Evento $evento, Request $request): void
    {
        if ($request->user()?->isAdmin()) {
            return;
        }

        if ($evento->user_id !== $request->user()->id || $evento->status !== Evento::STATUS_PENDENTE) {
            abort(403);
        }
    }
}