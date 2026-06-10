@extends('layouts.main')

@section('title', 'Pagina Inicial')

@section('content')

<div class="container">
	<h1 class="mb-4">Dashboard</h1>
	<div class="row">
		<div class="col-lg-8">
			<div class="card mb-4">
				<div class="card-body">
					<h5 class="card-title">Eventos por mês</h5>
					<canvas id="eventsChart" height="120"></canvas>
				</div>
			</div>
			<div class="card mb-4">
				<div class="card-body">
					<h5 class="card-title">Taxa de ocupação (%)</h5>
					<canvas id="occupancyChart" height="120"></canvas>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="card mb-4 text-center">
				<div class="card-body">
					<h5 class="card-title">Próximos eventos</h5>
					<canvas id="upcomingChart" height="160"></canvas>
				</div>
			</div>
			<div class="card p-3">
				<h6>Resumo rápido</h6>
				<ul class="list-unstyled">
					<li>Total eventos este mês: <strong id="totalEventsMonth">0</strong></li>
					<li>Salões ativos: <strong id="salonsActive">0</strong></li>
					<li>Reservas pendentes: <strong id="pendingReservations">0</strong></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/dashboard.js"></script>

@endsection