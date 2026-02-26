<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Small Clinic HMS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    
    <nav class="bg-blue-600 p-4 text-white shadow-lg">
        <div class="container mx-auto flex justify-between">
            <h1 class="text-xl font-bold">ClinicManager v1.0</h1>
            <div class="space-x-4">
                <a href="/doctor" class="hover:underline">Doctor</a>
                <a href="/pharmacy" class="hover:underline">Pharmacy</a>
                <a href="/reception" class="hover:underline">Reception</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}
    </main>

</body>
</html>