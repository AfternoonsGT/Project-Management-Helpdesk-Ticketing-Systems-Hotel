<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-[#09090b] text-gray-900 dark:text-gray-50 min-h-screen font-sans">

    <div class="h-screen w-full flex items-center justify-center py-16 px-4 md:py-24 md:px-20 relative overflow-hidden">
        
        <div class="absolute hidden md:flex inset-0 items-center justify-center pointer-events-none z-0">
            <span class="text-[25rem] font-black text-gray-200 dark:text-gray-800/40 select-none">
                404
            </span>
        </div>

        <div class="z-10 flex flex-col items-center justify-center gap-8 md:gap-12">
            
            <div class="flex flex-col items-center justify-center gap-4 md:gap-6">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-500 rounded-full mb-2">
                    <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h1 class="text-center text-4xl md:text-6xl font-bold tracking-tight">
                    Halaman Tidak Ditemukan
                </h1>
                
                <p class="text-center text-lg md:text-xl text-gray-500 dark:text-gray-400 max-w-md">
                    Maaf, rute yang Anda tuju tidak tersedia. Mungkin Anda salah mengetik URL atau halaman tersebut telah dipindahkan.
                </p>
            </div>

            <div class="flex gap-3 flex-col md:flex-row w-full items-center justify-center mt-4">
                <a href="{{ route('dashboard') }}" 
                   class="w-full md:w-fit inline-flex items-center justify-center h-11 px-8 rounded-md bg-gray-900 dark:bg-gray-50 text-gray-50 dark:text-gray-900 text-sm font-medium transition-colors hover:bg-gray-900/90 dark:hover:bg-gray-50/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 shadow-sm">
                    <svg class="size-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

</body>
</html>