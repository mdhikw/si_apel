<!-- 
    Component: Toast Notification
    Path: resources/views/components/toast-notification.blade.php
    Fungsi: Tampilkan notifikasi toast untuk success/error/info dengan animasi modern
-->

@if($message = Session::get('success'))
<div class="fixed top-6 right-6 z-50" id="toast-success">
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-2xl p-6 animate-slide-in-right max-w-md border border-green-400">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-400 animate-bounce">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="font-bold text-lg">Berhasil!</p>
                <p class="text-green-100 text-sm mt-1">{{ $message }}</p>
            </div>
            <button onclick="closeToast('toast-success')" class="ml-4 flex-shrink-0 text-green-200 hover:text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="absolute bottom-0 left-0 h-1 bg-green-400 rounded-full animate-pulse" style="animation: slideProgress 5s linear forwards;"></div>
    </div>
</div>

<script>
    function closeToast(id) {
        const toast = document.getElementById(id);
        if(toast) {
            toast.style.animation = 'slideOutRight 0.4s ease-in forwards';
            setTimeout(() => toast.remove(), 400);
        }
    }
    
    // Auto hide after 5 seconds
    setTimeout(() => closeToast('toast-success'), 5000);
</script>
@endif

@if($message = Session::get('error'))
<div class="fixed top-6 right-6 z-50" id="toast-error">
    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl shadow-2xl p-6 animate-slide-in-right max-w-md border border-red-400">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-red-400">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="font-bold text-lg">Error!</p>
                <p class="text-red-100 text-sm mt-1">{{ $message }}</p>
            </div>
            <button onclick="closeToast('toast-error')" class="ml-4 flex-shrink-0 text-red-200 hover:text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="absolute bottom-0 left-0 h-1 bg-red-400 rounded-full animate-pulse" style="animation: slideProgress 7s linear forwards;"></div>
    </div>
</div>

<script>
    setTimeout(() => closeToast('toast-error'), 7000);
</script>
@endif

@if($message = Session::get('info'))
<div class="fixed top-6 right-6 z-50" id="toast-info">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-2xl p-6 animate-slide-in-right max-w-md border border-blue-400">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-400">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="font-bold text-lg">Info</p>
                <p class="text-blue-100 text-sm mt-1">{{ $message }}</p>
            </div>
            <button onclick="closeToast('toast-info')" class="ml-4 flex-shrink-0 text-blue-200 hover:text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="absolute bottom-0 left-0 h-1 bg-blue-400 rounded-full animate-pulse" style="animation: slideProgress 6s linear forwards;"></div>
    </div>
</div>

<script>
    setTimeout(() => closeToast('toast-info'), 6000);
</script>
@endif

<style>
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    @keyframes slideProgress {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
</style>
