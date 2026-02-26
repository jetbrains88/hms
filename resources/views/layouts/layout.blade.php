<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hospital Management System')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Add this line -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet"/>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jsdelivr.js -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Roboto', 'sans-serif'], // Update default font to Roboto
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        secondary: {
                            500: '#10b981',
                            600: '#059669',
                        },
                        md: {
                            green: '#4caf50',
                            teal: '#009688',
                            cyan: '#00bcd4',
                            blue: '#2196f3',
                            navy: '#3c4858',
                            indigo: '#3f51b5',
                            purple: '#9c27b0',
                            red: '#f44336',
                            rose: '#e91e63',
                            yellow: '#ffeb3b',
                            orange: '#ff9800',
                            black: '#212529',
                            gray: '#6c757d',
                            'light-gray': '#d2d2d2',
                            white: '#fff',

                        },
                        'purple-dark': { // Or any name you prefer, e.g., 'my-purple'
                            50: '#F4F2FA', // Example: Lighter shade from generator
                            100: '#EAE7F5',
                            200: '#D0C9E8',
                            300: '#B6B0D9',
                            400: '#9D97C5',
                            500: '#837BC0', // Your base color might land here
                            600: '#6861A2', // Or here, adjust based on generator
                            700: '#4E4685',
                            800: '#342E67',
                            900: '#1A174A',
                            950: '#0E0C2B', // Example: Darkest shade from generator
                        },
                        navy: { // Your custom color name (e.g., 'midnight', 'deep-blue')
                            50: '#e0e3f1', // Very light shade
                            100: '#c2c7e3',
                            200: '#a3adce',
                            300: '#8592b9',
                            400: '#6676a4',
                            500: '#485b8f', // Mid-tone
                            600: '#2a407a',
                            700: '#1a326d',
                            800: '#12245c', // Darker shade
                            900: '#0a164e', // Near your target
                            950: '#07104a', // Even darker
                            // You can also just define a single color if you don't need shades:
                            // 'midnight': '#10165c',
                        },
                        "jade": {
                            50: "#BDFFDF",
                            100: "#6CFFC2",
                            200: "#0DE8A4",
                            300: "#0ACC90",
                            400: "#07B17C",
                            500: "#059669",
                            600: "#037753",
                            700: "#02593D",
                            800: "#013D29",
                            900: "#002316",
                            950: "#00150B"
                        },
                        "maroon": {
                            50: "#FEF1F1",
                            100: "#FCDEDE",
                            200: "#FAC1C1",
                            300: "#F89D9D",
                            400: "#F77B7B",
                            500: "#F64A4A",
                            600: "#E22525",
                            700: "#B91C1C",
                            800: "#811010",
                            900: "#490505",
                            950: "#330303"
                        },
                        "violet-x": {
                            50: "#F2EFFE",
                            100: "#E5DFFD",
                            200: "#CBBEFB",
                            300: "#B39DFA",
                            400: "#9D7BF8",
                            500: "#8856F5",
                            600: "#7422F1",
                            700: "#5817BA",
                            800: "#3D0D85",
                            900: "#240554",
                            950: "#17023B"
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-in': 'slideIn 0.3s ease-out',
                    },
                    keyframes: {
                        slideIn: {
                            '0%': {
                                transform: 'translateX(-100%)'
                            },
                            '100%': {
                                transform: 'translateX(0)'
                            },
                        }
                    }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            // Check if notification system is loaded
            if (typeof showNotification !== 'undefined') {

                // Handle multiple validation errors with delay between them
                @if ($errors->any())
                @php
                    $errorsArray = $errors->all();
                @endphp

                @foreach ($errorsArray as $index => $error)
                // Stagger notifications to prevent overlap
                setTimeout(function () {
                    showNotification(
                        '{{ addslashes($error) }}',
                        'error',
                        'Validation Error',
                        8000 // 8 seconds for errors
                    );
                }, {{ $index * 300 }}); // 300ms delay between each
                @endforeach
                @endif

                // Single session notifications
                @if (session('success'))
                setTimeout(function () {
                    showNotification('{{ addslashes(session('success')) }}', 'success');
                }, 100);
                @endif

                @if (session('error'))
                setTimeout(function () {
                    showNotification('{{ addslashes(session('error')) }}', 'error');
                }, 100);
                @endif

                @if (session('warning'))
                setTimeout(function () {
                    showNotification('{{ addslashes(session('warning')) }}', 'warning');
                }, 100);
                @endif

                @if (session('info'))
                setTimeout(function () {
                    showNotification('{{ addslashes(session('info')) }}', 'info');
                }, 100);
                @endif

                // Hide original session notifications
                setTimeout(() => {
                    const sessionContainer = document.getElementById('session-notifications');
                    if (sessionContainer) {
                        sessionContainer.remove();
                    }
                }, 500);

            } else {
                console.error('Notification system not loaded');

                // Fallback: Show alerts for errors
                @if ($errors->any())
                alert('Please fix the following errors:\n\n{{ implode("\n", $errors->all()) }}');
                @endif
            }
        });
    </script>

    {{-- <link rel="stylesheet" href="{{ asset('css/notification.css') }}"> --}}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> --}}
    <style>
        .sidebar-link {
            @apply flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 rounded-lg mx-2;
        }

        .sidebar-link.active {
            @apply bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-600 font-semibold border-l-4 border-blue-500;
        }

        .badge {
            @apply px-2 py-1 text-xs font-bold rounded-full;
        }

        .badge-admin {
            @apply bg-purple-100 text-purple-800;
        }

        .badge-doctor {
            @apply bg-blue-100 text-blue-800;
        }

        .badge-nurse {
            @apply bg-green-100 text-green-800;
        }

        .badge-pharmacy {
            @apply bg-yellow-100 text-yellow-800;
        }

        .badge-lab {
            @apply bg-pink-100 text-pink-800;
        }

        .badge-reception {
            @apply bg-indigo-100 text-indigo-800;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            @apply bg-gray-100;
        }

        ::-webkit-scrollbar-thumb {
            @apply bg-gray-300 rounded-full;
        }

        ::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-400;
        }

        /* Keep your existing animation styles */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }

        .animate-slide-out {
            animation: slideOut 0.3s ease-in forwards;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
<!-- Notifications Container -->
{{-- @include('components.simple-notification') --}}
<!-- Main Container -->
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-xl border-r border-gray-200 flex flex-col">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-hospital text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">MedCare</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Hospital Management</p>
                </div>
            </div>
        </div>

        <!-- User Info -->
        {{--        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">--}}
        {{--            <div class="flex items-center gap-3">--}}
        {{--                <div class="relative">--}}
        {{--                    <div--}}
        {{--                        class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center shadow-md">--}}
        {{--                        <i class="fas fa-user-md text-white"></i>--}}
        {{--                    </div>--}}
        {{--                    @if (auth()->user()->is_active)--}}
        {{--                        <div--}}
        {{--                            class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white">--}}
        {{--                        </div>--}}
        {{--                    @endif--}}
        {{--                </div>--}}
        {{--                <div class="flex-1 min-w-0">--}}
        {{--                    <p class="font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>--}}
        {{--                    <div class="flex items-center gap-2 mt-1">--}}
        {{--                        @if (auth()->user()->role)--}}
        {{--                            <span class="badge badge-{{ strtolower(auth()->user()->role->name) }}">--}}
        {{--                                    {{ auth()->user()->role->display_name ?? auth()->user()->role->name }}--}}
        {{--                                </span>--}}
        {{--                        @endif--}}
        {{--                        <span class="text-xs text-gray-500">{{ auth()->user()->email }}</span>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <!-- Navigation -->

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4">
            <!-- Quick Stats -->
            @if (auth()->user()->role_id == 2)
                <!-- Doctor role ID -->
                <div class="px-4 mb-4">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-3">
                        <div class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Today's Summary
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-900">12</div>
                                <div class="text-[10px] text-blue-600">Patients</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-900">45m</div>
                                <div class="text-[10px] text-green-600">Avg. Time</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Common Links -->
            <div class="px-2">
                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </div>

            <!-- Role-Based Navigation -->
            <!-- Admin Links -->
            @hasAnyRole(['admin'])
            <div class="px-4 py-2 mt-4">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Administration
                </div>
            </div>
            <div class="px-2 space-y-1">
                <ul>
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line w-5 text-center"></i>
                            <span class="ml-3">Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}"
                           class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog w-5 text-center"></i>
                            <span class="ml-3">User Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles') }}"
                           class="sidebar-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                            <i class="fas fa-user-tag w-5 text-center"></i>
                            <span class="ml-3">Roles & Permissions</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endhasAnyRole

            <!-- Doctor Links -->
            @hasAnyRole(['doctor'])
            <div class="px-4 py-2 mt-4">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center">
                    <i class="fas fa-stethoscope mr-2"></i>
                    Medical
                </div>
            </div>
            <div class="px-2 space-y-1">
                <ul>
                    <li>
                        <a href="{{ route('doctor.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-center"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('doctor.consultancy') }}"
                           class="sidebar-link {{ request()->routeIs('doctor.consultancy*') ? 'active' : '' }}">
                            <i class="fas fa-clinic-medical w-5 text-center"></i>
                            <span class="ml-3">Consultations</span>
                            @if (isset($totalWaiting) && $totalWaiting > 0)
                                <span
                                    class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
                                            {{ $totalWaiting }}
                                        </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('doctor.e-consultancy') }}"
                           class="sidebar-link {{ request()->routeIs('doctor.e-consultancy') ? 'active' : '' }}">
                            <i class="fas fa-video w-5 text-center"></i>
                            <span class="ml-3">E-Consultation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('doctor.reports') }}"
                           class="sidebar-link {{ request()->routeIs('doctor.reports*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar w-5 text-center"></i>
                            <span class="ml-3">Reports & Analytics</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endhasAnyRole
            <!-- Reception Links -->
            @hasAnyRole(['reception'])
            <div class="px-4 py-2 mt-4">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center">
                    <i class="fas fa-headset mr-2"></i>
                    Reception
                </div>
            </div>
            <div class="px-2 space-y-1">
                <ul>
                    <li>
                        <a href="{{ route('reception.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('reception.*') ? 'active' : '' }}">
                            <i class="fas fa-user-plus w-5 text-center"></i>
                            <span class="ml-3">Patient Registration</span>
                        </a>
                    </li>
                    <!-- In your navigation file -->
                    <li>
                        <a href="{{ route('reception.patients.index') }}"
                           class="sidebar-link {{ request()->routeIs('reception.patients.*') ? 'active' : '' }}">
                            {{--                           class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">--}}
                            <i class="fas fa-users text-center"></i>
                            <span class="ml-3">Patient Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('live.queue') }}" target="_blank" class="sidebar-link">
                            <i class="fas fa-tv w-5 text-center"></i>
                            <span class="ml-3">Live Queue Display</span>
                            <span class="ml-auto text-blue-500">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </span>
                        </a>
                    </li>
                </ul>
            </div>
            @endhasAnyRole

            <!-- Pharmacy Links -->
            @hasAnyRole(['pharmacy'])
            <div class="px-4 py-2 mt-4">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center">
                    <i class="fas fa-pills mr-2"></i>
                    Pharmacy
                </div>
            </div>
            <div class="px-2 space-y-1">
                <ul>
                    <li>
                        <a href="{{ route('pharmacy.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-center"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pharmacy.prescriptions.index') }}"
                           class="sidebar-link {{ request()->routeIs('pharmacy.prescriptions.*') ? 'active' : '' }}">
                            <i class="fas fa-prescription w-5 text-center"></i>
                            <span class="ml-3">Prescriptions</span>
                            @if ($pharmacyStats['pending_prescriptions'] ?? 0)
                                <span
                                    class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
                                            {{ $pharmacyStats['pending_prescriptions'] }}
                                        </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pharmacy.inventory') }}"
                           class="sidebar-link {{ request()->routeIs('pharmacy.inventory') || request()->routeIs('pharmacy.inventory.*') || request()->routeIs('pharmacy.medicines.*') ? 'active' : '' }}">
                            <i class="fas fa-boxes w-5 text-center"></i>
                            <span class="ml-3">Inventory</span>
                            @if ($pharmacyStats['low_stock_items'] ?? 0)
                                <span
                                    class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
                                            {{ $pharmacyStats['low_stock_items'] }}
                                        </span>
                            @endif
                            @if ($pharmacyStats['out_of_stock_items'] ?? 0)
                                <span
                                    class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
                                            {{ $pharmacyStats['out_of_stock_items'] }}
                                        </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pharmacy.dispense.history') }}"
                           class="sidebar-link {{ request()->routeIs('pharmacy.dispense.history') ? 'active' : '' }}">
                            <i class="fas fa-history w-5 text-center"></i>
                            <span class="ml-3">Dispense History</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pharmacy.reports') }}"
                           class="sidebar-link {{ request()->routeIs('pharmacy.reports') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar w-5 text-center"></i>
                            <span class="ml-3">Reports</span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('pharmacy.alerts') }}"
                           class="sidebar-link {{ request()->routeIs('pharmacy.alerts') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-triangle w-5 text-center"></i>
                            <span class="ml-3">Stock Alerts</span>
                            @if ($pharmacyStats['active_alerts'] ?? 0)
                                <span
                                    class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
                                            {{ $pharmacyStats['active_alerts'] }}
                                        </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
            @endhasAnyRole

            <!-- Nurse Links -->
            @hasAnyRole(['nurse'])
            <div class="px-4 py-2 mt-4">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center">
                    <i class="fas fa-user-nurse mr-2"></i>
                    Nursing
                </div>
            </div>
            <div class="px-2 space-y-1">
                <ul>
                    <li>
                        <a href="{{ route('nurse.dashboard') }}" class="sidebar-link">
                            <i class="fas fa-heartbeat w-5 text-center"></i>
                            <span class="ml-3">Vitals Recording</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-procedures w-5 text-center"></i>
                            <span class="ml-3">Ward Management</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endhasAnyRole

            <!-- Lab Links -->
            @hasAnyRole(['lab'])
            <div class="px-4 py-2 mt-4">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center">
                    <i class="fas fa-flask mr-2"></i>
                    Laboratory
                </div>
            </div>
            <div class="px-2 space-y-1">
                <ul>
                    <li>
                        <a href="{{ route('lab.dashboard') }}" class="sidebar-link">
                            <i class="fas fa-vial w-5 text-center"></i>
                            <span class="ml-3">Lab Tests</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-file-pdf w-5 text-center"></i>
                            <span class="ml-3">Generate Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endhasAnyRole
        </nav>

        <!-- Bottom Section -->
        <div class="border-t border-gray-200 p-4 bg-gradient-to-r from-gray-50 to-white">
            <!-- System Status -->
            <div class="mb-3">
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                    <span>System Status</span>
                    <span class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                                    Online
                                </span>
                </div>
                <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 w-3/4"></div>
                </div>
            </div>

            <!-- Logout Button -->
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-lg hover:from-gray-200 hover:to-gray-300 transition-all shadow-sm">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span class="font-medium">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Button (Hidden on desktop) -->
                    <button id="mobile-menu-button" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="hidden md:block">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">
                                        <i class="fas fa-home"></i>
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <span class="text-gray-500">@yield('page-title', 'Dashboard')</span>
                                    </div>
                                </li>
                                @hasSection('breadcrumb')
                                    <li aria-current="page">
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                            <span class="text-gray-700">@yield('breadcrumb')</span>
                                        </div>
                                    </li>
                                @endif
                            </ol>
                        </nav>
                        <h1 class="text-xl font-bold text-gray-900 mt-1">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-500">@yield('page-description', 'Welcome to MedCare Hospital Management')</p>
                    </div>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button id="notification-button" class="p-2 text-gray-500 hover:text-gray-700 relative">
                            <i class="fas fa-bell text-lg"></i>
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center animate-pulse">
                                    3
                                </span>
                        </button>
                        <!-- Notification Dropdown (Hidden by default) -->
                        <div id="notification-dropdown"
                             class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                            <div class="p-4 border-b">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-bold text-gray-900">Notifications</h3>
                                    <span class="text-xs text-blue-600 cursor-pointer">Mark all as read</span>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <!-- Notification items would go here -->
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:block text-right">
                        <div class="text-xs text-gray-500">Current Time</div>
                        <div id="current-time" class="font-mono font-bold text-gray-900"></div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                            <div
                                class="w-9 h-9 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center shadow-md">
                                    <span
                                        class="text-white font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gradient-to-b from-gray-50 to-white">
            <div class="p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div
                        class="mb-6 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-sm animate-slide-in">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium">Success!</p>
                                <p class="text-sm mt-1">{{ session('success') }}</p>
                            </div>
                            <button type="button"
                                    class="ml-auto -mx-1.5 -my-1.5 text-green-500 hover:text-green-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-sm animate-slide-in">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium">Error!</p>
                                <p class="text-sm mt-1">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-6 bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium">Please fix the following errors:</p>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4 px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm text-gray-500 mb-2 md:mb-0">
                    <span class="font-medium text-gray-700">MedCare Hospital Management</span>
                    &copy; {{ date('Y') }} - v1.0.0
                </div>
                <div class="flex items-center space-x-4">
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-database mr-1"></i>
                            Last sync: <span id="last-sync">Just now</span>
                        </span>
                    <div class="flex items-center text-xs text-gray-500">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                        System Operational
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://unpkg.com/@material-tailwind/html@latest/scripts/dismissible.js"></script>
<script src="https://unpkg.com/@material-tailwind/html@latest/scripts/ripple.js"></script>
<script src="{{ asset('js/notification.js') }}"></script>

<!-- JavaScript -->
<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });

        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = `${dateString} | ${timeString}`;
        }
    }

    setInterval(updateTime, 1000);
    updateTime();

    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('hidden');
    });

    // Notification dropdown
    const notificationButton = document.getElementById('notification-button');
    const notificationDropdown = document.getElementById('notification-dropdown');

    if (notificationButton && notificationDropdown) {
        notificationButton.addEventListener('click', function (e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!notificationButton.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });
    }

    // Update last sync time
    function updateLastSync() {
        const lastSyncElement = document.getElementById('last-sync');
        if (lastSyncElement) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            lastSyncElement.textContent = timeString;
        }
    }

    setInterval(updateLastSync, 60000); // Update every minute

    // Auto-dismiss success messages after 5 seconds
    setTimeout(() => {
        const successMessages = document.querySelectorAll('[data-dismiss="success"]');
        successMessages.forEach(msg => {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 300);
        });
    }, 5000);

    // Add active class to current route
    document.addEventListener('DOMContentLoaded', function () {
        const currentPath = window.location.pathname;
        const sidebarLinks = document.querySelectorAll('.sidebar-link');

        sidebarLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath ||
                link.getAttribute('href')?.startsWith(currentPath)) {
                link.classList.add('active');
            }
        });
    });

    // Initialize tooltips
    document.querySelectorAll('[data-tooltip]').forEach(el => {
        el.addEventListener('mouseenter', function () {
            const tooltip = document.createElement('div');
            tooltip.className =
                'absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;
            tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;

            this.tooltip = tooltip;
        });

        el.addEventListener('mouseleave', function () {
            if (this.tooltip) {
                this.tooltip.remove();
            }
        });
    });
</script>

@stack('scripts')
</body>

</html>
