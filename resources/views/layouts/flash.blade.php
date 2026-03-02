@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between shadow-sm">
    <div class="flex items-center">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mr-3">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-emerald-900">Success</p>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if (session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center justify-between shadow-sm">
    <div class="flex items-center">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 mr-3">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-rose-900">Error</p>
            <p class="text-sm text-rose-700">{{ session('error') }}</p>
        </div>
    </div>
    <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if (session('warning'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-center justify-between shadow-sm">
    <div class="flex items-center">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-3">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-amber-900">Warning</p>
            <p class="text-sm text-amber-700">{{ session('warning') }}</p>
        </div>
    </div>
    <button @click="show = false" class="text-amber-400 hover:text-amber-600 transition-colors">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if ($errors->any())
<div x-data="{ show: true }" x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 mr-3">
                <i class="fas fa-times-circle"></i>
            </div>
            <p class="text-sm font-bold text-rose-900">Validation Errors</p>
        </div>
        <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <ul class="list-disc list-inside text-sm text-rose-700 space-y-1 ml-13">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
