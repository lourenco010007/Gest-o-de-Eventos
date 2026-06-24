@extends('layouts.main')

@section('title', 'Detalhes do Evento')

@section('content')

<section class="content-shell">
    <div class="detail-grid">
        <article class="detail-card">
            @if($evento->image)
                <img src="{{ asset('img/eventos/' . $evento->image) }}" class="detail-cover" alt="{{ $evento->title }}">
            @endif

            <div class="detail-header">
                <div>
                    <p class="eyebrow">Evento #{{ $evento->id }}</p>
                    <h1>{{ $evento->title }}</h1>
                </div>
                <span class="status-pill status-{{ $evento->status }}">{{ str_replace('_', ' ', $evento->status) }}</span>
            </div>

            <p class="detail-description">{{ $evento->description }}</p>

            <div class="detail-meta">
                <span><i class="fa-solid fa-calendar"></i> {{ optional($evento->date)->format('d/m/Y') }}</span>
                <span><i class="fa-solid fa-clock"></i> {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($evento->hora_fim)->format('H:i') }}</span>
                <span><i class="fa-solid fa-location-dot"></i> {{ optional($evento->salao)->nome ?? $evento->salon }} - {{ $evento->city }}</span>
                <span><i class="fa-solid fa-users"></i> {{ $evento->participantes }} participantes</span>
                <span><i class="fa-solid fa-envelope"></i> {{ $evento->email }}</span>
                <span><i class="fa-solid fa-tag"></i> {{ $evento->tipo }}</span>
                <span><i class="fa-solid fa-user"></i> {{ optional($evento->user)->name ?? 'Sistema' }}</span>
            </div>

            @if($evento->status_reason)
                <div class="notice-item mt-3">
                    <strong>Observacao</strong>
                    <p class="mb-0">{{ $evento->status_reason }}</p>
                </div>
            @endif

            @if($evento->items && count($evento->items) > 0)
                <div class="services-box mt-3">
                    <legend>Servicos inclusos</legend>
                    <div class="services-grid">
                        @foreach($evento->items as $item)
                            <span class="service-option readonly">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="form-actions mt-4">
                <a href="/eventos" class="btn btn-outline-secondary">Voltar</a>
                @auth
                    @if(auth()->user()->isAdmin() || ($evento->user_id === auth()->id() && $evento->status === 'pendente'))
                        <a href="/eventos/edit/{{ $evento->id }}" class="btn btn-primary">Editar</a>
                    @endif
                @endauth
            </div>
        </article>
    </div>
</section>

@endsection
