@extends('layouts.admin')


@section('content')
<div class="container card py-10 px-5">
    <h1 class="mt-4">Tambah Penjualan</h1>
    <form id="addPengadaanForm" action="{{ route('penjualan.store') }}" method="POST">
        @csrf
        <!-- Detail Barang -->
        <h5 class="mt-4">Detail Barang</h5>
        <div id="barang-container">
            <div class="form-group row barang-row">
                <div class="col-md-4">
                    <label>Barang</label>
                    <select class="form-control barang-select select2" name="items[0][idbarang]" data-index="0" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->idbarang }}" data-harga="{{ $barang->harga }}">{{ $barang->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Harga Satuan</label>
                    <input type="number" class="form-control harga-satuan" name="items[0][harga]" placeholder="Harga Satuan" readonly required>
                </div>
                <div class="col-md-3">
                    <label>Jumlah</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-danger btn-sm minus-qty" data-index="0">-</button>
                        <input type="number" class="form-control jumlah-barang" name="items[0][jumlah]" placeholder="Jumlah" value="1" data-index="0" min="0" required>
                        <button type="button" class="btn btn-success btn-sm plus-qty" data-index="0">+</button>
                    </div>
                </div>
                <div class="col-md-2 mt-4">
                    <button type="button" class="btn btn-danger remove-item">Hapus</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-success mt-3" id="add-new-barang">Tambah Barang Lain</button>

        <!-- Input PPN -->
        <div class="form-group mt-4">
            <label for="ppnInput">PPN (%)</label>
            <input type="number" class="form-control" id="ppnInput" name="ppn" value="0" min="0" max="100" required>
        </div>

        <!-- Ringkasan -->
        <div class="mt-4">
            <h5>Ringkasan</h5>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <span id="subtotal" class="fw-bold">Rp 0</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>PPN:</span>
                    <span id="ppn" class="fw-bold">Rp 0</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total:</span>
                    <span id="total" class="fw-bold">Rp 0</span>
                </li>
            </ul>
        </div>

        <!-- Submit Button -->
        <div class="my-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
    let itemIndex = 1;

    // Tambah barang baru
    document.getElementById('add-new-barang').addEventListener('click', function () {
        const container = document.getElementById('barang-container');
        const newBarangRow = document.createElement('div');
        newBarangRow.classList.add('form-group', 'row', 'barang-row');
        newBarangRow.innerHTML = `
            <div class="col-md-4">
                <label>Barang</label>
                <select class="form-control barang-select" name="items[${itemIndex}][idbarang]" data-index="${itemIndex}" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->idbarang }}" data-harga="{{ $barang->harga }}">{{ $barang->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Harga Satuan</label>
                <input type="number" class="form-control harga-satuan" name="items[${itemIndex}][harga]" placeholder="Harga Satuan" readonly required>
            </div>
            <div class="col-md-3">
                <label>Jumlah</label>
                <div class="input-group">
                    <button type="button" class="btn btn-danger btn-sm minus-qty" data-index="${itemIndex}">-</button>
                    <input type="number" class="form-control jumlah-barang" name="items[${itemIndex}][jumlah]" placeholder="Jumlah" value="1" min="1" data-index="${itemIndex}" required>
                    <button type="button" class="btn btn-success btn-sm plus-qty" data-index="${itemIndex}">+</button>
                </div>
            </div>
            <div class="col-md-2 mt-4">
                <button type="button" class="btn btn-danger remove-item">Hapus</button>
            </div>
        `;

        container.appendChild(newBarangRow);

        // Tambah event listener
        newBarangRow.querySelector('.barang-select').addEventListener('change', updateRingkasan);
        newBarangRow.querySelector('.jumlah-barang').addEventListener('input', updateRingkasan);
        newBarangRow.querySelector('.plus-qty').addEventListener('click', incrementQty);
        newBarangRow.querySelector('.minus-qty').addEventListener('click', decrementQty);
        newBarangRow.querySelector('.remove-item').addEventListener('click', function () {
            newBarangRow.remove();
            updateRingkasan();
        });

        itemIndex++;
    });

    function incrementQty(event) {
        const input = event.target.parentElement.querySelector('.jumlah-barang');
        input.value = parseInt(input.value) + 1; // Tambah 1 ke jumlah
        updateRingkasan();
    }

    function decrementQty(event) {
        const input = event.target.parentElement.querySelector('.jumlah-barang');
        if (parseInt(input.value) > 1) { // Cegah jumlah menjadi kurang dari 1
            input.value = parseInt(input.value) - 1;
            updateRingkasan();
        }
    }

        document.querySelectorAll('.jumlah-barang').forEach(function (input) {
        input.addEventListener('input', function () {
            // Cegah input negatif
            if (parseInt(this.value) < 0) {
                this.value = 0; // Set ke 0 jika pengguna mencoba memasukkan angka negatif
            }
        });
    });


    // Fungsi untuk memperbarui ringkasan
function updateRingkasan() {
    let subtotal = 0;

    // Hitung subtotal dari barang yang dipilih
    document.querySelectorAll('.barang-row').forEach((row, index) => {
        const barangSelect = row.querySelector('.barang-select');
        const hargaInput = row.querySelector('.harga-satuan');
        const jumlahInput = row.querySelector('.jumlah-barang');

        if (barangSelect && hargaInput && jumlahInput) {
            const selectedOption = barangSelect.selectedOptions[0];
            const harga = selectedOption ? parseFloat(selectedOption.getAttribute('data-harga')) || 0 : 0;
            const jumlah = parseFloat(jumlahInput.value) || 0;

            hargaInput.value = harga; // Perbarui harga satuan jika ada perubahan
            subtotal += harga * jumlah;
        }
    });

    // Ambil nilai PPN dari input PPN
    const ppnPercentage = parseFloat(document.getElementById('ppnInput').value) || 0;
    const ppn = (subtotal * ppnPercentage) / 100; // Hitung PPN berdasarkan persentase

    // Total = subtotal + PPN
    const total = subtotal + ppn;

    // Update UI untuk ringkasan
    document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString()}`;
    document.getElementById('ppn').textContent = `Rp ${ppn.toLocaleString()}`;
    document.getElementById('total').textContent = `Rp ${total.toLocaleString()}`;
}

// Event listener untuk PPN input
document.getElementById('ppnInput').addEventListener('input', updateRingkasan);

// Event listener untuk barang dan jumlah barang
document.querySelectorAll('.barang-select').forEach(select => {
    select.addEventListener('change', updateRingkasan);
});
document.querySelectorAll('.jumlah-barang').forEach(input => {
    input.addEventListener('input', updateRingkasan);
});
document.querySelectorAll('.plus-qty').forEach(button => {
    button.addEventListener('click', incrementQty);
});
document.querySelectorAll('.minus-qty').forEach(button => {
    button.addEventListener('click', decrementQty);
});



</script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Cari Barang",
            allowClear: true
        });
    });

    // Re-initialize Select2 setiap kali elemen baru ditambahkan
    document.getElementById('add-new-barang').addEventListener('click', function () {
        setTimeout(() => {
            $('.select2').select2({
                placeholder: "Cari Barang",
                allowClear: true
            });
        }, 100);
    });
</script>
<script>
    // Script untuk memuat data detail pengadaan ke modal
    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('[data-bs-target="#detailModal"]');
        detailButtons.forEach(button => {
            button.addEventListener('click', function () {
                const idPengadaan = button.getAttribute('data-idpengadaan');
                fetch(`{{ url('/pengadaan/detail') }}/${idPengadaan}`)
                    .then(response => response.json())
                    .then(data => {
                        let content = `
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah Pesan</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        data.forEach(item => {
                            content += `
                                <tr>
                                    <td>${item.nama_barang}</td>
                                    <td>${item.harga_satuan}</td>
                                    <td>${item.jumlah}</td>
                                    <td>${item.sub_total}</td>
                                </tr>
                            `;
                        });
                        content += `</tbody></table>`;
                        document.getElementById('detailContent').innerHTML = content;
                    })
                    .catch(error => {
                        document.getElementById('detailContent').innerHTML = `
                            <div class="alert alert-danger">Gagal memuat data.</div>
                        `;
                    });
            });
        });
    });
</script>

@endsection
