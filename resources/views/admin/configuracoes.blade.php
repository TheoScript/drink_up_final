@extends('admin.layoutbase')

@section('conteudo')
<style>
:root {
  --bg-card: #ffffff;
  --text-main: #1f2937;
  --muted: #6b7280;
  --primary: #2563eb;
  --primary-light: #dbeafe;
  --success: #16a34a;
  --danger: #dc2626;
  --border: #e5e7eb;
}

h1 { font-size: 26px; }
h2 { font-size: 16px; margin-bottom: 10px; }

.grid {
  display: grid;
  gap: 20px;
}

@media (min-width: 900px) {
  .grid { grid-template-columns: 1fr 1fr; }
  .full { grid-column: span 2; }
}

.card {
  background: var(--bg-card);
  padding: 20px;
  border-radius: 12px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid var(--border);
  color: var(--text-main);
}

.row:last-child { border-bottom: none; }
.muted { color: var(--muted); }

.btn-update {
  margin-top: 12px;
  padding: 10px;
  background: var(--primary);
  border: none;
  color: white;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.2s;
  font-weight: 500;
}

.btn-update:hover { opacity: 0.9; }

.switch-row {
  display: flex;
  justify-content: space-between;
  padding: 12px;
  border: 1px solid var(--border);
  border-radius: 10px;
  margin-bottom: 10px;
  background: #f9fafb;
  color: var(--text-main);
}

.badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: bold;
  text-transform: uppercase;
}

.online {
  background: var(--primary-light);
  color: var(--primary);
}

.offline {
  background: #fee2e2;
  color: var(--danger);
}

.device {
  display: flex;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid var(--border);
  color: var(--text-main);
}

.device:last-child { border-bottom: none; }

input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: var(--primary);
  cursor: pointer;
}
</style>

<h1>Configurações</h1>
<p class="muted">Gerencie integrações, notificações e preferências do sistema.</p>

<div class="grid">

  <!-- CARD: SISTEMA -->
  <div class="card">
    <h2>Sistema</h2>

    <div class="row">
      <span class="muted">Versão</span>
      <span>{{ $infoSistema['versao'] }}</span>
    </div>

    <div class="row">
      <span class="muted">Última atualização</span>
      <span>{{ $infoSistema['ultima_atualizacao'] }}</span>
    </div>

    <div class="row">
      <span class="muted">Ambiente</span>
      <span>{{ $infoSistema['ambiente'] }}</span>
    </div>

    <div class="row">
      <span class="muted">Região</span>
      <span>{{ $infoSistema['regiao'] }}</span>
    </div>

    <button class="btn-update" onclick="verificarAtualizacoes()">Procurar atualizações</button>
  </div>

  <!-- CARD: APARÊNCIA -->
  <div class="card">
    <h2>Aparência</h2>

    <div class="switch-row">
      <div>
        <strong>Modo escuro</strong>
        <div class="muted">Ative o tema escuro</div>
      </div>
      <input type="checkbox" id="modo_escuro" checked onchange="salvarPreferencia('modo_escuro')">
    </div>

    <div class="switch-row">
      <div>
        <strong>Layout compacto</strong>
        <div class="muted">Reduz espaçamento</div>
      </div>
      <input type="checkbox" id="layout_compacto" onchange="salvarPreferencia('layout_compacto')">
    </div>
  </div>

  <!-- CARD: INTEGRAÇÕES IOT (DADOS VINDOS DO CONTROLLER) -->
  <div class="card full">
    <h2>Integrações com dispositivos</h2>
    <p class="muted">Status das conexões IoT</p>

    <div id="devices">
      @foreach($integracoes as $dispositivo)
        <div class="device">
          <div>
            <strong>{{ $dispositivo['name'] }}</strong><br>
            <span class="muted">{{ $dispositivo['count'] }} dispositivos ativos</span>
          </div>
          <span class="badge {{ $dispositivo['status'] === 'online' ? 'online' : 'offline' }}">
            {{ $dispositivo['status'] }}
          </span>
        </div>
      @endforeach
    </div>
  </div>

  <!-- CARD: NOTIFICAÇÕES -->
  <div class="card full">
    <h2>Notificações</h2>

    <div class="switch-row">
      <div>
        <strong>Alertas de manutenção</strong>
        <div class="muted">Avisos de manutenção de equipamentos</div>
      </div>
      <input type="checkbox" checked onchange="salvarPreferencia('alerta_manutencao')">
    </div>

    <div class="switch-row">
      <div>
        <strong>Filtros vencendo</strong>
        <div class="muted">Aviso de filtro de água ruim</div>
      </div>
      <input type="checkbox" checked onchange="salvarPreferencia('alerta_filtros')">
    </div>

    <div class="switch-row">
      <div>
        <strong>Quedas de conexão</strong>
        <div class="muted">Dispositivo ou bebedouro offline</div>
      </div>
      <input type="checkbox" checked onchange="salvarPreferencia('alerta_conexoes')">
    </div>

    <div class="switch-row">
      <div>
        <strong>Resumo semanal</strong>
        <div class="muted">Relatório de consumo por e-mail semanal</div>
      </div>
      <input type="checkbox" onchange="salvarPreferencia('resumo_semanal')">
    </div>
  </div>

</div>

<script>
function verificarAtualizacoes() {
  alert("Verificando se existem novas atualizações para o DrinkUp Admin...");
}

function salvarPreferencia(chave) {
  // Próximo passo: integrar com AJAX para salvar as preferências sem recarregar a página
  console.log("Preferência alterada: " + chave);
}
</script>
@endsection