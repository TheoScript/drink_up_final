\
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Drink Up - Cadastro</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; background: linear-gradient(-45deg, #0984e3, #74b9ff, #00cec9, #0984e3);}
            .glass-card { background: white; border-radius: 32px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); }
        </style>
    </head>
    <body class="flex flex-col items-center justify-center min-h-screen p-6">

        <header class="w-full max-w-md flex items-center mb-8 px-4">
            <div class="flex items-center gap-2">
                <div class="text-sky-500 text-2xl">💧</div>
                <span class="font-bold text-xl text-slate-800">Drink Up</span>
            </div>
        </header>

        <div class="glass-card w-full max-w-md p-8">
            <div class="flex bg-slate-100 p-1 rounded-full mb-8">
                <a href="{{ route('login') }}" class="flex-1 py-2 text-center text-slate-500 font-medium">Entrar</a>
                <button class="flex-1 py-2 bg-white rounded-full shadow-sm font-bold text-slate-800">Cadastrar</button>
            </div>

            <h2 class="text-2xl font-bold text-slate-900 mb-1">Criar sua conta</h2>
            <p class="text-slate-400 text-sm mb-8">Calcularemos sua meta diária de água automaticamente</p>

            @if($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded-2xl mb-6 text-sm font-semibold border border-red-100">
                    @foreach($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('cadastro.salvar') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase ml-1 mb-1 block">Nome completo</label>
                    <input type="text" name="nome" placeholder="Seu nome" required
                        class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase ml-1 mb-1 block">Email</label>
                    <input type="email" name="email" placeholder="seu@email.com" required
                        class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase ml-1 mb-1 block">Senha</label>
                    <input type="password" name="senha" placeholder="********" required
                        class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all">
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="text-xs font-bold text-slate-400 uppercase ml-1 mb-1 block">Sexo</label>
                        <select name="sexo" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none appearance-none">
                            <option value="Feminino">Feminino</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                    <div class="w-28">
                        <label class="text-xs font-bold text-slate-400 uppercase ml-1 mb-1 block">Idade</label>
                        <input type="number" name="idade" placeholder="18" required
                            class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase ml-1 mb-1 block">Peso (kg)</label>
                    <input type="number" id="peso_input" name="peso" placeholder="Ex: 70" required
                        class="w-full p-4 bg-slate-50 border-2 border-sky-400 rounded-2xl focus:bg-white outline-none transition-all">
                </div>

                <div id="meta_box" class="hidden bg-sky-50 border border-sky-100 p-4 rounded-2xl text-sky-700 text-center font-medium transition-all">
                    Sua meta diária será de <span id="meta_valor" class="font-bold text-sky-900">0 ml</span>
                </div>

                <button type="submit" 
                    class="w-full bg-sky-400 text-white py-4 rounded-2xl font-bold text-lg shadow-lg shadow-sky-100 hover:bg-sky-500 hover:-translate-y-0.5 transition-all">
                    Criar conta
                </button>
            </form>
        </div>

        <script>
            const pesoInput = document.getElementById('peso_input');
            const metaBox = document.getElementById('meta_box');
            const metaValor = document.getElementById('meta_valor');

            pesoInput.addEventListener('input', function() {
                const peso = parseFloat(this.value);
                if (peso > 0) {
                    const calculo = peso * 35;
                    metaValor.innerText = calculo + ' ml';
                    metaBox.classList.remove('hidden');
                } else {
                    metaBox.classList.add('hidden');
                }
            });
        </script>
    </body>
    </html>
