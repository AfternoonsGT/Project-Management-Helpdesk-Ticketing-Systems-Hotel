<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Helpdesk IT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">

    <div class="w-full min-h-screen md:grid md:grid-cols-2">

        <div class="flex min-h-screen items-center justify-center p-6 md:h-auto md:p-0 md:py-12">
            <div class="mx-auto grid w-full max-w-[350px] gap-6">

                <div class="flex flex-col items-center gap-1 text-center">

                    <div class="w-24 h-28 bg-white rounded-2xl flex items-center justify-center ">
                        <img src="{{ asset('images/logo_pangeran.png') }}" alt="Logo Hotel Pangeran" class="w-full h-full object-contain mix-blend-multiply">
                    </div>

                    <h1 class="text-2xl font-bold text-[#0f2942] mt-2">Buat Akun</h1>
                    <p class="text-sm text-gray-500">Silakan lengkapi data diri Anda di bawah ini</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="grid gap-4">
                    @csrf

                    <div class="grid gap-2">
                        <label for="name" class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="flex h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm transition-all focus:border-[#0f2942] focus:outline-none focus:ring-2 focus:ring-[#0f2942]/20"
                            placeholder="Mister Budi">
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="flex h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm transition-all focus:border-[#0f2942] focus:outline-none focus:ring-2 focus:ring-[#0f2942]/20"
                            placeholder="budi@hotelpangeran.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <div class="grid gap-2">
                        <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
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

                    <div class="grid gap-2">
                        <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="flex h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-sm transition-all focus:border-[#0f2942] focus:outline-none focus:ring-2 focus:ring-[#0f2942]/20"
                                placeholder="••••••••">

                            <button type="button" id="togglePasswordConf" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <svg id="eyeOffConf" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0l-3.29-3.29"></path></svg>
                                <svg id="eyeOnConf" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <button type="submit" class="w-full mt-2 flex justify-center items-center h-11 px-4 rounded-lg bg-[#0f2942] text-white text-sm font-bold shadow hover:bg-[#1a4066] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0f2942]">
                        Daftar Sekarang
                    </button>
                </form>

                <div class="text-center text-sm text-gray-600 mt-2">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-[#0f2942] hover:underline hover:text-blue-700 transition-colors">
                        Masuk di sini
                    </a>
                </div>

                <div class="text-center text-xs text-black mt-2">
                    &copy; {{ date('Y') }} Dirancang oleh Arif & Dika
                </div>
            </div>
        </div>

        <div class="hidden md:block relative bg-cover bg-center transition-all duration-500 ease-in-out"
             style="background-image: url('https://bzpublishassets.blob.core.windows.net/media/assets_medium/Hotel_Pangeran_Pekanbaru_New.webp');">

            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-[#0f2942] to-transparent"></div>

            <div class="relative z-10 flex h-full flex-col items-center justify-end p-8 pb-12">
                <blockquote class="space-y-4 text-center text-white max-w-lg">
                    <p class="text-2xl font-medium tracking-wide drop-shadow-md">
                        “<span id="typewriter"></span><span class="animate-pulse text-blue-400">|</span>”
                    </p>
                    <cite class="block text-sm font-light text-gray-300 not-italic">
                        — IT Department Development Team
                    </cite>
                </blockquote>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function setupPasswordToggle(inputId, btnId, eyeOnId, eyeOffId) {
                const input = document.getElementById(inputId);
                const btn = document.getElementById(btnId);
                const eyeOn = document.getElementById(eyeOnId);
                const eyeOff = document.getElementById(eyeOffId);

                if(btn && input) {
                    btn.addEventListener('click', function() {
                        if (input.type === 'password') {
                            input.type = 'text';
                            eyeOn.classList.add('hidden');
                            eyeOff.classList.remove('hidden');
                        } else {
                            input.type = 'password';
                            eyeOn.classList.remove('hidden');
                            eyeOff.classList.add('hidden');
                        }
                    });
                }
            }

            setupPasswordToggle('password', 'togglePassword', 'eyeOn', 'eyeOff');
            setupPasswordToggle('password_confirmation', 'togglePasswordConf', 'eyeOnConf', 'eyeOffConf');

            const textToType = "Bergabunglah bersama kami untuk membangun sistem operasional yang lebih baik dan terstruktur.";
            const speed = 60;
            let i = 0;
            const targetElement = document.getElementById('typewriter');

            function typeWriter() {
                if (i < textToType.length) {
                    targetElement.innerHTML += textToType.charAt(i);
                    i++;
                    setTimeout(typeWriter, speed);
                }
            }

            setTimeout(typeWriter, 1000);
        });
    </script>
</body>
</html>
