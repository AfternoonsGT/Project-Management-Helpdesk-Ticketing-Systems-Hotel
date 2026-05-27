<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Helpdesk IT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">

    <div class="w-full min-h-screen md:grid md:grid-cols-2">

        <div class="flex h-screen items-center justify-center p-6 md:h-auto md:p-0 md:py-12">
            <div class="mx-auto grid w-full max-w-[350px] gap-6">

                <div class="flex flex-col items-center gap-1 text-center">

                    <div class="w-24 h-28 bg-white rounded-2xl flex items-center justify-center">
                        <img src="{{ asset('images/logo_pangeran.png') }}" alt="Logo Hotel Pangeran" class="w-full h-full object-contain mix-blend-multiply">
                    </div>

                    <h1 class="text-2xl font-bold text-[#0f2942] mt-2">Lupa Password?</h1>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Tidak masalah. Masukkan email akun staf Anda di bawah ini, dan kami akan mengirimkan tautan untuk mengatur ulang password.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="grid gap-5">
                    @csrf

                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="flex h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm transition-all focus:border-[#0f2942] focus:outline-none focus:ring-2 focus:ring-[#0f2942]/20"
                            placeholder="nama@hotelpangeran.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center h-11 px-4 rounded-lg bg-[#0f2942] text-white text-sm font-bold shadow hover:bg-[#1a4066] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0f2942]">
                        Kirim Tautan Reset Password
                    </button>
                </form>

                <div class="text-center text-sm text-gray-600 mt-2">
                    Ingat password Anda?
                    <a href="{{ route('login') }}" class="font-bold text-[#0f2942] hover:underline hover:text-blue-700 transition-colors">
                        Kembali ke Login
                    </a>
                </div>

                <div class="text-center text-xs text-black mt-4">
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
                        — IT Security & Compliance
                    </cite>
                </blockquote>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textToType = "Keamanan data dan hak akses sistem adalah prioritas utama dalam mendukung operasional hotel.";
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
