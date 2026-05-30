<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<style>
    /* Responsive Table to Card untuk Mobile */
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
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .responsive-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            text-align: right;
        }

        .responsive-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #4b5563;
            text-align: left;
        }

        .responsive-table td:last-child {
            border-bottom: none;
            background-color: #f9fafb;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }
    }
</style>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Pivot Rekapitulasi Nominasi (Fase 2)</h2>
        <p class="text-gray-500 text-sm">Nama usulan dikelompokkan dan dihitung secara otomatis berdasarkan suara masuk.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button id="btnKunciTop3" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition flex items-center gap-2 text-xs">
            🔒 Kunci & Rilis 3 Besar
        </button>

        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-center shadow-sm">
            <span class="text-[10px] uppercase tracking-wider font-bold text-blue-600 block">Total Suara</span>
            <span id="totalSuaraFase2" class="text-xl font-bold text-blue-900">0</span>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg px-4 py-2 text-center shadow-sm">
            <span class="text-[10px] uppercase tracking-wider font-bold text-purple-600 block">Unik</span>
            <span id="totalKandidatUnik" class="text-xl font-bold text-purple-900">0</span>
        </div>
    </div>
</div>

<div class="bg-transparent md:bg-white md:rounded-xl md:shadow-sm md:border md:border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse responsive-table">
        <thead class="bg-gray-50 border-b border-gray-100 hidden md:table-header-group">
            <tr class="text-xs font-bold uppercase tracking-wider text-gray-500">
                <th class="py-3.5 px-6 w-12 text-center">Peringkat</th>
                <th class="py-3.5 px-6">Nama Tokoh Pilihan</th>
                <th class="py-3.5 px-6 w-1/3">Persentase Visual</th>
                <th class="py-3.5 px-6 w-24 text-center">Jumlah Suara</th>
            </tr>
        </thead>
        <tbody id="tabelNominasi" class="text-sm text-gray-700 divide-y divide-gray-100">
            <tr id="loadingRow">
                <td colspan="4" class="text-center py-12 text-gray-400 font-medium md:table-cell block bg-white">
                    <div class="flex items-center justify-center gap-3">
                        <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mengkalkulasi pivot data dari Firebase...
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
        db,
        auth
    } from '<?= base_url('assets/js/firebase-init.js') ?>';
    // Tambahkan set doc untuk menulis data kandidat final
    import {
        collection,
        onSnapshot,
        doc,
        setDoc
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
    import {
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    const tabelNominasi = document.getElementById('tabelNominasi');
    const totalSuaraFase2 = document.getElementById('totalSuaraFase2');
    const totalKandidatUnik = document.getElementById('totalKandidatUnik');
    const btnKunciTop3 = document.getElementById('btnKunciTop3');

    let semuaDataPemilih = [];
    let dataTop3 = []; // Menampung data 3 besar real-time

    onAuthStateChanged(auth, (user) => {
        if (user) {
            aktifkanPivotListener();
        } else {
            window.location.href = '<?= base_url('logout') ?>';
        }
    });

    function aktifkanPivotListener() {
        onSnapshot(collection(db, "pemilih"), (snapshot) => {
            const pivotCounts = {};
            let totalSuara = 0;

            snapshot.forEach((doc) => {
                const data = doc.data();
                if (data.fase2_sudah_memilih === true && data.fase2_pilihan_nama) {
                    const namaClean = data.fase2_pilihan_nama.trim().toUpperCase();
                    pivotCounts[namaClean] = (pivotCounts[namaClean] || 0) + 1;
                    totalSuara++;
                }
            });

            const sortedData = Object.keys(pivotCounts).map(nama => {
                return {
                    nama_tokoh: nama,
                    jumlah_suara: pivotCounts[nama]
                };
            }).sort((a, b) => b.jumlah_suara - a.jumlah_suara);

            // Ambil maksimal 3 baris teratas untuk dialirkan ke Fase 3
            dataTop3 = sortedData.slice(0, 3);

            totalSuaraFase2.innerText = totalSuara;
            totalKandidatUnik.innerText = sortedData.length;

            tabelNominasi.innerHTML = "";

            if (sortedData.length === 0) {
                tabelNominasi.innerHTML = `<tr><td colspan="4" class="text-center py-8 text-gray-400 block md:table-cell w-full bg-white rounded-lg shadow-sm">Belum ada suara nominasi yang masuk.</td></tr>`;
                return;
            }

            let rank = 1;
            sortedData.forEach((row) => {
                const persentase = totalSuara > 0 ? ((row.jumlah_suara / totalSuara) * 100).toFixed(1) : 0;

                const tr = document.createElement('tr');
                tr.className = "hover:bg-gray-50/50 transition-colors duration-150 bg-white md:bg-transparent";
                tr.innerHTML = `
                    <td data-label="Peringkat" class="md:py-3.5 md:px-6 text-center font-bold text-gray-500">${rank}</td>
                    <td data-label="Nama Tokoh" class="md:py-3.5 md:px-6 font-bold text-gray-800 tracking-wide uppercase">${row.nama_tokoh}</td>
                    <td data-label="Persentase" class="md:py-3.5 md:px-6">
                        <div class="w-full bg-gray-100 rounded-full h-2.5 flex overflow-hidden">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${persentase}%"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500 mt-1 block md:inline">${persentase}% dari total suara</span>
                    </td>
                    <td data-label="Jumlah Suara" class="md:py-3.5 md:px-6 text-center font-mono font-bold text-base text-blue-700 md:bg-blue-50/30">${row.jumlah_suara} Suara</td>
                `;
                tabelNominasi.appendChild(tr);
                rank++;
            });

        }, (error) => {
            console.error("Error pada Real-time Pivot:", error);
        });
    }

    // LOGIKA PROSES OTOMATISASI PINDAH KE FASE 3
    btnKunciTop3.addEventListener('click', () => {
        if (dataTop3.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Kosong',
                text: 'Belum ada data usulan masuk untuk diproses.',
                width: '280px'
            });
            return;
        }

        // Menyusun teks daftar nama yang lolos ke konfirmasi alert
        let HTMLDaftarKandidat = "<ol class='text-left text-sm bg-gray-50 p-3 rounded-lg border font-bold space-y-1 mt-2'>";
        dataTop3.forEach((k, i) => {
            HTMLDaftarKandidat += `<li>${i+1}. ${k.nama_tokoh} (${k.jumlah_suara} Suara)</li>`;
        });
        HTMLDaftarKandidat += "</ol>";

        Swal.fire({
            title: 'Rilis Sesi Final?',
            html: `3 Nama teratas berikut akan otomatis ditetapkan sebagai Kandidat Resmi di Bilik Suara Fase 3:<br>${HTMLDaftarKandidat}<br><span class="text-xs text-red-500 font-semibold block mt-1">Data kandidat fase 3 lama (jika ada) akan tertimpa.</span>`,
            icon: 'question',
            width: '320px',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#4b5563',
            confirmButtonText: 'Ya, Rilis!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mentransfer Data...',
                    width: '280px',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    // Looping eksekusi penyimpanan ke koleksi kandidat_final secara otomatis
                    for (let index = 0; index < dataTop3.length; index++) {
                        const kandidatId = `kandidat_${index + 1}`;
                        await setDoc(doc(db, "kandidat_final", kandidatId), {
                            nomor_urut: index + 1,
                            nama_tokoh: dataTop3[index].nama_tokoh,
                            visi_misi: "Visi dan misi belum diatur oleh panitia.",
                            foto_url: "" // Admin bisa update foto lewat Firestore Console jika dibutuhkan belakangan
                        });
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Fase 3 Aktif!',
                        text: '3 Besar berhasil ditetapkan menjadi kandidat final di bilik suara.',
                        width: '280px',
                        confirmButtonColor: '#16a34a'
                    });

                } catch (error) {
                    console.error("Gagal transfer data:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi masalah hak akses database.',
                        width: '280px'
                    });
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>