<x-layout :activeMenu="$activeMenu">
    <div class="flex flex-col gap-6 py-6 px-3 bg-gray-50/50 min-h-screen w-full" 
         x-data="{ 
            startDate: '{{ $startDate ?? '' }}', 
            endDate: '{{ $endDate ?? '' }}', 
            search: {
                all: '{{ $search['all'] ?? '' }}',
                t_log_scan_fg: '{{ $search['t_log_scan_fg'] ?? '' }}',
                t_log_scan_fg_part_number: '{{ $search['t_log_scan_fg_part_number'] ?? '' }}',
                t_log_scan_operator: '{{ $search['t_log_scan_operator'] ?? '' }}',
                t_log_scan_pic: '{{ $search['t_log_scan_pic'] ?? '' }}',
                t_log_scan_grup: '{{ $search['t_log_scan_grup'] ?? '' }}'
            },
            loading: false,

            fetchData(url = null) {
                this.loading = true;
                let targetUrl = url || '{{ route('scan.history') }}';
                
                let params = new URLSearchParams({
                    start_date: this.startDate,
                    end_date: this.endDate,
                    'search[all]': this.search.all,
                    'search[t_log_scan_fg]': this.search.t_log_scan_fg,
                    'search[t_log_scan_fg_part_number]': this.search.t_log_scan_fg_part_number,
                    'search[t_log_scan_operator]': this.search.t_log_scan_operator,
                    'search[t_log_scan_pic]': this.search.t_log_scan_pic,
                    'search[t_log_scan_grup]': this.search.t_log_scan_grup
                });

                fetch(`${targetUrl}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('table-container').innerHTML = data.html;
                    this.loading = false;
                    window.history.pushState({}, '', `${targetUrl}?${params.toString()}`);
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.loading = false;
                });
            },

            resetFilter() {
                this.startDate = '';
                this.endDate = '';
                this.search = {
                    all: '',
                    t_log_scan_fg: '',
                    t_log_scan_fg_part_number: '',
                    t_log_scan_operator: '',
                    t_log_scan_pic: '',
                    t_log_scan_grup: ''
                };
                this.fetchData();
            }
         }">
        
        <!-- Header Judul -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Riwayat Log Scan Material</h2>
            <p class="text-xs text-gray-400 mt-0.5">Filter dan pantau seluruh aktivitas pemindaian berdasarkan tanggal dan pencarian spesifik per kolom.</p>
        </div>

        <!-- Panel Filter & Pencarian Utama (Global Search & Date) -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                
                <!-- Filter Range Tanggal: Dari -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" x-model="startDate" @change="fetchData()" 
                           class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <!-- Filter Range Tanggal: Sampai -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" x-model="endDate" @change="fetchData()" 
                           class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <!-- Pencarian Global (Semua Kolom) -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Cari Semua Kolom</label>
                    <div class="flex gap-2">
                        <!-- Ganti @input.debounce dengan @keydown.enter -->
                        <input type="text" x-model="search.all" @keydown.enter="fetchData()" placeholder="Ketik lalu tekan Enter..." 
                            class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <button @click="fetchData()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition" title="Cari">
                            Cari
                        </button>
                        <button @click="resetFilter()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-lg text-xs font-semibold transition" title="Reset Filter">
                            Reset
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tabel Kontainer (Hasil AJAX) -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm relative">
            
            <div x-show="loading" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center z-10 rounded-xl" style="display: none;">
                <span class="text-xs font-semibold text-emerald-600 animate-pulse">Memuat data...</span>
            </div>

            <div class="pb-4 mb-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Data Riwayat Log</h3>
            </div>

            <!-- Bagian Tabel dengan Filter Input di Setiap Kolom Header -->
            <div id="table-container" class="overflow-x-auto">
                @include('scan.partials.riwayat_scan_table')
            </div>

        </div>

    </div>
</x-layout>