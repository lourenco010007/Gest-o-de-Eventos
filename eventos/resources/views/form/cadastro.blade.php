@extends('layouts.main')

@section('title', 'Cadastrar')

@section('content')


<form action="/eventos" method="POST" id="event-create-container" class="col-md-6 offset-md-3" enctype="multipart/form-data">
    @csrf
    <h1>Cadastre um evento</h1>
  <div class="mb-3">
    <label for="title" class="form-label">Nome do evento</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="Ex: Reunião de negócios da empresa LRS" required> 
  </div>
  <div class="mb-3">
    <label for="title" class="form-label">Contacto do responsavel</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="lartin@gmail.com" required> 
  </div>
   <div class="mb-3">
    <label for="salon" class="form-label">Tipo de evento</label>
    <select class="form-select"  id="tipo" name="tipo" required>
      <option>Selecione o tipo de evento</option>
        <option value="Social">Social(Casamento/Aniversario/Formatura)</option>
        <option value="Corporativo">Corporativo</option>
        <option value="Acadêmico e Científico">Acadêmico e Científico</option>
        <option value="Cultural">Cultural</option>
    </select>
  </div>
   <div class="mb-3">
    <label for="date" class="form-label">Data do evento</label>
    <input type="date" class="form-control" id="date" name="date" required>
  </div>
  <div class="mb-3">
    <label for="title" class="form-label">Numero de participantes</label>
    <input type="number" class="form-control" id="participantes" name="participantes" placeholder="Nº de participantes" required> 
  </div>
  <div class="mb-3">
    <label for="title" class="form-label">Duração do evento(em Horas)</label>
    <input type="number" class="form-control" id="hora" name="hora" placeholder="10" required>  
  </div>
  <div class="mb-3">
    <label for="city" class="form-label">Cidade</label>
   <select class="form-select"  id="city" name="city" required>
        <option value="">A cidade do evento</option>
        <option value="Matola" >Matola</option>
        <option value="Maputo">Maputo</option>
        <option value="Sofala">Sofala</option>
        <option value="Tete">Tete</option>
    </select>
  </div>
  <div class="mb-3">
    <label for="salon" class="form-label">Salão</label>
    <select class="form-select" id="salon" name="salon" required>
      <option value="">O salao ideal para o evento</option>
        <option value="WestPoint">WestPoint</option>
        <option value="El Shadai">El Shadai</option>
        <option value="Goodness">Goodness</option>
        <option value="Lovely">Lovely</option>
    </select>
  </div>
    <div class="mb-3">
    <label for="private" class="form-label">O evento é privado?</label>
     <select class="form-select" aria-label="Default select example" id="private" name="private">
        <option value="0">Não</option>
        <option value="1">Sim</option>
     </select>
  </div>
  
  <div class="form-floating">
  <textarea class="form-control" placeholder="Leave a comment here" id="description" name="description"></textarea>
  <label for="description" class="form-label">Descreva o evento</label>
</div> <br>
  <fieldset class="border p-3 mb-3">
    
    <legend class="float-none w-auto px-2 fs-5 fw-bold text-primary">
      Serviços
    </legend>
    
    <p class="text-muted small mb-3">Selecione os serviços a serem prestados por nós. </p>

    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" value="Audiovisual e Tecnologia" id="audiovisual" name="items[]">
      <label class="form-check-label" for="audiovisual">
        Audiovisual e Tecnologia (Projecao, iluminacao, suporte tecnico)
      </label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" value="Decoração e Ambientação" id="decoracao" name="items[]">
      <label class="form-check-label" for="decoracao">
          Decoração e Ambientação
      </label>
    </div>

    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" value="Bar" id="bar" name="items[]">
      <label class="form-check-label" for="bar">
        Bar 
      </label>
    </div>

    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" value="Cozinha e Gastronomia" id="cozinha" name="items[]">
      <label class="form-check-label" for="cozinha">
        Cozinha e Gastronomia
      </label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" value="Assessoria e Produção" id="assessoria" name="items[]">
      <label class="form-check-label" for="assessoria">
        Assessoria e Produção
      </label>
    </div>

  </fieldset>
  
  

  
<div class="mb-3">
    <label for="image" class="form-label">Imagem do evento (Opcional)</label>
    <input class="form-control" type="file" id="image" name="image" >
</div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection