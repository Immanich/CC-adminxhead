<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment-timezone@0.5.34/builds/moment-timezone-with-data.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="w-full bg-gradient-to-r from-[#c0d2fc] to-[#9fb3fb] shadow-md px-6 py-4 flex items-center justify-between fixed top-0 left-0 z-50">
            <!-- Logo and Title -->
            <div class="flex items-center">
                <a href="">
                    <x-application-logo class="h-9 w-auto fill-current text-gray-800" />
                </a>
                <span class="text-2xl font-bold ml-2 text-blue-900 mb-2"> Citizen's Charter</span>
            </div>

            <!-- User and Notifications -->
            <div class="flex items-center space-x-4">
                <!-- Notifications Bell -->
                <div class="relative">
                    <!-- Notification Icon -->
                    <button id="notificationBell" class="text-gray-700 hover:text-blue-500 transition-colors duration-300">
                        <i class="fas fa-bell text-lg"></i>
                        <span id="unreadCountBadge" style="display:none;"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    </button>


                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50">
                        <div class="p-4 border-b flex justify-between items-center">
                            <h3 class="font-bold text-lg">Notifications</h3>
                            <button id="dropdownOptionsButton" class="text-gray-500 hover:text-[#9fb3fb] focus:text-[#9fb3fb] transition">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>

                        </div>
                        <div id="dropdownOptions" class="hidden px-4 py-2">
                            <button id="markAllAsRead" class="text-sm text-blue-500 hover:underline">Mark All as Read</button>
                        </div>
                        <div id="dropdownNotifications" class="max-h-64 overflow-y-auto">
                            <p class="p-4 text-gray-500 text-sm text-center">Loading...</p>
                        </div>
                        <div class="p-4 border-t text-center">
                            <a href="{{ route('notifications.index') }}" class="text-blue-500 text-sm">See All Notifications</a>
                        </div>
                    </div>
                </div>




                <!-- User Dropdown -->
                <div class="relative z-50">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-m font-bold text-gray-700 hover:text-blue-900 transition">
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
                                    {{ __('Log out') }}
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
            <aside class="w-64 bg-[#e7ecfe] shadow-md text-black p-4 space-y-3 fixed top-16 left-0 h-[calc(100%-4rem)] overflow-y-auto">
                <!-- Navigation Links -->
                <nav class="space-y-3">
                    <div class="text-gray-600 font-bold text-center text-lg p-2">General Information</div>

                    <a href="{{ route('mvmsp') }}" class="sidebar-link font-semibold {{ request()->is('mvmsp') ? 'active' : '' }}">
                        <i class="fas fa-info-circle mr-3"></i> MVMSP
                    </a>
                    <a href="{{ route('org-chart') }}" class="sidebar-link font-semibold {{ request()->is('pages/org-chart') ? 'active' : '' }}">
                        <i class="fas fa-sitemap mr-3"></i> ORG. CHART
                    </a>

                    <a href="{{ route('municipal-officials') }}" class="sidebar-link font-semibold {{ request()->is('municipal-officials') ? 'active' : '' }}">
                        <i class="fas fa-user-tie mr-3"></i> MUNICIPAL OFFICIALS
                    </a>

                    {{-- <hr class="mb-6 border-1 border-gray-400"> --}}

                    <div class="text-gray-600 font-bold text-center text-lg p-2">Administration</div>

                    @role('admin|head')
                    <a href="/admin/users" class="sidebar-link font-semibold {{ request()->is('admin/users') ? 'active' : '' }}">
                        <i class="fas fa-users mr-3"></i> USERS
                    </a>
                    @endrole

                    <a href="{{ route('events.page') }}"
   class="sidebar-link font-semibold {{ request()->is('events') || request()->routeIs('events.show') ? 'active' : '' }}">
    <i class="fas fa-calendar-alt mr-3"></i> EVENTS
</a>


                    <a href="{{ route('offices') }}"
   class="sidebar-link font-semibold {{ request()->routeIs('offices*') || request()->routeIs('services.show') ? 'active' : '' }}">
    <i class="fas fa-building mr-3"></i> OFFICES
</a>



                    @role('admin')
                    <a href="{{ route('pendings') }}"
   class="sidebar-link font-semibold {{ request()->is('pendings') || request()->routeIs('pending.events', 'pending.services') ? 'active' : '' }}">
    <i class="fas fa-tasks mr-3"></i> PENDINGS
</a>
                    @endrole

                    <a href="{{ route('feedbacks.index') }}" class="sidebar-link font-semibold {{ request()->routeIs('feedbacks.index') ? 'active' : '' }}">
                        <i class="fas fa-comment-alt mr-3"></i> FEEDBACKS
                    </a>

                    <a href="{{ route('events.archived') }}" class="sidebar-link font-semibold {{ request()->is('archived') ? 'active' : '' }}">
                        <i class="fas fa-archive mr-3"></i> ARCHIVES
                    </a>
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
   <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cache elements and create a unique ID or class to prevent conflicts with other scripts
        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const dropdownNotifications = document.getElementById('dropdownNotifications');
        const unreadCountBadge = document.getElementById('unreadCountBadge');
        const dropdownOptionsButton = document.getElementById('dropdownOptionsButton');
        const dropdownOptions = document.getElementById('dropdownOptions');
        const markAllAsRead = document.getElementById('markAllAsRead');

        // Function to fetch notifications and update the dropdown
        function fetchNotifications() {
            fetch('{{ route('notifications.fetch') }}') // Adjust the URL as needed
                .then(response => response.json())
                .then(data => {
                    if (data.notifications.length > 0) {
                        dropdownNotifications.innerHTML = data.notifications.map(notification => `
                            <a href="/notifications/${notification.id}/read" class="block px-4 py-2 hover:bg-gray-100 transition ${notification.is_read ? 'bg-white' : 'bg-blue-50'}">
                                <div class="text-sm font-medium">${notification.title}</div>
                                <div class="text-xs text-gray-500">${notification.description}</div>
                                <div class="text-xs text-gray-400">${notification.created_at}</div>
                            </a>`).join('');
                    } else {
                        dropdownNotifications.innerHTML = `<p class="p-4 text-gray-500 text-sm text-center">No notifications</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                    dropdownNotifications.innerHTML = `<p class="p-4 text-gray-500 text-sm text-center">Error loading notifications</p>`;
                });
        }

        // Function to update the unread notification count
        function updateUnreadCount() {
            fetch('/notifications/fetch')  // Call the route that returns unread count
                .then(response => response.json())
                .then(data => {
                    const unreadCount = data.unreadCount;
                    if (unreadCount > 0) {
                        unreadCountBadge.style.display = 'flex';  // Show badge
                        unreadCountBadge.textContent = unreadCount;
                    } else {
                        unreadCountBadge.style.display = 'none';  // Hide badge if no unread notifications
                    }
                })
                .catch(error => console.error('Error fetching unread count:', error));
        }

        // Call the function immediately when the page loads to set initial unread count
        updateUnreadCount();

        // Fetch notifications only when the bell icon is clicked
        notificationBell.addEventListener('click', function (event) {
            event.stopPropagation(); // Prevent click from propagating
            notificationDropdown.classList.toggle('hidden');

            if (!notificationDropdown.classList.contains('hidden')) {
                fetchNotifications(); // Fetch notifications when dropdown is shown
            }
        });

        // Toggle the dropdown options (Mark All as Read)
        dropdownOptionsButton.addEventListener('click', function (event) {
            event.stopPropagation();
            dropdownOptions.classList.toggle('hidden');
        });

        // Mark all notifications as read
        markAllAsRead.addEventListener('click', function () {
            fetch('{{ route('notifications.markAllAsRead') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        dropdownNotifications.innerHTML = '<p class="p-4 text-gray-500 text-sm text-center">All notifications marked as read.</p>';
                        updateUnreadCount(); // Update count after marking as read
                    }
                })
                .catch(error => console.error('Error marking all as read:', error));
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (
                !notificationDropdown.contains(event.target) &&
                !notificationBell.contains(event.target) &&
                !dropdownOptionsButton.contains(event.target)
            ) {
                notificationDropdown.classList.add('hidden');
                dropdownOptions.classList.add('hidden');
            }
        });

        // Optional: Update unread count at regular intervals (e.g., 10 seconds)
        setInterval(updateUnreadCount, 10000); // Adjust the time as needed
    });
</script>

</body>
</html>
