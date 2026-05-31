# TwoGo — Travel Itinerary App for Two 🧳✈️

Aplikasi web travel itinerary mobile-first untuk merencanakan perjalanan berdua. Dibangun di atas **Laravel 12** yang sudah ada, dengan desain **Neo-Brutalism** yang bold, playful, dan sangat nyaman di smartphone.

## User Review Required

> [!IMPORTANT]
> **Database**: Project saat ini menggunakan `DB_CONNECTION=sqlite` di `.env`, tapi `DB_DATABASE=mysql` dan `DB_USERNAME=twogo`. Apakah kamu ingin menggunakan **MySQL** atau **SQLite**? Plan ini akan menggunakan MySQL via XAMPP karena sudah ada setup-nya.

> [!IMPORTANT]
> **Tailwind CSS vs Vanilla CSS**: Project sudah include Tailwind CSS 4. Karena kamu minta neo-brutalism yang custom, saya akan **tetap menggunakan Tailwind CSS 4** yang sudah terinstall sebagai utility framework, lalu menambahkan custom CSS untuk komponen neo-brutalism. Ini lebih efisien daripada menghapus Tailwind dan menulis semuanya dari nol.

> [!WARNING]
> **Foto/Dokumentasi**: Upload foto akan disimpan di `storage/app/public`. Pastikan menjalankan `php artisan storage:link` setelah setup.

## Open Questions

> [!IMPORTANT]
> 1. **Bahasa aplikasi**: Apakah UI dalam **Bahasa Indonesia** atau **English**? Plan ini default Bahasa Indonesia.
> 2. **Invite teman**: Apakah invite via **email** saja, atau juga bisa via **link/kode unik**? Plan ini menggunakan keduanya (email + kode invite).
> 3. **Budget currency**: Apakah hanya **Rupiah (IDR)** atau multi-currency? Plan ini default IDR.
> 4. **Maksimal anggota trip**: Apakah hanya berdua, atau bisa group? Nama app "TwoGo" menyiratkan berdua, tapi plan ini support **flexible** (2+ orang).

---

## Proposed Changes

Perubahan diorganisir dalam **7 fase** yang harus dikerjakan berurutan.

---

### Fase 1: Foundation & Design System

Setup database MySQL, design system neo-brutalism, dan layout dasar mobile-first.

#### [MODIFY] .env
- Ubah `APP_NAME=TwoGo`
- Ubah `DB_CONNECTION=mysql`
- Pastikan database config benar untuk XAMPP MySQL

#### [MODIFY] vite.config.js
- Tidak ada perubahan signifikan, Tailwind sudah terkonfigurasi

#### [MODIFY] resources/css/app.css
- Import Google Fonts (Space Grotesk + Plus Jakarta Sans)
- Definisi CSS custom properties untuk neo-brutalism design tokens:
  - Warna primer: kuning cerah `#FFE156`, hitam `#1A1A2E`, pink `#FF6B9D`, hijau mint `#00D4AA`, biru electric `#4361EE`, ungu `#7B2FF7`
  - Border tebal (3-4px) hitam solid — ciri khas neo-brutalism
  - Shadow offset keras (4px 4px 0px #000) — bukan blur, tapi solid offset
  - Border-radius kecil (8-12px) untuk kesan playful tapi clean
- Utility classes untuk komponen neo-brutal (cards, buttons, inputs, badges)
- Animasi micro-interaction:
  - Button press (translate + shadow shrink on click)
  - Card hover lift
  - Page transitions (slide-up fade-in)
  - Skeleton loading states
  - Toast notifications slide-in
  - Checkbox bounce
  - Swipe hint animations

#### [NEW] resources/views/layouts/app.blade.php
- Layout utama mobile-first dengan viewport meta
- Bottom navigation bar (sticky) dengan 5 tab: Home, Trip, Wishlist, Budget, Profil
- Include Vite assets
- Toast/notification container
- PWA-ready meta tags

#### [NEW] resources/views/components/ (Blade Components)
Komponen reusable neo-brutalism:

| Component | File | Deskripsi |
|-----------|------|-----------|
| Button | `components/button.blade.php` | Primary, secondary, danger, ghost variants |
| Card | `components/card.blade.php` | Neo-brutal card dengan border + shadow |
| Input | `components/input.blade.php` | Text input dengan label floating |
| Badge | `components/badge.blade.php` | Status badges (warna-warni) |
| Modal | `components/modal.blade.php` | Bottom sheet modal (mobile-friendly) |
| Toast | `components/toast.blade.php` | Notification toast dengan animasi |
| Avatar | `components/avatar.blade.php` | User avatar dengan border brutal |
| Empty State | `components/empty-state.blade.php` | Ilustrasi + CTA untuk halaman kosong |
| Bottom Nav | `components/bottom-nav.blade.php` | Navigation bar bawah |
| Tab Bar | `components/tab-bar.blade.php` | Horizontal scrollable tabs |

---

### Fase 2: Authentication (Register, Login, Lupa Password)

Sistem auth custom tanpa Laravel Breeze/Jetstream — agar tampilan 100% neo-brutalism.

#### [NEW] app/Http/Controllers/Auth/RegisterController.php
- `showRegistrationForm()` → tampilkan form registrasi
- `register()` → validasi, buat user, auto-login, redirect ke dashboard
- Generate avatar placeholder berdasarkan inisial nama

#### [NEW] app/Http/Controllers/Auth/LoginController.php
- `showLoginForm()` → tampilkan form login
- `login()` → validasi credentials, redirect
- `logout()` → logout dan redirect

#### [NEW] app/Http/Controllers/Auth/ForgotPasswordController.php
- `showForgotForm()` → form input email
- `sendResetLink()` → kirim email reset (menggunakan Laravel built-in password broker)
- `showResetForm()` → form reset password dengan token
- `resetPassword()` → update password

#### [MODIFY] app/Models/User.php
- Tambah field `avatar`, `phone`, `bio`
- Tambah relationships: `trips()`, `friends()`, `expenses()`

#### [NEW] database/migrations/xxxx_add_profile_fields_to_users_table.php
- Tambah kolom: `avatar` (nullable string), `phone` (nullable string), `bio` (nullable text)

#### View files:
| View | Path |
|------|------|
| Login | `resources/views/auth/login.blade.php` |
| Register | `resources/views/auth/register.blade.php` |
| Forgot Password | `resources/views/auth/forgot-password.blade.php` |
| Reset Password | `resources/views/auth/reset-password.blade.php` |

#### [MODIFY] routes/web.php
- Route group `auth`: login, register, forgot-password, reset-password, logout

---

### Fase 3: Trip Management & Timeline

Fitur inti — membuat trip dan mengatur timeline harian.

#### [NEW] app/Models/Trip.php
```
- id, user_id (creator), title, description, destination
- cover_image, start_date, end_date
- total_budget, currency (default 'IDR')
- invite_code (unique, 6 karakter)
- status: 'planning' | 'ongoing' | 'completed'
- timestamps
```

#### [NEW] app/Models/TripMember.php
```
- id, trip_id, user_id
- role: 'owner' | 'member'
- joined_at
```

#### [NEW] app/Models/TripDay.php
```
- id, trip_id, date, day_number
- notes (catatan harian opsional)
```

#### [NEW] app/Models/TripActivity.php
```
- id, trip_day_id, title, description
- session: 'pagi' | 'siang' | 'malam'
- location_name, location_url (Google Maps link)
- estimated_cost
- category: 'wisata' | 'kuliner' | 'transportasi' | 'akomodasi' | 'belanja' | 'lainnya'
- sort_order
- is_completed (boolean)
- timestamps
```

#### [NEW] app/Http/Controllers/TripController.php
- `index()` → list semua trip milik user (aktif + selesai)
- `create()` → form buat trip baru
- `store()` → simpan trip, auto-generate invite_code, auto-generate TripDay dari date range
- `show($trip)` → detail trip dengan timeline per hari
- `edit($trip)` → edit trip info
- `update($trip)` → update trip
- `destroy($trip)` → hapus trip (soft)

#### [NEW] app/Http/Controllers/TripActivityController.php
- `store()` → tambah aktivitas ke hari tertentu
- `update()` → edit aktivitas
- `destroy()` → hapus aktivitas
- `toggleComplete()` → tandai selesai/belum
- `reorder()` → ubah urutan aktivitas dalam sesi

#### Migrations:
| Migration | Tabel |
|-----------|-------|
| `create_trips_table` | trips |
| `create_trip_members_table` | trip_members |
| `create_trip_days_table` | trip_days |
| `create_trip_activities_table` | trip_activities |

#### View files:
| View | Deskripsi |
|------|-----------|
| `views/trips/index.blade.php` | Dashboard — list trip cards dengan status |
| `views/trips/create.blade.php` | Form buat trip baru |
| `views/trips/show.blade.php` | Detail trip — header + tab navigasi |
| `views/trips/timeline.blade.php` | Timeline view — hari per hari, sesi per sesi |
| `views/trips/edit.blade.php` | Edit trip info |
| `views/trips/activity-form.blade.php` | Modal/form tambah-edit aktivitas |

**Desain Timeline:**
```
┌─────────────────────────┐
│  📅 Hari 1 — Sen, 15 Jun │
├─────────────────────────┤
│  🌅 PAGI                │
│  ┌───────────────────┐  │
│  │ ☕ Sarapan di Kopi │  │
│  │    Kenangan        │  │
│  │    💰 Rp 50.000    │  │
│  └───────────────────┘  │
│  ┌───────────────────┐  │
│  │ 🏛️ Museum Nasional │  │
│  │    💰 Rp 20.000    │  │
│  └───────────────────┘  │
│                         │
│  🌞 SIANG               │
│  ┌───────────────────┐  │
│  │ 🍜 Makan di Sate  │  │
│  │    Khas Senayan    │  │
│  │    💰 Rp 150.000   │  │
│  └───────────────────┘  │
│                         │
│  🌙 MALAM               │
│  ┌───────────────────┐  │
│  │ + Tambah Kegiatan  │  │
│  └───────────────────┘  │
└─────────────────────────┘
```

---

### Fase 4: Budget Tracker

Kalkulator anggaran bersama dengan pencatatan siapa yang bayar.

#### [NEW] app/Models/Expense.php
```
- id, trip_id, paid_by (user_id)
- title, amount, category
- category: 'akomodasi' | 'transportasi' | 'kuliner' | 'tiket' | 'belanja' | 'lainnya'
- split_type: 'equal' | 'custom' | 'solo'
- receipt_image (nullable)
- notes, expense_date
- timestamps
```

#### [NEW] app/Models/ExpenseSplit.php
```
- id, expense_id, user_id
- amount (bagian yang harus dibayar user ini)
- is_settled (boolean — sudah lunas atau belum)
```

#### [NEW] app/Http/Controllers/ExpenseController.php
- `index($trip)` → ringkasan budget: total budget, total terpakai, sisa, breakdown per kategori
- `store($trip)` → catat pengeluaran baru + auto-split
- `update()` → edit pengeluaran
- `destroy()` → hapus pengeluaran
- `summary($trip)` → siapa utang berapa ke siapa (settlement calculator)

#### Migrations:
| Migration | Tabel |
|-----------|-------|
| `create_expenses_table` | expenses |
| `create_expense_splits_table` | expense_splits |

#### View files:
| View | Deskripsi |
|------|-----------|
| `views/expenses/index.blade.php` | Dashboard budget — donut chart + list |
| `views/expenses/create.blade.php` | Form catat pengeluaran |
| `views/expenses/summary.blade.php` | Ringkasan: siapa bayar apa, utang-piutang |

**Desain Budget Dashboard:**
```
┌──────────────────────────┐
│  💰 Budget Trip Bali     │
│                          │
│  Total Budget: Rp 5.000K │
│  ██████████░░░ 65%       │
│  Terpakai: Rp 3.250K    │
│  Sisa: Rp 1.750K        │
├──────────────────────────┤
│  📊 Per Kategori:        │
│  🏨 Akomodasi    1.500K  │
│  🍜 Kuliner        800K  │
│  🎫 Tiket          450K  │
│  🚗 Transport      500K  │
├──────────────────────────┤
│  ⚖️ Settlement:          │
│  Kamu → Dia: Rp 125.000  │
└──────────────────────────┘
```

---

### Fase 5: Wishlist / Bucket List

Halaman brainstorming tempat-tempat yang ingin dikunjungi sebelum finalisasi itinerary.

#### [NEW] app/Models/WishlistItem.php
```
- id, trip_id, added_by (user_id)
- name, description, category ('wisata' | 'kuliner' | 'belanja' | 'lainnya')
- location_name, location_url
- image_url (nullable)
- estimated_cost
- priority: 'wajib' | 'pengen' | 'kalau sempat'
- is_added_to_itinerary (boolean)
- votes (JSON — array user_id yang vote)
- timestamps
```

#### [NEW] app/Http/Controllers/WishlistController.php
- `index($trip)` → tampilkan semua wishlist items, filter by category/priority
- `store($trip)` → tambah item baru
- `update()` → edit item
- `destroy()` → hapus item
- `vote()` → toggle vote (mau/tidak mau)
- `addToItinerary()` → pindahkan item ke timeline sebagai activity

#### View files:
| View | Deskripsi |
|------|-----------|
| `views/wishlist/index.blade.php` | Grid cards wishlist + filter tabs |
| `views/wishlist/create.blade.php` | Form tambah wishlist |

---

### Fase 6: Friend System & Invitations

Sistem pertemanan dan invite ke trip.

#### [NEW] app/Models/Friendship.php
```
- id, user_id, friend_id
- status: 'pending' | 'accepted' | 'blocked'
- timestamps
```

#### [NEW] app/Models/TripInvitation.php
```
- id, trip_id, invited_by (user_id)
- invited_email (untuk non-registered users)
- invited_user_id (nullable, untuk registered users)
- status: 'pending' | 'accepted' | 'declined'
- token (unique)
- expires_at
- timestamps
```

#### [NEW] app/Http/Controllers/FriendController.php
- `index()` → list teman
- `search()` → cari user by name/email
- `sendRequest()` → kirim permintaan pertemanan
- `acceptRequest()` → terima
- `declineRequest()` → tolak
- `remove()` → hapus teman

#### [NEW] app/Http/Controllers/InvitationController.php
- `invite($trip)` → kirim undangan ke trip (via email atau friend list)
- `acceptByCode()` → join trip via invite code
- `acceptByLink($token)` → join trip via link invitation
- `decline($invitation)` → tolak undangan

#### Migrations:
| Migration | Tabel |
|-----------|-------|
| `create_friendships_table` | friendships |
| `create_trip_invitations_table` | trip_invitations |

#### View files:
| View | Deskripsi |
|------|-----------|
| `views/friends/index.blade.php` | List teman + pending requests |
| `views/friends/search.blade.php` | Cari & tambah teman |
| `views/trips/invite.blade.php` | Form invite ke trip |
| `views/invitations/accept.blade.php` | Halaman terima undangan |

---

### Fase 7: Trip Documentation

Dokumentasi perjalanan berupa foto + notes per hari.

#### [NEW] app/Models/TripDocument.php
```
- id, trip_id, user_id, trip_day_id (nullable)
- type: 'photo' | 'note'
- file_path (untuk foto), caption
- content (untuk note/jurnal)
- timestamps
```

#### [NEW] app/Http/Controllers/DocumentController.php
- `index($trip)` → gallery view semua foto + notes
- `store($trip)` → upload foto / tulis catatan
- `destroy()` → hapus dokumen

#### [NEW] app/Http/Controllers/ProfileController.php
- `show()` → halaman profil
- `edit()` → edit profil
- `update()` → update profil + avatar

#### Migrations:
| Migration | Tabel |
|-----------|-------|
| `create_trip_documents_table` | trip_documents |

#### View files:
| View | Deskripsi |
|------|-----------|
| `views/documents/index.blade.php` | Gallery masonry layout |
| `views/documents/create.blade.php` | Upload foto + form catatan |
| `views/profile/show.blade.php` | Halaman profil user |
| `views/profile/edit.blade.php` | Edit profil |

---

### Halaman Landing (Replace Welcome)

#### [DELETE] resources/views/welcome.blade.php
- Hapus halaman default Laravel

#### [NEW] resources/views/landing.blade.php
- Landing page neo-brutalism yang eye-catching
- Hero section dengan tagline "Rencana Seru, Bareng-Bareng 🎒"
- Feature highlights dengan ilustrasi/ikon
- CTA buttons: Mulai Sekarang (register) / Masuk (login)
- Animasi scroll reveal

---

## Ringkasan File yang Dibuat

### Database (9 migrations)
| # | Migration | Tabel |
|---|-----------|-------|
| 1 | add_profile_fields_to_users | users (alter) |
| 2 | create_trips_table | trips |
| 3 | create_trip_members_table | trip_members |
| 4 | create_trip_days_table | trip_days |
| 5 | create_trip_activities_table | trip_activities |
| 6 | create_expenses_table | expenses |
| 7 | create_expense_splits_table | expense_splits |
| 8 | create_friendships_table | friendships |
| 9 | create_trip_invitations_table | trip_invitations |
| 10 | create_trip_documents_table | trip_documents |
| 11 | create_wishlist_items_table | wishlist_items |

### Models (9 models)
`User` (modify), `Trip`, `TripMember`, `TripDay`, `TripActivity`, `Expense`, `ExpenseSplit`, `WishlistItem`, `Friendship`, `TripInvitation`, `TripDocument`

### Controllers (8 controllers)
`RegisterController`, `LoginController`, `ForgotPasswordController`, `TripController`, `TripActivityController`, `ExpenseController`, `WishlistController`, `FriendController`, `InvitationController`, `DocumentController`, `ProfileController`

### Views (~25 blade files)
Layouts, components, auth pages, trip pages, budget pages, wishlist pages, friend pages, document pages, profile pages, landing page.

---

## Design System: Neo-Brutalism

```
┌─────────────────────────────────────────┐
│  NEO-BRUTALISM DESIGN TOKENS            │
├─────────────────────────────────────────┤
│                                         │
│  Colors:                                │
│  ■ Primary:  #FFE156 (kuning cerah)     │
│  ■ Dark:     #1A1A2E (hampir hitam)     │
│  ■ Pink:     #FF6B9D (aksen playful)    │
│  ■ Mint:     #00D4AA (sukses/positif)   │
│  ■ Blue:     #4361EE (link/info)        │
│  ■ Purple:   #7B2FF7 (premium feel)     │
│  ■ Orange:   #FF8C42 (warning/budget)   │
│  ■ BG:       #FFFBEB (krem hangat)      │
│                                         │
│  Borders:    3px solid #1A1A2E          │
│  Shadows:    4px 4px 0px #1A1A2E        │
│  Radius:     12px                       │
│  Font Head:  Space Grotesk (bold)       │
│  Font Body:  Plus Jakarta Sans          │
│                                         │
│  Micro-Animations:                      │
│  • Button: translateY(2px) on press     │
│  • Card: translateY(-4px) on hover      │
│  • Page: fadeInUp on load               │
│  • Toast: slideInRight                  │
│  • Checkbox: bounceIn on check          │
│  • Tab switch: slideX transition        │
│  • Pull-to-refresh style loading        │
│  • Skeleton shimmer loading             │
│                                         │
└─────────────────────────────────────────┘
```

---

## Verification Plan

### Automated Tests
```bash
# Jalankan migrations
php artisan migrate:fresh

# Seed sample data
php artisan db:seed

# Run tests
php artisan test

# Build frontend assets
npm run build
```

### Manual Verification
1. **Auth flow**: Register → Login → Logout → Forgot Password
2. **Trip flow**: Buat trip → Tambah aktivitas per sesi → Edit → Hapus
3. **Budget flow**: Set budget → Catat pengeluaran → Cek ringkasan → Settlement
4. **Wishlist flow**: Tambah item → Vote → Pindah ke itinerary
5. **Friend flow**: Cari user → Add friend → Accept → Invite ke trip
6. **Document flow**: Upload foto → Tulis catatan → Gallery view
7. **Mobile responsiveness**: Test di Chrome DevTools mobile viewport (375px, 390px, 414px)
8. **Neo-brutalism visual**: Pastikan borders, shadows, warna, dan animasi konsisten
