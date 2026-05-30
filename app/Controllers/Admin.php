<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        // Halaman Dashboard Utama Panitia
        return view('admin/dashboard');
    }

    public function verifikasi_dpt()
    {
        // Halaman untuk memantau Fase 1 (Data DPT)
        // Data tabelnya dirender menggunakan JS Firebase Realtime Listener
        return view('admin/dpt_list');
    }

    public function nominasi()
    {
        $data['title'] = 'Rekap Data Nominasi';
        return view('admin/nominasi_list', $data);
    }

    public function live_diagram()
    {
        $data['title'] = 'Visualisasi Real-Time Suara';
        return view('admin/live_chart', $data);
    }

    public function kandidat()
    {
        $data['title'] = 'Kelola Profil Kandidat';
        return view('admin/kandidat_manage', $data);
    }

    public function upload_kandidat_foto()
    {
        $file = $this->request->getFile('foto_kandidat');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validasi jenis gambar demi keamanan server
            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format file harus JPG, PNG, atau WEBP']);
            }

            // Batasi ukuran file (misal maksimal 2MB)
            if ($file->getSizeByUnit('mb') > 2) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Ukuran file terlalu besar. Maksimal 2MB']);
            }

            // Enkripsi nama file acak baru agar tidak bentrok
            $newName = $file->getRandomName();

            // Simpan otomatis ke folder public/uploads/kandidat/
            $file->move(FCPATH . 'uploads/kandidat/', $newName);

            return $this->response->setJSON([
                'status' => 'success',
                'file_path' => 'uploads/kandidat/' . $newName
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengunggah gambar.']);
    }

    public function pengaturan()
    {
        $data['title'] = 'Pengaturan Aplikasi';
        return view('admin/pengaturan_manage', $data);
    }

    public function upload_logo()
    {
        $file = $this->request->getFile('logo_app');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format file harus JPG, PNG, atau WEBP']);
            }
            if ($file->getSizeByUnit('mb') > 2) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Ukuran file terlalu besar. Maksimal 2MB']);
            }

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/', $newName); // Disimpan langsung di public/uploads/

            return $this->response->setJSON([
                'status' => 'success',
                'file_path' => 'uploads/' . $newName
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengunggah gambar.']);
    }

    public function sync_config()
    {
        // Tangkap data JSON yang dikirim oleh Javascript Frontend
        $data = $this->request->getJSON(true);

        if ($data) {
            // Tulis/Timpa data tersebut ke dalam file config_app.json di root public/
            $path = FCPATH . 'config_app.json';
            file_put_contents($path, json_encode($data));

            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error']);
    }
}
