@extends('layouts.main')

@section('title', 'Sobre Nos')

@section('content')

<section class="content-shell">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Quem somos</p>
            <h1>Sobre Nos</h1>
        </div>
    </div>

    <div class="detail-card">
        <p class="detail-description">
            Somos uma equipa focada em gestão de eventos para clientes e empresas que precisam de organização, controlo e acompanhamento simples de reservas.
        </p>

        <div class="event-meta">
            <span>Planeamento e marcação de eventos</span>
            <span>Gestão de salões</span>
            <span>Controlo de cancelamentos e adiamentos</span>
            <span>Acompanhamento por cliente e por admin</span>
        </div>

        <div class="services-box mt-4">
            <legend>O que fazemos</legend>
            <div class="services-grid">
                <div class="service-option">Cadastro e reserva de eventos</div>
                <div class="service-option">Validação de datas e horários</div>
                <div class="service-option">Gestão de salões disponíveis</div>
                <div class="service-option">Solicitação de adiamento e cancelamento</div>
                <div class="service-option">Painel administrativo com controlo total</div>
                <div class="service-option">Visualização dos eventos por cliente</div>
            </div>
        </div>

        <div class="notice-item mt-4">
            <div>
                <strong>Visão do serviço</strong>
                <p class="mb-0">
                    Queremos que o cliente consiga marcar com segurança e que a empresa acompanhe tudo num só lugar, sem ruído e sem conflitos de agenda.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
