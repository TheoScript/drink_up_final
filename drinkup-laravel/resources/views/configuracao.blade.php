\
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Configurações | Drink Up</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    </head>
    <body class="bg-[#ebf2f7] font-['Inter'] p-4 md:p-10">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-black text-[#0984e3]">Minha Conta</h1>
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-500">Voltar ao Painel</a>
            </div>

            @if($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded-2xl mb-6 text-sm font-semibold border border-red-100">
                    @foreach($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('configuracao.atualizar') }}" class="space-y-6">
                @csrf

                <div class="bg-white p-8 rounded-[30px] shadow-sm">
                    <h3 class="text-lg font-bold mb-6 text-slate-700 border-b pb-2">Informações Pessoais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nome Completo</label>
                            <input type="text" name="nome" value="{{ $usuario->nome }}" class="w-full p-3 bg-slate-50 border rounded-xl outline-none focus:border-[#0984e3]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">E-mail</label>
                            <input type="email" name="email" value="{{ $usuario->email }}" class="w-full p-3 bg-slate-50 border rounded-xl outline-none focus:border-[#0984e3]">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[30px] shadow-sm border-2 border-orange-100">
                    <h3 class="text-lg font-bold mb-6 text-slate-700 border-b pb-2">Segurança</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nova Senha (deixe vazio para não alterar)</label>
                        <input type="password" name="nova_senha" placeholder="••••••••" class="w-full p-3 bg-slate-50 border rounded-xl outline-none focus:border-[#0984e3]">
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[30px] shadow-sm">
                    <h3 class="text-lg font-bold mb-6 text-slate-700 border-b pb-2">Metas e Medidas</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Peso (kg)</label>
                            <input type="number" step="0.1" name="peso" value="{{ $usuario->peso }}" class="w-full p-3 bg-slate-50 border rounded-xl">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Meta Personalizada (ml)</label>
                            <input type="number" name="meta_manual" value="{{ $usuario->meta_diaria }}" class="w-full p-3 bg-blue-50 border-2 border-[#0984e3] text-[#0984e3] font-bold rounded-xl">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#0984e3] text-white py-4 rounded-2xl font-black shadow-lg hover:scale-[1.02] transition-all">
                    SALVAR ALTERAÇÕES
                </button>
            </form>
        </div>
    </body>
    </html>
