# 🚀 SmartLab - Dynamic Landing Page Implementation

## 📋 Table of Contents
1. [Overview](#overview)
2. [File Structure](#file-structure)
3. [Installation Steps](#installation-steps)
4. [Filament Resources](#filament-resources)
5. [Usage Guide](#usage-guide)
6. [API Documentation](#api-documentation)
7. [Troubleshooting](#troubleshooting)

---

## Overview

Landing page dinamis SmartLab telah berhasil diimplementasi menggunakan Filament Resources. Sistem ini memungkinkan Anda untuk mengelola semua konten landing page melalui admin panel Filament tanpa perlu mengedit kode.

### ✅ Fitur yang Sudah Diimplementasi:
- **Gallery Images Management** - Upload dan atur posisi gambar
- **Announcements System** - Kelola pengumuman dengan berbagai jenis
- **Page Settings** - Konfigurasi teks, logo, dan pengaturan umum
- **Feature Cards** - Kartu fitur dengan gradient dan icon custom
- **Statistics Display** - Statistik dengan gradient dan suffix
- **Dynamic Content** - Semua konten dapat diubah melalui admin panel

---

## File Structure

```
smartlab/
├── app/
│   ├── Filament/Resources/
│   │   ├── GalleryImageResource.php
│   │   ├── GalleryImageResource/Pages/
│   │   │   ├── ListGalleryImages.php
│   │   │   ├── CreateGalleryImage.php
│   │   │   └── EditGalleryImage.php
│   │   ├── AnnouncementResource.php
│   │   ├── AnnouncementResource/Pages/
│   │   │   ├── ListAnnouncements.php
│   │   │   ├── CreateAnnouncement.php
│   │   │   └── EditAnnouncement.php
│   │   ├── LandingPageSettingResource.php
│   │   ├── LandingPageSettingResource/Pages/
│   │   │   ├── ListLandingPageSettings.php
│   │   │   ├── CreateLandingPageSetting.php
│   │   │   └── EditLandingPageSetting.php
│   │   ├── FeatureCardResource.php
│   │   ├── FeatureCardResource/Pages/
│   │   │   ├── ListFeatureCards.php
│   │   │   ├── CreateFeatureCard.php
│   │   │   └── EditFeatureCard.php
│   │   ├── StatisticResource.php
│   │   └── StatisticResource/Pages/
│   │       ├── ListStatistics.php
│   │       ├── CreateStatistic.php
│   │       └── EditStatistic.php
│   ├── Http/Controllers/
│   │   └── LandingPageController.php (Updated)
│   └── Models/ (Already exist)
│       ├── GalleryImage.php
│       ├── Announcement.php
│       ├── LandingPageSetting.php
│       ├── FeatureCard.php
│       └── Statistic.php
├── database/
│   ├── migrations/ (Already exist)
│   └── seeders/
│       └── LandingPageSeeder.php (New)
└── resources/views/
    └── layouts/
        └── authentication.blade.php (Updated)
```

---

## Installation Steps

### 1. Run Migrations
Pastikan semua migration sudah dijalankan:
```bash
php artisan migrate
```

### 2. Seed Default Data
Jalankan seeder untuk data default:
```bash
php artisan db:seed --class=LandingPageSeeder
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 4. Create Storage Link
```bash
php artisan storage:link
```

### 5. Access Admin Panel
```
http://localhost/admin
```

---

## Filament Resources

### 🖼️ Gallery Images Resource
**Path:** `Admin Panel > Landing Page > Gallery Images`

**Features:**
- Upload gambar dengan editor built-in
- Set posisi: Hero, Top Right, Middle Right, Bottom Left/Center/Right
- Badge text dan color untuk label
- Overlay gradient untuk hover effect
- Carousel support untuk auto-rotate
- Sort order untuk mengatur urutan

**Form Fields:**
- `file_path` - Upload gambar
- `title` - Judul gambar
- `alt_text` - Alt text untuk accessibility
- `caption` - Caption gambar
- `description` - Deskripsi lengkap
- `position` - Posisi di grid layout
- `sort_order` - Urutan tampil
- `is_active` - Status aktif
- `is_carousel` - Tampil di carousel
- `badge_text` - Teks badge
- `badge_color` - Warna badge
- `overlay_gradient` - Gradient hover

### 📢 Announcements Resource
**Path:** `Admin Panel > Landing Page > Announcements`

**Features:**
- Multiple announcement types (info, success, warning, error)
- Position control (top, bottom)
- Page targeting (home, login, register, dashboard)
- Date range (start/end date)
- Dismissible dan sticky options
- Priority system

**Form Fields:**
- `title` - Judul pengumuman
- `message` - Isi pesan
- `icon` - Emoji atau icon
- `type` - Jenis pengumuman
- `position` - Posisi (top/bottom)
- `color` - Warna tema
- `priority` - Prioritas tampil
- `pages` - Halaman yang ditampilkan
- `is_active` - Status aktif
- `is_sticky` - Tetap di posisi
- `is_dismissible` - Bisa ditutup user
- `start_date` - Tanggal mulai
- `end_date` - Tanggal berakhir

### ⚙️ Page Settings Resource
**Path:** `Admin Panel > Landing Page > Page Settings`

**Features:**
- Grouped settings (hero, general, footer, features, contact)
- Multiple input types (text, textarea, image, url, email, number, boolean)
- Validation rules
- Sort order within groups

**Form Fields:**
- `key` - Unique identifier
- `group` - Grup setting
- `label` - Label yang mudah dibaca
- `value` - Nilai setting
- `description` - Deskripsi fungsi
- `type` - Tipe input
- `placeholder` - Placeholder text
- `validation_rules` - Aturan validasi
- `sort_order` - Urutan dalam grup

### 🎨 Feature Cards Resource
**Path:** `Admin Panel > Landing Page > Feature Cards`

**Features:**
- Custom SVG icons
- Gradient background colors
- Custom text colors
- Sort order
- Rich content (title, subtitle, description)

**Form Fields:**
- `title` - Judul kartu
- `subtitle` - Subtitle
- `description` - Deskripsi
- `icon_svg` - SVG path untuk icon
- `color_from` - Warna gradient awal
- `color_to` - Warna gradient akhir
- `text_color` - Warna teks
- `sort_order` - Urutan tampil
- `is_active` - Status aktif

### 📊 Statistics Resource
**Path:** `Admin Panel > Landing Page > Statistics`

**Features:**
- Numeric values dengan suffix
- Gradient colors
- Sort order
- Active/inactive status

**Form Fields:**
- `label` - Label statistik
- `value` - Nilai numerik
- `suffix` - Suffix (%, +, Tahun, dll)
- `gradient_from` - Warna gradient awal
- `gradient_to` - Warna gradient akhir
- `sort_order` - Urutan tampil
- `is_active` - Status aktif

---

## Usage Guide

### A. Mengelola Gallery Images

1. **Upload Hero Image:**
   - Navigate ke Gallery Images
   - Click "Create"
   - Upload gambar
   - Set position = "Hero"
   - Enable "Show in Carousel" untuk auto-rotate
   - Set sort_order = 0 (prioritas tertinggi)

2. **Atur Grid Layout:**
   - **Hero**: Gambar utama besar (position = hero)
   - **Top Right**: Gambar kecil kanan atas (position = top-right)
   - **Middle Right**: Gambar kecil kanan tengah (position = middle-right)
   - **Bottom Row**: 3 gambar kecil bawah (positions = bottom-left, bottom-center, bottom-right)

3. **Tips Gallery:**
   - Gunakan aspect ratio 16:9 untuk hasil terbaik
   - Set badge_text untuk label menarik
   - Gunakan overlay_gradient untuk efek hover

### B. Mengelola Announcements

1. **Create Welcome Message:**
   - Navigate ke Announcements
   - Click "Create"
   - Title: "Selamat Datang!"
   - Type: "Success"
   - Position: "Top"
   - Pages: ["Home"]
   - Set start/end date jika perlu

2. **Maintenance Notice:**
   - Type: "Warning"
   - Color: "Yellow"
   - Is Sticky: true
   - Is Dismissible: false

### C. Mengelola Page Settings

1. **Edit Hero Section:**
   - Filter by group: "Hero"
   - Edit hero_title, hero_title_highlight, hero_subtitle
   - Update hero_badge_text

2. **Update General Info:**
   - Filter by group: "General"
   - Update site_name, site_tagline
   - Change site_logo_path jika ada logo baru

3. **Customize Footer:**
   - Filter by group: "Footer"
   - Update footer_text

### D. Mengelola Feature Cards

1. **Create Feature Card:**
   - Navigate ke Feature Cards
   - Click "Create"
   - Title: "Akurasi Tinggi"
   - Subtitle: "99.9% Precision"
   - Icon SVG: Copy dari heroicons.com
   - Set gradient colors
   - Sort order: 1

2. **Icon SVG Sources:**
   - [Heroicons](https://heroicons.com) - Copy path dari SVG
   - Contoh: `M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z`

### E. Mengelola Statistics

1. **Create Statistic:**
   - Navigate ke Statistics
   - Click "Create"
   - Label: "Akurasi"
   - Value: "99.9"
   - Suffix: "%"
   - Set gradient colors
   - Sort order: 1

---

## API Documentation

### LandingPageController Methods

#### `index()`
Returns the main landing page with all dynamic content.

**Returns:**
- `$galleryImages` - Gallery images grouped by position
- `$carouselImages` - Images for carousel
- `$announcements` - Active announcements
- `$settings` - Page settings grouped by category
- `$featureCards` - Active feature cards
- `$statistics` - Active statistics

#### `getSettingsWithDefaults($group, $defaults)`
Private method to get settings with fallback defaults.

**Parameters:**
- `$group` (string) - Settings group name
- `$defaults` (array) - Default values if settings not found

**Returns:** Array of settings with defaults merged

### Model Scopes

#### GalleryImage
- `active()` - Only active images
- `carousel()` - Only carousel images
- `ordered()` - Ordered by sort_order

#### Announcement
- `active()` - Only active announcements
- `forPage($page)` - For specific page
- `byPriority()` - Ordered by priority

#### FeatureCard
- `active()` - Only active cards
- `ordered()` - Ordered by sort_order

#### Statistic
- `active()` - Only active statistics
- `ordered()` - Ordered by sort_order

#### LandingPageSetting
- `getGroup($group)` - Get settings by group

---

## Troubleshooting

### 1. Filament Resources Not Showing
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild autoload
composer dump-autoload
```

### 2. Images Not Uploading
```bash
# Check storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Recreate storage link
php artisan storage:link
```

### 3. Settings Not Updating
```bash
# Clear application cache
php artisan cache:clear

# Check if settings exist in database
php artisan tinker
>>> App\Models\LandingPageSetting::all()
```

### 4. Gallery Images Not Displaying
- Check if images are uploaded to `storage/app/public/gallery/`
- Verify storage link: `php artisan storage:link`
- Check file permissions on storage directory

### 5. Announcements Not Showing
- Check if announcement is active
- Verify page targeting (home, login, etc.)
- Check date range (start_date, end_date)
- Verify priority is set correctly

### 6. Feature Cards Not Displaying
- Check if cards are active
- Verify sort_order is set
- Check if icon_svg path is valid

### 7. Statistics Not Showing
- Check if statistics are active
- Verify sort_order is set
- Check gradient colors are valid hex codes

---

## Performance Tips

### 1. Image Optimization
- Use WebP format for better compression
- Resize images before upload
- Use appropriate dimensions for each position

### 2. Caching
- Settings are automatically cached in LandingPageSetting model
- Consider implementing Redis for better performance

### 3. Database Optimization
- Add indexes on frequently queried fields:
  - `is_active` columns
  - `sort_order` columns
  - `position` columns

---

## Security Notes

1. **File Upload**: Filament automatically validates file types
2. **XSS Protection**: All user input is escaped in Blade templates
3. **SQL Injection**: Eloquent ORM provides protection
4. **CSRF**: All forms include CSRF tokens

---

## 🎉 Success!

Landing page dinamis SmartLab sudah siap digunakan!

**Access URLs:**
- Landing Page: `http://localhost/`
- Admin Panel: `http://localhost/admin`

**Next Steps:**
1. Login ke admin panel
2. Navigate ke "Landing Page" menu
3. Mulai mengelola konten sesuai kebutuhan
4. Test semua fitur untuk memastikan berfungsi dengan baik

**Support:**
Jika ada masalah, check troubleshooting section atau lihat log di `storage/logs/laravel.log`

Selamat mengelola konten landing page! 🚀
