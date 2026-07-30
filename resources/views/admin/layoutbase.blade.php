<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Layout - DrinkUp</title>

  <style>
    :root {
      --bg: #0f172a;
      --card: #111827;
      --text: #ffffff;
      --muted: #9ca3af;
      --primary: #6366f1;
      --danger: #ef4444;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: var(--bg);
      color: var(--text);
    }

    .layout {
      display: flex;
      min-height: 100vh;
      width: 100%;
    }

    /* SIDEBAR */
   /* SIDEBAR TOTALMENTE REESTILIZADA */
.sidebar {
  width: 200px; /* Um pouquinho mais larga para acomodar melhor os textos */
  background: #111827 !important; /* Agora ela fica escura, igual aos cards do header! */
  padding: 24px 16px;
  display: flex;
  flex-direction: column;
  border-right: 1px solid #1f2937; /* Linha sutil separando a sidebar do conteúdo */
}

/* TÍTULO PRINCIPAL DO SISTEMA */
.sidebar h2 {
  margin-bottom: 32px;
  font-size: 20px;
  font-weight: bold;
  color: #fff; /* Texto do título em branco brilhante */
  padding-left: 8px;
  letter-spacing: 0.5px;
}

/* LINKS DE NAVEGAÇÃO */
.sidebar a {
  display: flex;
  align-items: center;
  gap: 12px; /* Espaço perfeito entre o ícone e o texto */
  color: var(--muted);
  text-decoration: none;
  margin-bottom: 8px;
  font-size: 14px;
  font-weight: 500;
  padding: 10px 12px;
  border-radius: 8px; /* Cantos arredondados nos links estilo SaaS moderno */
  transition: all 0.2s ease;
}

/* HOVER (QUANDO PASSA O MOUSE) */
.sidebar a:hover {
  color: white;
  background: rgba(255, 255, 255, 0.05); /* Efeito sutil de fundo ao passar o mouse */
}

/* CLASSE ACTIVE (DESTAQUE DA PÁGINA ATUAL) */
.sidebar a.active {
  color: white;
  background: var(--primary); /* Fica azul/roxo para destacar onde o usuário está */
  font-weight: 600;
}

    /* CONTENT */
    .content {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    /* HEADER */
    .header {
      height: 64px;
      display: flex;
      align-items: center;
      padding: 0 20px;
      border-bottom: 1px solid #1f2937;
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(8px);
    }

    .search {
      margin-left: 10px;
      flex: 1;
      max-width: 300px;
      position: relative;
    }

    .search input {
      width: 100%;
      padding: 8px 8px 8px 30px;
      border-radius: 6px;
      border: none;
      background: #1f2937;
      color: white;
    }

    .search span {
      position: absolute;
      left: 8px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 12px;
      color: var(--muted);
    }

    .actions {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .icon-btn {
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      position: relative;
    }

    .dot {
      position: absolute;
      top: 4px;
      right: 4px;
      width: 8px;
      height: 8px;
      background: var(--danger);
      border-radius: 50%;
    }

    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, #6366f1, #a5b4fc);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }

    /* MAIN */
    .main {
      padding: 20px;
    }
  </style>
</head>

<body>
  <div class="layout">
    
    <!-- SIDEBAR (MENU LATERAL ATUALIZADO) -->
    <div class="sidebar">
      <h2>DrinkUp Admin</h2>
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <a href="{{ route('admin.usuario') }}">Usuários</a>
      <a href="{{ route('admin.bebedouros') }}">Bebedouros</a>
      <a href="{{ route('admin.relatorios') }}">Relatórios</a>
      <a href="{{ route('admin.perfil') }}">Meu Perfil</a>
      <a href="{{ route('admin.configuracoes') }}">Configurações</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
      
      <!-- HEADER -->
      <div class="header">
        <div class="search">
          <span>🔍</span>
          <input placeholder="Buscar bebedouros, usuários..." />
        </div>

        <div class="actions">
          <button class="icon-btn">
            🔔
            <div class="dot"></div>
          </button>

          <!-- ATALHO NO AVATAR: Clicar na foto também leva para o perfil -->
          <a href="{{ route('admin.perfil') }}" style="text-decoration: none; color: inherit;">
            <div class="avatar">AD</div>
          </a>
        </div>
      </div>

      <!-- MAIN CONTENT -->
      <div class="main">
        <!-- O yield recebe e renderiza as páginas dinamicamente --> 
        @yield("conteudo")
      </div> 
    </div> <!-- FIM DA DIV CONTENT -->
  </div> <!-- FIM DA DIV LAYOUT -->
</body>
</html>