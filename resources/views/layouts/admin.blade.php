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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="w-full bg-[#9fb3fb]  px-6 py-4 flex items-center justify-between fixed top-0 left-0 z-50">
            <!-- Logo and Title -->
            <div class="flex items-center">
                <a href="">
                    <x-application-logo class="h-9 w-auto fill-current text-gray-800" />
                </a>
                <span class="text-lg font-bold ml-2 text-blue-900 mb-2"> Citizen's Charter</span>
            </div>

            <!-- User and Notifications -->
            <div class="flex items-center space-x-4">
                <!-- Notifications Bell -->
                <div class="relative">
                    <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-blue-500 transition-colors duration-300">
                        <i class="fas fa-bell text-lg"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full text-xs px-1">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </div>
                <!-- User Dropdown -->
                <div class="relative z-50">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-500 transition">
                                <div>{{ Auth::user()->username }}</div>
                                <div class="ml-1">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </nav>

        <!-- Sidebar and Main Content -->
        <div class="flex flex-1 overflow-hidden mt-16">
            <!-- Sidebar -->
            <aside class="w-64 bg-[#e7ecfe] text-black p-4 space-y-3 fixed top-16 left-0 h-[calc(100%-4rem)] overflow-y-auto">
                <!-- Navigation Links -->
                <nav class="space-y-3">
                    <div class="text-gray-600 font-semibold text-center">General Information</div>

                    <a href="{{ route('mvmsp') }}" class="sidebar-link {{ request()->is('mvmsp') ? 'active' : '' }}">
                        <i class="fas fa-info-circle mr-3"></i> MVMSP
                    </a>
                    <a href="#" class="sidebar-link {{ request()->is('org-chart') ? 'active' : '' }}">
                        <i class="fas fa-sitemap mr-3"></i> ORG. CHART
                    </a>
                    <a href="#" class="sidebar-link {{ request()->is('elected-officials') ? 'active' : '' }}">
                        <i class="fas fa-user-tie mr-3"></i> ELECTED OFFICIALS
                    </a>

                    <div class="text-gray-600 font-semibold text-center">Administration</div>

                    <a href="{{ route('offices') }}" class="sidebar-link {{ request()->routeIs('offices') ? 'active' : '' }}">
                        <i class="fas fa-building mr-3"></i> OFFICES
                    </a>
                    <a href="{{ route('events.page') }}" class="sidebar-link {{ request()->is('events') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt mr-3"></i> EVENTS
                    </a>

                    @role('admin')
                    <a href="{{ route('pendings') }}" class="sidebar-link {{ request()->routeIs('pendings') ? 'active' : '' }}">
                        <i class="fas fa-tasks mr-3"></i> PENDINGS
                    </a>
                    @endrole

                    <a href="{{ route('feedbacks.index') }}" class="sidebar-link {{ request()->routeIs('feedbacks.index') ? 'active' : '' }}">
                        <i class="fas fa-comment-alt mr-3"></i> FEEDBACKS
                    </a>

                    @role('admin|user')
                    <a href="/admin/users" class="sidebar-link {{ request()->is('admin.users.index') ? 'active' : '' }}">
                        <i class="fas fa-users mr-3"></i> USERS
                    </a>
                    @endrole
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 bg-gray-100 p-6 ml-64 overflow-y-auto">
                <div>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
            color: #4a5568;
        }

        .sidebar-link:hover {
            background-color: #9fb3fb;
            color: #fff;
        }

        .sidebar-link.active {
            background-color: #9fb3fb;
            color: #fff;
        }
    </style>
</body>
</html>
