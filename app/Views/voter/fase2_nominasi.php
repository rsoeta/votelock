<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('login') ?>" class="fixed top-4 right-4 md:top-6 md:right-6 z-50 flex items-center gap-2 bg-white/90 backdrop-blur border border-gray-200 text-gray-500 hover:text-blue-600 hover:shadow-md px-3 py-2 rounded-full font-bold text-xs transition duration-300">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
    </svg>
    <span class="hidden md:inline">Panel Panitia</span>
</a>

<div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 border-t-4 border-blue-600">
    <div class="text-center mb-6">
        <div id="appLogoContainer" class="mb-3 hidden">
            <img id="appLogo" src="" alt="Logo" class="h-16 mx-auto object-contain">
        </div>
        <h1 id="appTitle" class="text-xl font-bold text-gray-800 tracking-tight">Memuat Nama Sistem...</h1>
        <p class="text-sm text-blue-600 font-semibold mt-1">Fase 2: Usulan Nominasi</p>
    </div>

    <div id="loginSection" class="text-center space-y-4">
        <div class="p-4 bg-yellow-50 text-yellow-800 text-sm rounded-lg border border-yellow-200">
            Silakan login untuk memverifikasi hak suara Anda.
        </div>
        <button type="button" id="btnGoogleLogin"
            class="w-full flex justify-center items-center gap-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
            </svg>
            Verifikasi Identitas (Google)
        </button>
    </div>

    <div id="formSection" class="hidden">
        <div class="mb-5 p-3 bg-green-50 text-green-800 text-sm rounded-lg text-center border border-green-200">
            Hak Suara Sah:<br><strong id="displayEmail"></strong>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800">Siapa calon pemimpin yang Anda usulkan?</h3>
            <p class="text-xs text-gray-500 mt-1">Ketikkan beberapa huruf, lalu pilih nama tokoh dari daftar yang muncul.</p>
        </div>

        <form id="formNominasi" class="space-y-5">
            <div class="relative">
                <input type="text" id="namaKandidat" required autocomplete="off" oninput="this.value = this.value.toUpperCase()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-center text-lg font-bold uppercase placeholder-gray-300 shadow-sm"
                    placeholder="KETIK NAMA DI SINI">

                <ul id="suggestionBox" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto text-left divide-y divide-gray-100">
                </ul>
            </div>

            <button type="submit" id="btnSubmit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-sm">
                Kunci & Kirim Usulan
            </button>
        </form>
    </div>
</div>

<div class="mt-6 w-full relative z-10">
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
        query,
        where,
        getDocs,
        serverTimestamp
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
    import {
        GoogleAuthProvider,
        signInWithPopup
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    const appTitle = document.getElementById('appTitle');
    const appLogo = document.getElementById('appLogo');
    const appLogoContainer = document.getElementById('appLogoContainer');
    const provider = new GoogleAuthProvider();
    const navHub = document.getElementById('navHub');

    let userEmail = "";
    let dataPemilihGlobal = null;
    let daftarRekomendasiNama = []; // Tempat menampung database nama
    let faseAktifGlobal = 0;

    // UBAH JADI REAL-TIME LISTENER
    onSnapshot(doc(db, "sistem_kontrol", "konfigurasi_app"), (docSnap) => {
        if (docSnap.exists()) {
            const config = docSnap.data();

            // 1. Sinkronisasi Header
            if (config.nama_aplikasi) appTitle.innerText = config.nama_aplikasi;
            if (config.logo_url && config.logo_url.trim() !== "") {
                appLogo.src = config.logo_url.startsWith('http') ? config.logo_url : '<?= base_url() ?>' + config.logo_url;
                appLogoContainer.classList.remove('hidden');
            }

            // SINKRONISASI CO-BRANDING DINAMIS (Title & Favicon)
            const namaInstansi = config.nama_aplikasi || "VoteLock";
            document.title = "VoteLock - " + namaInstansi + " (Usulan Nominasi)";

            if (config.logo_url && config.logo_url.trim() !== "") {
                const iconUrl = config.logo_url.startsWith('http') ? config.logo_url : '<?= base_url() ?>' + config.logo_url;
                const faviconElement = document.getElementById('dynamic-favicon');
                if (faviconElement) {
                    faviconElement.href = iconUrl;
                }
            }

            // 2. Ambil Kode Fase Aktif & Cetak Hub Bawah
            faseAktifGlobal = config.fase_aktif !== undefined ? config.fase_aktif : 2;
            renderNavHub(faseAktifGlobal);

            // 3. Sistem Proteksi Instan (Gatekeeper Luar)
            const btnLogin = document.getElementById('btnGoogleLogin');
            if (faseAktifGlobal !== 2 && btnLogin) {
                btnLogin.disabled = true;
                btnLogin.className = "w-full flex justify-center items-center gap-3 bg-gray-100 border border-gray-200 text-gray-400 font-semibold py-3 px-4 rounded-lg transition duration-200 cursor-not-allowed";
                btnLogin.innerHTML = '🔒 Akses Fase 2 Ditutup';
            }
        }
    });

    // FUNGSI PENCETAK TOMBOL NAVIGASI HUB
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

    // 2. LOGIKA LOGIN & GATEKEEPER
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

            const docRef = doc(db, "pemilih", userEmail);
            const docSnap = await getDoc(docRef);

            if (!docSnap.exists()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Belum Terdaftar',
                    text: 'Anda belum terdaftar dalam sistem.',
                    width: '280px'
                });
                auth.signOut();
                return;
            }

            dataPemilihGlobal = docSnap.data();

            if (dataPemilihGlobal.status_verifikasi !== 'verified') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Disahkan',
                    html: `Data Anda masih menunggu verifikasi panitia atau ditolak.<br><br><div class="bg-gray-100 p-3 rounded-lg text-left text-sm mt-2"><b>Email:</b> ${userEmail}<br><b>Nama:</b> ${dataPemilihGlobal.nama_lengkap}<br><b>Lembaga:</b> ${dataPemilihGlobal.asal_lembaga}</div>`,
                    width: '320px'
                });
                auth.signOut();
                return;
            }

            if (dataPemilihGlobal.fase2_sudah_memilih === true) {
                Swal.fire({
                    icon: 'info',
                    title: 'Suara Telah Direkam',
                    text: 'Anda sudah mengunci usulan pada fase ini.',
                    width: '280px'
                });
                auth.signOut();
                return;
            }

            // LOLOS! Buka Form & Tarik Database Nama Untuk Auto-Complete
            document.getElementById('loginSection').classList.add('hidden');
            document.getElementById('formSection').classList.remove('hidden');
            document.getElementById('displayEmail').innerText = userEmail;

            await siapkanRekomendasiNama(); // Load master nama

            Swal.close();
            document.getElementById('namaKandidat').focus();

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

    // 3. LOGIKA AUTO-COMPLETE NAMA KANDIDAT
    async function siapkanRekomendasiNama() {
        try {
            // Mengambil semua nama pemilih yang VERIFIED sebagai kandidat potensial
            const q = query(collection(db, "pemilih"), where("status_verifikasi", "==", "verified"));
            const querySnapshot = await getDocs(q);

            let tempNama = [];
            querySnapshot.forEach((doc) => {
                tempNama.push(doc.data().nama_lengkap.toUpperCase());
            });
            // Hapus duplikat
            daftarRekomendasiNama = [...new Set(tempNama)];
        } catch (error) {
            console.error("Gagal menarik daftar nama", error);
        }
    }

    const inputKandidat = document.getElementById('namaKandidat');
    const suggestionBox = document.getElementById('suggestionBox');

    inputKandidat.addEventListener('input', function() {
        const val = this.value.trim().toUpperCase();
        suggestionBox.innerHTML = '';

        if (!val) {
            suggestionBox.classList.add('hidden');
            return;
        }

        // Cari nama yang mirip/mengandung huruf yang diketik
        const filtered = daftarRekomendasiNama.filter(n => n.includes(val));

        if (filtered.length > 0) {
            filtered.forEach(nama => {
                const li = document.createElement('li');
                li.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer text-sm font-bold text-gray-700 transition';
                li.innerText = nama;

                // Jika diklik, masukkan ke input dan sembunyikan kotak
                li.addEventListener('click', () => {
                    inputKandidat.value = nama;
                    suggestionBox.classList.add('hidden');
                });
                suggestionBox.appendChild(li);
            });
            suggestionBox.classList.remove('hidden');
        } else {
            suggestionBox.classList.add('hidden');
        }
    });

    // Sembunyikan kotak saran jika klik di luar
    document.addEventListener('click', (e) => {
        if (e.target !== inputKandidat && e.target !== suggestionBox) {
            suggestionBox.classList.add('hidden');
        }
    });

    // 4. SUBMIT SUARA NOMINASI
    const form = document.getElementById('formNominasi');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const namaCalon = document.getElementById('namaKandidat').value.trim().toUpperCase();

        if (namaCalon.length < 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Nama Terlalu Pendek',
                text: 'Mohon masukkan nama yang jelas.',
                width: '280px'
            });
            return;
        }

        Swal.fire({
            title: 'Kunci Usulan?',
            html: `Anda akan mengusulkan:<br><strong class="text-lg text-blue-700 block mt-2">${namaCalon}</strong><br><span class="text-xs text-red-500 mt-2 block">Aksi ini tidak dapat dibatalkan.</span>`,
            icon: 'question',
            width: '280px',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Merekam Suara...',
                    width: '280px',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    await updateDoc(doc(db, "pemilih", userEmail), {
                        fase2_sudah_memilih: true,
                        fase2_pilihan_nama: namaCalon,
                        fase2_waktu_memilih: serverTimestamp()
                    });

                    Swal.fire({
                            icon: 'success',
                            title: 'Suara Sah!',
                            text: 'Terima kasih, usulan Anda telah terkunci dalam sistem.',
                            width: '280px',
                            confirmButtonColor: '#16a34a',
                            allowOutsideClick: false
                        })
                        .then(() => {
                            document.getElementById('formSection').innerHTML = '<div class="text-center p-6 bg-blue-50 text-blue-800 rounded-lg font-bold border border-blue-200">SUARA ANDA TELAH TEREKAM SECARA DIGITAL.</div>';
                            auth.signOut();
                        });

                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan sistem.',
                        width: '280px'
                    });
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>