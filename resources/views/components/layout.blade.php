<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIMS V1 System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- Alpine.js CDN -->
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased select-none">

    <!-- Global Loading Bar Livewire (Dipindah ke dalam body agar aman) -->
    <div wire:loading class="fixed top-0 left-0 w-full h-1 bg-blue-500 z-50 animate-pulse"></div>

    <!-- Kontainer Utama dengan Alpine.js State untuk Sidebar -->
    <div class="flex h-screen overflow-hidden" 
         x-data="{ 
             isOpen: localStorage.getItem('sidebar_open') !== 'false',
             toggleSidebar() {
                 this.isOpen = !this.isOpen;
                 localStorage.setItem('sidebar_open', this.isOpen);
             }
         }">
        
        <!-- SIDEBAR (Hanya tampil jika SUDAH login) -->
        @if (session()->has('operator_id') && !request()->has('embed'))
            <x-sidebar />
        @endif

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- HEADER BAR -->
            @if (!request()->has('embed'))
            <header class="bg-white border-b border-gray-200 shadow-sm px-6 py-3 flex justify-between items-center ">
                <div class="flex items-center gap-4">
                    <!-- Tombol Toggle Sidebar di Header (Hanya tampil jika SUDAH login) -->
                    @if (session()->has('operator_id'))
                        <button @click="toggleSidebar()" class="text-gray-600 hover:text-blue-600 focus:outline-none p-1 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    @endif
                    <!-- Menampilkan Menu yang Sedang Active -->
                    <h1 class="text-lg font-bold text-gray-800 uppercase tracking-wide">
                        @if ($activeMenu === 'Dashboard Coating')
                            <a href="/dashboard" wire:navigate>Dashboard Coating</a>
                        @elseif ($activeMenu === 'Dashboard Sealing')
                            <a href="/dashboard" wire:navigate>Dashboard Sealing</a>
                        @else
                            {{ $activeMenu ?? 'Dashboard' }}
                        @endif
                    </h1>
                </div>

                <!-- Informasi Operator / Tombol Login -->
                <div class="flex items-center gap-4">
                    @if (session()->has('operator_id'))
                        <!-- JIKA SUDAH LOGIN -->
                        <div class="flex items-center gap-2 text-sm text-gray-700 font-medium bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span><strong class="text-blue-900">{{ session('operator_name', 'Operator') }}</strong> 
                                <span class="text-xs text-gray-500">({{ session('role_display') }})</span>
                            </span>
                        </div>

                        <!-- Tombol Logout -->
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 text-sm bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg border border-red-200 font-semibold transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    @else
                        <!-- JIKA BELUM LOGIN -->
                        <a href="{{ route('login') }}" wire:navigate class="flex items-center gap-1.5 text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg border border-blue-200 font-semibold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login System
                        </a>
                    @endif
                </div>
            </header>
            @endif
            
            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                {{ $slot }}
            </main>

        </div>
    </div>
    @livewireScripts
</body>
</html>