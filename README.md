# Sistem Antrian

## Run On Your Local Machine

```
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

# Setup API Authentication from play.ht to your .env
# AUTHORIZATION='YOUR_SECRET_KEY'
# USER_ID='YOUR_USER_ID'

# Link storage to public folder (add 'FILESYSTEM_DISK=public' at your .env) and
php artisan storage:link

# Setup socket io
# add this to your .env files
# SOCKET_IO_SERVER='127.0.0.1:3000'

# Run APP
php artisan ser
npm run dev
node server.cjs
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
- [ ] Warna kolom di excel nya mengikuti warna di laporan  
- [ ] Print Otomatis
- [ ] Antrian Seragam display UI  
- [ ] Antrian Seragam List UI  
- [x] FE Bendahara  
- [x] Logout bendahara  
- [ ] Logout Seragam  
