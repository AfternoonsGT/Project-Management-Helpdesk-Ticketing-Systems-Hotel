<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Helpdesk IT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">

    <div class="w-full min-h-screen md:grid md:grid-cols-2">
        
        <div class="flex h-screen items-center justify-center p-6 md:h-auto md:p-0 md:py-12">
            <div class="mx-auto grid w-full max-w-[350px] gap-8">
                
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="w-16 h-16 bg-[#0f2942] rounded-xl flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-[#0f2942]">Portal Helpdesk</h1>
                    <p class="text-sm text-gray-500">Silakan masuk dengan akun staf Anda</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="grid gap-5">
                    @csrf

                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-semibold text-gray-700">Email Pegawai</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="flex h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm transition-all focus:border-[#0f2942] focus:outline-none focus:ring-2 focus:ring-[#0f2942]/20"
                            placeholder="nama@hotelpangeran.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <div class="grid gap-2">
                        <div class="flex justify-between items-center">
                            <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-600 hover:underline">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                class="flex h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-sm transition-all focus:border-[#0f2942] focus:outline-none focus:ring-2 focus:ring-[#0f2942]/20"
                                placeholder="••••••••">
                            
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <svg id="eyeOff" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0l-3.29-3.29"></path></svg>
                                <svg id="eyeOn" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-[#0f2942] focus:ring-[#0f2942]">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center h-11 px-4 rounded-lg bg-[#0f2942] text-white text-sm font-bold shadow hover:bg-[#1a4066] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0f2942]">
                        Masuk ke Dashboard
                    </button>
                </form>
                
                <div class="text-center text-xs text-gray-400 mt-4">
                    &copy; {{ date('Y') }} Dirancang oleh Arif & Dika
                </div>
            </div>
        </div>

        <div class="hidden md:block relative bg-cover bg-center transition-all duration-500 ease-in-out"
             style="background-image: url('https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1000&q=80');">
            
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-[#0f2942] to-transparent"></div>
            
            <div class="relative z-10 flex h-full flex-col items-center justify-end p-8 pb-12">
                <blockquote class="space-y-4 text-center text-white max-w-lg">
                    <p class="text-2xl font-medium tracking-wide drop-shadow-md">
                        “<span id="typewriter"></span><span class="animate-pulse text-blue-400">|</span>”
                    </p>
                    <cite class="block text-sm font-light text-gray-300 not-italic">
                        — Standard Operating Procedure
                    </cite>
                </blockquote>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. LOGIKA TOGGLE PASSWORD (Mata)
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');
            const eyeOn = document.getElementById('eyeOn');
            const eyeOff = document.getElementById('eyeOff');

            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOn.classList.add('hidden');
                    eyeOff.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeOn.classList.remove('hidden');
                    eyeOff.classList.add('hidden');
                }
            });

            // 2. LOGIKA TYPEWRITER EFFECT (Seperti di React Prompt)
            const textToType = "Melayani laporan teknis dengan cepat, tepat, dan terdata rapi untuk kenyamanan operasional hotel.";
            const speed = 60; // Kecepatan ketik dalam milidetik
            let i = 0;
            const targetElement = document.getElementById('typewriter');

            function typeWriter() {
                if (i < textToType.length) {
                    targetElement.innerHTML += textToType.charAt(i);
                    i++;
                    setTimeout(typeWriter, speed);
                }
            }
            
            // Beri jeda 1 detik sebelum mulai mengetik agar terlihat elegan saat halaman dimuat
            setTimeout(typeWriter, 1000);
        });
    </script>
</body>
</html>