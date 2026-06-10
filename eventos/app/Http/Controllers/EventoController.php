<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Evento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventoController extends Controller
{

    public function index(){
        $eventos = Evento::all();
        return view('pages.eventos', compact('eventos'));
    }

    public function dashboardData()
    {
        $year = Carbon::now()->year;

        $months = collect(range(1,12))->mapWithKeys(function($m){ return [$m => 0]; })->toArray();

        $perMonth = Evento::selectRaw('MONTH(`date`) as month, count(*) as total')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->pluck('total','month')
            ->toArray();

        foreach($perMonth as $m => $t){
            $months[$m] = (int) $t;
        }

        $privateCount = Evento::where('private', true)->count();
        $publicCount = Evento::where('private', false)->count();

        $upcomingCount = Evento::whereBetween('date', [Carbon::now()->toDateString(), Carbon::now()->addDays(30)->toDateString()])->count();

        $byCity = Evento::select('city', DB::raw('count(*) as total'))->groupBy('city')->orderByDesc('total')->limit(6)->get();

        // events per week for current month (4 ranges)
        $startMonth = Carbon::now()->startOfMonth();
        $eventsPerWeek = [];
        for ($i = 0; $i < 4; $i++) {
            $start = (clone $startMonth)->addDays($i * 7)->startOfDay();
            $end = ($i < 3) ? (clone $start)->addDays(6)->endOfDay() : (clone $startMonth)->endOfMonth()->endOfDay();
            $eventsPerWeek[] = Evento::whereBetween('date', [$start->toDateString(), $end->toDateString()])->count();
        }

        $eventsThisMonth = $months[Carbon::now()->month] ?? 0;
        $salonsActive = Evento::distinct('city')->count();

        return response()->json([
            'eventsPerMonth' => array_values($months),
            'eventsPerWeek' => $eventsPerWeek,
            'eventsThisMonth' => (int) $eventsThisMonth,
            'salonsActive' => (int) $salonsActive,
            'privateCount' => $privateCount,
            'publicCount' => $publicCount,
            'upcomingCount' => $upcomingCount,
            'eventsByCity' => $byCity,
        ]);
    }
}
