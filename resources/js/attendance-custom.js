/* Lokasi File: public/js/attendance-custom.js 
   Fungsi: Mengatur Webcam dan Peta Leaflet
*/

// 1. KONFIGURASI KAMERA
Webcam.set({
    width: 320,
    height: 240,
    image_format: 'jpeg',
    jpeg_quality: 90
});
Webcam.attach('#my_camera');

function take_snapshot() {
    Webcam.snap(function(data_uri) {
        // Tampilkan hasil foto
        document.getElementById('results').innerHTML = '<img src="'+data_uri+'" class="rounded-lg shadow-md mx-auto"/>';
        document.getElementById('results').classList.remove('hidden');
        
        // Simpan data base64 ke input hidden
        document.getElementById('photo_data').value = data_uri;
        
        // Cek validasi tombol
        checkValidation();
    });
}

// 2. KONFIGURASI PETA (LEAFLET)
// Catatan: Variabel officeLat, officeLng, officeRadius diambil dari file Blade (Global Variable)

var map = L.map('map').setView([officeLat, officeLng], 15);

// Tampilkan Peta OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Lingkaran Radius Kantor (Zona Hijau)
var circle = L.circle([officeLat, officeLng], {
    color: 'green',
    fillColor: '#0f3',
    fillOpacity: 0.2,
    radius: officeRadius
}).addTo(map);

// Marker Kantor
L.marker([officeLat, officeLng]).addTo(map)
    .bindPopup("<b>Lokasi Kantor</b><br>Radius: " + officeRadius + "m").openPopup();

// 3. DETEKSI LOKASI USER (GEOLOCATION)
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
} else {
    alert("Geolocation tidak didukung oleh browser ini.");
}

function showPosition(position) {
    var userLat = position.coords.latitude;
    var userLng = position.coords.longitude;

    // Masukkan ke input hidden
    document.getElementById('latitude').value = userLat;
    document.getElementById('longitude').value = userLng;

    // Tampilkan di teks
    document.getElementById('lat_display').innerHTML = userLat;
    document.getElementById('long_display').innerHTML = userLng;

    // Marker Posisi User (Warna Merah)
    var userIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    L.marker([userLat, userLng], {icon: userIcon}).addTo(map)
        .bindPopup("<b>Lokasi Anda</b>").openPopup();

    // Hitung Jarak (Haversine Formula) - Sederhana
    var distance = map.distance([userLat, userLng], [officeLat, officeLng]);
    document.getElementById('distance_display').innerHTML = distance.toFixed(0) + " Meter";

    // Validasi Jarak
    if (distance <= officeRadius) {
        document.getElementById('distance_display').classList.add('text-green-600');
        document.getElementById('distance_display').innerHTML += " (Di Dalam Jangkauan)";
    } else {
        document.getElementById('distance_display').classList.add('text-red-600');
        document.getElementById('distance_display').innerHTML += " (Di Luar Jangkauan!)";
    }

    checkValidation();
}

function showError(error) {
    alert("Gagal mengambil lokasi: " + error.message);
}

// 4. CEK VALIDASI TOMBOL KIRIM
function checkValidation() {
    var photo = document.getElementById('photo_data').value;
    var lat = document.getElementById('latitude').value;

    if(photo && lat) {
        var btn = document.getElementById('btn-submit');
        btn.disabled = false;
        btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        btn.classList.add('bg-green-600', 'hover:bg-green-700');
        btn.innerHTML = "🚀 Kirim Absensi";
    }
}