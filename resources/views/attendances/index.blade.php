<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between animate-fade-in-down">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-gray-100 leading-tight">
                    {{ __('Pilih Lokasi Absensi') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">✓ Pastikan GPS dan Kamera aktif</p>
            </div>
            <div class="text-5xl opacity-20">📍</div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 animate-fade-in-up">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-xl p-8 text-white border border-blue-400">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-2">Siap untuk Absensi?</h3>
                            <p class="text-blue-100">Pilih lokasi kantor Anda di bawah ini untuk melanjutkan proses absensi</p>
                        </div>
                        <div class="text-6xl opacity-20">🏢</div>
                    </div>
                </div>
            </div>

            <div class="animate-fade-in-up">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    @foreach($offices as $index => $office)
                    <div class="card-modern group overflow-hidden rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <!-- Gradient Background -->
                        <div class="h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20 group-hover:opacity-30 transition-opacity">
                                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white rounded-full opacity-10 group-hover:scale-150 transition-transform"></div>
                            </div>
                            <div class="absolute bottom-4 right-4 text-5xl">🏢</div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-bold text-xl text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $office->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kantor Dinas</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 badge-pulse">
                                    ✓ Aktif
                                </span>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $office->address }}
                                </p>

                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Radius: <span class="font-bold ml-1 text-purple-600 dark:text-purple-400">{{ $office->radius }}m</span>
                                </div>
                            </div>

                            <a href="{{ route('attendances.create', $office->id) }}" 
                               class="block w-full text-center bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105 hover:-translate-y-1 btn-animate">
                                📸 Mulai Absensi
                            </a>
                        </div>
                    </div>
                    @endforeach

                </div>

                @if($offices->isEmpty())
                    <div class="text-center py-16">
                        <div class="text-6xl mb-4">📭</div>
                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">Belum ada lokasi kantor</p>
                        <p class="text-gray-500 dark:text-gray-400">Silakan hubungi Admin untuk menambahkan lokasi kantor</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>