# Absensi Geolocation

Sistem absensi karyawan berbasis geolocation menggunakan PHP Native (versi 8.3.4), dengan antarmuka berbasis Tabler UI, serta pemanfaatan algoritma Haversine dan Leaflet.js untuk pemetaan.

## Fitur
- **Absensi berbasis Geolocation**: Mendeteksi lokasi pengguna saat melakukan absensi.
- **Validasi Radius**: Menggunakan algoritma Haversine untuk menentukan apakah karyawan berada dalam radius yang ditentukan.
- **Pemetaan dengan Leaflet.js**: Menampilkan lokasi karyawan dan area absensi di peta.
- **Dashboard Admin**: Mengelola data karyawan, absensi, dan laporan.
- **Antarmuka Modern**: Menggunakan Tabler UI untuk tampilan yang responsif dan menarik.

## Teknologi yang Digunakan
- **PHP Native** (versi 8.3.4)
- **Tabler UI** (untuk tampilan antarmuka)
- **Geolocation API** (untuk mendapatkan lokasi pengguna)
- **Haversine Formula** (untuk menghitung jarak antara dua titik koordinat)
- **Leaflet.js** (untuk menampilkan peta interaktif)

## Instalasi
1. **Clone repository**
   ```bash
   git clone https://github.com/akmlnajib/Absensi-Geolocation.git
   cd Absensi-Geolocation
   ```
2. **Pastikan PHP versi 8.3.4 terinstal**
3. **Jalankan server lokal**
   ```bash
   php -S localhost:8000
   ```
4. **Akses aplikasi** melalui browser di `http://localhost:8000`

## Konfigurasi
- **Konfigurasi koneksi database**:
  Edit file `config.php` dan sesuaikan dengan kredensial database Anda.

```php
$database_host = "localhost";
$database_username = "root";
$database_password = "";
$database_name = "absensi";
$conn = mysqli_connect($database_host, $database_username, $database_password, $database_name) or die ("Tidak terhubung kedatabase");
```

## Cara Penggunaan
1. **Login sebagai Admin atau Karyawan**
2. **Set Lokasi Absensi** (Admin menentukan lokasi kantor)
3. **Karyawan Melakukan Absensi** dengan menekan tombol "Absen" (sistem akan mendeteksi lokasi secara otomatis)
4. **Cek Laporan Absensi** melalui dashboard admin

## Lisensi
Proyek ini menggunakan lisensi [MIT](LICENSE).

---
Dikembangkan oleh [Akmal Najib](https://github.com/akmlnajib)

