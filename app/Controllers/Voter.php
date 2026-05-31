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

    // Fase 2: Halaman Nominasi
    public function nominasi()
    {
        return view('voter/fase2_nominasi');
    }

    // Fase 3: Bilik Suara Digital
    public function bilik_suara()
    {
        return view('voter/fase3_pemilihan');
    }
}
