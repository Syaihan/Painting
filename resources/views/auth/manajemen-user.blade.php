<x-layout :activeMenu="$activeMenu">
    <!-- Menggunakan Alpine.js state untuk Modal Tambah & Edit -->
    <div class="flex flex-col gap-6 py-6 px-3 bg-gray-50/50 min-h-screen w-full" 
         x-data="{ 
            showModal: false, 
            isEdit: false, 
            userId: '', 
            name: '', 
            username: '', 
            roleId: '',
            openCreateModal() {
                this.isEdit = false;
                this.userId = '';
                this.name = '';
                this.username = '';
                this.roleId = '';
                this.showModal = true;
            },
            openEditModal(user) {
                this.isEdit = true;
                this.userId = user.id;
                this.name = user.name;
                this.username = user.username;
                this.roleId = user.role_id;
                this.showModal = true;
            }
         }">
        
        <!-- Header & Tombol Trigger Modal Tambah -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Manajemen Pengguna Sistem</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola daftar akun, hak akses, dan pendaftaran pengguna baru.</p>
            </div>
            
            <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                <span>+ Tambah Akun Baru</span>
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

        <!-- TABEL DAFTAR USER -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="pb-4 mb-4 border-b border-gray-100">
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Daftar Seluruh Akun Terdaftar</h3>
            </div>

            <div class="overflow-x-auto border border-gray-100 rounded-lg">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-gray-50 text-gray-700 font-semibold">
                        <tr>
                            <th class="p-3 border-b border-gray-200">NAMA</th>
                            <th class="p-3 border-b border-gray-200">USERNAME</th>
                            <th class="p-3 border-b border-gray-200">ROLE</th>
                            <th class="p-3 border-b border-gray-200 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $usr)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-3 font-medium text-gray-800">{{ $usr->name }}</td>
                            <td class="p-3 font-mono text-gray-600">{{ $usr->username }}</td>
                            <td class="p-3">
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-semibold text-[10px]">
                                    {{ $usr->display_name ?? $usr->role_name }}
                                </span>
                            </td>
                            <td class="p-3 text-center space-x-1">
                                <!-- Tombol Edit -->
                                <button @click="openEditModal({{ json_encode($usr) }})" class="bg-amber-50 hover:bg-amber-100 text-amber-600 px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">
                                    Edit
                                </button>

                                <!-- Tombol Hapus -->
                                @if($usr->id != session('operator_id'))
                                <form action="{{ route('users.delete', $usr->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-2.5 py-1 rounded text-[11px] font-semibold transition-colors">
                                        Hapus
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 italic text-[10px]">(Akun Anda)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== MODAL MENGAMBANG (TAMBAH & EDIT) ==================== -->
        <div x-show="showModal" 
             style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-xs transition-opacity"
             x-transition.opacity>
            
            <!-- Kotak Form Modal -->
            <div @click.away="showModal = false" 
                 class="bg-white border border-gray-200 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden max-h-[90vh] flex flex-col">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider" x-text="isEdit ? 'Form Edit Akun Pengguna' : 'Form Tambah Akun Baru'"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-sm font-bold">
                        ✕
                    </button>
                </div>

                <!-- Modal Body (Bisa Di-scroll) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-grow">
                    <form :action="isEdit ? '{{ url('/manajemen-user') }}/' + userId : '{{ route('register.process') }}'" 
                          method="POST" 
                          id="formUserAction" 
                          class="space-y-4">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" name="name" x-model="name" required 
                                class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Username</label>
                            <input type="text" name="username" x-model="username" required 
                                class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Role / Hak Akses</label>
                            <select name="role_id" x-model="roleId" required class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name ?? $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                                Password <span x-show="isEdit" class="text-gray-400 font-normal normal-case">(Kosongkan jika tidak ingin mengubah password)</span>
                            </label>
                            <input type="password" name="password" :required="!isEdit" 
                                class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" :required="!isEdit" 
                                class="w-full text-xs border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50">
                    <button type="button" @click="showModal = false" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs px-4 py-2 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" form="formUserAction" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-5 py-2 rounded-lg shadow-sm transition-colors" x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Akun Baru'">
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-layout>