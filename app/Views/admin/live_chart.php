<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Bilik Suara Live Count (Fase 3)</h2>
        <p class="text-gray-500 text-sm">Grafik perolehan suara puncak bergeser secara real-time setiap kali ada suara masuk.</p>
    </div>

    <div class="bg-blue-900 text-white rounded-xl px-5 py-2.5 text-center shadow-md flex items-center gap-3">
        <div class="text-left">
            <span class="text-[10px] uppercase tracking-wider text-blue-200 block font-bold">Total Suara Masuk</span>
            <span id="votedCount" class="text-2xl font-black font-mono">0</span>
        </div>
        <div class="border-l border-blue-700 h-8 pl-2 text-left">
            <span class="text-[10px] uppercase tracking-wider text-blue-200 block font-bold">Belum Memilih</span>
            <span id="unvotedCount" class="text-sm font-bold font-mono text-amber-400">0</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 flex flex-col justify-center min-h-[350px]">
        <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Diagram Batang Perolehan Suara</h3>
        <div class="relative w-full h-64 md:h-80">
            <canvas id="liveChartCanvas"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 flex flex-col">
        <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Papan Skor Digital</h3>
        <div id="papanSkorContainer" class="space-y-3 flex-grow overflow-y-auto">
            <div class="text-center py-8 text-gray-400">
                <svg class="animate-spin h-5 w-5 text-blue-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menghubungkan ke server suara...
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
        getDocs,
        query,
        orderBy
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
    import {
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    const papanSkorContainer = document.getElementById('papanSkorContainer');
    const votedCountWidget = document.getElementById('votedCount');
    const unvotedCountWidget = document.getElementById('unvotedCount');

    let liveChartInstance = null;
    let mapKandidat = {}; // Menyimpan data master kandidat {id: {nama, nomor}}

    // Pastikan admin terotentikasi di Firebase Client
    onAuthStateChanged(auth, (user) => {
        if (user) {
            jalankanSistemLiveCount();
        } else {
            window.location.href = '<?= base_url('logout') ?>';
        }
    });

    async function jalankanSistemLiveCount() {
        try {
            // 1. Ambil Master Data Kandidat Terlebih Dahulu agar Nama Tampil di Grafik
            const qKandidat = query(collection(db, "kandidat_final"), orderBy("nomor_urut", "asc"));
            const snapshotKandidat = await getDocs(qKandidat);

            snapshotKandidat.forEach(doc => {
                mapKandidat[doc.id] = {
                    nama: doc.data().nama_tokoh,
                    nomor: doc.data().nomor_urut
                };
            });

            // 2. Inisialisasi Kerangka Awal Chart.js (Grafik Kosong)
            initChartJS();

            // 3. Pasang Real-time Listener pada Koleksi Pemilih untuk Menghitung Suara
            onSnapshot(collection(db, "pemilih"), (snapshotPemilih) => {
                let totalVoted = 0;
                let totalUnvoted = 0;

                // Siapkan objek tampungan hitungan suara per ID kandidat
                const kalkulasiSuara = {};
                Object.keys(mapKandidat).forEach(id => {
                    kalkulasiSuara[id] = 0;
                });

                snapshotPemilih.forEach((doc) => {
                    const data = doc.data();
                    if (data.status_verifikasi === 'verified') {
                        if (data.fase3_sudah_memilih === true && data.fase3_pilihan_id) {
                            const pilihanId = data.fase3_pilihan_id;
                            if (kalkulasiSuara[pilihanId] !== undefined) {
                                kalkulasiSuara[pilihanId]++;
                            }
                            totalVoted++;
                        } else {
                            totalUnvoted++;
                        }
                    }
                });

                // Update Angka Widget Atas
                votedCountWidget.innerText = totalVoted;
                unvotedCountWidget.innerText = totalUnvoted;

                // 4. Dorong Data Baru ke Grafik & Papan Skor
                updateVisualisasi(kalkulasiSuara, totalVoted);

            }, (error) => {
                console.error("Gagal mendengarkan data suara:", error);
            });

        } catch (error) {
            console.error("Gagal inisialisasi sistem live count:", error);
            papanSkorContainer.innerHTML = '<p class="text-center text-red-500 font-bold">Gagal memuat master kandidat.</p>';
        }
    }

    // Daftarkan Plugin Label Persentase
    Chart.register(ChartDataLabels);

    function initChartJS() {
        const ctx = document.getElementById('liveChartCanvas').getContext('2d');

        // Buat label
        const labels = Object.values(mapKandidat).map(k => k.nama);
        const dataAwal = Object.values(mapKandidat).map(() => 0);

        liveChartInstance = new Chart(ctx, {
            type: 'pie', // UBAH KE PIE CHART
            data: {
                labels: labels,
                datasets: [{
                    data: dataAwal,
                    // Palet Warna Mewah
                    backgroundColor: ['rgba(37, 99, 235, 0.9)', 'rgba(22, 163, 74, 0.9)', 'rgba(217, 119, 6, 0.9)'],
                    borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                    borderWidth: 3,
                    hoverOffset: 10 // Efek mekar saat disorot mouse
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 20
                },
                plugins: {
                    // Tampilkan Legend di bawah lingkaran
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Ubuntu',
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    // Label angka persentase yang menempel di atas kue
                    datalabels: {
                        color: '#ffffff',
                        font: {
                            family: 'Ubuntu',
                            size: 14,
                            weight: 'bold'
                        },
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            if (sum === 0 || value === 0) return ''; // Sembunyikan jika 0
                            let percentage = (value * 100 / sum).toFixed(1) + "%";
                            return percentage;
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) label += ': ';
                                label += context.parsed + ' Suara';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    function updateVisualisasi(kalkulasiSuara, totalVoted) {
        // 1. Update Data di Pie Chart
        const dataSuaraUrut = Object.keys(mapKandidat).map(id => kalkulasiSuara[id]);
        liveChartInstance.data.datasets[0].data = dataSuaraUrut;
        liveChartInstance.update();

        // 2. Update Data di Papan Skor Samping (Urutan Tetap)
        papanSkorContainer.innerHTML = "";

        Object.keys(mapKandidat).forEach((id, index) => {
            const k = mapKandidat[id];
            const jumlahSuara = kalkulasiSuara[id];
            const persentase = totalVoted > 0 ? ((jumlahSuara / totalVoted) * 100).toFixed(1) : 0;

            // Penanda warna sesuai urutan array backgroundColor pie chart
            const dotColors = ['bg-blue-600', 'bg-green-600', 'bg-amber-600'];

            const row = document.createElement('div');
            row.className = "flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100/50 transition";
            row.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full ${dotColors[index] || 'bg-gray-500'}"></span>
                    <div>
                        <h4 class="font-bold text-gray-800 text-xs uppercase tracking-wide truncate max-w-[120px] md:max-w-none">${k.nama}</h4>
                        <span class="text-[10px] text-gray-500 font-semibold">${persentase}% Dari Suara Masuk</span>
                    </div>
                </div>
                <div class="text-right font-mono font-black text-sm text-blue-900 bg-white border border-gray-200 px-2.5 py-1 rounded shadow-sm">
                    ${jumlahSuara} ST
                </div>
            `;
            papanSkorContainer.appendChild(row);
        });
    }
</script>
<?= $this->endSection() ?>