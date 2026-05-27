<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

       <div>
            <x-input-label for="avatar" :value="__('Foto Profil Pengguna')" class="text-gray-700 dark:text-gray-300 font-bold" />
            <div class="flex items-center gap-5 mt-2 bg-gray-50 dark:bg-[#0f172a] p-4 rounded-xl border border-gray-200 dark:border-gray-700 w-fit">
                
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover border-2 border-blue-500 shadow-md aspect-square">
                @else
                    <div class="w-16 h-16 rounded-full bg-[#0f2942] dark:bg-blue-600 flex items-center justify-center text-white font-bold uppercase border-2 border-gray-200 dark:border-gray-700 shadow-sm text-xl">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                @endif
                
              <input id="avatar" name="avatar" type="file" accept="image/*" class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition-colors" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Nomor WhatsApp')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="Contoh: 628123456789" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4">
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        {{ __('Save') }}
    </button>
</div>
    </form>
</section>