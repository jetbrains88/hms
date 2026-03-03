<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Futuristic Live Queue | Medical Center</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Roboto:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .token-font {
            font-family: 'Orbitron', sans-serif;
        }

        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .neon-text-blue {
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.5), 0 0 20px rgba(59, 130, 246, 0.3);
        }

        .neon-text-green {
            text-shadow: 0 0 10px rgba(34, 197, 94, 0.5), 0 0 20px rgba(34, 197, 94, 0.3);
        }

        /* Custom Scrollbar for the queue */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(51, 65, 85, 0.5);
            border-radius: 10px;
        }

        @keyframes flow {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .animate-flow {
            background-size: 200% 200%;
            animation: flow 5s ease infinite;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 overflow-hidden h-screen flex flex-col" x-data="{ currentTime: '' }"
      x-init="setInterval(() => { currentTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'}) }, 1000)">

<header class="h-24 glass flex justify-between items-center px-12 relative z-10 shadow-2xl">
    <div class="flex items-center gap-4">
        <div class="bg-blue-600 p-3 rounded-2xl shadow-lg shadow-blue-500/20">
            <i class="material-icons-round text-3xl text-white">medical_services</i>
        </div>
        <div>
            <h1 class="text-3xl font-black uppercase tracking-tighter text-white">Clinic <span class="text-blue-500">Live</span>
                Status</h1>
            <p class="text-xs text-slate-400 font-bold tracking-[0.3em] uppercase">Smart Queue Management System</p>
        </div>
    </div>

    <div class="flex items-center gap-8">
        <div class="text-right">
            <div class="text-4xl font-black text-white token-font tracking-widest neon-text-blue"
                 x-text="currentTime"></div>
            <div
                class="text-xs text-blue-400 font-bold uppercase tracking-widest">{{ now()->format('l, F j, Y') }}</div>
        </div>
    </div>
</header>

<main class="flex-1 grid grid-cols-12 gap-8 p-8 overflow-hidden">

    <div class="col-span-12 lg:col-span-7 flex flex-col gap-6">
        <div class="flex-1 relative group">
            <div
                class="absolute -inset-1 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-[3rem] blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>

            <div
                class="relative h-full glass rounded-[3rem] flex flex-col items-center justify-center border-2 border-emerald-500/30 overflow-hidden">
                <div
                    class="absolute top-10 flex items-center gap-2 px-6 py-2 bg-emerald-500/10 rounded-full border border-emerald-500/20">
                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                    <span class="text-emerald-400 font-black tracking-widest uppercase text-sm">Now Serving</span>
                </div>

                @php $nowServing = $visits->where('status', 'completed')->first(); @endphp

                <div class="text-center">
                    <h2 class="text-[18rem] font-black text-white leading-none token-font neon-text-green tracking-tighter">
                        {{ $nowServing ? $nowServing->queue_token : '--' }}
                    </h2>
                    <div class="mt-4 flex flex-col items-center gap-2">
                        <p class="text-3xl font-light text-slate-300 tracking-wide">
                            Please proceed to <span class="text-emerald-400 font-bold">Consultation Room 01</span>
                        </p>
                    </div>
                </div>

                <div
                    class="absolute bottom-0 w-full h-2 bg-gradient-to-r from-transparent via-emerald-500 to-transparent opacity-50"></div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-5 flex flex-col">
        <div class="glass rounded-[3rem] p-8 border border-slate-700/50 flex flex-col h-full shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-black text-white flex items-center gap-3">
                    <i class="material-icons-round text-blue-500">pending_actions</i>
                    UPCOMING TOKENS
                </h2>
                <span
                    class="px-4 py-1 bg-slate-800 rounded-full text-xs font-bold text-slate-400 border border-slate-700">
                        {{ $visits->where('status', 'waiting')->count() }} WAITING
                    </span>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 space-y-4">
                @forelse($visits->where('status', 'waiting')->take(10) as $v)
                    <div
                        class="group flex items-center justify-between p-6 bg-slate-800/40 border border-slate-700/50 rounded-3xl hover:bg-slate-700/40 hover:border-blue-500/50 transition-all duration-300">
                        <div class="flex items-center gap-6">
                            <div
                                class="w-16 h-16 flex items-center justify-center rounded-2xl bg-blue-600/10 border border-blue-500/20 text-blue-400 font-black text-2xl token-font group-hover:bg-blue-600 group-hover:text-white transition-all">
                                {{ $v->queue_token }}
                            </div>
                            <div>
                                <p class="text-lg font-bold text-white uppercase tracking-tight">Patient Token</p>
                                <p class="text-xs text-slate-500 font-medium">Est. Wait: ~10 mins</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 group-hover:text-blue-400">
                            <span class="text-xs font-black uppercase tracking-widest">Next</span>
                            <i class="material-icons-round">chevron_right</i>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center opacity-30">
                        <i class="material-icons-round text-[8rem] mb-4">hourglass_empty</i>
                        <p class="text-2xl font-light italic">No patients in queue</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

<footer class="h-16 bg-blue-600/10 border-t border-blue-500/20 flex items-center">
    <div class="bg-blue-600 px-8 h-full flex items-center gap-2 relative z-20 shadow-[20px_0_30px_rgba(0,0,0,0.5)]">
        <i class="material-icons-round text-white">campaign</i>
        <span class="font-black text-white uppercase text-sm tracking-widest">Notice</span>
    </div>
    <div class="flex-1 overflow-hidden">
        <marquee class="text-lg font-medium text-blue-300">
            Welcome to our modern healthcare facility. &bull; Please keep your tokens ready for the display. &bull;
            Contact the front desk for assistance. &bull; Your health is our priority. &bull; Free Wi-Fi is available in
            the waiting lounge.
        </marquee>
    </div>
</footer>

<div class="fixed top-0 left-0 w-full h-full pointer-events-none -z-10 opacity-30">
    <div class="absolute top-[10%] left-[5%] w-96 h-96 bg-blue-600/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[10%] right-[5%] w-[30rem] h-[30rem] bg-emerald-600/10 rounded-full blur-[120px]"></div>
</div>

</body>
</html>
