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
                        <span class="text-2xl font-black text-gray-900">{{ $stats['total_part'] }}</span>
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
                        <span class="text-2xl font-black text-emerald-700">{{ $stats['safe'] }}</span>
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
                        <span class="text-2xl font-black text-amber-600">{{ $stats['under_minimum'] }}</span>
                        <div class="text-[9px] text-gray-400 font-bold uppercase">Part Number</div>
                    </div>
                </div>
            </div>

            <!-- INVESTIGATE -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <div class="bg-gradient-to-r from-rose-600 to-rose-700 text-white font-semibold py-1.5 px-3 text-xs tracking-wider">
                    INVESTIGATE
                </div>
                <div class="flex items-center justify-between p-4 bg-white">
                    <span class="p-2.5 bg-rose-50 text-rose-600 rounded-lg text-xl group-hover:scale-110 transition-transform">❌</span>
                    <div class="text-right">
                        <span class="text-2xl font-black text-rose-600">{{ $stats['investigate'] }}</span>
                        <div class="text-[9px] text-gray-400 font-bold uppercase">Part Number</div>
                    </div>
                </div>
            </div>

            <!-- TOTAL SCAN TODAY -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold py-1.5 px-2 text-[10px] truncate text-center">
                    {{ $currentDate }}
                </div>
                <div class="flex items-center justify-between p-4 bg-white">
                    <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-lg text-lg group-hover:scale-110 transition-transform">📊</span>
                    <div class="text-right">
                        <span class="text-xl font-black text-indigo-900">{{ $stats['total_scan'] }}</span>
                        <div class="text-[8px] text-gray-400 font-bold uppercase">Scan Today</div>
                    </div>
                </div>
            </div>

            <!-- TOTAL NG TODAY -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <div class="bg-gradient-to-r from-red-700 to-rose-800 text-white font-semibold py-1.5 px-2 text-[10px] truncate text-center">
                    {{ $currentDate }}
                </div>
                <div class="flex items-center justify-between p-4 bg-white">
                    <span class="p-2.5 bg-red-50 text-red-600 rounded-lg text-lg group-hover:scale-110 transition-transform">📋</span>
                    <div class="text-right">
                        <span class="text-xl font-black text-red-600">{{ $stats['total_ng'] }}</span>
                        <div class="text-[8px] text-gray-400 font-bold uppercase">NG Today</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================== BARIS 2: CATEGORY NG, NG PART, & ACHIEVEMENT SEALING ==================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Category NG Sealing -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col">
                <div class="text-gray-800 font-bold text-xs uppercase tracking-wider pb-3 mb-3 border-b border-gray-100 flex items-center justify-between">
                    <span>Category NG Sealing (Apr - Mar)</span>
                    <span class="text-gray-400 text-xs">📉</span>
                </div>
                <div class="relative flex-grow flex items-center justify-center min-h-[180px]">
                    <canvas id="chartCategoryNg"></canvas>
                </div>
            </div>

            <!-- NG Part Sealing -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col">
                <div class="text-gray-800 font-bold text-xs uppercase tracking-wider pb-3 mb-3 border-b border-gray-100 flex items-center justify-between">
                    <span>NG Part Sealing (Apr - Mar)</span>
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
                <div class="min-h-[180px] flex-grow flex items-center justify-center text-gray-400 text-xs italic bg-gray-50/50 border border-dashed border-gray-200 rounded-lg p-4">
                    [Grafik Achievement Sealing]
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
                                <th class="p-2.5 border-b border-gray-200 text-left">PART NAME FG</th>
                                <th class="p-2.5 border-b border-gray-200">PIC</th>
                                <th class="p-2.5 border-b border-gray-200">GROUP</th>
                                <th class="p-2.5 border-b border-gray-200">QTY</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($history as $row)
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                <td class="p-2.5 font-mono text-gray-500">{{ $row->jam }}</td>
                                <td class="p-2.5 font-mono text-left font-medium text-gray-700">{{ $row->fq }}</td>
                                <td class="p-2.5 text-left text-gray-800 font-medium">{{ $row->part_number }}</td>
                                <td class="p-2.5 text-gray-600">{{ $row->pic }}</td>
                                <td class="p-2.5 font-semibold text-gray-700">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-[10px]">Grup {{ $row->grup }}</span>
                                </td>
                                <td class="p-2.5 font-bold text-blue-600">{{ $row->qty }}</td>
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
                        <tbody class="divide-y divide-gray-100">
                            @forelse($nonSafeStocks as $stock)
                            <tr class="text-center hover:bg-rose-50/30 transition-colors">
                                <td class="p-2.5 text-left font-mono text-[10px] text-gray-700 font-medium">{{ $stock['part_number'] }}</td>
                                <td class="p-2.5 text-left text-[10px] text-gray-600">{{ $stock['part_name'] }}</td>
                                <td class="p-2.5 font-bold text-gray-800">{{ $stock['balance'] }}</td>
                                <td class="p-2.5">
                                    <span class="{{ $stock['status'] == 'Investigate' ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }} px-2 py-0.5 rounded-md text-[10px] font-semibold inline-block">
                                        {{ $stock['status'] }}
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
    <!-- Masukkan Script Chart.js (Pastikan CDN Chart.js sudah diload di layout utama Anda) -->
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Plugin Datalabels -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Registrasi plugin secara global untuk memastikan terbaca oleh semua chart jika dibutuhkan
            // Chart.register(ChartDataLabels);

            // 1. Script Chart Category NG
            const categoryData = @json($chartCategoryNg);
            const ctxCategory = document.getElementById('chartCategoryNg').getContext('2d');
            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(categoryData),
                    datasets: [{
                        data: Object.values(categoryData),
                        backgroundColor: ['#f87171', '#fbbf24', '#34d399', '#60a5fa', '#a78bfa']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } },
                        // Nonaktifkan datalabels untuk doughnut chart jika tidak ingin angka memenuhi pie
                        datalabels: { display: false } 
                    }
                },
                plugins: [ChartDataLabels]
            });

           // 2. Script Chart NG Part
           const partData = @json($chartNgPart);
           const ctxPart = document.getElementById('chartNgPart').getContext('2d');

           new Chart(ctxPart, {
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
                           font: {
                               size: 9,
                               weight: 'bold'
                           },
                           rotation: -90,
                           formatter: function(value, context) {
                               return context.chart.data.labels[context.dataIndex] + ' (' + value + ')';
                           }
                       }
                   },
                   scales: {
                       x: { display: false },
                       y: { 
                           beginAtZero: true, 
                           ticks: { font: { size: 9 } } 
                       }
                   }
               },
               plugins: [ChartDataLabels]
           });
        });
    </script>
</x-layout>