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

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('assets/images/logo.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center 40%;
            opacity: 0.03;
            z-index: -1; /
            margin-top: 15px;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased" style="background-color: #E7ECFE;">
    <div class="background-image"></div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
        <div class="flex items-center space-x-4 mb-6">
            <a href="/">
                <x-application-logo class="w-20 h-10 fill-current text-gray-500" />
            </a>
            <span class="text-2xl font-bold text-black mb-3">Citizen's Charter</span>
        </div>

        <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
