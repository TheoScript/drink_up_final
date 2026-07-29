@extends('admin.layoutbase')

@section('conteudo')
<!-- Incluindo o Chart.js e estilos de forma correta e segura dentro da Section -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
:root {
  --bg-dash: #f8fafc; 
  --border: #1f2937;
  --grafic-border: #ffffff;
  --text: #e5e7eb;
  --text-dash: #ffffff;
  --muted: #64748b;
  --primary: #2563eb;
  --success: #16a34a;
  --warning: #f59e0b;
  --danger: #dc2626;
}

.dashboard-body {
  font-family: 'Inter', sans-serif;
  color: var(--text-dash);
  padding: 12px;
}

/* HEADER */
.header-dash {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.title-dash {
  font-size: 28px;
  font-weight: 700;
}

.subtitle {
  color: var(--muted);
  font-size: 14px;
}

.status {
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 12px;
  background: #dcfce7;
  color: var(--success);
}

/* GRID */
.grid {
  display: grid;
  gap: 20px;
}

.grid-4 {
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}

.grid-3 {
  grid-template-columns: 2fr 1fr;
}

.grid-2 {
  grid-template-columns: 2fr 1fr;
}

/* CARD */
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}

/* METRIC */
.metric-title {
  font-size: 13px;
  color: var(--muted);
}

.metric-value {
  font-size: 26px;
  font-weight: 700;
  margin-top: 6px;
}

.metric-delta {
  font-size: 12px;
  margin-top: 4px;
}

/* ACTIVITY */
.activity {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid var(--border);
}

.activity:last-child {
  border-bottom: none;
}

.activity small {
  color: var(--muted);
}

/* PROGRESS */
.progress {
  height: 6px;
  background: #e2e8f0;
  border-radius: 6px;
  overflow: hidden;
  margin-top: 6px;
}

.bar {
  height: 100%;
  border-radius: 6px;
}

@media(max-width:900px){
  .grid-3, .grid-2 {
    grid-template-columns: 1fr;
  }
}
</style>

<div class="dashboard-body">

  <!-- HEADER -->
  <div class="header-dash">
    <div>
      <div class="title-dash">Visão geral</div>
      <div class="subtitle">Monitoramento da rede de bebedouros</div>
    </div>
    <div class="status">● Sistema operacional</div>
  </div>

  <!-- MÉTRICAS (Dados Dinâmicos do Controller) -->
  <div class="grid grid-4">
    <div class="card">
      <div class="metric-title">Litros hoje</div>
      <div class="metric-value">{{ $dadosCards['litros_hoje'] }} L</div>
      <div class="metric-delta" style="color:var(--success)">{{ $dadosCards['litros_delta'] }}</div>
    </div>

    <div class="card">
      <div class="metric-title">Usuários ativos</div>
      <div class="metric-value">{{ $dadosCards['usuarios_ativos'] }}</div>
      <div class="metric-delta" style="color:var(--success)">{{ $dadosCards['usuarios_delta'] }}</div>
    </div>

    <div class="card">
      <div class="metric-title">Bebedouros</div>
      <div class="metric-value">{{ $dadosCards['bebedouros_on'] }} / {{ $dadosCards['bebedouros_total'] }}</div>
      <div class="metric-delta" style="color:var(--danger)">{{ $dadosCards['bebedouros_delta'] }}</div>
    </div>

    <div class="card">
      <div class="metric-title">Alertas</div>
      <div class="metric-value">{{ $dadosCards['alertas_qtde'] }}</div>
      <div class="metric-delta" style="color:var(--warning)">{{ $dadosCards['alertas_delta'] }}</div>
    </div>
  </div>

  <!-- GRÁFICOS -->
  <div class="grid grid-3" style="margin-top:20px">
    <div class="card">
      <h3>Consumo semanal</h3>
      <div style="position: relative; height: 300px; width: 100%;">
        <canvas id="lineChart"></canvas>
      </div>
    </div>

    <div class="card">
      <h3>Horários de pico</h3>
      <div style="position: relative; height: 300px; width: 100%;">
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>

  <!-- ATIVIDADES + SAÚDE -->
  <div class="grid grid-2" style="margin-top:20px">
    <div class="card">
      <h3>Atividades recentes</h3>
      
      <!-- Listando as atividades reais coletadas do banco pelo Controller -->
      @foreach($atividadesRecentes as $atividade)
      <div class="activity">
        <span>{{ $atividade['texto'] }}</span>
        <small>{{ $atividade['tempo'] }}</small>
      </div>
      @endforeach
    </div>

  <div class="card">
  <h3>Saúde da frota</h3>

    <div>
      <small>Filtros bons ({{ $saudeFrota['filtros_bons'] }}%)</small>
      <!-- Criamos a variável --w e o CSS lê de forma perfeitamente válida para o editor -->
      <div class="progress">
        <div class="bar" style="--w: {{ $saudeFrota['filtros_bons'] }}%; background: var(--success); width: var(--w);"></div>
      </div>
    </div>

    <div style="margin-top: 10px;">
      <small>Manutenção ({{ $saudeFrota['manutencao'] }}%)</small>
      <div class="progress">
        <div class="bar" style="--w: {{ $saudeFrota['manutencao'] }}%; background: var(--warning); width: var(--w);"></div>
      </div>
    </div>

    <div style="margin-top: 10px;">
    <small>Pressão OK ({{ $saudeFrota['pressao_ok'] }}%)</small>
      <div class="progress">
        <div class="bar" style="--w: {{ $saudeFrota['pressao_ok'] }}%; background: var(--primary); width: var(--w);"></div>
      </div>
    </div>

    <div style="margin-top: 10px;">
      <small>Falhas ({{ $saudeFrota['falhas'] }}%)</small>
      <div class="progress">
        <div class="bar" style="--w: {{ $saudeFrota['falhas'] }}%; background: var(--danger); width: var(--w);"></div>
      </div>
    </div>
</div>

</div>

<!-- SCRIPT DOS GRÁFICOS (Alimentados pelas matrizes PHP) -->
<script>
// LINE CHART - Consumo dinâmico dos últimos 7 dias
new Chart(document.getElementById("lineChart"), {
  type: "line",
  data: {
    labels: @json($labelsGraficoLinha), // Recebe os dias (Ex: ["Ter", "Qua", ...])
    datasets: [{
      label: "Consumo (L)",
      data: @json($dadosGraficoLinha),   // Recebe os valores em litros
      borderColor: "#010102",
      backgroundColor: "rgba(37,99,235,0.1)",
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales:{
      y:{
        min:0,  //Forçar inicio do grafico como zero
        beginAtZero: true
      }
    }
  }
});

// BAR CHART - Horários dinâmicos de hoje
new Chart(document.getElementById("barChart"), {
  type: "bar",
  data: {
    labels: @json($labelsGraficoBarra), // Recebe os horários (Ex: ["08h", "10h", ...])
    datasets: [{
      label: "Registros (2 em 2h)",
      data: @json($dadosGraficoBarra),    // Recebe a quantidade de registros por bloco
      backgroundColor: "#2563eb"
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: {
        ticks: {
          autoSkip: true,     // Ativa o pulo inteligente de textos se faltar espaço
          maxTicksLimit: 12,  // Garante um limite visual confortável na tela
          maxRotation: 45,    // Inclina o texto em 45 graus para caber mais informação
          minRotation: 45     // Mantém a inclinação fixa para um visual padrão
        }
      },
      y: {
        min: 0,               // Garante que o gráfico de barras comece no zero
        beginAtZero: true
      }
    }
  }
});
</script>
@endsection