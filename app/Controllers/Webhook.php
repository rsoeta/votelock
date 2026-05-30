<?php

namespace App\Controllers;

class Webhook extends BaseController
{
    // Method untuk menembakkan pesan massal (Broadcast) ke pemilih
    public function kirim_undangan_wa()
    {
        // Menerima data nomor WA dan Token dari request AJAX Admin
        $nomor_wa = $this->request->getPost('nomor_wa');
        $magic_link = base_url('voter/bilik_suara/' . $this->request->getPost('token'));

        // Konfigurasi pesan WhatsApp
        $pesan = "Halo! Waktunya memilih pimpinan. \nKlik link berikut untuk masuk ke bilik suara digital Anda (hanya bisa 1x pakai):\n" . $magic_link;

        // Logika cURL CI4 untuk mengirim ke API WhatsApp (misal n8n atau Fonnte)
        // $response = trigger_whatsapp_api($nomor_wa, $pesan);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Notifikasi WA berhasil dikirim!'
        ]);
    }
}
