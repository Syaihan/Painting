<x-layout :activeMenu="$activeMenu">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
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

        <!-- Info Shift Aktif -->
        <div class="bg-white shadow rounded-lg p-4 mb-6 border border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Shift Aktif</h2>
                    <p class="text-sm text-gray-500">
                        <span class="font-semibold">{{ $shiftData['shift_name'] }}</span> |
                        {{ \Carbon\Carbon::parse($shiftData['start_time'])->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($shiftData['end_time'])->format('H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-700">{{ $today }}</p>
                    <p class="text-xs text-gray-500">Tanggal Rencana</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Finish Good</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Isi jumlah Finish Good yang harus diproduksi untuk shift {{ $shiftData['shift_name'] }} pada tanggal {{ $today }}.
                </p>
            </div>

            <div class="overflow-x-auto">
                <form action="{{ route('sealing.plan.store') }}" method="POST">
                    @csrf
                    <table class="w-full text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase border-b border-gray-200">No</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase border-b border-gray-200">Barcode FG</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase border-b border-gray-200">Part Number</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase border-b border-gray-200">Nama Finish Good</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase border-b border-gray-200 text-right">Qty Rencana</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $no = 1;
                            @endphp
                            @foreach($finishGoods as $fg)
                                @php
                                    $barcode = $fg->barcode_fg;
                                    $plan = $existingPlans->get($barcode);
                                    $plannedQty = $plan ? $plan->t_log_sealing_plan_planned_qty : 0;
                                @endphp
                                <tr>
                                <td class="px-4 py-3 text-sm text-gray-700 border-b border-gray-200">{{ $no++ }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 border-b border-gray-200 font-mono">{{ $fg->barcode_fg }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 border-b border-gray-200">{{ $fg->part_number_fg }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 border-b border-gray-200">{{ $fg->fg_name }}</td>
                                <td class="px-4 py-3 border-b border-gray-200">
                                    <input type="number"
                                           name="planned_qty[{{ $barcode }}]"
                                           value="{{ $plannedQty }}"
                                           min="0"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm text-right"
                                           required>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                        <a href="{{ route('dashboard.sealing') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium">
                            Simpan Rencana
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>