<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Animations CSS -->
        <link rel="stylesheet" href="{{ asset('css/animations.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Figtree', sans-serif;
            }

            .gradient-bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                z-index: -1;
                animation: gradientShift 8s ease infinite;
            }

            @keyframes gradientShift {
                0%, 100% {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                }
                50% {
                    background: linear-gradient(135deg, #764ba2 0%, #f093fb 50%, #667eea 100%);
                }
            }

            .login-container {
                position: relative;
                z-index: 10;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="gradient-bg"></div>

        <div class="login-container min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Logo Section -->
            <div class="mb-8 animate-fade-in-down">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-white shadow-2xl flex items-center justify-center animate-bounce">
                        <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h1 class="mt-4 text-3xl font-bold text-white text-center">Polres Garut</h1>
                    <p class="text-purple-100 text-sm mt-2">Sistem Absensi & Monitoring</p>
                </div>
            </div>

            <!-- Login Card -->
            <div class="w-full sm:max-w-md animate-scale-in">
                <div class="relative bg-white dark:bg-gray-800 shadow-2xl overflow-hidden sm:rounded-3xl border border-purple-200 dark:border-purple-700">
                    <!-- Top Accent -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600"></div>

                    <!-- Header -->
                    <div class="px-8 pt-8 pb-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">Masuk</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-center text-sm mt-2">Silakan masuk dengan akun Anda</p>
                    </div>

                    <!-- Content -->
                    <div class="px-8 py-8">
                        {{ $slot }}
                    </div>

                    <!-- Footer -->
                    <div class="px-8 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                            © 2025 Polres Garut. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Text -->
            <div class="mt-8 text-center text-white text-sm animate-fade-in-up">
                <p>Hubungi Admin jika mengalami masalah login</p>
            </div>
        </div>
    </body>
</html>
