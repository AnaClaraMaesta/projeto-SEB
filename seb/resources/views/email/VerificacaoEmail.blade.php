<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifique seu E-mail - {{ config('app.name', 'Laravel') }}</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md border border-gray-200">
            
            <!-- Ícone -->
            <div class="flex justify-center mb-4">
                <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Título -->
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">
                Verifique seu endereço de e-mail
            </h2>

            <!-- Mensagem explicativa -->
            <p class="text-sm text-gray-600 text-center mb-6">
                Obrigado por se cadastrar! Antes de começar, por favor, verifique seu e-mail clicando no link que acabamos de enviar para você. Se não recebeu o e-mail, podemos enviar outro com prazer.
            </p>

            <!-- Alerta de confirmação de reenvio -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-md text-center border border-green-200">
                    Um novo link de verificação foi enviado para o endereço de e-mail fornecido no cadastro.
                </div>
            @endif

            <!-- Ações -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- Formulário para Reenviar E-mail -->
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Reenviar e-mail
                    </button>
                </form>

                <!-- Formulário para Sair (Logout) -->
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto text-sm text-gray-600 hover:text-gray-900 underline text-center focus:outline-none">
                        Sair da conta
                    </button>
                </form>

            </div>

        </div>

    </div>
</body>
</html>