@extends('layouts.main')

@section('title', 'Gestao de Eventos')

@section('content')

<section class="page-hero">
    <div>
        <p class="eyebrow">Reservas, salões e acompanhamento</p>
        <h1>Gestão de eventos.</h1>
        <p class="hero-copy">
            Marque eventos em nossos salões , acompanhe o estado da reserva e veja avisos importantes quando um evento for cancelado ou adiado.
        </p>
        <div class="d-flex gap-2 flex-wrap">
            @auth
                <a href="/eventos/create" class="btn btn-primary">Marcar evento</a>
                <a href="/meus-eventos" class="btn btn-light">Meus eventos</a>
            @else
                <a href="/register" class="btn btn-primary">Criar conta</a>
                <a href="/login" class="btn btn-light">Entrar</a>
            @endauth
        </div>
    </div>
</section>

@if($avisos->isNotEmpty())
    <section class="content-section">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Avisos</p>
                <h2>Eventos cancelados ou adiados</h2>
            </div>
        </div>

        <div class="notice-list">
            @foreach($avisos as $evento)
                <article class="notice-item status-{{ $evento->status }}">
                    <span class="status-pill">{{ str_replace('_', ' ', ucfirst($evento->status)) }}</span>
                    <div>
                        <strong>{{ $evento->title }}</strong>
                        <p>
                            {{ optional($evento->date)->format('d/m/Y') }}
                            @if($evento->hora_inicio)
                                as {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }}
                            @endif
                            em {{ optional($evento->salao)->nome ?? $evento->salon }}.
                        </p>
                        @if($evento->status_reason)
                            <small>{{ $evento->status_reason }}</small>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Salões</p>
    
        </div>
        <a href="/eventos" class="btn btn-outline-primary btn-sm">Ver eventos publicos</a>
    </div>

    <div class="salon-grid">
        @forelse($saloes as $salao)
            <article class="salon-card">
                <h3>{{ $salao->nome }}</h3>
                <p>{{ $salao->descricao }}</p>
                <div class="salon-meta">
                    <span>{{ $salao->cidade }}</span>
                    <span>{{ $salao->capacidade }} lugares</span>
                
                </div>
            </article>
        @empty
            <div class="empty-state">Nenhum salao cadastrado.</div>
        @endforelse
    </div>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Agenda</p>
            <h2>Proximos eventos</h2>
        </div>
    </div>

    <div class="table-responsive clean-table">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Evento</th>
                    <th>Salao</th>
                    <th>Data</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proximosEventos as $evento)
                    <tr>
                        <td>
                            <a href="/eventos/{{ $evento->id }}">{{ $evento->title }}</a>
                            <small class="d-block text-muted">{{ $evento->tipo }}</small>
                        </td>
                        <td>{{ optional($evento->salao)->nome ?? $evento->salon }}</td>
                        <td>
                            {{ optional($evento->date)->format('d/m/Y') }}
                            @if($evento->hora_inicio)
                                <small class="d-block text-muted">{{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($evento->hora_fim)->format('H:i') }}</small>
                            @endif
                        </td>
                        <td><span class="status-pill">{{ str_replace('_', ' ', $evento->status) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted">Ainda não existem eventos futuros publicados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection
