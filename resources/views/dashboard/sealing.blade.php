<x-layout :activeMenu="$activeMenu">
    <!-- Dihilangkan max-w-7xl dan mx-auto, padding samping diperkecil jadi px-3 -->
    <div class="flex flex-col gap-6 py-6 px-3 bg-gray-50/50 min-h-screen w-full">
        
        <!-- ==================== BARIS 1: TOP STATS CARDS ==================== -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            
            <!-- TOTAL PART -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <div class="bg-gradient-to-r from-blue-700 to-blue-800 text-white font-semibold py-1.5 px-3 text-xs tracking-wider flex items-center justify-between">
                    <span>TOTAL PART</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-white">
                    <span class="p-2.5 bg-blue-50 text-blue-600 rounded-lg text-xl group-hover:scale-110 transition-transform">📦</span>
                    <div class="text-right">
                        <span id="stat-total-part" class="text-2xl font-black text-gray-900">{{ $stats['total_part'] }}</span>
                    </div>
                </div>
            </div>

            <!-- SAFE -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold py-1.5 px-3 text-xs tracking-wider">
                    SAFE
                </div>
                <div class="flex items-center justify-between p-4 bg-white">
                    <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-lg text-xl group-hover:scale-110 transition-transform">🛡️</span>
                    <div class="text-right">
                        <span id="stat-safe" class="text-2xl font-black text-emerald-700">{{ $stats['safe'] }}</span>
                        <div class="text-[9px] text-gray-400 font-bold uppercase">Part Number</div>
                    </div>
                </div>
            </div>

            <!-- UNDER MINIMUM -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white font-semibold py-1.5 px-3 text-xs tracking-wider">
                    UNDER MINIMUM
                </div>
                <div class="flex items-center justify-between p-4 bg-white">
                    <span class="p-2.5 bg-amber-50 text-amber-600 rounded-lg text-xl group-hover:scale-110 transition-transform">⚠️</span>
                    <div class="text-right">
                        <span id="stat-under-minimum" class="text-2xl font-black text-amber-600">{{ $stats['under_minimum'] }}</span>
                        <div class="text-[9px] text-gray-400 font-bold uppercase">Part Number</div>
                    </div>
                </div>
            </div>



            <!-- TOTAL SCAN TODAY -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <div id="date-label-1" class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold py-1.5 px-2 text-[10px] truncate text-center">
                    {{ $currentDate }}
                </div>
                <div class="flex items-center justify-between p-4 bg-white">
                    <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-lg text-lg group-hover:scale-110 transition-transform">📊</span>
                    <div class="text-right">
                        <span id="stat-total-scan" class="text-xl font-black text-indigo-900">{{ $stats['total_scan'] }}</span>
                        <div class="text-[8px] text-gray-400 font-bold uppercase">Scan Today</div>
                    </div>
                </div>
            </div>

            <!-- TOTAL NG TODAY -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <div id="date-label-2" class="bg-gradient-to-r from-red-700 to-rose-800 text-white font-semibold py-1.5 px-2 text-[10px] truncate text-center">
                    {{ $currentDate }}
                </div>
                <div class="flex items-center justify-between p-4 bg-white">
                    <span class="p-2.5 bg-red-50 text-red-600 rounded-lg text-lg group-hover:scale-110 transition-transform">📋</span>
                    <div class="text-right">
                        <span id="stat-total-ng" class="text-xl font-black text-red-600">{{ $stats['total_ng'] }}</span>
                        <div class="text-[8px] text-gray-400 font-bold uppercase">NG Today</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================== BARIS 2: CATEGORY NG, NG PART, & ACHIEVEMENT SEALING ==================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Category NG Sealing -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col">
                <div class="text-gray-800 font-bold text-xs uppercase tracking-wider pb-3 mb-3 border-b border-gray-100 flex items-center justify-between relative">
                    <span>Category NG Sealing</span>
                    <span id="btn-filter-chart" class="text-gray-400 text-xs cursor-pointer hover:text-blue-600" title="Klik untuk filter tanggal" onclick="showFilterMenu()">📉 <span id="filter-active-badge" class="ml-1 hidden bg-blue-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">●</span></span>
                </div>
                <div class="relative flex-grow flex items-center justify-center min-h-[180px]">
                    <canvas id="chartCategoryNg"></canvas>
                </div>

                <!-- Backdrop transparan di belakang panel filter -->
                <div id="filter-backdrop" class="fixed inset-0 bg-black/20 backdrop-blur-sm z-49 hidden transition-opacity duration-200" onclick="hideFilterMenu()"></div>

                <!-- Panel Filter Tanggal Chart (overlay menimpa dashboard, background transparan di belakang) -->
                <div id="filter-chart-panel" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border border-gray-200 rounded-xl shadow-2xl p-4 z-50 hidden w-full max-w-xs transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-xs font-bold text-gray-700 uppercase tracking-wider">Filter Tanggal</div>
                        <button onclick="hideFilterMenu()" class="text-gray-400 hover:text-gray-600 text-xs">✕</button>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="text-[10px] text-gray-500 block mb-1">Start</label>
                            <input type="date" id="filter-date-start" class="w-full px-2.5 py-2 text-xs border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 block mb-1">End</label>
                            <input type="date" id="filter-date-end" class="w-full px-2.5 py-2 text-xs border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="applyFilterChart()" class="flex-1 bg-blue-600 text-white text-xs py-2 rounded-md hover:bg-blue-700 transition-colors">Apply</button>
                        <button onclick="resetFilterChart()" class="flex-1 bg-gray-100 text-gray-700 text-xs py-2 rounded-md hover:bg-gray-200 transition-colors border border-gray-300">Reset</button>
                    </div>
                </div>
            </div>

            <!-- NG Part Sealing -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col">
                <div class="text-gray-800 font-bold text-xs uppercase tracking-wider pb-3 mb-3 border-b border-gray-100 flex items-center justify-between">
                    <span>NG Part Sealing</span>
                    <span class="text-gray-400 text-xs">📊</span>
                </div>
                <div class="relative flex-grow flex items-center justify-center min-h-[180px]">
                    <canvas id="chartNgPart"></canvas>
                </div>
            </div>

            <!-- Achievement Sealing -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col">
                <div class="text-gray-800 font-bold text-xs uppercase tracking-wider pb-3 mb-3 border-b border-gray-100 flex items-center justify-between">
                    <span>Achievement Sealing</span>
                    <span class="text-gray-400 text-xs">📈</span>
                </div>
                <div class="relative flex-grow flex items-center justify-center min-h-[400px]">
                    <canvas id="chartPlanActual"></canvas>
                </div>
            </div>
        </div>

        <!-- ==================== BARIS 3: LAST TRANSACTION & CARD STOCK SEALING ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            
            <!-- Last Transaction (col-span-7) -->
            <div class="lg:col-span-7 bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col">
                <div class="text-gray-800 font-bold text-xs uppercase tracking-wider pb-3 mb-3 border-b border-gray-100 flex items-center justify-between">
                    <span>Last Transaction</span>
                    <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-semibold">Real-time</span>
                </div>
                <div class="max-h-[320px] overflow-y-auto overflow-x-auto flex-grow border border-gray-100 rounded-lg">
                    <table class="w-full text-center border-collapse text-[11px]">
                        <thead class="sticky top-0 bg-gray-50 text-gray-700 font-semibold shadow-xs z-10">
                            <tr>
                                <th class="p-2.5 border-b border-gray-200">TIMESTAMP</th>
                                <th class="p-2.5 border-b border-gray-200">BARCODE FG</th>
                                <th class="p-2.5 border-b border-gray-200 text-left">NAMA FG</th>
                                <th class="p-2.5 border-b border-gray-200">OPERATOR</th>
                                <th class="p-2.5 border-b border-gray-200">GROUP</th>
                                <th class="p-2.5 border-b border-gray-200">QTY</th>
                            </tr>
                        </thead>
                        <tbody id="table-history-body" class="divide-y divide-gray-100">
                            @forelse($history as $row)
                            @php
                                if (is_object($row)) {
                                    $rowJam   = $row->jam;
                                    $rowFq    = $row->fq;
                                    $rowFgName  = $row->fg_name;
                                    $rowOperator   = $row->operator;
                                    $rowGrup  = $row->grup;
                                    $rowQty   = $row->qty;
                                } elseif (is_array($row)) {
                                    $rowJam   = $row['jam'] ?? '-';
                                    $rowFq    = $row['fq'] ?? '-';
                                    $rowFgName  = $row['fg_name'] ?? '-';
                                    $rowOperator   = $row['operator'] ?? '-';
                                    $rowGrup  = $row['grup'] ?? '-';
                                    $rowQty   = $row['qty'] ?? '-';
                                } else {
                                    $rowJam   = $row;
                                    $rowFq    = '-';
                                    $rowFgName  = '-';
                                    $rowOperator   = '-';
                                    $rowGrup  = '-';
                                    $rowQty   = '-';
                                }
                            @endphp
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                <td class="p-2.5 font-mono text-gray-500">{{ $rowJam }}</td>
                                <td class="p-2.5 font-mono text-left font-medium text-gray-700">{{ $rowFq }}</td>
                                <td class="p-2.5 text-left text-gray-800 font-medium">{{ $rowFgName }}</td>
                                <td class="p-2.5 text-gray-600">{{ $rowOperator }}</td>
                                <td class="p-2.5 font-semibold text-gray-700">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-[10px]">Grup {{ $rowGrup }}</span>
                                </td>
                                <td class="p-2.5 font-bold text-blue-600">{{ $rowQty }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-400 text-xs italic">
                                    Belum ada riwayat transaksi scan hari ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card Stock Sealing (Non-Safe) (col-span-5) -->
            <div class="lg:col-span-5 bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col">
                <div class="text-gray-800 font-bold text-xs uppercase tracking-wider pb-3 mb-3 border-b border-gray-100 flex items-center justify-between">
                    <span>Card Stock Sealing (Non-Safe)</span>
                    <span class="text-xs bg-rose-50 text-rose-600 px-2 py-0.5 rounded-full font-semibold">Needs Attention</span>
                </div>
                <div class="max-h-[320px] overflow-y-auto overflow-x-auto flex-grow border border-gray-100 rounded-lg">
                    <table class="w-full border-collapse text-xs">
                        <thead class="sticky top-0 bg-gray-50 text-gray-700 font-semibold shadow-xs z-10">
                            <tr class="text-center">
                                <th class="p-2.5 border-b border-gray-200 text-left">PART NUMBER</th>
                                <th class="p-2.5 border-b border-gray-200 text-left">PART NAME</th>
                                <th class="p-2.5 border-b border-gray-200">BAL</th>
                                <th class="p-2.5 border-b border-gray-200">STATUS</th>
                            </tr>
                        </thead>
                        <tbody id="table-nonsafe-body" class="divide-y divide-gray-100">
                            @forelse($nonSafeStocks as $stock)
                            @php
                                if (is_array($stock)) {
                                    $stockPart   = $stock['part_number'] ?? '-';
                                    $stockName   = $stock['part_name'] ?? '-';
                                    $stockBalance = $stock['balance'] ?? '-';
                                    $stockStatus  = $stock['status'] ?? '-';
                                } elseif (is_object($stock)) {
                                    $stockPart   = $stock->part_number ?? '-';
                                    $stockName   = $stock->part_name ?? '-';
                                    $stockBalance = $stock->balance ?? '-';
                                    $stockStatus  = $stock->status ?? '-';
                                } else {
                                    $stockPart   = $stock;
                                    $stockName   = '-';
                                    $stockBalance = '-';
                                    $stockStatus  = '-';
                                }
                            @endphp
                            <tr class="text-center hover:bg-rose-50/30 transition-colors">
                                <td class="p-2.5 text-left font-mono text-[10px] text-gray-700 font-medium">{{ $stockPart }}</td>
                                <td class="p-2.5 text-left text-[10px] text-gray-600">{{ $stockName }}</td>
                                <td class="p-2.5 font-bold text-gray-800">{{ $stockBalance }}</td>
                                <td class="p-2.5">
                                    <span class="bg-amber-100 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-md text-[10px] font-semibold inline-block">
                                        {{ $stockStatus }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-400 text-xs italic">
                                    Tidak ada item non-safe. Semua stok dalam kondisi aman! 🎉
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js & Plugin Datalabels (Lokal) -->
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script src="{{ asset('js/chartjs-plugin-datalabels.min.js') }}"></script>
    
    <script>
        // State filter tanggal chart
        window.selectedDateStart = null;
        window.selectedDateEnd = null;
        window.filterMenuOpen = false;

        // Fungsi helper: dapatkan rentang default 1 bulan (tanggal 1 s.d. hari ini)
        function getDefaultOneMonthRange() {
            const now = new Date();
            const start = new Date(now.getFullYear(), now.getMonth(), 1);
            const toISO = d => {
                const pad = n => String(n).padStart(2, '0');
                return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
            };
            return { start: toISO(start), end: toISO(now) };
        }

        // Fungsi fetch data chart dengan filter tanggal (default 1 bulan)
        async function fetchChartData(dateStart = null, dateEnd = null) {
            if (dateStart === null || dateEnd === null) {
                const range = getDefaultOneMonthRange();
                dateStart = range.start;
                dateEnd   = range.end;
            }
            const url = `{{ route('dashboard.sealing.chart.data') }}?date_start=${encodeURIComponent(dateStart)}&date_end=${encodeURIComponent(dateEnd)}`;
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Gagal ambil data chart');
                const res = await response.json();
                window.initDashboardCharts(res.chartCategoryNg, res.chartNgPart);
                window.initPlanActualChart(res.chartPlanActual);
            } catch (error) {
                console.error('Gagal fetch chart data:', error);
            }
        }

        // Fungsi inisialisasi chart dengan parameter data opsional untuk update berkala
        window.initDashboardCharts = function(initialCategoryData = null, initialPartData = null) {
            if (typeof Chart === 'undefined' || typeof ChartDataLabels === 'undefined') {
                setTimeout(() => window.initDashboardCharts(initialCategoryData, initialPartData), 100);
                return;
            }

            const canvasCategory = document.getElementById('chartCategoryNg');
            const canvasPart = document.getElementById('chartNgPart');

            if (!canvasCategory || !canvasPart) return;

            // 1. Chart Category NG (Doughnut) — buat baru atau update data
            const categoryData = initialCategoryData || @json($chartCategoryNg);
            if (window.categoryChartInstance) {
                window.categoryChartInstance.data.labels = Object.keys(categoryData);
                window.categoryChartInstance.data.datasets[0].data = Object.values(categoryData);
                window.categoryChartInstance.update();
            } else {
                window.categoryChartInstance = new Chart(canvasCategory.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(categoryData),
                        datasets: [{
                            data: Object.values(categoryData),
                            backgroundColor: ['#f87171', '#fbbf24', '#34d399', '#60a5fa', '#a78bfa'],
                            cutout: '50%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 12, font: { size: 10 } }
                            },
                            datalabels: {
                                display: true,
                                color: '#ffffff',
                                font: { size: 11, weight: 'bold' },
                                formatter: function(value, context) {
                                    const data = context.chart.data.datasets[0].data;
                                    const total = data.reduce((sum, val) => sum + val, 0);
                                    if (total === 0) return '0%';
                                    return ((value / total) * 100).toFixed(1) + '%';
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }

            // 2. Chart NG Part (Bar) — buat baru atau update data
            const partData = initialPartData || @json($chartNgPart);
            if (window.partChartInstance) {
                window.partChartInstance.data.labels = Object.keys(partData);
                window.partChartInstance.data.datasets[0].data = Object.values(partData);
                window.partChartInstance.update();
            } else {
                window.partChartInstance = new Chart(canvasPart.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(partData),
                        datasets: [{
                            label: 'Total QTY NG',
                            data: Object.values(partData),
                            backgroundColor: '#ef4444',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            datalabels: {
                                anchor: 'center',
                                align: 'center',
                                color: '#ffffff',
                                font: { size: 9, weight: 'bold' },
                                rotation: -90,
                                formatter: function(value, context) {
                                    return context.chart.data.labels[context.dataIndex] + ' (' + value + ')';
                                }
                            }
                        },
                        scales: {
                            x: { display: false },
                            y: { beginAtZero: true, ticks: { font: { size: 9 } } }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }
        }

        // 3. Chart Achievement Sealing per FG (Horizontal Bar)
        window.initPlanActualChart = function(initialData = null) {
            if (typeof Chart === 'undefined' || typeof ChartDataLabels === 'undefined') {
                setTimeout(() => window.initPlanActualChart(initialData), 100);
                return;
            }

            const canvas = document.getElementById('chartPlanActual');
            if (!canvas) return;

            const data = initialData || @json($chartPlanActual ?? []);
            const labels = data.map(d => d.fg);
            const planned = data.map(d => d.planned);
            const actual = data.map(d => d.actual);

            // Hancurkan instance lama jika ada, lalu buat baru dengan indexAxis: 'y'
            if (window.planActualChartInstance) {
                window.planActualChartInstance.destroy();
                window.planActualChartInstance = null;
            }

            window.planActualChartInstance = new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Plan',
                            data: planned,
                            backgroundColor: '#3b82f6',
                            borderRadius: 4,
                        },
                        {
                            label: 'Actual',
                            data: actual,
                            backgroundColor: '#10b981',
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { size: 10 }, boxWidth: 12 }
                        },
                        datalabels: {
                            display: true,
                            align: 'end',
                            anchor: 'end',
                            color: '#374151',
                            font: { size: 9, weight: 'bold' },
                            formatter: function(value) {
                                return value > 0 ? value : '';
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { font: { size: 9 } },
                            title: { display: true, text: 'Qty', font: { size: 10, weight: 'bold' } }
                        },
                        y: {
                            ticks: { font: { size: 9 } },
                            title: { display: false }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }

        // ID interval untuk polling agar bisa dihentikan saat pindah halaman
        window.pollingIntervalId = null;
        window.chartIntervalId = null;

        // Hentikan semua interval polling
        function stopDashboardPolling() {
            if (window.pollingIntervalId) {
                clearInterval(window.pollingIntervalId);
                window.pollingIntervalId = null;
            }
            if (window.chartIntervalId) {
                clearInterval(window.chartIntervalId);
                window.chartIntervalId = null;
            }
        }

        // Cek apakah masih berada di halaman Dashboard Sealing (elemen utama ada)
        function isSealingPage() {
            return !!document.getElementById('stat-total-part');
        }

        // Fungsi AJAX Polling setiap 5 detik
        function startDashboardPolling() {
            // Hentikan interval lama sebelum memulai yang baru (hindari interval tumpuk)
            stopDashboardPolling();

            // Jika tidak ada di halaman sealing, jangan jalankan polling
            if (!isSealingPage()) return;

            window.pollingIntervalId = setInterval(async () => {
                try {
                    let response = await fetch("{{ route('dashboard.sealing.data') }}");
                    if (!response.ok) return;
                    let res = await response.json();

                    // Jika sudah pindah halaman, hentikan polling
                    if (!isSealingPage()) {
                        stopDashboardPolling();
                        return;
                    }

                    // 1. Update Kartu Statistik Utama (hanya jika elemen ada)
                    const elTotalPart = document.getElementById('stat-total-part');
                    const elSafe = document.getElementById('stat-safe');
                    const elUnderMinimum = document.getElementById('stat-under-minimum');
                    const elTotalScan = document.getElementById('stat-total-scan');
                    const elTotalNg = document.getElementById('stat-total-ng');
                    const elDate1 = document.getElementById('date-label-1');
                    const elDate2 = document.getElementById('date-label-2');

                    if (elTotalPart) elTotalPart.innerText = res.stats.total_part;
                    if (elSafe) elSafe.innerText = res.stats.safe;
                    if (elUnderMinimum) elUnderMinimum.innerText = res.stats.under_minimum;
                    if (elTotalScan) elTotalScan.innerText = res.stats.total_scan;
                    if (elTotalNg) elTotalNg.innerText = res.stats.total_ng;
                    if (elDate1) elDate1.innerText = res.stats.date;
                    if (elDate2) elDate2.innerText = res.stats.date;

                    // 2. Update Charts dengan data baru
                    // window.initDashboardCharts(res.chartCategoryNg, res.chartNgPart);

                    // 3. Update Tabel History Transaksi (hanya jika elemen ada)
                    const elHistoryBody = document.getElementById('table-history-body');
                    if (elHistoryBody) {
                        let historyHtml = '';
                    if (res.history.length > 0) {
                        res.history.forEach(row => {
                            historyHtml += `
                                <tr class="hover:bg-blue-50/40 transition-colors">
                                    <td class="p-2.5 font-mono text-gray-500">${row.jam}</td>
                                    <td class="p-2.5 font-mono text-left font-medium text-gray-700">${row.fq}</td>
                                    <td class="p-2.5 text-left text-gray-800 font-medium">${row.fg_name}</td>
                                    <td class="p-2.5 text-gray-600">${row.operator}</td>
                                    <td class="p-2.5 font-semibold text-gray-700">
                                        <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-[10px]">Grup ${row.grup}</span>
                                    </td>
                                    <td class="p-2.5 font-bold text-blue-600">${row.qty}</td>
                                </tr>
                            `;
                        });
                    } else {
                        historyHtml = `<tr><td colspan="6" class="p-8 text-center text-gray-400 text-xs italic">Belum ada riwayat transaksi scan hari ini.</td></tr>`;
                    }
                                            elHistoryBody.innerHTML = historyHtml;
                    }

                    // 4. Update Tabel Card Stock Sealing (Non-Safe) (hanya jika elemen ada)
                    const elNonSafeBody = document.getElementById('table-nonsafe-body');
                    if (elNonSafeBody) {
                        let nonSafeHtml = '';
                    if (res.nonSafeStocks.length > 0) {
                        res.nonSafeStocks.forEach(stock => {
                            let badgeClass = 'bg-amber-100 text-amber-800 border border-amber-200';
                            nonSafeHtml += `
                                <tr class="text-center hover:bg-rose-50/30 transition-colors">
                                    <td class="p-2.5 text-left font-mono text-[10px] text-gray-700 font-medium">${stock.part_number}</td>
                                    <td class="p-2.5 text-left text-[10px] text-gray-600">${stock.part_name}</td>
                                    <td class="p-2.5 font-bold text-gray-800">${stock.balance}</td>
                                    <td class="p-2.5">
                                        <span class="${badgeClass} px-2 py-0.5 rounded-md text-[10px] font-semibold inline-block">
                                            ${stock.status}
                                        </span>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        nonSafeHtml = `<tr><td colspan="4" class="p-8 text-center text-gray-400 text-xs italic">Tidak ada item non-safe. Semua stok dalam kondisi aman! 🎉</td></tr>`;
                    }
                        elNonSafeBody.innerHTML = nonSafeHtml;
                    }

                } catch (error) {
                    console.error("Gagal memperbarui data dashboard:", error);
                }
            }, 5000); // Interval 5000ms = 5 detik
            window.chartIntervalId = setInterval(async () => {
                // Jika sudah pindah halaman, hentikan interval chart
                if (!isSealingPage()) {
                    stopDashboardPolling();
                    return;
                }

                // Pakai tanggal terpilih user (atau default 1 bulan)
                await fetchChartData(window.selectedDateStart, window.selectedDateEnd);
            }, 60000);
        }

        // Inisialisasi awal saat halaman dimuat — default 1 bulan
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                fetchChartData();
                startDashboardPolling();
            });
        } else {
            fetchChartData();
            startDashboardPolling();
        }
        
        document.addEventListener('livewire:navigated', () => {
            // Hentikan polling lama saat pindah halaman
            stopDashboardPolling();

            // Hanya restart jika masih di halaman sealing
            if (isSealingPage()) {
                fetchChartData();
                startDashboardPolling();
            }
        });

        // === Fungsi Filter Tanggal Chart ===

        // Hover-based filter panel (overlay)
        function showFilterMenu() {
            const panel = document.getElementById('filter-chart-panel');
            const backdrop = document.getElementById('filter-backdrop');
            const range = getDefaultOneMonthRange();
            const elStart = document.getElementById('filter-date-start');
            const elEnd = document.getElementById('filter-date-end');
            if (elStart && !elStart.value) elStart.value = range.start;
            if (elEnd && !elEnd.value) elEnd.value = range.end;
            panel.classList.remove('hidden');
            backdrop.classList.remove('hidden');
        }

        function hideFilterMenu() {
            const panel = document.getElementById('filter-chart-panel');
            const backdrop = document.getElementById('filter-backdrop');
            panel.classList.add('hidden');
            backdrop.classList.add('hidden');
        }

        function applyFilterChart() {
            const elStart = document.getElementById('filter-date-start');
            const elEnd = document.getElementById('filter-date-end');
            const badge = document.getElementById('filter-active-badge');

            if (elStart && elEnd) {
                window.selectedDateStart = elStart.value;
                window.selectedDateEnd = elEnd.value;
                fetchChartData(window.selectedDateStart, window.selectedDateEnd);
                if (badge) badge.classList.remove('hidden');
            }
            hideFilterMenu();
        }

        function resetFilterChart() {
            const badge = document.getElementById('filter-active-badge');
            window.selectedDateStart = null;
            window.selectedDateEnd = null;
            fetchChartData(); // default 1 bulan
            if (badge) badge.classList.add('hidden');
            hideFilterMenu();
        }

        // Inisialisasi event klik saat DOM siap
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('btn-filter-chart');
            const panel = document.getElementById('filter-chart-panel');
            const backdrop = document.getElementById('filter-backdrop');
            if (btn && panel && backdrop) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showFilterMenu();
                });
                backdrop.addEventListener('click', hideFilterMenu);
            }
        });
    </script>
</x-layout>