# SISFO AKADEMIK LAILI

Sistem Informasi Akademik sekolah berbasis **Laravel** (template SB Admin 2) dengan database **MySQL**.

Fitur: login admin, dashboard, CRUD Data Siswa (+ detail), Data Guru, Data Kelas, Data Mata Pelajaran,
Nilai Akademik (Tugas 30% + UTS 30% + UAS 40%, status Lulus/Tidak Lulus berdasarkan KKM mapel), dan pencarian.

## Kebutuhan
- **PHP 8.4 atau lebih baru** (folder `vendor/` project ini memerlukan PHP >= 8.4.1) dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`
- MySQL / MariaDB (XAMPP, Laragon, dll.)
- Koneksi internet saat membuka website (Bootstrap JS, Font Awesome, dan font dimuat dari CDN)
- Folder `vendor/` sudah disertakan, jadi `composer install` tidak wajib.

## Cara Menjalankan

### 1. Siapkan database (pilih salah satu)
**A. Impor file SQL** (paling mudah)
1. Nyalakan MySQL.
2. Buka phpMyAdmin → tab **Import** → pilih `database/sisfo.sql` → **Go**
   (file ini membuat database `sisfo`, semua tabel, dan data contoh). Atau lewat terminal:
   `mysql -u root < database/sisfo.sql`

**B. Lewat migration Laravel**
1. Buat database kosong bernama `sisfo`.
2. Jalankan: `php artisan migrate:fresh --seed`

### 2. Cek konfigurasi `.env`
Sesuaikan jika perlu (default: user `root`, tanpa password, database `sisfo`):
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sisfo
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Jalankan website
```
php artisan serve
```
Buka http://localhost:8000 → Anda akan diarahkan ke halaman login.

## Akun Admin
| Email | Password |
|---|---|
| `admin@admin.com` | `123456` |

Akun ini dibuat oleh seeder / file SQL (password disimpan sebagai hash bcrypt). **Segera ganti** untuk penggunaan nyata.
Untuk membuat admin baru atau mereset password:
```
php artisan admin:create
```
Pendaftaran akun publik dan reset password via email sengaja dinonaktifkan; hanya admin yang bisa masuk.

## Mengganti Nama Project
Nama tampil (judul tab, sidebar, halaman login, footer) diambil dari satu tempat: `APP_NAME` di file `.env`
(dan `.env.example`):
```
APP_NAME="SISFO AKADEMIK LAILI"
```
Ganti `LAILI` dengan nama lain bila perlu (awalan `SISFO AKADEMIK` dipertahankan agar sidebar tetap sesuai desain).
Setelah itu, jalankan `php artisan config:clear` bila sebelumnya konfigurasi pernah di-cache.

## Pengujian Otomatis
```
php artisan test
```
Menguji login/logout, proteksi halaman, CRUD semua modul, validasi, perhitungan nilai, dan pencarian (memakai database SQLite sementara di memori, tidak menyentuh database MySQL Anda).

## Struktur Singkat
- `routes/web.php` – route login + semua modul (dilindungi middleware `auth`)
- `app/Http/Controllers/` – logika CRUD tiap modul
- `app/Models/` – Siswa, Guru, Kelas, Mapel, Nilai, User
- `resources/views/` – tampilan (`layouts/`, `siswa/`, `guru/`, `kelas/`, `mapel/`, `nilai/`, `auth/`)
- `database/migrations/`, `database/seeders/`, `database/sisfo.sql` – struktur & data database
