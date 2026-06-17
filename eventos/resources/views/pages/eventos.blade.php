@extends('layouts.main')

@section('title', 'Eventos')

@section('content')

<div class="container py-5">
    <h1 class="mb-3">Sistema de Gestão Eventos!</h1>
    <p class="mb-4">Verifique a agenda dos eventos em nossos salões.</p>

    @if(isset($eventos) && count($eventos) > 0)
        <div id="card-container" class="row g-2">
            @foreach($eventos as $evento)
                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                    <div class="card h-100 shadow-sm overflow-hidden">
                        @if(isset($evento->image))
                            <img src="/img/events/{{ $evento->image }}" class="card-img-top" alt="{{ $evento->title }}">
                        @endif
                        <div class="card-body d-flex flex-column gap-3">
                            <div>
                                <h5 class="card-title mb-2">{{ $evento->title }}</h5>
                                <p class="card-text text-muted small mb-0">{{ $evento->description }}</p>
                            </div>
                            <div class="mt-auto">
                                <p class="card-text mb-1"><strong>Data:</strong> {{ $evento->date }}</p>
                                <p class="card-text mb-1"><strong>Cidade:</strong> {{ $evento->city }}</p>
                                <p class="card-text mb-0"><strong>Salão:</strong> {{ $evento->salon }}</p>
                                <a href="/eventos/{{ $evento->id }}" class="btn btn-secondary mt-2">Saber mais</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else   
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Nenhum evento encontrado!</strong> Não existem eventos agendados de momento.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
@endsection

