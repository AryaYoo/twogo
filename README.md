# TwoGo V1.0.2 — Travel Itinerary App 🎒

Aplikasi perencanaan perjalanan (itinerary) berbasis web yang dirancang khusus untuk dua orang (pasangan/bestie) dengan gaya visual **Neo-Brutalism**. Dibuat dengan pendekatan *mobile-first* agar mudah diakses saat sedang jalan-jalan.

## 🚀 Fitur Utama

### 1. Timeline Perjalanan Fleksibel
Daripada dijadwalkan dengan ketat per jam/menit, timeline dibagi per sesi yang santai: **Pagi, Siang, dan Malam**. 
- Memungkinkan penambahan destinasi ke sesi tertentu.
- Sistem *checklist* (centang) untuk menandai kegiatan yang sudah diselesaikan.
- Detail estimasi biaya, kategori kegiatan, dan link ke Google Maps.

### 2. Budget Tracker & Settlement (Utang-Piutang)
Sistem pencatatan anggaran bersama yang transparan:
- Pencatatan pengeluaran per kategori (Akomodasi, Transportasi, Kuliner, dll).
- Melihat ringkasan total budget vs total terpakai.
- **Auto-Settlement**: Aplikasi secara otomatis menghitung *Siapa bayar ke Siapa* berdasarkan total pembagian rata (split equal).

### 3. Modul Wishlist & Bucket List
Sistem *brainstorming* sebelum fix masuk ke itinerary:
- Simpan ide tempat wisata, cafe, atau tempat belanja.
- Penandaan skala prioritas (Wajib Banget, Pengen, Kalau Sempat).
- Sistem **Voting (👍)** dari sesama anggota trip untuk menentukan destinasi mana yang paling diinginkan.

### 4. Dokumentasi & Kenangan
Catat momen tak terlupakan langsung dalam aplikasi:
- Upload foto perjalanan.
- Tulis catatan/jurnal mini harian.
- Ditampilkan dalam format galeri yang rapi.

### 5. Sistem Pertemanan & Invite Code
- Undang teman dengan memberikan **Kode Invite 6 digit**.
- Maksimal anggota dalam satu trip dibatasi hanya **2 orang** (sesuai nama TwoGo).
- Fitur pencarian teman sesama pengguna aplikasi.

## 💻 Tech Stack
- **Framework**: Laravel 12
- **Frontend**: Blade Templates, Tailwind CSS v4, Vanilla JavaScript
- **Database**: MySQL
- **Desain UI**: Neo-Brutalism (Custom CSS)

## 🎨 Design System: Neo-Brutalism
- **Warna Utama**: Kuning (`#FFE156`), Pink (`#FF6B9D`), Mint (`#00D4AA`), Ungu (`#7B2FF7`).
- **Gaya Elemen**: Border tebal 3px hitam legam (`#1A1A2E`), *offset shadows* keras, tanpa *blur*.
- **Tipografi**: Space Grotesk (untuk judul), Plus Jakarta Sans (untuk teks tubuh).

## ⚙️ Instalasi & Setup

1. Clone repositori ini.
2. Jalankan `composer install` dan `npm install`.
3. Salin `.env.example` ke `.env` dan konfigurasikan database (MySQL).
4. Jalankan `php artisan key:generate`.
5. Jalankan `php artisan migrate:fresh` untuk membuat tabel-tabel database.
6. Jalankan `php artisan storage:link` untuk menampilkan foto profil dan unggahan dokumen.
7. Jalankan `npm run build` untuk mengkompilasi *assets* (Tailwind).
8. Jalankan `php artisan serve` untuk mengakses aplikasi lokal.
