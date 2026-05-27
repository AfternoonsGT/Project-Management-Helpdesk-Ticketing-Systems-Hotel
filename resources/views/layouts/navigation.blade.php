<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 dark:bg-[#1e293b] dark:border-gray-700 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <button id="theme-toggle" type="button" class="relative p-2 mr-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none transition duration-150 ease-in-out rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.366a1 1 0 011.415 0l.707.707a1 1 0 01-1.414 1.415l-.707-.707a1 1 0 010-1.415zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM14.22 17.634a1 1 0 010-1.415l.707-.707a1 1 0 011.414 1.415l-.707.707a1 1 0 01-1.415 0zM10 18a1 1 0 01-1-1v-1a1 1 0 112 0v1a1 1 0 01-1 1zm-4.22-2.366a1 1 0 01-1.415 0l-.707-.707a1 1 0 011.414-1.415l.707.707a1 1 0 010 1.415zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zm2.366-4.22a1 1 0 010-1.415l-.707-.707a1 1 0 01-1.414 1.415l.707.707a1 1 0 011.414-1.415zM10 5a5 5 0 100 10 5 5 0 000-10z"></path></svg>
                    <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>

                <x-dropdown align="right" width="64"> 
                    <x-slot name="trigger">
                        <button id="btn-notification" class="relative p-2 mr-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span id="badge-notification" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div style="width: 320px;" class="bg-white dark:bg-[#1e293b] rounded-md shadow-lg border dark:border-gray-700 overflow-hidden">
                            <div class="px-4 py-3 font-bold text-xs text-white bg-[#0f2942] dark:bg-[#0b1b2b] uppercase tracking-wide">
                                🔔 Histori Notifikasi
                            </div>
                            <div class="max-h-80 overflow-y-auto bg-white dark:bg-[#1e293b]">
                                @forelse(Auth::user()->notifications()->take(10)->get() as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 border-b border-gray-100 dark:border-gray-700 transition duration-150 {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-gray-800/50 hover:bg-blue-100' : 'hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                        <p class="text-sm text-gray-800 dark:text-gray-200 mb-1 {{ is_null($notification->read_at) ? 'font-bold' : 'font-medium' }}">{{ $notification->data['message'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400 flex flex-col items-center">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        Tidak ada histori notifikasi
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white bg-transparent focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-white dark:bg-[#1e293b]">
                            <x-dropdown-link :href="route('profile.edit')" class="dark:text-gray-300 dark:hover:bg-gray-700">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" class="dark:text-gray-300 dark:hover:bg-gray-700">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
            
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden dark:bg-[#1e293b]">
    </div>
</nav>