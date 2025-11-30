<!-- 
    Component: Clear Geolocation Cache Helper
    Path: resources/views/components/geolocation-reset-helper.blade.php
    Fungsi: Bantu user reset geolocation permission yang tersimpan
-->

<div class="mt-6 p-4 bg-purple-50 dark:bg-purple-900 border-l-4 border-purple-400 rounded">
    <p class="text-purple-800 dark:text-purple-200 text-xs font-semibold mb-3">
        🔧 Geolocation Cache Reset Helper
    </p>
    
    <div class="space-y-3">
        <!-- Option 1: Clear Browser Cache -->
        <div>
            <p class="text-purple-700 dark:text-purple-300 text-xs font-semibold mb-1">
                Option 1: Clear Browser Cache & Storage
            </p>
            <p class="text-purple-600 dark:text-purple-400 text-xs mb-2">
                Tekan keyboard shortcut untuk open DevTools:
            </p>
            <div class="space-y-1">
                <button type="button" onclick="clearBrowserCache()" 
                        class="w-full px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded">
                    🧹 Clear All Cache & Storage
                </button>
            </div>
        </div>

        <!-- Option 2: Incognito Mode -->
        <div>
            <p class="text-purple-700 dark:text-purple-300 text-xs font-semibold mb-1">
                Option 2: Gunakan Incognito/Private Mode
            </p>
            <ol class="text-purple-600 dark:text-purple-400 text-xs list-decimal list-inside space-y-1">
                <li><strong>Chrome:</strong> Ctrl+Shift+N (Windows) / Cmd+Shift+N (Mac)</li>
                <li><strong>Firefox:</strong> Ctrl+Shift+P (Windows) / Cmd+Shift+P (Mac)</li>
                <li><strong>Safari:</strong> Cmd+Shift+N</li>
                <li>Akses website di mode incognito</li>
                <li>Permission akan fresh/baru</li>
            </ol>
        </div>

        <!-- Option 3: Manual Reset per Browser -->
        <div>
            <p class="text-purple-700 dark:text-purple-300 text-xs font-semibold mb-1">
                Option 3: Manual Reset di Settings
            </p>
            <button type="button" onclick="showBrowserResetGuide()" 
                    class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded">
                📖 Lihat Panduan Reset per Browser
            </button>
        </div>

        <!-- Status Info -->
        <div class="pt-3 border-t border-purple-300 dark:border-purple-600">
            <p class="text-purple-600 dark:text-purple-400 text-xs">
                <strong>💡 Tip:</strong> Jika masih error setelah reset, coba gunakan manual input koordinat
            </p>
        </div>
    </div>
</div>

<script>
    function clearBrowserCache() {
        // Clear localStorage
        localStorage.clear();
        
        // Clear sessionStorage
        sessionStorage.clear();
        
        // Show message
        alert("✅ Cache & Storage cleared!\n\nSilakan refresh halaman (F5) dan coba lagi.");
        
        // Refresh
        setTimeout(function() {
            location.reload();
        }, 1000);
    }

    function showBrowserResetGuide() {
        var guide = `
📖 PANDUAN RESET GEOLOCATION PERMISSION:

🔵 CHROME / EDGE:
1. Klik 🔒 lock icon di URL bar
2. Klik "Site settings"
3. Cari "Location"
4. Pilih "Remove" atau "Reset"
5. Refresh halaman

🦊 FIREFOX:
1. Klik 🛡️ shield icon
2. Klik "Permissions" tab
3. Klik "X" di sebelah Location
4. Refresh halaman

🧭 SAFARI:
1. Preferences (⌘,)
2. Tab "Websites"
3. Pilih "Location" di sidebar
4. Cari domain ini → Set "Allow"
5. Refresh halaman

📱 MOBILE:
Android: Settings → Apps → Browser → Permissions → Location (ON)
iPhone: Settings → Privacy → Location Services → Safari (Allow)

===

Setelah reset, refresh halaman dan coba lagi!
        `;
        
        alert(guide);
    }

    window.clearBrowserCache = clearBrowserCache;
    window.showBrowserResetGuide = showBrowserResetGuide;
</script>
