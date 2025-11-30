<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between animate-fade-in-down">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-gray-100 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
            </div>
            <div class="text-5xl opacity-20">📊</div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KARTU INFORMASI STATISTIK -->
            @if(Auth::user()->role == 'admin')
                <!-- UNTUK ADMIN: Tampilkan Statistik Umum -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Kartu 1: Total Kantor -->
                    <div class="card-modern bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 text-white rounded-2xl shadow-xl p-8 border border-blue-400 hover-lift cursor-pointer group">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider">Total Kantor</p>
                                <p class="text-5xl font-bold mt-2 group-hover:scale-110 transition-transform">{{ $totalKantor }}</p>
                            </div>
                            <div class="text-8xl opacity-15 group-hover:scale-110 transition-transform">🏢</div>
                        </div>
                        <div class="mt-4 h-1 bg-white opacity-30 rounded-full"></div>
                        <p class="text-blue-100 text-xs mt-3">Lokasi kerja tersedia</p>
                    </div>

                    <!-- Kartu 2: Total Pegawai -->
                    <div class="card-modern bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 text-white rounded-2xl shadow-xl p-8 border border-purple-400 hover-lift cursor-pointer group">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-purple-100 text-sm font-semibold uppercase tracking-wider">Total Pegawai</p>
                                <p class="text-5xl font-bold mt-2 group-hover:scale-110 transition-transform">{{ $totalPegawai }}</p>
                            </div>
                            <div class="text-8xl opacity-15 group-hover:scale-110 transition-transform">👥</div>
                        </div>
                        <div class="mt-4 h-1 bg-white opacity-30 rounded-full"></div>
                        <p class="text-purple-100 text-xs mt-3">Dalam sistem</p>
                    </div>

                    <!-- Kartu 3: Hadir Hari Ini -->
                    <div class="card-modern bg-gradient-to-br from-green-500 via-green-600 to-green-700 text-white rounded-2xl shadow-xl p-8 border border-green-400 hover-lift cursor-pointer group">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-green-100 text-sm font-semibold uppercase tracking-wider">Hadir Hari Ini</p>
                                <p class="text-5xl font-bold mt-2 group-hover:scale-110 transition-transform">{{ $hadirHariIni }}</p>
                            </div>
                            <div class="text-8xl opacity-15 group-hover:scale-110 transition-transform">✅</div>
                        </div>
                        <div class="mt-4 h-1 bg-white opacity-30 rounded-full"></div>
                        <p class="text-green-100 text-xs mt-3">Pegawai aktif hari ini</p>
                    </div>
                </div>

                <!-- NOTIFIKASI: Absensi Pending (RED ALERT) -->
                @if(isset($pendingCount) && $pendingCount > 0)
                    <div class="mb-8 animate-fade-in-up">
                        <div class="relative overflow-hidden bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl shadow-2xl p-8 border border-red-400">
                            <!-- Animated Background -->
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
                                        <h3 class="text-white font-bold text-xl">
                                            {{ $pendingCount }} Absensi Menunggu Persetujuan
                                        </h3>
                                        <p class="text-red-100 text-sm mt-1">
                                            📌 Segera review data absensi yang baru masuk dari pegawai untuk memastikan keakuratan data
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('attendances.admin-pending') }}" 
                                   class="ml-4 flex-shrink-0 px-8 py-3 bg-white text-red-600 font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-1 btn-animate">
                                    Lihat Sekarang ⚡
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- UNTUK USER: Tombol Absensi Utama -->
                <div class="animate-fade-in-up mb-8">
                    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-2xl shadow-2xl p-8 border border-indigo-500">
                        <!-- Animated Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute -top-40 -left-40 w-80 h-80 bg-white rounded-full"></div>
                            <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-white rounded-full"></div>
                        </div>
                        
                        <div class="relative flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-white text-3xl font-bold">Mulai Absensi Hari Ini</h3>
                                <p class="text-indigo-100 mt-2 text-lg">
                                    ✓ Pastikan GPS aktif  
                                    <span class="mx-2">•</span> 
                                    ✓ Kamera siap digunakan
                                </p>
                            </div>
                            <div class="ml-8 text-8xl opacity-20">📍</div>
                        </div>

                        <div class="mt-6 flex items-center">
                            <a href="{{ route('attendances.index') }}" 
                               class="px-10 py-4 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-1 btn-animate text-lg">
                                Buka Form Absensi
                            </a>
                            <a href="{{ route('attendances.history') }}" 
                               class="ml-4 px-10 py-4 bg-indigo-500 hover:bg-indigo-400 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-1 btn-animate text-lg">
                                Lihat Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- RIWAYAT ABSENSI BULAN INI -->
            <div class="animate-fade-in-up bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-3xl mr-3">📋</span>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Riwayat Absensi Bulan Ini</h3>
                        </div>
                        @if($riwayatBulanIni->count() > 0)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 badge-pulse">
                                {{ $riwayatBulanIni->count() }} data
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($riwayatBulanIni->count() > 0)
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs font-semibold text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300 sticky top-0">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">Tanggal</th>
                                        <th scope="col" class="px-6 py-4">Jam Masuk</th>
                                        <th scope="col" class="px-6 py-4">Status</th>
                                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatBulanIni as $index => $item)
                                    <tr class="table-row-animate bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all cursor-pointer">
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                {{ \Carbon\Carbon::parse($item->date)->format('l') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                🕐 {{ $item->time_in }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->status == 'hadir')
                                                <x-attendance-status-badge status="hadir" />
                                            @elseif($item->status == 'pending')
                                                <x-attendance-status-badge status="pending" />
                                            @elseif($item->status == 'ditolak')
                                                <x-attendance-status-badge status="ditolak" />
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('attendances.history') }}" 
                                               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition-all transform hover:scale-105">
                                                Lihat Detail →
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="text-6xl mb-4">📭</div>
                            <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">Belum ada data absensi bulan ini</p>
                            <p class="text-gray-500 dark:text-gray-400">Mulai absensi Anda sekarang untuk membuat catatan kehadiran</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
