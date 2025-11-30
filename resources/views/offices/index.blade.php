<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kantor Polisi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-4">
                        <a href="{{ route('offices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            2+ Tambah Kantor
                        </a>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Nama Kantor</th>
                <th scope="col" class="px-6 py-3">Alamat</th>
                <th scope="col" class="px-6 py-3">Koordinat (Lat, Long)</th>
                <th scope="col" class="px-6 py-3">Radius</th>
                <th scope="col" class="px-6 py-3">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($offices as $office)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $office->name }}
                </td>

                <td class="px-6 py-4">
                    {{ $office->address }}
                </td>

                <td class="px-6 py-4">
                    {{ $office->latitude }}, {{ $office->longitude }}
                </td>

                <td class="px-6 py-4">
                    {{ $office->radius }} Meter
                </td>

                <td class="px-6 py-4 flex space-x-2">
                    <a href="{{ route('offices.edit', $office->id) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-sm transition">
                        Edit
                    </a>

                    <form action="{{ route('offices.destroy', $office->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kantor ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm transition">
                            Hapus
                        </button>
                    </form>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                    Belum ada data kantor. Silakan tambah baru.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>