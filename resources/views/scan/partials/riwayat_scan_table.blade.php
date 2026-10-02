<table class="w-full text-left border-collapse text-xs">
    <thead>
        <tr class="bg-gray-100 text-gray-700 border-b">
            <th class="p-2.5">No</th>
            <th class="p-2.5">Finished Good (FG)</th>
            <th class="p-2.5">Part Number</th>
            <th class="p-2.5">Operator</th>
            <th class="p-2.5">PIC</th>
            <th class="p-2.5">Grup</th>
            <th class="p-2.5">Waktu Scan</th>
        </tr>
        <!-- Baris Input Pencarian Per Kolom -->
        <tr class="bg-gray-50 border-b">
            <th class="p-2"></th>
            <th class="p-2">
                <input type="text" x-model="search.t_log_scan_fg" @keydown.enter="fetchData()" placeholder="Enter cari FG..." 
                    class="w-full text-[11px] border border-gray-300 rounded p-1.5 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </th>
            <th class="p-2">
                <input type="text" x-model="search.t_log_scan_fg_part_number" @keydown.enter="fetchData()" placeholder="Enter cari Part No..." 
                    class="w-full text-[11px] border border-gray-300 rounded p-1.5 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </th>
            <th class="p-2">
                <input type="text" x-model="search.t_log_scan_operator" @keydown.enter="fetchData()" placeholder="Enter cari Operator..." 
                    class="w-full text-[11px] border border-gray-300 rounded p-1.5 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </th>
            <th class="p-2">
                <input type="text" x-model="search.t_log_scan_pic" @keydown.enter="fetchData()" placeholder="Enter cari PIC..." 
                    class="w-full text-[11px] border border-gray-300 rounded p-1.5 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </th>
            <th class="p-2">
                <input type="text" x-model="search.t_log_scan_grup" @keydown.enter="fetchData()" placeholder="Enter cari Grup..." 
                    class="w-full text-[11px] border border-gray-300 rounded p-1.5 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </th>
            <th class="p-2"></th>
        </tr>
    </thead>
    <tbody>
        @forelse($logs as $index => $log)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-2.5">{{ $logs->firstItem() + $index }}</td>
                <td class="p-2.5">{{ $log->t_log_scan_fg }}</td>
                <td class="p-2.5">{{ $log->t_log_scan_fg_part_number }}</td>
                <td class="p-2.5">{{ $log->t_log_scan_operator }}</td>
                <td class="p-2.5">{{ $log->t_log_scan_pic }}</td>
                <td class="p-2.5">{{ $log->t_log_scan_grup }}</td>
                <td class="p-2.5">{{ $log->t_log_scan_timestamp }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center p-4 text-gray-400">Tidak ada data ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination Links -->
<div class="mt-4">
    {{ $logs->links() }}
</div>