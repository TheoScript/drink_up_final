\
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Drink Up - Hidrate-se com inteligência</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { background-color: #f0f9ff; font-family: 'Inter', sans-serif; }
            .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); }
        </style>
    </head>
    <body class="min-h-screen">
        <nav class="flex justify-between items-center px-12 py-6">
            <div class="flex items-center gap-2">
                <div class="text-sky-500 text-3xl">💧</div>
                <span class="text-xl font-bold text-slate-800">Drink Up</span>
            </div>
            <a href="{{ route('login') }}" class="text-slate-600 hover:text-sky-600 font-medium">Entrar</a>
        </nav>

        <main class="max-w-7xl mx-auto px-12 pt-10 flex flex-col md:flex-row items-center gap-10">
            <div class="flex-1 space-y-6">
                <span class="bg-sky-100 text-sky-600 px-4 py-1 rounded-full text-sm font-medium">● Bebedouro inteligente conectado</span>
                <h1 class="text-6xl font-extrabold text-slate-900 leading-tight">
                    Hidrate-se com <br><span class="text-sky-400">inteligência</span>
                </h1>
                <p class="text-slate-500 text-lg max-w-md">
                    Monitore seu consumo diário de água, atinja sua meta de hidratação e conecte-se a bebedouros inteligentes.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('cadastro') }}" class="bg-sky-500 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:bg-sky-600 transition text-center">Começar agora</a>
                    <a href="{{ route('login') }}" class="bg-white text-slate-600 px-8 py-3 rounded-xl font-semibold border border-slate-100 shadow-sm hover:bg-slate-50 text-center">Já tenho conta</a>
                </div>
            </div>

            <div class="flex-1 relative flex justify-center">
                <div class="w-[500px] h-[400px] bg-sky-100/50 rounded-[100px] flex items-center justify-center">
                    <div class="w-64 h-64 bg-gradient-to-br from-sky-400 to-blue-600 rounded-full flex items-center justify-center shadow-2xl">
                         <span class="text-white text-7xl">💧</span>
                    </div>
                </div>
            </div>
        </main>

        <section class="max-w-7xl mx-auto px-12 py-20 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass p-6"><div class="text-sky-400 text-2xl mb-2">🎯</div><h3 class="font-bold">Meta personalizada</h3><p class="text-xs text-slate-500">Calculada por peso (35ml/kg).</p></div>
            <div class="glass p-6"><div class="text-sky-400 text-2xl mb-2">📈</div><h3 class="font-bold">Progresso real</h3><p class="text-xs text-slate-500">Acompanhe sua hidratação.</p></div>
            <div class="glass p-6"><div class="text-sky-400 text-2xl mb-2">🔌</div><h3 class="font-bold">Bebedouro inteligente</h3><p class="text-xs text-slate-500">Integração via API.</p></div>
            <div class="glass p-6"><div class="text-sky-400 text-2xl mb-2">☁️</div><h3 class="font-bold">Histórico completo</h3><p class="text-xs text-slate-500">Dados salvos na nuvem.</p></div>
        </section>
    </body>
    </html>
