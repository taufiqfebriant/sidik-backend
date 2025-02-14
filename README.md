Ah, I see! Here's the revised README.md without any frontend-related terms:

---

# Backend Laravel 11

Ini adalah backend menggunakan **Laravel 11** dan database **SQLite**.

## Cara Menjalankan di Lokal

### 1. Silahkan clone repo ini

### 2. Install Dependency NPM
```sh
npm install
```

### 3. Install Dependency Backend
```sh
composer install
```

### 4. Salin File `.env`
```sh
cp .env.example .env
```

### 5. Generate Key
```sh
php artisan key:generate
```

### 6. Jalankan Migrasi
```sh
php artisan migrate --seed
```
> Catatan: Seeder akan membuat akun admin default.

### 7. Jalankan Server
```sh
composer run dev
```
Backend akan berjalan di `http://127.0.0.1:8000`.

## Akun Admin Default
- **Email:** `admin@example.com`
- **Password:** `12345678`

## Catatan
- Pastikan PHP, Composer, dan Node.js sudah terinstall.
- Gunakan **Postman** atau **cURL** untuk menguji API.