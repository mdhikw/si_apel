<x-app-layout>
    <x-slot name="style">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #map { height: 400px; border-radius: 0.5rem; }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Kantor: {{ $office->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('offices.update', $office->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nama Kantor</label>
                                <input type="text" name="name" value="{{ $office->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Alamat Lengkap</label>
                                <textarea name="address" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $office->address }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Radius Absensi (Meter)</label>
                                <input type="number" name="radius" value="{{ $office->radius }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div>
                                    <label class="block text-xs font-bold mb-1">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" value="{{ $office->latitude }}" class="bg-gray-100 border rounded w-full py-2 px-3 text-xs" readonly required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold mb-1">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" value="{{ $office->longitude }}" class="bg-gray-100 border rounded w-full py-2 px-3 text-xs" readonly required>
                                </div>
                            </div>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                                Update Perubahan
                            </button>
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Geser Pin untuk Ubah Lokasi</label>
                            <div id="map"></div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            // Ambil data lama dari database untuk set posisi awal peta
            var curLat = Number("{{ $office->latitude }}");
            var curLng = Number("{{ $office->longitude }}");

            var map = L.map('map').setView([curLat, curLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([curLat, curLng], {
                draggable: true
            }).addTo(map);

            // Fungsi update input saat marker digeser
            function updateInput(lat, lng) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }

            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateInput(position.lat, position.lng);
            });

            map.on('click', function(e) {
                var position = e.latlng;
                marker.setLatLng(position);
                updateInput(position.lat, position.lng);
            });
        </script>
    </x-slot>
</x-app-layout>