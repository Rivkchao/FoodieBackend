# Foodie Backend REST API 🍔🍕🚀

Backend REST API untuk aplikasi pemesanan makanan **Foodie** yang dibangun menggunakan framework **Laravel 12**, basis data **MySQL (`foodie_db`)**, dan terintegrasi dengan payment gateway **Midtrans**. Repositori ini berfungsi sebagai server data dan pemrosesan pesanan yang terhubung langsung dengan Frontend client pada repositori:
👉 **[Foodie Frontend Repository](https://github.com/Rivkchao/FoodieFrontend)**

---

## 🏗️ Arsitektur Sistem & Hubungan dengan Frontend

```
+-------------------------------------------------------+
|                 FRONTEND (foodie)                     |
|        React 19 + Vite 8 + Tailwind CSS + Zustand     |
|              http://localhost:5173                    |
+---------------------------+---------------------------+
                            |
                     REST API (JSON)
                            |
+---------------------------v---------------------------+
|              BACKEND (foodiebackend)                  |
|           Laravel 12 + Laravel CORS + Midtrans        |
|              http://localhost:8000                    |
+---------------------------+---------------------------+
                            |
+---------------------------v---------------------------+
|                     DATABASE                          |
|             MySQL / MariaDB (`foodie_db`)             |
|              File: rivkypsm_foodie.sql                |
+-------------------------------------------------------+
```

Kedua repositori ini saling terintegrasi:
- **`foodiebackend` (Backend - Repositori ini)**: Menyediakan REST API untuk data kategori, menu makanan, restoran mitra, pemrosesan order, webhook Midtrans, dan basis data MySQL.
- **[Foodie Frontend (`foodie`) 🔗](https://github.com/Rivkchao/FoodieFrontend)**: Antarmuka pengguna berbasis React 19 untuk melihat katalog makanan, keranjang belanja, proses checkout, dan pelacakan pesanan.

---

## ✨ Fitur & Layanan Backend

1. **Kategori Menu (`/api/categories`)**:
   - Pengelolaan kategori makanan (*Burgers, Pizza, Chicken, Pasta, Asian, Drinks, Desserts*).
2. **Katalog Makanan & Minuman (`/api/foods`)**:
   - Mendukung filter berdasarkan kategori, pencarian teks (*search*), menu terpopuler (*popular*), dan pengurutan harga/rating (*sorting*).
   - Pengambilan detail 1 makanan beserta rekomendasi 3 menu terkait.
3. **Restoran Mitra (`/api/restaurants`)**:
   - Informasi resto mitra, rating, jarak, dan estimasi waktu pengantaran.
4. **Checkout & Pemrosesan Pesanan (`/api/orders`)**:
   - Penerimaan transaksi pesanan dari frontend.
   - Perhitungan otomatis subtotal, biaya pengantaran (*standard/express*), potongan voucher, dan total pembayaran.
   - Integrasi pembuatan Snap Token pembayaran online **Midtrans**.
   - Pelunasan pesanan metode tunai (**COD**).
5. **Pelacakan Status Pesanan (`/api/orders/{orderId}`)**:
   - Mengambil status real-time pesanan (*pending, processing, cooking, delivering, completed, cancelled*).
   - Endpoint cetak nota/struk digital pesanan.
6. **Voucher Diskon (`/api/vouchers`)**:
   - Validasi dan kalkulasi potongan harga voucher promo.
7. **Webhook Midtrans Callback (`/api/midtrans-callback` & `/midtrans/callback`)**:
   - Menerima notifikasi otomatis status pembayaran dari server Midtrans.

---

## 🛠️ Tech Stack Backend

- **Framework**: [Laravel 12](https://laravel.com/) (PHP 8.2+)
- **Database**: MySQL / MariaDB (`foodie_db`)
- **Payment Gateway**: [Midtrans PHP SDK](https://github.com/Midtrans/midtrans-php)
- **CORS Handling**: Laravel CORS (`allowed_origins: ['*']`)

---

## 🚀 Panduan Setup & Menjalankan Backend (`foodiebackend`)

### 1. Prasyarat Sistem
Pastikan perangkat Anda sudah terinstal:
- **PHP** `>= 8.2` (dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `json`, `bcmath`, `fileinfo`)
- **Composer** (v2+)
- **MySQL / MariaDB Server** (via XAMPP, Laragon, Docker, atau native)

---

### 2. Masuk ke Folder Backend
```bash
cd "C:\Users\MyBook Hype AMD\Downloads\foodiebackend"
```

---

### 3. Install Dependensi PHP (Composer)
```bash
composer install
```

---

### 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env` (jika belum ada):

**Windows (PowerShell / Command Prompt):**
```bash
copy .env.example .env
```
**Linux / macOS:**
```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan koneksi database lokal serta kredensial Midtrans Anda:

```env
APP_NAME="Foodie Backend API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_FRONTEND_URL=http://localhost:5173

# Konfigurasi Database Lokal
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodie_db
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi Midtrans Payment Gateway (Sandbox)
MIDTRANS_MERCHANT_ID=your-merchant-id
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

Generate application key Laravel:
```bash
php artisan key:generate
```

---

### 5. Setup Database & Import File SQL

File dump SQL lengkap sudah tersedia langsung di dalam folder ini: **`rivkypsm_foodie.sql`** (berisi tabel kategori, 18 menu makanan lengkap, restoran mitra, dan skema pesanan).

#### Cara A: Import via Command Line (CLI)
1. Buat database baru di MySQL:
   ```sql
   CREATE DATABASE foodie_db;
   ```
2. Import file `rivkypsm_foodie.sql`:
   ```bash
   mysql -u root -p foodie_db < rivkypsm_foodie.sql
   ```

#### Cara B: Import via phpMyAdmin
1. Buka `http://localhost/phpmyadmin`.
2. Buat database baru bernama `foodie_db`.
3. Klik tab **Import**, pilih file **`rivkypsm_foodie.sql`**, lalu klik **Kirim / Import**.

> 💡 **Opsi Alternatif (Menggunakan Migrations & Seeders Laravel):**
> Jika Anda ingin me-reset tabel dan mengisi ulang data seeder dari kode Laravel:
> ```bash
> php artisan migrate:fresh --seed
> ```

---

### 6. Jalankan Server Backend
```bash
php artisan serve
```

Server backend Laravel akan aktif di:
👉 **`http://localhost:8000`**

Endpoint API dapat diakses di:
👉 **`http://localhost:8000/api/...`**

---

## 📋 Daftar Lengkap Endpoint REST API

### 1. Categories
- **`GET /api/categories`**: Mengambil daftar kategori menu.

### 2. Foods
- **`GET /api/foods`**: Mengambil daftar makanan.
  - Parameter: `category` (slug kategori), `search` (kata kunci), `popular` (true), `sort` (`popular|rating|price-asc|price-desc`).
- **`GET /api/foods/{id}`**: Mengambil detail menu makanan dan 3 menu terkait.

### 3. Restaurants
- **`GET /api/restaurants`**: Mengambil daftar restoran mitra.

### 4. Orders
- **`POST /api/orders`**: Mengirim pesanan baru saat checkout dari ReactJS.
  ```json
  {
    "customer_name": "Budi Santoso",
    "customer_phone": "081234567890",
    "customer_address": "Jl. Sudirman No. 45, Jakarta",
    "notes": "Pagar hitam, tolong hubungi sebelum sampai.",
    "delivery_type": "standard",
    "payment_method": "midtrans",
    "items": [
      {
        "id": 1,
        "name": "Truffle Mushroom Burger",
        "price": 68000,
        "quantity": 2
      }
    ]
  }
  ```
- **`GET /api/orders/{orderId}`**: Mengambil status & rincian pesanan.
- **`POST /api/orders/{orderId}/complete-midtrans`**: Konfirmasi callback pelunasan Midtrans.
- **`POST /api/orders/{orderId}/mark-as-paid`**: Konfirmasi pembayaran COD/Tunai.
- **`GET /orders/{orderId}/receipt`**: Tampilan cetak nota struk pesanan.

### 5. Vouchers
- **`GET /api/vouchers`**: Mengambil daftar voucher aktif.
- **`POST /api/vouchers/apply`**: Memvalidasi kode voucher dan menghitung potongan diskon.

---

## 📄 Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).
