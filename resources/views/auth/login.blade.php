<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="animate-fade-in-up" style="animation-delay: 0.1s;">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300 font-semibold mb-2 block" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <x-text-input 
                    id="email" 
                    class="block mt-1 w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900 transition-all form-input-animate" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    placeholder="Masukan email Anda"
                    required 
                    autofocus 
                    autocomplete="username" 
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 dark:text-red-400 text-sm" />
        </div>

        <!-- Password -->
        <div class="animate-fade-in-up" style="animation-delay: 0.2s;">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300 font-semibold mb-2 block" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <x-text-input 
                    id="password" 
                    class="block mt-1 w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900 transition-all form-input-animate"
                    type="password"
                    name="password"
                    placeholder="Masukan password Anda"
                    required 
                    autocomplete="current-password" 
                />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 dark:text-red-400 text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between animate-fade-in-up" style="animation-delay: 0.3s;">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-600 cursor-pointer transition-all" name="remember">
                <span class="ms-2 text-sm text-gray-700 dark:text-gray-300 select-none">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="animate-fade-in-up" style="animation-delay: 0.4s;">
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105 btn-animate">
                <div class="flex items-center justify-center">
                    <span>Masuk</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </button>
        </div>

        <!-- Divider -->
        <div class="relative animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400">atau</span>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 rounded-lg animate-fade-in-up" style="animation-delay: 0.6s;">
            <p class="text-sm text-blue-900 dark:text-blue-100">
                <strong>Demo Account:</strong><br>
                📧 admin@polres.com<br>
                🔐 12345678
            </p>
        </div>
    </form>
</x-guest-layout>
