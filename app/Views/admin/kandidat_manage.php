<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manajemen Profil Kandidat Final</h2>
    <p class="text-gray-500 text-sm">Lengkapi foto resmi serta visi-misi dari 3 kandidat terbaik hasil saringan Fase 2.</p>
</div>

<div id="gridKandidat" class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="col-span-1 md:col-span-3 text-center py-12 text-gray-400 font-medium bg-white rounded-xl border">
        <div class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Sinkronisasi data kandidat final...
        </div>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all border-t-4 border-blue-600 animate-fadeIn">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-base font-bold text-gray-800">Ubah Profil Data <span id="modalNoUrut" class="text-blue-600"></span></h3>
            <button id="btnTutupModal" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <form id="formEditKandidat" class="p-5 space-y-4">
            <input type="hidden" id="editDocId">

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Tokoh</label>
                <input type="text" id="editNama" readonly class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm font-bold text-gray-700 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-3">Foto Profil Kandidat</label>

                <div class="flex justify-center mb-4">
                    <img id="previewFoto" src="" alt="Pratinjau Foto" class="w-24 h-24 rounded-full object-cover border-4 border-gray-100 shadow-md hidden bg-white">
                </div>

                <div class="space-y-3">
                    <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <span class="text-[10px] font-bold text-blue-600 block mb-1.5 uppercase tracking-wide">Pilihan A: Unggah dari Perangkat</span>
                        <input type="file" id="editFotoFile" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    <div class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-wider">- Atau -</div>

                    <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <span class="text-[10px] font-bold text-amber-600 block mb-1 uppercase tracking-wide">Pilihan B: Alamat URL Gambar Internet</span>
                        <input type="text" id="editFotoUrl" placeholder="https://link-gambar.com/foto.jpg" autocomplete="off" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Visi & Misi Kandidat</label>
                <textarea id="editVisiMisi" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 font-medium" placeholder="Tuliskan visi misi singkat kandidat di sini..."></textarea>
            </div>

            <div class="pt-2 flex gap-2">
                <button type="button" id="btnBatalModal" class="w-1/3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2 rounded-lg text-xs transition">Batal</button>
                <button type="submit" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-xs transition shadow-sm">Simpan Perubahan</button>
            </div>
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
        collection,
        onSnapshot,
        doc,
        updateDoc
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
    import {
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    const gridKandidat = document.getElementById('gridKandidat');
    const modalEdit = document.getElementById('modalEdit');
    const formEditKandidat = document.getElementById('formEditKandidat');

    // Element Input Modal
    const editDocId = document.getElementById('editDocId');
    const editNama = document.getElementById('editNama');
    const editFotoUrl = document.getElementById('editFotoUrl');
    const editVisiMisi = document.getElementById('editVisiMisi');
    const modalNoUrut = document.getElementById('modalNoUrut');
    const editFotoFile = document.getElementById('editFotoFile');
    const previewFoto = document.getElementById('previewFoto'); // Elemen Preview

    let dataKandidatLokal = {};

    // Cek Sesi Keamanan Firebase Client
    onAuthStateChanged(auth, (user) => {
        if (user) {
            aktifkanKandidatListener();
        } else {
            window.location.href = '<?= base_url('logout') ?>';
        }
    });

    // 1. Ambil Data Kandidat Final Secara Real-time
    function aktifkanKandidatListener() {
        onSnapshot(collection(db, "kandidat_final"), (snapshot) => {
            gridKandidat.innerHTML = "";
            dataKandidatLokal = {};

            if (snapshot.empty) {
                gridKandidat.innerHTML = `
                    <div class="col-span-1 md:col-span-3 text-center py-12 text-gray-400 font-medium bg-white rounded-xl border">
                        Belum ada data kandidat. Silakan kunci hasil Fase 2 terlebih dahulu di menu Data Nominasi.
                    </div>`;
                return;
            }

            // Urutkan dokumen berdasarkan nomor urut secara lokal
            const docsSorted = [];
            snapshot.forEach(doc => {
                docsSorted.push({
                    id: doc.id,
                    ...doc.data()
                });
            });
            docsSorted.sort((a, b) => a.nomor_urut - b.nomor_urut);

            docsSorted.forEach((k) => {
                dataKandidatLokal[k.id] = k; // Simpan ke wadah lokal untuk referensi modal

                // Logika pencocokan gambar logo/avatar pintar (mendukung folder lokal atau link luar)
                let imageSrc = `https://ui-avatars.com/api/?name=${encodeURIComponent(k.nama_tokoh)}&background=1e3a8a&color=fff&size=150`;
                if (k.foto_url && k.foto_url.trim() !== "") {
                    imageSrc = k.foto_url.startsWith('http') ? k.foto_url : '<?= base_url() ?>' + k.foto_url;
                }

                const card = document.createElement('div');
                card.className = "bg-white border border-gray-100 rounded-xl shadow-sm p-5 flex flex-col items-center text-center relative";
                card.innerHTML = `
                    <span class="absolute top-4 left-4 bg-blue-900 text-white w-7 h-7 flex items-center justify-center rounded-full text-xs font-black border-2 border-white shadow-sm">
                        ${k.nomor_urut}
                    </span>
                    <img class="w-20 h-20 rounded-full object-cover border-4 border-gray-50 shadow-sm mb-3" src="${imageSrc}" alt="Foto Profil">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-1 px-4 truncate w-full">${k.nama_tokoh}</h4>
                    <p class="text-xs text-gray-400 italic line-clamp-3 mb-5 px-2 flex-grow">"${k.visi_misi}"</p>
                    <button class="btn-buka-edit bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold py-2 px-4 rounded-lg text-xs transition w-full border border-blue-100" data-id="${k.id}">
                        🔧 Edit Profil & Visi-Misi
                    </button>
                `;
                gridKandidat.appendChild(card);
            });

            registerEventModal();
        });
    }

    function registerEventModal() {
        document.querySelectorAll('.btn-buka-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const k = dataKandidatLokal[id];

                editDocId.value = id;
                editNama.value = k.nama_tokoh;
                editFotoUrl.value = k.foto_url || "";
                editVisiMisi.value = k.visi_misi || "";
                modalNoUrut.innerText = `Kandidat No. ${k.nomor_urut}`;

                // Setel Preview Bawaan saat modal pertama kali dibuka
                let defaultImageSrc = `https://ui-avatars.com/api/?name=${encodeURIComponent(k.nama_tokoh)}&background=1e3a8a&color=fff&size=150`;
                if (k.foto_url && k.foto_url.trim() !== "") {
                    defaultImageSrc = k.foto_url.startsWith('http') ? k.foto_url : '<?= base_url() ?>' + k.foto_url;
                }
                previewFoto.src = defaultImageSrc;
                previewFoto.classList.remove('hidden');

                modalEdit.classList.remove('hidden');
            });
        });
    }

    // TRIGGER PREVIEW SAAT FILE DIUNGGAH
    editFotoFile.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewFoto.src = event.target.result;
            }
            reader.readAsDataURL(file);
            editFotoUrl.value = ""; // Kosongkan input URL agar tidak tumpang tindih
        }
    });

    // TRIGGER PREVIEW SAAT URL DIKETIK
    editFotoUrl.addEventListener('input', function(e) {
        const urlValue = e.target.value.trim();
        if (urlValue !== "") {
            // Cek apakah tautan luar atau tautan lokal CI4
            previewFoto.src = urlValue.startsWith('http') ? urlValue : '<?= base_url() ?>' + urlValue;
            editFotoFile.value = ""; // Kosongkan input file agar tidak tumpang tindih
        }
    });

    function tutupModal() {
        modalEdit.classList.add('hidden');
        formEditKandidat.reset();
        editFotoFile.value = "";
        previewFoto.classList.add('hidden');
    }
    document.getElementById('btnTutupModal').addEventListener('click', tutupModal);
    document.getElementById('btnBatalModal').addEventListener('click', tutupModal);

    // Proses Submit Form
    formEditKandidat.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = editDocId.value;
        let finalFotoUrl = editFotoUrl.value.trim();

        Swal.fire({
            title: 'Menyimpan...',
            width: '280px',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            if (editFotoFile.files.length > 0) {
                const formData = new FormData();
                formData.append('foto_kandidat', editFotoFile.files[0]);

                const uploadResponse = await fetch('<?= base_url('panel/kandidat/upload') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const uploadResult = await uploadResponse.json();

                if (uploadResult.status === 'success') {
                    finalFotoUrl = uploadResult.file_path;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Unggah',
                        text: uploadResult.message,
                        width: '280px'
                    });
                    return;
                }
            }

            const docRef = doc(db, "kandidat_final", id);
            await updateDoc(docRef, {
                foto_url: finalFotoUrl,
                visi_misi: editVisiMisi.value.trim()
            });

            tutupModal();
            Swal.fire({
                icon: 'success',
                title: 'Profil Diperbarui',
                width: '280px',
                showConfirmButton: false,
                timer: 1000
            });

        } catch (error) {
            console.error("Gagal update profil:", error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Jaringan',
                text: 'Terjadi gangguan komunikasi database atau server.',
                width: '280px'
            });
        }
    });

    // GANTI BAGIAN SUBMIT HANDLER DI SECTIONS SCRIPTS (kandidat_manage.php)

    // // Fungsi Tutup Modal (Disempurnakan untuk reset input file)
    // function tutupModal() {
    //     modalEdit.classList.add('hidden');
    //     formEditKandidat.reset();
    //     document.getElementById('editFotoFile').value = "";
    // }
    // document.getElementById('btnTutupModal').addEventListener('click', tutupModal);
    // document.getElementById('btnBatalModal').addEventListener('click', tutupModal);

    // Proses Submit Form
    formEditKandidat.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = editDocId.value;
        const fileInput = document.getElementById('editFotoFile');
        let finalFotoUrl = editFotoUrl.value.trim();

        Swal.fire({
            title: 'Menyimpan...',
            width: '280px',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            // PROSES 1: JIKA ADMIN PILIH UNGGAH FILE GAMBAR
            if (fileInput.files.length > 0) {
                const formData = new FormData();
                formData.append('foto_kandidat', fileInput.files[0]);

                // Kirim file ke backend CI4 via AJAX Fetch
                const uploadResponse = await fetch('<?= base_url('panel/kandidat/upload') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const uploadResult = await uploadResponse.json();

                if (uploadResult.status === 'success') {
                    // Ambil path lokasi file baru dari server CI4
                    finalFotoUrl = uploadResult.file_path;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Unggah',
                        text: uploadResult.message,
                        width: '280px'
                    });
                    return; // Hentikan proses jika gagal upload gambar
                }
            }

            // PROSES 2: DORONG PERUBAHAN KE FIRESTORE DATA
            const docRef = doc(db, "kandidat_final", id);
            await updateDoc(docRef, {
                foto_url: finalFotoUrl,
                visi_misi: editVisiMisi.value.trim()
            });

            tutupModal();
            Swal.fire({
                icon: 'success',
                title: 'Profil Diperbarui',
                width: '280px',
                showConfirmButton: false,
                timer: 1000
            });

        } catch (error) {
            console.error("Gagal update profil:", error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Jaringan',
                text: 'Terjadi gangguan komunikasi database atau server.',
                width: '280px'
            });
        }
    });
</script>
<?= $this->endSection() ?>