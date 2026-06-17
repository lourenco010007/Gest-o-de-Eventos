<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

<!--Bootstrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

<!--Css Global-->
<link rel="stylesheet" href="/css/index.css">

<!--Fontes-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<!--Icones-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <header>
       <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Gestão de Eventos</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="/">Dashboard</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="/eventos">Eventos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="/eventos/create">Cadastrar Evento</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="/about">Sobre Nós</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="/contact">Contacto</a>
    </li>
  </ul>
</div>
  </div>
</nav>
    </header>
    <main>
      <div class="conteiner-fluid">
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
<!--Bootstrap-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>  
</body>
</html>