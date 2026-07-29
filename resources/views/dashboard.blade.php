<!DOCTYPE html> 
<html lang="pt-br"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Drink Up | Gestão de Hidratação</title> 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 

    <style> 
        :root { 
            --primary: #0984e3; 
            --primary-dark: #0674ca; 
            --secondary: #17c8ce; 
            --danger: #ff4757; 
            --success: #10b981; 
            --warning: #ffa502; 
            --text: #1e293b; 
            --muted: #64748b; 
            --border: #e2e8f0; 
            --card: rgba(255, 255, 255, 0.96); 
            --accent: {{ $cor_tema }}; 
        } 

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Inter', sans-serif;
        } 

        html {scroll-behavior: smooth;} 

        body { 
            min-height: 100vh; 
            color: var(--text); 
            background: 
                radial-gradient( 
                    circle at 15% 15%, 
                    rgba(255, 255, 255, 0.14), 
                    transparent 28% 
                ), 

                radial-gradient( 
                    circle at 85% 30%, 
                    rgba(255, 255, 255, 0.12), 
                    transparent 30% 
                ),

                linear-gradient( 
                    135deg, 
                    #168bd9 0%, 
                    #12a9dc 45%, 
                    #17d0cb 100%
                );

            background-attachment: fixed;} 

        button, 

        input { font-family: inherit;}

        button {border: none;} 

        header { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%;
            min-height: 70px; 
            display: flex; 
            justify-content: space-between;
            align-items: center; 
            padding: 0 7%; 
            background: rgba(255, 255, 255, 0.97); 
            backdrop-filter: blur(18px); 
            box-shadow: 0 3px 20px rgba(15, 23, 42, 0.08); 
            z-index: 1000; 
        } 

        .brand { 
            display: flex; 
            align-items: baseline; 
            gap: 5px; 
            white-space: nowrap; 
        } 

        .brand strong { 
            color: var(--primary); 
            font-size: 1.4rem; 
            font-weight: 800; 
            letter-spacing: -1px; 
        } 

        .brand span { 
            color: #a5afb9; 
            font-size: 0.72rem; 
            font-weight: 500; 
        } 

        .nav-group { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        } 

        .nav-item { 
            position: relative; 
            padding: 26px 14px 23px; 
            color: #5f6b73; 
            font-size: 0.86rem; 
            font-weight: 700; 
            cursor: pointer; 
            white-space: nowrap; 
            transition: 0.25s; 
        } 

        .nav-item::after { 
            content: ''; 
            position: absolute; 
            left: 14px; 
            right: 14px; 
            bottom: 0; 
            height: 3px; 
            border-radius: 5px 5px 0 0; 
            background: var(--primary); 
            transform: scaleX(0); 
            transition: 0.25s; 
        } 

        .nav-item:hover, 

        .nav-item.active { 
            color: var(--primary); 
        } 

        .nav-item.active::after { 
            transform: scaleX(1); 
        } 

        .logout-btn { 
            padding: 10px 17px; 
            border-radius: 11px; 
            background: var(--danger); 
            color: white; 
            font-weight: 800; 
            cursor: pointer; 
            box-shadow: 0 8px 20px rgba(255, 71, 87, 0.22); 
            transition: 0.25s; 
        } 

        .logout-btn:hover { 
            transform: translateY(-1px); 
            background: #e83f4f; 
        } 

        .page-container { 
            width: min(1580px, 88%); 
            margin: 0 auto; 
            padding: 110px 0 55px; 
        } 

        .tab-content { 
            display: none; 
            animation: fadeIn 0.35s ease; 
        } 

        .tab-content.active { 
            display: block; 
        } 

        @keyframes fadeIn { 
            from { 
                opacity: 0; 
                transform: translateY(12px); 
            } 

            to { 
                opacity: 1; 
                transform: translateY(0); 
            } 
        } 

        .page-heading { 
            margin-bottom: 25px; 
        } 
 
        .page-heading h1, 

        .page-heading h2 { 
            color: white; 
            font-size: clamp(1.9rem, 3vw, 2.6rem); 
            font-weight: 800; 
            letter-spacing: -1px; 
            text-shadow: 0 5px 18px rgba(0, 70, 130, 0.22); 
        } 

        .page-heading p { 
            margin-top: 8px; 
            color: rgba(255, 255, 255, 0.92); 
            font-size: 0.92rem; 
            font-weight: 500; 
        } 

        .dashboard-grid { 
            display: grid; 
            grid-template-columns: minmax(0, 1.25fr) minmax(380px, 0.75fr); 
            gap: 30px; 
            align-items: start; 
        } 

        .main-column, 
        .visual-column { 
            min-width: 0; 
        } 

        .box { 
            padding: 26px; 
            margin-bottom: 22px; 
            border: 1px solid rgba(255, 255, 255, 0.55); 
            border-radius: 24px; 
            background: var(--card); 
            backdrop-filter: blur(15px); 
            box-shadow: 
                0 18px 45px rgba(0, 72, 135, 0.17), 
                inset 0 1px 0 rgba(255, 255, 255, 0.75); 
        } 

        .box-title { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            margin-bottom: 22px; 
        } 

        .box-title-icon { 
            width: 44px; 
            height: 44px; 
            display: grid; 
            place-items: center; 
            border-radius: 14px; 
            color: var(--primary); 
            background: #e9f4ff; 
            font-size: 1.2rem; 
            font-weight: 800; 
        } 

        .box-title h3 { 
            color: #334155; 
            font-size: 1rem; 
            font-weight: 800; 
        } 

        .box-title p { 
            margin-top: 4px; 
            color: #94a3b8; 
            font-size: 0.75rem; 
        } 

        .status-badge { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            margin-bottom: 22px; 
            padding: 13px 19px; 
            border-left: 6px solid var(--accent); 
            border-radius: 13px; 
            background: rgba(255, 255, 255, 0.96); 
            color: var(--accent); 
            font-size: 0.88rem; 
            font-weight: 800; 
            box-shadow: 0 10px 25px rgba(0, 70, 130, 0.12); 
        } 

        .status-dot { 
            width: 9px; 
            height: 9px; 
            border-radius: 50%; 
            background: var(--accent); 
        } 

        .alert { 
            margin-bottom: 20px;
            padding: 15px 17px; 
            border-radius: 14px; 
            font-size: 0.85rem; 
            font-weight: 700; 
        } 

        .alert-success { 
            color: #087444; 
            background: #f0fff4; 
            border: 1px solid #9ae6b4; 
        } 

        .alert-danger { 
            color: #c53030; 
            background: #fff5f5; 
            border: 1px solid #feb2b2; 
        } 

        .grid-stats { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 15px; 
        } 

        .stat-card { 
            padding: 21px; 
            border: 1px solid #edf2f7;
            border-radius: 17px; 
            background: #f8fafc; 
            text-align: center; 
        } 

        .stat-card small { 
            display: block; 
            margin-bottom: 7px; 
            color: #94a3b8; 
            font-size: 0.65rem; 
            font-weight: 800; 
            letter-spacing: 0.8px; 
        } 

        .stat-card strong { 
            color: var(--primary); 
            font-size: 1.35rem; 
            font-weight: 800; 
        } 

        .progress-info { 
            margin-top: 18px; 
            padding: 15px 17px; 
            border: 1px solid #e5edf6; 
            border-radius: 15px; 
            background: #f8fbff; 
        } 

        .progress-info p { 
            color: var(--muted); 
            font-size: 0.82rem; 
            font-weight: 600; 
            line-height: 1.6; 
        } 

        .progress-track { 
            width: 100%; 
            height: 11px; 
            margin-top: 12px; 
            overflow: hidden; 
            border-radius: 999px; 
            background: #e3ebf5; 
        } 

        .progress-fill { 
            width: {{ min(max($eficiencia_percentual, 0), 100) }}%; 
            height: 100%; 
            border-radius: inherit; 
            background: linear-gradient( 90deg, #0984e3, #18cbd0,); 
            transition: width 0.8s ease; 
        } 

        .input-group { 
            margin-bottom: 15px; 
        } 

        .input-group label { 
            display: block; 
            margin-bottom: 7px; 
            color: #94a3b8; 
            font-size: 0.72rem; 
            font-weight: 800; 
            text-transform: uppercase; 
        } 

        .input-group input { 
            width: 100%; 
            height: 50px; 
            padding: 0 15px; 
            border: 1px solid var(--border); 
            border-radius: 13px; 
            background: #f8fafc; 
            color: #334155; 
            font-size: 0.95rem; 
            font-weight: 600; 
            outline: none; 
            transition: 0.25s; 
        } 

        .input-group input:focus { 
            border-color: var(--primary); 
            background: white; 
            box-shadow: 0 0 0 4px rgba(9, 132, 227, 0.09); 
        } 

        .btn-action { 
            width: 100%;
            min-height: 48px; 
            padding: 13px 18px; 
            border-radius: 13px; 
            background: #e2e8f0; 
            color: #475569; 
            font-weight: 800; 
            cursor: pointer; 
            transition: 0.25s; 
        } 

        .btn-action:hover { 
            background: #cbd5e1; 
        } 

        .btn-primary { 
            background: var(--primary); 
            color: white; 
        } 

        .btn-primary:hover { 
            background: var(--primary-dark); 
        } 

        .btns-volume { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 11px; 
            margin-top: 17px; 
        } 

        .btn-v { 
            width: 100%; 
            padding: 15px; 
            border: 1px solid #dce6f0; 
            border-radius: 14px; 
            background: white; 
            color: #64748b; 
            font-weight: 800; 
            cursor: pointer; 
            transition: 0.25s; 
        } 

        .btn-v:hover { 
            border-color: var(--primary); 
            background: #f0f7ff; 
            color: var(--primary); 
            transform: translateY(-1px); 
        } 

        .visual-card { 
            position: sticky;
            top: 95px; 
            min-height: 680px; 
            overflow: hidden; 
            padding: 24px; 
            text-align: center; 
            background: 
                radial-gradient( 
                    circle at 50% 20%, 
                    #ffffff 0%, 
                    #f5fbff 48%, 
                    #eaf5ff 100% 
                ); 
        } 

        .character-stage { 
            position: relative; 
            width: 100%; 
            max-width: 420px;
            height: 530px; 
            margin: 0 auto; 
        } 

        .character-stage svg { 
            width: 100%; 
            height: 100%; 
            overflow: visible; 
        } 

        .percentage-display { 
            position: relative; 
            z-index: 4; 
            margin-top: -20px; 
        } 

        .percentage-display strong { 
            color: var(--accent); 
            font-size: 3.8rem; 
            font-weight: 800; 
            letter-spacing: -3px; 
        } 

        .percentage-display strong span { 
            font-size: 1.65rem; 
            letter-spacing: 0; 
        } 

        .percentage-display p { 
            margin-top: 3px; 
            color: var(--muted); 
            font-size: 0.85rem; 
            font-weight: 700; 
        } 

        .metric-list { 
            display: flex; 
            flex-direction: column; 
        } 

        .metric-row { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            gap: 20px; 
            padding: 17px 0; 
            border-bottom: 1px solid #edf2f7; 
        } 

        .metric-row:last-child { 
            border-bottom: none; 
        } 

        .metric-row span { 
            color: #334155; 
            font-size: 0.88rem; 
            font-weight: 600;
        } 

        .metric-row strong { 
            color: #1e293b; 
            font-size: 0.95rem; 
            font-weight: 800; 
            text-align: right; 
        } 

        .chart-header { 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            gap: 15px; 
            margin-bottom: 15px; 
        } 

        .chart-header h3 { 
            color: #334155; 
            font-size: 0.95rem; 
            font-weight: 800; 
        } 

        .chart-header span { 
            padding: 8px 12px; 
            border-radius: 10px; 
            color: var(--primary); 
            background: #eaf4ff; 
            font-size: 0.72rem; 
            font-weight: 800; 
        } 

        .chart-container { 
            position: relative; 
            width: 100%; 
            height: 370px; 
        } 

        .chart-summary { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 11px; 
            margin-top: 18px; 
        } 

        .chart-mini-card { 
            padding: 13px; 
            border: 1px solid #e7eef5; 
            border-radius: 13px; 
            background: #f8fafc;
        }

        .chart-mini-card strong { 
            display: block; 
            color: var(--primary); 
            font-size: 1rem; 
            font-weight: 800; 
        }

        .chart-mini-card span { 
            display: block; 
            margin-top: 4px; 
            color: #94a3b8; 
            font-size: 0.63rem; 
        } 

        .history-list { 
            max-height: 480px; 
            overflow-y: auto; 
        } 

        .history-row, 
        .ranking-row { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            gap: 18px; 
            padding: 15px 0;  
            border-bottom: 1px solid #edf2f7; 
        } 

        .history-row:last-child, 
        .ranking-row:last-child { 
            border-bottom: none; 
        } 

        .history-volume { 
            color: var(--primary); 
            font-weight: 800; 
        } 

        .history-date { 
            margin-top: 4px;  
            color: #94a3b8; 
            font-size: 0.72rem; 
        } 

        .delete-btn { 
            padding: 7px 11px; 
            border-radius: 9px; 
            background: #fff1f2; 
            color: var(--danger); 
            font-size: 0.72rem; 
            font-weight: 800; 
            cursor: pointer; 
        } 

        .settings-grid { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            gap: 15px; 
        } 

        .settings-grid .full { 
            grid-column: 1 / -1; 
        } 

        .modal-overlay { 
            display: none; 
            position: fixed; 
            inset: 0; 
            align-items: center; 
            justify-content: center; 
            padding: 20px; 
            background: rgba(15, 23, 42, 0.75); 
            backdrop-filter: blur(7px); 
            z-index: 2500; 
        } 

        .modal-card {
            width: min(450px, 100%); 
            padding: 34px; 
            border-radius: 25px; 
            background: white; 
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.30); 
        } 

        .modal-card h2 { 
            color: #1e293b; 
            font-size: 1.4rem; 
            font-weight: 800; 
        } 

        .modal-card p { 
            margin: 8px 0 20px; 
            color: var(--muted); 
            font-size: 0.83rem; 
            line-height: 1.6; 
        } 

        .modal-actions { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 11px; 
        } 

        @media(max-width: 1100px) { 
            header { 
                padding: 0 3%; 
            } 

            .nav-group { 
                gap: 0; 
            } 

            .nav-item { 
                padding-left: 9px; 
                padding-right: 9px; 
            } 

            .dashboard-grid { 
                grid-template-columns: 1fr; 
            }

            .visual-card { 
                position: relative; 
                top: auto; 
            } 
        } 

        @media(max-width: 820px) { 
            header { 
                position: relative; 
                height: auto; 
                padding: 18px; 
                flex-direction: column; 
                gap: 12px; 
            } 

            .nav-group { 
                flex-wrap: wrap; 
                justify-content: center; 
            } 
 
            .nav-item { 
                padding: 8px; 
            } 

            .nav-item::after { 
                display: none; 
            }

            .page-container { 
                width: 93%; 
                padding-top: 35px; 
            }

            .grid-stats, 
            .chart-summary { 
                grid-template-columns: 1fr; 
            } 
        } 
        @media(max-width: 600px) { 
            .btns-volume, 
            .settings-grid { 
                grid-template-columns: 1fr; 
            } 
            .settings-grid .full { 
                grid-column: auto; 
            } 
            .metric-row, 
            .history-row, 
            .ranking-row { 
                align-items: flex-start; 
                flex-direction: column; 
            } 

            .metric-row strong { 
                text-align: left; 
            } 

            .character-stage { 
                height: 450px; 
            } 

            .chart-container { 
                height: 310px; 
            } 
        } 
    </style> 
</head> 

<body> 

@php 
    /*|-------------------------------------------------------------------------- | Registros usados pelo gráfico |-------------------------------------------------------------------------- | O gráfico é montado diretamente a partir do histórico do usuário.| Todos os registros de hoje são considerados, de 00h até 23h.*/ 

    $registrosGrafico = collect($historico ?? []) 
        ->filter(function ($registro) { 
            return \Carbon\Carbon::parse( 
                $registro->data_registro 
            )->isToday(); 
        }) 
        ->map(function ($registro) { 
            return [ 
                'hora' => (int) \Carbon\Carbon::parse( 
                    $registro->data_registro 
                )->format('H'), 
                'quantidade' => (int) $registro->quantidade_ml, 
            ]; 
        }) 
        ->values(); 

    /*|-------------------------------------------------------------------------- | Nível de água do boneco |-------------------------------------------------------------------------- */

    $nivelBoneco = min( 
        max($eficiencia_percentual, 0), 
        100 
    ); 
    $alturaAgua = 420 * ($nivelBoneco / 100); 
    $posicaoAgua = 420 - $alturaAgua; 
@endphp 

<header> 
    <div class="brand"> 
        <strong>DRINK UP</strong> 
        <span>CORPORATE</span> 
    </div> 

    <div class="nav-group"> 
        <div class="nav-item active" onclick="switchTab('main', this)" > Visão Geral </div> 

        <div class="nav-item" onclick="switchTab('reports', this)"> Performance </div> 

        <div class="nav-item" onclick="switchTab('history', this)"> Histórico </div> 

        <div class="nav-item" onclick="switchTab('rank', this)" > Ranking </div> 

        <div class="nav-item" onclick="switchTab('settings', this)"> Configurações </div> 

        <form method="POST" action="{{ route('sair') }}"> 

            @csrf 

            <button type="submit" class="logout-btn"> Sair </button> 
        </form> 
    </div> 
</header> 

<main class="page-container"> 

    @if(session('sucesso')) 
        <div class="alert alert-success"> 
            {{ session('sucesso') }} 
        </div> 
    @endif 

    @if($errors->any()) 
        <div class="alert alert-danger"> 
            @foreach($errors->all() as $erro) 
                <div>{{ $erro }}</div> 
            @endforeach 
        </div> 
    @endif 

    <!-- VISÃO GERAL --> 

    <section 
        id="tab-main" 
        class="tab-content active" 
    > 
        <div class="dashboard-grid"> 
            <div class="main-column"> 
                @if($tempo_inatividade_min > 120) 
                    <div class="alert alert-danger"> 
                        <strong>Alerta de hidratação:</strong> 
                        você está há mais de duas horas sem registrar água. 
                    </div> 

                @endif 

                <div class="status-badge"> 
                    <span class="status-dot"></span> 
                    {{ $status_msg }} 
                </div> 

                <div class="page-heading">
                    <h1>Gestão de Ingestão</h1> 
                    <p>
                        Análise em tempo real para 
                        <strong>{{ $usuario->nome }}</strong>. 
                    </p> 
                </div>

                <div class="box"> 
                    <div class="box-title"> 
                        <div class="box-title-icon">💧</div> 
                        <div>
                            <h3>Resumo da hidratação</h3> 
                            <p> 
                                Acompanhamento do consumo diário. 
                            </p> 
                        </div> 
                    </div> 

                    <div class="grid-stats"> 
                        <div class="stat-card"> 
                            <small>OBJETIVO DIÁRIO</small> 
                            <strong> 
                                {{ 
                                    number_format( 
                                        $meta_diaria_ml / 1000, 
                                        1, 
                                        ',', 
                                        '.' 
                                    ) 
                                }} 
                                L 
                            </strong> 
                        </div> 

                        <div class="stat-card"> 
                            <small>ÁGUA BEBIDA HOJE</small> 
                            <strong> 
                                {{ 
                                    number_format( 
                                        $total_ml / 1000, 
                                        1, 
                                        ',', 
                                        '.' 
                                    ) 
                                }} 
                                L 
                            </strong> 
                        </div> 

                        <div class="stat-card"> 
                            <small>FALTA PARA A META</small> 
                            <strong> 
                                {{ 
                                    number_format( 
                                        $falta_ml / 1000, 
                                        1, 
                                        ',', 
                                        '.' 
                                    ) 
                                }} 
                                L 
                            </strong> 
                        </div> 
                    </div> 

                    <div class="progress-info"> 
                        <p> 
                            Você já bebeu 
                            <strong> 
                                {{ 
                                    number_format( 
                                        $eficiencia_percentual, 
                                        1,
                                        ',', 
                                        '.' 
                                    ) 
                                }}% 
                            </strong> 
                            da meta. Faltam 
                            <strong> 
                                {{
                                    number_format( 
                                        $porcentagem_faltante, 
                                        1, 
                                        ',', 
                                        '.' 
                                    ) 
                                }}% 
                            </strong> 
                            para completar. 
                        </p> 
                        <div class="progress-track"> 
                            <div class="progress-fill"></div> 
                        </div> 
                    </div> 
                </div> 

                <div class="box"> 
                    <div class="box-title"> 
                        <div class="box-title-icon">＋</div> 

                        <div> 
                            <h3>Registrar aporte de água</h3> 
                            <p> Digite uma quantidade ou escolha uma opção rápida.</p> 
                        </div> 
                    </div> 

                    <form
                        method="POST" 
                        action="{{ route('agua.adicionar') }}" 
                    > 
                        @csrf 

                        <div class="input-group"> 
                            <label for="quantidade_ml"> 
                                Quantidade de água em ml 
                            </label> 

                            <input 
                                id="quantidade_ml" 
                                type="number" 
                                name="quantidade_ml" 
                                min="1" 
                                max="10000" 
                                step="1" 
                                value="{{ old('quantidade_ml') }}" 
                                placeholder="Exemplo: 350" 
                                required
                            > 
                        </div> 

                        <button 
                            type="submit" 
                            class="btn-action btn-primary" 
                        > 
                            Adicionar quantidade personalizada 
                        </button> 
                    </form> 

                    <div class="btns-volume"> 
                        <form 
                            method="POST" 
                            action="{{ route('agua.adicionar') }}"
                        > 
                            @csrf 

                            <input 
                                type="hidden" 
                                name="quantidade_ml" 
                                value="250" 
                            > 

                            <button 
                                type="submit" 
                                class="btn-v" 
                            >
                                250 ml 
                            </button> 
                        </form> 

                        <form
                            method="POST" 
                            action="{{ route('agua.adicionar') }}" 
                        > 

                            @csrf 

                            <input
                                type="hidden" 
                                name="quantidade_ml" 
                                value="500" 
                            > 

                            <button 
                                type="submit" 
                                class="btn-v" 
                            >
                                500 ml
                            </button> 
                        </form> 

                        <form
                            method="POST" 
                            action="{{ route('agua.adicionar') }}"
                        > 

                            @csrf 

                            <input 
                                type="hidden" 
                                name="quantidade_ml" 
                                value="700" 
                            > 

                            <button 
                                type="submit" 
                                class="btn-v" 
                            > 
                                700 ml 
                            </button> 
                        </form> 
                    </div> 
                </div> 

                <button 
                    type="button" 
                    class="btn-action" 
                    onclick="abrirModalMeta()" 
                > 
                    Configurar metas 
                </button> 
            </div> 

            <div class="visual-column"> 
                <div class="box visual-card"> 

                    <!-- BONECO ANTIGO --> 

                    <div class="character-stage"> 
                        <svg viewBox="0 0 200 420"> 
                            <defs> 
                                <clipPath id="bonecoClip"> 
                                    <path d="M70 250 L55 400 Q55 415 80 415 L95 260 Z"/> 

                                    <path d="M130 250 L145 400 Q145 415 120 415 L105 260 Z"/> 

                                    <path d="M60 180 Q100 170 140 180 L145 255 Q100 265 55 255 Z"/> 

                                    <path d="M55 100 Q100 90 145 100 L150 185 Q100 195 50 185 Z"/> 

                                    <path d="M50 105 L20 220 Q15 230 30 230 L55 140 Z"/> 

                                    <path d="M150 105 L180 220 Q185 230 170 230 L145 140 Z"/> 

                                    <rect 
                                        x="92" 
                                        y="80" 
                                        width="16" 
                                        height="25" 
                                    /> 

                                    <circle 
                                        cx="100" 
                                        cy="50" 
                                        r="35" 
                                    /> 
                                </clipPath> 

                                <linearGradient 
                                  id="aguaBoneco" 
                                    x1="0" 
                                    y1="0" 
                                    x2="0" 
                                    y2="1" 
                                >
                                    <stop 
                                        offset="0%" 
                                        stop-color="{{ $cor_tema }}" 
                                    /> 

                                    <stop 
                                        offset="100%" 
                                        stop-color="#74b9ff" 
                                    /> 
                                </linearGradient> 
                            </defs> 

                            <g 
                                fill="#e2e8f0" 
                                stroke="#c5d2df" 
                                stroke-width="2" 
                            > 

                                <path d="M70 250 L55 400 Q55 415 80 415 L95 260 Z"/> 

                                <path d="M130 250 L145 400 Q145 415 120 415 L105 260 Z"/> 

                                <path d="M60 180 Q100 170 140 180 L145 255 Q100 265 55 255 Z"/> 

                                <path d="M55 100 Q100 90 145 100 L150 185 Q100 195 50 185 Z"/> 

                                <path d="M50 105 L20 220 Q15 230 30 230 L55 140 Z"/> 

                                <path d="M150 105 L180 220 Q185 230 170 230 L145 140 Z"/> 

                                <rect 
                                    x="92" 
                                    y="80" 
                                    width="16" 
                                    height="25"
                                />

                                <circle 
                                    cx="100" 
                                    cy="50" 
                                    r="35"
                                /> 
                            </g> 

                            <rect
                                x="0" 
                                y="{{ $posicaoAgua }}" 
                                width="200" 
                                height="{{ $alturaAgua }}" 
                                fill="url(#aguaBoneco)" 
                                clip-path="url(#bonecoClip)" 
                                style="transition: 1.2s ease-in-out;"/> 
                        </svg> 
                    </div> 

                    <div class="percentage-display"> 
                        <strong> 
                            {{ round($eficiencia_percentual) }} 

                            <span>%</span> 
                        </strong> 

                        <p>da meta diária alcançada</p> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </section> 

    <!-- PERFORMANCE --> 

    <section 
        id="tab-reports" 
        class="tab-content">

        <div class="page-heading"> 
            <h2>Relatório de Hidratação</h2> 

            <p> 
                Acompanhe os registros realizados ao longo do dia. 
            </p> 
        </div> 

        <div class="dashboard-grid"> 
            <div class="main-column"> 
                <div class="box"> 
                    <div class="metric-list"> 
                        <div class="metric-row"> 
                            <span>Média por ingestão</span> 

                            <strong> 
                                {{ $media_volume_ingestao }} ml 
                            </strong> 
                        </div> 

                        <div class="metric-row"> 
                            <span>Ciclos completados hoje</span> 

                            <strong> 
                                {{ $frequencia_hoje }} 

                                {{ 
                                    $frequencia_hoje === 1 
                                        ? 'registro' 
                                        : 'registros' 
                                }} 
                            </strong> 
                        </div> 

                        <div class="metric-row"> 
                            <span>Tempo desde a última hidratação</span> 

                            <strong> 
                                {{ $tempo_inatividade_formatado }} 
                            </strong> 
                        </div> 
                    </div> 
                </div> 

                <div class="box"> 
                    <div class="chart-header"> 
                        <h3>Aporte de água por horário</h3> 
                        <span>Registros de hoje</span> 
                    </div> 

                    <div class="chart-container"> 
                        <canvas id="chartPerformance"></canvas> 
                    </div> 

                    <div class="chart-summary"> 
                        <div class="chart-mini-card"> 
                            <strong>{{ $total_ml }} ml</strong> 
                            <span>Total consumido hoje</span> 
                        </div> 

                        <div class="chart-mini-card"> 
                            <strong>{{ $frequencia_hoje }}</strong> 
                            <span>Registros realizados</span> 
                        </div> 

                        <div class="chart-mini-card"> 
                            <strong id="horarioPico"> 
                                Sem dados 
                            </strong> 
                            <span>Horário de maior consumo</span> 
                        </div> 
                    </div> 
                </div> 
            </div> 

            <div class="visual-column"> 
                <div class="box visual-card"> 
                    <div class="character-stage"> 
                        <svg viewBox="0 0 200 420"> 
                            <defs> 
                                <clipPath id="bonecoPerformanceClip"> 
                                    <path d="M70 250 L55 400 Q55 415 80 415 L95 260 Z"/> 

                                    <path d="M130 250 L145 400 Q145 415 120 415 L105 260 Z"/> 

                                    <path d="M60 180 Q100 170 140 180 L145 255 Q100 265 55 255 Z"/>
                                    
                                    <path d="M55 100 Q100 90 145 100 L150 185 Q100 195 50 185 Z"/> 

                                    <path d="M50 105 L20 220 Q15 230 30 230 L55 140 Z"/> 

                                    <path 
                                        d="M150 105 L180 220 Q185 230 170 230 L145 140 Z" 
                                    /> 

                                    <rect 
                                        x="92" 
                                        y="80" 
                                        width="16" 
                                        height="25" 
                                    /> 

                                    <circle
                                        cx="100" 
                                        cy="50" 
                                        r="35" 
                                    /> 
                                </clipPath> 

                                <linearGradient 
                                    id="aguaPerformance" 
                                    x1="0" 
                                    y1="0" 
                                    x2="0" 
                                    y2="1" 
                                > 
                                    <stop 
                                        offset="0%" 
                                        stop-color="{{ $cor_tema }}" 
                                    /> 

                                    <stop 
                                        offset="100%" 
                                        stop-color="#74b9ff" 
                                    /> 
                                </linearGradient> 
                            </defs> 

                            <g 
                                fill="#e2e8f0" 
                                stroke="#c5d2df" 
                                stroke-width="2" 
                            > 
                                <path d="M70 250 L55 400 Q55 415 80 415 L95 260 Z"/> 

                                <path d="M130 250 L145 400 Q145 415 120 415 L105 260 Z"/> 
 
                                <path d="M60 180 Q100 170 140 180 L145 255 Q100 265 55 255 Z"/> 

                                <path d="M55 100 Q100 90 145 100 L150 185 Q100 195 50 185 Z"/> 

                                <path d="M50 105 L20 220 Q15 230 30 230 L55 140 Z"/> 

                                <path d="M150 105 L180 220 Q185 230 170 230 L145 140 Z" /> 
 
                                <rect 
                                    x="92" 
                                    y="80" 
                                    width="16" 
                                    height="25" 
                                /> 
 
                                <circle 
                                    cx="100" 
                                    cy="50" 
                                    r="35" 
                                /> 

                            </g> 

                            <rect 
                                x="0" 
                                y="{{ $posicaoAgua }}" 
                                width="200" 
                                height="{{ $alturaAgua }}" 
                                fill="url(#aguaPerformance)" 
                                clip-path="url(#bonecoPerformanceClip)" 
                                style="transition: 1.2s ease-in-out;" 
                            /> 
                        </svg> 
                    </div> 

                    <div class="percentage-display"> 
                        <strong> 
                            {{ round($eficiencia_percentual) }} 
                            <span>%</span> 
                        </strong> 

                        <p>da meta diária alcançada</p> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </section> 
 
    <!-- HISTÓRICO --> 

    <section 
        id="tab-history" 
        class="tab-content">

        <div class="page-heading"> 
            <h2>Histórico de Consumo</h2> 

            <p> 
                Todos os registros de água do usuário. 
            </p> 
        </div> 

        <div class="box"> 
            <div class="history-list"> 
                @forelse($historico as $registro) 
                    <div class="history-row"> 
                        <div> 
                            <div class="history-volume"> 
                                {{ $registro->quantidade_ml }} ml 
                            </div> 

                            <div class="history-date"> 
                                {{ 
                                    \Carbon\Carbon::parse( 
                                        $registro->data_registro 
                                    )->format('d/m/Y - H:i') 
                                }} 
                            </div> 
                        </div> 

                        <form 
                            method="POST" 
                            action="{{ route('agua.excluir') }}" 
                            onsubmit="return confirm('Deseja excluir este registro?');"> 
                            
                            @csrf 

                            <input 
                                type="hidden" 
                                name="id_registro" 
                                value="{{ $registro->id }}"> 

                            <button 
                                type="submit" 
                                class="delete-btn"> 
                                Excluir 
                            </button> 
                        </form> 
                    </div> 
                @empty 
                    <p style="color: #94a3b8;"> 
                        Nenhum registro encontrado. 
                    </p> 
                @endforelse 
            </div> 
        </div> 
    </section> 

    <!-- RANKING --> 

    <section
        id="tab-rank" 
        class="tab-content">
        <div class="page-heading"> 
            <h2>Ranking de Hidratação</h2> 
            <p> 
                Usuários com maior consumo registrado hoje. 
            </p> 
        </div>

        <div class="box"> 
            @forelse(($ranking_engajamento ?? []) as $indice => $ranking) 
                <div class="ranking-row"> 
                    <span> 
                        <strong style="color: var(--primary);"> 
                            #{{ $indice + 1 }} 
                        </strong> 

                        &nbsp; 

                        {{ 
                            data_get($ranking, 'usuario.nome') 
                            ?? data_get($ranking, 'nome') 
                            ?? 'Usuário' 
                        }} 
                    </span> 

                    <strong> 
                        {{ 
                            number_format( 
                                ( 
                                    data_get( 
                                        $ranking, 
                                        'volume_total' 
                                    ) ?? 0 
                                ) / 1000, 
                                1, 
                                ',', 
                                '.' 
                            ) 
                        }} 
                        L 
                    </strong> 
                </div> 
            @empty 
                <p style="color: #94a3b8;"> 
                    Nenhum ranking disponível hoje. 
                </p> 
            @endforelse 
        </div> 
    </section> 

    <!-- CONFIGURAÇÕES --> 

    <section 
        id="tab-settings" 
        class="tab-content" 
    >
        <div class="page-heading"> 
            <h2>Configurações</h2> 
            <p>
                Atualize as informações do seu perfil. 
            </p> 
        </div>

        <div class="box"> 
            <form 
                method="POST" 
                action="{{ route('perfil.atualizar') }}">

                @csrf

                <div class="settings-grid"> 
                    <div class="input-group"> 
                        <label for="nome"> 
                            Nome completo 
                        </label> 
                        <input 
                            id="nome" 
                            type="text" 
                            name="nome" 
                            value="{{ old('nome', $usuario->nome) }}" 
                            required>
                    </div> 

                    <div class="input-group"> 
                        <label for="email"> 
                            E-mail 
                        </label> 
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $usuario->email) }}" 
                            required> 
                    </div> 

                    <div class="input-group"> 
                        <label for="peso"> 
                            Peso atual em kg 
                        </label> 
                        <input 
                            id="peso" 
                            type="number" 
                            name="peso" 
                            min="20" 
                            max="300" 
                            step="0.1" 
                            value="{{ old('peso', $peso_usuario) }}" 
                            required> 
                    </div> 

                    <div class="input-group"> 
                        <label for="senha"> 
                            Nova senha 
                        </label> 
                        <input
                            id="senha" 
                            type="password" 
                            name="senha" 
                            placeholder="Deixe vazio para manter"> 
                    </div> 

                    <div class="full"> 
                        <button
                            type="submit" 
                            class="btn-action btn-primary" > 
                            Salvar alterações 
                        </button> 
                    </div> 
                </div> 
            </form> 
        </div> 
    </section> 
</main> 

<!-- MODAL DE META --> 

<div 
    id="modal-meta" 
    class="modal-overlay" 
    onclick="fecharModalFora(event)" > 

    <div class="modal-card"> 
        <h2>Ajustar meta diária</h2> 
        <p>
            A recomendação baseada no seu peso é de 
            <strong> 
                {{ $meta_ideal_recomendada }} ml por dia. 
            </strong> 
        </p> 

        <form
            method="POST" 
            action="{{ route('meta.atualizar') }}" 
            > 
            @csrf 

            <div class="input-group"> 
                <label for="nova_meta"> 
                    Nova meta em ml 
                </label> 

                <input 
                    id="nova_meta" 
                    type="number" 
                    name="nova_meta" 
                    min="500" 
                    max="10000" 
                    value="{{ old('nova_meta', $meta_diaria_ml) }}" 
                    required 
                > 
            </div> 

            <div class="modal-actions"> 

                <button 
                    type="button" 
                    class="btn-action" 
                    onclick="fecharModalMeta()" 
                > 
                    Cancelar 
                </button> 

                <button 
                    type="submit" 
                    class="btn-action btn-primary" 
                > 
                    Salvar meta 
                </button> 
            </div> 
        </form> 
    </div> 
</div> 

<script> 
    let graficoPerformance = null; 

    /*|--------------------------------------------------------------------------| Registros reais enviados pela Blade |--------------------------------------------------------------------------*/ 

    const registrosGrafico = @json($registrosGrafico); 

    /*|--------------------------------------------------------------------------| Navegação entre as abas |-------------------------------------------------------------------------- */ 

    function switchTab(id, elemento) { 
        document 
            .querySelectorAll('.tab-content') 
            .forEach(function (aba) { 
                aba.classList.remove('active'); 
            }); 

        const abaSelecionada = document.getElementById( 
            'tab-' + id 
        ); 

        if (abaSelecionada) { 
            abaSelecionada.classList.add('active'); 
        } 

        document 
            .querySelectorAll('.nav-item') 
            .forEach(function (item) { 
                item.classList.remove('active'); 
            }); 

        if (elemento) { 
            elemento.classList.add('active'); 
        } 

        if (id === 'reports') { 
            setTimeout(function () { 
                criarGrafico(); 
            }, 120); 
        } 
    } 

    /*|--------------------------------------------------------------------------| Criação do gráfico |--------------------------------------------------------------------------*/ 
    
    function criarGrafico() { 
        const canvas = document.getElementById( 
            'chartPerformance' 
        ); 
        if (!canvas) { 
            return; 
        } 

        /*Cria as 24 horas do dia.*/ 

        const labels = []; 
        const dados = new Array(24).fill(0); 
        for (let hora = 0; hora < 24; hora++) { 
            labels.push( 
                String(hora).padStart(2, '0') + 'h' 
            ); 
        } 

        /*Soma a água registrada em cada hora.*/ 

        registrosGrafico.forEach(function (registro) { 
            const hora = Number(registro.hora); 
            const quantidade = Number(registro.quantidade) || 0; 
            if ( 
                Number.isInteger(hora) && 
                hora >= 0 && 
                hora <= 23 
            ) { 
                dados[hora] += quantidade; 
            } 
        });

        /*Destroi o gráfico anterior antes de recriar.*/ 

        if (graficoPerformance) { 
            graficoPerformance.destroy(); 
        }

        /*Descobre o horário de maior consumo.*/ 

        let maiorValor = 0; 
        let indiceMaior = 0;
        dados.forEach(function (valor, indice) { 
            if (valor > maiorValor) { 
                maiorValor = valor; 
                indiceMaior = indice; 
            } 
        }); 
        const horarioPico = document.getElementById( 
            'horarioPico' 
        ); 
        if (horarioPico) { 
            horarioPico.textContent = 
                maiorValor > 0 
                    ? labels[indiceMaior] 
                    : 'Sem dados'; 
        } 
        const contexto = canvas.getContext('2d'); 
        const preenchimento = contexto.createLinearGradient( 
            0, 
            0, 
            0, 
            370 
        ); 
        preenchimento.addColorStop( 
            0, 
            'rgba(9, 132, 227, 0.42)' 
        ); 
        preenchimento.addColorStop( 
            1, 
            'rgba(23, 200, 206, 0.03)' 
        ); 
        const valorMaximo = 
            maiorValor > 0 
                ? Math.ceil((maiorValor + 200) / 100) * 100 
                : 1000; 
        graficoPerformance = new Chart( 
            contexto, 
            {
                type: 'line', 
                data: {
                    labels: labels, 
                    datasets: [ 
                        { 
                            label: 'Água consumida', 
                            data: dados, 
                            borderColor: '#0984e3', 
                            backgroundColor: preenchimento, 
                            borderWidth: 4, 
                            fill: true, 
                            tension: 0.36, 
                            pointBackgroundColor: '#0984e3', 
                            pointBorderColor: '#ffffff', 
                            pointBorderWidth: 3, 
                            pointRadius: function (contextoPonto) { 
                                return Number(contextoPonto.raw) > 0 
                                    ? 6 
                                    : 2; 
                            }, 
                            pointHoverRadius: 8 
                        } 
                    ] 
                }, 
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    interaction: { 
                        mode: 'index', 
                        intersect: false 
                    }, 
                    animation: { 
                        duration: 900, 
                        easing: 'easeOutQuart' 
                    }, 
                    plugins: { 
                        legend: { 
                            display: false 
                        }, 
                        tooltip: { 
                            enabled: true, 
                            displayColors: false, 
                            backgroundColor: '#1e293b', 
                            titleColor: '#ffffff', 
                            bodyColor: '#ffffff', 
                            padding: 12, 
                            cornerRadius: 10, 
                            callbacks: { 
                                title: function (itens) { 
                                    if (!itens.length) { 
                                        return ''; 
                                    } 
                                    return ( 
                                        'Horário: ' + 
                                        itens[0].label 
                                    ); 
                                }, 
                                label: function (contextoTooltip) { 
                                    return ( 
                                        contextoTooltip.raw + 
                                        ' ml consumidos' 
                                    ); 
                                } 
                            } 
                        } 
                    }, 
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            suggestedMax: valorMaximo, 
                            ticks: { 
                                precision: 0, 
                                color: '#64748b', 
                                callback: function (valor) { 
                                    return valor + ' ml'; 
                                } 
                            },
                            grid: { 
                                color: 
                                    'rgba(148, 163, 184, 0.20)' 
                            }, 
                            border: { 
                                display: false 
                            } 
                        }, 
                        x: { 
                            ticks: { 
                                color: '#64748b', 
                                maxRotation: 0, 
                                autoSkip: true, 
                                maxTicksLimit: 12 
                            }, 
 
                            grid: { 
                                display: false 
                            }, 

                            border: { 
                                display: false 
                            } 
                        } 
                    } 
                } 
            } 
        ); 
    }
    /*|--------------------------------------------------------------------------|Modal da meta|--------------------------------------------------------------------------*/ 

    function abrirModalMeta() { 
        const modal = document.getElementById('modal-meta'); 
        if (modal) { 
            modal.style.display = 'flex'; 
        } 
    } 

    function fecharModalMeta() { 
        const modal = document.getElementById('modal-meta'); 
        if (modal) { 
            modal.style.display = 'none'; 
        } 
    } 

    function fecharModalFora(evento) { 
        if (evento.target.id === 'modal-meta') { 
            fecharModalMeta(); 
        } 
    }
    document.addEventListener( 
        'keydown', 
        function (evento) { 
            if (evento.key === 'Escape') { 
                fecharModalMeta(); 
            } 
        } 
    ); 
</script> 
</body> 
</html>