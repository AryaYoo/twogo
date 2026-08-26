<?php

namespace App\Http\Controllers;

use App\Models\OpenPartnerQuota;
use App\Models\OpenPartnerRequest;
use App\Models\Trip;
use App\Notifications\AppActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpenPartnerController extends Controller
{
    /**
     * Halaman Publik/List Open Partner (menggantikan coming soon).
     */
    public function index(Request $request)
    {
        $query = Trip::with(['creator', 'members'])
            ->where('is_open_partner', true)
            ->whereHas('members', function ($q) {
                // Trip yang belum penuh (< 2 anggota)
            }, '<', 2);

        // Filter Kata Kunci (Judul, Destinasi, Catatan)
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhere('open_partner_note', 'like', "%{$search}%");
            });
        }

        // Filter Lokasi / Destinasi
        if ($location = $request->input('location')) {
            $query->where('destination', 'like', "%{$location}%");
        }

        // Filter Bulan
        if ($month = $request->input('month')) {
            $query->whereMonth('start_date', $month);
        }

        // Filter Tahun
        if ($year = $request->input('year')) {
            $query->whereYear('start_date', $year);
        }

        $trips = $query->latest()->paginate(10)->withQueryString();

        // Ambil daftar destinasi unik untuk rekomendasi/datalist
        $availableDestinations = Trip::where('is_open_partner', true)
            ->whereNotNull('destination')
            ->distinct()
            ->pluck('destination')
            ->filter()
            ->values();

        // Ambil ID trip yang sudah pernah diajukan oleh user yang sedang login
        $myPendingTripIds = [];
        if (Auth::check()) {
            $myPendingTripIds = OpenPartnerRequest::where('requester_id', Auth::id())
                ->where('status', 'pending')
                ->pluck('trip_id')
                ->toArray();
        }

        return view('search.partner', compact('trips', 'myPendingTripIds', 'availableDestinations'));
    }

    /**
     * Aktifkan Open Partner untuk sebuah trip oleh Host.
     */
    public function activate(Request $request, Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Hanya pembuat perjalanan (Host) yang dapat mengaktifkan Open Partner.');
        }

        if ($trip->members()->count() >= 2) {
            return back()->with('error', 'Trip ini sudah memiliki 2 anggota (penuh).');
        }

        $user = Auth::user();
        $remainingQuota = OpenPartnerQuota::getRemainingQuota($user);

        if ($remainingQuota <= 0) {
            return back()->with('error', 'Kuota Open Partner kamu bulan ini sudah habis (Maksimal 2x per bulan). Kuota akan direset awal bulan depan.');
        }

        $validated = $request->validate([
            'open_partner_note' => 'nullable|string|max:500',
        ]);

        // Gunakan 1 kuota
        OpenPartnerQuota::useQuota($user);

        $trip->update([
            'is_open_partner'   => true,
            'open_partner_note' => $validated['open_partner_note'] ?? null,
        ]);

        return redirect()->route('invitations.show', $trip)->with('success', 'Status perjalanan berhasil diubah menjadi Open Partner! 🤝');
    }

    /**
     * Nonaktifkan status Open Partner oleh Host.
     */
    public function deactivate(Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Hanya pembuat perjalanan (Host) yang dapat mengubah status ini.');
        }

        $trip->update([
            'is_open_partner' => false,
        ]);

        return redirect()->route('invitations.show', $trip)->with('success', 'Status Open Partner dinonaktifkan.');
    }

    /**
     * Kirim permohonan gabung partner oleh explorer lain.
     */
    public function sendRequest(Request $request, Trip $trip)
    {
        $user = Auth::user();

        if ($trip->user_id === $user->id) {
            return back()->with('error', 'Kamu adalah pemilik trip ini.');
        }

        if ($trip->members()->where('users.id', $user->id)->exists()) {
            return back()->with('info', 'Kamu sudah bergabung dalam trip ini.');
        }

        if (!$trip->is_open_partner || $trip->members()->count() >= 2) {
            return back()->with('error', 'Trip ini saat ini tidak membuka lowongan partner atau sudah penuh.');
        }

        // Cek jika sudah pernah mengirim permintaan pending
        $existing = OpenPartnerRequest::where('trip_id', $trip->id)
            ->where('requester_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('info', 'Kamu sudah mengirimkan permohonan gabung untuk trip ini. Mohon tunggu respon dari host.');
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:600',
        ]);

        $partnerRequest = OpenPartnerRequest::create([
            'trip_id'      => $trip->id,
            'requester_id' => $user->id,
            'message'      => $validated['message'] ?? null,
            'status'       => 'pending',
        ]);

        // Kirim notifikasi ke Host
        $host = $trip->creator;
        if ($host) {
            $host->notify(new AppActivityNotification(
                "{$user->name} mengirim permohonan gabung partner ke trip '{$trip->title}' 🤝",
                '🤝',
                route('partner-requests.index', $trip),
                'open_partner_request'
            ));
        }

        return back()->with('success', 'Permohonan gabung berhasil dikirim ke Host trip! 🎉');
    }

    /**
     * Daftar permohonan gabung yang masuk untuk trip ini (Khusus Host).
     */
    public function requests(Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Hanya pembuat perjalanan (Host) yang dapat melihat daftar permohonan partner.');
        }

        $requests = $trip->openPartnerRequests()
            ->with('requester')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
            ->latest()
            ->get();

        return view('trips.partner_requests.index', compact('trip', 'requests'));
    }

    /**
     * Detail satu permohonan gabung (Khusus Host).
     */
    public function requestDetail(Trip $trip, OpenPartnerRequest $partnerRequest)
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Hanya pembuat perjalanan (Host) yang dapat melihat detail permohonan.');
        }

        if ($partnerRequest->trip_id !== $trip->id) {
            abort(404);
        }

        $partnerRequest->load('requester');

        return view('trips.partner_requests.show', compact('trip', 'partnerRequest'));
    }

    /**
     * Terima permohonan gabung partner oleh Host.
     */
    public function accept(Trip $trip, OpenPartnerRequest $partnerRequest)
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Hanya pembuat perjalanan (Host) yang dapat menerima partner.');
        }

        if ($partnerRequest->trip_id !== $trip->id) {
            abort(404);
        }

        if (!$partnerRequest->isPending()) {
            return back()->with('info', 'Permohonan ini sudah diproses sebelumnya.');
        }

        if ($trip->members()->count() >= 2) {
            return back()->with('error', 'Trip sudah penuh (2/2 explorer).');
        }

        $requester = $partnerRequest->requester;

        // Tambahkan ke anggota trip
        $trip->members()->syncWithoutDetaching([
            $requester->id => [
                'role'      => 'member',
                'joined_at' => now(),
            ],
        ]);

        // Update status request ini
        $partnerRequest->update(['status' => 'accepted']);

        // Trip sekarang penuh (2 orang) -> Otomatis matikan status Open Partner
        $trip->update(['is_open_partner' => false]);

        // Tolak permohonan pending lainnya dengan notifikasi ramah
        $otherPendingRequests = $trip->openPartnerRequests()
            ->where('id', '!=', $partnerRequest->id)
            ->where('status', 'pending')
            ->with('requester')
            ->get();

        foreach ($otherPendingRequests as $otherReq) {
            $otherReq->update(['status' => 'rejected']);
            if ($otherReq->requester) {
                $otherReq->requester->notify(new AppActivityNotification(
                    "Terima kasih atas ketertarikanmu pada trip '{$trip->title}'. Untuk trip ini kuota partner sudah terpenuhi, tapi jangan berkecil hati ya! Masih banyak trip seru lainnya di TwoGo. Tetap semangat explore! 🌟✈️",
                    '💙',
                    route('search.partner'),
                    'open_partner_rejected'
                ));
            }
        }

        // Kirim notifikasi selamat ke partner yang diterima
        $requester->notify(new AppActivityNotification(
            "Hore! Permintaanmu bergabung ke trip '{$trip->title}' telah diterima oleh {$trip->creator->name}! 🎉 Waktunya petualangan seru berdua!",
            '🎉',
            route('trips.show', $trip),
            'open_partner_accepted'
        ));

        return redirect()->route('trips.show', $trip)->with('success', "Selamat! {$requester->name} resmi menjadi partner perjalananmu. Trip ini sekarang siap dijelajahi berdua! 🚀");
    }

    /**
     * Tolak permohonan gabung partner oleh Host.
     */
    public function reject(Trip $trip, OpenPartnerRequest $partnerRequest)
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Hanya pembuat perjalanan (Host) yang dapat menolak partner.');
        }

        if ($partnerRequest->trip_id !== $trip->id) {
            abort(404);
        }

        if (!$partnerRequest->isPending()) {
            return back()->with('info', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $requester = $partnerRequest->requester;

        $partnerRequest->update(['status' => 'rejected']);

        // Kirim notifikasi yang hangat dan tidak membuat user down / berkecil hati
        if ($requester) {
            $requester->notify(new AppActivityNotification(
                "Terima kasih sudah tertarik dengan trip '{$trip->title}'! Untuk perjalanan kali ini belum berjodoh, tapi jangan berkecil hati ya! Masih banyak petualangan seru dan partner asyik lainnya yang menunggumu di TwoGo. Tetap semangat explore! 🌟✈️",
                '💙',
                route('search.partner'),
                'open_partner_rejected'
            ));
        }

        return redirect()->route('partner-requests.index', $trip)->with('success', 'Permohonan telah ditolak dengan pemberitahuan ramah.');
    }
}
