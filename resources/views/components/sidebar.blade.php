<aside id="sidebar" 
       :class="isOpen ? 'w-64' : 'w-20'" 
       class="bg-slate-700 text-white flex flex-col min-h-screen shadow-xl transition-all duration-300 relative z-20">
    
    <!-- Brand / Logo Title -->
    <div class="p-1 bg-slate-800 text-center font-black text-lg tracking-wider border-b border-slate-600 flex items-center justify-center overflow-hidden">
        <a href="/dashboard" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded hover:bg-slate-800 text-white font-semibold transition">
            <h1 x-show="isOpen" class="truncate tracking-wider font-medium inline-flex items-center">
                <span class="text-blue-600">Paint</span>
                <span class="text-orange-500">HUB</span>
            </h1>
            <h1 x-show="!isOpen" x-cloak class="text-xl font-bold inline-flex items-center">
                <span class="text-blue-600">P</span>
                <span class="text-orange-500">H</span>
            </h1>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 p-3 space-y-2 text-sm overflow-y-auto" 
         x-data="{ 
             openDashboard: {{ request()->is('dashboard*') ? 'true' : 'false' }}, 
             openScan: {{ request()->is('scan*') || request()->is('master-sealing*') ? 'true' : 'false' }}, 
             openMaterial: {{ request()->is('material*') ? 'true' : 'false' }} 
         }">
         
        <!-- Menu Dashboard -->
        <div>
            <button @click="if(isOpen) { openDashboard = !openDashboard }" class="w-full flex items-center justify-between px-3 py-2.5 rounded hover:bg-slate-800 text-white font-semibold transition">
                <div class="flex items-center gap-3">
                    <span class="text-lg">📊</span>
                    <span x-show="isOpen">Dashboard</span>
                </div>
                <span x-show="isOpen" class="transition-transform duration-200" :class="openDashboard ? 'rotate-180' : ''">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
            </button>
            
            <div x-show="isOpen && openDashboard" x-cloak class="pl-9 pr-2 py-1 space-y-1 text-xs font-medium text-blue-200">
                <a href="/dashboard/coating" wire:navigate class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition {{ request()->is('dashboard/coating') ? 'bg-slate-800 text-white font-bold' : '' }}">Dashboard Coating</a>
                <a href="/dashboard/sealing" wire:navigate class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition {{ request()->is('dashboard/sealing') ? 'bg-slate-800 text-white font-bold' : '' }}">Dashboard Sealing</a>
            </div>
        </div>

        <!-- Menu Dropdown: Scan -->
        <div>
            <button @click="if(isOpen) { openScan = !openScan }" class="w-full flex items-center justify-between px-3 py-2.5 rounded hover:bg-slate-800 text-white font-semibold transition">
                <div class="flex items-center gap-3">
                    <span class="text-lg">📷</span>
                    <span x-show="isOpen">Scan</span>
                </div>
                <span x-show="isOpen" class="transition-transform duration-200" :class="openScan ? 'rotate-180' : ''">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
            </button>
            
            <div x-show="isOpen && openScan" x-cloak class="pl-9 pr-2 py-1 space-y-1 text-xs font-medium text-blue-200">
                <a href="/scan/console" wire:navigate class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition {{ request()->is('scan/console') ? 'bg-slate-800 text-white font-bold' : '' }}">Scan Console</a>
                <a href="/scan/stock-card" wire:navigate class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition {{ request()->is('scan/stock-card') ? 'bg-slate-800 text-white font-bold' : '' }}">Stock Card</a>
                <a href="/scan/stock-in" wire:navigate class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition {{ request()->is('scan/stock-in') ? 'bg-slate-800 text-white font-bold' : '' }}">Stock In</a>
                <a href="/scan/nglog" wire:navigate class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition {{ request()->is('scan/nglog') ? 'bg-slate-800 text-white font-bold' : '' }}">NG Log</a>
                <a href="#" class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition">Riwayat Stock</a>
                <a href="/master-sealing" wire:navigate class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition {{ request()->is('master-sealing') ? 'bg-slate-800 text-white font-bold' : '' }}">Master Part</a>
            </div>
        </div>

        <!-- Menu Problem/Downtime -->
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded hover:bg-slate-800 text-white font-semibold transition">
            <span class="text-lg">⚠️</span>
            <span x-show="isOpen">Problem/Downtime</span>
        </a>

        <!-- Menu Dropdown: Material -->
        <div>
            <button @click="if(isOpen) { openMaterial = !openMaterial }" class="w-full flex items-center justify-between px-3 py-2.5 rounded hover:bg-slate-800 text-white font-semibold transition">
                <div class="flex items-center gap-3">
                    <span class="text-lg">📦</span>
                    <span x-show="isOpen">Material</span>
                </div>
                <span x-show="isOpen" class="transition-transform duration-200" :class="openMaterial ? 'rotate-180' : ''">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
            </button>
            
            <div x-show="isOpen && openMaterial" x-cloak class="pl-9 pr-2 py-1 space-y-1 text-xs font-medium text-blue-200">
                <a href="#" class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition">Powder</a>
                <a href="#" class="block px-2 py-1.5 rounded hover:bg-slate-800 hover:text-white transition">Bonderite</a>
            </div>
        </div>

    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 bg-slate-950 text-xs text-center text-gray-400 border-t border-slate-600 overflow-hidden">
        <span x-show="isOpen">Painting Inventory Management System v1.0</span>
        <span x-show="!isOpen" x-cloak class="font-bold text-sm">v1.0</span>
    </div>
</aside>