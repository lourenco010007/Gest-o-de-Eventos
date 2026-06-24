@extends('layouts.main')

@section('title', 'Meus Eventos')

@section('content')

<section class="content-shell">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Cliente</p>
            <h1>Meus eventos</h1>
        </div>
        <a href="/eventos/create" class="btn btn-primary">Novo evento</a>
    </div>

    @if($eventos->isEmpty())
        <div class="empty-state">Ainda nao tens eventos marcados.</div>
    @else
        <div class="event-grid">
            @foreach($eventos as $evento)
                <article class="event-card">
                    <div class="event-card-top">
                        <span class="status-pill status-{{ $evento->status }}">{{ str_replace('_', ' ', $evento->status) }}</span>
                        <span class="event-date">{{ optional($evento->date)->format('d/m/Y') }}</span>
                    </div>

                    <h3>{{ $evento->title }}</h3>
                    <p>{{ optional($evento->salao)->nome ?? $evento->salon }} - {{ $evento->city }}</p>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($evento->hora_fim)->format('H:i') }}</p>

                    @if($evento->status_reason)
                        <div class="notice-item">
                            <p class="mb-0">{{ $evento->status_reason }}</p>
                        </div>
                    @endif

                    <div class="event-actions">
                        <a href="/eventos/{{ $evento->id }}" class="btn btn-sm btn-outline-primary">Ver detalhes</a>
                        @if($evento->status === 'pendente')
                            <a href="/eventos/edit/{{ $evento->id }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                        @endif
                    </div>

                    @if(in_array($evento->status, ['pendente', 'confirmado'], true))
                        <form action="/meus-eventos/{{ $evento->id }}/cancelar" method="POST" class="stack-form">
                            @csrf
                            @method('PATCH')
                            <textarea name="status_reason" class="form-control" rows="2" placeholder="Motivo do cancelamento" required></textarea>
                            <button type="submit" class="btn btn-sm btn-outline-danger">Solicitar cancelamento</button>
                        </form>

                        <form action="/meus-eventos/{{ $evento->id }}/adiar" method="POST" class="stack-form">
                            @csrf
                            @method('PATCH')
                            <div class="row g-2">
                                <div class="col-12">
                                    <input type="date" min="{{ now()->toDateString() }}" name="requested_date" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <input type="time" name="requested_hora_inicio" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <input type="time" name="requested_hora_fim" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="status_reason" class="form-control" rows="2" placeholder="Motivo do adiamento" required></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Solicitar adiamento</button>
                        </form>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</section>

@endsection
