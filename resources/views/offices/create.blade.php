<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kantor Baru') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        #map { 
            height: 400px; 
            width: 100%; 
            border: 4px solid #3b82f6; 
            border-radius: 8px;
            z-index: 1;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('offices.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            
                            <div>
                                <label class="block text-sm font-bold mb-1">Nama Kantor</label>
                                <input type="text" name="name" class="w-full border rounded p-2" placeholder="Contoh: Mapolres Pusat" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold mb-1">Alamat Lengkap</label>
                                <textarea name="address" class="w-full border rounded p-2" rows="3" placeholder="Jl. Jenderal Sudirman No. 1..." required></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold mb-1">Radius (Meter)</label>
                                <input type="number" name="radius" value="200" class="w-full border rounded p-2">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-xs font-bold">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="w-full bg-gray-100 border p-2 text-xs font-mono" readonly required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="w-full bg-gray-100 border p-2 text-xs font-mono" readonly required>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded transition">
                                Simpan Kantor
                            </button>
                        </div>

                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <label class="block text-sm font-bold">Geser Pin Biru 👇</label>
                                <span class="text-xs text-gray-500">Mode: OpenStreetMap</span>
                            </div>
                            
                            <div id="map"></div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof L === 'undefined') {
                alert("Gagal memuat script Leaflet. Cek koneksi internet!");
                return;
            }

            // Default Jakarta
            var map = L.map('map').setView([-6.2088, 106.8456], 15); 

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([-6.2088, 106.8456], { 
                draggable: true 
            }).addTo(map);

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

            updateInput(-6.2088, 106.8456);
            setTimeout(function(){ map.invalidateSize(); }, 500);
        });
    </script>
</x-app-layout>