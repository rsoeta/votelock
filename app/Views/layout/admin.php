<?php
// LOGIKA PHP MEMBACA CACHE KONFIGURASI UNTUK METATAGS
$configPath = FCPATH . 'config_app.json';

// 1. Tentukan Nilai Default (Jika file konfigurasi belum ada/kosong)
$namaInstansi = 'Sedang memuat...';
$appLogoOG    = base_url('assets/img/votelock-share.jpg');
$faviconUrl   = base_url('assets/img/favicon.png');
$deskripsiWA  = 'Sedang memuat...';

// 2. Baca file konfigurasi (Hanya lakukan 1x baca agar server lebih ringan)
if (file_exists($configPath)) {
    $configApp = json_decode(file_get_contents($configPath), true);

    // Timpa nilai default jika data dari admin tersedia
    if (!empty($configApp['nama_aplikasi'])) {
        $namaInstansi = $configApp['nama_aplikasi'];
    }

    if (!empty($configApp['logo_url'])) {
        $logo = $configApp['logo_url'];
        // Cek apakah url luar (http) atau gambar lokal CI4
        $appLogoOG = (strpos($logo, 'http') === 0) ? $logo : base_url($logo);
        $faviconUrl = $appLogoOG; // Gunakan logo yayasan sebagai Favicon
    }

    // Tangkap data deskripsi WA
    if (!empty($configApp['deskripsi_wa'])) {
        $deskripsiWA = $configApp['deskripsi_wa'];
    }
}

// 3. Format Title Utama
$finalTitle = isset($title) ? 'VoteLock - ' . $title : 'VoteLock - ' . $namaInstansi;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- MANTRA PENOLAK MODE GELAP -->
    <meta name="color-scheme" content="light only">
    <title><?= htmlspecialchars($finalTitle) ?></title>

    <link rel="icon" type="image/png" href="<?= $faviconUrl ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</head>

<body class="bg-gray-50 text-gray-800 flex h-screen overflow-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 hidden transition-opacity"></div>

    <aside id="adminSidebar" class="fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out w-64 bg-blue-900 text-white flex flex-col z-30 shadow-2xl h-full">
        <div class="p-5 text-center border-b border-blue-800 flex justify-between items-center md:block">
            <div class="md:w-full text-left md:text-center overflow-hidden">
                <h2 class="text-xl font-bold tracking-wider">VoteLock</h2>
                <p class="text-[11px] text-blue-300 mt-0.5 truncate" title="<?= htmlspecialchars($namaInstansi) ?>">
                    <?= htmlspecialchars($namaInstansi) ?>
                </p>
            </div>
            <button id="btnCloseMenu" class="md:hidden text-blue-200 hover:text-white">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <nav class="flex-grow p-4 space-y-1 overflow-y-auto">
            <a href="<?= base_url('panel/dashboard') ?>" class="block py-2.5 px-4 rounded transition duration-200 text-sm <?= (current_url() == base_url('panel/dashboard')) ? 'bg-blue-800 text-white font-bold' : 'hover:bg-blue-800 hover:text-white text-blue-200' ?>">
                📊 Dasbor Utama
            </a>
            <a href="<?= base_url('panel/dpt') ?>" class="block py-2.5 px-4 rounded transition duration-200 text-sm <?= (current_url() == base_url('panel/dpt')) ? 'bg-blue-800 text-white font-bold' : 'hover:bg-blue-800 hover:text-white text-blue-200' ?>">
                👥 Verifikasi DPT
            </a>
            <a href="<?= base_url('panel/nominasi') ?>" class="block py-2.5 px-4 rounded transition duration-200 text-sm <?= (current_url() == base_url('panel/nominasi')) ? 'bg-blue-800 text-white font-bold' : 'hover:bg-blue-800 hover:text-white text-blue-200' ?>">
                🗳️ Data Nominasi
            </a>
            <a href="<?= base_url('panel/kandidat') ?>" class="block py-2.5 px-4 rounded transition duration-200 text-sm <?= (current_url() == base_url('panel/kandidat')) ? 'bg-blue-800 text-white font-bold' : 'hover:bg-blue-800 hover:text-white text-blue-200' ?>">
                🎓 Kelola Profil Kandidat
            </a>
            <a href="<?= base_url('panel/live') ?>" class="block py-2.5 px-4 rounded transition duration-200 text-sm <?= (current_url() == base_url('panel/live')) ? 'bg-blue-800 text-white font-bold' : 'hover:bg-blue-800 hover:text-white text-blue-200' ?>">
                📈 Live Diagram
            </a>
            <a href="<?= base_url('panel/pengaturan') ?>" class="block py-2.5 px-4 rounded transition duration-200 text-sm <?= (current_url() == base_url('panel/pengaturan')) ? 'bg-blue-800 text-white font-bold' : 'hover:bg-blue-800 hover:text-white text-blue-200' ?>">
                ⚙️ Pengaturan Aplikasi
            </a>
        </nav>

        <div class="p-4 border-t border-blue-800">
            <button onclick="confirmLogout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded transition duration-200 text-sm font-medium flex items-center justify-center gap-2">
                Keluar (Logout)
            </button>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <header class="bg-white shadow-sm py-3 px-6 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <button id="btnOpenMenu" class="md:hidden text-gray-600 hover:text-blue-600 focus:outline-none transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-800"><?= $title ?? 'Ruang Kendali' ?></h1>
            </div>
            <div class="text-xs font-medium text-gray-500 flex items-center gap-2 bg-gray-100 py-1.5 px-3 rounded-full border border-gray-200">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <?= session()->get('admin_email') ?>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 md:p-6 flex flex-col">

            <div class="flex-grow">
                <?= $this->renderSection('content') ?>
            </div>

            <footer class="mt-8 pt-4 pb-2 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
                <div class="mb-2 md:mb-0">
                    &copy; <?= date('Y') ?> <span class="font-bold text-blue-900 tracking-wide">VoteLock</span> Enterprise. All rights reserved.
                </div>

                <div class="flex items-center gap-1.5 font-medium">
                    <span>Handcrafted with</span>
                    <svg class="w-3.5 h-3.5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                    <span>by</span>
                    <a href="https://studio-pasti.net" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold transition flex items-center gap-1 group">
                        PASTI Tech Studio
                        <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </footer>

        </main>

    </div>


    <script>
        // Logika Hamburger Menu
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const btnOpen = document.getElementById('btnOpenMenu');
        const btnClose = document.getElementById('btnCloseMenu');

        function toggleMenu() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        btnOpen.addEventListener('click', toggleMenu);
        btnClose.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu); // Tutup jika klik area gelap

        // Logika Logout
        function confirmLogout() {
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Anda akan keluar dari panel panitia.",
                icon: 'warning',
                width: '280px',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('logout') ?>';
                }
            })
        }
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>