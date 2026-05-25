@extends('admin.layoutbase')

@section('conteudo')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Estilos adaptados para conversar com o tema escuro geral */
h1 { font-size: 26px; }
h2 { font-size: 16px; margin-bottom: 15px; color: #fff; }

.muted { color: #9ca3af; margin-bottom: 20px; }

.card-relatorio {
  background: #111827; /* Fundo escuro fixo igual ao layoutbase */
  border: 1px solid #1f2937;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 20px;
  color: #e5e7eb;
}

.grid {
  display: grid;
  gap: 20px;
}

@media(min-width: 900px){
  .grid-4 { grid-template-columns: repeat(4, 1fr); }
  .grid-3 { grid-template-columns: 2fr 1fr; }
}

.metric {
  font-size: 24px;
  font-weight: bold;
  color: #fff;
  margin-top: 5px;
}

.btn-filtrar {
  padding: 8px 16px;
  background: #6366f1; /* Roxo para combinar com a identidade visual */
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

input, select {
  padding: 8px 12px;
  border: 1px solid #1f2937;
  border-radius: 6px;
  background: #1f2937;
  color: #fff;
  margin-right: 10px;
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.table th, .table td {
  padding: 12px 10px;
  border-bottom: 1px solid #1f2937;
  text-align: left;
}

.table th {
  color: #9ca3af;
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: bold;
}

.ok { background: rgba(99, 102, 241, 0.2); color: #a5b4fc; }
.warn { background: rgba(245, 158, 11, 0.2); color: #fcd34d; }
.danger { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
</style>

<h1>Relatórios</h1>
<p class="muted">Análise de consumo e dispositivos</p>

<div class="card-relatorio">
  <input type="text" placeholder="ID do bebedouro">
  <select>
    <option>Todas localidades</option>
    <option>Sede</option>
    <option>Filial</option>
  </select>
  <button class="btn-filtrar">Aplicar filtros</button>
</div>

<div class="grid grid-4">
  <div class="card-relatorio"><p class="muted">Total consumido</p><div class="metric">{{ $metricas['total_consumido'] }}</div></div>
  <div class="card-relatorio"><p class="muted">Gastos estimados</p><div class="metric">{{ $metricas['gastos'] }}</div></div>
  <div class="card-relatorio"><p class="muted">Acionamentos</p><div class="metric">{{ $metricas['acionamentos'] }}</div></div>
  <div class="card-relatorio"><p class="muted">Tempo médio</p><div class="metric">{{ $metricas['tempo_medio'] }}</div></div>
</div>

<div class="grid grid-3">
  <div class="card-relatorio">
    <h2>Consumo mensal</h2>
    <div style="position: relative; height:220px;">
      <canvas id="lineChart"></canvas>
    </div>
  </div>

  <div class="card-relatorio">
    <h2>Status dos filtros</h2>
    <div style="position: relative; height:220px; display: flex; justify-content: center;">
      <canvas id="pieChart"></canvas>
    </div>
  </div>
</div>

<div class="card-relatorio">
  <h2>Litros por bebedouro</h2>
  <div style="position: relative; height:250px;">
    <canvas id="barChart"></canvas>
  </div>
</div>

<div class="card-relatorio">
  <h2>Detalhes por dispositivo</h2>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Local</th>
        <th>Litros</th>
        <th>Acion.</th>
        <th>Tempo</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($dispositivos as $disp)
        <tr>
          <td><strong>{{ $disp['id'] }}</strong></td>
          <td>{{ $disp['local'] }}</td>
          <td>{{ $disp['litros'] }} L</td>
          <td>{{ $disp['acionamentos'] }}</td>
          <td>{{ $disp['tempo'] }}</td>
          <td>
            <span class="badge {{ $disp['status'] === 'OK' ? 'ok' : 'warn' }}">
              {{ $disp['status'] }}
            </span>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<script>
// INTEGRAÇÃO DOS DADOS DO BANCO/CONTROLLER PARA O JAVASCRIPT DO CHART.JS
const trendData = @json($graficos['linhas']['valores']);
const trendLabels = @json($graficos['linhas']['meses']);

new Chart(document.getElementById("lineChart"), {
  type: "line",
  data: {
    labels: trendLabels,
    datasets: [{
      label: "Consumo (L)",
      data: trendData,
      borderColor: "#6366f1",
      tension: 0.2,
      fill: false
    }]
  },
  options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById("pieChart"), {
  type: "pie",
  data: {
    labels: ["Ótimo", "Regular", "Ruim"],
    datasets: [{
      data: @json($graficos['pizza']['dados']),
      backgroundColor: ["#6366f1", "#f59e0b", "#ef4444"],
      borderWidth: 0
    }]
  },
  options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById("barChart"), {
  type: "bar",
  data: {
    labels: @json($graficos['barras']['labels']),
    datasets: [{
      label: "Litros Consumidos",
      data: @json($graficos['barras']['valores']),
      backgroundColor: "#6366f1"
    }]
  },
  options: { responsive: true, maintainAspectRatio: false }
});
</script>
@endsection