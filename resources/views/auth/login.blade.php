<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PIMS V1 System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 font-sans antialiased flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden p-8">
        
        <!-- Header Logo / Title -->
        <div class="text-center mb-8">
            <h1 class="text-3xl tracking-wider font-medium p-3 inline-flex items-center gap-1 rounded">
                <span class="text-blue-600">Paint</span>
                <span class="text-orange-500">HUB</span>
            </h1>
            <p class="text-xs text-gray-500 font-semibold uppercase mt-1">Painting Inventory Management System v1.0</p>
        </div>

        <!-- Notifikasi Error / Success -->
        @if($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 text-sm text-red-700 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-3 text-sm text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-900 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-900 focus:outline-none text-sm">
            </div>

            <button type="submit" 
                class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg transition duration-200 text-sm uppercase tracking-wide shadow-lg">
                Login
            </button>
        </form>

        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; 2026 Production Control. All rights reserved.
        </div>
    </div>

</body>
</html>