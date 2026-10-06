# Database Design — Entity Relationship Diagram (FishMarket Penjualan Ikan)

Dokumen ini menjelaskan skema database aplikasi **FishMarket — Penjualan Ikan** dan setiap relasi Eloquent yang digunakan dalam proyek.

## 1. Entity Relationship Diagram

```mermaid
erDiagram
    USERS ||--o| PROFILES : "has one"
    USERS ||--o{ HABITS : "owns/sells (Fish Products)"
    USERS ||--o{ MOOD_ENTRIES : "records buyer review"
    USERS ||--o{ JOURNAL_ENTRIES : "writes daily sales report"
    USERS ||--o{ ACHIEVEMENT_USER : "earns badge"
    ACHIEVEMENTS ||--o{ ACHIEVEMENT_USER : "awarded via"

    CATEGORIES ||--o{ HABITS : "groups"
    HABITS ||--o{ HABIT_LOGS : "has sales log"
    HABITS ||--o{ REMINDERS : "has restock reminder"
    HABITS ||--o{ HABIT_TAG : "tagged via"
    TAGS ||--o{ HABIT_TAG : "labels"

    MOOD_ENTRIES ||--o| JOURNAL_ENTRIES : "may have"

    USERS {
        bigint id PK
        string name
        string email UK
        string password
    }
    PROFILES {
        bigint id PK
        bigint user_id FK "unique"
        text bio
        string avatar
        string timezone
        int daily_goal
    }
    CATEGORIES {
        bigint id PK
        string name
        string slug UK
        string icon
        string color
    }
    HABITS {
        bigint id PK
        bigint user_id FK
        bigint category_id FK
        string name "Produk Ikan"
        text description
        int target_count "Stok/Target Penjualan"
        string unit "Satuan (ekor, kg, paket)"
        boolean is_active
    }
    HABIT_LOGS {
        bigint id PK
        bigint habit_id FK
        date logged_date
        int value "Jumlah Terjual"
        text note
    }
    TAGS {
        bigint id PK
        string name UK "e.g. segar, terlaris, diskon"
    }
    HABIT_TAG {
        bigint habit_id FK
        bigint tag_id FK
    }
    REMINDERS {
        bigint id PK
        bigint habit_id FK
        time remind_at
        json days_of_week
        boolean is_enabled
    }
    MOOD_ENTRIES {
        bigint id PK
        bigint user_id FK
        date entry_date
        tinyint mood_level "1-5 (Tingkat Kepuasan Pembeli)"
        string note
    }
    JOURNAL_ENTRIES {
        bigint id PK
        bigint user_id FK
        bigint mood_entry_id FK "nullable"
        date entry_date
        string title "Judul Laporan Penjualan"
        text content "Catatan Penjualan"
    }
    ACHIEVEMENTS {
        bigint id PK
        string name
        text description
        string criteria
    }
    ACHIEVEMENT_USER {
        bigint user_id FK
        bigint achievement_id FK
        timestamp earned_at
    }
```

## 2. Ringkasan Relasi Eloquent

| Tipe Relasi | Pemetaan Objek Penjualan Ikan | Model Eloquent |
|---|---|---|
| One-to-One | Profil Penjual ↔ User | `User` ↔ `Profile` (`hasOne` / `belongsTo`) |
| One-to-One (optional) | Catatan Laporan Penjualan ↔ Ulasan Pembeli | `MoodEntry` ↔ `JournalEntry` (`hasOne` / `belongsTo`) |
| One-to-Many | Penjual → Katalog Produk Ikan | `User` → `Habit` (`hasMany` / `belongsTo`) |
| One-to-Many | Penjual → Ulasan Pembeli Harian | `User` → `MoodEntry` (`hasMany` / `belongsTo`) |
| One-to-Many | Penjual → Laporan Penjualan Harian | `User` → `JournalEntry` (`hasMany` / `belongsTo`) |
| One-to-Many | Kategori Ikan → Produk Ikan | `Category` → `Habit` (`hasMany` / `belongsTo`) |
| One-to-Many | Produk Ikan → Log Penjualan Harian | `Habit` → `HabitLog` (`hasMany` / `belongsTo`) |
| One-to-Many | Produk Ikan → Pengingat Restok Produk | `Habit` → `Reminder` (`hasMany` / `belongsTo`) |
| Many-to-Many | Produk Ikan ↔ Tag (*segar*, *diskon*, *terlaris*) | `Habit` ↔ `Tag` (pivot `habit_tag`, `belongsToMany`) |
| Many-to-Many + Pivot | Seller ↔ Badge Lencana Pencapaian (`earned_at`) | `User` ↔ `Achievement` (`belongsToMany` + `withPivot`) |
| Has-Many-Through | Seller → Total Log Penjualan melalui Produk Ikan | `User` → `HabitLog` through `Habit` (`hasManyThrough`) |
| Has-Many-Through | Kategori Ikan → Log Penjualan melalui Produk Ikan | `Category` → `HabitLog` through `Habit` (`hasManyThrough`) |

## 3. Kategori Penjualan Ikan (Seeded)

Tabel `categories` diisi oleh seeder dengan 8 kategori produk ikan:

| Nama Kategori | Slug | Icon |
|---|---|---|
| Ikan Hias | `ikan-hias` | 🐠 |
| Ikan Konsumsi | `ikan-konsumsi` | 🐟 |
| Ikan Laut | `ikan-laut` | 🌊 |
| Ikan Tawar | `ikan-tawar` | 💧 |
| Bibit & Benih Ikan | `bibit-benih-ikan` | 🌱 |
| Pakan Ikan | `pakan-ikan` | 📦 |
| Perlengkapan Akuarium | `perlengkapan-akuarium` | 🫧 |
| Obat & Nutrisi Ikan | `obat-nutrisi-ikan` | 💊 |
