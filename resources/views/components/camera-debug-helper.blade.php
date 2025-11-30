<!-- 
    File: resources/views/components/camera-debug-helper.blade.php
    Komponen untuk debugging masalah camera/geolocation di halaman absensi
-->
<div class="mt-6 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-mono">
    <h4 class="font-bold mb-2">🔧 DEBUG INFO</h4>
    
    <div id="debug-info" class="space-y-1 text-gray-700 dark:text-gray-300">
        <div>Webcam Library: <span id="debug-webcam">Loading...</span></div>
        <div>Leaflet Library: <span id="debug-leaflet">Loading...</span></div>
        <div>Geolocation: <span id="debug-geolocation">Checking...</span></div>
        <div>take_snapshot: <span id="debug-function">Loading...</span></div>
    </div>

    <button type="button" onclick="window.testDebug && window.testDebug()" 
            class="mt-3 px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
        🧪 Test All
    </button>
</div>

<script>
    // Wait for libraries to load
    setTimeout(function() {
        document.getElementById('debug-webcam').innerHTML = 
            (typeof Webcam !== 'undefined') ? '✅ OK' : '❌ NOT LOADED';
        
        document.getElementById('debug-leaflet').innerHTML = 
            (typeof L !== 'undefined') ? '✅ OK' : '❌ NOT LOADED';
        
        document.getElementById('debug-geolocation').innerHTML = 
            navigator.geolocation ? '✅ OK' : '❌ NOT AVAILABLE';
        
        document.getElementById('debug-function').innerHTML = 
            (typeof window.take_snapshot === 'function') ? '✅ OK' : '❌ NOT FOUND';
    }, 500);

    // Test function
    window.testDebug = function() {
        console.clear();
        console.log("=== DEBUG TEST ===");
        console.log("Webcam:", typeof Webcam);
        console.log("Leaflet:", typeof L);
        console.log("take_snapshot:", typeof window.take_snapshot);
        console.log("Geolocation:", !!navigator.geolocation);
        alert("Check console (F12) for details");
    };
</script>
