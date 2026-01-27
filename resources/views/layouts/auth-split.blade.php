<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white/90 backdrop-blur-sm shadow-lg rounded-2xl p-8 ring-1 ring-indigo-100">
                        {{ $slot }}
                    </div>
                    <div class="hidden lg:flex items-center justify-center">
                        <div class="w-full max-w-xl">
                            <img src="{{ asset('img/church-photo.jpg') }}" alt="Immaculate Conception Catholic Church, North Lewisburg, Ohio" class="w-full rounded-2xl shadow-xl object-cover ring-1 ring-black/5">
                            <p class="mt-2 text-xs text-gray-500 text-center">Photo via Wikimedia Commons (Nheyob, CC BY-SA 3.0)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
