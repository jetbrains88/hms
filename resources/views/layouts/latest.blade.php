<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MedCare | Smart Hospital OS')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8'},
                        secondary: {
                            500: '#10b981', 600: '#059669',
                        },
                        // Custom light futuristic palette
                        'tech-blue': {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
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
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(59, 130, 246, 0.2)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {opacity: '0', transform: 'translateY(10px)'},
                            '100%': {opacity: '1', transform: 'translateY(0)'},
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Smooth Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Sidebar Transition */
        .sidebar-transition {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Menu Item Hover Gradient */
        .menu-item-hover:hover {
            background: linear-gradient(90deg, #eff6ff 0%, #ffffff 100%);
            border-right: 3px solid #3b82f6;
        }

        /* Active Menu Item */
        .menu-item-active {
            background: linear-gradient(90deg, #eff6ff 0%, #ffffff 100%);
            border-right: 3px solid #3b82f6;
            color: #2563eb;
            font-weight: 600;
        }

        /* Accordion Rotate */
        .rotate-icon {
            transition: transform 0.3s ease;
        }

        .expanded .rotate-icon {
            transform: rotate(180deg);
        }

        /* Enhanced hover effects for sidebar links */
        .hover\:shadow-xl:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        }

        /* Color-specific shadow effects */
        .hover\:shadow-xl:hover.hover\:bg-pink-500 {
            box-shadow: 0 10px 25px -5px rgba(236, 72, 153, 0.3);
        }

        .hover\:shadow-xl:hover.hover\:bg-blue-500 {
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.3);
        }

        .hover\:shadow-xl:hover.hover\:bg-amber-500 {
            box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.3);
        }

        .hover\:shadow-xl:hover.hover\:bg-green-500 {
            box-shadow: 0 10px 25px -5px rgba(34, 197, 94, 0.3);
        }

        .hover\:shadow-xl:hover.hover\:bg-purple-500 {
            box-shadow: 0 10px 25px -5px rgba(168, 85, 247, 0.3);
        }

        /* Menu toggle button hover enhancement */
        .hover\:shadow:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Active state improvements for sidebar links */
        .bg-blue-500.shadow-xl,
        .bg-pink-500.shadow-xl,
        .bg-amber-500.shadow-xl,
        .bg-green-500.shadow-xl,
        .bg-purple-500.shadow-xl {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
        }

        /* Sidebar item hover gradient */
        .menu-item-hover:hover {
            background: linear-gradient(90deg, #f0f9ff 0%, #ffffff 100%);
            border-right: 3px solid #3b82f6;
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-600 bg-slate-50 overflow-hidden"
      x-data="{
          sidebarOpen: window.innerWidth >= 1024,
          isMobile: window.innerWidth < 1024,
          toggleSidebar() { this.sidebarOpen = !this.sidebarOpen },
          notificationsOpen: false,
          profileOpen: false,
          // Accordion Menus State
          menus: {
              admin: {{ request()->routeIs('admin.*') ? 'true' : 'false' }},
              medical: {{ request()->routeIs('doctor.*') ? 'true' : 'false' }},
              reception: {{ request()->routeIs('reception.*') ? 'true' : 'false' }},
              pharmacy: {{ request()->routeIs('pharmacy.*') ? 'true' : 'false' }},
              nurse: {{ request()->routeIs('nurse.*') ? 'true' : 'false' }},
              lab: {{ request()->routeIs('lab.*') ? 'true' : 'false' }}
          },
          toggleMenu(menu) {
              this.menus[menu] = !this.menus[menu];
          }
      }"
      @resize.window="isMobile = window.innerWidth < 1024; if(!isMobile) sidebarOpen = true"
>

<div class="flex h-screen overflow-hidden relative">

    <div x-show="sidebarOpen && isMobile"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-40 lg:hidden">
    </div>

    <aside
        class="fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-200 shadow-xl sidebar-transition flex flex-col lg:static"
        :class="sidebarOpen ? 'w-72 translate-x-0' : 'w-0 -translate-x-full lg:w-0 lg:translate-x-0 lg:overflow-hidden'">

        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100 bg-white">
            <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20 shrink-0">
                    <i class="fas fa-hospital text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">MedCare</h1>
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold">Hospital OS</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-200 shadow-xl sidebar-transition flex flex-col lg:static"
            :class="sidebarOpen ? 'w-72 translate-x-0' : 'w-0 -translate-x-full lg:w-0 lg:translate-x-0 lg:overflow-hidden'">

            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100 bg-white">
                <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20 shrink-0">
                        <i class="fas fa-hospital text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 tracking-tight">MedCare</h1>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold">Hospital OS</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

                @if (auth()->user()->role_id == 2)
                    <div class="mb-6 mx-2 overflow-hidden">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-4">
                            <div
                                class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Today's Summary
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center bg-white rounded-lg p-2 shadow-sm">
                                    <div class="text-lg font-bold text-slate-800">12</div>
                                    <div class="text-[10px] text-slate-400">Patients</div>
                                </div>
                                <div class="text-center bg-white rounded-lg p-2 shadow-sm">
                                    <div class="text-lg font-bold text-emerald-600">45m</div>
                                    <div class="text-[10px] text-slate-400">Avg Time</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 menu-item-hover {{ request()->routeIs('dashboard') ? 'menu-item-active' : 'text-slate-500' }}">
                    <i class="fas fa-home w-6 text-center text-lg"></i>
                    <span class="ml-3 truncate">Dashboard</span>
                </a>

                <!-- ADMIN SECTION -->
                @hasAnyRole(['admin'])
                <div class="mt-4">
                    <button @click="toggleMenu('admin')"
                            class="w-full flex items-center justify-between px-4 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider hover:text-blue-600 hover:shadow transition-colors rounded-lg"
                            :class="{ 'expanded': menus.admin }">
                <span class="flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i> Administration
                </span>
                        <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>
                    </button>

                    <div x-show="menus.admin" x-collapse class="pl-2 space-y-1 mt-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-blue-500 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white bg-blue-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-chart-line w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Analytics</span>
                        </a>
                        <a href="{{ route('admin.users') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-blue-500 transition-colors {{ request()->routeIs('admin.users*') ? 'text-white bg-blue-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-users-cog w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">User Management</span>
                        </a>
                        <a href="{{ route('admin.roles') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-blue-500 transition-colors {{ request()->routeIs('admin.roles*') ? 'text-white bg-blue-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-user-tag w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Roles & Permissions</span>
                        </a>
                    </div>
                </div>
                @endhasAnyRole

                <!-- DOCTOR SECTION -->
                @hasAnyRole(['doctor'])
                <div class="mt-4">
                    <button @click="toggleMenu('medical')"
                            class="w-full flex items-center justify-between px-4 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider hover:text-blue-600 hover:shadow transition-colors rounded-lg"
                            :class="{ 'expanded': menus.medical }">
                <span class="flex items-center gap-2">
                    <i class="fas fa-stethoscope"></i> Medical
                </span>
                        <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>
                    </button>

                    <div x-show="menus.medical" x-collapse class="pl-2 space-y-1 mt-1">
                        <a href="{{ route('doctor.dashboard') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-blue-500 transition-colors {{ request()->routeIs('doctor.dashboard') ? 'text-white bg-blue-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                        <a href="{{ route('doctor.consultancy') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-blue-500 transition-colors {{ request()->routeIs('doctor.consultancy*') ? 'text-white bg-blue-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-clinic-medical w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Consultations</span>
                            @if (isset($totalWaiting) && $totalWaiting > 0)
                                <span
                                    class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg shadow-rose-500/30 animate-pulse">{{ $totalWaiting }}</span>
                            @endif
                        </a>
                        <a href="{{ route('doctor.e-consultancy') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-blue-500 transition-colors {{ request()->routeIs('doctor.e-consultancy') ? 'text-white bg-blue-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-video w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">E-Consultation</span>
                        </a>
                        <a href="{{ route('doctor.reports') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-blue-500 transition-colors {{ request()->routeIs('doctor.reports*') ? 'text-white bg-blue-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-chart-bar w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Reports</span>
                        </a>
                    </div>
                </div>
                @endhasAnyRole

                <!-- RECEPTION SECTION -->
                @hasAnyRole(['reception'])
                <div class="mt-4">
                    <button @click="toggleMenu('reception')"
                            class="w-full flex items-center justify-between px-4 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider hover:text-pink-600 hover:shadow transition-colors rounded-lg"
                            :class="{ 'expanded': menus.reception }">
                <span class="flex items-center gap-2">
                    <i class="fas fa-headset"></i> Front Desk
                </span>
                        <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>
                    </button>

                    <div x-show="menus.reception" x-collapse class="pl-2 space-y-1 mt-1">
                        <a href="{{ Route::has('reception.dashboard') ? route('reception.dashboard') : url('/reception') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-pink-500 transition-colors {{ request()->routeIs('reception.dashboard') ? 'text-white bg-pink-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-user-plus w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Registration</span>
                        </a>
                        <a href="{{ route('reception.patients.index') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-pink-500 transition-colors {{ request()->routeIs('reception.patients.*') ? 'text-white bg-pink-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-users w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Patient Mgmt</span>
                        </a>
                        <a href="{{ route('live.queue') }}" target="_blank"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-pink-500 transition-colors">
                            <i class="fas fa-tv w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Live Queue</span>
                            <i class="fas fa-external-link-alt text-[9px] ml-auto opacity-50"></i>
                        </a>
                    </div>
                </div>
                @endhasAnyRole

                <!-- PHARMACY SECTION -->
                @hasAnyRole(['pharmacy'])
                <div class="mt-4">
                    <button @click="toggleMenu('pharmacy')"
                            class="w-full flex items-center justify-between px-4 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider hover:text-amber-600 hover:shadow transition-colors rounded-lg"
                            :class="{ 'expanded': menus.pharmacy }">
                <span class="flex items-center gap-2">
                    <i class="fas fa-pills"></i> Pharmacy
                </span>
                        <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>
                    </button>

                    <div x-show="menus.pharmacy" x-collapse class="pl-2 space-y-1 mt-1">
                        <a href="{{ route('pharmacy.dashboard') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-amber-500 transition-colors {{ request()->routeIs('pharmacy.dashboard') ? 'text-white bg-amber-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                        <a href="{{ route('pharmacy.prescriptions.index') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-amber-500 transition-colors {{ request()->routeIs('pharmacy.prescriptions.*') ? 'text-white bg-amber-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-prescription w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Prescriptions</span>
                            @if ($pharmacyStats['pending_prescriptions'] ?? 0)
                                <span
                                    class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $pharmacyStats['pending_prescriptions'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('pharmacy.inventory') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-amber-500 transition-colors {{ request()->routeIs('pharmacy.inventory') ? 'text-white bg-amber-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-boxes w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Inventory</span>
                            @if (($pharmacyStats['low_stock_items'] ?? 0) + ($pharmacyStats['out_of_stock_items'] ?? 0) > 0)
                                <span
                                    class="ml-auto bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ ($pharmacyStats['low_stock_items'] ?? 0) + ($pharmacyStats['out_of_stock_items'] ?? 0) }}</span>
                            @endif
                        </a>
                        <a href="{{ route('pharmacy.dispense.history') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-amber-500 transition-colors {{ request()->routeIs('pharmacy.dispense.history') ? 'text-white bg-amber-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-history w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Dispense History</span>
                        </a>
                        <a href="{{ route('pharmacy.reports') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-amber-500 transition-colors {{ request()->routeIs('pharmacy.reports') ? 'text-white bg-amber-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-chart-bar w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Reports</span>
                        </a>
                        <a href="{{ route('pharmacy.alerts') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-amber-500 transition-colors {{ request()->routeIs('pharmacy.alerts') ? 'text-white bg-amber-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-exclamation-triangle w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Stock Alerts</span>
                            @if ($pharmacyStats['active_alerts'] ?? 0)
                                <span
                                    class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $pharmacyStats['active_alerts'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                @endhasAnyRole

                <!-- NURSE SECTION -->
                @hasAnyRole(['nurse'])
                <div class="mt-4">
                    <button @click="toggleMenu('nurse')"
                            class="w-full flex items-center justify-between px-4 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider hover:text-green-600 hover:shadow transition-colors rounded-lg"
                            :class="{ 'expanded': menus.nurse }">
                <span class="flex items-center gap-2">
                    <i class="fas fa-user-nurse"></i> Nursing
                </span>
                        <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>
                    </button>

                    <div x-show="menus.nurse" x-collapse class="pl-2 space-y-1 mt-1">
                        <a href="{{ route('nurse.dashboard') }}"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-green-500 transition-colors {{ request()->routeIs('nurse.dashboard') ? 'text-white bg-green-500 font-medium shadow-xl' : '' }}">
                            <i class="fas fa-heartbeat w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Vitals</span>
                        </a>
                        <a href="#"
                           class="flex items-center px-4 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-green-500 transition-colors">
                            <i class="fas fa-procedures w-5 text-center text-xs opacity-70"></i>
                            <span class="ml-3">Wards</span>
                        </a>
                    </div>
                </div>
                @endhasAnyRole

                <!-- LAB SECTION -->
                @hasAnyRole(['lab'])
                    <div class="mt-4">
                        <button @click="toggleMenu('lab')"
                            class="w-full flex items-center justify-between px-1 py-4 text-xs font-bold text-slate-600 uppercase tracking-wider hover:text-purple-600 hover:shadow transition-colors rounded-lg"
                            :class="{ 'expanded': menus.lab }">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-flask"></i> Laboratory
                            </span>
                            <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>
                        </button>

                        <div x-show="menus.lab" x-collapse class="pl-2 space-y-1 mt-1">
                            <a href="{{ Route::has('lab.dashboard') ? route('lab.dashboard') : url('/lab/dashboard') }}"
                                class="flex items-center px-1 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-purple-500 transition-colors {{ request()->routeIs('lab.dashboard') ? 'text-white bg-purple-500 font-medium shadow-xl' : '' }}">
                                <i class="fas fa-vial w-5 text-center text-xs opacity-70"></i>
                                <span class="ml-3">Lab Tests</span>
                            </a>
                            <a href="{{ Route::has('lab.reports.index') ? route('lab.reports.index') : url('/lab/reports') }}"
                                class="flex items-center px-1 py-2.5 text-sm rounded-xl hover:shadow-xl text-slate-800 hover:text-white hover:bg-purple-500 transition-colors {{ request()->routeIs('lab.reports.*') ? 'text-white bg-purple-500 font-medium shadow-xl' : '' }}">
                                <i class="fas fa-file-pdf w-5 text-center text-xs opacity-70"></i>
                                <span class="ml-3">Reports</span>
                            </a>
                        </div>
                    </div>
                @endhasAnyRole

            </nav>

            <div class="p-4 border-t border-slate-100 bg-slate-50">
                <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                    <span>Status</span>
                    <span class="flex items-center text-emerald-500 font-bold">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-1 animate-pulse"></span> Online
            </span>
                </div>
                <div class="h-1 bg-slate-200 rounded-full overflow-hidden mb-3">
                    <div class="h-full bg-emerald-500 w-3/4"></div>
                </div>

                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center justify-center px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm group">
                    <i class="fas fa-sign-out-alt mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                    <span class="font-medium">Sign Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </aside>

        {{--        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">--}}

        {{--            @if (auth()->user()->role_id == 2)--}}
        {{--                <div class="mb-6 mx-2 overflow-hidden">--}}
        {{--                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-4">--}}
        {{--                        <div--}}
        {{--                            class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-2 flex items-center gap-2">--}}
        {{--                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Today's Summary--}}
        {{--                        </div>--}}
        {{--                        <div class="grid grid-cols-2 gap-3">--}}
        {{--                            <div class="text-center bg-white rounded-lg p-2 shadow-sm">--}}
        {{--                                <div class="text-lg font-bold text-slate-800">12</div>--}}
        {{--                                <div class="text-[10px] text-slate-400">Patients</div>--}}
        {{--                            </div>--}}
        {{--                            <div class="text-center bg-white rounded-lg p-2 shadow-sm">--}}
        {{--                                <div class="text-lg font-bold text-emerald-600">45m</div>--}}
        {{--                                <div class="text-[10px] text-slate-400">Avg Time</div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            @endif--}}

        {{--            <a href="{{ route('dashboard') }}"--}}
        {{--               class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 menu-item-hover {{ request()->routeIs('dashboard') ? 'menu-item-active' : 'text-slate-500' }}">--}}
        {{--                <i class="fas fa-home w-6 text-center text-lg"></i>--}}
        {{--                <span class="ml-3 truncate">Dashboard</span>--}}
        {{--            </a>--}}

        {{--            @hasAnyRole(['admin'])--}}
        {{--            <div class="mt-4">--}}
        {{--                <button @click="toggleMenu('admin')"--}}
        {{--                        class="w-full flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider hover:text-blue-600 transition-colors group"--}}
        {{--                        :class="{ 'expanded': menus.admin }">--}}
        {{--                        <span class="flex items-center gap-2">--}}
        {{--                            <i class="fas fa-shield-alt"></i> Administration--}}
        {{--                        </span>--}}
        {{--                    <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>--}}
        {{--                </button>--}}

        {{--                <div x-show="menus.admin" x-collapse class="pl-2 space-y-1 mt-1">--}}
        {{--                    <a href="{{ route('admin.dashboard') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-chart-line w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Analytics</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('admin.users') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.users*') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-users-cog w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">User Management</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('admin.roles') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('admin.roles*') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-user-tag w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Roles & Permissions</span>--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            @endhasAnyRole--}}

        {{--            @hasAnyRole(['doctor'])--}}
        {{--            <div class="mt-4">--}}
        {{--                <button @click="toggleMenu('medical')"--}}
        {{--                        class="w-full flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider hover:text-blue-600 transition-colors"--}}
        {{--                        :class="{ 'expanded': menus.medical }">--}}
        {{--                        <span class="flex items-center gap-2">--}}
        {{--                            <i class="fas fa-stethoscope"></i> Medical--}}
        {{--                        </span>--}}
        {{--                    <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>--}}
        {{--                </button>--}}

        {{--                <div x-show="menus.medical" x-collapse class="pl-2 space-y-1 mt-1">--}}
        {{--                    <a href="{{ route('doctor.dashboard') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('doctor.dashboard') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-tachometer-alt w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Dashboard</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('doctor.consultancy') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('doctor.consultancy*') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-clinic-medical w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Consultations</span>--}}
        {{--                        @if (isset($totalWaiting) && $totalWaiting > 0)--}}
        {{--                            <span--}}
        {{--                                class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg shadow-rose-500/30 animate-pulse">{{ $totalWaiting }}</span>--}}
        {{--                        @endif--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('doctor.e-consultancy') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('doctor.e-consultancy') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-video w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">E-Consultation</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('doctor.reports') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('doctor.reports*') ? 'text-blue-600 bg-blue-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-chart-bar w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Reports</span>--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            @endhasAnyRole--}}

        {{--            @hasAnyRole(['reception'])--}}
        {{--            <div class="mt-4">--}}
        {{--                <button @click="toggleMenu('reception')"--}}
        {{--                        class="w-full flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider hover:text-pink-600 transition-colors"--}}
        {{--                        :class="{ 'expanded': menus.reception }">--}}
        {{--                        <span class="flex items-center gap-2">--}}
        {{--                            <i class="fas fa-headset"></i> Front Desk--}}
        {{--                        </span>--}}
        {{--                    <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>--}}
        {{--                </button>--}}

        {{--                <div x-show="menus.reception" x-collapse class="pl-2 space-y-1 mt-1">--}}
        {{--                    <a href="{{ route('reception.dashboard') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-pink-600 hover:bg-pink-50 transition-colors {{ request()->routeIs('reception.dashboard') ? 'text-pink-600 bg-pink-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-user-plus w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Registration</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('reception.patients.index') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-pink-600 hover:bg-pink-50 transition-colors {{ request()->routeIs('reception.patients.*') ? 'text-pink-600 bg-pink-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-users w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Patient Mgmt</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('live.queue') }}" target="_blank"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-pink-600 hover:bg-pink-50 transition-colors">--}}
        {{--                        <i class="fas fa-tv w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Live Queue</span>--}}
        {{--                        <i class="fas fa-external-link-alt text-[9px] ml-auto opacity-50"></i>--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            @endhasAnyRole--}}

        {{--            @hasAnyRole(['pharmacy'])--}}
        {{--            <div class="mt-4">--}}
        {{--                <button @click="toggleMenu('pharmacy')"--}}
        {{--                        class="w-full flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider hover:text-amber-600 transition-colors"--}}
        {{--                        :class="{ 'expanded': menus.pharmacy }">--}}
        {{--                        <span class="flex items-center gap-2">--}}
        {{--                            <i class="fas fa-pills"></i> Pharmacy--}}
        {{--                        </span>--}}
        {{--                    <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>--}}
        {{--                </button>--}}

        {{--                <div x-show="menus.pharmacy" x-collapse class="pl-2 space-y-1 mt-1">--}}
        {{--                    <a href="{{ route('pharmacy.dashboard') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors {{ request()->routeIs('pharmacy.dashboard') ? 'text-amber-600 bg-amber-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-tachometer-alt w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Dashboard</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('pharmacy.prescriptions.index') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors {{ request()->routeIs('pharmacy.prescriptions.*') ? 'text-amber-600 bg-amber-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-prescription w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Prescriptions</span>--}}
        {{--                        @if ($pharmacyStats['pending_prescriptions'] ?? 0)--}}
        {{--                            <span--}}
        {{--                                class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $pharmacyStats['pending_prescriptions'] }}</span>--}}
        {{--                        @endif--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('pharmacy.inventory') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors {{ request()->routeIs('pharmacy.inventory') ? 'text-amber-600 bg-amber-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-boxes w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Inventory</span>--}}
        {{--                        @if (($pharmacyStats['low_stock_items'] ?? 0) + ($pharmacyStats['out_of_stock_items'] ?? 0) > 0)--}}
        {{--                            <span--}}
        {{--                                class="ml-auto bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ ($pharmacyStats['low_stock_items'] ?? 0) + ($pharmacyStats['out_of_stock_items'] ?? 0) }}</span>--}}
        {{--                        @endif--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('pharmacy.dispense.history') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors {{ request()->routeIs('pharmacy.dispense.history') ? 'text-amber-600 bg-amber-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-history w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Dispense History</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('pharmacy.reports') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors {{ request()->routeIs('pharmacy.reports') ? 'text-amber-600 bg-amber-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-chart-bar w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Reports</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="{{ route('pharmacy.alerts') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors {{ request()->routeIs('pharmacy.alerts') ? 'text-amber-600 bg-amber-50 font-medium' : '' }}">--}}
        {{--                        <i class="fas fa-exclamation-triangle w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Stock Alerts</span>--}}
        {{--                        @if ($pharmacyStats['active_alerts'] ?? 0)--}}
        {{--                            <span--}}
        {{--                                class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $pharmacyStats['active_alerts'] }}</span>--}}
        {{--                        @endif--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            @endhasAnyRole--}}

        {{--            @hasAnyRole(['nurse'])--}}
        {{--            <div class="mt-4">--}}
        {{--                <button @click="toggleMenu('nurse')"--}}
        {{--                        class="w-full flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider hover:text-green-600 transition-colors"--}}
        {{--                        :class="{ 'expanded': menus.nurse }">--}}
        {{--                        <span class="flex items-center gap-2">--}}
        {{--                            <i class="fas fa-user-nurse"></i> Nursing--}}
        {{--                        </span>--}}
        {{--                    <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>--}}
        {{--                </button>--}}

        {{--                <div x-show="menus.nurse" x-collapse class="pl-2 space-y-1 mt-1">--}}
        {{--                    <a href="{{ route('nurse.dashboard') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-green-600 hover:bg-green-50 transition-colors">--}}
        {{--                        <i class="fas fa-heartbeat w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Vitals</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="#"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-green-600 hover:bg-green-50 transition-colors">--}}
        {{--                        <i class="fas fa-procedures w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Wards</span>--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            @endhasAnyRole--}}

        {{--            @hasAnyRole(['lab'])--}}
        {{--            <div class="mt-4">--}}
        {{--                <button @click="toggleMenu('lab')"--}}
        {{--                        class="w-full flex items-center justify-between px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider hover:text-purple-600 transition-colors"--}}
        {{--                        :class="{ 'expanded': menus.lab }">--}}
        {{--                        <span class="flex items-center gap-2">--}}
        {{--                            <i class="fas fa-flask"></i> Laboratory--}}
        {{--                        </span>--}}
        {{--                    <i class="fas fa-chevron-down text-[10px] rotate-icon"></i>--}}
        {{--                </button>--}}

        {{--                <div x-show="menus.lab" x-collapse class="pl-2 space-y-1 mt-1">--}}
        {{--                    <a href="{{ route('lab.dashboard') }}"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-purple-600 hover:bg-purple-50 transition-colors">--}}
        {{--                        <i class="fas fa-vial w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Lab Tests</span>--}}
        {{--                    </a>--}}
        {{--                    <a href="#"--}}
        {{--                       class="flex items-center px-4 py-2.5 text-sm rounded-xl text-slate-500 hover:text-purple-600 hover:bg-purple-50 transition-colors">--}}
        {{--                        <i class="fas fa-file-pdf w-5 text-center text-xs opacity-70"></i>--}}
        {{--                        <span class="ml-3">Reports</span>--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            @endhasAnyRole--}}

        {{--        </nav>--}}

        <div class="p-4 border-t border-slate-100 bg-slate-50">
            <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                <span>Status</span>
                <span class="flex items-center text-emerald-500 font-bold">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-1 animate-pulse"></span> Online
                    </span>
            </div>
            <div class="h-1 bg-slate-200 rounded-full overflow-hidden mb-3">
                <div class="h-full bg-emerald-500 w-3/4"></div>
            </div>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="flex items-center justify-center px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm group">
                <i class="fas fa-sign-out-alt mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                <span class="font-medium">Sign Out</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">

        <header class="h-20 bg-white/80 backdrop-blur-xl sticky top-0 z-30 border-b border-slate-200 shadow-sm">
            <div class="px-6 h-full flex justify-between items-center">

                <div class="flex items-center gap-4">
                    <button @click="toggleSidebar()"
                            class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-blue-600 transition-colors focus:outline-none">
                        <i class="fas fa-bars text-xl" x-show="!sidebarOpen"></i>
                        <i class="fas fa-chevron-left text-xl" x-show="sidebarOpen"></i>
                    </button>

                    <div class="hidden md:block animate-fade-in">
                        <nav class="flex text-xs text-slate-400 mb-0.5">
                            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600"><i
                                            class="fas fa-home"></i></a></li>
                                <li><i class="fas fa-chevron-right text-[8px]"></i></li>
                                <li><span class="font-medium text-slate-600">@yield('page-title', 'Dashboard')</span>
                                </li>
                            </ol>
                        </nav>
                        <h2 class="text-xl font-bold text-slate-800 leading-tight">@yield('page-title', 'Overview')</h2>
                    </div>
                </div>

                <div class="flex items-center gap-4">

                    <div class="hidden lg:block text-right mr-2">
                        <div class="text-[10px] text-slate-400 uppercase tracking-wider">System Time</div>
                        <div id="current-time" class="text-sm font-mono font-bold text-slate-700"></div>
                    </div>

                    <div class="relative" x-data>
                        <button @click="notificationsOpen = !notificationsOpen" @click.away="notificationsOpen = false"
                                class="relative p-2.5 rounded-full bg-slate-100 text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="far fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-white"></span>
                                </span>
                        </button>

                        <div x-show="notificationsOpen" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                                <span class="text-sm font-bold text-slate-700">Notifications</span>
                                <span class="text-xs text-blue-600 cursor-pointer hover:underline">Mark all read</span>
                            </div>
                            <div class="max-h-64 overflow-y-auto p-4 text-center text-slate-400 text-xs">
                                No new notifications
                            </div>
                        </div>
                    </div>

                    <div class="relative pl-2 border-l border-slate-200 ml-2">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
                                class="flex items-center gap-3 focus:outline-none">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-slate-700">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">{{ strtoupper(auth()->user()->role->name ?? 'User') }}</p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 p-0.5 shadow-md hover:scale-105 transition-transform">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                    <span
                                        class="font-bold text-blue-600">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                            </div>
                        </button>

                        <div x-show="profileOpen" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-4 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 py-2">
                            <a href="#"
                               class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600"><i
                                    class="fas fa-user-circle mr-2"></i> Profile</a>
                            <a href="#"
                               class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600"><i
                                    class="fas fa-cog mr-2"></i> Settings</a>
                            <div class="h-px bg-slate-100 my-1"></div>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="block px-4 py-2 text-sm text-rose-500 hover:bg-rose-50"><i
                                    class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 scroll-smooth">
            <div id="flash-container" class="mb-6 space-y-2">
            </div>

            <div class="animate-fade-in min-h-[calc(100vh-14rem)]">
                @yield('content')
            </div>

            <footer
                class="mt-8 pt-4 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
                <div>
                    <span class="font-bold text-slate-700">MedCare Hospital OS</span> &copy; {{ date('Y') }}
                </div>
                <div class="flex items-center gap-4 mt-2 md:mt-0">
                    <span><i class="fas fa-server mr-1"></i> v2.5 Stable</span>
                    <span><i class="fas fa-shield-alt mr-1"></i> Secure</span>
                </div>
            </footer>
        </main>
    </div>
</div>
<script src="{{ asset('js/notification.js') }}"></script>

<script>
    // System Clock
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'});
        const dateString = now.toLocaleDateString('en-US', {weekday: 'short', month: 'short', day: 'numeric'});
        const el = document.getElementById('current-time');
        if (el) el.innerHTML = `${dateString} | ${timeString}`;
    }

    setInterval(updateTime, 1000);
    updateTime();

    /**
     * Notification Trigger Logic
     * Fired only after the DOM and notification.js are fully loaded.
     */
    document.addEventListener('DOMContentLoaded', function () {
        // Ensure the notification system is initialized
        if (typeof window.showNotification === 'function') {

            // 1. Handle Validation Errors (PHP $errors)
            @if ($errors->any())
            @foreach ($errors->all() as $index => $error)
            setTimeout(() => {
                window.showNotification('{{ addslashes($error) }}', 'error', 'Validation Error', 8000);
            }, {{ $index * 400 }}); // Stagger by 400ms to prevent stacking
            @endforeach
            @endif

            // 2. Handle Session Success
            @if (session('success'))
            window.showNotification('{{ addslashes(session('success')) }}', 'success', 'Success');
            @endif

            // 3. Handle Session Errors
            @if (session('error'))
            window.showNotification('{{ addslashes(session('error')) }}', 'error', 'Error');
            @endif

            // 4. Handle Session Warnings/Info
            @if (session('warning'))
            window.showNotification('{{ addslashes(session('warning')) }}', 'warning', 'Warning');
            @endif
            @if (session('info'))
            window.showNotification('{{ addslashes(session('info')) }}', 'info', 'Information');
            @endif

        } else {
            console.error('Critical: Notification system failed to load via notification.js');
        }
    });
</script>
@stack('scripts')
</body>
</html>
