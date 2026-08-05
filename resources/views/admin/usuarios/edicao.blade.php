@extends('admin.layoutbase')

@section('conteudo')
<style>
:root {
  --bg-dash: #f8fafc; 
  --border: #1f2937;
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

/* CARDS E GRIDS */
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.03);
  margin-bottom: 24px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

/* FORMULÁRIOS */
.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 15px;
}

.form-label {
  font-size: 13px;
  color: var(--muted);
  font-weight: 600;
  margin-bottom: 8px;
}

.form-control, .form-select {
  width: 100%;
  padding: 12px 15px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--input-bg);
  color: var(--text-dash);
  font-size: 14px;
  transition: border-color 0.3s;
}

.form-control:focus, .form-select:focus {
  outline: none;
  border-color: var(--primary);
}

.form-select option {
  background: var(--card);
  color: var(--text-dash);
}

.form-text {
  font-size: 12px;
  color: var(--muted);
  margin-top: 5px;
}

/* DIVISOR */
.divider {
  height: 1px;
  background: var(--border);
  margin: 24px 0;
}

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
.btn-secondary { background: var(--border); color: var(--text-dash); }
.btn-secondary:hover { background: #374151; }
</style>

<div class="dashboard-body">

  <!-- HEADER -->
  <div class="header-dash">
    <div>
      <div class="title-dash">Editar Usuário</div>
      <div class="subtitle">Atualize as informações, nível de acesso e cartão RFID</div>
    </div>
    <a href="{{ route('admin.usuario') }}" class="btn btn-secondary">← Voltar</a>
  </div>

  <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- CARD 1: INFORMAÇÕES BÁSICAS E ACESSO -->
    <div class="card">
      <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px;">Dados Principais</h3>
      
      <div class="form-grid">
        <!-- Nome -->
        <div class="form-group">
          <label class="form-label">Nome Completo</label>
          <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome', $usuario->nome) }}" required>
        </div>

        <!-- Senha -->
        <div class="form-group">
          <label class="form-label">Nova Senha</label>
          <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite apenas se quiser alterar">
          <div class="form-text">Deixe em branco para manter a senha atual.</div>
        </div>

        <!-- Nível de Acesso -->
        <div class="form-group">
          <label class="form-label">Nível do Usuário</label>
          <select name="nivel" class="form-select" required>
            <!-- Adapte 'admin' e 'usuario' para os valores reais do seu banco -->
            <option value="usuario" {{ old('nivel', $usuario->nivel) == 'usuario' ? 'selected' : '' }}>Usuário Comum</option>
            <option value="admin" {{ old('nivel', $usuario->nivel) == 'admin' ? 'selected' : '' }}>Administrador</option>
          </select>
        </div>

        <!-- RFID -->
        <div class="form-group">
          <label class="form-label">Código do Cartão (RFID UID)</label>
          <input type="text" id="rfid_uid" name="rfid_uid" class="form-control" value="{{ old('rfid_uid', $usuario->rfid_uid) }}" placeholder="Ex: A1B2C3D4">
          <div class="form-text">Usado para liberar o bebedouro IoT.</div>
        </div>
      </div>
    </div>

    <!-- CARD 2: SEGURANÇA E BLOQUEIOS -->
    <div class="card">
      <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: var(--warning);">Segurança e Bloqueios</h3>
      
      <div class="form-grid">
        <!-- Bloqueio Geral do Usuário -->
        <div class="form-group">
          <label class="form-label">Status da Conta</label>
          <select name="is_blocked" class="form-select">
            <option value="0" {{ old('is_blocked', $usuario->is_blocked) == 0 ? 'selected' : '' }}>🟢 Ativa (Desbloqueado)</option>
            <option value="1" {{ old('is_blocked', $usuario->is_blocked) == 1 ? 'selected' : '' }}>🔴 Suspensa (Bloqueado)</option>
          </select>
          <div class="form-text">Impede o usuário de acessar tanto o sistema web quanto o bebedouro.</div>
        </div>

        <!-- Desbloqueio de Login (Falhas excessivas) -->
        <div class="form-group">
          <label class="form-label">Status de Login Web</label>
          <select name="login_bloqueado" class="form-select">
            <option value="0" {{ old('login_bloqueado', $usuario->login_bloqueado) == 0 ? 'selected' : '' }}>🔓 Livre para logar</option>
            <option value="1" {{ old('login_bloqueado', $usuario->login_bloqueado) == 1 ? 'selected' : '' }}>🔒 Bloqueado por tentativas</option>
          </select>
          <div class="form-text">Mude para "Livre" caso o usuário tenha errado a senha muitas vezes.</div>
        </div>
      </div>
    </div>

    <!-- BOTÃO DE SALVAR -->
    <div style="text-align: right;">
      <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 16px;">
        💾 Salvar Alterações
      </button>
    </div>

  </form>

</div>
@endsection