# Foodie REST API Documentation

Backend REST API untuk aplikasi **Foodie (ReactJS)** yang dibangun menggunakan **Laravel 12**, **MySQL (`foodie_db`)**, dan **Laravel CORS**.

---

## 🚀 Base URL
```
http://localhost/foodie-backend/public/api
```

---

## 📋 Daftar Endpoint API

### 1. Categories (Kategori Menu)
- **`GET /api/categories`**
  - Mengambil daftar kategori menu beserta icon Lucide.
- **`POST /api/categories`**
  - Menambah kategori baru (`name`, `slug`, `icon`).

### 2. Foods (Menu Makanan & Minuman)
- **`GET /api/foods`**
  - Query Parameters:
    - `category`: Filter berdasarkan kategori (`Burgers`, `Pizza`, `Chicken`, `Pasta`, `Asian`, `Drinks`, `Desserts`, atau `all`)
    - `search`: Pencarian kata kunci (nama makanan atau deskripsi)
    - `popular`: `true` / `false` (hanya menu terpopuler)
    - `sort`: `popular`, `rating`, `price-asc`, `price-desc`
- **`GET /api/foods/{id}`**
  - Mengambil detail menu makanan beserta 3 menu terkait (*related menu*).
- **`POST /api/foods`**
  - Menambah menu makanan baru.

### 3. Restaurants (Restoran Mitra Pilihan)
- **`GET /api/restaurants`**
  - Mengambil daftar resto mitra, rating, jarak, dan estimasi waktu pengantaran.

### 4. Orders (Checkout & Pesanan Pelanggan)
- **`POST /api/orders`**
  - Mengirim pesanan saat checkout dari ReactJS.
  - Request Body:
    ```json
    {
      "customer_name": "Budi Santoso",
      "customer_phone": "081234567890",
      "customer_address": "Jl. Sudirman No. 45, Jakarta Selatan",
      "notes": "Pagar hitam, tolong bel bila sampai.",
      "delivery_type": "standard", // standard | express
      "payment_method": "gopay",   // gopay | ovo | shopeepay | bca | cod
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
- **`GET /api/orders`**
  - Mengambil seluruh riwayat pesanan pelanggan.
- **`GET /api/orders/{orderId}`**
  - Mengambil detail pesanan berdasarkan ID nota (misal: `FD-262690`).

---

## 🛠️ Informasi Database & Server
- **Database**: `foodie_db` (MySQL Port 3306)
- **Seeder**: Sudah terisi **18 menu lengkap**, **8 kategori**, dan **3 restoran mitra** yang 100% cocok dengan frontend ReactJS Foodie.
- **CORS**: Sudah diaktifkan untuk `allowed_origins: ['*']` sehingga aman dipanggil dari `http://localhost:5173` (Vite React).
