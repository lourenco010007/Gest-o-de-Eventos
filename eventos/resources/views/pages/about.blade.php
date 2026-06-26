@extends('layouts.main')

@section('title', 'Sobre Nós — Gestão de Eventos')

@section('content')

{{-- HERO --}}
<section class="page-hero about-hero">
    <div class="about-hero-inner">
        <p class="eyebrow">Quem somos</p>
        <h1>A empresa por trás dos melhores eventos de Moçambique</h1>
        <p class="hero-copy">
            Somos uma empresa moçambicana especializada no aluguer e gestão de salões para eventos. 
            Presentes em Maputo, Matola, Sofala e Tete, oferecemos espaços modernos, 
            serviços completos e uma plataforma digital para que cada detalhe do seu evento seja perfeito.
        </p>
        <div class="d-flex gap-2 flex-wrap">
            <a href="/eventos" class="btn btn-primary">Ver eventos</a>
            <a href="/contact" class="btn btn-light">Falar connosco</a>
        </div>
    </div>
</section>

{{-- MISSÃO / VISÃO / VALORES --}}
<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Nosso propósito</p>
            <h2>O que nos move</h2>
        </div>
    </div>

    <div class="mvv-grid">
        <div class="mvv-card">
            <div class="mvv-icon"><i class="fa-solid fa-bullseye"></i></div>
            <h3>Missão</h3>
            <p>Proporcionar espaços de qualidade e serviços completos para que cada evento — corporativo, social ou cultural — aconteça com excelência e sem preocupações.</p>
        </div>
        <div class="mvv-card">
            <div class="mvv-icon"><i class="fa-solid fa-eye"></i></div>
            <h3>Visão</h3>
            <p>Ser a principal referência em aluguer de salões e gestão de eventos em Moçambique, reconhecida pela qualidade, inovação e pelo impacto positivo nas comunidades onde actuamos.</p>
        </div>
        <div class="mvv-card">
            <div class="mvv-icon"><i class="fa-solid fa-star"></i></div>
            <h3>Valores</h3>
            <p>Comprometimento, transparência, inovação e respeito. Acreditamos que um evento bem organizado começa com uma empresa em quem se pode confiar.</p>
        </div>
    </div>
</section>

{{-- SALÕES / PROVÍNCIAS --}}
<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Presença nacional</p>
            <h2>Os nossos salões por província</h2>
        </div>
    </div>

    <div class="province-grid">
        <div class="province-card">
            <div class="province-badge"><i class="fa-solid fa-location-dot"></i> Maputo</div>
            <h3>WestPoint</h3>
            <p>Salão versátil localizado no coração de Maputo, ideal para eventos corporativos, sociais e académicos. Capacidade para 240 pessoas, com infraestrutura moderna e estacionamento.</p>
            <ul class="province-list">
                <li><i class="fa-solid fa-check"></i> Sala climatizada</li>
                <li><i class="fa-solid fa-check"></i> Equipamento audiovisual</li>
                <li><i class="fa-solid fa-check"></i> Estacionamento privado</li>
                <li><i class="fa-solid fa-check"></i> Wi-Fi de alta velocidade</li>
            </ul>
        </div>

        <div class="province-card">
            <div class="province-badge"><i class="fa-solid fa-location-dot"></i> Matola</div>
            <h3>El Shadai</h3>
            <p>Espaço acolhedor na Matola, pensado para casamentos, aniversários e celebrações familiares. Com capacidade para 180 convidados e ambiente cuidadosamente decorado.</p>
            <ul class="province-list">
                <li><i class="fa-solid fa-check"></i> Jardim exterior</li>
                <li><i class="fa-solid fa-check"></i> Cozinha profissional</li>
                <li><i class="fa-solid fa-check"></i> Serviço de decoração</li>
                <li><i class="fa-solid fa-check"></i> Gerador próprio</li>
            </ul>
        </div>

        <div class="province-card">
            <div class="province-badge"><i class="fa-solid fa-location-dot"></i> Sofala</div>
            <h3>Goodness</h3>
            <p>Em Sofala, o Goodness é o espaço ideal para eventos culturais, reuniões e workshops. Moderno, funcional e com capacidade para 150 participantes.</p>
            <ul class="province-list">
                <li><i class="fa-solid fa-check"></i> Palco profissional</li>
                <li><i class="fa-solid fa-check"></i> Sistema de som</li>
                <li><i class="fa-solid fa-check"></i> Camarins</li>
                <li><i class="fa-solid fa-check"></i> Área de exposição</li>
            </ul>
        </div>

        <div class="province-card">
            <div class="province-badge"><i class="fa-solid fa-location-dot"></i> Tete</div>
            <h3>Lovely</h3>
            <p>Ambiente compacto e elegante em Tete, perfeito para eventos privados, encontros executivos e reuniões de pequeno e médio porte. Capacidade para 120 pessoas.</p>
            <ul class="province-list">
                <li><i class="fa-solid fa-check"></i> Sala VIP</li>
                <li><i class="fa-solid fa-check"></i> Serviço de bar</li>
                <li><i class="fa-solid fa-check"></i> Segurança 24h</li>
                <li><i class="fa-solid fa-check"></i> Acessibilidade garantida</li>
            </ul>
        </div>
    </div>
</section>

{{-- SERVIÇOS --}}
<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">O que oferecemos</p>
            <h2>Serviços incluídos</h2>
        </div>
    </div>

    <div class="services-about-grid">
        <div class="service-about-card">
            <i class="fa-solid fa-calendar-check"></i>
            <h4>Aluguer de salões</h4>
            <p>Espaços disponíveis para meio-dia ou dia inteiro, com flexibilidade de horários e configuração personalizada.</p>
        </div>
        <div class="service-about-card">
            <i class="fa-solid fa-tv"></i>
            <h4>Audiovisual e Tecnologia</h4>
            <p>Projeção, iluminação profissional e suporte técnico durante todo o evento.</p>
        </div>
        <div class="service-about-card">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <h4>Decoração e Ambientação</h4>
            <p>Equipa criativa para transformar qualquer espaço de acordo com o tema e a identidade do seu evento.</p>
        </div>
        <div class="service-about-card">
            <i class="fa-solid fa-utensils"></i>
            <h4>Cozinha e Gastronomia</h4>
            <p>Serviço de catering com menus variados, elaborados por chefs experientes para satisfazer todos os gostos.</p>
        </div>
        <div class="service-about-card">
            <i class="fa-solid fa-martini-glass-citrus"></i>
            <h4>Bar</h4>
            <p>Bar completo com bebidas nacionais e internacionais, cocktails personalizados e staff especializado.</p>
        </div>
        <div class="service-about-card">
            <i class="fa-solid fa-headset"></i>
            <h4>Assessoria e Produção</h4>
            <p>Acompanhamento total do planeamento ao encerramento, garantindo que nada falhe no dia do evento.</p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="content-section">
    <div class="about-cta">
        <p class="eyebrow">Pronto para começar?</p>
        <h2>Reserve o seu salão hoje</h2>
        <p>Crie uma conta, escolha o salão ideal e marque o seu evento em minutos. A nossa equipa confirma a reserva e acompanha tudo até ao fim.</p>
        <div class="d-flex gap-2 flex-wrap justify-content-center mt-3">
            @auth
                <a href="/eventos/create" class="btn btn-primary btn-lg">Marcar evento</a>
            @else
                <a href="/register" class="btn btn-primary btn-lg">Criar conta grátis</a>
                <a href="/contact" class="btn btn-outline-primary btn-lg">Falar connosco</a>
            @endauth
        </div>
    </div>
</section>

@endsection