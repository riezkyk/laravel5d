# FishMarket — Aplikasi Penjualan Ikan

FishMarket adalah web aplikasi manajemen dan sistem penjualan ikan berbasis **Laravel**, **Blade**, dan **Tailwind CSS**. Proyek ini dibuat untuk tugas mata kuliah *Pemrograman Berbasis Objek 2 (PBO2)* dengan fokus utama pada perancangan dan implementasi **relasi tabel database** menggunakan Eloquent ORM.

## Fitur Utama

- Pengelolaan katalog produk ikan yang terbagi dalam 8 kategori:
  Ikan Hias, Ikan Konsumsi, Ikan Laut, Ikan Tawar, Bibit & Benih Ikan, Pakan Ikan, Perlengkapan Akuarium, dan Obat & Nutrisi Ikan
- Pencatatan transaksi dan log penjualan harian produk ikan
- **Ulasan kepuasan pembeli** harian (skala 1–5)
- **Laporan harian toko** yang dapat dihubungkan dengan ulasan transaksi
- Pengingat restok produk (reminders) dengan opsi hari & jam khusus
- Tagging produk ikan untuk fleksibilitas pencarian (e.g. *segar*, *terlaris*, *diskon*, *grade_A*)
- Lencana pencapaian seller (achievements/badges)
- Dashboard rekap penjualan, produk terlaris, dan progress per kategori

## Desain Database

Skema lengkap database dan seluruh relasi tabel didokumentasikan dengan diagram Mermaid di
[`docs/database/erd.md`](docs/database/erd.md).

```mermaid
erDiagram
    USERS ||--o| PROFILES : "has one"
    USERS ||--o{ HABITS : "owns/sells"
    USERS ||--o{ MOOD_ENTRIES : "records sale"
    USERS ||--o{ JOURNAL_ENTRIES : "writes report"
    USERS }o--o{ ACHIEVEMENTS : "earns badge"
    CATEGORIES ||--o{ HABITS : "groups"
    HABITS ||--o{ HABIT_LOGS : "sales log"
    HABITS ||--o{ REMINDERS : "restock reminder"
    HABITS }o--o{ TAGS : "tagged with"
    MOOD_ENTRIES ||--o| JOURNAL_ENTRIES : "may have"
```

### Relasi Eloquent yang Diterapkan

| Tipe Relasi | Contoh Kasus pada Aplikasi Penjualan Ikan |
|---|---|
| One-to-One | `User` ↔ `Profile`, `MoodEntry` ↔ `JournalEntry` |
| One-to-Many | `User` → `Habit` (Produk Ikan), `Category` → `Habit`, `Habit` → `HabitLog` (Log Penjualan) |
| Many-to-Many | `Habit` (Produk Ikan) ↔ `Tag` |
| Many-to-Many dengan Pivot Data | `User` ↔ `Achievement` (`earned_at`) |
| Has-Many-Through | `User` → `HabitLog` melalui `Habit`, `Category` → `HabitLog` melalui `Habit` |

## Tech Stack

- PHP 8.3+ dan Laravel
- Blade templates
- Tailwind CSS (via Vite)
- MySQL / SQLite

## Cara Menjalankan Aplikasi

```bash
# Clone repositori
git clone https://github.com/riezkyk/laravel5d.git
cd laravel5d

# Install dependensi
composer install
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate

# Jalankan migrasi dan seeder data penjualan ikan
php artisan migrate --seed

# Jalankan server
npm run dev
php artisan serve
```

Akses aplikasi di <http://localhost:8000>.

## Progres Pengerjaan

Catatan progres per fase (P01, ...) dan job (J1, J2, ...) lengkap dengan bukti commit: [`docs/progress/P01-database-design.md`](docs/progress/P01-database-design.md).

## Struktur Proyek

```
app/Models/        Model Eloquent dan definisi relasi
database/
  migrations/      Definisi tabel database
  factories/       Generator data dummy penjualan ikan
  seeders/         Kategori dan sampel produk ikan
resources/views/   Template Blade
docs/
  database/        ERD dan dokumentasi relasi database Penjualan Ikan
  guides/          Panduan Git dan Pull Request
  progress/        Laporan progres per fase
```

## Author

Riezky Kurniawan — NPM 2410010564 — TI 5D REG BJB

## Lisensi

Dirilis di bawah [MIT License](https://opensource.org/licenses/MIT).
