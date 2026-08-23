<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title'     => 'Fitur Photobooth Digital Resmi Meluncur di TwoGo! 📸',
                'slug'      => 'fitur-photobooth-digital-resmi-meluncur',
                'excerpt'   => 'Abadikan momen kebersamaan sebelum trip dengan template bingkai Neo-Brutalism TwoGo yang estetik dan instan.',
                'content'   => "Kami sangat senang mengumumkan peluncuran fitur terbaru TwoGo: **Photobooth Digital**!\n\nKini kamu bisa langsung berfoto berdua menggunakan kamera perangkatmu, memilih bingkai bertema *TwoGo Holiday Polaroid* atau *Passport Stamp*, lalu mengunduh hasilnya secara langsung tanpa perlu mendaftar.\n\nCoba fiturnya sekarang di menu Photobooth Digital!",
                'image_url' => null,
                'author'    => 'Tim TwoGo',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title'     => 'Tips Perencanaan Budget Liburan Anti Berantem Berdua 💰',
                'slug'      => 'tips-perencanaan-budget-liburan-anti-berantem',
                'excerpt'   => 'Simak cara mengelola anggaran perjalanan dengan fitur Auto Split Bill & Debt Calculation di TwoGo.',
                'content'   => "Liburan berdua seringkali diwarnai rasa canggung saat menghitung sisa pengeluaran dan patungan uang.\n\nDengan fitur **Budget Tracker TwoGo**, kamu cukup memasukkan nominal pengeluaran dan siapa yang membayar. Sistem akan otomatis menghitung kalkulasi utang-piutang secara adil dan akurat.\n\nLiburan jadi lebih tenang, transparan, dan makin akrab!",
                'image_url' => null,
                'author'    => 'Arya & Yohanes',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title'     => 'Pemberitahuan Sistem Gamifikasi XP & Level Update 🌟',
                'slug'      => 'pemberitahuan-sistem-gamifikasi-xp-update',
                'excerpt'   => 'Dapatkan poin XP ekstra setiap kali kamu menuntaskan rencana perjalanan dan membagikan itinerary ke komunitas.',
                'content'   => "Sistem Gamifikasi TwoGo kini mendukung penambahan XP otomatis! Kumpulkan XP dari setiap checklist aktivitas trip yang kamu tuntaskan untuk menaikkan level profil kamu.\n\nTunjukkan badge level kebersamaan kamu di halaman profil!",
                'image_url' => null,
                'author'    => 'Pengembang TwoGo',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
        ];

        foreach ($articles as $art) {
            News::updateOrCreate(['slug' => $art['slug']], $art);
        }
    }
}
