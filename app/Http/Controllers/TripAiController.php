<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripDay;
use App\Models\TripActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripAiController extends Controller
{
    public function generate(Request $request, Trip $trip)
    {
        $user = Auth::user();

        // Cek keanggotaan
        if (!$trip->members()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        // Cek Tier Early Access
        if (!$user->isEarlyAccess()) {
            return back()->with('error', 'Fitur AI Itinerary hanya tersedia untuk Early Access & Premium.');
        }

        // Cek Kuota
        if (!$user->canUseAiItinerary()) {
            return back()->with('error', 'Kuota penggunaan AI Itinerary Anda telah habis (maksimal 2).');
        }

        // Validasi input
        $request->validate([
            'day_id'      => 'required|exists:trip_days,id',
            'description' => 'required|string|max:1000',
        ]);

        $day = TripDay::findOrFail($request->day_id);

        if ($day->trip_id !== $trip->id) {
            abort(403);
        }

        // Setup Gemini API Call
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return back()->with('error', 'API Key Gemini belum disetting di server. Mohon hubungi admin.');
        }

        $prompt  = "Buatkan itinerary kegiatan liburan berdasarkan deskripsi berikut:\n\"{$request->description}\"\n\n";
        $prompt .= "Aturan Output WAJIB dalam bentuk Array JSON murni (tanpa markdown ```json, HANYA JSON array). Tiap object memiliki key:\n";
        $prompt .= "- 'title' (string, max 50 char)\n";
        $prompt .= "- 'session' (string, HANYA boleh bernilai 'pagi', 'siang', atau 'malam')\n";
        $prompt .= "- 'start_time' (string format HH:MM, bisa null)\n";
        $prompt .= "- 'end_time' (string format HH:MM, bisa null, harus setelah start_time)\n";
        $prompt .= "- 'category' (string, HANYA boleh bernilai 'wisata', 'kuliner', 'transportasi', 'akomodasi', 'belanja', atau 'lainnya')\n";
        $prompt .= "- 'estimated_cost' (integer, estimasi biaya rupiah, bisa 0)\n";
        $prompt .= "- 'location_name' (string, nama tempat, opsional)\n";
        $prompt .= "- 'description' (string, alasan rekomendasi)\n\n";
        $prompt .= "Buatkan sekitar 3-5 kegiatan.";

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                ]
            );

            if (!$response->successful()) {
                $errBody = $response->json();
                $errMsg  = $errBody['error']['message'] ?? $response->body();
                Log::error('Gemini API Error: ' . $errMsg);
                return back()->with('error', 'Gagal dari AI: ' . ($errBody['error']['message'] ?? 'Periksa API Key Gemini Anda.'));
            }

            $responseData = $response->json();
            $responseText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Bersihkan teks JSON (hapus markdown ```json jika ada)
            $responseText = trim($responseText);
            if (str_starts_with($responseText, '```json')) {
                $responseText = substr($responseText, 7);
            }
            if (str_starts_with($responseText, '```')) {
                $responseText = substr($responseText, 3);
            }
            if (str_ends_with($responseText, '```')) {
                $responseText = substr($responseText, 0, -3);
            }
            $responseText = trim($responseText);

            $activities = json_decode($responseText, true);

            if (!is_array($activities)) {
                Log::error('Gemini Invalid JSON Output: ' . $responseText);
                return back()->with('error', 'AI gagal menghasilkan format yang benar. Coba ubah deskripsi Anda.');
            }

            // Simpan kegiatan ke database
            $maxSort = $day->activities()->max('sort_order') ?? 0;

            foreach ($activities as $act) {
                $session  = in_array($act['session'] ?? '', ['pagi', 'siang', 'malam']) ? $act['session'] : 'siang';
                $category = in_array($act['category'] ?? '', ['wisata', 'kuliner', 'transportasi', 'akomodasi', 'belanja', 'lainnya']) ? $act['category'] : 'lainnya';

                TripActivity::create([
                    'trip_day_id'    => $day->id,
                    'title'          => substr($act['title'] ?? 'Kegiatan AI', 0, 255),
                    'description'    => $act['description'] ?? null,
                    'session'        => $session,
                    'start_time'     => $act['start_time'] ?? null,
                    'end_time'       => $act['end_time'] ?? null,
                    'location_name'  => $act['location_name'] ?? null,
                    'category'       => $category,
                    'estimated_cost' => intval($act['estimated_cost'] ?? 0),
                    'sort_order'     => ++$maxSort,
                    'is_public'      => true,
                ]);
            }

            // Tambah hitungan kuota user
            $user->increment('ai_itinerary_count');

            return back()->with('success', 'Itinerary otomatis berhasil dibuat oleh AI!');

        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menghubungi AI.');
        }
    }
}
