<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Dasbor Utama</h2>
        <p class="text-gray-500 text-sm">Ringkasan lalu lintas data e-voting secara real-time.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 relative overflow-hidden">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Pendaftar</p>
                <h3 class="text-3xl font-bold text-gray-800" id="statTotal"><i class="fas fa-spinner animate-spin text-lg"></i></h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Terverifikasi (DPT)</p>
                <h3 class="text-3xl font-bold text-gray-800" id="statVerified"><i class="fas fa-spinner animate-spin text-lg"></i></h3>
            </div>
            <div class="p-3 bg-green-50 rounded-full text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Anomali / Ditolak</p>
                <h3 class="text-3xl font-bold text-gray-800" id="statAnomaly"><i class="fas fa-spinner animate-spin text-lg"></i></h3>
            </div>
            <div class="p-3 bg-red-50 rounded-full text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 hover:bg-yellow-50 transition cursor-pointer group" id="btnUbahFase" title="Klik untuk mengubah Fase Sistem">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1 group-hover:text-yellow-700">Fase Aktif <span class="text-[10px] bg-yellow-200 text-yellow-800 px-1.5 rounded ml-1">UBAH</span></p>
                <h3 class="text-xl font-bold text-gray-800 mt-1" id="statFase">Memuat...</h3>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full text-yellow-600 group-hover:bg-yellow-500 group-hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
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
    import {
        collection,
        onSnapshot,
        doc,
        updateDoc
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
    import {
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    const statTotal = document.getElementById('statTotal');
    const statVerified = document.getElementById('statVerified');
    const statAnomaly = document.getElementById('statAnomaly');
    const statFase = document.getElementById('statFase');
    const btnUbahFase = document.getElementById('btnUbahFase');

    let currentFase = 1;

    onAuthStateChanged(auth, (user) => {
        if (user) {
            jalankanStatistik();
            jalankanMonitorFase();
        } else {
            window.location.href = '<?= base_url('logout') ?>';
        }
    });

    // 1. Monitor Statistik Pendaftar
    function jalankanStatistik() {
        onSnapshot(collection(db, "pemilih"), (snapshot) => {
            let total = 0;
            let verified = 0;
            let anomaly = 0;

            snapshot.forEach((doc) => {
                total++;
                const status = doc.data().status_verifikasi;
                if (status === 'verified') verified++;
                if (status === 'anomali') anomaly++;
            });

            statTotal.innerText = total;
            statVerified.innerText = verified;
            statAnomaly.innerText = anomaly;
        });
    }

    // 2. Monitor Fase Aktif (Perbaikan Logika Angka 0)
    function jalankanMonitorFase() {
        onSnapshot(doc(db, "sistem_kontrol", "konfigurasi_app"), (docSnap) => {
            if (docSnap.exists()) {
                const data = docSnap.data();
                // Jika fase_aktif bernilai 0, ambil 0. Jika belum di-set sama sekali, baru ambil 1.
                currentFase = data.fase_aktif !== undefined ? data.fase_aktif : 1;

                if (currentFase == 1) statFase.innerText = "1: Pendataan";
                else if (currentFase == 2) statFase.innerText = "2: Nominasi";
                else if (currentFase == 3) statFase.innerText = "3: Pemilihan";
                else statFase.innerHTML = "<span class='text-red-600'>0: Terkunci</span>";
            }
        });
    }

    // 3. Logika Mengubah Fase (UI SweetAlert Kelas Expert)
    btnUbahFase.addEventListener('click', async () => {
        // Kita buat form radio button kustom menggunakan Tailwind di dalam popup
        const htmlContent = `
            <div class="space-y-3 mt-2 text-left" id="faseOptions">
                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition ${currentFase == 1 ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200 hover:bg-gray-50'}">
                    <input type="radio" name="fase_radio" value="1" class="w-5 h-5 text-blue-600 focus:ring-blue-500" ${currentFase == 1 ? 'checked' : ''}>
                    <div class="ml-4"><span class="block font-extrabold text-gray-800">Fase 1: Pendataan DPT</span><span class="text-[10px] text-gray-500 font-semibold uppercase">Pendaftaran Hak Pilih</span></div>
                </label>
                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition ${currentFase == 2 ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200 hover:bg-gray-50'}">
                    <input type="radio" name="fase_radio" value="2" class="w-5 h-5 text-blue-600 focus:ring-blue-500" ${currentFase == 2 ? 'checked' : ''}>
                    <div class="ml-4"><span class="block font-extrabold text-gray-800">Fase 2: Nominasi</span><span class="text-[10px] text-gray-500 font-semibold uppercase">Usulan Nama Kandidat</span></div>
                </label>
                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition ${currentFase == 3 ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200 hover:bg-gray-50'}">
                    <input type="radio" name="fase_radio" value="3" class="w-5 h-5 text-blue-600 focus:ring-blue-500" ${currentFase == 3 ? 'checked' : ''}>
                    <div class="ml-4"><span class="block font-extrabold text-gray-800">Fase 3: Pemilihan</span><span class="text-[10px] text-gray-500 font-semibold uppercase">Bilik Suara Pencoblosan</span></div>
                </label>
                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition ${currentFase == 0 ? 'border-red-500 bg-red-50 ring-1 ring-red-500' : 'border-gray-200 hover:bg-red-50'}">
                    <input type="radio" name="fase_radio" value="0" class="w-5 h-5 text-red-600 focus:ring-red-500" ${currentFase == 0 ? 'checked' : ''}>
                    <div class="ml-4"><span class="block font-black text-red-600">0: TUTUP SEMUA AKSES</span><span class="text-[10px] text-red-400 font-bold uppercase">Sistem Dibekukan</span></div>
                </label>
            </div>
        `;

        const {
            value: selectedFase
        } = await Swal.fire({
            title: 'Kendali Fase Operasional',
            html: htmlContent,
            showCancelButton: true,
            confirmButtonText: 'Terapkan Kunci',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border-t-4 border-yellow-500',
                title: 'text-xl font-bold text-gray-800 pt-2',
                confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-md w-full sm:w-auto',
                cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-8 rounded-xl transition w-full sm:w-auto mt-3 sm:mt-0'
            },
            buttonsStyling: false,
            // Eksekusi untuk mengambil nilai dari radio kustom kita
            preConfirm: () => {
                const checked = document.querySelector('input[name="fase_radio"]:checked');
                return checked ? checked.value : null;
            },
            // Sedikit interaktivitas JS agar card radio berubah warna saat diklik
            didOpen: () => {
                const labels = document.querySelectorAll('#faseOptions label');
                labels.forEach(label => {
                    label.addEventListener('click', () => {
                        labels.forEach(l => {
                            l.className = "flex items-center p-4 border rounded-xl cursor-pointer transition border-gray-200 hover:bg-gray-50";
                        });
                        label.className = "flex items-center p-4 border rounded-xl cursor-pointer transition border-blue-500 bg-blue-50 ring-1 ring-blue-500";
                        if (label.querySelector('input').value === '0') {
                            label.className = "flex items-center p-4 border rounded-xl cursor-pointer transition border-red-500 bg-red-50 ring-1 ring-red-500";
                        }
                    });
                });
            }
        });

        if (selectedFase) {
            Swal.fire({
                title: 'Menyegel Sistem...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            try {
                await updateDoc(doc(db, "sistem_kontrol", "konfigurasi_app"), {
                    fase_aktif: parseInt(selectedFase)
                });
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Sistem terkunci ke status: ' + selectedFase,
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
            }
        }
    });
</script>
<?= $this->endSection() ?>