<x-layout :activeMenu="$activeMenu">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Notifikasi Sukses / Gagal -->
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm text-rose-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- FORM INPUT NG SEALING -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Input Data NG Sealing</h3>
            
            <form action="{{ route('scan.nglog.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                @csrf
                
                <!-- Child Part -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Child Part</label>
                    <!-- Tambahkan id="child_part_select" di sini -->
                    <select name="t_log_ng_sealing_child_part" id="child_part_select" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">-- Pilih Part Number --</option>
                        @foreach($childParts as $part)
                            <option value="{{ $part->part_number_child_part }}" data-name="{{ $part->material_name }}" {{ old('t_log_ng_sealing_child_part') == $part->part_number_child_part ? 'selected' : '' }}>
                                {{ $part->part_number_child_part }} - {{ $part->material_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Part Name (Otomatis Terisi & Dikirim ke Controller) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Part Name</label>
                    <!-- Ubah menjadi input text dengan name agar ikut terkirim ke database saat form disubmit -->
                    <input type="text" name="t_log_ng_sealing_part_name" id="part_name_input" readonly placeholder="Otomatis terisi..." class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-600 text-sm cursor-not-allowed">
                </div>

                <!-- Qty NG -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">QTY NG</label>
                    <input type="number" name="t_log_ng_sealing_qty" placeholder="Jumlah NG..." min="1" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <!-- Keterangan NG -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Keterangan / Jenis NG</label>
                    <select name="t_log_ng_sealing_ket" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">Pilih Keterangan NG</option>
                        <option value="Retak">Retak</option>
                        <option value="Peel off">Peel off</option>
                        <option value="NG Pemasangan">NG Pemasangan</option>
                        <option value="Dimensi Beda">Dimensi Beda</option>
                    </select>
                </div>

                <!-- Operator -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Operator</label>
                    <select name="t_log_ng_sealing_operator" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">Pilih Operator</option>
                        @foreach($operators as $operator)
                            <option value="{{ $operator->nama }}">{{ $operator->nama }} (Grup {{ $operator->grup }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Grup (Readonly) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Grup</label>
                    <input type="text" value="Grup {{ $grup }}" readonly class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 text-sm cursor-not-allowed">
                </div>

                <!-- Tombol Simpan -->
                <div class="md:col-span-2 lg:col-span-2">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition text-sm">
                        Simpan Data NG
                    </button>
                </div>
            </form>
        </div>

        <!-- TABEL RIWAYAT NG -->
        <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Riwayat NG Sealing Terbaru</h3>
            
            <div class="max-h-[350px] overflow-y-auto overflow-x-auto border border-gray-200 rounded">
                <table class="w-full text-center border-collapse text-xs">
                    <thead class="sticky top-0 bg-red-100 text-red-900 z-10">
                        <tr>
                            <th class="border border-gray-300 p-2">WAKTU</th>
                            <th class="border border-gray-300 p-2">CHILD PART</th>
                            <th class="border border-gray-300 p-2">PART NAME</th>
                            <th class="border border-gray-300 p-2">QTY</th>
                            <th class="border border-gray-300 p-2">KETERANGAN</th>
                            <th class="border border-gray-300 p-2">OPERATOR</th>
                            <th class="border border-gray-300 p-2">GRUP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ngHistory as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 p-2 font-mono text-[11px]">{{ $row->t_log_ng_sealing_timestamp }}</td>
                            <td class="border border-gray-300 p-2 font-mono text-left">{{ $row->t_log_ng_sealing_child_part }}</td>
                            <td class="border border-gray-300 p-2 text-left">{{ $row->t_log_ng_sealing_part_name }}</td>
                            <td class="border border-gray-300 p-2 font-bold text-red-600">{{ $row->t_log_ng_sealing_qty }}</td>
                            <td class="border border-gray-300 p-2 text-left">{{ $row->t_log_ng_sealing_ket }}</td>
                            <td class="border border-gray-300 p-2">{{ $row->t_log_ng_sealing_operator }}</td>
                            <td class="border border-gray-300 p-2 font-semibold">Grup {{ $row->t_log_ng_sealing_grup }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="border border-gray-300 p-6 text-center text-gray-400 italic">
                                Belum ada data NG yang diinput.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectElement = document.getElementById('child_part_select');
            if(selectElement) {
                function updatePartName() {
                    var selectedOption = selectElement.options[selectElement.selectedIndex];
                    var materialName = selectedOption.getAttribute('data-name');
                    document.getElementById('part_name_input').value = materialName ? materialName : '';
                }
                selectElement.addEventListener('change', updatePartName);
                // Jalankan saat halaman dimuat (jika ada nilai lama / old value)
                if(selectElement.value) { 
                    updatePartName(); 
                }
            }
        });
    </script>
</x-layout>