<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $num1 = rand(2, 9);
        $num2 = rand(1, 9);
        session(['captcha_answer' => $num1 + $num2]);
        $captchaQuestion = "Berapa hasil dari $num1 + $num2 ?";

        return view('contact.index', compact('captchaQuestion'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:5',
            'captcha' => 'required|numeric',
        ]);

        $correctAnswer = session('captcha_answer');

        if ($request->input('captcha') != $correctAnswer) {
            return back()->withInput()->with('error', '❌ Verifikasi Manusia Gagal! Jawaban matematika kamu salah. Silakan coba lagi.');
        }

        Feedback::create([
            'name'    => $request->input('name'),
            'email'   => $request->input('email'),
            'subject' => $request->input('subject', 'Kritik & Saran'),
            'message' => $request->input('message'),
        ]);

        // Generate new captcha for next attempt
        session()->forget('captcha_answer');

        return back()->with('success', '🎉 Terima kasih! Kritik & saran kamu berhasil terkirim kepada tim TwoGo.');
    }
}
