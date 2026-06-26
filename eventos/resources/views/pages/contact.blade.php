@extends('layouts.main')

@section('title', 'Contacto — Gestão de Eventos')

@section('content')

<section class="page-hero">
    <p class="eyebrow">Estamos aqui para ajudar</p>
    <h1>Fale connosco</h1>
    <p class="hero-copy">
        Tem dúvidas sobre um salão, quer pedir uma proposta ou simplesmente saber mais sobre os nossos serviços? 
        Entre em contacto pelos canais abaixo — respondemos rapidamente.
    </p>
</section>

<section class="content-section">
    <div class="contact-layout">

        {{-- CANAIS DE CONTACTO --}}
        <div class="contact-channels">
            <div class="section-heading mb-3">
                <div>
                    <p class="eyebrow">Canais directos</p>
                    <h2>Como nos encontrar</h2>
                </div>
            </div>

            <div class="channel-list">

                <a href="https://wa.me/258877575959" target="_blank" rel="noopener" class="channel-card channel-whatsapp">
                    <div class="channel-icon"><i class="fa-brands fa-whatsapp"></i></div>
                    <div>
                        <strong>WhatsApp</strong>
                        <span>+258 87 757 5959</span>
                        <small>Resposta em menos de 1 hora</small>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square channel-arrow"></i>
                </a>

                <a href="mailto:lourencoderogerios@gmail.com" class="channel-card channel-email">
                    <div class="channel-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <strong>E-mail</strong>
                        <span>lourencoderogerios@gmail.com</span>
                        <small>Resposta em até 24 horas</small>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square channel-arrow"></i>
                </a>

                <a href="linkedin.com/in/louren%C3%A7o-de-rog%C3%A9rio-s-761a70384/   " target="_blank" rel="noopener" class="channel-card channel-linkedin">
                    <div class="channel-icon"><i class="fa-brands fa-linkedin-in"></i></div>
                    <div>
                        <strong>LinkedIn</strong>
                        <span>Lourenço Souto</span>
                        <small>Parcerias e oportunidades</small>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square channel-arrow"></i>
                </a>

                

                <a href="tel:+258877575959" class="channel-card channel-phone">
                    <div class="channel-icon"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <strong>Telefone</strong>
                        <span>+258 87 757 5959</span>
                        <small>Seg – Sex, 08h00 – 17h00</small>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square channel-arrow"></i>
                </a>

            </div>
        </div>

        {{-- LOCALIZAÇÕES --}}
        <div class="contact-locations">
            <div class="section-heading mb-3">
                <div>
                    <p class="eyebrow">Onde estamos</p>
                    <h2>Salões e escritórios</h2>
                </div>
            </div>

            <div class="location-list">
                <div class="location-item">
                    <div class="location-dot" style="background:#12345b"></div>
                    <div>
                        <strong>Maputo — WestPoint</strong>
                        <p>Av. 25 de Setembro, Maputo</p>
                        <small>Sede principal · 240 lugares</small>
                    </div>
                </div>
                <div class="location-item">
                    <div class="location-dot" style="background:#1f4d7a"></div>
                    <div>
                        <strong>Matola — El Shadai</strong>
                        <p>Bairro Acordos de Lusaka, Matola</p>
                        <small>180 lugares</small>
                    </div>
                </div>
                <div class="location-item">
                    <div class="location-dot" style="background:#f59e0b"></div>
                    <div>
                        <strong>Sofala — Goodness</strong>
                        <p>Av. das FPLM, Beira</p>
                        <small>150 lugares</small>
                    </div>
                </div>
                <div class="location-item">
                    <div class="location-dot" style="background:#027a48"></div>
                    <div>
                        <strong>Tete — Lovely</strong>
                        <p>Av. Eduardo Mondlane, Tete</p>
                        <small>120 lugares</small>
                    </div>
                </div>
            </div>

            <div class="contact-hours mt-4">
                <p class="eyebrow mb-2">Horário de funcionamento</p>
                <div class="hours-grid">
                    <span>Segunda – Sexta</span><span>08h00 – 18h00</span>
                    <span>Sábado</span><span>08h00 – 14h00</span>
                    <span>Domingo</span><span>Fechado</span>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection