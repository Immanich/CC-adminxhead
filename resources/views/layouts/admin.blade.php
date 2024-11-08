<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-full bg-[#e7ecfe] text-black w-64 p-4 flex flex-col justify-between">
            <div>
                <!-- Logo and Title -->
                <div class="flex items-center px-2 mb-6">
                    <a href="">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                    <span class="text-lg font-bold ml-2">
                        Citizen's Charter
                    </span>
                </div>

                <!-- User Display -->
                <div class="flex flex-col items-center mb-8">
                    @if(Auth::check())
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                        </div>
                        <div class="text-gray-700 font-semibold mt-2">
                            {{ Auth::user()->username }}
                        </div>
                    @endif
                </div>

                <!-- Navigation Sections -->
                <nav class="space-y-2">
                    {{-- <div class="text-gray-600 font-semibold text-center">General Information</div> --}}
                    <a href="{{ route('mvmsp') }}" class="flex items-center py-2.5 px-4 rounded transition-colors duration-300 {{ request()->is('mvmsp') ? 'bg-[#9fb3fb] text-white' : 'bg-[#cfd9fd] hover:bg-[#9fb3fb]' }}">
                        <i class="fas fa-info-circle mr-3"></i> MVMSP
                    </a>
                    <a href="#" class="flex items-center py-2.5 px-4 rounded transition-colors duration-300 {{ request()->is('org-chart') ? 'bg-[#9fb3fb] text-white' : 'bg-[#cfd9fd] hover:bg-[#9fb3fb]' }}">
                        <i class="fas fa-sitemap mr-3"></i> ORG. CHART
                    </a>
                    <a href="#" class="flex items-center py-2.5 px-4 rounded transition-colors duration-300 {{ request()->is('elected-officials') ? 'bg-[#9fb3fb] text-white' : 'bg-[#cfd9fd] hover:bg-[#9fb3fb]' }}">
                        <i class="fas fa-user-tie mr-3"></i> ELECTED OFFICIALS
                    </a>

                    {{-- <div class="text-gray-600 font-semibold text-center mt-6">Administration</div> --}}
                    <a href="{{ route('offices') }}" class="flex items-center py-2.5 px-4 rounded transition-colors duration-300 {{ request()->routeIs('offices') ? 'bg-[#9fb3fb] text-white' : 'bg-[#cfd9fd] hover:bg-[#9fb3fb]' }}">
                        <i class="fas fa-building mr-3"></i> OFFICES
                    </a>
                    <a href="{{ route('events.page') }}" class="flex items-center py-2.5 px-4 rounded transition-colors duration-300 {{ request()->is('events') ? 'bg-[#9fb3fb] text-white' : 'bg-[#cfd9fd] hover:bg-[#9fb3fb]' }}">
                        <i class="fas fa-calendar-alt mr-3"></i> EVENTS
                    </a>

                    @role('admin')
                    <a href="{{ route('pendings') }}" class="flex items-center py-2.5 px-4 rounded transition-colors duration-300 {{ request()->routeIs('pendings') ? 'bg-[#9fb3fb] text-white' : 'bg-[#cfd9fd] hover:bg-[#9fb3fb]' }}">
                        <i class="fas fa-tasks mr-3"></i> PENDINGS
                    </a>
                    @endrole

                    <a href="{{ route('feedbacks.index') }}" class="flex items-center py-2.5 px-4 rounded transition-colors duration-300 {{ request()->routeIs('feedbacks.index') ? 'bg-[#9fb3fb] text-white' : 'bg-[#cfd9fd] hover:bg-[#9fb3fb]' }}">
                        <i class="fas fa-comment-alt mr-3"></i> FEEDBACKS
                    </a>
                    <a href="/admin/users" class="flex items-center py-2.5 px-4 rounded transition-colors duration-300 {{ request()->is('admin.users.index') ? 'bg-[#9fb3fb] text-white' : 'bg-[#cfd9fd] hover:bg-[#9fb3fb]' }}">
                        <i class="fas fa-users mr-3"></i> USERS
                    </a>
                </nav>
            </div>

            <!-- Logout Button -->
            <div class="mb-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded flex items-center justify-center transition-colors duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 bg-white p-6 rounded-lg shadow-md min-h-screen overflow-y-auto">
            <div>
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
