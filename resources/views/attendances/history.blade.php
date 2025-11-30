<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between animate-fade-in-down">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-gray-100 leading-tight">
                    {{ __('Riwayat Absensi') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Lihat detail dan status semua absensi Anda</p>
            </div>
            <div class="text-5xl opacity-20">📜</div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="animate-fade-in-up bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700">

                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-3xl mr-3">📋</span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Data Riwayat Absensi</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Swipe untuk melihat lebih lengkap</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-x-auto shadow-inner">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs font-semibold text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300 sticky top-0">
                            <tr>
                                <th scope="col" class="px-6 py-4">Tanggal</th>
                                <th scope="col" class="px-6 py-4">Pegawai</th>
                                <th scope="col" class="px-6 py-4">Jam Masuk</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Bukti</th>
                                
                                <!-- Kolom Aksi Khusus Admin -->
                                @if(Auth::user()->role == 'admin')
                                    <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $index => $data)
                            <tr class="table-row-animate bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($data->date)->format('l') }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm mr-3">
                                            {{ substr($data->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $data->user->name ?? 'User Terhapus' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        🕐 {{ $data->time_in }}
                                    </span>
                                </td>

                                <!-- STATUS WARNA-WARNI -->
                                <td class="px-6 py-4">
                                    <x-attendance-status-badge :status="$data->status" />
                                </td>

                                <td class="px-6 py-4">
                                    @if($data->photo_in)
                                        <a href="{{ asset('storage/attendances/'.$data->photo_in) }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:shadow-lg transition-all transform hover:scale-105">
                                            📸 Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- TOMBOL AKSI ADMIN -->
                                @if(Auth::user()->role == 'admin')
                                    <td class="px-6 py-4">
                                        @if($data->status == 'pending')
                                            <div class="flex gap-2 justify-center flex-wrap">
                                                <!-- Tombol Approve -->
                                                <form action="{{ route('attendances.approve', $data->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui absensi ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-lg text-sm shadow-lg hover:shadow-xl transition-all transform hover:scale-105 btn-animate">
                                                        ✅ Setujui
                                                    </button>
                                                </form>

                                                <!-- Tombol Reject -->
                                                <form action="{{ route('attendances.reject', $data->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menolak absensi ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-lg text-sm shadow-lg hover:shadow-xl transition-all transform hover:scale-105 btn-animate">
                                                        ❌ Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                ✓ Selesai
                                            </span>
                                        @endif
                                    </td>
                                @endif

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="text-6xl mb-4">📭</div>
                                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">Belum ada data absensi</p>
                                        <p class="text-gray-500 dark:text-gray-400">Mulai absensi Anda sekarang untuk membuat catatan kehadiran</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>