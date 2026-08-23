<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\XpRule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin YohanesMA
        User::updateOrCreate(
            ['email' => 'yohanesma@twogo.com'],
            [
                'name'     => 'YohanesMA',
                'password' => Hash::make('AryaSangCEO'),
                'is_admin' => true,
                'status'   => 'active',
            ]
        );

        // 2. Admin Default
        User::updateOrCreate(
            ['email' => 'admin@twogo.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'status'   => 'active',
            ]
        );

        // 3. Default XP Rules
        $rules = [
            [
                'key'         => 'trip_created',
                'name'        => 'Buat Itinerary Baru',
                'xp_amount'   => 50,
                'description' => 'Diberikan saat pengguna membuat rincian itinerary perjalanan baru.',
            ],
            [
                'key'         => 'activity_completed',
                'name'        => 'Selesaikan Kegiatan',
                'xp_amount'   => 10,
                'description' => 'Diberikan setiap kali menandai satu aktivitas itinerary selesai.',
            ],
            [
                'key'         => 'trip_completed',
                'name'        => 'Tandai Trip Selesai',
                'xp_amount'   => 100,
                'description' => 'Diberikan saat seluruh perjalanan diselesaikan.',
            ],
            [
                'key'         => 'trip_liked',
                'name'        => 'Itinerary Disukai',
                'xp_amount'   => 20,
                'description' => 'Diberikan kepada pemilik itinerary ketika pengguna lain menyukai itinerary miliknya.',
            ],
            [
                'key'         => 'trip_cloned',
                'name'        => 'Itinerary Disalin',
                'xp_amount'   => 50,
                'description' => 'Diberikan saat pengguna lain menyalin itinerary publik.',
            ],
            [
                'key'         => 'friend_added',
                'name'        => 'Tambah Teman Baru',
                'xp_amount'   => 15,
                'description' => 'Diberikan ketika berhasil menerima/menambahkan koneksi teman baru.',
            ],
            [
                'key'         => 'partner_bonus',
                'name'        => 'Bonus Trip Berdua',
                'xp_amount'   => 50,
                'description' => 'Bonus XP saat menyelesaikan trip bersama partner perjalanan.',
            ],
        ];

        foreach ($rules as $rule) {
            XpRule::updateOrCreate(
                ['key' => $rule['key']],
                [
                    'name'        => $rule['name'],
                    'xp_amount'   => $rule['xp_amount'],
                    'description' => $rule['description'],
                    'is_active'   => true,
                ]
            );
        }
    }
}
