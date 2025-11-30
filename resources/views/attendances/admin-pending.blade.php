<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between animate-fade-in-down">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-gray-100 leading-tight">
                    {{ __('Persetujuan Absensi') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Review dan validasi absensi yang masuk</p>
            </div>
            <div class="text-5xl opacity-20">✅</div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- ALERT: Jumlah Pending -->
            @if($pendingCount > 0)
                <div class="mb-8 animate-fade-in-up">
                    <div class="relative overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl shadow-2xl p-8 border border-red-400">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white rounded-full animate-pulse"></div>
                        </div>
                        
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-start flex-1">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-red-700 animate-bounce">
                                        <span class="text-2xl">🔔</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-white font-bold text-2xl">
                                        {{ $pendingCount }} Absensi Menunggu Persetujuan
                                    </h3>
                                    <p class="text-red-100 text-sm mt-1">
                                        📌 Segera review data absensi yang baru masuk untuk memastikan keakuratan data
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0 text-6xl opacity-30">⏳</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TABEL ABSENSI PENDING -->
            <div class="animate-fade-in-up bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700">
                
                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-3xl mr-3">📋</span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Data Absensi Pending</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Geser tabel untuk melihat kolom lebih banyak</p>
                            </div>
                        </div>
                        @if($pendingAttendances->count() > 0)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 badge-pulse">
                                {{ $pendingAttendances->count() }} data
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if($pendingAttendances->count() > 0)
                        <div class="relative overflow-x-auto shadow-inner">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs font-semibold text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300 sticky top-0">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">No</th>
                                        <th scope="col" class="px-6 py-4">Pegawai</th>
                                        <th scope="col" class="px-6 py-4">Jabatan</th>
                                        <th scope="col" class="px-6 py-4">Tanggal & Jam</th>
                                        <th scope="col" class="px-6 py-4">Foto Bukti</th>
                                        <th scope="col" class="px-6 py-4">Lokasi</th>
                                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingAttendances as $key => $attendance)
                                    <tr class="table-row-animate bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                        
                                        <!-- No -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold text-sm">
                                                {{ $key + 1 }}
                                            </span>
                                        </td>

                                        <!-- Pegawai -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm mr-3">
                                                    {{ substr($attendance->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 dark:text-white">
                                                        {{ $attendance->user->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        NRP: {{ $attendance->user->nrp }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Jabatan -->
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $attendance->user->pangkat ?? '-' }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $attendance->user->jabatan ?? '-' }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Tanggal & Jam -->
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded mt-1 inline-block">
                                                🕐 {{ $attendance->time_in }}
                                            </div>
                                        </td>

                                        <!-- Foto Bukti -->
                                        <td class="px-6 py-4 text-center">
                                            @if($attendance->photo_in)
                                                <a href="{{ asset('storage/attendances/'.$attendance->photo_in) }}" 
                                                   target="_blank"
                                                   class="inline-block group">
                                                    <img src="{{ asset('storage/attendances/'.$attendance->photo_in) }}" 
                                                         alt="Bukti Absensi"
                                                         class="h-12 w-12 rounded-lg object-cover hover:scale-150 transition cursor-pointer shadow-md hover:shadow-lg group-hover:ring-2 group-hover:ring-indigo-500"
                                                         title="Klik untuk buka ukuran penuh">
                                                </a>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>

                                        <!-- Lokasi -->
                                        <td class="px-6 py-4">
                                            @php
                                                $coords = explode(',', $attendance->latlon_in ?? '');
                                                $lat = $coords[0] ?? null;
                                                $lng = $coords[1] ?? null;
                                            @endphp
                                            @if($lat && $lng)
                                                <a href="https://maps.google.com/?q={{ $lat }},{{ $lng }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:shadow-lg transition-all hover:scale-105">
                                                    📍 {{ number_format($lat, 4) }}, {{ number_format($lng, 4) }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>

                                        <!-- Aksi Admin -->
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2 justify-center flex-wrap">
                                                <!-- Tombol Approve -->
                                                <form action="{{ route('attendances.approve', $attendance->id) }}" method="POST" style="display:inline;" 
                                                      onsubmit="return confirm('✅ Setujui absensi dari ' + '{{ $attendance->user->name }}' + '?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-lg text-xs shadow-lg hover:shadow-xl transition-all transform hover:scale-105 btn-animate">
                                                        ✅ Setujui
                                                    </button>
                                                </form>

                                                <!-- Tombol Reject -->
                                                <form action="{{ route('attendances.reject', $attendance->id) }}" method="POST" style="display:inline;" 
                                                      onsubmit="return confirm('❌ Tolak absensi dari ' + '{{ $attendance->user->name }}' + '?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-lg text-xs shadow-lg hover:shadow-xl transition-all transform hover:scale-105 btn-animate">
                                                        ❌ Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination jika ada banyak data -->
                        @if($pendingAttendances->hasPages())
                            <div class="mt-6 flex justify-center">
                                {{ $pendingAttendances->links() }}
                            </div>
                        @endif

                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="text-8xl mb-4 animate-bounce">✨</div>
                            <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">
                                Tidak ada absensi menunggu persetujuan
                            </p>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                Semua absensi sudah diproses. Kerja bagus! 🎉
                            </p>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                                ← Kembali ke Dashboard
                            </a>
                        </div>
                    @endif

                </div>
            </div>

            <!-- NAVIGATION LINKS -->
            <div class="mt-8 animate-fade-in-up grid grid-cols-2 gap-4">
                <a href="{{ route('attendances.history') }}" 
                   class="group p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 border border-indigo-200 dark:border-indigo-700 rounded-xl hover:shadow-lg transition-all transform hover:scale-105">
                    <div class="text-3xl mb-3 group-hover:scale-125 transition-transform">📊</div>
                    <div class="text-sm font-bold text-indigo-900 dark:text-indigo-100">Riwayat Absensi</div>
                    <div class="text-xs text-indigo-700 dark:text-indigo-300">Lihat semua data historis</div>
                </a>

                <a href="{{ route('dashboard') }}" 
                   class="group p-6 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 border border-purple-200 dark:border-purple-700 rounded-xl hover:shadow-lg transition-all transform hover:scale-105">
                    <div class="text-3xl mb-3 group-hover:scale-125 transition-transform">📈</div>
                    <div class="text-sm font-bold text-purple-900 dark:text-purple-100">Dashboard</div>
                    <div class="text-xs text-purple-700 dark:text-purple-300">Kembali ke halaman utama</div>
                </a>
            </div>

        </div>
    </div>

    <!-- Modal Detail (untuk future enhancement) -->
    <script>
        function showDetail(id) {
            alert("Detail untuk absensi ID: " + id + "\n\n(Feature coming soon)");
        }
    </script>
</x-app-layout>
