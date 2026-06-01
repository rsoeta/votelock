<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Pengaturan Sistem Aplikasi</h2>
    <p class="text-gray-500 text-sm">Sesuaikan identitas nama aplikasi, logo instansi, dan daftar jenjang lembaga penampung hak pilih.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 max-w-2xl overflow-hidden">
    <div class="p-5 border-b border-gray-50 bg-gray-50/50">
        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
            🛠️ Konfigurasi Inti VoteLock
        </h3>
    </div>

    <form id="formPengaturan" class="p-6 space-y-5">

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5 tracking-wide">Nama Aplikasi / Instansi Yayasan</label>
            <input type="text" id="configNamaApp" required placeholder="Contoh: Pesantren Persatuan Islam 94 Pakenjeng" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium shadow-sm">
        </div>

        <div class="mt-4">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5 tracking-wide">Deskripsi WhatsApp (Link Preview)</label>
            <textarea id="configDeskripsiWA" rows="3" placeholder="Contoh: Mari berpartisipasi! Gunakan hak suara Anda..." class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium shadow-sm"></textarea>
            <span class="text-[10px] text-gray-400 mt-1 block font-medium">⚠️ Teks ini akan muncul sebagai deskripsi saat link aplikasi dibagikan ke WhatsApp atau media sosial.</span>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-3 tracking-wide">Logo Aplikasi</label>

            <div class="flex justify-start mb-4">
                <div class="p-2 bg-gray-50 border border-gray-200 rounded-xl shadow-inner">
                    <img id="previewLogoApp" src="" alt="Pratinjau Logo" class="h-20 w-auto object-contain hidden bg-white p-1 rounded-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-xl p-3 bg-gray-50/50">
                    <span class="text-[10px] font-bold text-blue-600 block mb-1.5 uppercase tracking-wide">Opsi A: Unggah dari Perangkat</span>
                    <input type="file" id="configLogoFile" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>

                <div class="border border-gray-200 rounded-xl p-3 bg-gray-50/50">
                    <span class="text-[10px] font-bold text-amber-600 block mb-1.5 uppercase tracking-wide">Opsi B: Tautan / Link URL Luar</span>
                    <input type="text" id="configLogoUrl" placeholder="https://link-gambar.com/logo.png" autocomplete="off" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                </div>
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5 tracking-wide">Teks Subjudul (Fase 1)</label>
            <input type="text" id="configDeskripsiFase1" placeholder="Contoh: Fase 1: Pendataan Awal Hak Pilih" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium shadow-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5 tracking-wide">Daftar Pilihan Jenjang / Lembaga</label>
            <textarea id="configDaftarLembaga" rows="5" required placeholder="Contoh: RA PERSIS PAKENJENG, MI PERSIS 94 PAKENJENG, SMA PERSIS PAKENJENG" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs leading-relaxed shadow-sm"></textarea>
            <span class="text-[10px] text-gray-400 mt-1 block font-medium">⚠️ Gunakan tanda koma <b>( , )</b> sebagai pemisah antar nama jenjang lembaga.</span>
        </div>

        <div class="mt-4">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5 tracking-wide">Daftar Pilihan Masa Kerja / Pengabdian</label>
            <textarea id="configMasaKerja" rows="3" required placeholder="Contoh: < 5 Tahun, 5 - 10 Tahun, > 10 Tahun" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 font-mono text-xs leading-relaxed shadow-sm"></textarea>
            <span class="text-[10px] text-gray-400 mt-1 block font-medium">⚠️ Pisahkan dengan koma. Kosongkan jika tidak ingin digunakan.</span>
        </div>

        <div class="pt-2 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-xl transition duration-200 text-xs shadow-md">
                💾 Simpan Konfigurasi Aplikasi
            </button>
        </div>

    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 max-w-2xl overflow-hidden mt-8 border-t-4 border-red-500">
    <div class="p-5 border-b border-gray-50 bg-red-50/30">
        <h3 class="text-sm font-bold text-red-800 uppercase tracking-wider flex items-center gap-2">
            🛡️ Pemeliharaan & Zona Bahaya Sistem
        </h3>
    </div>

    <div class="p-6 space-y-4">
        <p class="text-xs text-gray-500 font-medium">Lakukan pencadangan berkas secara berkala sebelum melakukan sterilisasi data atau penutupan sesi pemilihan resmi.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <button type="button" id="btnBackupData" class="flex items-center justify-center gap-3 bg-blue-50 border border-blue-200 hover:bg-blue-100 text-blue-700 font-bold py-3 px-4 rounded-xl transition duration-200 text-xs shadow-sm">
                📥 1. Amankan Backup Data (.JSON)
            </button>

            <button type="button" id="btnResetSistem" class="flex items-center justify-center gap-3 bg-red-50 border border-red-200 hover:bg-red-100 text-red-600 font-bold py-3 px-4 rounded-xl transition duration-200 text-xs shadow-sm">
                🚨 2. Kosongkan & Reset Data
            </button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="module">
    import {
        db,
        auth
    } from '<?= base_url('assets/js/firebase-init.js') ?>';

    // 1. IMPOR KHUSUS DATABASE (FIRESTORE)
    import {
        collection,
        doc,
        getDoc,
        getDocs,
        updateDoc,
        deleteDoc,
        writeBatch
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

    // 2. IMPOR KHUSUS OTENTIKASI (AUTH)
    import {
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    const configNamaApp = document.getElementById('configNamaApp');
    const configLogoUrl = document.getElementById('configLogoUrl');
    const configLogoFile = document.getElementById('configLogoFile');
    const configDaftarLembaga = document.getElementById('configDaftarLembaga');
    const previewLogoApp = document.getElementById('previewLogoApp');
    const formPengaturan = document.getElementById('formPengaturan');
    const btnBackupData = document.getElementById('btnBackupData');
    const btnResetSistem = document.getElementById('btnResetSistem');
    const configMasaKerja = document.getElementById('configMasaKerja');
    const configDeskripsiFase1 = document.getElementById('configDeskripsiFase1');
    const configDeskripsiWA = document.getElementById('configDeskripsiWA');

    // Cek Keamanan Sesi
    onAuthStateChanged(auth, (user) => {
        if (user) {
            muatDataAwalKonfigurasi();
        } else {
            window.location.href = '<?= base_url('logout') ?>';
        }
    });

    // 1. Ambil Data Konfigurasi Saat Ini Dari Firestore
    async function muatDataAwalKonfigurasi() {
        try {
            const docSnap = await getDoc(doc(db, "sistem_kontrol", "konfigurasi_app"));
            if (docSnap.exists()) {
                const config = docSnap.data();

                // Set Nama
                configNamaApp.value = config.nama_aplikasi || "";

                // Set URL Logo
                configLogoUrl.value = config.logo_url || "";

                // Set Deskripsi Fase 1 (Jika Tidak Ada, Tetap Biarkan Kosong untuk Default di Frontend)
                configDeskripsiFase1.value = config.deskripsi_fase1 || "Fase 1: Pendataan Awal Hak Pilih";

                // Set Deskripsi WhatsApp (Jika Tidak Ada, Tetap Biarkan Kosong untuk Default di Meta Tags PHP)
                configDeskripsiWA.value = config.deskripsi_wa || "Mari berpartisipasi! Gunakan hak suara Anda dengan aman, rahasia, dan transparan melalui bilik suara digital.";

                // Set Preview Gambar
                if (config.logo_url && config.logo_url.trim() !== "") {
                    previewLogoApp.src = config.logo_url.startsWith('http') ? config.logo_url : '<?= base_url() ?>' + config.logo_url;
                    previewLogoApp.classList.remove('hidden');
                } else {
                    previewLogoApp.src = "https://ui-avatars.com/api/?name=Vote+Lock&background=cbd5e1&color=64748b";
                    previewLogoApp.classList.remove('hidden');
                }

                // Set Textarea Lembaga (Gabungkan Array Menjadi String Dipisah Koma)
                if (config.daftar_lembaga && Array.isArray(config.daftar_lembaga)) {
                    config.daftar_lembaga.forEach(item => {
                        if (item.includes(',')) {
                            // Jika ada data lama berupa string gabungan, pecah dulu
                            const cleanArr = item.split(',').map(s => s.trim());
                            configDaftarLembaga.value = cleanArr.join(',\n');
                        } else {
                            configDaftarLembaga.value = config.daftar_lembaga.join(',\n');
                        }
                    });
                }
                // Set Textarea Masa Kerja
                if (config.daftar_masa_kerja && Array.isArray(config.daftar_masa_kerja)) {
                    configMasaKerja.value = config.daftar_masa_kerja.join(',\n');
                }
            }
        } catch (error) {
            console.error("Gagal memuat konfigurasi:", error);
        }
    }

    // 2. LOGIKA PRATINJAU GAMBAR KETIKA FILE DIPILIH
    configLogoFile.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewLogoApp.src = event.target.result;
                previewLogoApp.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
            configLogoUrl.value = ""; // Bersihkan opsi input URL
        }
    });

    // LOGIKA PRATINJAU GAMBAR KETIKA LINK URL DIKETIK
    configLogoUrl.addEventListener('input', function(e) {
        const val = e.target.value.trim();
        if (val !== "") {
            previewLogoApp.src = val.startsWith('http') ? val : '<?= base_url() ?>' + val;
            previewLogoApp.classList.remove('hidden');
            configLogoFile.value = ""; // Bersihkan opsi input file berkas
        }
    });

    // 3. PROSES SIMPAN UPDATE DATA KE DATABASE
    formPengaturan.addEventListener('submit', async (e) => {
        e.preventDefault();
        let finalLogoUrl = configLogoUrl.value.trim();

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Seluruh identitas sistem di halaman pemilih akan langsung diperbarui.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl shadow-xl border-t-4 border-blue-500',
                title: 'text-lg font-bold text-gray-800',
                confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg text-xs transition shadow-sm mr-2',
                cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-lg text-xs transition'
            },
            buttonsStyling: false
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    // JIKA PANITIA MEMILIH UPLOAD FILE BARU
                    if (configLogoFile.files.length > 0) {
                        const formData = new FormData();
                        formData.append('logo_app', configLogoFile.files[0]);

                        const uploadResponse = await fetch('<?= base_url('panel/pengaturan/upload') ?>', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const uploadResult = await uploadResponse.json();

                        if (uploadResult.status === 'success') {
                            finalLogoUrl = uploadResult.file_path;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Upload',
                                text: uploadResult.message,
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });
                            return;
                        }
                    }

                    // KONVERSI TEXTAREA MENJADI ARRAY KEMBALI
                    const arrayLembaga = configDaftarLembaga.value
                        .split(',')
                        .map(str => str.trim().toUpperCase())
                        .filter(str => str !== ""); // Membuang baris kosong akibat typo koma berlebih

                    // KONVERSI TEXTAREA MASA KERJA MENJADI ARRAY KEMBALI
                    const arrayMasaKerja = configMasaKerja.value
                        .split(',')
                        .map(str => str.trim()) // Tidak perlu toUpperCase
                        .filter(str => str !== "");

                    // UPDATE FIRESTORE (Kirim semua data dalam SATU KALI tembakan)
                    await updateDoc(doc(db, "sistem_kontrol", "konfigurasi_app"), {
                        nama_aplikasi: configNamaApp.value.trim(),
                        deskripsi_fase1: configDeskripsiFase1.value.trim(),
                        deskripsi_wa: configDeskripsiWA.value.trim(),
                        logo_url: finalLogoUrl,
                        daftar_lembaga: arrayLembaga,
                        daftar_masa_kerja: arrayMasaKerja
                    });

                    // --- TAMBAHKAN BLOK DUAL-SYNC CI4 INI ---
                    // Mengirim salinan Nama dan Logo ke server CI4 untuk dicetak di Meta Tags PHP
                    await fetch('<?= base_url('panel/pengaturan/sync') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            nama_aplikasi: configNamaApp.value.trim(),
                            logo_url: finalLogoUrl,
                            deskripsi_wa: configDeskripsiWA.value.trim()
                        })
                    });
                    // ----------------------------------------

                    Swal.fire({
                        icon: 'success',
                        title: 'Konfigurasi Diperbarui',
                        text: 'Aplikasi VoteLock berhasil disinkronisasi.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    }).then(() => {
                        window.location.reload();
                    });

                } catch (error) {
                    console.error("Gagal simpan:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Jaringan',
                        text: 'Periksa kembali koneksi database.',
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });
                }
            }
        });
    });

    // ========================================================
    // LOGIKA PERINTAH 1: BACKUP DATA UTUH KE BERKAS JSON
    // ========================================================
    btnBackupData.addEventListener('click', async () => {
        Swal.fire({
            title: 'Menyusun Cadangan...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            // 1. Tarik Data Pemilih (DPT + Hasil Suara Fase 2 & Fase 3)
            const snapPemilih = await getDocs(collection(db, "pemilih"));
            const dataPemilih = [];
            snapPemilih.forEach(doc => {
                dataPemilih.push({
                    id: doc.id,
                    ...doc.data()
                });
            });

            // 2. Tarik Data Profil Kandidat Final
            const snapKandidat = await getDocs(collection(db, "kandidat_final"));
            const dataKandidat = [];
            snapKandidat.forEach(doc => {
                dataKandidat.push({
                    id: doc.id,
                    ...doc.data()
                });
            });

            // Bundling seluruh snapshot ke dalam objek struktur tunggal
            const backupMaster = {
                votelock_version: "2.0-Enterprise",
                backup_at: new Date().toISOString(),
                pemilih: dataPemilih,
                kandidat_final: dataKandidat
            };

            // Proses pembuatan trigger download JSON file browser
            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(backupMaster, null, 2));
            const dlAnchorElem = document.createElement('a');
            const tgl = new Date().toISOString().split('T')[0];

            dlAnchorElem.setAttribute("href", dataStr);
            dlAnchorElem.setAttribute("download", `Backup_VoteLock_Master_${tgl}.json`);
            dlAnchorElem.style.visibility = 'hidden';
            document.body.appendChild(dlAnchorElem);
            dlAnchorElem.click();
            document.body.removeChild(dlAnchorElem);

            Swal.fire({
                icon: 'success',
                title: 'Backup Aman!',
                text: 'Salinan database berhasil diunduh.',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });

        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Cadangkan',
                text: 'Periksa koneksi jaringan database.',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        }
    });

    // ========================================================
    // LOGIKA PERINTAH 2: RESET DATA (ZONA BAHAYA) BERLAPIS EXPERT
    // ========================================================
    btnResetSistem.addEventListener('click', async () => {
        // Tembok Konfirmasi Level 1
        const {
            isConfirmed: konfirmasi1
        } = await Swal.fire({
            title: 'Sapu Bersih & Reset Data?',
            html: '<span class="text-xs text-red-600 font-bold block bg-red-50 p-3 rounded-lg border border-red-100">TINDAKAN BERBAHAYA! Seluruh data DPT, status rekam kehadiran, usulan nominasi, dan hasil coblosan final pemilih akan DIHAPUS PERMANEN dari server.</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan, Saya Mengerti',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border-t-4 border-red-600',
                title: 'text-lg font-black text-gray-800',
                confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs shadow-sm mr-2',
                cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl text-xs shadow-sm'
            },
            buttonsStyling: false
        });

        if (!konfirmasi1) return;

        // Tembok Konfirmasi Level 2: Proteksi Validasi Ketikan Kata Kunci demi keamanan mutlak
        const {
            value: keyword
        } = await Swal.fire({
            title: 'Verifikasi Otoritas Pembersihan',
            html: '<label class="block text-xs font-semibold text-gray-500 text-center mb-2">Ketik kalimat <b class="text-red-600 tracking-wide">RESET NOMER SEMBILAN EMPAT</b> di bawah untuk membuka segel:</label>',
            input: 'text',
            inputPlaceholder: 'Ketik kalimat verifikasi di sini...',
            showCancelButton: true,
            confirmButtonText: 'Eksekusi Reset Sekarang',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl shadow-2xl',
                title: 'text-base font-bold text-gray-800',
                input: 'w-full px-3 py-2 border rounded-lg text-sm font-black text-center text-red-600 uppercase bg-gray-50',
                confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs shadow-sm mr-2 mt-4',
                cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl text-xs shadow-sm mt-4'
            },
            buttonsStyling: false,
            preConfirm: (input) => {
                if (input.trim().toUpperCase() !== 'RESET NOMER SEMBILAN EMPAT') {
                    Swal.showValidationMessage('Kalimat verifikasi salah / tidak cocok!');
                }
                return input;
            }
        });

        if (!keyword) return;

        // LOLOS SEMUA VERIFIKASI: MULAI PROSES PENGHAPUSAN BERANTAI
        Swal.fire({
            title: 'Mengosongkan Server Firestore...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const batch = writeBatch(db);

            // 1. Ambil & Antrikan Penghapusan Koleksi Pemilih
            const snapPemilih = await getDocs(collection(db, "pemilih"));
            snapPemilih.forEach((docSnap) => {
                batch.delete(doc(db, "pemilih", docSnap.id));
            });

            // 2. Ambil & Antrikan Penghapusan Koleksi Kandidat Final
            const snapKandidat = await getDocs(collection(db, "kandidat_final"));
            snapKandidat.forEach((docSnap) => {
                batch.delete(doc(db, "kandidat_final", docSnap.id));
            });

            // Komit penghapusan massal dalam satu transaksi tunggal agar bersih dan cepat
            await batch.commit();

            Swal.fire({
                icon: 'success',
                title: 'Sistem Kembali Nol!',
                text: 'Koleksi pemilih dan kandidat_final berhasil dikosongkan.',
                customClass: {
                    popup: 'rounded-2xl'
                }
            }).then(() => {
                window.location.reload();
            });

        } catch (error) {
            console.error("Gagal melakukan reset total:", error);
            Swal.fire({
                icon: 'error',
                title: 'Operasi Gagal',
                text: 'Terjadi kegagalan komunikasi dengan Firebase Storage.',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>