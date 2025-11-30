<x-app-layout>
    <x-slot name="style">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            #map { height: 400px; width: 100%; border-radius: 1.5rem; z-index: 0; }
            #my_camera { width: 100% !important; height: auto !important; min-height: 280px; border-radius: 1.5rem; object-fit: cover; }
            #my_camera video { width: 100% !important; height: auto !important; border-radius: 1.5rem; }
        </style>
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between animate-fade-in-down">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-gray-100 leading-tight">
                    Mulai Absensi
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">📍 {{ $office->name ?? 'Lokasi Umum' }}</p>
            </div>
            <div class="text-5xl opacity-20">📸</div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- PROGRESS INDICATOR -->
            <div class="mb-8 animate-fade-in-up">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="mx-auto h-12 w-12 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg mb-2">
                            1
                        </div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Ambil Foto</p>
                    </div>
                    <div class="text-center">
                        <div class="mx-auto h-12 w-12 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg mb-2">
                            2
                        </div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Lokasi GPS</p>
                    </div>
                    <div class="text-center">
                        <div class="mx-auto h-12 w-12 rounded-full bg-gradient-to-r from-pink-500 to-pink-600 flex items-center justify-center text-white font-bold text-lg mb-2">
                            3
                        </div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Kirim</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('attendances.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in-up">
                @csrf
                <!-- Input tersembunyi untuk foto, latitude, longitude -->
                <input type="hidden" name="photo" id="photo_data">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                
                <!-- KARTU KAMERA -->
                <div class="card-modern bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">📷 Ambil Foto Selfie</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300">
                            Step 1
                        </span>
                    </div>
                    
                    <div id="my_camera" class="bg-gray-200 dark:bg-gray-700 flex items-center justify-center mb-6 rounded-xl overflow-hidden shadow-inner border-2 border-gray-300 dark:border-gray-600">
                        <div class="text-center p-8">
                            <div class="text-4xl mb-2 animate-bounce">📷</div>
                            <span class="text-gray-500 dark:text-gray-400">Menghubungkan Kamera...</span>
                        </div>
                    </div>

                    <button type="button" onClick="take_snapshot()" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3 px-6 rounded-xl mb-4 transition-all transform hover:scale-105 hover:-translate-y-1 btn-animate shadow-lg">
                        📸 Jepret Foto
                    </button>
                    
                    <div id="results" class="hidden mt-4 text-center rounded-xl overflow-hidden shadow-lg border-2 border-indigo-500 animate-scale-in"></div>
                </div>

                <!-- KARTU LOKASI -->
                <div class="card-modern bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">📍 Lokasi Anda</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300">
                            Step 2
                        </span>
                    </div>
                    
                    <div id="map" class="mb-6 shadow-lg rounded-xl border border-gray-300 dark:border-gray-600 overflow-hidden"></div>
                    
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900 dark:to-indigo-900 p-6 rounded-xl border border-purple-200 dark:border-purple-700 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                <span class="inline-block w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                Latitude
                            </span>
                            <span id="lat_display" class="font-mono font-bold text-lg text-purple-600 dark:text-purple-300">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full mr-2"></span>
                                Longitude
                            </span>
                            <span id="long_display" class="font-mono font-bold text-lg text-indigo-600 dark:text-indigo-300">-</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-purple-200 dark:border-purple-700 pt-3">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                <span class="inline-block text-lg mr-2">📏</span>
                                Jarak ke Kantor
                            </span>
                            <span id="distance_display" class="font-bold text-lg text-green-600 dark:text-green-300 badge-pulse">Menghitung...</span>
                        </div>
                    </div>

                    <!-- INFO HELPER: Cara Mengaktifkan Geolocation -->
                    <div id="geolocation-help" class="hidden mt-4 p-4 bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 rounded">
                        <p class="text-yellow-800 dark:text-yellow-200 text-xs font-semibold mb-2">⚠️ Geolocation Ditolak (Kode: 1)</p>
                        <p class="text-yellow-700 dark:text-yellow-300 text-xs mb-3">
                            Browser masih mengingat bahwa Anda pernah menolak akses lokasi. Silakan reset:
                        </p>
                        
                        <!-- Panduan per browser -->
                        <div class="mb-3 p-3 bg-yellow-100 dark:bg-yellow-800 rounded text-xs text-yellow-900 dark:text-yellow-100">
                            <p class="font-semibold mb-2">📱 Pilih Browser Anda:</p>
                            
                            <!-- Chrome -->
                            <details class="mb-2">
                                <summary class="cursor-pointer font-semibold text-yellow-800 dark:text-yellow-200">🔵 Chrome / Edge</summary>
                                <ol class="list-decimal list-inside ml-2 mt-2 space-y-1 text-yellow-800 dark:text-yellow-200">
                                    <li>Klik 🔒 lock icon di URL bar</li>
                                    <li>Klik "Site settings"</li>
                                    <li>Cari "Location" → **Reset**</li>
                                    <li>Atau ganti ke "Allow"</li>
                                    <li>Refresh halaman & klik retry</li>
                                </ol>
                            </details>

                            <!-- Firefox -->
                            <details class="mb-2">
                                <summary class="cursor-pointer font-semibold text-yellow-800 dark:text-yellow-200">🦊 Firefox</summary>
                                <ol class="list-decimal list-inside ml-2 mt-2 space-y-1 text-yellow-800 dark:text-yellow-200">
                                    <li>Klik 🛡️ shield icon</li>
                                    <li>Klik "Permissions"</li>
                                    <li>Cari "Location" → delete/remove</li>
                                    <li>Refresh & coba lagi</li>
                                </ol>
                            </details>

                            <!-- Safari -->
                            <details class="mb-2">
                                <summary class="cursor-pointer font-semibold text-yellow-800 dark:text-yellow-200">🧭 Safari</summary>
                                <ol class="list-decimal list-inside ml-2 mt-2 space-y-1 text-yellow-800 dark:text-yellow-200">
                                    <li>Menu → Preferences (⌘,)</li>
                                    <li>Pilih "Websites"</li>
                                    <li>Klik "Location"</li>
                                    <li>Cari domain → Set ke "Allow"</li>
                                    <li>Refresh halaman</li>
                                </ol>
                            </details>

                            <!-- Mobile -->
                            <details class="mb-2">
                                <summary class="cursor-pointer font-semibold text-yellow-800 dark:text-yellow-200">📱 Mobile (Android/iOS)</summary>
                                <ol class="list-decimal list-inside ml-2 mt-2 space-y-1 text-yellow-800 dark:text-yellow-200">
                                    <li><strong>Android:</strong> Settings → Apps → Browser → Permissions → Location (ON)</li>
                                    <li><strong>iPhone:</strong> Settings → Privacy → Location Services (ON) → Safari (Allow While Using)</li>
                                    <li>Buka browser & refresh halaman</li>
                                </ol>
                            </details>
                        </div>

                        <!-- Tombol Action -->
                        <div class="flex gap-2 mt-3">
                            <button type="button" onclick="retryGeolocation()" 
                                    class="flex-1 px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded">
                                🔄 Coba Lagi
                            </button>
                            <button type="button" onclick="toggleManualLocation()" 
                                    class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded">
                                📍 Input Manual
                            </button>
                        </div>
                    </div>

                    <!-- FALLBACK: Manual Location Input (Optional) -->
                    <div id="manual-location" class="hidden mt-4 p-4 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 rounded">
                        <p class="text-blue-800 dark:text-blue-200 text-xs font-semibold mb-2">💡 Input Manual Lokasi</p>
                        <p class="text-blue-700 dark:text-blue-300 text-xs mb-3">
                            Atau masukkan koordinat secara manual jika geolocation tidak bisa:
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-blue-700 dark:text-blue-300">Latitude</label>
                                <input type="number" id="manual_lat" placeholder="cth: -6.2088" step="0.00001" 
                                       class="w-full px-2 py-1 border rounded text-xs dark:bg-gray-700 dark:text-white"
                                       onchange="updateManualLocation()">
                            </div>
                            <div>
                                <label class="text-xs text-blue-700 dark:text-blue-300">Longitude</label>
                                <input type="number" id="manual_lng" placeholder="cth: 106.8456" step="0.00001" 
                                       class="w-full px-2 py-1 border rounded text-xs dark:bg-gray-700 dark:text-white"
                                       onchange="updateManualLocation()">
                            </div>
                        </div>
                        <p class="text-blue-600 dark:text-blue-300 text-xs mt-2">
                            💡 <strong>Cara:</strong> Buka Google Maps → Klik lokasi → Copy koordinat (lat,lng)
                        </p>
                        <button type="button" onclick="toggleManualLocation()" 
                                class="mt-2 w-full px-3 py-1 bg-gray-400 hover:bg-gray-500 text-white text-xs font-bold rounded">
                            Gunakan GPS Otomatis
                        </button>
                    </div>

                    <!-- SUBMIT BUTTON - BAGIAN FORM UTAMA -->
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <a href="{{ route('attendances.index') }}" class="flex items-center justify-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 font-bold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all transform hover:scale-105">
                            ← Kembali
                        </a>
                        <button type="submit" id="btn-submit" disabled class="w-full bg-gray-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed transition-all transform hover:scale-105 btn-animate shadow-lg disabled:shadow-none">
                            ⏳ Menunggu Lokasi & Foto...
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="script">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <script>
            // --- JURUS ANTI ERROR: PENGAMAN VARIABEL ---
            // Kita pakai Number() dan tanda kutip "" agar kalau kosong dia jadi 0 (tidak error syntax)
            var officeLat = Number("{{ $office->latitude ?? -6.2088 }}");
            var officeLng = Number("{{ $office->longitude ?? 106.8456 }}");
            var officeRadius = Number("{{ $office->radius ?? 200 }}");

            console.log("Target Kantor:", officeLat, officeLng, officeRadius);

            // --- 1. SETUP KAMERA ---
            try {
                Webcam.set({
                    width: 320,
                    height: 240,
                    image_format: 'jpeg',
                    jpeg_quality: 90
                });
                Webcam.attach('#my_camera');
            } catch (err) {
                console.error("Kamera Error:", err);
            }

            // --- 2. SETUP FUNGSI JEPRET (Di definisikan Global) ---
            // Kita buat fungsi ini nempel ke window agar PASTI terbaca oleh tombol
            function take_snapshot() {
                try {
                    Webcam.snap(function(data_uri) {
                        document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
                        document.getElementById('results').classList.remove('hidden');
                        document.getElementById('photo_data').value = data_uri;
                        validateForm(); // Cek tombol
                    });
                } catch (err) {
                    alert("Kamera belum siap! Coba refresh halaman.");
                }
            }

            // Set fungsi ke window agar accessible dari onClick
            window.take_snapshot = take_snapshot;

            // --- 3. SETUP PETA ---
            var map = L.map('map').setView([officeLat, officeLng], 15);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
            
            // Lingkaran Hijau
            L.circle([officeLat, officeLng], { 
                color: 'green', 
                fillColor: '#22c55e', 
                fillOpacity: 0.2, 
                radius: officeRadius 
            }).addTo(map);

            // --- 4. GPS USER ---
            if (navigator.geolocation) {
                // Request geolocation dengan timeout
                navigator.geolocation.watchPosition(
                    // Success callback
                    function(pos) {
                        var lat = pos.coords.latitude;
                        var lng = pos.coords.longitude;
                        
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        document.getElementById('lat_display').innerText = lat.toFixed(5);
                        document.getElementById('long_display').innerText = lng.toFixed(5);

                        // Marker User
                        L.marker([lat, lng]).addTo(map).bindPopup("Posisi Anda").openPopup();

                        // Hitung Jarak
                        var dist = map.distance([lat, lng], [officeLat, officeLng]);
                        var el = document.getElementById('distance_display');
                        
                        if (dist <= officeRadius) {
                            el.innerHTML = dist.toFixed(0) + "m <span class='text-green-600'>✅ Masuk</span>";
                        } else {
                            el.innerHTML = dist.toFixed(0) + "m <span class='text-red-600'>❌ Jauh</span>";
                        }
                        validateForm();
                    },
                    // Error callback - Handle berbagai error
                    function(error) {
                        var errorMsg = "";
                        var errorEl = document.getElementById('distance_display');
                        var helpEl = document.getElementById('geolocation-help');
                        var manualEl = document.getElementById('manual-location');
                        
                        console.error("🔴 Geolocation Error Code:", error.code);
                        console.error("🔴 Error Message:", error.message);
                        console.error("🔴 Full Error Object:", error);
                        
                        switch(error.code) {
                            case 1: // PERMISSION_DENIED
                                errorMsg = "❌ User denied Geolocation (Kode: 1)";
                                helpEl.classList.remove('hidden');
                                manualEl.classList.add('hidden');
                                console.warn("💡 Solusi: Reset permission di browser settings");
                                break;
                            case error.PERMISSION_DENIED:
                                errorMsg = "❌ Lokasi ditolak - Aktifkan di Settings";
                                helpEl.classList.remove('hidden');
                                console.warn("Permission denied");
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg = "❌ Lokasi tidak tersedia (Kode: 2)";
                                manualEl.classList.remove('hidden');
                                console.warn("Position unavailable - gunakan manual input");
                                break;
                            case error.TIMEOUT:
                                errorMsg = "⏱️ Timeout - Coba lagi (Kode: 3)";
                                console.warn("Geolocation timeout");
                                break;
                            default:
                                errorMsg = "❌ Error: " + error.message + " (Kode: " + error.code + ")";
                        }
                        
                        errorEl.innerHTML = errorMsg + ' <button onclick="retryGeolocation()" class="ml-2 text-xs underline hover:no-underline">🔄 Retry</button>';
                        console.error("Geolocation Error:", error);
                    },
                    // Options
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,  // 10 detik timeout
                        maximumAge: 0    // Selalu ambil lokasi terbaru
                    }
                );
            } else {
                document.getElementById('distance_display').innerHTML = "❌ Browser tidak support Geolocation";
            }

            // --- 5. VALIDASI FORM ---
            function validateForm() {
                var photoData = document.getElementById('photo_data').value;
                var latitude = document.getElementById('latitude').value;
                
                if(photoData && latitude) {
                    var btn = document.getElementById('btn-submit');
                    btn.disabled = false;
                    btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    btn.classList.add('bg-green-600', 'hover:bg-green-700');
                    btn.innerText = "🚀 Kirim Absensi";
                } else {
                    var btn = document.getElementById('btn-submit');
                    btn.disabled = true;
                    btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    btn.classList.add('bg-gray-400', 'cursor-not-allowed');
                    btn.innerText = "⏳ Menunggu Lokasi & Foto...";
                }
            }

            // DEBUG: Verify functions loaded
            console.log("✅ Script loaded successfully");
            console.log("take_snapshot function:", typeof window.take_snapshot);

            // --- 6. RETRY GEOLOCATION FUNCTION ---
            function retryGeolocation() {
                // Clear previous data
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                document.getElementById('lat_display').innerText = '-';
                document.getElementById('long_display').innerText = '-';
                document.getElementById('distance_display').innerText = 'Menghitung...';
                document.getElementById('geolocation-help').classList.add('hidden');
                
                // Coba lagi
                if (navigator.geolocation) {
                    navigator.geolocation.watchPosition(
                        function(pos) {
                            var lat = pos.coords.latitude;
                            var lng = pos.coords.longitude;
                            
                            document.getElementById('latitude').value = lat;
                            document.getElementById('longitude').value = lng;
                            document.getElementById('lat_display').innerText = lat.toFixed(5);
                            document.getElementById('long_display').innerText = lng.toFixed(5);
                            document.getElementById('geolocation-help').classList.add('hidden');

                            // Marker User
                            L.marker([lat, lng]).addTo(map).bindPopup("Posisi Anda").openPopup();

                            // Hitung Jarak
                            var dist = map.distance([lat, lng], [officeLat, officeLng]);
                            var el = document.getElementById('distance_display');
                            
                            if (dist <= officeRadius) {
                                el.innerHTML = dist.toFixed(0) + "m <span class='text-green-600'>✅ Masuk</span>";
                            } else {
                                el.innerHTML = dist.toFixed(0) + "m <span class='text-red-600'>❌ Jauh</span>";
                            }
                            validateForm();
                        },
                        function(error) {
                            var errorMsg = "";
                            var errorEl = document.getElementById('distance_display');
                            var helpEl = document.getElementById('geolocation-help');
                            
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMsg = "❌ Permission masih ditolak - Reset di Settings";
                                    helpEl.classList.remove('hidden');
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMsg = "❌ Lokasi tidak tersedia";
                                    break;
                                case error.TIMEOUT:
                                    errorMsg = "⏱️ Timeout - Coba lagi";
                                    break;
                                default:
                                    errorMsg = "❌ Error: " + error.message;
                            }
                            
                            errorEl.innerHTML = errorMsg;
                            console.error("Geolocation Retry Error:", error);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                }
            }

            // Set ke window agar bisa dipanggil dari button onclick
            window.retryGeolocation = retryGeolocation;

            // --- 7. TOGGLE MANUAL LOCATION ---
            function toggleManualLocation() {
                var manualEl = document.getElementById('manual-location');
                var helpEl = document.getElementById('geolocation-help');
                
                if(manualEl.classList.contains('hidden')) {
                    // Show manual
                    manualEl.classList.remove('hidden');
                    helpEl.classList.add('hidden');
                } else {
                    // Hide manual
                    manualEl.classList.add('hidden');
                    helpEl.classList.remove('hidden');
                }
            }

            window.toggleManualLocation = toggleManualLocation;
            function updateManualLocation() {
                var manualLat = document.getElementById('manual_lat').value;
                var manualLng = document.getElementById('manual_lng').value;
                
                if(manualLat && manualLng) {
                    // Set ke form fields
                    document.getElementById('latitude').value = manualLat;
                    document.getElementById('longitude').value = manualLng;
                    document.getElementById('lat_display').innerText = parseFloat(manualLat).toFixed(5);
                    document.getElementById('long_display').innerText = parseFloat(manualLng).toFixed(5);
                    
                    // Hitung jarak
                    var dist = map.distance([manualLat, manualLng], [officeLat, officeLng]);
                    var el = document.getElementById('distance_display');
                    
                    if (dist <= officeRadius) {
                        el.innerHTML = dist.toFixed(0) + "m <span class='text-green-600'>✅ Masuk</span>";
                    } else {
                        el.innerHTML = dist.toFixed(0) + "m <span class='text-red-600'>❌ Jauh</span>";
                    }
                    
                    // Update marker di peta
                    map.setView([manualLat, manualLng], 15);
                    L.marker([manualLat, manualLng]).bindPopup("Manual Location").openPopup().addTo(map);
                    
                    validateForm();
                }
            }

            window.updateManualLocation = updateManualLocation;
        </script>
    </x-slot>
</x-app-layout>