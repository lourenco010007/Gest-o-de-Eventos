@extends('layouts.main')

@section('title', 'Admin')

@section('content')

<section class="content-shell">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Admin</p>
            <h1>Painel de controlo total</h1>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card"><span>Total eventos</span><strong>{{ $totalEventos }}</strong></div>
        <div class="stat-card"><span>Pendentes</span><strong>{{ $eventosPendentes }}</strong></div>
        <div class="stat-card"><span>Cancelados</span><strong>{{ $eventosCancelados }}</strong></div>
        <div class="stat-card"><span>Utilizadores</span><strong>{{ $usuariosTotal }}</strong></div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-layout">
        <section class="admin-panel">
            <h2>Criar utilizador</h2>
            <form action="/admin/users" method="POST" class="stack-form">
                @csrf
                <input class="form-control" name="name" placeholder="Nome" value="{{ old('name') }}" required>
                <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <input class="form-control" type="password" name="password" placeholder="Password inicial" required>
                <label class="service-option">
                    <input type="checkbox" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                    <span>Administrador</span>
                </label>
                <button class="btn btn-primary" type="submit">Criar utilizador</button>
            </form>
        </section>

        <section class="admin-panel">
            <h2>Eventos</h2>
            <div class="table-responsive clean-table">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Cliente</th>
                            <th>Salao</th>
                            <th>Estado</th>
                            <th>Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos as $evento)
                            <tr>
                                <td>{{ $evento->title }}<small class="d-block text-muted">{{ optional($evento->date)->format('d/m/Y') }}</small></td>
                                <td>{{ optional($evento->user)->name ?? 'Sem utilizador' }}</td>
                                <td>{{ optional($evento->salao)->nome ?? $evento->salon }}</td>
                                <td><span class="status-pill status-{{ $evento->status }}">{{ str_replace('_', ' ', $evento->status) }}</span></td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <form action="/admin/eventos/{{ $evento->id }}/status" method="POST" class="d-flex gap-2 flex-wrap">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm">
                                                @foreach (['pendente' => 'Pendente', 'confirmado' => 'Confirmado', 'adiado' => 'Adiado', 'cancelado' => 'Cancelado'] as $value => $label)
                                                    <option value="{{ $value }}" {{ $evento->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="status_reason" class="form-control form-control-sm" placeholder="Observacao">
                                            <button class="btn btn-sm btn-primary" type="submit">Aplicar</button>
                                        </form>
                                        <form action="/eventos/{{ $evento->id }}" method="POST" onsubmit="return confirm('Remover este evento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Apagar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-panel">
            <h2>Usuarios</h2>
            <div class="table-responsive clean-table">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Eventos</th>
                            <th>Perfil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->eventos_count }}</td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="status-pill status-confirmado">Admin</span>
                                    @else
                                        <span class="status-pill">Cliente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-panel">
            <h2>Saloes</h2>
            <form action="/admin/saloes" method="POST" class="stack-form mb-4">
                @csrf
                <div class="row g-2">
                    <div class="col-md-4"><input class="form-control" name="nome" placeholder="Nome" required></div>
                    <div class="col-md-3"><input class="form-control" name="cidade" placeholder="Cidade" required></div>
                    <div class="col-md-2"><input class="form-control" type="number" name="capacidade" placeholder="Capacidade" min="1" required></div>
                    <div class="col-md-3"><input class="form-control" name="descricao" placeholder="Descricao"></div>
                </div>
                <button class="btn btn-primary btn-sm" type="submit">Adicionar salao</button>
            </form>

            <div class="event-grid">
                @foreach($saloes as $salao)
                    <article class="event-card">
                        <div class="d-flex justify-content-between gap-2 align-items-start">
                            <div>
                                <h3>{{ $salao->nome }}</h3>
                                <p class="text-muted mb-1">{{ $salao->cidade }} | {{ $salao->capacidade }} lugares</p>
                                <span class="status-pill">{{ $salao->ativo ? 'Activo' : 'Inactivo' }}</span>
                            </div>
                            <span class="status-pill">{{ $salao->eventos_count }} eventos</span>
                        </div>

                        <form action="/admin/saloes/{{ $salao->id }}" method="POST" class="stack-form">
                            @csrf
                            @method('PUT')
                            <input class="form-control form-control-sm" name="nome" value="{{ $salao->nome }}" required>
                            <input class="form-control form-control-sm" name="cidade" value="{{ $salao->cidade }}" required>
                            <input class="form-control form-control-sm" type="number" min="1" name="capacidade" value="{{ $salao->capacidade }}" required>
                            <input class="form-control form-control-sm" name="descricao" value="{{ $salao->descricao }}">
                            <label class="service-option">
                                <input type="checkbox" name="ativo" value="1" {{ $salao->ativo ? 'checked' : '' }}>
                                <span>Activo</span>
                            </label>
                            <div class="event-actions">
                                <button class="btn btn-sm btn-primary" type="submit">Salvar</button>
                            </div>
                        </form>

                        <form action="/admin/saloes/{{ $salao->id }}" method="POST" onsubmit="return confirm('Remover este salao?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Apagar</button>
                        </form>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</section>

@endsection
