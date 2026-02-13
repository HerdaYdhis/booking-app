# 📅 BookingApp — Aplikasi Booking PHP 8

Aplikasi booking online dengan tampilan premium, dark mode, glassmorphism, dan animasi modern.

## 📁 Struktur Folder

```
booking-app/
├── index.php              → Halaman utama (Landing Page)
├── booking.php            → Formulir booking
├── process_booking.php    → Proses submit booking (POST)
├── booking_success.php    → Halaman sukses setelah booking
├── my-bookings.php        → Daftar semua booking
├── .htaccess              → Konfigurasi Apache (security)
│
├── config/
│   └── database.php       → Koneksi database (PDO)
│
├── database/
│   └── schema.sql         → Skema database + seed data
│
├── includes/
│   ├── header.php         → Template header & navbar
│   └── footer.php         → Template footer
│
├── css/
│   └── style.css          → Seluruh styling (CSS murni)
│
├── js/
│   └── app.js             → Logika frontend (validasi, animasi)
│
└── assets/                → Folder untuk gambar/aset
```

## 🚀 Cara Menjalankan

### Prasyarat
- **PHP 8.0+** (disarankan PHP 8.1+)
- **MySQL 5.7+** atau **MariaDB 10.3+**
- **Apache** dengan `mod_rewrite` aktif
- Atau cukup install **XAMPP / Laragon / WAMP**

### Langkah-langkah

1. **Copy folder `booking-app`** ke document root web server Anda:
   - XAMPP: `C:\xampp\htdocs\booking-app\`
   - Laragon: `C:\laragon\www\booking-app\`

2. **Buat database**, jalankan file SQL:
   ```
   Buka phpMyAdmin → Tab SQL → Copy-paste isi file database/schema.sql → Klik Go
   ```
   Atau via terminal:
   ```bash
   mysql -u root -p < database/schema.sql
   ```

3. **Sesuaikan konfigurasi database** di `config/database.php` jika diperlukan:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_PORT', '3306');
   define('DB_NAME', 'booking_app');
   define('DB_USER', 'root');
   define('DB_PASS', '');          // Password MySQL Anda
   ```

4. **Akses aplikasi** di browser:
   ```
   http://localhost/booking-app/
   ```

## ✨ Fitur
- 🎨 **Tampilan Premium** — Dark mode, glassmorphism, gradient, animasi smooth
- 📱 **Responsive** — Optimal di desktop, tablet, dan mobile
- 📝 **Form Booking** — Validasi client-side & server-side
- 🔍 **Cari Booking** — Filter booking berdasarkan kode/nama
- ❌ **Batalkan Booking** — Pembatalan booking dengan konfirmasi
- 🔒 **Keamanan** — PDO prepared statements, XSS protection, .htaccess security
- 🗃️ **Database Relasional** — Tabel services, bookings, time_slots dengan foreign keys

## 🛠 Teknologi
- PHP 8 (PDO, strict typing)
- MySQL / MariaDB
- HTML5 Semantik
- CSS3 (Custom Properties, Grid, Flexbox, Animations)
- Vanilla JavaScript (ES6+)
- Bootstrap Icons (CDN)
- Google Fonts (Inter, Playfair Display)
