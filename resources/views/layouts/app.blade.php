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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 md:flex">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 bg-white dark:bg-gray-800 shadow-md md:fixed md:inset-y-0 md:left-0 transform md:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out z-20">
                <div class="p-4 border-b dark:border-gray-700">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-semibold text-gray-800 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                <nav class="mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center mt-2 py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Customers
                    </a>
                    <a href="{{ route('admin.phones.index') }}" class="flex items-center mt-2 py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Phones
                    </a>
                    <a href="{{ route('admin.repairs.index') }}" class="flex items-center mt-2 py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Repairs
                    </a>
                    <a href="{{ route('admin.repair-parts.index') }}" class="flex items-center mt-2 py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h4M8 7a2 2 0 012-2h4a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2v-4a2 2 0 012-2h4a2 2 0 002-2z"></path></svg>
                        Repair Parts (Stock)
                    </a>
                </nav>
            </aside>

            <div class="flex flex-col flex-1 md:ml-64">
                <!-- Page Heading / Top Bar -->
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                        <!-- Hamburger for mobile -->
                        <button @click="open = !open" class="md:hidden text-gray-500 focus:outline-none focus:text-gray-700">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="flex-1">
                            @if (isset($header))
                                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                    {{ $header }}
                                </h2>
                            @endif
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="relative z-10 block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                                <!-- Placeholder for user avatar or initials -->
                                <span class="h-full w-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 text-xs">
                                    {{ Auth::user()->name[0] ?? 'U' }}
                                </span>
                            </button>
                            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-20" style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Profile') }}</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6">
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
        </div>
        <script>
            // Simple Alpine.js-like toggle for mobile menu and dropdown
            document.addEventListener('alpine:init', () => {
                Alpine.data('layout', () => ({
                    open: false,
                    dropdownOpen: false,
                }))
            })
        </script>
    </body>
</html>
