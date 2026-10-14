<x-layout :activeMenu="$activeMenu">
    <div class="flex flex-col gap-6 py-6 px-3 bg-gray-50/50 min-h-screen w-full">

        <!-- Header Halaman -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Histori Session (Riwayat Login & Logout)</h2>
                <p class="text-xs text-gray-400 mt-0.5">Catatan aktivitas login dan logout seluruh pengguna sistem.</p>
            </div>

            <!-- Form Pencarian -->
            <form action="{{ route('session.history') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari username / nama..."
                       class="border border-gray-300 rounded-lg text-xs px-3 py-2 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 w-56">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2 rounded-lg shadow-sm transition-colors">
                    Cari
                </button>
                @if(!empty($search))
                    <a href="{{ route('session.history') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-xs px-4 py-2 rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </form>
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

        <!-- TABEL HISTORI SESSION -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="pb-4 mb-4 border-b border-gray-100">
                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Daftar Aktivitas Login & Logout</h3>
            </div>

            <div class="overflow-x-auto border border-gray-100 rounded-lg">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-gray-50 text-gray-700 font-semibold">
                        <tr>
                            <th class="p-3 border-b border-gray-200">WAKTU</th>
                            <th class="p-3 border-b border-gray-200">USERNAME</th>
                            <th class="p-3 border-b border-gray-200">NAMA</th>
                            <th class="p-3 border-b border-gray-200 text-center">AKSI</th>
                            <th class="p-3 border-b border-gray-200">IP ADDRESS</th>
                            <th class="p-3 border-b border-gray-200">BROWSER</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-3 font-mono text-gray-600">{{ $log->t_log_session_history_timestamp }}</td>
                            <td class="p-3 font-mono text-gray-800">{{ $log->t_log_session_history_username }}</td>
                            <td class="p-3 font-medium text-gray-800">{{ $log->t_log_session_history_name }}</td>
                            <td class="p-3 text-center">
                                @if($log->t_log_session_history_action === 'login')
                                    <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">Login</span>
                                @else
                                    <span class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">Logout</span>
                                @endif
                            </td>
                            <td class="p-3 font-mono text-gray-600">{{ $log->t_log_session_history_ip ?? '-' }}</td>
                            <td class="p-3 text-gray-500 max-w-xs truncate" title="{{ $log->t_log_session_history_browser }}">{{ $log->t_log_session_history_browser ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400 italic text-xs">
                                Belum ada histori session yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>

    </div>
</x-layout>
