<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Entrar — DrinkUp Admin</title>

  <style>
    :root {
      --primary: #6366f1;
      --primary-glow: #a5b4fc;
      --background: #0f172a;
      --card: #111827;
      --text: #e5e7eb;
      --muted: #9ca3af;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background: var(--background);
      color: var(--text);
    }

    .container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      padding: 16px;
      overflow: hidden;
    }

    /* Background effects */
    .bg-gradient {
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom right, rgba(99,102,241,0.1), transparent, rgba(165,180,252,0.1));
      z-index: -2;
    }

    .blob {
      position: absolute;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      filter: blur(80px);
      z-index: -1;
    }

    .blob.top {
      top: -100px;
      right: -100px;
      background: rgba(99,102,241,0.2);
    }

    .blob.bottom {
      bottom: -100px;
      left: -100px;
      background: rgba(165,180,252,0.2);
    }

    .card {
      background: var(--card);
      padding: 32px;
      border-radius: 16px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    }

    .header {
      text-align: center;
      margin-bottom: 24px;
    }

    .logo {
      width: 56px;
      height: 56px;
      background: linear-gradient(135deg, var(--primary), var(--primary-glow));
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
      font-size: 24px;
    }

    h1 {
      font-size: 22px;
    }

    .subtitle {
      font-size: 14px;
      color: var(--muted);
      margin-top: 4px;
    }

    .form-group {
      margin-bottom: 16px;
    }

    label {
      font-size: 14px;
      display: block;
      margin-bottom: 6px;
    }

    input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      background: #1f2937;
      color: white;
    }

    .row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .forgot {
      font-size: 12px;
      color: var(--primary);
      cursor: pointer;
    }

    .forgot:hover {
      text-decoration: underline;
    }

    button {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: none;
      background: linear-gradient(135deg, var(--primary), var(--primary-glow));
      color: white;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
    }

    button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .footer {
      margin-top: 20px;
      text-align: center;
      font-size: 12px;
      color: var(--muted);
    }

    .demo-link {
      color: var(--primary);
      cursor: pointer;
    }

    .demo-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="bg-gradient"></div>
    <div class="blob top"></div>
    <div class="blob bottom"></div>

    <div class="card">
      <div class="header">
        <div class="logo">💧</div>
        <h1>DrinkUp Admin</h1>
        <p class="subtitle">Entre para gerenciar a hidratação inteligente</p>
      </div>

      <form id="loginForm" method="POST" action="{{ route('admin.login.submit') }}">
      @csrf <!-- Proteção obrigatória do Laravel contra ataques -->

    @if($errors->any())
      <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--danger); color: #fc8181; padding: 10px; border-radius: 8px;   font-size: 13px; margin-bottom: 16px; text-align: center;">
        {{ $errors->first() }}
      </div>
    @endif  
      <div class="form-group">
          <label>E-mail</label>
          <!-- Adicionado o atributo 'name' e o 'value' antigo para não apagar o que ele digitou -->
          <input type="email" name="email" placeholder="admin@drinkup.com" value="{{ old('email') }}" required />
        </div>

        <div class="form-group">
          <div class="row">
            <label>Senha</label>
            <span class="forgot">Esqueci minha senha</span>
          </div>
           <!-- Adicionado o atributo 'name' -->
           <input type="password" name="senha" placeholder="••••••••" required />
        </div>

        <button type="submit" id="submitBtn">Entrar</button>
      </form>

      <div class="footer">
        Protegido por DrinkUp · 
        <span class="demo-link" onclick="goDemo()">Acesso demo</span>
      </div>
    </div>
  </div>
  
</body>
</html>