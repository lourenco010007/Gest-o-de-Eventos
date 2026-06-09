@extends('layouts.main')

@section('title', 'Pagina Inicial')

@section('content')

<div class="container">
    <h1>Bem-vindo ao Eventos!</h1>
    <p>Encontre os melhores eventos da cidade e participe de momentos inesquecíveis.</p>
    
    <div id="card-conteiner" class="row">
    @foreach($eventos as $evento)
    
        <div class="card col-md-4">
            <img src="{{ $evento->image }}" class="card-img-top" alt="{{ $evento->title }}">
            <div class="card-body">
                <h5 class="card-title">{{ $evento->title }}</h5>
                <p class="card-text">{{ $evento->description }}</p>
                <p class="card-text"><strong>Data:</strong> {{ $evento->date }}</p>
                <p class="card-text"><strong>Cidade:</strong> {{ $evento->city }}</p>
            </div>
        </div> 
    @endforeach
    </div>

@endsection