# 📋 Workflow Sistem Absensi Polres Garut

## 🔍 Daftar Isi
1. [Alur Sistem](#alur-sistem)
2. [User Submission Flow](#user-submission-flow)
3. [Admin Approval Flow](#admin-approval-flow)
4. [Struktur Database](#struktur-database)
5. [Testing Checklist](#testing-checklist)

---

## ✅ Alur Sistem

### 📊 Diagram Alur:
```
┌─────────────────────┐
│ USER LOGIN (Anggota)│
└──────────┬──────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ Dashboard → "Tambah Absensi" Button      │
└──────────┬───────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ Pilih Kantor untuk Absen                │
└──────────┬───────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ Form Absensi (Kamera + GPS)              │
│ - Ambil Foto Selfie                      │
│ - Aktifkan GPS/Lokasi                    │
│ - Verifikasi di dalam Radius             │
└──────────┬───────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ SUBMIT ABSENSI                           │
│ - POST /attendances (Status: PENDING)    │
│ - Foto disimpan di storage/attendances/  │
│ - Data di Database: attendances table    │
└──────────┬───────────────────────────────┘
           │
           ▼ [Success Message]
┌──────────────────────────────────────────┐
│ ✅ TOAST NOTIFICATION MUNCUL             │
│ "Absensi berhasil dikirim!               │
│  Menunggu persetujuan Admin."            │
│ [Auto-hide dalam 5 detik]                │
└──────────┬───────────────────────────────┘
           │
           ▼ [Redirect ke Dashboard]
┌──────────────────────────────────────────┐
│ Dashboard → Status: ⏳ PENDING            │
│ (User melihat riwayat dengan status)     │
└──────────────────────────────────────────┘


┌─────────────────────┐
│ ADMIN LOGIN         │
└──────────┬──────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ Dashboard → RED ALERT BOX:               │
│ 🔔 "X Absensi Menunggu Persetujuan"      │
│    "Lihat Sekarang ⚡" Button            │
└──────────┬───────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ GET /admin/pending-absensi (Admin Page)  │
│ Table dengan:                            │
│ - Nama Pegawai + NRP                     │
│ - Jabatan + Pangkat                      │
│ - Tanggal & Jam Masuk                    │
│ - Foto Bukti (Thumbnail)                 │
│ - Lokasi (GPS Coordinates)               │
│ - Tombol: ✅ Setujui | ❌ Tolak         │
└──────────┬───────────────────────────────┘
           │
           ├─ Klik "✅ Setujui"
           │  PATCH /attendances/{id}/approve
           │  Status: PENDING → HADIR
           │  ✅ Toast: "Absensi berhasil disetujui!"
           │
           └─ Klik "❌ Tolak"
              PATCH /attendances/{id}/reject
              Status: PENDING → DITOLAK
              ✅ Toast: "Absensi berhasil ditolak!"
```

---

## 👤 User Submission Flow (Lengkap)

### **Step 1: Login Sebagai Anggota**
- Email: `budi@polres.com`
- Password: `12345678`
- Role: `anggota`

### **Step 2: Buka Dashboard**
- URL: `http://localhost:8000/dashboard`
- Tombol: "➕ Tambah Absensi"

### **Step 3: Pilih Kantor**
- Route: `GET /attendances` (AttendanceController@index)
- Tampilkan: Grid/list kantor dengan:
  - Nama kantor
  - Lokasi (latitude, longitude)
  - Radius jangkauan
- Klik kantor → Redirect ke form absensi

### **Step 4: Form Absensi (Create)**
- Route: `GET /attendances/create/{office}` (AttendanceController@create)
- Layout 2-kolom:
  - **Kiri: Kamera Selfie**
    - Webcam.js: `#my_camera`
    - Button: "📸 Jepret Foto"
    - Preview foto hasil: `#results`
  - **Kanan: Peta Lokasi**
    - Leaflet.js map
    - Kantor: Green circle (radius)
    - User location: Blue marker
    - Jarak ke kantor: Display real-time

### **Step 5: Capture Foto & GPS**

#### **5a. Foto Selfie:**
```javascript
// Button onClick → take_snapshot()
// Function menggunakan Webcam.js
// Output: Base64 string disimpan di #photo_data
// Preview ditampilkan di #results
```

**Form field:**
```html
<input type="hidden" name="photo" id="photo_data">
```

#### **5b. Geolocation/GPS:**
```javascript
// navigator.geolocation.watchPosition() terus monitor lokasi
// Success: Update latitude + longitude
// Error: Tampilkan help box dengan 3 solusi:
//   1. Reset permission di browser
//   2. Gunakan Incognito mode
//   3. Input manual (fallback)
```

**Form fields:**
```html
<input type="hidden" name="latitude" id="latitude">
<input type="hidden" name="longitude" id="longitude">
```

### **Step 6: Validasi Client-Side**

Fungsi `validateForm()` akan:
- ✅ Cek apakah `photo` ada
- ✅ Cek apakah `latitude` ada
- ✅ Jika keduanya ada → Enable submit button
- ❌ Jika tidak lengkap → Disable button (gray, disabled)

### **Step 7: Submit Absensi**

**Route:**
```
POST /attendances
AttendanceController@store()
```

**Validasi Server:**
```php
$request->validate([
    'latitude'  => 'required',   // GPS
    'longitude' => 'required',   // GPS
    'photo'     => 'required',   // Foto Base64
]);
```

**Proses di Server:**

1. **Validasi Radius (PENTING!):**
   - Cek apakah user berada dalam radius kantor
   - Gunakan: `findNearestOffice($lat, $lng)`
   - Fungsi `calculateDistance()` pakai Haversine formula
   - Jika TIDAK dalam radius → Error + redirect back

2. **Simpan Foto:**
   - Terima: Base64 string dari frontend
   - Decode: `base64_decode($photo)`
   - Simpan: `storage/app/public/attendances/{filename}.png`
   - Filename: `attendance_{user_id}_{timestamp}.png`

3. **Simpan Data ke Database:**
   ```php
   Attendance::create([
       'user_id'    => Auth::id(),
       'date'       => now()->toDateString(),     // YYYY-MM-DD
       'time_in'    => now()->toTimeString(),     // HH:MM:SS
       'latlon_in'  => "{lat},{lng}",              // Koordinat
       'photo_in'   => $filename,                  // Nama file foto
       'status'     => 'pending',                  // ⏳ PENDING status
   ]);
   ```

4. **Return Success Message:**
   ```php
   return redirect()->route('dashboard')
       ->with('success', 'Absensi berhasil dikirim! Menunggu persetujuan Admin.');
   ```

### **Step 8: Toast Notification (AUTO-TRIGGER)**

**Component: `resources/views/components/toast-notification.blade.php`**

**Kondisi:**
- Jika ada `session('success')` → Tampilkan green toast
- Jika ada `session('error')` → Tampilkan red toast
- Jika ada `session('info')` → Tampilkan blue toast

**Green Toast untuk Submit:**
```
✅ Berhasil!
Absensi berhasil dikirim! Menunggu persetujuan Admin.
```

**Styling:**
- Fixed position: top-right
- Animation: Slide-in 0.3s
- Auto-hide: 5 detik dengan reverse animation

### **Step 9: Redirect ke Dashboard**

- User otomatis redirect ke `/dashboard`
- Toast notification ditampilkan
- Di "Riwayat Bulan Ini" table:
  - Tampil attendance baru dengan status: `⏳ PENDING`
  - Badge status: Yellow/Orange color

---

## 👨‍💼 Admin Approval Flow (Lengkap)

### **Step 1: Login Sebagai Admin**
- Email: `admin@polres.com`
- Password: `12345678`
- Role: `admin`

### **Step 2: Buka Dashboard**
- URL: `http://localhost:8000/dashboard`
- Tampilkan: RED ALERT BOX (jika ada pending absensi)

```
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
┃ 🔴 NOTIFIKASI ABSENSI PENDING!            ┃
┃ X Absensi Menunggu Persetujuan             ┃
┃ [Lihat Sekarang ⚡]                        ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛
```

**Controller:**
```php
// DashboardController@index
if(Auth::user()->role == 'admin') {
    $pendingCount = Attendance::where('status', 'pending')->count();
}
```

### **Step 3: Klik "Lihat Sekarang ⚡"**

**Route:**
```
GET /admin/pending-absensi
AttendanceController@adminPending()
```

**Authorization Check:**
```php
if(Auth::user()->role !== 'admin') {
    abort(403, 'Unauthorized action.');
}
```

### **Step 4: Admin Pending Page**

**View: `resources/views/attendances/admin-pending.blade.php`**

**Tampilan:**
```
┌──────────────────────────────────────────────────┐
│ 🔔 Absensi Menunggu Persetujuan                  │
│ ┌─────────────────────────────────────────────┐  │
│ │ No │ Pegawai      │ Jam    │ Foto │ Lokasi │  │
│ ├────┼──────────────┼────────┼──────┼────────┤  │
│ │ 1  │ Budi Santoso │ 08:30  │ 📷   │ 🗺️     │  │
│ │    │ NRP: 202301  │        │      │ Maps   │  │
│ │    │ Jabatan: ... │        │      │        │  │
│ ├────┴──────────────┴────────┴──────┴────────┤  │
│ │ ✅ Setujui | ❌ Tolak                      │  │
│ └────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────┘
```

**Table Columns:**
1. **No** - Nomor urut
2. **Pegawai** - Nama + NRP
3. **Jabatan** - Pangkat + Jabatan
4. **Tanggal & Jam** - Date + time_in
5. **Foto Bukti** - Thumbnail + clickable untuk view full
6. **Lokasi** - Coordinates + link ke Google Maps
7. **Aksi** - Setujui/Tolak buttons

**Pagination:** 15 items per page

**Data Query:**
```php
$pendingAttendances = Attendance::with('user')
                        ->where('status', 'pending')
                        ->latest()
                        ->paginate(15);
```

### **Step 5: Review Data Absensi**

**Informasi yang ditampilkan:**
- ✅ Foto bukti (thumbnai 48x48px, clickable untuk full)
- ✅ Koordinat GPS (clickable → Google Maps)
- ✅ Data pegawai lengkap (NRP, jabatan, pangkat)
- ✅ Waktu submit

### **Step 6a: Approve Absensi (✅ Setujui)**

**Route:**
```
PATCH /attendances/{attendance}/approve
AttendanceController@approve()
```

**Form:**
```blade
<form action="{{ route('attendances.approve', $attendance->id) }}" method="POST">
    @method('PATCH')
    @csrf
    <button type="submit" onclick="return confirm('Setujui absensi ini?')">
        ✅ Setujui
    </button>
</form>
```

**Controller Logic:**
```php
public function approve(Attendance $attendance)
{
    // 1. Cek authorization
    if(Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    // 2. Update status
    $attendance->update([
        'status' => 'hadir'  // ✅ APPROVED
    ]);

    // 3. Return success message
    return redirect()->back()
        ->with('success', 'Absensi berhasil disetujui!');
}
```

**Database Update:**
```sql
UPDATE attendances 
SET status = 'hadir' 
WHERE id = {attendance_id};
```

**Toast Notification:**
```
✅ Berhasil!
Absensi berhasil disetujui!
```

### **Step 6b: Reject Absensi (❌ Tolak)**

**Route:**
```
PATCH /attendances/{attendance}/reject
AttendanceController@reject()
```

**Form:**
```blade
<form action="{{ route('attendances.reject', $attendance->id) }}" method="POST">
    @method('PATCH')
    @csrf
    <button type="submit" onclick="return confirm('Tolak absensi ini?')">
        ❌ Tolak
    </button>
</form>
```

**Controller Logic:**
```php
public function reject(Attendance $attendance)
{
    // 1. Cek authorization
    if(Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    // 2. Update status
    $attendance->update([
        'status' => 'ditolak'  // ❌ REJECTED
    ]);

    // 3. Return success message
    return redirect()->back()
        ->with('success', 'Absensi berhasil ditolak!');
}
```

**Database Update:**
```sql
UPDATE attendances 
SET status = 'ditolak' 
WHERE id = {attendance_id};
```

**Toast Notification:**
```
✅ Berhasil!
Absensi berhasil ditolak!
```

### **Step 7: View Updates**

**Setelah Approve/Reject:**
1. ✅ Toast notification muncul
2. ✅ Page refresh/update otomatis
3. ✅ Absensi yang sudah di-approve tidak tampil di admin-pending
4. ✅ Absensi bisa dilihat di history page dengan status updated

---

## 🗄️ Struktur Database

### **Tabel: `attendances`**

```sql
CREATE TABLE attendances (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    date DATE NOT NULL,                    -- Tanggal absensi
    time_in TIME,                          -- Jam masuk
    time_out TIME NULLABLE,                -- Jam keluar
    latlon_in VARCHAR(255),                -- Koordinat masuk "lat,lng"
    latlon_out VARCHAR(255) NULLABLE,      -- Koordinat keluar
    photo_in VARCHAR(255) NOT NULL,        -- Filename foto masuk
    photo_out VARCHAR(255) NULLABLE,       -- Filename foto keluar
    status ENUM('pending', 'hadir', 'ditolak') DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### **Status Values:**
| Status | Arti | Color | Kondisi |
|--------|------|-------|---------|
| `pending` | ⏳ Menunggu | Yellow | Data baru, belum di-review admin |
| `hadir` | ✅ Hadir | Green | Sudah di-approve admin |
| `ditolak` | ❌ Ditolak | Red | Di-reject admin |

### **File Storage:**
```
storage/app/public/attendances/
├── attendance_1_1732879200.png  (user_id=1, timestamp=1732879200)
├── attendance_2_1732879300.png
├── attendance_1_1732879400.png
└── ...
```

**Public Access:**
```
http://localhost:8000/storage/attendances/attendance_1_1732879200.png
```

---

## ✅ Testing Checklist

### **🧪 Test 1: User Submit Absensi**

**Preconditions:**
- ✅ Database sudah ter-seed (2 users: admin + anggota)
- ✅ Attendance table kosong (0 records)
- ✅ Storage link sudah di-setup
- ✅ Kamera & GPS sudah aktif di browser

**Steps:**
1. Login sebagai `budi@polres.com` / `12345678`
2. Klik "Tambah Absensi"
3. Pilih salah satu kantor
4. Ambil foto selfie (📸 Jepret Foto)
5. Tunggu GPS mendapat lokasi (latitude & longitude terisi)
6. Button submit: "🚀 Kirim Absensi" (harus enable/hijau)
7. Klik submit

**Expected Results:**
- ✅ Form POST ke `/attendances`
- ✅ Toast notification hijau: "Absensi berhasil dikirim! Menunggu persetujuan Admin."
- ✅ Redirect ke `/dashboard`
- ✅ Toast visible selama 5 detik kemudian hilang
- ✅ Database: 1 record baru di `attendances` table dengan `status='pending'`
- ✅ File foto: Tersimpan di `storage/app/public/attendances/`

**Verification Query:**
```php
// Di terminal / tinker:
Attendance::latest()->first();
// Output:
Attendance {
  id: 1,
  user_id: 2,
  date: "2025-11-29",
  time_in: "14:30:45",
  latlon_in: "-6.2088,106.8456",
  photo_in: "attendance_2_1732879245.png",
  status: "pending",
  ...
}
```

---

### **🧪 Test 2: Admin See Pending Notification**

**Preconditions:**
- ✅ 1 attendance dengan status='pending' sudah ada di database

**Steps:**
1. Logout dari user account
2. Login sebagai `admin@polres.com` / `12345678`
3. Buka `/dashboard`

**Expected Results:**
- ✅ Dashboard menampilkan RED ALERT box:
  ```
  🔔 1 Absensi Menunggu Persetujuan
  [Lihat Sekarang ⚡]
  ```
- ✅ Di "Stat Cards" - Pending count = 1

**Verification:**
```php
// Di DashboardController@index:
$pendingCount = Attendance::where('status', 'pending')->count();
// Output: 1
```

---

### **🧪 Test 3: Admin Navigate to Pending Page**

**Preconditions:**
- ✅ 1 attendance pending sudah ada

**Steps:**
1. Di dashboard admin, klik "Lihat Sekarang ⚡"
2. Browser navigate ke `/admin/pending-absensi`

**Expected Results:**
- ✅ Page judul: "🔔 Absensi Menunggu Persetujuan"
- ✅ Table menampilkan 1 row dengan:
  - No: 1
  - Pegawai: Budi Santoso (atau nama yang submit)
  - NRP: (dari user data)
  - Jabatan: (dari user data)
  - Tanggal & Jam: (dari attendance data)
  - Foto: Thumbnail 48x48px
  - Lokasi: Coordinates clickable
  - Action buttons: ✅ Setujui | ❌ Tolak
- ✅ Pagination links (jika > 15 items)

---

### **🧪 Test 4: Admin Approve Absensi**

**Preconditions:**
- ✅ Di admin pending page dengan 1 pending record

**Steps:**
1. Klik "✅ Setujui" button
2. Confirm dialog: "Setujui absensi ini?"
3. Klik "OK"

**Expected Results:**
- ✅ Form POST to PATCH `/attendances/{id}/approve`
- ✅ Toast notification hijau: "Absensi berhasil disetujui!"
- ✅ Page refresh otomatis
- ✅ Pending table menjadi kosong
- ✅ Database: attendance status berubah dari 'pending' → 'hadir'

**Verification Query:**
```php
Attendance::find(1)->status;  // Output: "hadir"
```

---

### **🧪 Test 5: Admin Reject Absensi**

**Preconditions:**
- ✅ 1 attendance dengan status='pending'

**Steps:**
1. Di admin pending page, klik "❌ Tolak"
2. Confirm dialog: "Tolak absensi ini?"
3. Klik "OK"

**Expected Results:**
- ✅ Form PATCH to `/attendances/{id}/reject`
- ✅ Toast notification hijau: "Absensi berhasil ditolak!"
- ✅ Page refresh otomatis
- ✅ Pending table menjadi kosong
- ✅ Database: attendance status berubah dari 'pending' → 'ditolak'

**Verification Query:**
```php
Attendance::find(1)->status;  // Output: "ditolak"
```

---

### **🧪 Test 6: View Attendance History**

**Preconditions:**
- ✅ Multiple attendance records dengan berbagai status

**Steps:**

**Sebagai User:**
1. Login sebagai `budi@polres.com`
2. Klik "Riwayat Absensi" atau navigate ke `/riwayat-absensi`

**Expected Results:**
- ✅ Page judul: "Riwayat Absensi"
- ✅ Table hanya menampilkan attendance milik user itu saja
- ✅ Status badge ditampilkan dengan warna:
  - 🟢 Green untuk 'hadir'
  - 🟡 Yellow untuk 'pending'
  - 🔴 Red untuk 'ditolak'

**Sebagai Admin:**
1. Login sebagai `admin@polres.com`
2. Klik "Riwayat Absensi" atau navigate ke `/riwayat-absensi`

**Expected Results:**
- ✅ Page judul: "Riwayat Absensi"
- ✅ Table menampilkan SEMUA attendance (dari semua user)
- ✅ Kolom tambahan: "Pegawai" (nama user)
- ✅ Untuk pending records → tampil buttons: ✅ Setujui | ❌ Tolak
- ✅ Untuk approved/rejected → buttons tidak tampil

---

### **🧪 Test 7: Error Handling - Missing Photo**

**Steps:**
1. Login sebagai user
2. Buka form absensi `/attendances/create/{office}`
3. SKIP ambil foto
4. Tunggu GPS dapat lokasi
5. Klik submit button

**Expected Results:**
- ✅ Button submit tetap DISABLED (gray)
- ✅ Text: "⏳ Menunggu Lokasi & Foto..."
- ❌ Form tidak bisa disubmit

---

### **🧪 Test 8: Error Handling - Missing GPS**

**Steps:**
1. Login sebagai user
2. Buka form absensi `/attendances/create/{office}`
3. Ambil foto
4. GPS tidak didapat (permission denied)
5. Error box muncul dengan 3 solusi
6. Coba gunakan fallback manual input
7. Masukkan latitude & longitude secara manual
8. Klik submit

**Expected Results:**
- ✅ Manual input berfungsi
- ✅ Button submit ENABLE setelah manual input
- ✅ Submit berhasil dengan koordinat manual

---

### **🧪 Test 9: Error Handling - Outside Radius**

**Steps:**
1. Login sebagai user
2. Buka form absensi di kantor A (radius: 200m)
3. GPS dapat lokasi yang JAUH dari kantor A (> 200m)
4. Distance display: "❌ Jauh" (merah)
5. Klik submit

**Expected Results:**
- ✅ Server error: "Gagal! Anda berada di luar jangkauan radius kantor manapun."
- ✅ Redirect back ke form
- ✅ Tidak ada record baru di database

---

### **🧪 Test 10: Photo Storage**

**Steps:**
1. User submit absensi dengan foto
2. Cek folder: `storage/app/public/attendances/`

**Expected Results:**
- ✅ File foto ada dengan nama: `attendance_{user_id}_{timestamp}.png`
- ✅ File bisa diakses public: `http://localhost:8000/storage/attendances/{filename}`
- ✅ Foto bisa ditampilkan di admin pending page

---

## 📋 Summary

**Routes yang aktif:**
```
✅ GET /attendances                        → Daftar kantor
✅ GET /attendances/create/{office}        → Form absensi
✅ POST /attendances                       → Submit absensi
✅ GET /riwayat-absensi                    → History absensi
✅ GET /admin/pending-absensi              → Admin pending page
✅ PATCH /attendances/{id}/approve        → Approve absensi
✅ PATCH /attendances/{id}/reject         → Reject absensi
```

**Status Flow:**
```
pending (⏳ Menunggu)
    ↓
    ├─ APPROVE → hadir (✅ Hadir)
    └─ REJECT  → ditolak (❌ Ditolak)
```

**Notification System:**
```
User Submit (Success)
    ↓
Toast: "Absensi berhasil dikirim! Menunggu persetujuan Admin."
    ↓
Admin Dashboard: RED ALERT "X Absensi Menunggu Persetujuan"
    ↓
Admin Click: Navigate ke /admin/pending-absensi
    ↓
Admin Approve/Reject
    ↓
Toast: "Absensi berhasil disetujui!" / "Absensi berhasil ditolak!"
```

---

**Last Updated:** November 29, 2025
**Status:** ✅ PRODUCTION READY
