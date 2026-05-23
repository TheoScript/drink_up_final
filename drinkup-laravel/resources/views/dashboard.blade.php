<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drink Up | Gestão de Hidratação</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #0984e3;
            --bg: #f0f4f8;
            --accent: {{ $cor_tema }};
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: #2d3436;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 8%;
            height: 70px;
            background: white;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .nav-group {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-item {
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            color: #636e72;
            padding: 25px 0;
            border-bottom: 3px solid transparent;
            transition: 0.3s;
        }

        .nav-item.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .logout-btn {
            background: #ff4757;
            color: white;
            border: none;
            padding: 9px 14px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .hero {
            padding: 110px 8% 50px;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            margin-bottom: 20px;
            border: 1px solid #e1e8ed;
        }

        .status-badge {
            padding: 12px 20px;
            border-radius: 12px;
            background: white;
            border-left: 6px solid var(--accent);
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .grid-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 25px 0;
        }

        .stat-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #edf2f7;
        }

        .stat-card small {
            display: block;
            color: #94a3b8;
            font-weight: 800;
            font-size: 0.65rem;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary);
        }

        .btn-action {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: none;
            background: #e2e8f0;
            color: #475569;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-action:hover {
            background: #cbd5e1;
        }

        .btns-volume {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }

        .btn-v {
            flex: 1;
            padding: 15px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            font-weight: 700;
            transition: 0.3s;
            color: #64748b;
        }

        .btn-v:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #f0f7ff;
        }

        .visual-side {
            position: sticky;
            top: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .report-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 800;
            color: #94a3b8;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #e1e8ed;
            background: #f8fafc;
            outline: none;
        }

        @media(max-width: 900px) {
            header {
                position: static;
                height: auto;
                padding: 20px;
                flex-direction: column;
                gap: 15px;
            }

            .nav-group {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .nav-item {
                padding: 8px 0;
            }

            .hero {
                padding: 30px 20px;
                grid-template-columns: 1fr;
            }

            .grid-stats {
                grid-template-columns: 1fr;
            }

            .visual-side {
                position: static;
            }
        }
    </style>
</head>

<body>

<header>
    <div style="font-weight: 800; color: var(--primary); font-size: 1.4rem; letter-spacing: -1px;">
        DRINK UP
        <span style="font-weight: 300; font-size: 0.8rem; color: #b2bec3;">CORPORATE</span>
    </div>

    <div class="nav-group">
        <div class="nav-item active" onclick="switchTab('main', this)">Visão Geral</div>
        <div class="nav-item" onclick="switchTab('reports', this)">Performance</div>
        <div class="nav-item" onclick="switchTab('history', this)">Histórico</div>
        <div class="nav-item" onclick="switchTab('rank', this)">Ranking</div>
        <div class="nav-item" onclick="switchTab('settings', this)">Configurações</div>

        <form method="POST" action="{{ route('sair') }}">
            @csrf
            <button class="logout-btn">Sair</button>
        </form>
    </div>
</header>

<section class="hero">
    <div class="main-content">

        <div id="tab-main" class="tab-content active">
            @if($tempo_inatividade_min > 120)
                <div style="background: #fff5f5; border: 1px solid #feb2b2; color: #c53030; padding: 15px; border-radius: 15px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600;">
                    🚨 <strong>Alerta de Saúde Ocupacional:</strong> Inatividade de hidratação superior a 2 horas detectada.
                </div>
            @endif

            @if(session('sucesso'))
                <div style="background: #f0fff4; border: 1px solid #9ae6b4; color: #2f855a; padding: 15px; border-radius: 15px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600;">
                    {{ session('sucesso') }}
                </div>
            @endif

            <div class="status-badge">
                <span class="indicator" style="background: var(--accent)"></span>
                {{ $status_msg }}
            </div>

            <h1 style="font-size: 2.2rem; font-weight: 800; letter-spacing: -1px;">Gestão de Ingestão</h1>
            <p style="color: #64748b; margin-bottom: 30px;">
                Análise em tempo real para <strong>{{ $usuario->nome }}</strong>
            </p>

            <div class="grid-stats">
                <div class="stat-card">
                    <small>OBJETIVO DIÁRIO</small>
                    <p>{{ number_format($meta_diaria_ml / 1000, 1, ',', '') }}L</p>
                </div>

                <div class="stat-card">
                    <small>ÁGUA BEBIDA HOJE</small>
                    <p>{{ number_format($total_ml / 1000, 1, ',', '') }}L</p>
                </div>

                <div class="stat-card">
                    <small>FALTA PARA A META</small>
                    <p>{{ number_format($falta_ml / 1000, 1, ',', '') }}L</p>
                </div>
            </div>

            <p style="color:#64748b; font-size:0.85rem; margin-top:-15px; margin-bottom:25px; font-weight:600;">
                Você já bebeu {{ number_format($eficiencia_percentual, 1, ',', '') }}% da meta.
                Falta {{ number_format($porcentagem_faltante, 1, ',', '') }}% para completar.
                Pessoas cadastradas: {{ $total_usuarios }}.
            </p>

            <div class="box">
                <h3 style="font-size: 0.9rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 1px;">
                    Registrar Aporte de Água
                </h3>

                <form method="POST" action="{{ route('agua.adicionar') }}" class="btns-volume">
                    @csrf
                    <button name="quantidade_ml" value="250" class="btn-v">250ml</button>
                    <button name="quantidade_ml" value="500" class="btn-v">500ml</button>
                    <button name="quantidade_ml" value="1000" class="btn-v">1.0L</button>
                </form>
            </div>

            <button onclick="document.getElementById('modal-meta').style.display='flex'" class="btn-action">
                Configurar Metas
            </button>
        </div>

        <div id="tab-reports" class="tab-content">
            <h2 style="font-size: 1.8rem; margin-bottom: 20px;">Relatório de Hidratação</h2>

            <div class="box">
                <div class="report-row">
                    <span>Média por Ingestão</span>
                    <strong>{{ $media_volume_ingestao }} ml</strong>
                </div>

                <div class="report-row">
                    <span>Ciclos Completados Hoje</span>
                    <strong>{{ $frequencia_hoje }} registros</strong>
                </div>

                <div class="report-row">
                    <span>Tempo desde a última hidratação</span>
                    <strong>{{ $tempo_inatividade_min }} min</strong>
                </div>

                <div style="margin-top: 25px;">
                    <h4 style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 15px;">
                        CURVA DE APORTE HORÁRIO
                    </h4>
                    <canvas id="chartPerformance" height="150"></canvas>
                </div>
            </div>
        </div>

        <div id="tab-history" class="tab-content">
            <h2 style="font-size: 1.8rem; margin-bottom: 20px;">Histórico de Consumo</h2>

            <div class="box" style="max-height: 400px; overflow-y: auto;">
                @forelse($historico as $h)
                    <div class="report-row">
                        <div>
                            <span style="font-weight: 800; color: var(--primary);">
                                {{ $h->quantidade_ml }}ml
                            </span>
                            <br>
                            <small style="color: #94a3b8;">
                                {{ \Carbon\Carbon::parse($h->data_registro)->format('d/m/Y - H:i') }}
                            </small>
                        </div>

                        <form method="POST" action="{{ route('agua.excluir') }}">
                            @csrf
                            <input type="hidden" name="id_registro" value="{{ $h->id }}">

                            <button type="submit" style="background: #ff4757; color: white; border: none; padding: 5px 10px; border-radius: 8px; cursor: pointer; font-size: 0.7rem; font-weight: 700;">
                                Excluir
                            </button>
                        </form>
                    </div>
                @empty
                    <p style="color: #94a3b8;">Nenhum registro encontrado.</p>
                @endforelse
            </div>
        </div>

        <div id="tab-rank" class="tab-content">
            <h2 style="font-size: 1.8rem; margin-bottom: 20px;">Líderes de Engajamento</h2>

            <div class="box" style="padding: 10px 25px;">
                @forelse(($ranking_engajamento ?? []) as $idx => $r)
                    <div class="report-row" style="{{ $idx == 0 ? 'color: var(--primary);' : '' }}">
                        <span style="display: flex; align-items: center; gap: 15px;">
                            <strong style="font-size: 1.2rem;">#{{ $idx + 1 }}</strong>
                            {{ data_get($r, 'usuario.nome') ?? data_get($r, 'nome') ?? 'Usuário' }}
                        </span>

                        <strong>
                            {{ number_format((data_get($r, 'volume_total') ?? 0) / 1000, 1) }} L
                        </strong>
                    </div>
                @empty
                    <p style="color: #94a3b8; padding: 20px 0;">Nenhum ranking disponível hoje.</p>
                @endforelse
            </div>
        </div>

        <div id="tab-settings" class="tab-content">
            <h2 style="font-size: 1.8rem; margin-bottom: 20px;">Configurações</h2>

            <div class="box">
                <form method="POST" action="{{ route('perfil.atualizar') }}">
                    @csrf

                    <div class="input-group">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" value="{{ $usuario->nome }}" required>
                    </div>

                    <div class="input-group">
                        <label>E-mail</label>
                        <input type="email" name="email" value="{{ $usuario->email }}" required>
                    </div>

                    <div class="input-group">
                        <label>Peso Atual em kg</label>
                        <input type="number" step="0.1" name="peso" value="{{ $peso_usuario }}" required>
                    </div>

                    <div class="input-group">
                        <label>Nova Senha deixe em branco para manter</label>
                        <input type="password" name="senha">
                    </div>

                    <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 20px;">
                        * Ao alterar seu peso, sua meta ideal será recalculada automaticamente.
                    </p>

                    <button type="submit" class="btn-action" style="background: var(--primary); color: white;">
                        Salvar Alterações
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="visual-side">
        <div style="width: 280px; height: 450px;">
            <svg viewBox="0 0 200 420">
                <defs>
                    <clipPath id="c">
                        <path d="M70 250 L55 400 Q55 415 80 415 L95 260 Z" />
                        <path d="M130 250 L145 400 Q145 415 120 415 L105 260 Z" />
                        <path d="M60 180 Q100 170 140 180 L145 255 Q100 265 55 255 Z" />
                        <path d="M55 100 Q100 90 145 100 L150 185 Q100 195 50 185 Z" />
                        <path d="M50 105 L20 220 Q15 230 30 230 L55 140 Z" />
                        <path d="M150 105 L180 220 Q185 230 170 230 L145 140 Z" />
                        <rect x="92" y="80" width="16" height="25" />
                        <circle cx="100" cy="50" r="35" />
                    </clipPath>

                    <linearGradient id="grad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="{{ $cor_tema }}" />
                        <stop offset="100%" stop-color="#74b9ff" />
                    </linearGradient>
                </defs>

                <g fill="#e2e8f0" stroke="#cbd5e1" stroke-width="2">
                    <path d="M70 250 L55 400 Q55 415 80 415 L95 260 Z" />
                    <path d="M130 250 L145 400 Q145 415 120 415 L105 260 Z" />
                    <path d="M60 180 Q100 170 140 180 L145 255 Q100 265 55 255 Z" />
                    <path d="M55 100 Q100 90 145 100 L150 185 Q100 195 50 185 Z" />
                    <path d="M50 105 L20 220 Q15 230 30 230 L55 140 Z" />
                    <path d="M150 105 L180 220 Q185 230 170 230 L145 140 Z" />
                    <rect x="92" y="80" width="16" height="25" />
                    <circle cx="100" cy="50" r="35" />
                </g>

                @php
                    $altura_agua = (min($eficiencia_percentual, 100) / 100) * 420;
                    $y_pos = 420 - $altura_agua;
                @endphp

                <rect
                    x="0"
                    y="{{ $y_pos }}"
                    width="200"
                    height="{{ $altura_agua }}"
                    fill="url(#grad)"
                    clip-path="url(#c)"
                    style="transition: 1.5s ease-in-out;"
                />
            </svg>

            <div style="text-align: center; font-weight: 800; font-size: 3rem; color: var(--accent); margin-top: -10px; letter-spacing: -2px;">
                {{ round($eficiencia_percentual) }}<span style="font-size: 1.5rem;">%</span>
            </div>
        </div>
    </div>
</section>

<div id="modal-meta" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 23, 42, 0.8); z-index:2000; justify-content:center; align-items:center; backdrop-filter:blur(8px);">
    <div style="background:white; padding:40px; border-radius:30px; width:90%; max-width:450px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
        <h2 style="margin-bottom: 10px; font-weight: 800;">Ajustar Parâmetros</h2>

        <p style="font-size:0.85rem; color:#64748b; margin-bottom:25px;">
            Recomendação baseada em biometria:
            <strong>{{ $meta_ideal_recomendada }}ml/dia</strong>
        </p>

        <form method="POST" action="{{ route('meta.atualizar') }}">
            @csrf

            <label style="display:block; text-align:left; font-size:0.7rem; font-weight:800; color:#94a3b8; margin-bottom:8px; text-transform:uppercase;">
                Meta Customizada em ml
            </label>

            <input
                type="number"
                name="nova_meta"
                value="{{ $meta_diaria_ml }}"
                style="width:100%; padding:18px; border:2px solid #f1f5f9; border-radius:16px; margin-bottom:25px; font-size:1.8rem; font-weight:800; text-align:center; outline:none; color:var(--primary);"
            >

            <div style="display:flex; gap:12px;">
                <button type="button" onclick="document.getElementById('modal-meta').style.display='none'" style="flex:1; padding:15px; border-radius:12px; border:none; background:#f1f5f9; font-weight:700; cursor:pointer;">
                    Cancelar
                </button>

                <button type="submit" style="flex:1; padding:15px; border-radius:12px; border:none; background:var(--primary); color:white; font-weight:700; cursor:pointer;">
                    Aplicar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(id, element) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.getElementById('tab-' + id).classList.add('active');

        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        element.classList.add('active');

        if (id === 'reports') {
            initChart();
        }
    }

    function initChart() {
        const ctx = document.getElementById('chartPerformance').getContext('2d');

        if (window.myChart) {
            window.myChart.destroy();
        }

        window.myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels_grafico),
                datasets: [{
                    label: 'Volume de Aporte',
                    data: @json($dados_grafico),
                    borderColor: '#0984e3',
                    backgroundColor: 'rgba(9, 132, 227, 0.05)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
</script>

</body>
</html>