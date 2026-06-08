<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Trip;
use App\Models\TripDay;
use App\Models\TripActivity;

class KepanjenTripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find user by email
        $user = User::where('email', 'yohanesmdk10@gmail.com')->first();

        if (!$user) {
            $this->command->error("User with email yohanesmdk10@gmail.com not found!");
            return;
        }

        // Check if Kepanjen Trip already exists for this user to prevent duplicates
        $existingTrip = Trip::where('user_id', $user->id)
            ->where('title', 'Kepanjen Trip')
            ->where('start_date', '2026-06-15')
            ->first();

        if ($existingTrip) {
            $this->command->info("Kepanjen Trip already exists. Deleting the old one to re-seed...");
            $existingTrip->delete();
        }

        // Create Trip
        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Kepanjen Trip',
            'description' => 'goes to beach (via kepanjen)',
            'destination' => 'Kepanjen',
            'cover_image' => null,
            'start_date' => '2026-06-15',
            'end_date' => '2026-06-15',
            'total_budget' => 0,
            'invite_code' => Trip::generateInviteCode(),
            'status' => 'planning',
        ]);

        // Add owner to members
        $trip->members()->attach($user->id, [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        // Create Day 1
        $day = TripDay::create([
            'trip_id' => $trip->id,
            'date' => '2026-06-15',
            'day_number' => 1,
            'notes' => 'itinerary goes to beach',
        ]);

        // Create Activities
        $activities = [
            [
                'title' => 'otw naik kereta',
                'start_time' => '04:37',
                'end_time' => '08:19',
                'session' => 'pagi',
                'category' => 'transportasi',
                'sort_order' => 1,
            ],
            [
                'title' => 'beres beres check out stasiun',
                'start_time' => '08:19',
                'end_time' => '08:30',
                'session' => 'pagi',
                'category' => 'lainnya',
                'sort_order' => 2,
            ],
            [
                'title' => 'ambil motor',
                'start_time' => '08:30',
                'end_time' => '08:50',
                'session' => 'pagi',
                'category' => 'transportasi',
                'sort_order' => 3,
            ],
            [
                'title' => 'transaksi motor',
                'start_time' => '08:50',
                'end_time' => '09:00',
                'session' => 'pagi',
                'category' => 'transportasi',
                'sort_order' => 4,
            ],
            [
                'title' => 'perjalanan ke pantai batu bekung',
                'start_time' => '09:00',
                'end_time' => '10:30',
                'session' => 'pagi',
                'category' => 'transportasi',
                'sort_order' => 5,
            ],
            [
                'title' => 'parkir dan cek barang bawaan',
                'start_time' => '10:30',
                'end_time' => '10:40',
                'session' => 'pagi',
                'category' => 'lainnya',
                'sort_order' => 6,
            ],
            [
                'title' => 'main di batu bekung',
                'start_time' => '10:40',
                'end_time' => '13:40',
                'session' => 'siang',
                'category' => 'wisata',
                'sort_order' => 7,
            ],
            [
                'title' => 'pindah tempat dan jalan ke parkiran',
                'start_time' => '13:40',
                'end_time' => '13:50',
                'session' => 'siang',
                'category' => 'lainnya',
                'sort_order' => 8,
            ],
            [
                'title' => 'perjalanan ke pantai tanjung penyu',
                'start_time' => '13:50',
                'end_time' => '14:00',
                'session' => 'siang',
                'category' => 'transportasi',
                'sort_order' => 9,
            ],
            [
                'title' => 'parkir dan cek barang bawaan',
                'start_time' => '14:00',
                'end_time' => '14:10',
                'session' => 'siang',
                'category' => 'lainnya',
                'sort_order' => 10,
            ],
            [
                'title' => 'main di pantai tanjung penyu dan makan siang',
                'start_time' => '14:10',
                'end_time' => '16:10',
                'session' => 'siang',
                'category' => 'kuliner',
                'sort_order' => 11,
            ],
            [
                'title' => 'persiapan balik ke shisha queen',
                'start_time' => '16:10',
                'end_time' => '17:40',
                'session' => 'siang',
                'category' => 'transportasi',
                'sort_order' => 12,
            ],
            [
                'title' => 'parkir, cari tempat, pesan menu',
                'start_time' => '17:40',
                'end_time' => '17:50',
                'session' => 'malam',
                'category' => 'kuliner',
                'sort_order' => 13,
            ],
            [
                'title' => 'nyantai di shisha queen',
                'start_time' => '17:50',
                'end_time' => '18:20',
                'session' => 'malam',
                'category' => 'kuliner',
                'sort_order' => 14,
            ],
            [
                'title' => 'perjalanan shisha queen ke stasiun',
                'start_time' => '18:20',
                'end_time' => '18:25',
                'session' => 'malam',
                'category' => 'transportasi',
                'sort_order' => 15,
            ],
            [
                'title' => 'mengembalikan motor',
                'start_time' => '18:25',
                'end_time' => '18:40',
                'session' => 'malam',
                'category' => 'transportasi',
                'sort_order' => 16,
            ],
            [
                'title' => 'free time menunggu kereta',
                'start_time' => '18:40',
                'end_time' => '19:08',
                'session' => 'malam',
                'category' => 'lainnya',
                'sort_order' => 17,
            ],
        ];

        foreach ($activities as $act) {
            TripActivity::create(array_merge($act, [
                'trip_day_id' => $day->id,
            ]));
        }

        $this->command->info("Kepanjen Trip seeded successfully for yohanesmdk10@gmail.com!");
    }
}
