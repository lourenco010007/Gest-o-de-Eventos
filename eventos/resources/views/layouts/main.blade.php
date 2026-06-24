<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Gestao de Eventos</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                                <i class="fa-solid fa-house"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('eventos') ? 'active' : '' }}" href="/eventos">
                                <i class="fa-solid fa-calendar-days"></i> Eventos
                            </a>
                        </li>

                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('eventos/create') ? 'active' : '' }}" href="/eventos/create">
                                    <i class="fa-solid fa-plus"></i> Marcar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('meus-eventos') ? 'active' : '' }}" href="/meus-eventos">
                                    <i class="fa-solid fa-list-check"></i> Meus Eventos
                                </a>
                            </li>
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="/admin">
                                        <i class="fa-solid fa-screwdriver-wrench"></i> Admin
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link" style="color:#fff !important;">
                                        <i class="fa-solid fa-right-from-bracket"></i> Sair
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="/about">
                                    <i class="fa-solid fa-circle-info"></i> Sobre Nos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="/contact">
                                    <i class="fa-solid fa-envelope"></i> Contacto
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="/register">
                                    <i class="fa-solid fa-user-plus"></i> Cadastrar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-success ms-2 px-3 {{ request()->is('login') ? 'active' : '' }}" href="/login">
                                    <i class="fa-solid fa-right-to-bracket"></i> Entrar
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="container-fluid">
            <div class="row">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @if(session('msg'))
                    <div class="alert alert-info alert-dismissible fade show w-100" role="alert">
                        {{ session('msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Gestao de Eventos. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
