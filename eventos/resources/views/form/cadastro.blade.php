@extends('layouts.main')

@section('title', 'Cadastrar Evento')

@section('content')

<form action="/eventos" method="POST" id="event-form" class="col-md-6 offset-md-3 py-4" enctype="multipart/form-data">
    @csrf
    <h1 class="mb-4">Cadastre um evento</h1>

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
               value="{{ old('title') }}" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Contacto do responsável</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email"
               placeholder="lartin@gmail.com"
               value="{{ old('email') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="tipo" class="form-label">Tipo de evento</label>
        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
            <option value="" disabled selected>Selecione o tipo de evento</option>
            <option value="Social" {{ old('tipo') == 'Social' ? 'selected' : '' }}>Social (Casamento / Aniversário / Formatura)</option>
            <option value="Corporativo" {{ old('tipo') == 'Corporativo' ? 'selected' : '' }}>Corporativo</option>
            <option value="Académico e Científico" {{ old('tipo') == 'Académico e Científico' ? 'selected' : '' }}>Académico e Científico</option>
            <option value="Cultural" {{ old('tipo') == 'Cultural' ? 'selected' : '' }}>Cultural</option>
        </select>
        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="date" class="form-label">Data do evento</label>
        <input type="date" class="form-control @error('date') is-invalid @enderror"
               id="date" name="date"
               min="{{ now()->toDateString() }}"
               value="{{ old('date') }}" required>
        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="horario-group mb-3">
        <div>
            <label for="hora_inicio" class="form-label">Hora de início</label>
            <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror"
                   id="hora_inicio" name="hora_inicio"
                   value="{{ old('hora_inicio') }}" required>
            @error('hora_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div>
            <label for="hora_fim" class="form-label">Hora de fim</label>
            <input type="time" class="form-control @error('hora_fim') is-invalid @enderror"
                   id="hora_fim" name="hora_fim"
                   value="{{ old('hora_fim') }}" required>
            @error('hora_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="participantes" class="form-label">Número de participantes</label>
        <input type="number" class="form-control @error('participantes') is-invalid @enderror"
               id="participantes" name="participantes"
               placeholder="Nº de participantes"
               value="{{ old('participantes') }}" required>
        @error('participantes') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="hora" class="form-label">Duração do evento (em horas)</label>
        <input type="number" class="form-control @error('hora') is-invalid @enderror"
               id="hora" name="hora"
               placeholder="Ex: 8"
               value="{{ old('hora') }}" required>
        @error('hora') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="city" class="form-label">Cidade</label>
        <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
            <option value="" disabled selected>A cidade do evento</option>
            @foreach (['Matola', 'Maputo', 'Sofala', 'Tete'] as $cidade)
                <option value="{{ $cidade }}" {{ old('city') == $cidade ? 'selected' : '' }}>{{ $cidade }}</option>
            @endforeach
        </select>
        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="salon" class="form-label">Salão</label>
        <select class="form-select @error('salon') is-invalid @enderror" id="salon" name="salon" required>
            <option value="" disabled selected>O salão ideal para o evento</option>
            @foreach (['WestPoint', 'El Shadai', 'Goodness', 'Lovely'] as $salao)
                <option value="{{ $salao }}" {{ old('salon') == $salao ? 'selected' : '' }}>{{ $salao }}</option>
            @endforeach
        </select>
        @error('salon') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="private" class="form-label">O evento é privado?</label>
        <select class="form-select" id="private" name="private">
            <option value="0" {{ old('private') == '0' ? 'selected' : '' }}>Não</option>
            <option value="1" {{ old('private') == '1' ? 'selected' : '' }}>Sim</option>
        </select>
    </div>

    <div class="form-floating mb-3">
        <textarea class="form-control @error('description') is-invalid @enderror"
                  placeholder="Descreva o evento"
                  id="description" name="description"
                  style="height: 100px">{{ old('description') }}</textarea>
        <label for="description">Descreva o evento</label>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <fieldset class="border p-3 mb-3">
        <legend class="float-none w-auto px-2 fs-5 fw-bold text-primary">Serviços</legend>
        <p class="text-muted small mb-3">Selecione os serviços a serem prestados por nós.</p>

        @php
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
                       {{ in_array($servico['value'], old('items', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="{{ $id }}">{{ $servico['label'] }}</label>
            </div>
        @endforeach
    </fieldset>

    <div class="mb-3">
        <label for="image" class="form-label">Imagem do evento (Opcional)</label>
        <input class="form-control" type="file" id="image" name="image">
    </div>

    <button type="submit" class="btn btn-primary">Cadastrar Evento</button>
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
        if (el) el.addEventListener('change', scheduleCheck);
    });

    function scheduleCheck() {
        clearTimeout(debounce);
        debounce = setTimeout(checkConflito, 400);
    }

    async function checkConflito() {
        const salon = document.getElementById('salon').value;
        const date = document.getElementById('date').value;
        const horaInicio = document.getElementById('hora_inicio').value;
        const horaFim = document.getElementById('hora_fim').value;
        const alert = document.getElementById('conflict-alert');
        const btn = document.querySelector('button[type="submit"]');

        if (!salon || !date || !horaInicio || !horaFim) {
            alert.classList.remove('show');
            btn.disabled = false;
            return;
        }

        try {
            const res = await fetch('/eventos/check-conflito', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ salon, date, hora_inicio: horaInicio, hora_fim: horaFim }),
            });

            const data = await res.json();

            if (data.conflito) {
                document.getElementById('conflict-message').innerHTML =
                    `Conflito de horário detectado. O salão ${salon} já está reservado das ${data.conflito.hora_inicio} às ${data.conflito.hora_fim}.`;
                alert.classList.add('show');
                btn.disabled = true;
            } else {
                alert.classList.remove('show');
                btn.disabled = false;
            }
        } catch (e) {
            console.error('Erro ao verificar conflito:', e);
        }
    }
})();
</script>
@endpush
