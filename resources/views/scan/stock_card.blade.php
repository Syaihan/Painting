<x-layout :activeMenu="$activeMenu">
    <!-- Menggunakan Alpine.js untuk kontrol 2 Modal (Modal Form Adjustment & Modal Log) -->
    <div x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }}, openLogModal: false }" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 h-[calc(100vh-4rem)] flex flex-col">
        
        <!-- CARD UTAMA -->
        <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6 flex flex-col flex-1 overflow-hidden">
            
            <!-- HEADER, SEARCH BAR & TOMBOL AKSI (Adjustment & Log) -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-4 border-b border-gray-100 flex-shrink-0 ">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg">
                        📋
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Stock Card Child Part</h3>
                        <p class="text-xs text-gray-500">Monitoring stok, akumulasi scan produksi, dan data NG material secara real-time.</p>
                    </div>
                </div>

                <!-- Bagian Form Pencarian & Tombol Aksi Tambahan -->
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <form action="{{ route('scan.stock_card') }}" method="GET" class="flex items-center gap-2">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari Part Number / Nama Material..." class="rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-1.5 px-3 w-52 sm:w-64">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-1.5 px-4 rounded-lg shadow transition text-sm">
                            Cari
                        </button>
                        @if(isset($search) && $search != '')
                            <a href="{{ route('scan.stock_card') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-1.5 px-3 rounded-lg text-sm transition">
                                Reset
                            </a>
                        @endif
                    </form>

                    <!-- Tombol Lihat Riwayat Log -->
                    <button @click="openLogModal = true" class="bg-gray-700 hover:bg-gray-800 text-white font-semibold py-1.5 px-3 rounded-lg shadow transition text-sm flex items-center gap-1.5">
                        <span>📜</span> Log
                    </button>

                    <!-- Tombol New Adjustment -->
                    <button @click="openModal = true" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-1.5 px-3 rounded-lg shadow transition text-sm flex items-center gap-1.5">
                        <span>⚙️</span> Adjustment
                    </button>
                </div>
            </div>

            <!-- ALERT NOTIFIKASI -->
            @if(session('success'))
                <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm font-medium flex-shrink-0">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-sm font-medium flex-shrink-0">
                    {{ session('error') }}
                </div>
            @endif

            <!-- WRAPPER TABEL DENGAN SCROLL KHUSUS -->
            <div class="flex-1 overflow-y-auto overflow-x-auto mt-4 relative border border-gray-100 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider sticky top-0 z-10 shadow-sm ">
                        <tr>
                            <th class="py-3 px-4 font-semibold bg-gray-50">No</th>
                            <th class="py-3 px-4 font-semibold bg-gray-50">Part Number Child Part</th>
                            <th class="py-3 px-4 font-semibold bg-gray-50">Material Name</th>
                            <th class="py-3 px-4 font-semibold text-center bg-gray-50">Child Part Qty (Stok)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-600 bg-white">
                        @forelse($childParts as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-gray-400 text-xs">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3 px-4 font-mono font-medium text-gray-900">
                                    {{ $item->part_number_child_part }}
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-800">
                                    {{ $item->material_name }}
                                </td>
                                <!-- Kolom Stok Akhir -->
                                <td class="py-3 px-4 text-center">
                                    @if($item->child_part_qty > 10)
                                        <span class="bg-emerald-50 text-emerald-700 font-bold px-3 py-1 rounded-full text-xs">
                                            {{ number_format($item->child_part_qty) }} pcs
                                        </span>
                                    @elseif($item->child_part_qty > 0)
                                        <span class="bg-amber-50 text-amber-700 font-bold px-3 py-1 rounded-full text-xs">
                                            {{ number_format($item->child_part_qty) }} pcs (Menipis)
                                        </span>
                                    @else
                                        <span class="bg-rose-50 text-rose-700 font-bold px-3 py-1 rounded-full text-xs">
                                            Habis (0)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-400 italic">
                                    Tidak ada data stock card yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <!-- MODAL 1: FORM STOCK ADJUSTMENT -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto bg-opacity-10 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
            <div @click.away="openModal = false" class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative">
                <button @click="openModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>

                <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">⚙️</div>
                    <h3 class="text-base font-bold text-gray-800">Form Stock Adjustment Material</h3>
                </div>

                <form action="{{ route('scan.stock_adjustment.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="child_part_select" class="block text-sm font-semibold text-gray-700 mb-1">Part Number Child Part</label>
                        <select name="t_log_stock_adjustment_child_part" id="child_part_select" required class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                            <option value="">-- Pilih Part Number --</option>
                            @foreach($childParts as $part)
                                <option value="{{ $part->part_number_child_part }}" data-name="{{ $part->material_name }}" data-qty="{{ $part->child_part_qty }}" {{ old('t_log_stock_adjustment_child_part') == $part->part_number_child_part ? 'selected' : '' }}>
                                    {{ $part->part_number_child_part }} - {{ $part->material_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="part_name_input" class="block text-sm font-semibold text-gray-700 mb-1">Nama Part (Material Name)</label>
                        <input type="text" id="part_name_input" readonly placeholder="Otomatis terisi..." class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 text-sm cursor-not-allowed">
                    </div>

                    <div>
                        <label for="current_stock_input" class="block text-sm font-semibold text-gray-700 mb-1">Stock Saat Ini</label>
                        <input type="number" id="current_stock_input" readonly placeholder="Otomatis terisi..." class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 text-sm cursor-not-allowed">
                    </div>

                   <div>
                        <label for="actual_stock_input" class="block text-sm font-semibold text-gray-700 mb-1">Stok Fisik Riil di Lapangan (QTY Aktual)</label>
                        <input type="number" id="actual_stock_input" name="t_log_stock_adjustment_qty" value="{{ old('t_log_stock_adjustment_qty') }}" min="0" placeholder="Masukkan jumlah stok aktual saat ini..." required class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Sistem akan otomatis menyesuaikan stok agar sama persis dengan angka ini.</p>
                    </div>

                    <div>
                        <label for="reason_input" class="block text-sm font-semibold text-gray-700 mb-1">Alasan Penyesuaian (Reason)</label>
                        <textarea id="reason_input" name="t_log_stock_adjustment_reason" rows="2" placeholder="Contoh: Hasil Stock Opname Bulanan / Selisih Fisik" required class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm">{{ old('t_log_stock_adjustment_reason') }}</textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                        <button type="button" @click="openModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">Batal</button>
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-5 rounded-lg shadow transition text-sm">Simpan Adjustment</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: RIWAYAT LOG STOCK ADJUSTMENT -->
        <div x-show="openLogModal" class="fixed inset-0 z-50 overflow-y-auto bg-opacity-10 backdrop-blur-sm flex items-top justify-center p-4" style="display: none;">
            <div @click.away="openLogModal = false" class="bg-white rounded-2xl shadow-xl max-w-6xl w-full p-6 relative max-h-[90vh] flex flex-col">
                <button @click="openLogModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>

                <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-800 flex items-center justify-center font-bold text-sm">📜</div>
                    <h3 class="text-base font-bold text-gray-800">Riwayat Log Stock Adjustment</h3>
                </div>

                <!-- Tabel Riwayat Log Scrollable -->
                <div class="overflow-y-auto flex-grow">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs sticky top-0">
                            <tr>
                                <th class="py-3 px-3">No</th>
                                <th class="py-3 px-3">Timestamp</th>
                                <th class="py-3 px-3">Part Number</th>
                                <th class="py-3 px-3">Part Name</th>
                                <th class="py-3 px-3 text-center">Tipe</th>
                                <th class="py-3 px-3 text-center">Qty</th>
                                <th class="py-3 px-3 text-center">Perubahan</th>
                                <th class="py-3 px-3">Alasan</th>
                                <th class="py-3 px-3 text-center">PIC & Grup</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-600">
                            @isset($adjustmentLogs)
                                @forelse($adjustmentLogs as $index => $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2.5 px-3 text-gray-400 text-xs">{{ $adjustmentLogs->firstItem() + $index }}</td>
                                        <td class="py-2.5 px-3 text-xs text-gray-500">{{ $log->t_log_stock_adjustment_timestamp }}</td>
                                        <td class="py-2.5 px-3 font-mono text-xs text-gray-900">{{ $log->t_log_stock_adjustment_child_part }}</td>
                                        <td class="py-2.5 px-3 text-xs text-gray-800">{{ $log->t_log_stock_adjustment_part_name }}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            @if($log->t_log_stock_adjustment_type == 'IN')
                                                <span class="bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded text-xs">IN</span>
                                            @else
                                                <span class="bg-rose-50 text-rose-700 font-bold px-2 py-0.5 rounded text-xs">OUT</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-3 text-center font-bold text-xs">{{ number_format($log->t_log_stock_adjustment_qty) }}</td>
                                        <td class="py-2.5 px-3 text-center text-xs">
                                            <span class="text-gray-400">{{ $log->t_log_stock_adjustment_stock_before }}</span> ➔ 
                                            <span class="font-bold text-gray-800">{{ $log->t_log_stock_adjustment_stock_after }}</span>
                                        </td>
                                        <td class="py-2.5 px-3 text-xs text-gray-600">{{ $log->t_log_stock_adjustment_reason }}</td>
                                        <td class="py-2.5 px-3 text-center text-xs">
                                            <span class="font-semibold">{{ $log->t_log_stock_adjustment_pic }}</span> 
                                            <span class="text-gray-500">(Grp {{ $log->t_log_stock_adjustment_grup }})</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-8 text-center text-gray-400 italic">Belum ada riwayat log adjustment.</td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-gray-400 italic">Variabel log belum dikirim dari controller.</td>
                                </tr>
                            @endisset
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Log (Opsional jika variabel tersedia) -->
                @if(isset($adjustmentLogs) && method_exists($adjustmentLogs, 'links'))
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        {{ $adjustmentLogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>