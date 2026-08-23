<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingFeature;
use App\Models\LandingSetting;
use App\Models\LandingShowcase;
use App\Models\LandingStat;
use App\Models\LandingTestimonial;
use Illuminate\Http\Request;

class AdminLandingController extends Controller
{
    public function index()
    {
        $settings = LandingSetting::all()->pluck('value', 'key')->toArray();
        $features = LandingFeature::orderBy('order')->get();
        $showcases = LandingShowcase::orderBy('order')->get();
        $stats = LandingStat::orderBy('order')->get();
        $testimonials = LandingTestimonial::orderBy('order')->get();

        return view('admin.landing.index', compact('settings', 'features', 'showcases', 'stats', 'testimonials'));
    }

    public function updateSettings(Request $request)
    {
        try {
            $settings = $request->input('settings', []);

            if (is_array($settings)) {
                foreach ($settings as $key => $value) {
                    LandingSetting::setValue($key, is_array($value) ? json_encode($value) : $value);
                }
            }

            $targetDir = public_path('assets/images');
            if (!file_exists($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }

            // Handle hero card image upload
            if ($request->hasFile('hero_card_image') && $request->file('hero_card_image')->isValid()) {
                $file = $request->file('hero_card_image');
                $filename = 'hero_card_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $filename);
                LandingSetting::setValue('hero_card_image', 'assets/images/' . $filename);
            }

            // Handle auth background image upload
            if ($request->hasFile('auth_bg_image') && $request->file('auth_bg_image')->isValid()) {
                $file = $request->file('auth_bg_image');
                $filename = 'auth_bg_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $filename);
                LandingSetting::setValue('auth_bg_image', 'assets/images/' . $filename);
            }

            return back()->with('success', 'Pengaturan teks Landing Page berhasil disimpan!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Features CRUD                                                      */
    /* ------------------------------------------------------------------ */

    public function storeFeature(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon'        => 'required|string|max:10',
            'bg_color'    => 'required|string|max:20',
        ]);

        LandingFeature::create([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'icon'        => $request->input('icon'),
            'bg_color'    => $request->input('bg_color'),
            'text_color'  => $request->input('text_color', '#1A1A2E'),
            'order'       => LandingFeature::count() + 1,
            'is_active'   => true,
        ]);

        return back()->with('success', 'Fitur baru berhasil ditambahkan!');
    }

    public function updateFeature(Request $request, LandingFeature $feature)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon'        => 'required|string|max:10',
            'bg_color'    => 'required|string|max:20',
        ]);

        $feature->update([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'icon'        => $request->input('icon'),
            'bg_color'    => $request->input('bg_color'),
            'text_color'  => $request->input('text_color', '#1A1A2E'),
            'is_active'   => $request->has('is_active'),
        ]);

        return back()->with('success', 'Fitur berhasil diperbarui!');
    }

    public function destroyFeature(LandingFeature $feature)
    {
        $feature->delete();
        return back()->with('success', 'Fitur berhasil dihapus!');
    }

    /* ------------------------------------------------------------------ */
    /*  Stats CRUD                                                         */
    /* ------------------------------------------------------------------ */

    public function storeStat(Request $request)
    {
        $request->validate([
            'number'   => 'required|string|max:50',
            'label'    => 'required|string|max:255',
            'bg_color' => 'required|string|max:20',
        ]);

        LandingStat::create([
            'number'     => $request->input('number'),
            'label'      => $request->input('label'),
            'bg_color'   => $request->input('bg_color'),
            'text_color' => $request->input('text_color', '#1A1A2E'),
            'order'      => LandingStat::count() + 1,
            'is_active'  => true,
        ]);

        return back()->with('success', 'Angka pencapaian baru berhasil ditambahkan!');
    }

    public function updateStat(Request $request, LandingStat $stat)
    {
        $request->validate([
            'number'   => 'required|string|max:50',
            'label'    => 'required|string|max:255',
            'bg_color' => 'required|string|max:20',
        ]);

        $stat->update([
            'number'     => $request->input('number'),
            'label'      => $request->input('label'),
            'bg_color'   => $request->input('bg_color'),
            'text_color' => $request->input('text_color', '#1A1A2E'),
            'is_active'  => $request->has('is_active'),
        ]);

        return back()->with('success', 'Angka pencapaian berhasil diperbarui!');
    }

    public function destroyStat(LandingStat $stat)
    {
        $stat->delete();
        return back()->with('success', 'Angka pencapaian berhasil dihapus!');
    }

    /* ------------------------------------------------------------------ */
    /*  Testimonials CRUD                                                  */
    /* ------------------------------------------------------------------ */

    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'user_name'    => 'required|string|max:255',
            'quote'        => 'required|string',
            'avatar_emoji' => 'required|string|max:10',
        ]);

        LandingTestimonial::create([
            'user_name'    => $request->input('user_name'),
            'user_tier'    => $request->input('user_tier', 'Pelancong TwoGo'),
            'quote'        => $request->input('quote'),
            'avatar_emoji' => $request->input('avatar_emoji'),
            'bg_color'     => $request->input('bg_color', '#FFF3C4'),
            'order'        => LandingTestimonial::count() + 1,
            'is_active'    => true,
        ]);

        return back()->with('success', 'Testimoni baru berhasil ditambahkan!');
    }

    public function updateTestimonial(Request $request, LandingTestimonial $testimonial)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'quote'     => 'required|string',
        ]);

        $testimonial->update([
            'user_name'    => $request->input('user_name'),
            'user_tier'    => $request->input('user_tier'),
            'quote'        => $request->input('quote'),
            'avatar_emoji' => $request->input('avatar_emoji'),
            'bg_color'     => $request->input('bg_color'),
            'is_active'    => $request->has('is_active'),
        ]);

        return back()->with('success', 'Testimoni berhasil diperbarui!');
    }

    public function destroyTestimonial(LandingTestimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimoni berhasil dihapus!');
    }
}
