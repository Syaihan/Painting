<x-layout :activeMenu="'Setting Sealing'">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Sealing</h1>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('sealing.setting.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Batas Minimum Stock (min_limit)
                    </label>
                    <input type="number" name="min_limit"
                           value="{{ $settings->min_limit }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Batas Maksimum Stock (max_limit)
                    </label>
                    <input type="number" name="max_limit"
                           value="{{ $settings->max_limit }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Qty Potong Saat Scan Reguler (cutting_qty)
                    </label>
                    <input type="number" name="cutting_qty"
                           value="{{ $settings->cutting_qty }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm"
                           required>
                    <p class="mt-1 text-xs text-gray-400">
                        Qty yang akan dikurangi saat scan reguler. Jika menghasilkan < 0, proses tidak akan diizinkan.
                    </p>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('dashboard.sealing') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>