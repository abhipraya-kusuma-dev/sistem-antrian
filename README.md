# Sistem Antrian

## Prerequisite

1. php 8.2
2. ffmpeg

## Run On Your Local Machine

```bash
# Clone Repo
git clone https://github.com/AdiCahyaSaputra/sistem-antrian
cd sistem-antrian

# Install Dependencies
composer install
npm install

# Setup Laravel .env
cp -r .env.example .env
php artisan key:generate

# Migration (Don't forget to fill the database credentials in .env first)
php artisan migrate:fresh --seed

# Link storage to public folder (add 'FILESYSTEM_DISK=public' at your .env) and
php artisan storage:link

# Install ffmpeg
choco install ffmpeg

# Move audio template
cp -r .\public\audio .\storage\app\public

# Setup socket io
# add this to your .env files
# SOCKET_IO_SERVER='127.0.0.1:3000'

# Run APP
php artisan ser
npm run dev
node server.cjs
```

### Run using `docker-compose.yaml`

```bash
# Clone Repo
git clone https://github.com/AdiCahyaSaputra/sistem-antrian
cd sistem-antrian

# Setup Laravel .env
cp -r .env.example .env

# Detonate the bomb
docker-compose up -d --build
```

### Set `.env`

```env
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=sistem_antrian
DB_USERNAME=adm-antrian-swk # based on docker-compose file
DB_PASSWORD=hehe1234 # based on docker-compose file

SOCKET_IO_SERVER='localhost:3000'

```

### PHP Arisan

```bash
docker exec -it sistem-antrian-be php artisan key:generate
docker exec -it sistem-antrian-be php artisan migrate:fresh --seed

# Un-symlink
docker exec -it sistem-antrian-be rm /var/www/public/storage

# Symlink
docker exec -it sistem-antrian-be ln -s /var/www/storage/app/public /var/www/public/storage

# Open http://localhost
```

## People Behind This Project

- [AdiCahyaSaputra](https://github.com/AdiCahyaSaputra)
- [MuhammadRisky](https://github.com/dante-heisenberg)

## Reference

- [API Access play.ht](https://play.ht/app/api-access)
- [API Docs play.ht](https://docs.play.ht/reference/api-getting-started)
- [Error Tracking (sentry)](https://docs.sentry.io/platforms/php/guides/laravel/)

## Todo

- [x] Text To Speech API
- [x] Daftar Antrian
- [x] Kelola Antrian (admin/op)
- [x] Kelola Antrian Bendahara (admin/op)
- [x] Display Antrian
- [x] Laporan per hari
- [x] Pisah menu berdasarkan kategori "terpanggil" (kelola antrian)
- [x] Real time card antrian
- [x] Suara pada display antrean
- [x] Warna card antrian saat ini
- [x] tampilkan jumlah antrian disebelah tombol daftar
- [x] Regenerate ulang card antrian display setelah pendaftaran berhasil
- [x] Optimasi Daftar Antrian
- [x] Antrian berhasil date
- [x] Antrian Berhasil kembali ke menu awal setelah beberapa detik
- [x] Antrian Seragam daftar dari bendahara (tanpa print)
- [x] Antrian Seragam display
- [x] Antrian Seragam List
- [x] Antrian Bendahara ada kutipan kecil dari antrian operator sebelumnya
- [x] Antrian Bendahara terkoneksi dengan antrian sebelumnya
- [x] Bug seragam di display /antrian
- [x] Jumlah antre filter yang belum nya aja
- [x] Antrian Seragam display and list SocketIO
- [x] Pagination list antrian
- [x] Daftar Antrian Card UI dibikin kotak kotak
- [x] Akun operator ada 5
- [x] Middleware operator
- [x] Export ke excel dan pdf per minggu
- [x] FE Bendahara
- [x] Logout bendahara
- [x] Ngasih tau user nomor antrian dia saat lanjut ke bendahara dan seragam
- [x] Antrian Seragam display UI
- [x] Antrian Seragam List UI
- [x] Logout Seragam
- [x] Data di Antrian Seragam
- [ ] Warna kolom di excel nya mengikuti warna di laporan
- [ ] Print Otomatis

- [ ] Rekaman angka 0-9 dan "Nomor antrian ... menuju loket (x)" secara manual
