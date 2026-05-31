# Standard Operating Procedure (SOP) Pengembangan TwoGo

SOP ini dibuat untuk menjaga konsistensi gaya desain (UI/UX) dan struktur penulisan kode/file selama masa pengembangan aplikasi TwoGo.

---

## 1. Gaya Desain: Neo-Brutalism
Semua UI baru **wajib** mematuhi gaya desain Neo-Brutalism yang sudah diset di `app.css`.

### A. Komponen Visual
- **Border**: Semua elemen interaktif (card, tombol, input, avatar) wajib menggunakan border tegas `border-[3px] border-[#1A1A2E]`.
- **Shadow**: Jangan gunakan shadow blur bawaan Tailwind (`shadow-md`, dll). Gunakan *hard offset shadow* dari CSS variables: `shadow-[4px_4px_0px_#1A1A2E]` atau via class custom jika ada.
- **Sudut (Radius)**: Gunakan radius membulat `rounded-xl` atau `rounded-lg` (sekitar 12px), jangan bersudut tajam.
- **Warna Palet (Hex)**:
  - Background Utama: `#FFFBEB` (Krem hangat)
  - Hitam/Teks: `#1A1A2E`
  - Primer: `#FFE156` (Kuning)
  - Sekunder/Aksen: `#FF6B9D` (Pink), `#00D4AA` (Mint), `#4361EE` (Biru), `#7B2FF7` (Ungu), `#FF8C42` (Orange)

### B. Tipografi
- **Headings (Judul)**: Gunakan font `Space Grotesk` tebal. Class Tailwind: `font-heading font-bold`.
- **Body Text**: Gunakan font `Plus Jakarta Sans`. Teks deskripsi wajib font tebal minimal `font-medium`.

### C. Animasi & Interaksi (Micro-interactions)
- **Hover/Click**: Setiap tombol dan card wajib merespons interaksi.
  - Tombol yang ditekan wajib tertekan ke bawah dan shadownya hilang: `hover:translate-y-[-2px]` saat hover, dan translate kebawah saat `:active`. Gunakan class `.nb-btn`.
- **Page Load**: Gunakan elemen animasi `animate-fade-in-up` untuk kemunculan konten.
- **Modal**: Gunakan komponen Blade `<x-modal>` berkonsep *bottom-sheet* yang muncul dari bawah layar.

---

## 2. Struktur File & Penulisan Kode

### A. Blade Components
Semua elemen UI yang diulang lebih dari 2 kali wajib dibuat sebagai Blade Component di `resources/views/components/`.
- Jangan menulis ulang gaya tombol (`class="bg-yellow border-2..."`). Selalu gunakan `<x-button variant="primary">`.
- Gunakan `<x-card>`, `<x-input>`, `<x-badge>`, `<x-avatar>`.

### B. Controller & Routing
- Patuhi standar RESTful (Resource Controller) Laravel: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.
- Pisahkan logika Controller jika sudah melebihi 1 ranah fitur (Contoh: `TripController` dan `TripActivityController` dipisah).
- Semua route view dan action yang wajib login diletakkan di dalam `Route::middleware('auth')->group(...)`.

### C. Eloquent Models & Migrations
- Gunakan *Type Hinting* (kembalian `: void`, `: array`, `: bool`) untuk setiap *method* baru di Model/Controller.
- Di dalam migrasi, pastikan `cascadeOnDelete()` selalu diset pada *foreign key constraint* agar data tidak yatim-piatu ketika parent dihapus.

### D. Penamaan & Bahasa
- Kode program, nama variabel, nama model, nama file = **Bahasa Inggris** (Contoh: `TripDay`, `estimated_cost`, `ExpenseController`).
- Teks yang muncul di User Interface (View Blade) = **Bahasa Indonesia** santai dan asyik (Contoh: "Buat Trip Baru ✨", "Siapa bayar siapa?").

---

## 3. Aturan Mobile-First
Aplikasi ini target utamanya adalah layar Smartphone.
- Selalu uji perubahan pada *DevTools Device Toolbar* (ukuran iPhone 12/13/14).
- Batasi lebar konten dengan `<div class="app-container">` (max-width: 480px) agar saat dibuka di desktop, tampilan tetap proporsional menyerupai aplikasi mobile di tengah layar.
- Jangan gunakan *Hover Menu* atau dropdown standar yang susah diklik jari. Ganti dengan Modal Bottom Sheet (`<x-modal>`).
