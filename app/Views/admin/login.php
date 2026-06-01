<?php
// BACA CACHE KONFIGURASI LOKAL
$configPath = FCPATH . 'config_app.json';
$namaInstansi = 'Sedang memuat...'; // Nama instansi default jika file konfigurasi belum ada
$appLogoOG = base_url('assets/img/votelock-share.jpg'); // Gambar default untuk Open Graph
$faviconUrl = base_url('assets/img/favicon.png'); // Favicon bawaan jika kosong

if (file_exists($configPath)) {
    $configApp = json_decode(file_get_contents($configPath), true);
    if (!empty($configApp['nama_aplikasi'])) {
        $namaInstansi = $configApp['nama_aplikasi'];
    }
    if (!empty($configApp['favicon'])) {
        $faviconUrl = base_url($configApp['favicon']);
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- MANTRA PENOLAK MODE GELAP -->
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Login Panitia - <?= htmlspecialchars($namaInstansi) ?></title>

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
            },
            darkMode: 'class', // MANTRA 1: Mengunci Tailwind agar hanya mau gelap kalau kita perintahkan lewat class khusus, bukan dari sistem HP
        }
    </script>

    <style>
        /* Ukuran dasar Mobile: Diperkecil agar Card tidak terlihat raksasa */
        html {
            font-size: 13px;
        }

        html,
        body {
            background-color: #ffffff !important;
            color-scheme: light only;
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

<body class="bg-[#fefefe] text-gray-800 min-h-screen flex items-center justify-center font-sans p-4">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border-t-4 border-blue-800 text-center">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">VoteLock</h1>
            <h6 class="text-lg font-semibold text-gray-700 leading-snug mt-1"><?= htmlspecialchars($namaInstansi) ?></h6>
            <p class="text-sm text-gray-500 mt-2">Panel Kontrol Panitia</p>
        </div>

        <button type="button" id="btnGoogleLoginAdmin"
            class="w-full flex justify-center items-center gap-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-4 px-4 rounded-lg transition duration-200 shadow-sm mb-4">
            <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
            </svg>
            Masuk sebagai Panitia
        </button>

        <p class="text-xs text-red-500 mt-2 font-medium hidden" id="errorMsg">Akses ditolak. Email tidak terdaftar sebagai Panitia.</p>

        <div id="navHubWrapper" class="mt-6 w-full relative z-10">
            <div class="flex items-center justify-center gap-2 mb-3">
                <div class="h-px bg-gray-300 flex-1"></div>
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">FASE BERJALAN</span>
                <div class="h-px bg-gray-300 flex-1"></div>
            </div>
            <!-- Ubah class menjadi flex dan justify-center -->
            <div id="navHub" class="flex justify-center gap-3">
            </div>
        </div>

        <div class="mt-8">
            <p class="text-xs text-gray-400">&copy; <?= date('Y') ?> PASTI Tech Studio. All rights reserved.</p>
        </div>
    </div>

    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
        import {
            getFirestore,
            doc,
            onSnapshot,
            getDoc
        } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
        import {
            getAuth,
            GoogleAuthProvider,
            signInWithPopup
        } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

        // Ganti dengan konfigurasi Firebase Anda (Bisa juga import dari firebase-init.js)
        const firebaseConfig = {
            apiKey: "AIzaSyDqE5VxK2kmXNiKduLBMzSQc1oI9347Kiw",
            authDomain: "ppi-94-pkj.firebaseapp.com",
            projectId: "ppi-94-pkj",
            storageBucket: "ppi-94-pkj.firebasestorage.app",
            messagingSenderId: "140599266448",
            appId: "1:140599266448:web:3a7d72b2727d4ed00e1c15"
        };

        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        const btnLogin = document.getElementById('btnGoogleLoginAdmin');
        const errorMsg = document.getElementById('errorMsg');

        btnLogin.addEventListener('click', async () => {
            errorMsg.classList.add('hidden'); // Sembunyikan error lama

            try {
                // 1. Munculkan Popup Google Login
                const result = await signInWithPopup(auth, provider);
                const userEmail = result.user.email;

                // Memunculkan SweetAlert2 loading ukuran mobile
                Swal.fire({
                    title: 'Memverifikasi Akses...',
                    text: 'Mengecek database panitia',
                    width: '280px',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // 2. Cek apakah email ini terdaftar di koleksi "admin"
                const docRef = doc(db, "admin", userEmail);
                const docSnap = await getDoc(docRef);

                if (docSnap.exists()) {
                    // 3. Email Valid! Beritahu CI4 untuk membuat Session PHP
                    const formData = new FormData();
                    formData.append('email', userEmail);

                    const response = await fetch('<?= base_url('login/process') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const ciResult = await response.json();

                    if (ciResult.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Selamat Datang!',
                            text: ciResult.message,
                            width: '280px',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= base_url('panel/dashboard') ?>'; // Arahkan ke Dasbor
                        });
                    }
                } else {
                    // Email login Google berhasil, tapi tidak terdaftar di Koleksi admin
                    auth.signOut(); // Langsung keluarkan dari Firebase Auth
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Ditolak!',
                        text: 'Email Anda tidak terdaftar sebagai Panitia.',
                        width: '280px',
                        confirmButtonColor: '#d33'
                    });
                    errorMsg.classList.remove('hidden');
                }

            } catch (error) {
                console.error('Error saat login:', error);
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Dibatalkan',
                    text: 'Proses otentikasi gagal atau dibatalkan.',
                    width: '280px'
                });
            }
        });

        // FUNGSI RENDER PINTASAN NAVIGASI (NAVHUB)
        const navHub = document.getElementById('navHub');

        // RENDER PINTASAN NAVIGASI (HANYA TAMPIL YANG AKTIF)
        function renderNavHub(faseAktif) {
            const navHub = document.getElementById('navHub');
            const navHubWrapper = document.getElementById('navHubWrapper');

            // Sembunyikan seluruh blok pembatas jika tidak ada fase yang aktif (Kunci Total)
            if (faseAktif === 0) {
                if (navHubWrapper) navHubWrapper.classList.add('hidden');
                return;
            } else {
                if (navHubWrapper) navHubWrapper.classList.remove('hidden');
            }

            const phases = [{
                    id: 1,
                    label: 'Fase 1',
                    name: 'Pendataan',
                    icon: '📝',
                    url: '<?= base_url('/') ?>'
                },
                {
                    id: 2,
                    label: 'Fase 2',
                    name: 'Nominasi',
                    icon: '💡',
                    url: '<?= base_url('nominasi') ?>'
                },
                {
                    id: 3,
                    label: 'Fase 3',
                    name: 'Coblos',
                    icon: '🗳️',
                    url: '<?= base_url('pemilihan') ?>'
                } // Pastikan base_url sesuai routing Anda
            ];

            navHub.innerHTML = '';
            phases.forEach(p => {
                // HANYA RENDER (LUKIS) TOMBOL JIKA FASENYA SEDANG AKTIF
                if (p.id === faseAktif) {
                    navHub.innerHTML += `
                <a href="${p.url}" class="flex flex-col items-center justify-center p-3 w-40 rounded-2xl border-2 border-blue-600 bg-blue-50 text-blue-700 shadow-md transition transform hover:scale-105 relative animate-fade-in">
                    <span class="absolute -top-2 -right-2 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-600 border-2 border-white"></span>
                    </span>
                    <span class="text-3xl mb-1">${p.icon}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wide text-blue-600">${p.label}</span>
                    <span class="text-sm font-black uppercase tracking-wider">${p.name}</span>
                </a>`;
                }
            });
        }

        // MONITOR FASE AKTIF REAL-TIME DARI FIRESTORE
        onSnapshot(doc(db, "sistem_kontrol", "konfigurasi_app"), (docSnap) => {
            if (docSnap.exists()) {
                const config = docSnap.data();
                const faseAktifGlobal = config.fase_aktif !== undefined ? config.fase_aktif : 1;
                renderNavHub(faseAktifGlobal);
            }
        });
    </script>
</body>

</html>