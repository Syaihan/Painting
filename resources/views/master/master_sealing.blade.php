<x-layout :activeMenu="$activeMenu">
    <!-- Inisialisasi State Alpine.js dengan mendeteksi parameter tab dari URL -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{ activeTab: '{{ request('tab', 'fg') }}' }">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Master Data Sealing</h1>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- SELECTOR PILIH TABEL -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <label for="tableSelector" class="font-semibold text-gray-700">Pilih Tabel yang Dibuka:</label>
                <!-- Tambahkan handler @change agar saat tab diganti, URL ikut memperbarui parameter ?tab=... (opsional tapi bagus untuk state form search) -->
                <select id="tableSelector" x-model="activeTab" class="mt-1 block w-full sm:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm border p-2">
                    <option value="fg">Finish Good (FG)</option>
                    <option value="cp">Child Part (CP)</option>
                    <option value="bom">Bill of Materials (BOM)</option>
                </select>
            </div>
        </div>

        <div class="space-y-6">
            
            <!-- TAB 1: FINISH GOOD -->
            <div x-show="activeTab === 'fg'" x-transition.opacity.duration.300ms class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Finish Good</h3>
                    <button type="button" onclick="openModal('addFgModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">Tambah FG</button>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('master.sealing.index') }}" class="mb-4 flex gap-2">
                        <input type="hidden" name="tab" value="fg">
                        <input type="text" name="search_fg" class="flex-1 rounded-md border-gray-300 shadow-sm border p-2 text-sm" placeholder="Cari Child Part..." value="{{ $searchFg ?? '' }}">
                        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">Cari</button>
                    </form>
                    <div class="overflow-x-auto max-h-80 overflow-y-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Barcode FG</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Part Number FG</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Nama FG</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($finishGoods as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->barcode_fg }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->part_number_fg }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->fg_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <button type="button" onclick="openModal('editFg{{ $item->barcode_fg }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                                        <form action="{{ route('master.sealing.fg.destroy', $item->barcode_fg) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit FG (Dipindah keluar dari baris tabel agar struktur HTML valid) -->
                                <div id="editFg{{ $item->barcode_fg }}" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-30 backdrop-blur-sm hidden">
                                    <div class="bg-white rounded-lg max-w-lg w-full p-6">
                                        <div class="flex justify-between items-center pb-3 border-b">
                                            <h3 class="text-lg font-medium">Edit Finish Good</h3>
                                            <button type="button" onclick="closeModal('editFg{{ $item->barcode_fg }}')" class="text-gray-400 hover:text-gray-600">&times;</button>
                                        </div>
                                        <form action="{{ route('master.sealing.fg.update', $item->barcode_fg) }}" method="POST" class="mt-4 space-y-4">
                                            @csrf @method('PUT')
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Barcode FG</label>
                                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 border p-2 text-sm" value="{{ $item->barcode_fg }}" disabled>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Part Number FG</label>
                                                <input type="text" name="part_number_fg" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="{{ $item->part_number_fg }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Nama FG</label>
                                                <input type="text" name="fg_name" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="{{ $item->fg_name }}" required>
                                            </div>
                                            <div class="flex justify-end space-x-2 pt-4">
                                                <button type="button" onclick="closeModal('editFg{{ $item->barcode_fg }}')" class="bg-gray-300 px-4 py-2 rounded text-sm">Batal</button>
                                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: CHILD PART -->
            <div x-show="activeTab === 'cp'" x-transition.opacity.duration.300ms class="bg-white shadow rounded-lg overflow-hidden" x-cloak>
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Child Part</h3>
                    <button type="button" onclick="openModal('addCpModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">Tambah Child Part</button>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('master.sealing.index') }}" class="mb-4 flex gap-2">
                        <input type="hidden" name="tab" value="cp">
                        <input type="text" name="search_cp" class="flex-1 rounded-md border-gray-300 shadow-sm border p-2 text-sm" placeholder="Cari Child Part..." value="{{ $searchCp ?? '' }}">
                        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">Cari</button>
                    </form>
                    <div class="overflow-x-auto max-h-80 overflow-y-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Part Number</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Material Name</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Qty Stok</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($childParts as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->part_number_child_part }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->material_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->child_part_qty }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <button type="button" onclick="openModal('editCp{{ $item->part_number_child_part }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                                        <form action="{{ route('master.sealing.cp.destroy', $item->part_number_child_part) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit CP -->
                                <div id="editCp{{ $item->part_number_child_part }}" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-30 backdrop-blur-sm hidden">
                                    <div class="bg-white rounded-lg max-w-lg w-full p-6">
                                        <div class="flex justify-between items-center pb-3 border-b">
                                            <h3 class="text-lg font-medium">Edit Child Part</h3>
                                            <button type="button" onclick="closeModal('editCp{{ $item->part_number_child_part }}')" class="text-gray-400 hover:text-gray-600">&times;</button>
                                        </div>
                                        <form action="{{ route('master.sealing.cp.update', $item->part_number_child_part) }}" method="POST" class="mt-4 space-y-4">
                                            @csrf @method('PUT')
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Part Number</label>
                                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 border p-2 text-sm" value="{{ $item->part_number_child_part }}" disabled>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Material Name</label>
                                                <input type="text" name="material_name" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="{{ $item->material_name }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Qty Stok</label>
                                                <input type="number" name="child_part_qty" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="{{ $item->child_part_qty }}" min="0" required>
                                            </div>
                                            <div class="flex justify-end space-x-2 pt-4">
                                                <button type="button" onclick="closeModal('editCp{{ $item->part_number_child_part }}')" class="bg-gray-300 px-4 py-2 rounded text-sm">Batal</button>
                                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: BOM -->
            <div x-show="activeTab === 'bom'" x-transition.opacity.duration.300ms class="bg-white shadow rounded-lg overflow-hidden" x-cloak>
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Daftar BOM</h3>
                    <button type="button" onclick="openModal('addBomModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">Tambah BOM</button>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('master.sealing.index') }}" class="mb-4 flex gap-2">
                        <input type="hidden" name="tab" value="bom">
                        <input type="text" name="search_bom" class="flex-1 rounded-md border-gray-300 shadow-sm border p-2 text-sm" placeholder="Cari BOM..." value="{{ $searchBom ?? '' }}">
                        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">Cari</button>
                    </form>
                    <div class="overflow-x-auto max-h-80 overflow-y-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Barcode FG</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Part FG</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Part Child Part</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">BOM Qty</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($boms as $item)
                                @php
                                    $compositeId = $item->barcode_fg . '_' . $item->part_number_child_part;
                                    $modalKey = md5($compositeId);
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->barcode_fg }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->part_number_fg }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->part_number_child_part }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->bom_qty }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <button type="button" onclick="openModal('editBom{{ $modalKey }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                                        <form action="{{ route('master.sealing.bom.destroy', $compositeId) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit BOM -->
                                <div id="editBom{{ $modalKey }}" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-30 backdrop-blur-sm hidden">
                                    <div class="bg-white rounded-lg max-w-lg w-full p-6">
                                        <div class="flex justify-between items-center pb-3 border-b">
                                            <h3 class="text-lg font-medium">Edit BOM</h3>
                                            <button type="button" onclick="closeModal('editBom{{ $modalKey }}')" class="text-gray-400 hover:text-gray-600">&times;</button>
                                        </div>
                                        <form action="{{ route('master.sealing.bom.update', $compositeId) }}" method="POST" class="mt-4 space-y-4">
                                            @csrf @method('PUT')
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Barcode FG</label>
                                                <input type="text" name="barcode_fg" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="{{ $item->barcode_fg }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Part Number FG</label>
                                                <input type="text" name="part_number_fg" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="{{ $item->part_number_fg }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Part Child Part</label>
                                                <input type="text" name="part_number_child_part" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="{{ $item->part_number_child_part }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">BOM Qty</label>
                                                <input type="number" name="bom_qty" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="{{ $item->bom_qty }}" min="1" required>
                                            </div>
                                            <div class="flex justify-end space-x-2 pt-4">
                                                <button type="button" onclick="closeModal('editBom{{ $modalKey }}')" class="bg-gray-300 px-4 py-2 rounded text-sm">Batal</button>
                                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL TAMBAH FINISH GOOD -->
    <div id="addFgModal" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-30 backdrop-blur-sm hidden">
        <div class="bg-white rounded-lg max-w-lg w-full p-6">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-medium">Tambah Finish Good</h3>
                <button type="button" onclick="closeModal('addFgModal')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('master.sealing.fg.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Barcode FG</label>
                    <input type="text" name="barcode_fg" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number FG</label>
                    <input type="text" name="part_number_fg" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama FG</label>
                    <input type="text" name="fg_name" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" required>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" onclick="closeModal('addFgModal')" class="bg-gray-300 px-4 py-2 rounded text-sm">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH CHILD PART -->
    <div id="addCpModal" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-30 backdrop-blur-sm hidden">
        <div class="bg-white rounded-lg max-w-lg w-full p-6">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-medium">Tambah Child Part</h3>
                <button type="button" onclick="closeModal('addCpModal')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('master.sealing.cp.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number Child Part</label>
                    <input type="text" name="part_number_child_part" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Material Name</label>
                    <input type="text" name="material_name" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Qty Stok Awal</label>
                    <input type="number" name="child_part_qty" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="0" min="0" required>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" onclick="closeModal('addCpModal')" class="bg-gray-300 px-4 py-2 rounded text-sm">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH BOM -->
    <div id="addBomModal" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-30 backdrop-blur-sm hidden">
        <div class="bg-white rounded-lg max-w-lg w-full p-6">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-medium">Tambah BOM</h3>
                <button type="button" onclick="closeModal('addBomModal')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('master.sealing.bom.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Barcode FG</label>
                    <input type="text" name="barcode_fg" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number FG</label>
                    <input type="text" name="part_number_fg" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Part Number Child Part</label>
                    <input type="text" name="part_number_child_part" class="mt-1 block w-full rounded-md border-gray-300 border p-2 name class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">BOM Qty</label>
                    <input type="number" name="bom_qty" class="mt-1 block w-full rounded-md border-gray-300 border p-2 text-sm" value="1" min="1" required>
                </div>
                <div class="flex justify-end space-x-1 pt-4">
                    <button type="button" onclick="closeModal('addBomModal')" class="bg-gray-300 px-4 py-2 rounded text-sm">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</x-layout>