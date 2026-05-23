\
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Drink Up | Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-animate {
                background: linear-gradient(-45deg, #0984e3, #74b9ff, #00cec9, #0984e3);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            }
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
    </head>
    <body class="bg-animate flex items-center justify-center min-h-screen p-6">
        <div class="bg-white/90 backdrop-blur-md p-8 rounded-[40px] shadow-2xl w-full max-w-md border border-white/20">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-black text-[#0984e3] tracking-tighter">Drink Up</h2>
                <p class="text-slate-500 font-medium mt-2">Bem-vindo de volta!</p>
            </div>

            @if(session('erro'))
                <div class="bg-red-50 text-red-500 p-4 rounded-2xl mb-6 text-sm font-semibold border border-red-100 text-center">
                    {{ session('erro') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded-2xl mb-6 text-sm font-semibold border border-red-100 text-center">
                    @foreach($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.entrar') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase ml-4 mb-1">E-mail</label>
                    <input type="email" name="email" placeholder="exemplo@email.com" required 
                           class="w-full p-4 bg-white border border-slate-200 rounded-2xl outline-none focus:border-[#0984e3] focus:ring-4 focus:ring-[#0984e3]/10 transition-all">
                </div>

                <div>
                    <div class="flex justify-between items-center ml-4 mb-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Senha</label>
                        <a href="#" class="text-xs font-bold text-[#0984e3] hover:underline">Esqueceu a senha?</a>
                    </div>
                    <input type="password" name="senha" placeholder="••••••••" required 
                           class="w-full p-4 bg-white border border-slate-200 rounded-2xl outline-none focus:border-[#0984e3] focus:ring-4 focus:ring-[#0984e3]/10 transition-all">
                </div>

                <button type="submit" 
                        class="w-full bg-[#0984e3] text-white py-4 rounded-2xl font-black shadow-lg shadow-blue-200 hover:bg-[#0773c5] hover:-translate-y-1 active:scale-95 transition-all uppercase tracking-wider">
                    Entrar no Sistema
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-slate-500 text-sm">
                    Não tem uma conta? 
                    <a href="{{ route('cadastro') }}" class="text-[#0984e3] font-bold hover:underline transition-all">Cadastre-se agora</a>
                </p>
            </div>
        </div>
    </body>
    </html>
