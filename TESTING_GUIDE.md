# 🚀 Setup & Testing Guide - Sistem Absensi Polres Garut

## 🔧 Environment Setup

### Prerequisites
- PHP 8.1+
- Laravel 11
- MySQL 8.0+
- Node.js 16+ (untuk Tailwind CSS)

### Installation

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Database setup
php artisan migrate:fresh
php artisan db:seed

# 4. Storage link
php artisan storage:link

# 5. Build assets
npm run build

# 6. Start server
php artisan serve
```

---

## 🧪 Quick Testing

### Test Accounts
```
ADMIN Account:
├─ Email: admin@polres.com
├─ Password: 12345678
└─ Role: admin

USER Account:
├─ Email: budi@polres.com
├─ Password: 12345678
└─ Role: anggota
```

### Test Flow (5 minutes)

**Step 1: User Submit Absensi (2 min)**
```bash
1. Open http://localhost:8000
2. Login: budi@polres.com / 12345678
3. Dashboard → Tambah Absensi
4. Select kantor
5. Take photo (📸 Jepret Foto)
6. Wait for GPS location
7. Submit → See green toast notification ✅
```

**Step 2: Admin Review (2 min)**
```bash
1. Logout
2. Login: admin@polres.com / 12345678
3. See red alert on dashboard
4. Click "Lihat Sekarang ⚡"
5. Review pending attendance data
6. Click ✅ Approve or ❌ Reject
7. See green toast notification ✅
```

**Step 3: Verify Results (1 min)**
```bash
1. Check database: 
   Attendance::latest()->first()
   // Status should be 'hadir' or 'ditolak'

2. Check photo:
   storage/app/public/attendances/
   // Should contain attendance_{id}_{timestamp}.png
```

---

## 🔍 Database Verification

### Check Attendance Records
```bash
# Via Tinker
php artisan tinker

# Check users
User::all()
// Output: Collection of 2 users (admin + anggota)

# Check attendances
Attendance::all()
// Output: Collection of submitted records

# Check specific record
Attendance::with('user')->latest()->first()
// Output: Full attendance with user details

# Count by status
Attendance::where('status', 'pending')->count()
Attendance::where('status', 'hadir')->count()
Attendance::where('status', 'ditolak')->count()
```

### Check Storage Files
```bash
# List uploaded photos
dir storage\app\public\attendances\

# Expected format:
# attendance_1_1732879200.png
# attendance_2_1732879300.png
# attendance_1_1732879400.png
```

---

## 🛠️ Troubleshooting

### Issue 1: "Toast notification tidak muncul"
**Check:**
- ✅ Layout app.blade.php has `<x-toast-notification />`
- ✅ Session flash message dipassing dari controller
- ✅ Browser console no errors
- ✅ Cache cleared: `php artisan view:clear`

**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Issue 2: "Photo tidak tersimpan"
**Check:**
- ✅ Storage link exists: `public/storage` symlink
- ✅ Folder exists: `storage/app/public/attendances/`
- ✅ Permission: Windows full access
- ✅ Base64 photo string tidak kosong

**Solution:**
```bash
# Recreate storage link
php artisan storage:link
# OR on Windows
mklink /D "C:\xampp\htdocs\si-apel\public\storage" "C:\xampp\htdocs\si-apel\storage\app\public"
```

### Issue 3: "GPS tidak aktif"
**Check:**
- ✅ Browser permission: Location allowed
- ✅ HTTPS or localhost (geolocation requires HTTPS)
- ✅ Script loaded: Console shows "✅ Script loaded successfully"
- ✅ Function defined: `typeof window.take_snapshot` returns 'function'

**Solution:**
```javascript
// Check in browser console:
console.log(navigator.geolocation);
// Should return Geolocation object

// Check function:
console.log(window.take_snapshot);
// Should return: ƒ take_snapshot() { ... }
```

### Issue 4: "Admin pending page shows no data"
**Check:**
- ✅ Attendance status is 'pending' (not 'hadir' or 'ditolak')
- ✅ User is logged as admin
- ✅ Query working: `Attendance::where('status', 'pending')->count()`

**Solution:**
```bash
# Check database
php artisan tinker
Attendance::where('status', 'pending')->count()
// If 0, manually update:
Attendance::first()->update(['status' => 'pending'])
```

### Issue 5: "Form tidak bisa submit"
**Check:**
- ✅ Photo captured: `#photo_data` input has value
- ✅ GPS obtained: `#latitude` and `#longitude` have values
- ✅ Submit button enabled: Not gray/disabled
- ✅ Form valid: No validation errors

**Solution:**
```javascript
// Check in console:
document.getElementById('photo_data').value;     // Should have base64
document.getElementById('latitude').value;       // Should have number
document.getElementById('longitude').value;      // Should have number
document.getElementById('btn-submit').disabled;  // Should be false
```

### Issue 6: "Outside radius error when user is actually inside"
**Check:**
- ✅ Office coordinates correct
- ✅ Office radius correct (meters)
- ✅ GPS accuracy (use more decimal places)
- ✅ Distance calculation

**Solution:**
```bash
# Check office data
php artisan tinker
Office::all()
// Verify latitude, longitude, radius

# Test distance calculation manually
$dist = map.distance([userLat, userLng], [officeLat, officeLng])
// Should be < office radius
```

---

## 📊 Routes Verification

```bash
php artisan route:list | Select-String "attendances"

# Expected output:
  GET|HEAD        admin/pending-absensi
  GET|HEAD        attendances
  POST            attendances
  GET|HEAD        attendances/create/{office}
  PATCH           attendances/{attendance}/approve
  PATCH           attendances/{attendance}/reject
  GET|HEAD        riwayat-absensi
```

---

## 🔐 Authorization Check

### User Authorization
```bash
# User can only see own attendance
User::where('role', 'anggota')->first()->attendances;

# Admin can see all attendance
Attendance::all();  // Admin only
```

### Role-based Routes
```php
// These routes check role in controller:
AttendanceController@adminPending()     // role == 'admin'
AttendanceController@approve()          // role == 'admin'
AttendanceController@reject()           // role == 'admin'
```

---

## 📈 Performance Tips

### Cache Optimization
```bash
# Enable query caching for office list
php artisan optimize

# Monitor database queries
DB::enableQueryLog()
// Your code
dd(DB::getQueryLog())
```

### Photo Optimization
```php
// Current: attendance_user_id_timestamp.png
// Size: Usually 50-200KB (base64 jpeg)

// Monitor storage:
Storage::disk('public')->size('attendances/');
```

---

## 🚀 Production Checklist

- [ ] SSL/HTTPS configured (geolocation requires HTTPS)
- [ ] Database backups scheduled
- [ ] Storage cleanup policy (old photos)
- [ ] Email notifications setup (optional future feature)
- [ ] Logging configured
- [ ] Rate limiting enabled
- [ ] CORS properly configured
- [ ] File upload size limits set
- [ ] Timezone configured correctly
- [ ] Cache driver configured

---

## 📞 Support Commands

```bash
# View current status
php artisan status

# Check database connection
php artisan migrate:status

# Test email (future)
php artisan mail:send

# Clear everything
php artisan optimize:clear

# Health check
php artisan tinker
// DB::connection()->getPdo()
```

---

## 📝 File Structure Reference

```
app/
├── Http/
│   └── Controllers/
│       └── AttendanceController.php    ← Main logic
├── Models/
│   ├── Attendance.php                  ← Data model
│   └── User.php                        ← User model

resources/
├── views/
│   ├── attendances/
│   │   ├── create.blade.php            ← Form absensi (camera+gps)
│   │   ├── index.blade.php             ← Daftar kantor
│   │   ├── history.blade.php           ← Riwayat absensi
│   │   └── admin-pending.blade.php     ← Admin review page
│   ├── components/
│   │   └── toast-notification.blade.php ← Notifikasi
│   └── dashboard.blade.php             ← Dashboard

routes/
└── web.php                             ← Semua routes

database/
└── migrations/
    └── 2025_11_26_165050_create_attendances_table.php

storage/app/public/attendances/        ← Foto tersimpan di sini
```

---

## ✅ Final Checklist

**Before going live:**
- [ ] All routes working
- [ ] Toast notifications showing
- [ ] Photo upload working
- [ ] GPS obtaining coordinates
- [ ] Admin approval system working
- [ ] Database records saving correctly
- [ ] Status transitions (pending→hadir/ditolak) working
- [ ] Pagination working (if 15+ records)
- [ ] Authorization checks working
- [ ] Error handling working

---

**Status:** ✅ READY FOR TESTING
**Last Updated:** November 29, 2025
**Version:** 1.0.0
