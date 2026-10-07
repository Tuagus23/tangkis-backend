# Tangkis Backend (Staging)

Backend REST API untuk aplikasi Android Tangkis. Backend ini memakai Laravel 12 dan MySQL. Model klasifikasi tetap berjalan di perangkat Android; backend menyimpan hasil deteksi agar beberapa perangkat dapat memakai satu sumber data yang sama.

## Target arsitektur

```text
Android Tangkis
  -> HTTPS REST API
  -> Laravel 12
  -> MySQL
```

## Fitur tahap 1

- `GET /api/v1/health` — health check tanpa query data aplikasi.
- `POST /api/v1/detections` — menyimpan hasil klasifikasi.
- `GET /api/v1/detections` — mengambil riwayat deteksi bersama.
- `GET /api/v1/stats` — rekap jumlah deteksi per kategori.
- Filter riwayat dengan `?category=PENIPUAN|PROMO|NORMAL`.

> Tahap ini sengaja belum memakai login/token agar integrasi server dapat diuji dulu. Sebelum production atau data sensitif digunakan, tambahkan autentikasi (misalnya Sanctum), authorization, rate limiting yang lebih ketat, dan kebijakan retensi data.

## Menjalankan dari Windows

Folder ini adalah starter/overlay, bukan vendor Laravel lengkap. Jalankan `setup_backend.ps1` dari PowerShell setelah memastikan PHP >= 8.2 dan Composer tersedia.

```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
.\\setup_backend.ps1
```

Script akan:
1. membuat Laravel 12 baru di folder sementara;
2. memasang API routes;
3. menyalin file backend Tangkis;
4. menyiapkan `.env.example` dan README hasil setup.

Setelah selesai:

```powershell
php artisan key:generate
php artisan migrate
php artisan serve
```

Health check:

```text
http://127.0.0.1:8000/api/v1/health
```

## Database lokal

Untuk pengembangan lokal, MySQL dapat diarahkan ke database `tangkis` melalui `.env`.

## Railway staging

Railway dapat deploy langsung dari repository GitHub dan membuat public domain dari Settings -> Networking -> Generate Domain. Railway juga menyediakan service MySQL dan environment variables koneksi untuk service lain di project yang sama. Referensi service variable Railway yang umum untuk MySQL: `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE`, dan `MYSQL_URL`.

Dokumentasi resmi: https://docs.railway.com/guides/laravel

## Variabel Railway yang disarankan

Set pada service Laravel:

```env
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://YOUR-RAILWAY-DOMAIN
LOG_CHANNEL=stderr

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

Nama service `MySQL` di atas harus disesuaikan dengan nama service database kamu di Railway.

## Git

Setelah script selesai, backend yang dihasilkan bisa dijadikan repository terpisah, misalnya:

```text
https://github.com/<akun>/tangkis-backend
```
