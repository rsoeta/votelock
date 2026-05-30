<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('login') ?>" class="fixed top-4 right-4 md:top-6 md:right-6 z-50 flex items-center gap-2 bg-white/90 backdrop-blur border border-gray-200 text-gray-500 hover:text-blue-600 hover:shadow-md px-3 py-2 rounded-full font-bold text-xs transition duration-300">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
    </svg>
    <span class="hidden md:inline">Panel Panitia</span>
</a>

<style>
    /* Trik Break-Out: Memaksa Card Fase 3 melebar di Desktop mengabaikan max-w-md dari layout master */
    @media (min-width: 768px) {
        .wide-card-override {
            width: 90vw !important;
            max-width: 1100px !important;
            margin-left: 50% !important;
            transform: translateX(-50%) !important;
        }
    }

    /* Modifikasi Scrollbar agar halus saat header sticky bekerja */
    html {
        scroll-behavior: smooth;
    }
</style>

<div class="wide-card-override bg-white rounded-2xl shadow-lg p-6 md:p-8 border-t-4 border-blue-600 relative">

    <div class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm pt-6 pb-4 mb-6 border-b border-gray-100 -mt-6 -mx-6 px-6 md:-mt-8 md:-mx-8 md:px-8 rounded-t-2xl shadow-sm">
        <div id="appLogoContainer" class="mb-3 hidden">
            <img id="appLogo" src="" alt="Logo" class="h-16 mx-auto object-contain">
        </div>
        <h1 id="appTitle" class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight text-center">Memuat Nama Sistem...</h1>
        <p class="text-xs md:text-sm text-blue-600 font-semibold mt-1 text-center">Fase 3: Bilik Suara Penentuan</p>
    </div>

    <div id="loginSection" class="text-center space-y-4 max-w-md mx-auto">
        <button type="button" id="btnGoogleLogin" class="w-full flex justify-center items-center gap-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
            </svg>
            Daftar dengan Akun Google
        </button>
    </div>

    <div id="votingSection" class="hidden">
        <div class="mb-6 p-3 bg-green-50 text-green-800 text-sm rounded-lg text-center border border-green-200 max-w-md mx-auto">
            Hak Suara Sah:<br><strong id="displayEmail"></strong>
        </div>

        <div id="kandidatContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="col-span-1 md:col-span-3 text-center py-6 text-gray-500">
                <svg class="animate-spin h-6 w-6 text-blue-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Membuka Bilik Suara...
            </div>
        </div>
    </div>
</div>

<div class="mt-6 w-full max-w-md mx-auto relative z-10 px-4 md:px-0">
    <div class="flex items-center justify-center gap-2 mb-3">
        <div class="h-px bg-gray-300 flex-1"></div>
        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Akses Bilik Pemilihan</span>
        <div class="h-px bg-gray-300 flex-1"></div>
    </div>
    <div id="navHub" class="grid grid-cols-3 gap-3">
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="module">
    import {
        db,
        auth
    } from '<?= base_url('assets/js/firebase-init.js') ?>';
    import {
        doc,
        getDoc,
        onSnapshot,
        updateDoc,
        collection,
        getDocs,
        query,
        orderBy,
        serverTimestamp
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
    import {
        GoogleAuthProvider,
        signInWithPopup
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    const appTitle = document.getElementById('appTitle');
    const appLogo = document.getElementById('appLogo');
    const appLogoContainer = document.getElementById('appLogoContainer');
    const navHub = document.getElementById('navHub');

    let faseAktifGlobal = 0;
    let userEmail = "";
    const provider = new GoogleAuthProvider();

    // MONITOR SINKRONISASI FASE & HUB JALAN DI SINI
    onSnapshot(doc(db, "sistem_kontrol", "konfigurasi_app"), (docSnap) => {
        if (docSnap.exists()) {
            const config = docSnap.data();

            // Sinkronisasi Judul & Logo
            appTitle.innerText = config.nama_aplikasi || "VoteLock";
            if (config.logo_url && config.logo_url.trim() !== "") {
                appLogo.src = config.logo_url.startsWith('http') ? config.logo_url : '<?= base_url() ?>' + config.logo_url;
                appLogoContainer.classList.remove('hidden');
            }

            // SINKRONISASI CO-BRANDING DINAMIS (Title & Favicon)
            const namaInstansi = config.nama_aplikasi || "VoteLock";
            document.title = "VoteLock - " + namaInstansi + " (Bilik Suara)";

            if (config.logo_url && config.logo_url.trim() !== "") {
                const iconUrl = config.logo_url.startsWith('http') ? config.logo_url : '<?= base_url() ?>' + config.logo_url;
                const faviconElement = document.getElementById('dynamic-favicon');
                if (faviconElement) {
                    faviconElement.href = iconUrl;
                }
            }

            // Jalankan Navigasi Hub
            faseAktifGlobal = config.fase_aktif !== undefined ? config.fase_aktif : 3;
            renderNavHub(faseAktifGlobal);

            // Gatekeeper Tombol Coblos Utama jika fase ditutup
            const btnLogin = document.getElementById('btnGoogleLogin');
            if (faseAktifGlobal !== 3 && btnLogin) {
                btnLogin.disabled = true;
                btnLogin.className = "w-full flex justify-center items-center gap-3 bg-gray-100 border border-gray-200 text-gray-400 font-semibold py-3 px-4 rounded-lg transition duration-200 cursor-not-allowed max-w-md mx-auto";
                btnLogin.innerHTML = '🔒 Akses Bilik Suara Ditutup';
            }
        }
    });

    // FUNGSI RENDER HUB NAVIGASI (Sama demi standarisasi sistem)
    function renderNavHub(fase) {
        const phases = [{
                id: 1,
                name: 'Pendataan',
                url: '<?= base_url('/') ?>',
                icon: '📝'
            },
            {
                id: 2,
                name: 'Nominasi',
                url: '<?= base_url('nominasi') ?>',
                icon: '🗳️'
            },
            {
                id: 3,
                name: 'Coblos',
                url: '<?= base_url('vote') ?>',
                icon: '🏆'
            }
        ];

        let html = '';
        phases.forEach(p => {
            if (fase === p.id) {
                html += `
                <a href="${p.url}" class="flex flex-col items-center justify-center py-4 bg-blue-600 text-white rounded-2xl shadow-lg border-2 border-blue-400 transform transition hover:scale-105 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    <span class="text-2xl mb-1">${p.icon}</span>
                    <span class="text-[10px] font-black uppercase tracking-wider">${p.name}</span>
                    <span class="absolute top-2 right-2 flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span></span>
                </a>`;
            } else {
                html += `
                <a href="#" onclick="event.preventDefault(); Swal.fire({icon:'warning', title:'Akses Tertutup', text:'Fase ini sedang tidak aktif.', customClass: {popup: 'rounded-2xl'}})" 
                   class="flex flex-col items-center justify-center py-4 bg-white text-gray-400 rounded-2xl shadow-sm border border-gray-200 hover:bg-gray-50 transition cursor-not-allowed">
                    <span class="text-2xl mb-1 opacity-50 grayscale">🔒</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider">${p.name}</span>
                </a>`;
            }
        });
        navHub.innerHTML = html;
    }
    // -----------------------------------------------------

    document.getElementById('btnGoogleLogin').addEventListener('click', async () => {
        try {
            const result = await signInWithPopup(auth, provider);
            userEmail = result.user.email;

            Swal.fire({
                title: 'Memeriksa Hak Suara...',
                width: '280px',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const docSnap = await getDoc(doc(db, "pemilih", userEmail));

            // GATEKEEPER LOGIC
            if (!docSnap.exists()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ditolak',
                    text: 'Anda belum terdaftar.',
                    width: '280px'
                });
                auth.signOut();
                return;
            }

            const dataPemilih = docSnap.data();
            if (dataPemilih.status_verifikasi !== 'verified') {
                // --- ALERT DIPERBARUI DENGAN DETAIL IDENTITAS ---
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Disahkan',
                    html: `Data Anda tidak valid atau belum disahkan panitia.<br><br><div class="bg-gray-100 p-3 rounded-lg text-left text-sm mt-2"><b>Email:</b> ${userEmail}<br><b>Nama:</b> ${dataPemilih.nama_lengkap}<br><b>Lembaga:</b> ${dataPemilih.asal_lembaga}</div>`,
                    width: '320px'
                });
                auth.signOut();
                return;
            }

            if (dataPemilih.fase3_sudah_memilih === true) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sudah Memilih',
                    text: 'Sistem mencatat Anda sudah menggunakan hak suara pada fase ini.',
                    width: '280px'
                });
                auth.signOut();
                return;
            }

            // Lolos Verifikasi, Muat Data Kandidat
            document.getElementById('loginSection').classList.add('hidden');
            document.getElementById('votingSection').classList.remove('hidden');
            document.getElementById('displayEmail').innerText = userEmail;

            await muatKandidat();
            Swal.close();

        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Akses Batal',
                text: 'Gagal memuat akun Google.',
                width: '280px'
            });
        }
    });

    async function muatKandidat() {
        const kandidatContainer = document.getElementById('kandidatContainer');
        kandidatContainer.innerHTML = '';

        try {
            const q = query(collection(db, "kandidat_final"), orderBy("nomor_urut", "asc"));
            const querySnapshot = await getDocs(q);

            if (querySnapshot.empty) {
                kandidatContainer.innerHTML = '<p class="text-center text-red-500 font-bold">Data kandidat belum disiapkan panitia.</p>';
                return;
            }

            querySnapshot.forEach((docSnap) => {
                const k = docSnap.data();
                const kId = docSnap.id;

                // Gambar Default jika foto_url kosong
                const avatar = k.foto_url ? k.foto_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(k.nama_tokoh)}&background=1e3a8a&color=fff&size=128`;

                const card = document.createElement('div');
                // PERUBAHAN SUSUNAN CARD MENJADI VERTIKAL (FLEX-COL) DAN TINGGI PENUH (H-FULL)
                card.className = "flex flex-col items-center p-5 border border-gray-200 rounded-xl hover:border-blue-500 hover:shadow-lg transition bg-gray-50 h-full relative";

                card.innerHTML = `
                    <div class="flex-shrink-0 relative mb-4 mt-2">
                        <img class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-sm" src="${avatar}" alt="Kandidat">
                        <span class="absolute -top-2 -right-2 bg-blue-700 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold border-2 border-white shadow-sm">${k.nomor_urut}</span>
                    </div>
                    <div class="flex-1 flex flex-col text-center w-full h-full">
                        <h4 class="text-lg font-extrabold text-gray-900 uppercase">${k.nama_tokoh}</h4>
                        
                        <div class="flex-grow text-[11px] md:text-xs text-gray-700 mt-3 mb-5 text-justify whitespace-pre-line leading-relaxed bg-white p-4 border border-gray-100 rounded-lg shadow-inner w-full">
                            ${k.visi_misi}
                        </div>

                        <button class="btn-pilih w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-sm mt-auto" data-id="${kId}" data-nama="${k.nama_tokoh}">
                            COBLOS KANDIDAT
                        </button>
                    </div>
                `;
                kandidatContainer.appendChild(card);
            });

            registerTombolPilih();

        } catch (error) {
            console.error(error);
            kandidatContainer.innerHTML = '<p class="text-center text-red-500 font-bold">Gagal memuat kandidat.</p>';
        }
    }

    function registerTombolPilih() {
        document.querySelectorAll('.btn-pilih').forEach(btn => {
            btn.addEventListener('click', function() {
                const kandidatId = this.getAttribute('data-id');
                const kandidatNama = this.getAttribute('data-nama');

                Swal.fire({
                    title: 'Konfirmasi Pilihan',
                    html: `Anda akan memberikan suara untuk:<br><strong class="text-xl text-blue-700 block mt-2">${kandidatNama}</strong><br><span class="text-xs text-red-500 block mt-3">Suara yang masuk tidak dapat diubah/ditarik kembali.</span>`,
                    icon: 'warning',
                    width: '300px',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Sahkan Suara',
                    cancelButtonText: 'Kembali'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyegel Suara...',
                            width: '280px',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            await updateDoc(doc(db, "pemilih", userEmail), {
                                fase3_sudah_memilih: true,
                                fase3_pilihan_id: kandidatId,
                                fase3_waktu_memilih: serverTimestamp()
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'Suara Sah!',
                                text: 'Hak suara Anda telah terekam dan disegel digital.',
                                width: '280px',
                                allowOutsideClick: false,
                                confirmButtonColor: '#1e3a8a'
                            }).then(() => {
                                document.getElementById('votingSection').innerHTML = '<div class="text-center p-8 bg-blue-50 text-blue-800 rounded-xl font-extrabold border border-blue-200 shadow-sm text-lg">VOTING SELESAI.<br><span class="text-sm font-normal">Terima kasih atas partisipasi Anda.</span></div>';
                                auth.signOut();
                            });
                        } catch (error) {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan jaringan.',
                                width: '280px'
                            });
                        }
                    }
                });
            });
        });
    }
</script>
<?= $this->endSection() ?>