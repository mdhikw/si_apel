<!-- Debugging helper untuk memverifikasi script sudah loaded -->
<script>
    console.log("✅ Script loaded successfully");
    console.log("Available functions:", {
        take_snapshot: typeof window.take_snapshot,
        validateForm: typeof validateForm,
    });
    
    // Verify webcam library
    console.log("Webcam Library:", typeof Webcam !== 'undefined' ? '✅ Loaded' : '❌ Not loaded');
    console.log("Leaflet Library:", typeof L !== 'undefined' ? '✅ Loaded' : '❌ Not loaded');
</script>
