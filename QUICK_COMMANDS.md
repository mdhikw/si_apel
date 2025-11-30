# 🎯 Quick Commands - Testing Sistem Absensi

## 🚀 Setup Awal

```bash
# 1. Masuk ke folder project
cd c:\xampp\htdocs\si-apel

# 2. Fresh database
php artisan migrate:fresh
php artisan db:seed

# 3. Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 4. Verify routes
php artisan route:list | Select-String "attendances"

# 5. Start server
php artisan serve
```

---

## 📋 Test Commands

### **Check Database State**

```bash
php artisan tinker

# Check users
User::all()
// Should show 2 users: admin + anggota

# Check attendances
Attendance::all()
// After user submits, should have records

# Check pending count
Attendance::where('status', 'pending')->count()

# Check approved
Attendance::where('status', 'hadir')->count()

# Check rejected  
Attendance::where('status', 'ditolak')->count()

# Get latest attendance
Attendance::with('user')->latest()->first()

# Exit tinker
exit
```

### **Verify Photo Storage**

```bash
# List uploaded photos
dir storage\app\public\attendances\

# Expected files:
# attendance_1_1732879200.png
# attendance_2_1732879300.png
# etc...

# Test public URL in browser:
# http://localhost:8000/storage/attendances/attendance_2_1732879245.png
```

### **Check Routes Registered**

```bash
# View all attendance routes
php artisan route:list | Select-String "attendances"

# Expected output should show:
# - GET  /attendances (index)
# - GET  /attendances/create/{office} (create)
# - POST /attendances (store)
# - GET  /riwayat-absensi (history)
# - GET  /admin/pending-absensi (adminPending)
# - PATCH /attendances/{id}/approve (approve)
# - PATCH /attendances/{id}/reject (reject)
```

---

## 🧪 Manual Testing Flow

### **Test 1: User Submit Attendance (2 min)**

```
1. Open http://localhost:8000/login
2. Login: budi@polres.com / 12345678
3. Click "Tambah Absensi"
4. Select any office
5. Click "📸 Jepret Foto" - Capture selfie
6. Wait for GPS location to populate
7. When button turns green "🚀 Kirim Absensi" → Click it
8. Should see GREEN TOAST: "Absensi berhasil dikirim!"
9. Redirect to dashboard
```

### **Verify in Database**

```bash
php artisan tinker
Attendance::latest()->first()
// Check: user_id=2, status='pending', photo_in has value
exit
```

---

### **Test 2: Admin View Pending (2 min)**

```
1. Open http://localhost:8000/logout (logout user)
2. Login: admin@polres.com / 12345678
3. Should see RED ALERT on dashboard: "X Absensi Menunggu Persetujuan"
4. Click "Lihat Sekarang ⚡"
5. Should see admin-pending page with table
6. Table should show:
   - Pegawai: Budi Santoso (or submitted user)
   - NRP: 202301
   - Foto: Thumbnail clickable
   - Lokasi: Coordinates clickable
   - Buttons: ✅ Setujui | ❌ Tolak
```

### **Verify in Database**

```bash
php artisan tinker
Attendance::where('status', 'pending')->count()
// Should be 1 (or number submitted)
exit
```

---

### **Test 3: Admin Approve Attendance (1 min)**

```
1. On admin-pending page, click "✅ Setujui"
2. Confirm dialog: Click "OK"
3. Should see GREEN TOAST: "Absensi berhasil disetujui!"
4. Page refresh - pending table should be empty
```

### **Verify in Database**

```bash
php artisan tinker
Attendance::where('status', 'hadir')->count()
// Should be 1
Attendance::where('status', 'pending')->count()
// Should be 0
exit
```

---

### **Test 4: Admin Reject Attendance (1 min)**

```
1. Submit another attendance as user
2. As admin, go to pending page
3. Click "❌ Tolak"
4. Confirm dialog: Click "OK"
5. Should see GREEN TOAST: "Absensi berhasil ditolak!"
6. Status changed to "ditolak"
```

### **Verify in Database**

```bash
php artisan tinker
Attendance::where('status', 'ditolak')->count()
// Should be 1
exit
```

---

## 🔍 Debug Commands

### **If Form Not Submitting**

```bash
# Check browser console for errors:
# F12 → Console tab

# Should see:
# ✅ Script loaded successfully
# take_snapshot function: function
# ✅ All 7 routes registered

# Clear browser cache:
# Ctrl+Shift+Delete → Clear browsing data → Cache → Clear now
```

### **If Photo Not Saving**

```bash
# Check storage permissions
icacls storage\app\public\attendances

# Check if directory exists
Test-Path "storage\app\public\attendances"
# If not:
New-Item -ItemType Directory -Path "storage\app\public\attendances" -Force

# Check storage link
Test-Path "public\storage"
# If not:
php artisan storage:link
```

### **If GPS Not Working**

```bash
# Check in browser console:
navigator.geolocation
// Should return Geolocation object

# Try manual input:
1. On form, GPS error appears
2. Click "📍 Input Manual"
3. Enter coordinates manually from Google Maps
4. Submit form

# Browser-specific steps:
Chrome: Settings → Privacy & Security → Site Settings → Location
Firefox: Settings → Privacy → Permissions → Location
Safari: Safari → Settings → Websites → Location
```

---

## 🎯 Expected Success Indicators

### **✅ User Submission Success**
- [ ] Form POST to `/attendances`
- [ ] Photo file saved in `storage/app/public/attendances/`
- [ ] Database record created with `status='pending'`
- [ ] Green toast notification appears
- [ ] Redirect to dashboard successful
- [ ] Attendance appears in riwayat with ⏳ status

### **✅ Admin Notification Success**
- [ ] RED ALERT appears on admin dashboard
- [ ] Shows correct count of pending
- [ ] "Lihat Sekarang ⚡" button clickable
- [ ] Routes to `/admin/pending-absensi`

### **✅ Admin Review Success**
- [ ] Pending page loads with data table
- [ ] All columns visible (pegawai, NRP, foto, lokasi, aksi)
- [ ] Photo thumbnail clickable for full view
- [ ] Coordinates clickable for Google Maps
- [ ] Approve/Reject buttons present

### **✅ Approval Success**
- [ ] Status changes from `pending` → `hadir`
- [ ] Green toast "Absensi berhasil disetujui!" appears
- [ ] Record disappears from pending table
- [ ] Database status updated correctly

### **✅ Rejection Success**
- [ ] Status changes from `pending` → `ditolak`
- [ ] Green toast "Absensi berhasil ditolak!" appears
- [ ] Record disappears from pending table
- [ ] Database status updated correctly

---

## 📱 Testing on Mobile

```
1. Change .env: APP_URL=http://your-ip-address:8000
2. Access from mobile: http://192.168.x.x:8000
3. Camera will request access
4. GPS will request location access
5. Test full flow on mobile browser
```

---

## ⚠️ Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Form not submitting | Check photo + GPS, verify console logs |
| No toast notification | Clear cache: `php artisan view:clear` |
| GPS permission denied | Browser settings → Reset location permission |
| Photo not uploading | Check storage permissions + folder exists |
| Admin pending empty | Verify status='pending' in database |
| Can't approve/reject | Login as admin, check role in database |
| 403 Unauthorized | Verify user role (must be admin) |

---

## 🎬 Full Test Scenario (5 minutes)

```bash
# Terminal 1: Start server
php artisan serve

# Terminal 2: Watch database
php artisan tinker
# (keep it open to watch changes)

# Browser:
1. http://localhost:8000/dashboard
   - Login: budi@polres.com / 12345678
   
2. Click "Tambah Absensi"
   - Select office
   - Capture photo
   - Get GPS location
   - Submit form ← Check Terminal 2 for new record
   
3. See green toast notification

4. Logout, login as admin

5. http://localhost:8000/dashboard
   - See RED ALERT ← Check Terminal 2 for pending count
   - Click "Lihat Sekarang ⚡"
   
6. http://localhost:8000/admin/pending-absensi
   - See pending attendance table
   - Click "✅ Setujui" ← Check Terminal 2 for status update
   
7. See green toast notification

8. Repeat step 5 - pending count now 0

✅ SUCCESS!
```

---

## 📚 Documentation Files

```
- WORKFLOW_SYSTEM.md       → Complete workflow diagrams
- TESTING_GUIDE.md         → Detailed testing guide
- FIXES_SUMMARY.md         → Changes made this session
- VERIFICATION_COMPLETE.md → Full verification checklist
```

---

**Ready for Testing! 🚀**

Run these commands to get started:
```bash
cd c:\xampp\htdocs\si-apel
php artisan migrate:fresh
php artisan db:seed
php artisan cache:clear
php artisan serve
```

Then visit: http://localhost:8000
