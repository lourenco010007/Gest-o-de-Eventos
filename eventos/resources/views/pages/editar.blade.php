@extends('layouts.main')

@section('title', 'Editar: ' . $evento->title)

@section('content')

<form action="/eventos/update/{{ $evento->id }}" method="POST" id="event-form" class="col-md-6 offset-md-3 py-4" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $evento->id }}">

    <h1 class="mb-4">Editar: {{ $evento->title }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="conflict-alert" id="conflict-alert">
        <div id="conflict-message"></div>
    </div>

    <div class="mb-3">
        <label for="title" class="form-label">Nome do evento</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror"
               id="title" name="title"
               placeholder="Ex: Reunião de negócios da empresa LRS"
               value="{{ old('title', $evento->title) }}" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Contacto do responsável</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email"
               placeholder="lartin@gmail.com"
               value="{{ old('email', $evento->email) }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="tipo" class="form-label">Tipo de evento</label>
        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
            <option value="" disabled>Selecione o tipo de evento</option>
            @foreach ([
                'Social' => 'Social (Casamento / Aniversário / Formatura)',
                'Corporativo' => 'Corporativo',
                'Académico e Científico' => 'Académico e Científico',
                'Cultural' => 'Cultural',
            ] as $value => $label)
                <option value="{{ $value }}" {{ old('tipo', $evento->tipo) === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="date" class="form-label">Data do evento</label>
        <input type="date" class="form-control @error('date') is-invalid @enderror"
               id="date" name="date"
               min="{{ now()->toDateString() }}"
               value="{{ old('date', \Carbon\Carbon::parse($evento->date)->format('Y-m-d')) }}" required>
        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="horario-group mb-3">
        <div>
            <label for="hora_inicio" class="form-label">Hora de inicio</label>
            <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror"
                   id="hora_inicio" name="hora_inicio"
                   value="{{ old('hora_inicio', $evento->hora_inicio) }}" required>
            @error('hora_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div>
            <label for="hora_fim" class="form-label">Hora de fim</label>
            <input type="time" class="form-control @error('hora_fim') is-invalid @enderror"
                   id="hora_fim" name="hora_fim"
                   value="{{ old('hora_fim', $evento->hora_fim) }}" required>
            @error('hora_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="participantes" class="form-label">Número de participantes</label>
        <input type="number" class="form-control @error('participantes') is-invalid @enderror"
               id="participantes" name="participantes"
               placeholder="Nº de participantes"
               value="{{ old('participantes', $evento->participantes) }}" required>
        @error('participantes') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="hora" class="form-label">Duração do evento (em horas)</label>
        <input type="number" class="form-control @error('hora') is-invalid @enderror"
               id="hora" name="hora"
               placeholder="Ex: 8"
               value="{{ old('hora', $evento->hora) }}" required>
        @error('hora') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="city" class="form-label">Cidade</label>
        <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
            <option value="" disabled>A cidade do evento</option>
            @foreach (['Matola', 'Maputo', 'Sofala', 'Tete'] as $cidade)
                <option value="{{ $cidade }}" {{ old('city', $evento->city) === $cidade ? 'selected' : '' }}>
                    {{ $cidade }}
                </option>
            @endforeach
        </select>
        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="salon" class="form-label">Salão</label>
        <select class="form-select @error('salon') is-invalid @enderror" id="salon" name="salon" required>
            <option value="" disabled>O salão ideal para o evento</option>
            @foreach (['WestPoint', 'El Shadai', 'Goodness', 'Lovely'] as $salao)
                <option value="{{ $salao }}" {{ old('salon', $evento->salon) === $salao ? 'selected' : '' }}>
                    {{ $salao }}
                </option>
            @endforeach
        </select>
        @error('salon') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="private" class="form-label">O evento é privado?</label>
        <select class="form-select" id="private" name="private">
            <option value="0" {{ old('private', $evento->private) == '0' ? 'selected' : '' }}>Não</option>
            <option value="1" {{ old('private', $evento->private) == '1' ? 'selected' : '' }}>Sim</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descreva o evento</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description"
                  rows="4"
                  placeholder="Descreva o evento...">{{ old('description', $evento->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <fieldset class="border p-3 mb-3">
        <legend class="float-none w-auto px-2 fs-5 fw-bold text-primary">Serviços</legend>
        <p class="text-muted small mb-3">Selecione os serviços a serem prestados por nós.</p>

        @php
            $savedItems = old('items', is_array($evento->items)
                ? $evento->items
                : json_decode($evento->items ?? '[]', true) ?? []
            );
            $servicos = [
                'audiovisual' => ['value' => 'Audiovisual e Tecnologia', 'label' => 'Audiovisual e Tecnologia (Projeção, iluminação, suporte técnico)'],
                'decoracao'   => ['value' => 'Decoração e Ambientação',  'label' => 'Decoração e Ambientação'],
                'bar'         => ['value' => 'Bar',                      'label' => 'Bar'],
                'cozinha'     => ['value' => 'Cozinha e Gastronomia',    'label' => 'Cozinha e Gastronomia'],
                'assessoria'  => ['value' => 'Assessoria e Produção',    'label' => 'Assessoria e Produção'],
            ];
        @endphp

        @foreach ($servicos as $id => $servico)
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox"
                       value="{{ $servico['value'] }}"
                       id="{{ $id }}" name="items[]"
                       {{ in_array($servico['value'], $savedItems) ? 'checked' : '' }}>
                <label class="form-check-label" for="{{ $id }}">{{ $servico['label'] }}</label>
            </div>
        @endforeach
    </fieldset>

    <div class="mb-3">
        <label class="form-label">Imagem do evento</label>
        @if ($evento->image)
            <div class="mb-2">
                <img src="{{ asset('img/eventos/' . $evento->image) }}"
                     alt="Imagem actual do evento"
                     class="img-thumbnail"
                     style="max-height: 160px;">
                <p class="text-muted small mt-1">Imagem actual. Carregue uma nova para substituir.</p>
            </div>
        @endif
        <input class="form-control" type="file" id="image" name="image" accept="image/*">
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary" id="btn-submit">Guardar alterações</button>
        <a href="/eventos" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

@endsection

@push('scripts')
<script>
(function () {
    const fields = ['salon', 'date', 'hora_inicio', 'hora_fim'];
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let debounce;

    fields.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', () => {
            clearTimeout(debounce);
            debounce = setTimeout(checkConflito, 350);
        });
    });

    async function checkConflito() {
        const salaoId = document.getElementById('salon').value;
        const date = document.getElementById('date').value;
        const horaInicio = document.getElementById('hora_inicio').value;
        const horaFim = document.getElementById('hora_fim').value;
        const alert = document.getElementById('conflict-alert');
        const btn = document.getElementById('btn-submit');

        if (!salaoId || !date || !horaInicio || !horaFim) return;

        const res = await fetch('/eventos/check-conflito', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
            body: JSON.stringify({ salao_id: salaoId, date, hora_inicio: horaInicio, hora_fim: horaFim, evento_id: {{ $evento->id }} }),
        });
        const data = await res.json();

        if (data.conflito) {
            document.getElementById('conflict-message').innerHTML = `Horario indisponivel. ${data.conflito.title} ja ocupa este periodo.`;
            alert.classList.add('show');
            btn.disabled = true;
        } else {
            alert.classList.remove('show');
            btn.disabled = false;
        }
    }
})();
</script>
@endpush
