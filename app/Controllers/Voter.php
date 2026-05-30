<?php

namespace App\Controllers;

class Voter extends BaseController
{
    // Fase 1: Halaman Form Pendataan
    public function registrasi()
    {
        // Menampilkan halaman form pendaftaran (HTML statis + JS Firebase)
        return view('voter/fase1_registrasi');
    }

    // Fase 2: Halaman Nominasi (Akses via Magic Link / QR)
    // public function nominasi($email_atau_token)
    // {
    //     // View ini akan memuat Firebase JS untuk mengecek status verifikasi pemilih
    //     $data['user_id'] = $email_atau_token;
    //     return view('voter/fase2_nominasi', $data);
    // }

    // Fase 3: Bilik Suara Digital
    // public function bilik_suara($email_atau_token)
    // {
    //     $data['user_id'] = $email_atau_token;
    //     return view('voter/fase3_pemilihan', $data);
    // }

    public function nominasi()
    {
        return view('voter/fase2_nominasi');
    }

    public function bilik_suara()
    {
        return view('voter/fase3_pemilihan');
    }
}
