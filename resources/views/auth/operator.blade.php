<x-layout :activeMenu="$activeMenu">
    <!-- Menggunakan Alpine.js state untuk Modal Tambah & Edit -->
    <div class="flex flex-col gap-6 py-6 px-3 bg-gray-50/50 min-h-screen w-full"
         x-data="{ 
            showModal: false, 
            isEdit: false, 
            operatorId: '', 
            nama: '', 
            grup: '',
            openCreateModal() {
                this.isEdit = false;
                this.operatorId = '';
                this.nama = '';
                this.grup = '';
                this.showModal = true;
            },
            openEditModal(operator) {
                this.isEdit = true;
                this.operatorId = operator.id;
                this.nama = operator.nama;
                this.grup = operator.grup;
                this.showModal = true;
            }
         }">

        <!-- Header & Tombol Trigger Modal Tambah -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Manajemen Operator</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola data operator produksi dan grupnya.</p>
            </div>

            <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <span>+ Tambah Operator Baru</span>
            </button>
        </div>

        <!-- Notifikasi Global -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl shadow-xs">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl shadow-xs">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl shadow-xs">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- TABEL DAFTAR OPERATOR -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="pb-4 mb-4 border-b border-gray-100">
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Daftar Seluruh Operator</h3>
            </div>

            <div class="overflow-x-auto border border-gray-100 rounded-lg">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-gray-50 text-gray-700 font-semibold">
                        <tr>
                            <th class="p-3 border-b border-gray-200">NAMA</th>
                            <th class="p-3 border-b border-gray-200">GRUP</th>
                            <th class="p-3 border-b border-gray-200 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($operators as $operator)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-3 font-medium text-gray-800">{{ $operator->nama }}</td>
                            <td class="p-3 font-mono text-gray-600">{{ $operator->grup }}</td>
                            <td class="p-3 text-center">
                                <button @click="openEditModal({{ json_encode($operator) }})" 
                                        class="bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded hover:bg-blue-100 transition border border-blue-200">
                                    Edit
                                </button>
                                <form action="{{ route('operator.destroy', $operator->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Yakin ingin menghapus operator ini?')"
                                            class="bg-red-50 text-red-600 text-xs px-2 py-1 rounded hover:bg-red-100 transition border border-red-200">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-gray-400 italic text-xs">
                                Belum ada data operator yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL TAMBAH / EDIT OPERATOR -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase" x-text="isEdit ? 'Edit Operator' : 'Tambah Operator Baru'"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="formOperatorAction" class="space-y-4"
                      :action="isEdit ? '/operator/' + operatorId : '/operator/store'"
                      method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" x-model="operatorId">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Operator</label>
                        <input type="text" name="nama" x-model="nama" required 
                            class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Grup</label>
                        <input type="text" name="grup" x-model="grup" required placeholder="Contoh: A1" 
                            class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </form>

                <!-- Modal Footer -->
                <div class="px-6 py-3 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50">
                    <button type="button" @click="showModal = false" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs px-4 py-2 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" form="formOperatorAction" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-5 py-2 rounded-lg shadow-sm transition-colors" 
                            x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Operator'">
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-layout>