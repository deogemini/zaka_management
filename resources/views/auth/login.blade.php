<x-auth-split-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-xs uppercase tracking-widest text-indigo-600 font-semibold">Zaka Management System</h2>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">Bombambili Parish</h1>
        <p class="text-sm text-gray-600 mt-2">Sign in to continue. Accounts are created by the admin.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email or Phone -->
        <div>
            <x-input-label for="login" :value="__('Email au Namba ya Simu')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="email@example.com au 0712345678" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                              type="password"
                              name="password"
                              required autocomplete="current-password" />
                <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-gray-700"
                        aria-label="Onyesha/KFicha Nywila">
                    <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <circle cx="12" cy="12" r="3" stroke-width="2" stroke="currentColor"></circle>
                    </svg>
                    <svg id="iconEyeOff" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.06 10.06 0 012.223-3.592m3.68-2.507A9.967 9.967 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.996 9.996 0 01-4.122 5.225M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 space-y-4">
            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
            <x-primary-button class="w-full justify-center py-3 text-sm shadow-md">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        (function() {
            const input = document.getElementById('password');
            const btn = document.getElementById('togglePassword');
            const eye = document.getElementById('iconEye');
            const eyeOff = document.getElementById('iconEyeOff');
            if (btn && input) {
                btn.addEventListener('click', function() {
                    const showing = input.type === 'text';
                    input.type = showing ? 'password' : 'text';
                    eye.classList.toggle('hidden', !showing);
                    eyeOff.classList.toggle('hidden', showing);
                });
            }
        })();
    </script>
</x-auth-split-layout>
