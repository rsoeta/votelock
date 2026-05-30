<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 border-t-4 border-blue-600">

    <div class="text-center mb-6">
        <div id="appLogoContainer" class="mb-3 hidden">
            <img id="appLogo" src="" alt="Logo" class="h-16 mx-auto object-contain">
        </div>
        <h1 id="appTitle" class="text-xl font-bold text-gray-800 tracking-tight">Memuat Nama Sistem...</h1>
        <p class="text-xs text-blue-600 font-semibold mt-1">Fase 1: Pendataan Awal Hak Pilih</p>
    </div>

    <div id="loginSection" class="text-center space-y-4">
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

    <div id="formSection" class="hidden">
        <div class="mb-4 p-2.5 bg-green-50 text-green-800 text-xs rounded-lg text-center border border-green-200">
            Identitas perangkat tertaut:<br><strong id="displayEmail"></strong>
        </div>

        <form id="formRegistrasi" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" id="namaLengkap" required oninput="this.value = this.value.toUpperCase()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm font-bold uppercase">
            </div>

            <div>
                <label for="asalLembaga" class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Asal Lembaga</label>
                <select id="asalLembaga" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 bg-white text-sm text-gray-700 font-medium">
                    <option value="" disabled selected>-- Pilih Jenjang / Lembaga --</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nomor WhatsApp</label>
                <input type="tel" id="nomorWa" placeholder="08xxxxxxxxxx" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition duration-200 text-sm shadow-sm">
                Kirim Data Pendataan
            </button>
        </form>
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

    // FUNGSI TARIK CONFIG SISTEM SECARA DINAMIS (Poin 2 & 3)
    async function muatKonfigurasiSistem() {
        try {
            const docSnap = await getDoc(doc(db, "sistem_kontrol", "konfigurasi_app"));
            if (docSnap.exists()) {
                const config = docSnap.data();

                // 1. Set Judul Aplikasi Dinamis
                if (config.nama_aplikasi) {
                    appTitle.innerText = config.nama_aplikasi;
                } else {
                    appTitle.innerText = "VoteLock";
                }

                // 2. Set Logo Dinamis
                if (config.logo_url && config.logo_url.trim() !== "") {
                    // Cek apakah admin menginput URL Firebase Storage (http) atau nama file lokal CI4
                    if (config.logo_url.startsWith('http')) {
                        appLogo.src = config.logo_url;
                    } else {
                        // Gabungkan base_url CI4 dengan nama file dari database
                        appLogo.src = '<?= base_url() ?>' + config.logo_url;
                    }
                    appLogoContainer.classList.remove('hidden');
                }

                // 3. Set Dropdown Pilihan Lembaga Dinamis
                if (config.daftar_lembaga && Array.isArray(config.daftar_lembaga)) {
                    let listLembaga = [];
                    config.daftar_lembaga.forEach(item => {
                        if (item.includes(',')) {
                            listLembaga.push(...item.split(',').map(s => s.trim()));
                        } else {
                            listLembaga.push(item);
                        }
                    });

                    listLembaga.forEach(lembaga => {
                        const option = document.createElement("option");
                        option.value = lembaga;
                        option.text = lembaga;
                        asalLembagaSelect.appendChild(option);
                    });
                }
            }
        } catch (error) {
            console.error("Gagal sinkronisasi konfigurasi:", error);
            appTitle.innerText = "VoteLock";
        }
    }

    // Jalankan penarikan data konfigurasi saat halaman terbuka (bisa diakses publik tanpa login)
    muatKonfigurasiSistem();

    // Logika Otentikasi Google & Pendaftaran (Tetap sama aman seperti skrip sebelumnya)
    let userEmail = "";
    const provider = new GoogleAuthProvider();

    document.getElementById('btnGoogleLogin').addEventListener('click', async () => {
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

            // --- INI DUA BARIS YANG HILANG MBAH! ---
            const docRef = doc(db, "pemilih", userEmail);
            const docSnap = await getDoc(docRef);
            // ---------------------------------------

            if (docSnap.exists()) {
                const dataPemilih = docSnap.data();
                Swal.fire({
                    icon: 'info',
                    title: 'Sudah Terdaftar',
                    html: `Anda sudah masuk dalam sistem pendataan.<br><br><div class="bg-gray-100 p-3 rounded-lg text-left text-sm mt-2"><b>Email:</b> ${userEmail}<br><b>Nama:</b> ${dataPemilih.nama_lengkap}<br><b>Lembaga:</b> ${dataPemilih.asal_lembaga}</div>`,
                    width: '300px'
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
                text: 'Otentikasi Google dibatalkan atau terjadi masalah koneksi.',
                width: '280px'
            });
        }
    });

    // Form Submit Handler
    document.getElementById('formRegistrasi').addEventListener('submit', async (e) => {
        e.preventDefault();
        Swal.fire({
            title: 'Menyimpan Data...',
            width: '280px',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            await setDoc(doc(db, "pemilih", userEmail), {
                email: userEmail,
                nama_lengkap: document.getElementById('namaLengkap').value.trim().toUpperCase(),
                asal_lembaga: asalLembagaSelect.value,
                nomor_whatsapp: document.getElementById('nomorWa').value.trim(),
                status_verifikasi: 'pending',
                fase2_sudah_memilih: false,
                fase3_sudah_memilih: false,
                registered_at: serverTimestamp()
            });

            Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data Anda berhasil disimpan.',
                    width: '280px',
                    confirmButtonColor: '#2563eb'
                })
                .then(() => {
                    const nama = document.getElementById('namaLengkap').value.trim().toUpperCase();
                    const lembaga = asalLembagaSelect.value;
                    document.getElementById('formSection').innerHTML = `
                    <div class="text-center p-5 bg-blue-50 text-blue-900 rounded-xl border border-blue-200 shadow-sm">
                        <h3 class="font-extrabold text-lg mb-4">BUKTI PENDATAAN</h3>
                        <div class="bg-white p-3 rounded border border-blue-100 text-left mb-4 shadow-inner">
                            <p class="text-xs text-gray-500 mb-1">Nama Pendaftar:</p>
                            <p class="font-bold text-sm mb-3">${nama}</p>
                            <p class="text-xs text-gray-500 mb-1">Asal Lembaga:</p>
                            <p class="font-bold text-sm">${lembaga}</p>
                        </div>
                        <p class="text-xs text-gray-600">Silakan tangkap layar (screenshot) halaman ini sebagai bukti. Tunggu verifikasi panitia selanjutnya.</p>
                    </div>`;
                    auth.signOut();
                });
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: 'Terjadi gangguan jaringan.',
                width: '280px'
            });
        }
    });
</script>
<?= $this->endSection() ?>