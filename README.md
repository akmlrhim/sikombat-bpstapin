## Persyaratan Sistem

- PHP >= 8.2 (Dianjurkan PHP 8.3 karena server lokal sebelumnya menggunakan 8.3.9)
- MySQL / MariaDB
- Composer

---

## 1. Instalasi di Komputer Lokal (Development)

Berikut adalah langkah-langkah untuk menjalankan aplikasi secara lokal di komputer (menggunakan Laragon / XAMPP):

1. **Clone/Download** repository/file proyek ini ke folder server lokal Anda (misal: `C:\laragon\www\sikombat-bpstapin`).
2. Buka Terminal/CMD, lalu arahkan direktori ke dalam folder proyek:
   ```bash
   cd e:\laragon\www\sikombat-bpstapin
   ```
3. Jalankan perintah untuk menginstal dependensi PHP:
   ```bash
   composer install
   ```
4. Salin file konfigurasi lingkungan:
   ```bash
   cp .env.example .env
   ```
   *(Atau Anda bisa *copy-paste* file `.env.example` secara manual dan mengubah nama salinannya menjadi `.env`)*
5. Hasilkan kunci aplikasi Laravel:
   ```bash
   php artisan key:generate
   ```
6. Buat database baru di MySQL (misalnya bernama `sikombat_bpstapin`).
7. **Import file database:**
   Buka phpMyAdmin di lokal, pilih database `sikombat_bpstapin`, lalu **Import** file `sikombat_bpstapin.sql` yang ada di direktori root proyek.
8. Buka file `.env` di teks editor, dan sesuaikan konfigurasi database-nya:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sikombat_bpstapin
   DB_USERNAME=root
   DB_PASSWORD=
   ```
9. Buat link untuk storage (agar file gambar atau _upload_ lainnya bisa diakses publik):
   ```bash
   php artisan storage:link
   ```
10. Jika tidak menggunakan Laragon (di mana Anda harus mengakses `http://sikombat-bpstapin.test`), Anda dapat menyalakan server bawaan Laravel:
    ```bash
    php artisan serve
    ```
    Buka `http://localhost:8000` di browser untuk mengakses aplikasi.

---

## 2. Deployment ke Hosting (Hostinger / Rumahweb)

Panduan ini berasumsi Anda menggunakan paket _Shared Hosting_ yang menyediakan panel kontrol seperti hPanel (Hostinger) atau cPanel (Rumahweb).

### Langkah Persiapan di Lokal

1. Pastikan project di lokal sudah berjalan dengan baik.
2. Kompres (Zip) **seluruh** isi folder proyek ini (misalnya menjadi `sikombat.zip`).

### Langkah Konfigurasi di Hosting

#### A. Upload File & Direktori

1. Login ke Control Panel hosting.
2. Buka menu **File Manager**.
3. Di _root directory_ (biasanya sejajar / satu tingkat di atas `public_html`), buat folder baru, misalnya bernama `sikombat_app`.
   _(Ilustrasi path: `/home/username/sikombat_app` dan `/home/username/public_html`)_
4. Upload file `sikombat.zip` ke dalam folder `sikombat_app` tersebut.
5. Klik kanan pada file zip tersebut dan pilih **Extract**.

#### B. Menyiapkan Folder Public

Agar website lebih aman, kita memisahkan sistem utama Laravel dengan _public core_-nya.

1. Masuk ke folder `sikombat_app/public`.
2. Pilih semua file dan folder di dalamnya (seperti `index.php`, `.htaccess`, direktori `build`, `assets`, dll).
3. **Pindahkan (Move)** semua file tersebut ke dalam folder `public_html` hosting Anda.
4. Pergi ke dalam folder `public_html`, lalu Edit file `index.php`.
5. Ubah dua baris direktori berikut agar mengarah ke folder instalasi aplikasi kita:

   Cari baris ini:

   ```php
   require __DIR__.'/../vendor/autoload.php';
   ```

   Ubah menjadi:

   ```php
   require __DIR__.'/../sikombat_app/vendor/autoload.php';
   ```

   Lalu cari baris ini:

   ```php
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

   Ubah menjadi:

   ```php
   $app = require_once __DIR__.'/../sikombat_app/bootstrap/app.php';
   ```

   Simpan file tersebut.

#### C. Setup Database

1. Buka menu **MySQL Databases** di control panel hosting Anda.
2. Buat database baru (contoh: `u12345_sikombat`) dan buat pengguna (User) baru beserta kata sandinya.
3. Hubungkan _User_ tersebut ke _Database_ dan berikan semua hak akses (_All Privileges_).
4. Buka **phpMyAdmin** dari control panel hosting.
5. Pilih database yang baru Anda buat, pilih menu **Import**, lalu unggah file `sikombat_bpstapin.sql`.

#### D. Konfigurasi .env

1. Kembali ke File Manager, masuk ke dalam folder `sikombat_app`.
2. Edit file `.env`. Ubah nilai-nilainya ke _mode production_ dan sesuaikan dengan database hosting:

   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domainanda.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=u12345_sikombat
   DB_USERNAME=u12345_user
   DB_PASSWORD=password_db_anda
   ```

   Simpan file tersebut.

#### E. Storage Link (Penting)

Agar gambar atau file dokumen yang diunggah dapat diakses dari browser, Anda perlu menautkan folder _storage_ ke _public_html_. Karena _shared hosting_ terkadang tidak memiliki akses Terminal SSH, kita bisa melakukannya via Route.

1. Buka kembali file `routes/web.php` di dalam `sikombat_app/routes/`.
2. Tambahkan kode sementara ini di bagian paling bawah:
   ```php
   Route::get('/buat-symlink', function () {
       $targetFolder = base_path().'/storage/app/public';
       $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';
       symlink($targetFolder, $linkFolder);
       return 'Symlink berhasil dibuat!';
   });
   ```
3. Buka browser dan akses halaman tersebut: `https://domainanda.com/buat-symlink`
4. Jika berhasil dan tertulis "Symlink berhasil dibuat!", **kembali ke `routes/web.php` dan hapus kode route tersebut** demi keamanan.

#### F. Sesuaikan Versi PHP

Buka menu **Select PHP Version** atau **PHP Configuration** di Control Panel Anda. Pastikan versi PHP yang aktif disetel minimal ke versi **8.2** atau **8.3**.

### Selesai 🎉

Sekarang aplikasi Anda sudah _live_ dan dapat diakses dengan membuka domain utama Anda di browser.
