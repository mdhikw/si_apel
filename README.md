# 📱 SISTEM INFORMASI ABSENSI POLRES GARUT

**Status:** ✅ Production Ready  
**Versi:** 1.0.0  
**Tanggal Update:** 2 Desember 2025

**TOC**

-   **Deskripsi Singkat**
-   **Persyaratan (Requirements)**
-   **Clone & Instalasi**
-   **Konfigurasi `.env` & Key**
-   **Migrate & Seed**
-   **Menjalankan Aplikasi**
-   **Peran Admin**
-   **ERD & Relasi (PK/FK dan tipe kolom)**
-   **Catatan Tambahan & Troubleshooting**

**Deskripsi Singkat**

-   **Aplikasi:** Sistem absensi untuk pengelolaan kehadiran personel Polres.
-   **Fitur utama:** Pengguna dapat mengambil foto (webcam), mengirim koordinat GPS, mengirim bukti absen; Admin dapat meninjau, menyetujui atau menolak absen.

**Persyaratan (Requirements)**

-   **OS:** Windows / macOS / Linux
-   **PHP:** 8.1+ (disarankan sesuai kebutuhan Laravel di composer.json)
-   **Composer:** latest stable
-   **Node.js & npm:** Node >= 20.19 atau >= 22.12 (Vite membutuhkan versi minimal ini)
-   **Database:** MySQL 8.0+ atau MariaDB setara
-   **Ekstensi PHP penting:** `pdo`, `mbstring`, `openssl`, `tokenizer`, `json`, `ctype`, `xml`, `ctype`, `fileinfo` (cek composer.json / Laravel docs)

**Clone & Instalasi**

1. Clone repository

```powershell
git clone https://github.com/mdhikw/si_apel.git
cd si_apel
```

2. Install dependensi PHP

```powershell
composer install
```

3. Install dependensi frontend

```powershell
npm install
```

Catatan: jika `npm` muncul error di PowerShell karena policy, jalankan `npm.cmd` atau gunakan `cmd /c "npm install"`, atau ubah execution policy (lihat dokumentasi keamanan PowerShell).

**Konfigurasi `.env` & Key**

1. Salin file environment

Cross-platform:

```powershell
copy .env.example .env
```

atau (Linux/macOS):

```bash
cp .env.example .env
```

2. Generate app key

```powershell
php artisan key:generate
```

3. Edit ` .env` untuk mengatur database, mail, APP_URL, dsb. Contoh minimal:

```
APP_NAME=SI_APEL
APP_ENV=local
APP_KEY= (sudah tergenerate)
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=si_apel
DB_USERNAME=root
DB_PASSWORD=
```

**Migrasi & Seeder**

1. Jalankan migrasi dan seeder

```powershell
php artisan migrate --seed
```

Jika ingin membersihkan dan mulai ulang (data akan terhapus):

```powershell
php artisan migrate:fresh --seed
```

**Menjalankan Aplikasi (Dev)**

-   Jalankan server Laravel (PHP built-in):

```powershell
php artisan serve
# Default: http://127.0.0.1:8000
```

-   Jalankan Vite dev server (frontend):

```powershell
npm run dev
```

Jika Anda menggunakan Node versi yang tidak kompatibel, silakan upgrade Node (disarankan `nvm-windows` atau installer resmi).

**Peran Admin (Tanggung Jawab)**

-   **Meninjau Absensi:** Melihat daftar absensi berstatus `pending`.
-   **Verifikasi Bukti:** Memeriksa foto bukti, koordinat GPS (mis. membuka link peta) dan keterangan.
-   **Menerima / Menolak:** Menyetujui (`hadir`) atau menolak (`ditolak`) absensi.
-   **Manajemen Pengguna:** (Jika tersedia) menambah/mengedit akun anggota dan mengelola peran.
-   **Pemeliharaan Sistem:** Memantau antrian pekerjaan, log, dan penyimpanan file.

**ERD & Relasi (ringkas, PK / FK / tipe data penting)**

**Diagram ERD Visual:**

![ERD Diagram](DOCS/image.png)

Tabel utama dan ringkasan kolom (singkat):

-   `users`

    -   PK: `id` (BIGINT unsigned, auto-increment)
    -   `name` VARCHAR
    -   `email` VARCHAR UNIQUE
    -   `nrp` VARCHAR NULL UNIQUE
    -   `pangkat`, `jabatan`, `no_hp` VARCHAR NULL
    -   `role` ENUM('admin','anggota')
    -   `password`, `foto`, timestamps

-   `offices`

    -   PK: `id` (BIGINT)
    -   `name` VARCHAR, `address` VARCHAR
    -   `latitude` DOUBLE, `longitude` DOUBLE
    -   `radius` INT

-   `shifts`

    -   PK: `id` (BIGINT)
    -   `name` VARCHAR, `start_time` TIME, `end_time` TIME

-   `attendances`

    -   PK: `id` (BIGINT)
    -   FK: `user_id` -> `users.id` (cascade on delete)
    -   `date` DATE, `time_in` TIME, `time_out` TIME NULL
    -   `latlon_in`, `latlon_out` VARCHAR NULL
    -   `status` VARCHAR default 'pending'
    -   `photo_in`, `photo_out` VARCHAR

-   `permissions`

    -   PK: `id` (BIGINT)
    -   FK: `user_id` -> `users.id` (cascade on delete)
    -   `date_permission` DATE, `reason` TEXT, `image` VARCHAR NULL
    -   `is_approved` BOOLEAN default false

-   `sessions`
    -   PK: `id` (STRING)
    -   `user_id` BIGINT NULL (indexed, not constrained in migration)
    -   `ip_address`, `user_agent`, `payload`, `last_activity` INT

**Diagram UML:**

![UML Diagram](DOCS/Screenshot%202025-12-02%20102641.png)

ASCII ERD (sederhana):

```
users (1) ----------------< (N) attendances
    id PK                 attendances.id PK
                          attendances.user_id FK -> users.id

users (1) ----------------< (N) permissions
                          permissions.user_id FK -> users.id

offices (no FK)    shifts (no FK)
```

Catatan: `attendances.user_id` dan `permissions.user_id` dibuat dengan `constrained()->cascadeOnDelete()` di migration — artinya FK DB-level ada. `sessions.user_id` dibuat tanpa `constrained()` (hanya indexed) sehingga tidak ada FK enforcement di DB.

Jika Anda ingin menambahkan relasi seperti `attendances.office_id` atau `attendances.shift_id`, saya bisa buat migration baru untuk menambahkannya.**Perintah berguna**

```powershell
# Clear caches
php artisan optimize:clear

# Rebuild node modules (jika ganti Node)
Remove-Item -Recurse -Force node_modules,package-lock.json
npm install

# Jalankan migrasi + seed
php artisan migrate --seed

# Lihat routes
php artisan route:list
```

**Troubleshooting singkat**

-   Jika foto tidak tampil: jalankan `php artisan storage:link` dan pastikan `storage/app/public` dapat diakses.
-   Jika npm error di PowerShell: coba `npm.cmd install` atau jalankan `cmd /c \"npm install\"`.
-   Jika Vite memperingatkan versi Node: update Node ke versi yang direkomendasikan.

**Saran & Next Steps**

-   Tambahkan ERD visual (PNG/SVG/diagrams.net) ke repo jika perlu distribusi dokumentasi.
-   Tambahkan migration bila mau mengaitkan `attendances` ke `offices`/`shifts`.

---

Jika mau, saya bisa: menambahkan migration untuk `attendances.office_id` & `attendances.shift_id`, atau menghasilkan diagram ERD visual (SVG/PNG) dan menaruhnya di `docs/erd.svg` — pilih yang Anda mau dan saya buatkan perubahan.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

---

# 🚨 SISTEM ABSENSI POLRES GARUT - PRODUCTION READY

**Status:** 🟢 PRODUCTION READY  
**Version:** 1.1.0  
**Updated:** November 29, 2025

---

## 🎯 Quick Start

```bash
# 1. Setup Database
php artisan migrate:fresh
php artisan db:seed

# 2. Clear Caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 3. Start Server
php artisan serve
```

**Test Accounts:**

```
Admin:  admin@polres.com / 12345678
User:   budi@polres.com / 12345678
```

**URLs:**

```
Dashboard:    http://localhost:8000/dashboard
User Form:    http://localhost:8000/attendances
Admin Review: http://localhost:8000/admin/pending-absensi
```

---

## ✨ Features

✅ **User Submission**

-   Webcam photo capture (Webcam.js)
-   GPS location tracking (Leaflet.js)
-   Form validation & error handling

✅ **Admin Review System**

-   Pending attendance dashboard
-   Photo preview & verification
-   GPS coordinate verification (Google Maps link)
-   Approve/Reject functionality

✅ **Notification System**

-   Toast notifications for user feedback
-   Red alert on admin dashboard for pending items
-   Real-time status updates

✅ **Data Management**

-   Photo storage in `storage/app/public/attendances/`
-   Database persistence
-   Status tracking (pending → hadir/ditolak)

---

## 📁 Project Structure

```
app/Http/Controllers/
├── AttendanceController.php
│   ├── index()           - Office selection
│   ├── create()          - Attendance form
│   ├── store()           - Submit attendance ⭐
│   ├── history()         - View records
│   ├── adminPending()    - Admin review ⭐
│   ├── approve()         - Approve attendance ⭐
│   └── reject()          - Reject attendance ⭐

resources/views/attendances/
├── index.blade.php       - Office list
├── create.blade.php      - Attendance form (CAMERA + GPS)
├── history.blade.php     - View history
└── admin-pending.blade.php - Admin review (NEW)

database/migrations/
└── 2025_11_26_165050_create_attendances_table.php
    Columns: id, user_id, date, time_in, latlon_in,
             photo_in, status, timestamps

storage/app/public/attendances/
└── Photos uploaded by users
```

---

## 🔄 Complete Workflow

### **User Flow**

```
1. Login → Dashboard
2. Click "Tambah Absensi"
3. Select Office
4. Take Photo (Webcam)
5. Enable GPS Location
6. Submit Form
7. See Green Toast: "Absensi berhasil dikirim!"
8. Status: ⏳ PENDING (in dashboard)
```

### **Admin Flow**

```
1. Login → Dashboard
2. See RED ALERT: "X Absensi Menunggu Persetujuan"
3. Click "Lihat Sekarang ⚡"
4. Review Pending Attendance Table
   - Pegawai info
   - Photo thumbnail
   - GPS coordinates
5. Click "✅ Setujui" or "❌ Tolak"
6. See Green Toast: "Absensi berhasil disetujui!"
7. Status Updated: ✅ Hadir or ❌ Ditolak
```

---

## 🔧 Fixed Issues (v1.1.0)

**Problem:** User tidak melihat notif & admin tidak melihat data

**Root Cause:** Form HTML structure error - input `photo` berada di luar form element

**Solution:**

-   Pindahkan form element untuk membungkus SEMUA input fields
-   Input photo, latitude, longitude sekarang DALAM form

**Result:** ✅ Sistem notifikasi bekerja end-to-end

See `PERBAIKAN_RINGKAS.md` for detailed changes.

---

## 📊 Database Schema

```sql
CREATE TABLE attendances (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NOT NULL,          -- FK to users
    date DATE,                         -- Attendance date
    time_in TIME,                      -- Check-in time
    latlon_in VARCHAR(255),            -- GPS coordinates "lat,lng"
    photo_in VARCHAR(255),             -- Photo filename
    status ENUM('pending', 'hadir', 'ditolak') DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Status Values:**

-   `pending` ⏳ - Waiting for admin approval
-   `hadir` ✅ - Approved, marked present
-   `ditolak` ❌ - Rejected

---

## 🔐 Authorization

| User Type          | Permissions                                             |
| ------------------ | ------------------------------------------------------- |
| **Admin**          | View all attendance, approve/reject, access admin panel |
| **User (Anggota)** | Submit attendance, view own records                     |

---

## 📖 Documentation Files

1. **PERBAIKAN_RINGKAS.md** ⭐ START HERE

    - Ringkas masalah & solusi
    - Before vs After comparison

2. **WORKFLOW_SYSTEM.md**

    - Complete workflow documentation
    - Step-by-step guides
    - Flow diagrams

3. **TESTING_GUIDE.md**

    - Testing procedures
    - Troubleshooting
    - Common issues

4. **VERIFICATION_COMPLETE.md**

    - Complete verification checklist
    - Database state checks
    - Authorization verification

5. **QUICK_COMMANDS.md**
    - Quick reference commands
    - Testing flows
    - Debug commands

---

## 🧪 Testing

### **Quick Test (5 min)**

```bash
# User Submission
1. Login: budi@polres.com / 12345678
2. Tambah Absensi → Jepret foto → GPS → Submit
3. Expected: Green toast + Status ⏳ PENDING

# Admin Review
1. Logout, Login: admin@polres.com / 12345678
2. See RED ALERT on dashboard
3. Click "Lihat Sekarang ⚡"
4. Review & Approve/Reject
5. Expected: Green toast + Status ✅/❌
```

### **Database Verification**

```bash
php artisan tinker

# Check attendance
Attendance::latest()->first()

# Count by status
Attendance::where('status', 'pending')->count()
Attendance::where('status', 'hadir')->count()
Attendance::where('status', 'ditolak')->count()

exit
```

---

## 🛠️ Troubleshooting

| Issue                 | Solution                                |
| --------------------- | --------------------------------------- |
| Form not submitting   | Check photo + GPS filled, clear console |
| No toast notification | `php artisan view:clear`                |
| GPS permission denied | Browser settings → Reset location       |
| Photo not uploading   | `php artisan storage:link`              |
| Admin page empty      | Verify status='pending' in database     |

---

## 🚀 Routes

```
GET  /attendances                    - Office selection
GET  /attendances/create/{office}    - Attendance form
POST /attendances                    - Submit attendance
GET  /riwayat-absensi                - View history
GET  /admin/pending-absensi          - Admin review (admin only)
PATCH /attendances/{id}/approve      - Approve (admin only)
PATCH /attendances/{id}/reject       - Reject (admin only)
```

---

## 📱 Browser Support

-   Chrome ✅ (Recommended)
-   Firefox ✅
-   Safari ✅
-   Edge ✅

---

## ✅ Production Checklist

-   [x] Form structure fixed & working
-   [x] User submission with notifications
-   [x] Photo upload & storage
-   [x] GPS location tracking
-   [x] Admin review system
-   [x] Approval/Rejection workflow
-   [x] Database persistence
-   [x] Authorization checks
-   [x] Error handling
-   [x] Documentation complete

**Status: 🟢 PRODUCTION READY**

---

## 📞 Quick Reference

```bash
# Clear everything
php artisan optimize:clear

# Fresh database
php artisan migrate:fresh && php artisan db:seed

# View routes
php artisan route:list | Select-String "attendances"

# Check database
php artisan tinker

# View logs
tail -f storage/logs/laravel.log
```

---

## 📝 Technology Stack

-   **Framework:** Laravel 11
-   **Frontend:** Blade + Tailwind CSS
-   **Database:** MySQL 8.0+
-   **Libraries:**
    -   Webcam.js - Camera capture
    -   Leaflet.js - Maps & GPS
    -   Tailwind CSS - Styling

---

## 📄 License

Proprietary - Polres Garut

---

## 🎉 Status Summary

✅ **All Features Complete**
✅ **All Tests Passing**
✅ **Documentation Complete**
✅ **Ready for Production**

**Last Updated:** November 29, 2025  
**Version:** 1.1.0

---

**Start with:** `PERBAIKAN_RINGKAS.md` for complete overview of fixes!

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
