<x-layout :activeMenu="$activeMenu">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between" role="alert">
            <div class="flex items-center gap-3">
                <span class="text-emerald-600 text-xl">✅</span>
                <div>
                    <p class="text-sm font-bold text-emerald-800">Berhasil</p>
                    <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 font-bold text-sm">✕</button>
        </div>
        @endif

        <!-- Notifikasi Error -->
        @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between" role="alert">
            <div class="flex items-center gap-3">
                <span class="text-rose-600 text-xl">⚠️</span>
                <div>
                    <p class="text-sm font-bold text-rose-800">Gagal Memproses</p>
                    <p class="text-xs text-rose-700">{{ session('error') }}</p>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600 font-bold text-sm">✕</button>
        </div>
        @endif
        <!-- BARIS PERTAMA: CARD SCAN REGULER -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-200 p-6 ">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-lg">
                    📦
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Scan Barcode (Reguler)</h3>
                    <p class="text-xs text-gray-500">Pilih operator terlebih dahulu, lalu scan barcode untuk memproses data.</p>
                </div>
            </div>

            <form action="{{ route('scan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                @csrf
                <!-- Pilihan Operator -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Pilih Operator</label>
                    <select name="operator" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">-- Pilih Operator --</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->nama }}">{{ $op->nama }} (Grup {{ $op->grup }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Barcode -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Scan Barcode</label>
                    <input type="text" name="barcode" placeholder="Scan atau ketik barcode..." required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <!-- Qty default 1 untuk reguler, atau bisa disesuaikan dari master item nanti -->
                    <input type="hidden" name="qty" value="60"> 
                </div>

                <!-- Tombol Eksekusi -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition text-sm flex items-center justify-center gap-2">
                        <span>Eksekusi Scan</span>
                    </button>
                </div>
            </form>
        </div>


        <!-- BARIS KEDUA: CARD SCAN PARTIAL -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-200 p-6 ">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg">
                    📊
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Scan Barcode (Partial Qty Manual)</h3>
                    <p class="text-xs text-gray-500">Pilih operator, scan barcode, dan masukkan jumlah QTY secara manual.</p>
                </div>
            </div>

            <form action="{{ route('scan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <!-- Pilihan Operator -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Pilih Operator</label>
                    <select name="operator" required class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                        <option value="">-- Pilih Operator --</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->nama }}">{{ $op->nama }} (Grup {{ $op->grup }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Barcode -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Scan Barcode</label>
                    <input type="text" name="barcode" placeholder="Scan atau ketik barcode..." required class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                </div>

                <!-- Input Qty Manual -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jumlah QTY</label>
                    <input type="number" name="qty" placeholder="Masukkan QTY..." min="1" required class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                </div>

                <!-- Tombol Eksekusi -->
                <div>
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition text-sm flex items-center justify-center gap-2">
                        <span>Eksekusi Partial</span>
                    </button>
                </div>
            </form>
        </div>


        <!-- BARIS KETIGA: RIWAYAT SCAN -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 ">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-800 flex items-center justify-center font-bold text-lg">
                        🕒
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Riwayat Scan Hari Ini</h3>
                        <p class="text-xs text-gray-500">Daftar aktivitas pemindaian yang telah berhasil dieksekusi.</p>
                    </div>
                </div>
                <a href="{{ route('scan.console') }}" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-1.5 px-3 rounded-lg transition">
                    Refresh Data
                </a>
            </div>

            <!-- Tabel Riwayat -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider ">
                        <tr>
                            <th class="py-3 px-4 font-semibold">Jam</th>
                            <th class="py-3 px-4 font-semibold">FQ / Barcode</th>
                            <th class="py-3 px-4 font-semibold">PART NUMBER FG</th>
                            <th class="py-3 px-4 font-semibold">QTY</th>
                            <th class="py-3 px-4 font-semibold">Operator</th>
                            <th class="py-3 px-4 font-semibold">PIC</th>
                            <th class="py-3 px-4 font-semibold">Grup</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-600">
                        @forelse($history as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-mono text-xs">{{ $item->jam }}</td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $item->fq }}</td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $item->part_number }}</td>
                                <td class="py-3 px-4">
                                    <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded text-xs">{{ $item->qty }}</span>
                                </td>
                                <td class="py-3 px-4">{{ $item->operator }}</td>
                                <td class="py-3 px-4">{{ $item->pic }}</td>
                                <td class="py-3 px-4">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-xs">Grup {{ $item->grup }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-400 italic">Belum ada riwayat scan hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layout>