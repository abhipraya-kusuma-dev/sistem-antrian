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

# Link storage to public folder (add 'FILESYSTEM_DISK=public' at your .env) and
php artisan storage:link

# Run APP
php artisan ser
npm run dev
```

## Todo
- [x] Text To Speech API  
- [x] Daftar Antrian  
- [x] Kelola Antrian (admin)  
- [ ] Display Antrian  
- [ ] Laporan per hari    
