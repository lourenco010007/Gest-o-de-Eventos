@extends('layouts.main')

@section('title', 'Detalhes')

@section('content')
    <div class="card" style="width: 18rem;">
  <img src="..." class="card-img-top" alt="{{ $evento->title }}">
  <div class="card-body">
    <h5 class="card-title">{{ $evento->title }}</h5>
    <p class="card-text"><strong>Descrição: </strong> {{ $evento->description }}</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Data:</strong>{{ $evento->date }}</li>
    <li class="list-group-item"><strong>Cidade:</strong> {{ $evento-> city }}</li>
    <li class="list-group-item"><strong>Salão: </strong>{{ $evento->salon }}</li>
    <li class="list-group-item"><strong>Estado:</strong></li>
  </ul>
 <p class="card-text"><strong>Servicos inclusos:</strong></p>
  
  @foreach($evento->items as $items)
    <ul class="list-group list-group-flush">
    <li class="list-group-item">{{ $items }}</li> 
    </ul>
  @endforeach
  <div class="card-body">
    <a href="#" class="card-link"><i class="fa-solid fa-pencil"></i></a>
    <a href="#" class="card-link"><i class="fa-solid fa-trash"></i></a>
  </div>
</div>
@endsection