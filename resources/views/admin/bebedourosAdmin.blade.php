@extends('admin.layoutbase')

@section('conteudo')
<!-- Incluindo Chart.js via CDN para o Gráfico -->
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
  --primary-hover: #1d4ed8;
  --success: #16a34a;
  --warning: #f59e0b;
  --danger: #dc2626;
  --danger-hover: #b91c1c;
  --card: #0f172a;
  --input-bg: #1e293b;
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
  flex-wrap: wrap;
  gap: 15px;
}

.title-dash { font-size: 28px; font-weight: 700; }
.subtitle { color: var(--muted); font-size: 14px; }

/* GRID E CARDS */
.grid { display: grid; gap: 20px; }
.grid-3 { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
.grid-2 { grid-template-columns: 2fr 1fr; } /* Layout para gráfico e mini-relatório */

.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}

.metric-title { font-size: 13px; color: var(--muted); margin-bottom: 8px;}
.metric-value { font-size: 26px; font-weight: 700; margin-top: 6px; }

/* SEARCH BAR & BUTTONS */
.search-container { display: flex; gap: 10px; margin-bottom: 20px; }
.search-input {
  flex: 1; padding: 10px 15px; border-radius: 8px;
  border: 1px solid var(--border); background: var(--input-bg);
  color: var(--text-dash); font-size: 14px;
}
.search-input:focus { outline: none; border-color: var(--primary); }

.btn {
  padding: 10px 15px; border-radius: 8px; font-size: 14px; font-weight: 600;
  cursor: pointer; border: none; text-decoration: none; color: #fff;
  transition: background 0.3s; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-primary { background: var(--primary); }
.btn-primary:hover { background: var(--primary-hover); }
.btn-warning { background: var(--warning); color: #000; }
.btn-danger { background: var(--danger); }
.btn-danger:hover { background: var(--danger-hover); }
.btn-success { background: var(--success); }
.btn-sm { padding: 6px 12px; font-size: 12px; }

/* TABELA */
.table-container { overflow-x: auto; }
.table-dash { width: 100%; border-collapse: collapse; text-align: left; }
.table-dash th, .table-dash td { padding: 12px 15px; border-bottom: 1px solid var(--border); }
.table-dash th { color: var(--muted); font-size: 13px; font-weight: 600; }
.table-dash td { font-size: 14px; }

/* STATUS TAGS */
.tag { padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.tag-success { background: rgba(22, 163, 74, 0.2); color: var(--success); }
.tag-danger { background: rgba(220, 38, 38, 0.2); color: var(--danger); }
.tag-warning { background: rgba(245, 158, 11, 0.2); color: var(--warning); }

.actions { display: flex; gap: 8px; }

/* ESTILOS DE IMPRESSÃO */
@media print {
  body * { visibility: hidden; }
  .dashboard-body, .dashboard-body * { visibility: visible; }
  .dashboard-body { position: absolute; left: 0; top: 0; width: 100%; color: #000 !important; background: #fff !important; }
  .card { border: 1px solid #ccc; box-shadow: none; background: #fff; color: #000; }
  .btn, .search-container, .actions, .header-dash a { display: none !important; }
  .table-dash th { color: #333; border-bottom: 2px solid #000; }
  .table-dash td { border-bottom: 1px solid #ccc; }
  .metric-title, .subtitle { color: #555 !important; }
  canvas { max-height: 300px !important; }
}

@media(max-width:900px){
  .header-dash { flex-direction: column; align-items: flex-start; }
  .grid-2 { grid-template-columns: 1fr; }
}
</style>

<div class="dashboard-body">

  <!-- HEADER -->
  <div class="header-dash">
    <div>
      <div class="title-dash">Bebedouros IoT</div>
      <div class="subtitle">Monitoramento de consumo e conectividade dos dispositivos ESP</div>
    </div>
    <div class="actions">
      <button onclick="window.print()" class="btn btn-warning">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg>
        Imprimir Relatório
      </button>
      
    </div>
  </div>

  <!-- HIGHLIGHTS -->
  <div class="grid grid-3" style="margin-bottom: 24px;">
    <div class="card">
      <div class="metric-title">Bebedouros Ativos</div>
      <div class="metric-value">{{ $dadosBebedouros['total_ativos'] ?? 0 }}</div>
    </div>
    <div class="card">
      <div class="metric-title">Problemas de Conexão</div>
      <div class="metric-value" style="color: var(--danger)">{{ $dadosBebedouros['alertas_conexao'] ?? 0 }}</div>
    </div>
    <div class="card">
      <div class="metric-title">Consumo Total (Hoje)</div>
      <div class="metric-value">{{ $dadosBebedouros['consumo_total_hoje'] ?? 0 }} L</div>
    </div>
  </div>

  <!-- SESSÃO DE GRÁFICO E RELAÇÃO ESP -->
  <div class="grid grid-2" style="margin-bottom: 24px;">
    <!-- Gráfico -->
    <div class="card">
      <div class="metric-title" style="margin-bottom: 15px;">Consumo Semanal (Litros)</div>
      <canvas id="consumoChart" height="100"></canvas>
    </div>
    
    <!-- Info Adicional / ESP -->
    <div class="card">
      <div class="metric-title">Arquitetura do Sistema</div>
      <p style="font-size: 14px; line-height: 1.6; color: var(--muted); margin-top: 10px;">
        O sistema vincula o <strong>ID Interno</strong> do banco de dados ao <strong>ESP ID</strong> (Endereço MAC ou identificador único do microcontrolador). <br><br>
        O ESP transmite pulsos lidos do sensor de fluxo de água via Wi-Fi (MQTT/HTTP), atualizando os status de funcionalidade em tempo real na tabela abaixo.
      </p>
    </div>
  </div>

  <!-- ÁREA DE PESQUISA E TABELA DE BEBEDOUROS -->
  <div class="card">
    <!-- BARRA DE PESQUISA -->
    <form action="{{ route('admin.bebedouros') }}" method="GET" class="search-container">
      <input type="text" name="search" class="search-input" placeholder="Pesquisar por nome ou ESP ID..." value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary">Buscar</button>
      @if(request('search'))
        <a href="{{ route('admin.bebedouros.index') }}" class="btn btn-danger">Limpar</a>
      @endif
    </form>

    <!-- TABELA -->
    <div class="table-container">
      <div class="metric-title" style="margin-bottom: 10px;">Status e Funcionalidade da Rede de Bebedouros</div>
      <table class="table-dash">
        <thead>
          <tr>
            <th>ID Base</th>
            <th>ESP ID (Microcontrolador)</th>
            <th>Localização</th>
            <th>Consumo Hoje</th>
            <th>Conectividade (Wi-Fi)</th>
            <th>Status do ESP</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bebedouros as $beb)
          <tr>
            <td>#{{ $beb->id }}</td>
            <td style="font-family: monospace; color: var(--warning)">{{ $beb->esp_id }}</td>
            <td>{{ $beb->nome }}</td>
            <td><strong>{{ $beb->consumo_hoje }} L</strong></td>
            
            <!-- Conectividade -->
            <td>
                @if($beb->status == 'online')
                    <span style="font-size: 12px;">Sinal: {{ $beb->sinal_wifi }} dBm</span><br>
                    <span style="font-size: 11px; color: var(--muted)">Visto: {{ $beb->ultima_comunicacao->diffForHumans() }}</span>
                @else
                    <span style="font-size: 12px; color: var(--danger)">Sem sinal</span><br>
                    <span style="font-size: 11px; color: var(--muted)">Queda: {{ $beb->ultima_comunicacao->format('d/m H:i') }}</span>
                @endif
            </td>

            <!-- Status do ESP -->
            <td>
              @if($beb->status == 'online')
                <span class="tag tag-success">Online</span>
              @else
                <span class="tag tag-danger">Offline</span>
              @endif
            </td>

            <td>
              <div class="actions">
                <a href="{{ route('admin.bebedouros', $beb->id) }}" class="btn btn-primary btn-sm">Detalhes</a>
                <a href="{{ route('admin.bebedouros', $beb->id) }}" class="btn btn-warning btn-sm">Editar</a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 20px; color: var(--muted);">
              Nenhum bebedouro encontrado na pesquisa.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- PAGINAÇÃO -->
    {{-- <div style="margin-top: 15px;">{{ $bebedouros->links() }}</div> --}}
  </div>

</div>

<!-- SCRIPT DO GRÁFICO (Chart.js) -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('consumoChart').getContext('2d');
      
      // Recebe os dados gerados lá no Controller pelo PHP
      const chartData = @json($chartData);

      new Chart(ctx, {
          type: 'line', // ou 'bar'
          data: {
              labels: chartData.labels,
              datasets: [{
                  label: 'Consumo Diário (Litros)',
                  data: chartData.data,
                  borderColor: '#2563eb', // primary color
                  backgroundColor: 'rgba(37, 99, 235, 0.1)',
                  borderWidth: 2,
                  fill: true,
                  tension: 0.3
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: { display: false }
              },
              scales: {
                  y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#64748b' }
                  },
                  x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                  }
              }
          }
      });
  });
</script>
@endsection