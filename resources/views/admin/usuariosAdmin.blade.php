@extends('admin.layoutbase')

@section('conteudo')
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
.grid-2 { grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }

.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}

.metric-title { font-size: 13px; color: var(--muted); }
.metric-value { font-size: 26px; font-weight: 700; margin-top: 6px; }

/* SEARCH BAR */
.search-container { display: flex; gap: 10px; margin-bottom: 20px; }

.search-input {
  flex: 1;
  padding: 10px 15px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--input-bg);
  color: var(--text-dash);
  font-size: 14px;
}

.search-input:focus { outline: none; border-color: var(--primary); }

/* BOTÕES */
.btn {
  padding: 10px 15px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  text-decoration: none;
  color: #fff;
  transition: background 0.3s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.btn-primary { background: var(--primary); }
.btn-primary:hover { background: var(--primary-hover); }
.btn-warning { background: var(--warning); color: #000; }
.btn-warning:hover { opacity: 0.9; }
.btn-danger { background: var(--danger); }
.btn-danger:hover { background: var(--danger-hover); }
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

/* AÇÕES DA TABELA */
.actions { display: flex; gap: 8px; }

@media(max-width:900px){
  .header-dash { flex-direction: column; align-items: flex-start; }
}
</style>

<div class="dashboard-body">

  <!-- HEADER -->
  <div class="header-dash">
    <div>
      <div class="title-dash">Usuários</div>
      <div class="subtitle">Gerenciamento e controle de acessos</div>
    </div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">+ Novo Usuário</a>
  </div>

  <!-- HIGHLIGHTS (Cadastros Hoje e Acesso IoT) -->
  <div class="grid grid-2" style="margin-bottom: 24px;">
    <div class="card">
      <div class="metric-title">Cadastrados Hoje</div>
      <!-- ✅ Correção 1: Ajustado para $dadosUsuarios -->
      <div class="metric-value">{{ $dadosUsuarios['cadastrados_hoje'] ?? 0 }}</div>
      <div class="metric-delta" style="color:var(--muted)">Usuários registrados hoje</div>
    </div>

    <div class="card">
      <div class="metric-title">Acesso ao Bebedouro IoT</div>
      <!-- ✅ Correção 1: Ajustado para $dadosUsuarios -->
      <div class="metric-value">{{ $dadosUsuarios['acessos_iot'] ?? 0 }}</div>
      <div class="metric-delta" style="color:var(--muted)">Total com cartão RFID cadastrado</div>
    </div>
  </div>

  <!-- ÁREA DE PESQUISA E TABELA -->
  <div class="card">
    
    <!-- BARRA DE PESQUISA -->
    <form action="{{ route('admin.usuario') }}" method="GET" class="search-container">
      <input 
        type="text" 
        name="search" 
        class="search-input" 
        placeholder="Pesquisar por nome ou ID..." 
        value="{{ request('search') }}"
      >
      <button type="submit" class="btn btn-primary">Buscar</button>
      @if(request('search'))
        <a href="{{ route('admin.usuario') }}" class="btn btn-danger">Limpar</a>
      @endif
    </form>

    <!-- TABELA DE USUÁRIOS -->
    <div class="table-container">
      <div class="metric-title" style="margin-bottom: 12px;">Tabela de Usuários</div>
      <table class="table-dash">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome do Usuário</th>
            <th>Data de Cadastro</th>
            <th>Acesso IoT</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($usuarios as $user)
          <tr>
            <td>#{{ $user->id }}</td>
            <td>{{ $user->nome }}</td>
            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y - H:i') }}</td>
            <td>
              <!-- ✅ Correção 2: Verifica diretamente se rfid_uid possui valor -->
              @if($user->rfid_uid)
                <span class="tag tag-success">Habilitado</span>
              @else
                <span class="tag tag-danger">Sem RFID</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <!-- Botão Editar -->
                <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-warning btn-sm">Editar</a>
                
                <!-- Botão Excluir -->
                <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align: center; padding: 20px; color: var(--muted);">
              Nenhum usuário encontrado na pesquisa.
            </td>
          </tr>
          @empty
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- PAGINAÇÃO -->
    <div style="margin-top: 15px;">
      {{ $usuarios->links() }}
    </div>

  </div>

</div>
@endsection