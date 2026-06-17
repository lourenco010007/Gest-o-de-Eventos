<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Evento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\search;

class EventoController extends Controller
{

    public function index(){
        $eventos = Evento::all();
        return view('pages.eventos', compact('eventos'));
    }

    public function create(){
        return view('form.cadastro');   
    }
    
    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'city' => 'required|string|max:255',
            'salon' => 'required|string|max:255',
        ]);

        try {
            $evento = new Evento;
            $evento->title = $request->title;
            $evento->description = $request->description;
            $evento->date = $request->date;
            $evento->city = $request->city;
            $evento->private = $request->private;
            $evento->salon = $request->salon;
            $evento->image=$request->image;
            $evento->items=$request->items;
            $evento->participantes=$request->participantes;
            $evento->email=$request->email;
            $evento->hora=$request->hora;
            $evento->tipo=$request->tipo;

            //image
            if($request->hasFile('image') && $request->file('image')->isValid()){
                $requestImage= $request->image;
                
                $extension= $requestImage->extension(); 

                $imageName =  md5($requestImage->getClientOriginalName() .strtotime("now")) . "." . $extension;

                $request-> image->move(public_path('img/events'), $imageName);
                
                $evento->image=$imageName;

            
            }   
            $evento->save();

            return redirect('/eventos')->with('success', 'Evento criado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Algo deu errado ao cadastrar o evento. Tente novamente.');
        }
        
    }
      public function show($id)
    {
        $evento = Evento::findOrFail($id);
        return view('pages.mostrar', ['evento' => $evento]);
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


