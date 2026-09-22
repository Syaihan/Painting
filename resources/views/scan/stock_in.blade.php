<x-layout :activeMenu="$activeMenu">
    <!-- Menggunakan Alpine.js untuk kontrol Modal Pop-up Form -->
    <div x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }} }" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- HEADER & TOMBOL ADD STOCK IN -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-lg">
                        📥
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Stock In Management</h3>
                        <p class="text-xs text-gray-500">Monitoring riwayat masuk material dan form input penambahan stok.</p>
                    </div>
                </div>

                <!-- Aksi: Tombol Buka Modal & Form Search Riwayat -->
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <form action="{{ route('scan.stock_in.form') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari Part / Delivery / PIC..." class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 px-3 w-full sm:w-60">
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-1.5 px-4 rounded-lg shadow transition text-sm">
                            Cari
                        </button>
                        @if(isset($search) && $search != '')
                            <a href="{{ route('scan.stock_in.form') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-1.5 px-3 rounded-lg text-sm transition">
                                Reset
                            </a>
                        @endif
                    </form>

                    <!-- Tombol Add Stock In -->
                    <button @click="openModal = true" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition text-sm flex items-center justify-center gap-2">
                        <span>➕</span> Add Stock In
                    </button>
                </div>
            </div>

            <!-- ALERT NOTIFIKASI -->
            @if(session('success'))
                <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <!-- TABEL RIWAYAT LOG STOCK IN -->
            <div class="overflow-x-auto mt-6">
                <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="py-3 px-4 font-semibold">No</th>
                            <th class="py-3 px-4 font-semibold">Timestamp</th>
                            <th class="py-3 px-4 font-semibold">Part Number</th>
                            <th class="py-3 px-4 font-semibold">Part Name</th>
                            <th class="py-3 px-4 font-semibold text-center">Qty Masuk</th>
                            <th class="py-3 px-4 font-semibold">Delivery Man</th>
                            <th class="py-3 px-4 font-semibold text-center">PIC & Grup</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-600">
                        @forelse($stockInLogs as $index => $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-gray-400 text-xs">
                                    {{ $stockInLogs->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4 text-xs font-medium text-gray-500">
                                    {{ $log->t_log_stock_in_timestamp }}
                                </td>
                                <td class="py-3 px-4 font-mono font-medium text-gray-900">
                                    {{ $log->t_log_stock_in_child_part }}
                                </td>
                                <td class="py-3 px-4 text-gray-800">
                                    {{ $log->t_log_stock_in_part_name }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="bg-blue-50 text-blue-700 font-bold px-3 py-1 rounded-full text-xs">
                                        +{{ number_format($log->t_log_stock_in_qty) }} pcs
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-700 font-medium">
                                    {{ $log->t_log_stock_in_delivery }}
                                </td>
                                <td class="py-3 px-4 text-center text-xs">
                                    <span class="font-semibold text-gray-800">{{ $log->t_log_stock_in_pic }}</span> 
                                    <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded ml-1">Grp {{ $log->t_log_stock_in_grup }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-400 italic">
                                    Belum ada riwayat stock in yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="mt-4 pt-4 border-t border-gray-100">
                {{ $stockInLogs->appends(['search' => $search])->links() }}
            </div>
        </div>

        <!-- MODAL FORM ADD STOCK IN (Background Transparan & Blur Tipis) -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto bg-opacity-10 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
            <div @click.away="openModal = false" class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative">
                
                <!-- Tombol Close Modal (X) -->
                <button @click="openModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">
                    ✕
                </button>

                <!-- HEADER MODAL -->
                <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-sm">
                        ➕
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Form Input Stock In Material</h3>
                </div>

                <!-- FORM INPUT -->
                <form action="{{ route('scan.stock_in.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- 1. Pilih Child Part -->
                    <div>
                        <label for="child_part_select" class="block text-sm font-semibold text-gray-700 mb-1">Part Number Child Part</label>
                        <select name="t_log_stock_in_child_part" id="child_part_select" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">-- Pilih Part Number --</option>
                            @foreach($childParts as $part)
                                <option value="{{ $part->part_number_child_part }}" data-name="{{ $part->material_name }}" data-qty="{{ $part->child_part_qty }}" {{ old('t_log_stock_in_child_part') == $part->part_number_child_part ? 'selected' : '' }}>
                                    {{ $part->part_number_child_part }} - {{ $part->material_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('t_log_stock_in_child_part')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 2. Nama Part (Auto Fill) -->
                    <div>
                        <label for="part_name_input" class="block text-sm font-semibold text-gray-700 mb-1">Nama Part (Material Name)</label>
                        <input type="text" id="part_name_input" readonly placeholder="Otomatis terisi..." class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 text-sm cursor-not-allowed">
                    </div>
                    
                    <div>
                        <label for="current_stock_input" class="block text-sm font-semibold text-gray-700 mb-1">Stock Saat Ini</label>
                        <input type="number" id="current_stock_input" readonly placeholder="Otomatis terisi..." class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 text-sm cursor-not-allowed">
                    </div>

                    <!-- 3. QTY Input Manual -->
                    <div>
                        <label for="qty_input" class="block text-sm font-semibold text-gray-700 mb-1">Quantity (QTY)</label>
                        <input type="number" id="qty_input" name="t_log_stock_in_qty" value="{{ old('t_log_stock_in_qty') }}" min="1" placeholder="Masukkan jumlah qty..." required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('t_log_stock_in_qty')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 4. Delivery Man -->
                    <div>
                        <label for="delivery_input" class="block text-sm font-semibold text-gray-700 mb-1">Delivery Man (Nama Pengantar)</label>
                        <input type="text" id="delivery_input" name="t_log_stock_in_delivery" value="{{ old('t_log_stock_in_delivery') }}" placeholder="Contoh: Budi" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('t_log_stock_in_delivery')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Info Otomatis PIC & Grup -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <label for="pic_input" class="block text-xs font-semibold text-gray-500 mb-1">PIC (Login)</label>
                            <input type="text" id="pic_input" value="{{ $picName }}" readonly class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 text-xs cursor-not-allowed">
                        </div>
                        <div>
                            <label for="group_input" class="block text-xs font-semibold text-gray-500 mb-1">Grup</label>
                            <input type="text" id="group_input" value="Grup {{ $grup }}" readonly class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 text-xs cursor-not-allowed">
                        </div>
                    </div>

                    <!-- TOMBOL KONTROL MODAL -->
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                        <button type="button" @click="openModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg shadow transition text-sm">
                            Simpan Stock In
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layout>