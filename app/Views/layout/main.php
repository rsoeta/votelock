<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php
        // LOGIKA PHP MEMBACA CACHE KONFIGURASI UNTUK METATAGS
        $configPath = FCPATH . 'config_app.json';

        // Nilai Default
        $namaInstansi = 'Sistem e-Voting Aman & Modern';
        $appLogoOG = base_url('assets/img/votelock-share.jpg');
        $faviconUrl = base_url('assets/img/favicon.png');

        // Jika file config hasil sinkronisasi Admin ada, timpa nilai default
        if (file_exists($configPath)) {
            $configApp = json_decode(file_get_contents($configPath), true);
            if (!empty($configApp['nama_aplikasi'])) {
                $namaInstansi = $configApp['nama_aplikasi'];
            }
            if (!empty($configApp['logo_url'])) {
                $logo = $configApp['logo_url'];
                // Cek apakah url luar (http) atau gambar lokal CI4
                $appLogoOG = (strpos($logo, 'http') === 0) ? $logo : base_url($logo);
                $faviconUrl = $appLogoOG; // Gunakan logo yayasan sebagai Favicon!
            }
        }

        // Format Title Utama: VoteLock - Title Spesifik Controller / Nama Instansi
        $finalTitle = isset($title) ? 'VoteLock - ' . $title : 'VoteLock - ' . $namaInstansi;
        ?>

        <title><?= $finalTitle ?></title>
        <meta name="description" content="Portal bilik suara digital resmi. Gunakan hak pilih Anda secara jujur, transparan, dan rahasia.">

        <meta property="og:type" content="website">
        <meta property="og:url" content="<?= current_url() ?>">
        <meta property="og:title" content="<?= $finalTitle ?>">
        <meta property="og:description" content="Mari berpartisipasi! Gunakan hak suara Anda dengan aman, rahasia, dan transparan melalui bilik suara digital <?= htmlspecialchars($namaInstansi) ?>.">

        <meta property="og:image" content="<?= $appLogoOG ?>">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">

        <link id="dynamic-favicon" rel="icon" type="image/png" href="<?= $faviconUrl ?>">

        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Ubuntu', 'sans-serif']
                        }
                    }
                }
            }
        </script>

        <style>
            /* Ukuran dasar Mobile: Diperkecil agar Card tidak terlihat raksasa */
            html {
                font-size: 13px;
            }

            /* Ukuran dasar Desktop/Tablet: Diperbesar ke standar baca normal */
            @media (min-width: 768px) {
                html {
                    font-size: 15px;
                }
            }

            /* Penyesuaian SweetAlert2 agar presisi dengan ukuran layar */
            .swal2-popup {
                font-size: 0.9rem !important;
                border-radius: 12px;
                font-family: 'Ubuntu', sans-serif;
            }

            body {
                font-size: 1rem;
                line-height: 1.5;
            }
        </style>
        <style>
            .swal2-popup {
                font-size: 0.85rem !important;
                border-radius: 15px;
            }
        </style>
    </head>

<body class="bg-gray-50 min-h-screen flex flex-col font-sans">

    <main class="flex-grow flex flex-col justify-center items-center w-full px-4 py-8">
        <div class="w-full max-w-md">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <footer class="text-center py-4 text-xs text-gray-500">
        &copy; <?= date('Y') ?> VoteLock by PASTI. All rights reserved.
    </footer>

    <?= $this->renderSection('scripts') ?>
</body>

</html>