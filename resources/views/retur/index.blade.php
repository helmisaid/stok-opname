@extends('layouts.admin')

@section('content')
<div class="container card my-4 p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Retur Barang</h1>
        <span class="badge bg-primary fs-5">ID Pengadaan: <span id="idPengadaanLabel">-</span></span>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="mb-3">
        <label for="idpengadaan" class="form-label">Masukkan ID Pengadaan</label>
        <input type="text" class="form-control" id="idpengadaan" placeholder="Masukkan ID Pengadaan">
    </div>

    <button class="btn btn-primary" id="loadDetailBtn">Load Detail Penerimaan</button>

    <div id="detailPenerimaanTable" class="mt-4" style="display:none;">
        <h3>Detail Penerimaan</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Penerimaan ID</th>
                        <th>Tanggal Penerimaan</th>
                        <th>Status</th>
                        <th>Nama User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="detailPenerimaanBody">
                    <!-- Data penerimaan akan dimuat di sini -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Penerimaan -->
<div class="modal fade" id="modalDetailPenerimaan" tabindex="-1" aria-labelledby="modalDetailPenerimaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailPenerimaanLabel">Penerimaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah Terima</th>
                            <th>Harga Satuan</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody id="modalDetailBody">
                        <!-- Detail penerimaan akan dimuat di sini -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Pilihan Barang -->
<div class="modal fade" id="modalRetur" tabindex="-1" aria-labelledby="modalReturLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReturLabel">Form Retur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Detail Penerimaan</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Terima</th>
                                <th>Harga Satuan</th>
                                <th>Sub Total</th>
                                <th>Jumlah Retur</th>
                            </tr>
                        </thead>
                        <tbody id="returDetailPenerimaanBody">
                            <!-- Data detail penerimaan akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>

                <form id="formRetur" method="POST" action="{{ route('retur.store') }}">
                    @csrf
                    <input type="hidden" id="returIdPenerimaan" name="idpenerimaan">

                    <div id="barangInputContainer"></div> <!-- Container input barang -->

                    <div class="mb-3">
                        <label for="alasanRetur" class="form-label">Alasan Retur</label>
                        <textarea class="form-control" id="alasanRetur" rows="3" name="alasan" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Retur</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>






<script>
    let penerimaanData = []; // Variabel global untuk menyimpan data penerimaan

    document.getElementById('loadDetailBtn').addEventListener('click', function() {
        var idPengadaan = document.getElementById('idpengadaan').value;

        if (!idPengadaan) {
            alert("ID Pengadaan harus diisi!");
            return;
        }

        fetch('{{ route('retur.loadDetail') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ idpengadaan: idPengadaan })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            } else {
                // Simpan data penerimaan ke variabel global
                penerimaanData = data.penerimaan;

                document.getElementById('idPengadaanLabel').textContent = idPengadaan;
                var tableBody = document.getElementById('detailPenerimaanBody');
                tableBody.innerHTML = '';

                data.penerimaan.forEach(function(penerimaan) {
                    var status = penerimaan.status === '1' ? 'Selesai' : 'Proses Retur';
                    var penerimaanRow = document.createElement('tr');
                    penerimaanRow.innerHTML = `
                        <td>${penerimaan.idpenerimaan}</td>
                        <td>${penerimaan.tanggal_penerimaan || 'Tanggal tidak tersedia'}</td>
                        <td>${status}</td>
                        <td>${penerimaan.nama_user || 'Nama user tidak tersedia'}</td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="showDetailModal(${penerimaan.idpenerimaan})">Detail</button>
                            <button class="btn btn-danger btn-sm" onclick="openReturForm(${penerimaan.idpenerimaan})">Retur</button>
                        </td>
                    `;
                    tableBody.appendChild(penerimaanRow);
                });

                document.getElementById('detailPenerimaanTable').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data.');
        });
    });

    function showDetailModal(idPenerimaan) {
        // Mengambil detail penerimaan berdasarkan idPenerimaan
        var penerimaan = penerimaanData.find(penerimaan => penerimaan.idpenerimaan === idPenerimaan);
        var modalBody = document.getElementById('modalDetailBody');
        modalBody.innerHTML = ''; // Clear modal body

        penerimaan.details.forEach(function(item) {
            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.nama_barang}</td>
                <td>${item.jumlah_terima}</td>
                <td>${item.harga_satuan}</td>
                <td>${item.sub_total}</td>
            `;
            modalBody.appendChild(row);
        });

        // Menampilkan modal
        var myModal = new bootstrap.Modal(document.getElementById('modalDetailPenerimaan'));
        myModal.show();
    }
</script>
<script>
   function openReturForm(idPenerimaan) {
    // Set nilai hidden input untuk ID penerimaan
    document.getElementById('returIdPenerimaan').value = idPenerimaan;

    // Cari data penerimaan berdasarkan idPenerimaan dari penerimaanData
    var penerimaan = penerimaanData.find(item => item.idpenerimaan === idPenerimaan);

    if (!penerimaan) {
        alert('Data penerimaan tidak ditemukan.');
        return;
    }

    // Menampilkan detail penerimaan di tabel modal
    var returDetailBody = document.getElementById('returDetailPenerimaanBody');
    returDetailBody.innerHTML = ''; // Bersihkan isi tabel sebelumnya

    // Clear barang input container sebelum menambahkan yang baru
    var barangInputContainer = document.getElementById('barangInputContainer');
    barangInputContainer.innerHTML = ''; // Bersihkan input barang sebelumnya

    penerimaan.details.forEach(function(item) {
        var row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <input type="checkbox" class="form-check-input pilih-barang" name="pilih_barang[]" value="${item.id}" data-id="${item.id}">
            </td>
            <td>${item.nama_barang}</td>
            <td>${item.jumlah_terima}</td>
            <td>${item.harga_satuan}</td>
            <td>${item.sub_total}</td>
            <td>
                <input type="number" class="form-control jumlah-retur" name="jumlah_retur[${item.id}]" min="1" max="${item.jumlah_terima}" disabled>
            </td>
        `;
        returDetailBody.appendChild(row);

        // Menambahkan input barang dan jumlah retur ke form
        var barangInput = document.createElement('div');
        barangInput.innerHTML = `
            <input type="hidden" name="barang[${item.id}][id]" value="${item.id}">
            <input type="hidden" name="barang[${item.id}][nama]" value="${item.nama_barang}">
        `;
        barangInputContainer.appendChild(barangInput);
    });

    // Menampilkan modal retur
    var modalRetur = new bootstrap.Modal(document.getElementById('modalRetur'));
    modalRetur.show();

    // Aktifkan/Nonaktifkan input jumlah retur berdasarkan checkbox
    document.querySelectorAll('.pilih-barang').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const row = this.closest('tr');
            const jumlahReturInput = row.querySelector('.jumlah-retur');
            jumlahReturInput.disabled = !this.checked;
            if (!this.checked) {
                jumlahReturInput.value = ''; // Reset input jika checkbox tidak dipilih
            }
        });
    });

    // Menangani pengiriman data barang yang dipilih
    document.getElementById('formRetur').addEventListener('submit', function(event) {
    // Collect selected items with valid return quantities
    let selectedBarang = [];
    document.querySelectorAll('.pilih-barang:checked').forEach(function(checkbox) {
        const row = checkbox.closest('tr');
        const jumlahReturInput = row.querySelector('.jumlah-retur');
        const jumlahRetur = jumlahReturInput.value;

        if (jumlahRetur > 0) {
            selectedBarang.push({
                iddetail_penerimaan: checkbox.value,
                jumlah_retur: jumlahRetur
            });
        }
    });

    // If no valid items are selected, prevent form submission
    if (selectedBarang.length === 0) {
        alert('Silakan pilih barang yang ingin diretur dengan jumlah yang valid.');
        event.preventDefault();
        return;
    }

    // Add the selected items to the formData
    let formData = new FormData(this);
    formData.append('barang', JSON.stringify(selectedBarang));
});

}



</script>


@endsection
