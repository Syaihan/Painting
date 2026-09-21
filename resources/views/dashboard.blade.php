<x-layout :activeMenu="$activeMenu">
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Banner -->
        <div class="mb-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-900">
            <h2 class="text-2xl font-black text-blue-900 uppercase tracking-wide">
                @if (session()->has('operator_id'))
                    Selamat Datang, {{ session('operator_name') }}!
                @else
                    Selamat Datang!
                @endif
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Silakan pilih area kerja atau dashboard produksi yang ingin Anda pantau di bawah ini.
            </p>
        </div>
        <!-- Grid 2 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- CARD 1: DASHBOARD COATING -->
            <a href="{{ route('dashboard.coating') }}" class="group bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-200 hover:border-blue-500 transition-all duration-300 overflow-hidden flex flex-col justify-between p-6">
                <div>
                    <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center text-2xl mb-4 group-hover:bg-blue-900 group-hover:text-white transition-colors">
                        💨
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-900 transition-colors">
                        Dashboard Coating
                    </h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Pantau proses produksi line coating, status material powder/bonderite, log NG, serta kontrol kualitas permukaan part.
                    </p>
                </div>
                <div class="mt-6 flex items-center text-sm font-semibold text-blue-900 group-hover:translate-x-1 transition-transform">
                    <span>Akses Coating</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- CARD 2: DASHBOARD SEALING -->
            <a href="{{ route('dashboard.sealing') }}" class="group bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-200 hover:border-blue-500 transition-all duration-300 overflow-hidden flex flex-col justify-between p-6">
                <div>
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 text-indigo-900 flex items-center justify-center text-2xl mb-4 group-hover:bg-indigo-900 group-hover:text-white transition-colors">
                        🔧
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-indigo-900 transition-colors">
                        Dashboard Sealing
                    </h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Kelola data operasional line sealing, pemantauan stok material perekat, pencatatan downtime, dan laporan harian operator.
                    </p>
                </div>
                <div class="mt-6 flex items-center text-sm font-semibold text-indigo-900 group-hover:translate-x-1 transition-transform">
                    <span>Akses Sealing</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
        <!-- TOMBOL AKSES / CARD SLIDESHOW DASHBOARD -->
        <div class="mt-6 ">
            <a href="{{ route('dashboardslide') }}" class="group relative bg-gradient-to-r from-blue-900 to-indigo-900 rounded-xl shadow-lg hover:shadow-2xl border border-blue-800 transition-all duration-300 overflow-hidden flex items-center justify-between p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                        🖥️
                    </div>
                    <div>
                        <h3 class="text-xl font-bold tracking-wide">
                            Mode Slideshow Otomatis (TV / Monitor)
                        </h3>
                        <p class="text-sm text-blue-200 mt-1">
                            Tampilkan Dashboard Coating dan Sealing secara bergantian otomatis dalam satu layar penuh.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition shadow">
                    <span>Mulai Slide</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</x-layout>