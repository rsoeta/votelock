<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index()
    {
        // Jika admin sudah login sebelumnya, langsung arahkan ke Dashboard
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to('/panel/dashboard');
        }

        // Tampilkan halaman form login
        return view('admin/login');
    }

    public function process()
    {
        // Menerima email valid yang dikirim oleh AJAX setelah lolos verifikasi Firebase
        $email = $this->request->getPost('email');

        if (!empty($email)) {
            // Buat Session CI4 untuk admin
            session()->set([
                'is_admin_logged_in' => true,
                'admin_email'        => $email
            ]);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Otentikasi Berhasil! Mengalihkan...'
            ]);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Akses tidak sah!'
            ]);
        }
    }

    public function logout()
    {
        // Hancurkan session saat admin logout
        session()->destroy();
        return redirect()->to('/login');
    }
}
