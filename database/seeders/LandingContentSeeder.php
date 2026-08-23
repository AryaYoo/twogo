<?php

namespace Database\Seeders;

use App\Models\LandingFeature;
use App\Models\LandingSetting;
use App\Models\LandingShowcase;
use App\Models\LandingStat;
use App\Models\LandingTestimonial;
use Illuminate\Database\Seeder;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        $settings = [
            'hero_badge'           => '✨ Aplikasi Itinerary #1 buat Berdua',
            'hero_title'           => 'Rencana Seru, Bareng-Bareng! 🎒',
            'hero_subtitle'        => 'Aplikasi perencanaan perjalanan yang bikin liburanmu makin asyik, rapi, dan terorganisir tanpa ribet adu argumen budget.',
            'hero_btn_primary'     => 'Mulai Sekarang 🔥',
            'hero_btn_secondary'   => 'Sudah Punya Akun',
            'marquee_destinations' => 'BALI • JOGJA • LOMBOK • RAJA AMPAT • BANDUNG • LABUAN BAJO • MALANG • SURABAYA • UBUD • FLORES',
            'cta_badge'            => 'Tunggu Apa Lagi? 🎒',
            'cta_title'            => 'Siap untuk Liburan Berikutnya?',
            'cta_subtitle'         => 'Yuk bikin itinerary pertamamu di TwoGo secara gratis!',
            'cta_btn'              => 'Buat Trip Sekarang 🚀',
            'footer_tagline'       => 'Rencana Seru, Bareng-Bareng! Aplikasi itinerary & budget tracker perjalanan #1 di Indonesia.',
            'footer_email'         => 'adventuretwogo@gmail.com',
        ];

        foreach ($settings as $key => $value) {
            LandingSetting::setValue($key, $value);
        }

        // 2. Features
        $features = [
            [
                'title'       => 'Timeline Fleksibel',
                'description' => 'Atur jadwal per hari dengan santai. Pagi, Siang, Malam tanpa dikejar waktu karena liburan butuh kepastian tanpa stres.',
                'icon'        => '📅',
                'bg_color'    => '#00D4AA',
                'text_color'  => '#1A1A2E',
                'order'       => 1,
            ],
            [
                'title'       => 'Budget Tracker',
                'description' => 'Catat pengeluaran bersama secara riil. Siapa bayar apa langsung tercatat rapi dan otomatis ngitung pembagian utang.',
                'icon'        => '💰',
                'bg_color'    => '#FFE156',
                'text_color'  => '#1A1A2E',
                'order'       => 2,
            ],
            [
                'title'       => 'Wishlist Destinasi',
                'description' => 'Kumpulkan ide destinasi di bucket list bersama. Pilih dan vote tempat mana saja yang wajib dikunjungi.',
                'icon'        => '📍',
                'bg_color'    => '#FF6B9D',
                'text_color'  => '#FFFFFF',
                'order'       => 3,
            ],
            [
                'title'       => 'Dokumentasi',
                'description' => 'Abadikan momen dan berkas perjalanan. Foto serta catatan tiket tersimpan rapi per trip sebagai kenangan digital.',
                'icon'        => '📸',
                'bg_color'    => '#7B2FF7',
                'text_color'  => '#FFFFFF',
                'order'       => 4,
            ],
        ];

        foreach ($features as $f) {
            LandingFeature::updateOrCreate(['title' => $f['title']], $f);
        }

        // 3. Showcases
        $showcases = [
            [
                'section_badge' => 'Fitur #1 • Itinerary Builder',
                'title'         => 'Timeline & Itinerary Harian yang Terstruktur',
                'description'   => 'Menyusun kegiatan harian dari pagi hingga malam jadi lebih mudah. Setiap aktivitas dilengkapi lokasi, jam, dan status penyelesaian.',
                'bullet_points' => [
                    'Pengelompokan jadwal per hari yang fleksibel',
                    'Penanda aktivitas selesai (Checklist real-time)',
                    'Sistem bonus XP setiap kegiatan dituntaskan',
                ],
                'badge_color'   => '#4361EE',
                'mockup_type'   => 'itinerary',
                'order'         => 1,
            ],
            [
                'section_badge' => 'Fitur #2 • Budget & Split Bill',
                'title'         => 'Auto Hitung Budget & Transparan Berdua',
                'description'   => 'Tidak perlu lagi mencatat manual di kertas atau kalkulator HP. Setiap pengeluaran langsung dihitung secara otomatis siapa bayar apa.',
                'bullet_points' => [
                    'Pencatatan pengeluaran riil per kategori',
                    'Kalkulasi otomatis utang-piutang antar partner',
                    'Pantau sisa anggaran trip secara transparan',
                ],
                'badge_color'   => '#FF6B9D',
                'mockup_type'   => 'budget',
                'order'         => 2,
            ],
        ];

        foreach ($showcases as $s) {
            LandingShowcase::updateOrCreate(['title' => $s['title']], $s);
        }

        // 4. Stats
        $stats = [
            [
                'number'     => '15.000+',
                'label'      => 'Itinerary Dibuat',
                'bg_color'   => '#FFE156',
                'text_color' => '#1A1A2E',
                'order'      => 1,
            ],
            [
                'number'     => '500+',
                'label'      => 'Destinasi Populer',
                'bg_color'   => '#00D4AA',
                'text_color' => '#1A1A2E',
                'order'      => 2,
            ],
            [
                'number'     => '4.9 ★',
                'label'      => 'Rating Kepuasan',
                'bg_color'   => '#FF6B9D',
                'text_color' => '#FFFFFF',
                'order'      => 3,
            ],
            [
                'number'     => '28.000+',
                'label'      => 'Trip Selesai',
                'bg_color'   => '#4361EE',
                'text_color' => '#FFFFFF',
                'order'      => 4,
            ],
        ];

        foreach ($stats as $st) {
            LandingStat::updateOrCreate(['label' => $st['label']], $st);
        }

        // 5. Testimonials
        $testimonials = [
            [
                'user_name'    => 'Budi & Sarah',
                'user_tier'    => 'Traveler Sejati 🌟',
                'quote'        => 'Liburan ke Bali minggu lalu rapi banget pakenya TwoGo! Gak pake adu argumen soal pengeluaran karena utang-piutang langsung auto dihitung. Recommended parah!',
                'avatar_emoji' => '🌟',
                'bg_color'     => '#FFF3C4',
                'order'        => 1,
            ],
            [
                'user_name'    => 'Reza & Amanda',
                'user_tier'    => 'TwoGo-er Legend 👑',
                'quote'        => 'Buat itinerary berdua sama doi langsung gampang. Tinggal nyusun hari demi hari trus foto kenangannya tersimpan rapi per trip. Gak bingung nyari foto lama.',
                'avatar_emoji' => '👑',
                'bg_color'     => '#FFD1E3',
                'order'        => 2,
            ],
        ];

        foreach ($testimonials as $t) {
            LandingTestimonial::updateOrCreate(['user_name' => $t['user_name']], $t);
        }
    }
}
