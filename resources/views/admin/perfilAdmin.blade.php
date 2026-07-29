@extends('admin.layoutbase')

@section('conteudo')
<style>
:root {
  --bg-profile: #f9fafb;
  --text-profile: #ffffff;
  --muted: #6b7280;
  --primary: #2563eb;
  --border: #1f2937;
}

h1 { font-size: 26px; margin-bottom: 5px; }
.subtitle { color: var(--muted); margin-bottom: 20px; }

.grid {
  display: grid;
  gap: 20px;
}

@media(min-width: 900px){
  .grid { grid-template-columns: 1fr 2fr; }
  .full { grid-column: span 2; }
}

.card {
  background: var(--card);
  padding: 20px;
  border-radius: 12px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.profile { text-align: center; }

.avatar-wrapper {
  position: relative;
  width: 120px;
  height: 120px;
  margin: auto;
}

/* Mudamos o nome para .avatar-perfil */
.avatar-perfil {
  width: 120px;  /* Voltou a ficar grande e proporcional ao card */
  height: 120px; 
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb, #60a5fa);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px; /* Letras grandes para o card */
  color: white;
}

.name { margin-top: 16px; font-size: 20px; font-weight: bold; }
.role, .email { color: var(--muted); margin-top: 4px; font-size: 14px; }

.field { display: flex; flex-direction: column; margin-bottom: 15px; }
label { font-size: 13px; margin-bottom: 4px; font-weight: 600; }
input { padding: 10px; border-radius: 6px; border: 1px solid var(--border); background: #fff; color: #1f2937; }
input:disabled { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }

.row { display: grid; gap: 10px; }
@media(min-width:600px){ .row { grid-template-columns: 1fr 1fr; } }

.row-3 { display: grid; gap: 10px; }
@media(min-width:600px){ .row-3 { grid-template-columns: 1fr 1fr 1fr; } }

button { padding: 10px 14px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; }
.primary { background: var(--primary); color: white; }
.outline { background: transparent; border: 1px solid var(--border); color: var(--text-profile); text-decoration: none; display: inline-block; text-align: center; }
.actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; }
button:hover, .outline:hover { opacity: 0.9; }

.alert-success { background: #dcfce7; color: #16a34a; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
.alert-danger { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; list-style: none; }
</style>

<h1>Perfil</h1>
<p class="subtitle">Gerencie seus dados pessoais e de acesso.</p>

<!-- Feedback de Sucesso ou Erros de Validação -->
@if(session('sucesso'))
    <div class="alert-success">✓ {{ session('sucesso') }}</div>
@endif

@if($errors->any())
    <div class="alert-danger">
        @foreach($errors->all() as $error)
            <li>X {{ $error }}</li>
        @endforeach
    </div>
@endif

<div class="grid">

  <!-- CARD VISUAL DE PERFIL -->
  <div class="card profile">
    <div class="avatar-wrapper">
      <div class="avatar-perfil">
        {{ strtoupper(substr($admin->nome, 0, 2)) }}
      </div>
    </div>
    <div class="name">{{ $admin->nome }}</div>
    <div class="role">Administrador (Nível {{ $admin->nivel }})</div>
    <div class="email">{{ $admin->email }}</div>
  </div>

  <!-- FORMULÁRIO DE INFORMAÇÕES PESSOAIS -->
  <div class="card">
    <h2>Informações pessoais</h2>

    <form action="{{ route('admin.perfil.salvar') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="field">
          <label>Nome</label>
          <input type="text" name="nome" value="{{ old('nome', $admin->nome) }}" required>
        </div>

        <div class="field">
          <label>Email</label>
          <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>
        </div>

        <div class="field">
          <label>Idade</label>
          <input type="number" name="idade" value="{{ old('idade', $admin->idade) }}">
        </div>

        <div class="field">
          <label>Peso (kg)</label>
          <input type="number" step="0.1" name="peso" value="{{ old('peso', $admin->peso) }}">
        </div>
      </div>

      <div class="actions">
        <a href="{{ route('admin.perfil') }}" class="outline">Cancelar</a>
        <button type="submit" class="primary">Salvar alterações</button>
      </div>
    </form>
  </div>

  <!-- FORMULÁRIO DE ALTERAÇÃO DE SENHA -->
  <div class="card full">
    <h2>Alterar senha</h2>

    <form action="{{ route('admin.perfil.senha') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row-3">
        <div class="field">
          <label>Senha atual</label>
          <input type="password" name="senha_atual" required>
        </div>

        <div class="field">
          <label>Nova senha</label>
          <input type="password" name="nova_senha" required>
        </div>

        <div class="field">
          <label>Confirmar nova senha</label>
          <input type="password" name="nova_senha_confirmation" required>
        </div>
      </div>

      <div class="actions">
        <button type="submit" class="primary">Atualizar senha</button>
      </div>
    </form>
  </div>

</div>
@endsection