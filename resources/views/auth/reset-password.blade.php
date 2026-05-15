<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atur Ulang Password - Hotel Pangeran Helpdesk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Transisi dasar agar mulus seperti di React */
        .char-transition { transition: all 0.7s ease-in-out; }
        .eye-transition { transition: all 0.1s ease-out; }
        .blink-transition { transition: height 0.15s ease-in-out; }
    </style>
</head>
<body class="font-sans antialiased bg-[#030712] text-slate-200">

    <div class="min-h-screen grid lg:grid-cols-2">

        <div class="relative hidden lg:flex flex-col justify-between bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 p-12 overflow-hidden border-r border-white/5">

            <div class="relative z-20">
                <div class="flex items-center gap-2 text-lg font-semibold text-white">
                    <div class="w-8 h-8 rounded-lg bg-white/10 backdrop-blur-sm flex items-center justify-center p-1">
                        <img src="{{ asset('images/logo_pangeran.png') }}" class="w-full h-full object-contain mix-blend-screen">
                    </div>
                    <span>Hotel Pangeran <span class="text-slate-500 font-normal">Helpdesk</span></span>
                </div>
            </div>

            <div class="relative z-20 flex items-end justify-center h-[500px]">
                <div class="relative" style="width: 550px; height: 400px;">

                    <div id="char-purple" class="char-transition absolute bottom-0 bg-[#6C3FF5] rounded-t-[10px] z-10" style="left: 70px; width: 180px; height: 400px; transform-origin: bottom center;">
                        <div id="eyes-purple" class="char-transition absolute flex gap-8">
                            <div class="blink-purple flex items-center justify-center bg-white rounded-full overflow-hidden blink-transition" style="width: 18px; height: 18px;">
                                <div class="pupil-purple bg-[#2D2D2D] rounded-full eye-transition" style="width: 7px; height: 7px;"></div>
                            </div>
                            <div class="blink-purple flex items-center justify-center bg-white rounded-full overflow-hidden blink-transition" style="width: 18px; height: 18px;">
                                <div class="pupil-purple bg-[#2D2D2D] rounded-full eye-transition" style="width: 7px; height: 7px;"></div>
                            </div>
                        </div>
                    </div>

                    <div id="char-black" class="char-transition absolute bottom-0 bg-[#2D2D2D] rounded-t-[8px] z-20" style="left: 240px; width: 120px; height: 310px; transform-origin: bottom center;">
                        <div id="eyes-black" class="char-transition absolute flex gap-6">
                            <div class="blink-black flex items-center justify-center bg-white rounded-full overflow-hidden blink-transition" style="width: 16px; height: 16px;">
                                <div class="pupil-black bg-[#2D2D2D] rounded-full eye-transition" style="width: 6px; height: 6px;"></div>
                            </div>
                            <div class="blink-black flex items-center justify-center bg-white rounded-full overflow-hidden blink-transition" style="width: 16px; height: 16px;">
                                <div class="pupil-black bg-[#2D2D2D] rounded-full eye-transition" style="width: 6px; height: 6px;"></div>
                            </div>
                        </div>
                    </div>

                    <div id="char-orange" class="char-transition absolute bottom-0 bg-[#FF9B6B] rounded-t-[120px] z-30" style="left: 0px; width: 240px; height: 200px; transform-origin: bottom center;">
                        <div id="eyes-orange" class="char-transition absolute flex gap-8">
                            <div class="pupil-orange bg-[#2D2D2D] rounded-full eye-transition" style="width: 12px; height: 12px;"></div>
                            <div class="pupil-orange bg-[#2D2D2D] rounded-full eye-transition" style="width: 12px; height: 12px;"></div>
                        </div>
                    </div>

                    <div id="char-yellow" class="char-transition absolute bottom-0 bg-[#E8D754] rounded-t-[70px] z-40" style="left: 310px; width: 140px; height: 230px; transform-origin: bottom center;">
                        <div id="eyes-yellow" class="char-transition absolute flex gap-6">
                            <div class="pupil-yellow bg-[#2D2D2D] rounded-full eye-transition" style="width: 12px; height: 12px;"></div>
                            <div class="pupil-yellow bg-[#2D2D2D] rounded-full eye-transition" style="width: 12px; height: 12px;"></div>
                        </div>
                        <div id="mouth-yellow" class="char-transition absolute w-20 h-[4px] bg-[#2D2D2D] rounded-full"></div>
                    </div>

                </div>
            </div>

            <div class="relative z-20 text-sm text-slate-500 font-medium">
                &copy; {{ date('Y') }} IT Department Team
            </div>

            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"></div>
        </div>

        <div class="flex items-center justify-center p-8 bg-[#030712] relative z-50">
            <div class="w-full max-w-[420px]">

                <div class="lg:hidden flex items-center justify-center gap-2 text-lg font-semibold mb-10 text-white">
                    <img src="{{ asset('images/logo_pangeran.png') }}" class="w-8 h-8 object-contain">
                    <span>Hotel Pangeran</span>
                </div>

                <div class="mb-8">
                    <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Atur Ulang Password</h1>
                    <p class="text-slate-400 text-sm">Silakan buat password baru untuk akun Anda.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-slate-400">Email Pegawai</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly autocomplete="username"
                            class="w-full h-11 bg-[#0f172a]/50 border border-slate-800/50 rounded-md px-4 text-slate-500 cursor-not-allowed focus:outline-none"
                            placeholder="budi@hotelpangeran.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium text-slate-300">Password Baru</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autofocus autocomplete="new-password"
                                class="input-track w-full h-11 bg-[#0f172a] border border-slate-800 rounded-md px-4 pr-10 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                                placeholder="••••••••">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300 transition-colors">
                                <svg id="eyeOff" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0l-3.29-3.29"></path></svg>
                                <svg id="eyeOn" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-medium text-slate-300">Konfirmasi Password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                class="input-track w-full h-11 bg-[#0f172a] border border-slate-800 rounded-md px-4 pr-10 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                                placeholder="••••••••">
                            <button type="button" id="togglePasswordConf" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300 transition-colors">
                                <svg id="eyeOffConf" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0l-3.29-3.29"></path></svg>
                                <svg id="eyeOnConf" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full h-11 bg-white hover:bg-slate-200 text-slate-900 font-bold rounded-md transition-all active:scale-[0.98] shadow-lg shadow-blue-500/5">
                            SIMPAN PASSWORD BARU
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- STATE VARIABEL ---
            let state = {
                mouseX: window.innerWidth / 2, mouseY: window.innerHeight / 2,
                isTyping: false, passLength: 0, showPassword: false,
                isLookingAtEachOther: false, isPurplePeeking: false,
                blinkPurple: false, blinkBlack: false
            };

            // --- ELEMENTS ---
            const els = {
                charPurple: document.getElementById('char-purple'),
                eyesPurple: document.getElementById('eyes-purple'),
                charBlack: document.getElementById('char-black'),
                eyesBlack: document.getElementById('eyes-black'),
                charOrange: document.getElementById('char-orange'),
                eyesOrange: document.getElementById('eyes-orange'),
                charYellow: document.getElementById('char-yellow'),
                eyesYellow: document.getElementById('eyes-yellow'),
                mouthYellow: document.getElementById('mouth-yellow'),

                inputs: document.querySelectorAll('.input-track'),
                passInput: document.getElementById('password'),
                passConfInput: document.getElementById('password_confirmation'),
            };

            // --- LOGIKA MATA (TOGGLE PASSWORD) ---
            function setupEyeToggle(btnId, eyeOnId, eyeOffId, inputElement, isMainPassword = false) {
                const btn = document.getElementById(btnId);
                const eyeOn = document.getElementById(eyeOnId);
                const eyeOff = document.getElementById(eyeOffId);
                if(btn && inputElement) {
                    btn.addEventListener('click', () => {
                        const isText = inputElement.type === 'password';
                        inputElement.type = isText ? 'text' : 'password';
                        eyeOn.classList.toggle('hidden', isText);
                        eyeOff.classList.toggle('hidden', !isText);
                        if (isMainPassword) state.showPassword = isText;
                    });
                }
            }
            setupEyeToggle('togglePassword', 'eyeOn', 'eyeOff', els.passInput, true);
            setupEyeToggle('togglePasswordConf', 'eyeOnConf', 'eyeOffConf', els.passConfInput, false);

            // --- EVENT LISTENERS ---
            window.addEventListener('mousemove', (e) => {
                state.mouseX = e.clientX; state.mouseY = e.clientY;
            });

            els.inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    state.isTyping = true;
                    state.isLookingAtEachOther = true;
                    setTimeout(() => state.isLookingAtEachOther = false, 800);
                });
                input.addEventListener('blur', () => state.isTyping = false);
            });

            els.passInput.addEventListener('input', (e) => state.passLength = e.target.value.length);
            els.passInput.addEventListener('focus', () => state.isTyping = false);

            // --- LOGIKA BERKEDIP (BLINKING) ---
            const startBlinking = (charName) => {
                setTimeout(() => {
                    state[`blink${charName}`] = true;
                    setTimeout(() => {
                        state[`blink${charName}`] = false;
                        startBlinking(charName);
                    }, 150);
                }, Math.random() * 4000 + 3000);
            };
            startBlinking('Purple');
            startBlinking('Black');

            // --- LOGIKA PEEKING (MENGINTIP PASSWORD) ---
            setInterval(() => {
                if (state.passLength > 0 && state.showPassword) {
                    state.isPurplePeeking = true;
                    setTimeout(() => state.isPurplePeeking = false, 800);
                } else {
                    state.isPurplePeeking = false;
                }
            }, 3500);

            // --- FUNGSI KALKULASI POSISI KEPALA ---
            function calcPos(element) {
                if (!element) return { fX: 0, fY: 0, skew: 0 };
                const rect = element.getBoundingClientRect();
                const dX = state.mouseX - (rect.left + rect.width / 2);
                const dY = state.mouseY - (rect.top + rect.height / 3);
                return {
                    fX: Math.max(-15, Math.min(15, dX / 20)),
                    fY: Math.max(-10, Math.min(10, dY / 30)),
                    skew: Math.max(-6, Math.min(6, -dX / 120))
                };
            }

            function calcPupil(cx, cy, maxD, forceX, forceY) {
                if (forceX !== undefined) return { x: forceX, y: forceY };
                const a = Math.atan2(state.mouseY - cy, state.mouseX - cx);
                const d = Math.min(Math.hypot(state.mouseX - cx, state.mouseY - cy), maxD);
                return { x: Math.cos(a) * d, y: Math.sin(a) * d };
            }

            // --- RENDER LOOP UTAMA ---
            function render() {
                const pos = { p: calcPos(els.charPurple), b: calcPos(els.charBlack), o: calcPos(els.charOrange), y: calcPos(els.charYellow) };
                const passMode = (state.passLength > 0 && state.showPassword);
                const typeMode = (state.isTyping || (state.passLength > 0 && !state.showPassword));

                // 1. Purple
                els.charPurple.style.height = typeMode ? '440px' : '400px';
                els.charPurple.style.transform = passMode ? `skewX(0deg)` : typeMode ? `skewX(${pos.p.skew - 12}deg) translateX(40px)` : `skewX(${pos.p.skew}deg)`;
                els.eyesPurple.style.left = passMode ? '20px' : state.isLookingAtEachOther ? '55px' : `${45 + pos.p.fX}px`;
                els.eyesPurple.style.top = passMode ? '35px' : state.isLookingAtEachOther ? '65px' : `${40 + pos.p.fY}px`;
                document.querySelectorAll('.blink-purple').forEach(e => e.style.height = state.blinkPurple ? '2px' : '18px');
                document.querySelectorAll('.pupil-purple').forEach(e => {
                    const rect = e.parentElement.getBoundingClientRect();
                    const pup = calcPupil(rect.left+9, rect.top+9, 5, passMode ? (state.isPurplePeeking?4:-4) : state.isLookingAtEachOther?3:undefined, passMode ? (state.isPurplePeeking?5:-4) : state.isLookingAtEachOther?4:undefined);
                    e.style.transform = `translate(${pup.x}px, ${pup.y}px)`;
                });

                // 2. Black
                els.charBlack.style.transform = passMode ? `skewX(0deg)` : state.isLookingAtEachOther ? `skewX(${pos.b.skew * 1.5 + 10}deg) translateX(20px)` : typeMode ? `skewX(${pos.b.skew * 1.5}deg)` : `skewX(${pos.b.skew}deg)`;
                els.eyesBlack.style.left = passMode ? '10px' : state.isLookingAtEachOther ? '32px' : `${26 + pos.b.fX}px`;
                els.eyesBlack.style.top = passMode ? '28px' : state.isLookingAtEachOther ? '12px' : `${32 + pos.b.fY}px`;
                document.querySelectorAll('.blink-black').forEach(e => e.style.height = state.blinkBlack ? '2px' : '16px');
                document.querySelectorAll('.pupil-black').forEach(e => {
                    const rect = e.parentElement.getBoundingClientRect();
                    const pup = calcPupil(rect.left+8, rect.top+8, 4, passMode ? -4 : state.isLookingAtEachOther?0:undefined, passMode ? -4 : state.isLookingAtEachOther?-4:undefined);
                    e.style.transform = `translate(${pup.x}px, ${pup.y}px)`;
                });

                // 3. Orange
                els.charOrange.style.transform = passMode ? `skewX(0deg)` : `skewX(${pos.o.skew}deg)`;
                els.eyesOrange.style.left = passMode ? '50px' : `${82 + pos.o.fX}px`;
                els.eyesOrange.style.top = passMode ? '85px' : `${90 + pos.o.fY}px`;
                document.querySelectorAll('.pupil-orange').forEach(e => {
                    const rect = e.parentElement.getBoundingClientRect();
                    const pup = calcPupil(rect.left+6, rect.top+6, 5, passMode?-5:undefined, passMode?-4:undefined);
                    e.style.transform = `translate(${pup.x}px, ${pup.y}px)`;
                });

                // 4. Yellow
                els.charYellow.style.transform = passMode ? `skewX(0deg)` : `skewX(${pos.y.skew}deg)`;
                els.eyesYellow.style.left = passMode ? '20px' : `${52 + pos.y.fX}px`;
                els.eyesYellow.style.top = passMode ? '35px' : `${40 + pos.y.fY}px`;
                els.mouthYellow.style.left = passMode ? '10px' : `${40 + pos.y.fX}px`;
                els.mouthYellow.style.top = passMode ? '88px' : `${88 + pos.y.fY}px`;
                document.querySelectorAll('.pupil-yellow').forEach(e => {
                    const rect = e.parentElement.getBoundingClientRect();
                    const pup = calcPupil(rect.left+6, rect.top+6, 5, passMode?-5:undefined, passMode?-4:undefined);
                    e.style.transform = `translate(${pup.x}px, ${pup.y}px)`;
                });

                requestAnimationFrame(render);
            }
            render(); // Jalankan loop mesin animasi
        });
    </script>
</body>
</html>
