@extends('layouts.main')

@section('title', 'Eventos')

@section('content')

<section class="content-shell">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Agenda publica</p>
            <h1>Eventos disponiveis</h1>
        </div>
        @auth
            <a href="/eventos/create" class="btn btn-primary">Marcar evento</a>
        @endauth
    </div>

    @if($eventos->isEmpty())
        <div class="empty-state">Nenhum evento encontrado.</div>
    @else
        <div class="event-grid">
            @foreach($eventos as $evento)
                @php
                    $isAdmin  = auth()->check() && auth()->user()->isAdmin();
                    $isOwner  = auth()->check() && $evento->user_id === auth()->id();
                    $canSeeDetails = $isAdmin || $isOwner;
                @endphp

                <article class="event-card">
                    <div class="event-card-top">
                        @if($canSeeDetails)
                            <span class="status-pill status-{{ $evento->status }}">{{ str_replace('_', ' ', $evento->status) }}</span>
                        @endif
                        <span class="event-date">{{ optional($evento->date)->format('d/m/Y') }}</span>
                    </div>

                    @if($evento->image)
                        <img src="{{ asset('img/eventos/' . $evento->image) }}" class="event-cover" alt="{{ $evento->title }}">
                    @endif

                    <h3>{{ $evento->title }}</h3>

                    {{-- Descrição e detalhes extras só para admin/dono --}}
                    @if($canSeeDetails)
                        <p>{{ $evento->description }}</p>
                    @endif

                    <div class="event-meta">
                        <span><i class="fa-solid fa-location-dot"></i> {{ optional($evento->salao)->nome ?? $evento->salon }}</span>
                        <span><i class="fa-solid fa-city"></i> {{ $evento->city }}</span>
                        @if($evento->hora_inicio && $evento->hora_fim)
                            <span><i class="fa-solid fa-clock"></i> {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($evento->hora_fim)->format('H:i') }}</span>
                        @endif
                        <span><i class="fa-solid fa-tag"></i> {{ $evento->tipo }}</span>

                        {{-- Participantes e contacto só para admin/dono --}}
                        @if($canSeeDetails)
                            <span><i class="fa-solid fa-users"></i> {{ $evento->participantes }} participantes</span>
                            <span><i class="fa-solid fa-envelope"></i> {{ $evento->email }}</span>
                        @endif
                    </div>

                    <div class="event-actions">
                        {{-- Botão "Ver detalhes" apenas para admin ou dono --}}
                        @if($canSeeDetails)
                            <a href="/eventos/{{ $evento->id }}" class="btn btn-sm btn-outline-primary">Ver</a>
                        @endif

                        @if($isAdmin || ($isOwner && $evento->status === 'pendente'))
                            <a href="/eventos/edit/{{ $evento->id }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

@endsection