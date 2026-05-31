<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('login') ?>" class="fixed top-4 right-4 md:top-6 md:right-6 z-50 flex items-center gap-2 bg-white/90 backdrop-blur border border-gray-200 text-gray-500 hover:text-blue-600 hover:shadow-md px-3 py-2 rounded-full font-bold text-xs transition duration-300">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
    </svg>
    <span class="hidden md:inline">Panel Panitia</span>
</a>

<div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 border-t-4 border-blue-600 relative z-10 w-full">

    <div class="text-center mb-6">
        <div id="appLogoContainer" class="mb-3 hidden">
            <img id="appLogo" src="" alt="Logo" class="h-16 mx-auto object-contain">
        </div>
        <h1 id="appTitle" class="text-xl font-bold text-gray-800 tracking-tight">Memuat Nama Sistem...</h1>
        <p id="deskripsiFase1" class="text-xs text-blue-600 font-semibold mt-1">Fase 1: Pendataan Awal Hak Pilih</p>
    </div>

    <div id="loginSection" class="text-center space-y-4">
        <div id="statusAlert" class="p-3 bg-gray-50 text-gray-500 text-xs font-bold rounded-lg border border-gray-200 flex items-center justify-center gap-2">
            <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memeriksa Status Fase...
        </div>
        <button type="button" id="btnGoogleLogin" disabled class="w-full flex justify-center items-center gap-3 bg-gray-100 border border-gray-200 text-gray-400 font-semibold py-3 px-4 rounded-lg transition duration-200 cursor-not-allowed">
            <svg class="w-5 h-5 grayscale opacity-50" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
            </svg>
            Harap Tunggu...
        </button>
    </div>

    <div id="formSection" class="hidden">
        <div class="mb-4 p-2.5 bg-green-50 text-green-800 text-xs rounded-lg text-center border border-green-200">
            Identitas perangkat tertaut:<br><strong id="displayEmail"></strong>
        </div>
        <form id="formPendataan" class="space-y-4">
            <div>
                <label for="regNamaLengkap" class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" id="regNamaLengkap" required placeholder="Contoh: AHMAD ABDULLAH" oninput="this.value = this.value.toUpperCase()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 font-medium shadow-sm uppercase">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="regTempatLahir" class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Tempat Lahir</label>
                    <input type="text" id="regTempatLahir" required placeholder="Contoh: GARUT" oninput="this.value = this.value.toUpperCase()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 font-medium shadow-sm uppercase">
                </div>
                <div>
                    <label for="regTanggalLahir" class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Tanggal Lahir</label>
                    <input type="date" id="regTanggalLahir" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 font-medium shadow-sm">
                </div>
            </div>

            <div>
                <label for="regAlamat" class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Alamat Tinggal / Domisili</label>
                <textarea id="regAlamat" required rows="2" placeholder="MASUKKAN ALAMAT LENGKAP SAAT INI..." oninput="this.value = this.value.toUpperCase()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 font-medium shadow-sm leading-relaxed uppercase"></textarea>
            </div>

            <div>
                <label for="regWA" class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nomor WhatsApp Aktif</label>
                <input type="tel" id="regWA" required placeholder="Contoh: 081234567890" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 font-medium shadow-sm">
            </div>

            <div>
                <label for="asalLembaga" class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Asal Lembaga / Tugas Pokok</label>
                <select id="asalLembaga" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 bg-white text-sm text-gray-700 font-medium shadow-sm">
                    <option value="" disabled selected>-- Pilih Jenjang / Lembaga --</option>
                </select>
            </div>

            <div>
                <label for="regMasaKerja" class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Lama Masa Kerja / Pengabdian</label>
                <select id="regMasaKerja" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 bg-white text-sm text-gray-700 font-medium shadow-sm">
                    <option value="" disabled selected>-- Pilih Lama Masa Kerja --</option>
                </select>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md">
                    📤 Kirim Data Pendataan
                </button>
            </div>
        </form>
    </div>
</div>

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
        setDoc,
        onSnapshot,
        serverTimestamp
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
    import {
        GoogleAuthProvider,
        signInWithPopup
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    const asalLembagaSelect = document.getElementById('asalLembaga');
    const appTitle = document.getElementById('appTitle');
    const appLogo = document.getElementById('appLogo');
    const appLogoContainer = document.getElementById('appLogoContainer');

    const btnGoogleLogin = document.getElementById('btnGoogleLogin');
    const statusAlert = document.getElementById('statusAlert');
    const navHub = document.getElementById('navHub');

    let faseAktifGlobal = 0;
    let userEmail = "";
    const provider = new GoogleAuthProvider();

    // SINKRONISASI REAL-TIME KONFIGURASI DAN FASE
    onSnapshot(doc(db, "sistem_kontrol", "konfigurasi_app"), (docSnap) => {
        if (docSnap.exists()) {
            const config = docSnap.data();

            // Setup Header
            appTitle.innerText = config.nama_aplikasi || "VoteLock";
            if (config.logo_url && config.logo_url.trim() !== "") {
                appLogo.src = config.logo_url.startsWith('http') ? config.logo_url : '<?= base_url() ?>' + config.logo_url;
                appLogoContainer.classList.remove('hidden');
            }

            // SINKRONISASI CO-BRANDING DINAMIS (Title & Favicon)
            const namaInstansi = config.nama_aplikasi || "VoteLock";
            document.title = "VoteLock - " + namaInstansi + " (Pendataan DPT)";

            if (config.logo_url && config.logo_url.trim() !== "") {
                const iconUrl = config.logo_url.startsWith('http') ? config.logo_url : '<?= base_url() ?>' + config.logo_url;
                const faviconElement = document.getElementById('dynamic-favicon');
                if (faviconElement) {
                    faviconElement.href = iconUrl;
                }
            }

            // Setup Select Dropdown jika masih kosong
            if (asalLembagaSelect.options.length <= 1 && config.daftar_lembaga) {
                let listLembaga = [];
                config.daftar_lembaga.forEach(item => {
                    if (item.includes(',')) listLembaga.push(...item.split(',').map(s => s.trim()));
                    else listLembaga.push(item);
                });
                listLembaga.forEach(lembaga => {
                    const option = document.createElement("option");
                    option.value = lembaga;
                    option.text = lembaga;
                    asalLembagaSelect.appendChild(option);
                });
            }

            // SETUP SELECT DROPDOWN MASA KERJA JIKA MASIH KOSONG
            const regMasaKerjaSelect = document.getElementById('regMasaKerja');
            if (regMasaKerjaSelect && regMasaKerjaSelect.options.length <= 1 && config.daftar_masa_kerja) {
                let listMasaKerja = [];
                config.daftar_masa_kerja.forEach(item => {
                    if (item.includes(',')) listMasaKerja.push(...item.split(',').map(s => s.trim()));
                    else listMasaKerja.push(item);
                });
                listMasaKerja.forEach(masa => {
                    const option = document.createElement("option");
                    option.value = masa;
                    option.text = masa;
                    regMasaKerjaSelect.appendChild(option);
                });
            }

            // SINKRONISASI SUBJUDUL DINAMIS
            const deskripsiFase1El = document.getElementById('deskripsiFase1');
            if (deskripsiFase1El) {
                deskripsiFase1El.innerText = config.deskripsi_fase1 || "Fase 1: Pendataan Awal Hak Pilih";
            }

            // Setup Global Fase & Gatekeeper UI
            faseAktifGlobal = config.fase_aktif !== undefined ? config.fase_aktif : 1;
            renderNavHub(faseAktifGlobal);

            // Buka gembok tombol jika Fase 1 aktif
            if (faseAktifGlobal === 1) {
                btnGoogleLogin.disabled = false;
                btnGoogleLogin.className = "w-full flex justify-center items-center gap-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-4 rounded-lg transition duration-200 shadow-sm";
                btnGoogleLogin.innerHTML = '<svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" /><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" /><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" /><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" /></svg> Daftar dengan Akun Google';
                statusAlert.className = "p-3 bg-yellow-50 text-yellow-800 text-xs rounded-lg border border-yellow-200 font-medium";
                statusAlert.innerHTML = "Sistem pendataan resmi dibuka. Siapkan identitas.";
            } else {
                btnGoogleLogin.disabled = true;
                btnGoogleLogin.className = "w-full flex justify-center items-center gap-3 bg-gray-100 border border-gray-200 text-gray-400 font-semibold py-3 px-4 rounded-lg transition duration-200 cursor-not-allowed";
                btnGoogleLogin.innerHTML = '<span class="text-lg">🔒</span> Akses Pendataan Ditutup';
                statusAlert.className = "p-3 bg-red-50 text-red-600 text-xs font-bold rounded-lg border border-red-200";
                statusAlert.innerHTML = "Fase 1 telah berakhir. Silakan cek status di Fase Berjalan.";
            }
        }
    });

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

    // LOGIN GOOGLE DENGAN GATEKEEPER BERLAPIS
    btnGoogleLogin.addEventListener('click', async () => {
        // Tembok pertahanan terakhir (in case tombol di-hack lewat inspect element)
        if (faseAktifGlobal !== 1) {
            Swal.fire({
                icon: 'error',
                title: 'Terkunci',
                text: 'Pendataan telah ditutup oleh Panitia.',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
            return;
        }

        try {
            const result = await signInWithPopup(auth, provider);
            userEmail = result.user.email;

            Swal.fire({
                title: 'Memeriksa Data...',
                width: '280px',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const docSnap = await getDoc(doc(db, "pemilih", userEmail));
            if (docSnap.exists()) {
                const dataPemilih = docSnap.data();
                Swal.fire({
                    icon: 'info',
                    title: 'Sudah Terdaftar',
                    html: `Anda sudah masuk dalam sistem pendataan.<br><br><div class="bg-gray-100 p-3 rounded-lg text-left text-sm mt-2 border border-gray-200"><b>Nama:</b> ${dataPemilih.nama_lengkap}<br><b>Lembaga:</b> ${dataPemilih.asal_lembaga}</div>`,
                    width: '320px',
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
                auth.signOut();
                return;
            }

            Swal.close();
            document.getElementById('loginSection').classList.add('hidden');
            document.getElementById('formSection').classList.remove('hidden');
            document.getElementById('displayEmail').innerText = userEmail;

        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Otentikasi Google dibatalkan.',
                width: '280px',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        }
    });

    // PROSES SUBMIT FORMULIR REGISTRASI
    document.getElementById('formPendataan').addEventListener('submit', async (e) => {
        e.preventDefault();

        // Tangkap seluruh data dari input form (Tambahkan pengaman toUpperCase)
        const inputNama = document.getElementById('regNamaLengkap').value.trim().toUpperCase();
        const inputTempatLahir = document.getElementById('regTempatLahir').value.trim().toUpperCase();
        const inputTanggalLahir = document.getElementById('regTanggalLahir').value;
        const inputAlamat = document.getElementById('regAlamat').value.trim().toUpperCase();
        const inputWA = document.getElementById('regWA').value.trim();
        const inputLembaga = document.getElementById('asalLembaga').value;
        const inputMasaKerja = document.getElementById('regMasaKerja').value;

        // Validasi Nomor WA
        if (!/^[0-9]+$/.test(inputWA)) {
            Swal.fire({
                icon: 'error',
                title: 'Format Salah',
                text: 'Nomor WhatsApp hanya boleh berisi angka.',
                width: '280px',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
            return;
        }

        // Tampilkan Loading
        Swal.fire({
            title: 'Menyimpan Data...',
            width: '280px',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            // Susun Payload dan Simpan ke Firestore
            await setDoc(doc(db, "pemilih", userEmail), {
                email: userEmail,
                nama_lengkap: inputNama,
                tempat_lahir: inputTempatLahir,
                tanggal_lahir: inputTanggalLahir,
                alamat_tinggal: inputAlamat,
                nomor_whatsapp: inputWA,
                asal_lembaga: inputLembaga,
                masa_kerja: inputMasaKerja,
                status_verifikasi: 'pending',
                fase2_sudah_memilih: false,
                fase3_sudah_memilih: false,
                registered_at: serverTimestamp()
            });

            // Tampilkan Notifikasi Sukses
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data Anda berhasil disimpan.',
                width: '280px',
                confirmButtonColor: '#2563eb',
                customClass: {
                    popup: 'rounded-2xl'
                }
            }).then(() => {
                // Ubah Tampilan Menjadi Bukti Pendataan
                document.getElementById('formSection').innerHTML = `
                    <div class="text-center p-5 bg-blue-50 text-blue-900 rounded-xl border border-blue-200 shadow-sm animate-fade-in">
                        <h3 class="font-extrabold text-lg mb-4">BUKTI PENDATAAN</h3>
                        <div class="bg-white p-4 rounded-lg border border-blue-100 text-left mb-4 shadow-inner">
                            <p class="text-[10px] text-gray-500 mb-0.5 uppercase tracking-wider font-bold">Nama Pendaftar:</p>
                            <p class="font-bold text-sm mb-3">${inputNama}</p>
                            
                            <p class="text-[10px] text-gray-500 mb-0.5 uppercase tracking-wider font-bold">Asal Lembaga:</p>
                            <p class="font-bold text-sm mb-3">${inputLembaga}</p>
                            
                            <p class="text-[10px] text-gray-500 mb-0.5 uppercase tracking-wider font-bold">Masa Kerja:</p>
                            <p class="font-bold text-sm">${inputMasaKerja}</p>
                        </div>
                        <p class="text-xs text-gray-600 font-medium leading-relaxed">Silakan tangkap layar (screenshot) halaman ini sebagai bukti. Tunggu verifikasi panitia selanjutnya.</p>
                    </div>`;

                auth.signOut();
            });
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: 'Terjadi gangguan jaringan.',
                width: '280px',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>