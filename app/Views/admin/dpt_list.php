<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<style>
    /* CSS Khusus untuk Responsive Table (Mobile Card View) */
    @media (max-width: 768px) {
        .responsive-table thead {
            display: none;
        }

        .responsive-table,
        .responsive-table tbody,
        .responsive-table tr,
        .responsive-table td {
            display: block;
            width: 100%;
        }

        .responsive-table tr {
            margin-bottom: 1rem;
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
        }

        .responsive-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            text-align: right;
            font-size: 0.85rem;
        }

        .responsive-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6b7280;
            text-align: left;
            padding-right: 1rem;
        }

        .responsive-table td:last-child {
            border-bottom: none;
            justify-content: flex-end;
            gap: 0.5rem;
            background-color: #f9fafb;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .responsive-table td:last-child::before {
            display: none;
        }
    }
</style>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Verifikasi Daftar Pemilih Tetap (DPT)</h2>
        <p class="text-gray-500 text-sm">Validasi data pendaftar dan eliminasi data fiktif secara real-time.</p>
    </div>

    <div class="flex items-center gap-2">
        <div class="bg-white p-2 rounded-lg shadow-sm border border-gray-100 flex items-center gap-2">
            <label for="filterLembaga" class="text-sm font-bold text-gray-600 block whitespace-nowrap pl-2">Filter:</label>
            <select id="filterLembaga" class="px-3 py-1.5 border-none bg-gray-50 rounded text-sm text-gray-700 outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                <option value="ALL">Semua Lembaga</option>
            </select>
        </div>
        <button id="btnExportWA" class="bg-green-600 hover:bg-green-700 text-white p-2.5 rounded-lg shadow-sm transition flex items-center justify-center" title="Ekspor Data DPT (Excel)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </button>
    </div>
</div>

<div class="bg-transparent md:bg-white md:rounded-xl md:shadow-sm md:border md:border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse responsive-table">
        <thead class="bg-gray-50 border-b border-gray-100 hidden md:table-header-group">
            <tr class="text-xs font-bold uppercase tracking-wider text-gray-500">
                <th class="py-4 px-6">Nama Lengkap</th>
                <th class="py-4 px-6">Lembaga</th>
                <th class="py-4 px-6">WhatsApp</th>
                <th class="py-4 px-6">Email Perangkat</th>
                <th class="py-4 px-6 text-center">Status</th>
                <th class="py-4 px-6 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="tabelDPT" class="text-sm text-gray-700">
            <tr id="loadingRow">
                <td colspan="6" class="text-center py-12 text-gray-400 font-medium md:table-cell block">
                    <div class="flex items-center justify-center gap-3">
                        <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memverifikasi otentikasi & memuat data...
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="module">
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import {
        getFirestore,
        collection,
        onSnapshot,
        doc,
        getDoc,
        updateDoc,
        deleteDoc,
        query,
        orderBy
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
    // 1. IMPORT AUTH UNTUK MEMASTIKAN LOGIN DI SISI CLIENT
    import {
        getAuth,
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

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
    const auth = getAuth(app); // INISIALISASI AUTH

    const tabelDPT = document.getElementById('tabelDPT');
    const filterLembaga = document.getElementById('filterLembaga');
    let semuaDataPemilih = [];

    // 2. TUNGGU FIREBASE AUTH SIAP SEBELUM MENARIK DATA
    onAuthStateChanged(auth, (user) => {
        if (user) {
            // Jika Firebase mengenali user ini, baru kita tarik datanya
            inisialisasiHalaman();
        } else {
            // Jika token habis/hilang, kembalikan ke halaman login
            Swal.fire({
                icon: 'error',
                title: 'Sesi Firebase Terputus',
                text: 'Silakan login ulang untuk mengakses data.',
                width: '280px',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = '<?= base_url('logout') ?>';
            });
        }
    });

    async function muatDaftarLembaga() {
        try {
            const docSnap = await getDoc(doc(db, "sistem_kontrol", "konfigurasi_app"));
            if (docSnap.exists()) {
                const dataConfig = docSnap.data();
                if (dataConfig.daftar_lembaga && Array.isArray(dataConfig.daftar_lembaga)) {

                    let listLembaga = [];
                    // Pecah otomatis jika admin tidak sengaja menggabungkan pakai koma
                    dataConfig.daftar_lembaga.forEach(item => {
                        if (item.includes(',')) {
                            listLembaga.push(...item.split(',').map(s => s.trim()));
                        } else {
                            listLembaga.push(item);
                        }
                    });

                    // Render ke select option
                    listLembaga.forEach(lembaga => {
                        const option = document.createElement("option");
                        option.value = lembaga;
                        option.text = lembaga;
                        filterLembaga.appendChild(option);
                    });
                }
            }
        } catch (error) {
            console.error("Gagal memuat daftar lembaga:", error);
        }
    }

    async function inisialisasiHalaman() {
        await muatDaftarLembaga();
        const q = query(collection(db, "pemilih"), orderBy("registered_at", "desc"));

        onSnapshot(q, (snapshot) => {
            semuaDataPemilih = [];
            snapshot.forEach((doc) => {
                semuaDataPemilih.push({
                    id: doc.id,
                    ...doc.data()
                });
            });
            renderTabel(filterLembaga.value);
        }, (error) => {
            console.error("Error mengambil data Firestore: ", error);
            tabelDPT.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-red-500 font-bold">Gagal memuat data. Pastikan Security Rules sudah diperbarui.</td></tr>`;
        });
    }

    function renderTabel(filterValue) {
        tabelDPT.innerHTML = "";
        const dataTerfilter = semuaDataPemilih.filter(p => filterValue === "ALL" || p.asal_lembaga === filterValue);

        if (dataTerfilter.length === 0) {
            tabelDPT.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-gray-400 block md:table-cell w-full bg-white rounded-lg shadow-sm">Belum ada data pemilih.</td></tr>`;
            return;
        }

        dataTerfilter.forEach((pemilih) => {
            let badgeStyle = "bg-yellow-50 text-yellow-700 border-yellow-200";
            if (pemilih.status_verifikasi === "verified") badgeStyle = "bg-green-50 text-green-700 border-green-200";
            if (pemilih.status_verifikasi === "anomali") badgeStyle = "bg-red-50 text-red-700 border-red-200";

            // Tambahkan Atribut data-label untuk setiap <td> agar support Mobile Card View
            const tr = document.createElement('tr');
            tr.className = "hover:bg-gray-50/50 transition-colors duration-200";
            tr.innerHTML = `
                <td data-label="Nama Pemilih" class="md:py-4 md:px-6 font-bold text-gray-800">${pemilih.nama_lengkap}</td>
                <td data-label="Asal Lembaga" class="md:py-4 md:px-6 text-xs font-semibold text-gray-600">${pemilih.asal_lembaga}</td>
                <td data-label="WhatsApp" class="md:py-4 md:px-6 text-gray-600 font-mono text-xs">${pemilih.nomor_whatsapp}</td>
                <td data-label="Email Terdaftar" class="md:py-4 md:px-6 text-gray-500 text-xs">${pemilih.email}</td>
                <td data-label="Status DPT" class="md:py-4 md:px-6 md:text-center">
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border ${badgeStyle} tracking-wider">
                        ${pemilih.status_verifikasi.toUpperCase()}
                    </span>
                </td>
                <td class="md:py-4 md:px-6 text-center space-x-1 whitespace-nowrap md:justify-center flex-wrap">
                    ${pemilih.status_verifikasi !== 'verified' ? `
                        <button class="btn-verif bg-green-600 hover:bg-green-700 text-white font-semibold py-1.5 px-3 rounded text-xs transition shadow-sm" data-id="${pemilih.id}">Sahkan</button>
                    ` : ''}
                    ${pemilih.status_verifikasi !== 'anomali' ? `
                        <button class="btn-anomali bg-amber-500 hover:bg-amber-600 text-white font-semibold py-1.5 px-3 rounded text-xs transition shadow-sm" data-id="${pemilih.id}">Anomali</button>
                    ` : ''}
                    <button class="btn-hapus bg-red-600 hover:bg-red-700 text-white font-semibold py-1.5 px-3 rounded text-xs transition shadow-sm" data-id="${pemilih.id}">Hapus</button>
                </td>
            `;
            tabelDPT.appendChild(tr);
        });
        registerAksiTombol();
    }

    filterLembaga.addEventListener('change', (e) => renderTabel(e.target.value));

    function registerAksiTombol() {
        document.querySelectorAll('.btn-verif').forEach(btn => {
            btn.addEventListener('click', function() {
                const docId = this.getAttribute('data-id');
                Swal.fire({
                        title: 'Sahkan Pemilih?',
                        text: "Email ini akan dinyatakan sah masuk ke DPT resmi.",
                        icon: 'question',
                        width: '280px',
                        showCancelButton: true,
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: 'Ya, Sah',
                        cancelButtonText: 'Batal'
                    })
                    .then(async (result) => {
                        if (result.isConfirmed) {
                            await updateDoc(doc(db, "pemilih", docId), {
                                status_verifikasi: "verified"
                            });
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                width: '280px',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }
                    });
            });
        });

        document.querySelectorAll('.btn-anomali').forEach(btn => {
            btn.addEventListener('click', function() {
                const docId = this.getAttribute('data-id');
                Swal.fire({
                        title: 'Tandai Anomali?',
                        text: "Data dikategorikan mencurigakan.",
                        icon: 'warning',
                        width: '280px',
                        showCancelButton: true,
                        confirmButtonColor: '#f59e0b',
                        confirmButtonText: 'Ya, Tandai',
                        cancelButtonText: 'Batal'
                    })
                    .then(async (result) => {
                        if (result.isConfirmed) {
                            await updateDoc(doc(db, "pemilih", docId), {
                                status_verifikasi: "anomali"
                            });
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                width: '280px',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }
                    });
            });
        });

        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function() {
                const docId = this.getAttribute('data-id');
                Swal.fire({
                        title: 'Hapus Permanen?',
                        text: "Data akan dihapus dari sistem.",
                        icon: 'error',
                        width: '280px',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    })
                    .then(async (result) => {
                        if (result.isConfirmed) {
                            await deleteDoc(doc(db, "pemilih", docId));
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                width: '280px',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }
                    });
            });
        });
    }

    // FITUR EKSPOR UNTUK EXCEL REKAPITULASI DPT
    document.getElementById('btnExportWA').addEventListener('click', () => {
        // Ambil data yang saat ini sudah terverifikasi (verified)
        const dataTarget = semuaDataPemilih.filter(p => p.status_verifikasi === 'verified');

        if (dataTarget.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Kosong',
                text: 'Belum ada data DPT sah yang bisa diekspor.',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
            return;
        }

        // Susun nama file dengan tanggal hari ini
        const tanggal = new Date().toISOString().split('T')[0];
        const filename = `Rekap_DPT_VoteLock_${tanggal}.xls`;

        // Merancang struktur HTML khusus Excel agar Gridlines (Garis Kotak) otomatis aktif dan rapi
        let excelTemplate = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
                table { border-collapse: collapse; width: 100%; }
                th { background-color: #1e3a8a; color: #ffffff; font-weight: bold; padding: 10px; border: 1px solid #cbd5e1; text-align: center; }
                td { padding: 8px; border: 1px solid #cbd5e1; text-align: left; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <h2>REKAPITULASI DAFTAR PEMILIH TETAP (DPT) SAH</h2>
            <p>Diekstrak otomatis pada tanggal: ${tanggal}</p>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Email</th>
                        <th>Nama Lengkap</th>
                        <th>No. WhatsApp</th>
                        <th>Lembaga</th>
                    </tr>
                </thead>
                <tbody>
        `;

        // Iterasi menyuntikkan data baris demi baris
        dataTarget.forEach((p, index) => {
            excelTemplate += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${p.email}</td>
                    <td style="text-transform: uppercase; font-weight: 500;">${p.nama_lengkap}</td>
                    <td style="font-family: monospace;">'${p.nomor_whatsapp}</td> <td>${p.asal_lembaga}</td>
                </tr>
            `;
        });

        excelTemplate += `
                </tbody>
            </table>
        </body>
        </html>
        `;

        // Proses download Blob MS-Excel
        const blob = new Blob([excelTemplate], {
            type: 'application/vnd.ms-excel;charset=utf-8;'
        });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);

        link.setAttribute("href", url);
        link.setAttribute("download", filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        Swal.fire({
            icon: 'success',
            title: 'Ekspor Berhasil',
            text: 'File Excel berformat rapi siap digunakan.',
            timer: 2000,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-2xl'
            }
        });
    });
</script>
<?= $this->endSection() ?>