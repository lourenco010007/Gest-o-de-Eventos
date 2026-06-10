document.addEventListener('DOMContentLoaded', function(){
fetch('/dashboard/data')
    .then(res => res.json())
    .then(data => {
// Eventos por mês
    const eventsCanvas = document.getElementById('eventsChart');
    if (eventsCanvas && data.eventsPerMonth) {
        const ctxEvents = eventsCanvas.getContext('2d');
        new Chart(ctxEvents, {
        type: 'bar',
        data: {
            labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            datasets: [{
            label: 'Eventos',
            backgroundColor: '#1e3c72',
            borderColor: '#1e3c72',
            data: data.eventsPerMonth
            }]
        },
        options: { responsive:true, maintainAspectRatio:false }
        });
    }

// Eventos por semana (mês atual)
    const occCanvas = document.getElementById('occupancyChart');
    if (occCanvas && data.eventsPerWeek) {
        const ctxOcc = occCanvas.getContext('2d');
        new Chart(ctxOcc, {
        type: 'line',
        data: {
            labels: ['Semana 1','Semana 2','Semana 3','Semana 4'],
            datasets:[{ label:'Eventos (semanais)', data:data.eventsPerWeek, borderColor:'#ffc107', backgroundColor:'rgba(255,193,7,0.15)', fill:true }]
        },
        options:{ responsive:true, maintainAspectRatio:false }
        });
    }

      // Privado vs Público (doughnut)
    const upcomingCanvas = document.getElementById('upcomingChart');
    if (upcomingCanvas && (typeof data.privateCount !== 'undefined')) {
        const ctxUpcoming = upcomingCanvas.getContext('2d');
        new Chart(ctxUpcoming, {
        type:'doughnut',
        data:{
            labels:['Privados','Públicos'],
            datasets:[{ data:[data.privateCount, data.publicCount], backgroundColor:['#1e3c72','#e9ecef'] }]
        },
        options:{ responsive:true, maintainAspectRatio:false, cutout:'65%' }
        });
    }

// Preencher resumo rápido
    const totalEl = document.getElementById('totalEventsMonth');
    if (totalEl && typeof data.eventsThisMonth !== 'undefined') {
        totalEl.innerText = data.eventsThisMonth;
    }

    const salonsEl = document.getElementById('salonsActive');
    if (salonsEl && typeof data.salonsActive !== 'undefined') {
        salonsEl.innerText = data.salonsActive;
    }

    const pendingEl = document.getElementById('pendingReservations');
    if (pendingEl && typeof data.upcomingCount !== 'undefined') {
        pendingEl.innerText = data.upcomingCount;
    }

    }).catch(err => console.error('Erro ao carregar dados do dashboard', err));
});
