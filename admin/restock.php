<div class="page product-page">
    <h1>Restock Barang</h1>
    <div class="create-container">
        <button class="createBtn" id="createProductBtn">Form Restock Barang</button>
    </div>

    <div class="filter-container">
        <div class="filter">
            <label for="from_date">Dari :</label>
            <input type="date" id="from_date" name="from_date">

            <label for="to_date">Sampai :</label>
            <input type="date" id="to_date" name="to_date">
        </div>
        <button id="filter_btn">Filter</button>
    </div>

    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>Tanggal Pembuatan Data</th>
                <th>Nama Barang beserta Satuan</th>
                <th>Nama Supplier</th>
                <th>Jumlah Restock</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- Popup modal -->
    <div id="RestockModal" class="modal" style="display: none;">
        <div class="modal-content" style="position: relative;">
            <h2>Form Restock Barang</h2>
            <form id="restockForm">
                <label for="tanggal_pembelian">Tanggal Pembelian:</label>
                <input type="date" id="tanggal_pembelian" name="tanggal_pembelian" required>

                <label for="supplier_id">Nama Supplier:</label>
                <select id="supplier_id" name="supplier_id" required style="width: 100%;">
                    <option value="">Pilih Supplier</option>
                </select>

                <label for="product_id">Nama Barang:</label>
                <select id="product_id" name="product_id" required style="width: 100%;">
                    <option value="">Pilih Barang</option>
                </select>

                <label for="harga_jual">Harga Jual:</label>
                <input type="number" id="harga_jual" name="harga_jual" required min="0" placeholder="Masukkan harga jual">

                <label for="harga_beli">Harga Beli:</label>
                <input type="number" id="harga_beli" name="harga_beli" required min="0" placeholder="Masukkan harga beli">

                <label for="selisih">Selisih / Keuntungan:</label>
                <input type="number" id="selisih" name="selisih" readonly placeholder="Otomatis">

                <label for="sisa_stock">Sisa Stock Terakhir:</label>
                <input type="number" id="sisa_stock" name="sisa_stock" readonly placeholder="Otomatis">

                <label for="restock_quantity">Tambahin Stock Baru / Restock:</label>
                <input type="number" id="restock_quantity" name="restock_quantity" required min="1" placeholder="Jumlah restock">

                <label for="total_stock">Jumlah Stock Baru + Sisa Stock Terakhir:</label>
                <input type="number" id="total_stock" name="total_stock" readonly placeholder="Otomatis">

                <label for="tax">Tax:</label>
                <input type="number" id="tax" name="tax" min="0" step="0.01" placeholder="0.00">
            </form>

            <div style="position: sticky; bottom: 0; margin-top: 16px; padding-top: 12px; text-align: right; background: #fefefe; border-top: 1px solid #ddd;">
                <button type="button" id="closeModalBtn">Batal</button>
                <button type="button" id="saveRestockBtn">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Popup modal detail pembelian -->
    <div id="DetailModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Detail Restock</h2>
            <div id="detailContent">
                <!-- Detail akan diisi oleh JS -->
            </div>
            <div style="text-align: right;">
                <button type="button" id="closeDetailModalBtn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // on document ready
    $(document).ready(function () {
        // init
        const start_date = document.getElementById('from_date');
        const to_date    = document.getElementById('to_date');
        let edit_price   = false; // Flag for edit price mode
        start_date.value = new Date().toISOString().split('T')[0]; // Set to today
        to_date.value    = new Date().toISOString().split('T')[0]; // Set to today

        const product_list = {};
        const supplier_list = {};

        async function fetchSupplier() {
            try {
                const result = await callAPI({ url: '../api/supplier.php', body: { method: 'read' } });
                const supplierSelect = document.getElementById('supplier_id');
                supplierSelect.innerHTML = '<option value="">Pilih Supplier</option>';
                result.data.forEach(supplier => {
                    const option = document.createElement('option');
                    option.value = supplier.id;
                    option.textContent = supplier.nama;
                    supplierSelect.appendChild(option);
                    supplier_list[supplier.id] = supplier;
                });

                $("#supplier_id").select2({
                    placeholder: "Pilih Supplier",
                    allowClear: true
                });
            } catch (error) {
                console.error('Gagal memuat supplier:', error);
            }
        }

        async function fetchProduct() {
            try {
                const result = await callAPI({ url: '../api/product.php', body: { method: 'read' } });
                const productSelect = document.getElementById('product_id');
                productSelect.innerHTML = '<option value="">Pilih Barang</option>';
                result.data.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id_product;
                    option.textContent = product.nama_product + " - " + product.nama_satuan;
                    productSelect.appendChild(option);

                    product_list[product.id_product] = product;
                });

                // Initialize Select2 for product select
                $("#product_id").select2({
                    placeholder: "Pilih Barang",
                    allowClear: true
                }).on('change', function() {
                    const productId = $(this).val();
                    const product = product_list[productId];
                    if (product) {
                        document.getElementById('harga_jual').value = product.harga_jual_product;
                        document.getElementById('sisa_stock').value = product.stok_product;
                        document.getElementById('harga_beli').value = product.harga_beli_product;
                        updateSelisih();
                        updateTotalStock();
                    }
                });
            } catch (error) {
                console.error('Gagal memuat barang:', error);
            }
        }
        fetchSupplier();
        fetchProduct();

        // get data pembelian
        function formatDateToDDMMYYYY(dateInput) {
            if (!dateInput) return '-';
            const dateOnly = String(dateInput).split(' ')[0];
            const parts = dateOnly.split('-');
            if (parts.length !== 3) return dateOnly;
            return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }

        async function fetchPembelian() {
            try {
                const from_date = document.getElementById('from_date').value;
                const to_date = document.getElementById('to_date').value;
                const result = await callAPI({ url: '../api/restock.php', body: { method: 'read', from_date, to_date } });
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = ''; // Clear existing rows

                result.data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${formatDateToDDMMYYYY(item.created_at)}</td>
                        <td>${item.nama_product} - ${item.nama_satuan}</td>
                        <td>${item.nama_supplier}</td>
                        <td>${item.jumlah_pembelian}</td>
                        <td><button class="detailBtn" data-id="${item.id_detail_pembelian}">Detail</button> <button class="deleteBtn" data-id="${item.id_detail_pembelian}">Delete</button></td>
                    `;
                    tbody.appendChild(row);
                });

                // Add event listener for detail buttons
                document.querySelectorAll('.detailBtn').forEach(button => {
                    button.addEventListener('click', function () {
                        const idDetail = this.getAttribute('data-id');
                        const detailModal = document.getElementById('DetailModal');
                        const detailContent = document.getElementById('detailContent');
                        detailModal.style.display = 'flex';

                        callAPI({ url: '../api/restock.php', body: { method: 'read', action: 'detail', id_detail: idDetail } })
                            .then(result => {
                                const detail = result.data[0];
                                detailContent.innerHTML = `
                                    <p><strong>Tanggal Pembelian:</strong> ${formatDateToDDMMYYYY(detail.created_at)}</p>
                                    <p><strong>Nama Supplier:</strong> ${detail.nama_supplier}</p>
                                    <p><strong>Nama Barang:</strong> ${detail.nama_product}</p>
                                    <p><strong>Satuan:</strong> ${detail.nama_satuan}</p>
                                    <p><strong>Harga Jual:</strong> ${formatCurrencyIDR(detail.harga_jual)}</p>
                                    <p><strong>Harga Beli:</strong> ${formatCurrencyIDR(detail.harga_pembelian)}</p>
                                    <p><strong>Selisih:</strong> ${formatCurrencyIDR(detail.harga_jual - detail.harga_pembelian)}</p>
                                    <p><strong>Sisa Stock Terakhir:</strong> ${detail.stok_product - detail.jumlah_pembelian}</p>
                                    <p><strong>Jumlah Restock:</strong> ${detail.jumlah_pembelian}</p>
                                    <p><strong>Total Stock:</strong> ${detail.stok_product}</p>
                                    <p><strong>Tax:</strong> ${detail.tax}</p>
                                `;
                            })
                            .catch(error => {
                                console.error('Gagal memuat detail:', error);
                            });
                    });
                });

                // Add event listener for delete buttons
                document.querySelectorAll('.deleteBtn').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');
                        if (confirm('Apakah Anda yakin ingin menghapus restock ini?')) {
                            callAPI({ url: '../api/restock.php', body: { method: 'delete', id: id } })
                                .then(result => {
                                    if (result.success || (result.data && result.data.success)) {
                                        alert('Restock berhasil dihapus!');
                                        fetchPembelian(); // Refresh table
                                    } else {
                                        alert('Gagal menghapus restock: ' + (result.error || 'Unknown error'));
                                    }
                                })
                                .catch(error => {
                                    console.error('Error deleting restock:', error);
                                    alert('Terjadi kesalahan saat menghapus restock');
                                });
                        }
                    });
                });
            } catch (error) {
                console.error('Gagal memuat pembelian:', error);
            }
        }

        fetchPembelian();
        document.getElementById('filter_btn').addEventListener('click', fetchPembelian);

        // closeDetailModalBtn
        const closeDetailModalBtn = document.getElementById('closeDetailModalBtn');
        closeDetailModalBtn.addEventListener('click', () => {
            const detailModal = document.getElementById('DetailModal');
            detailModal.style.display = 'none';
        });

        function updateSelisih() {
            const hargaJual = parseFloat(document.getElementById('harga_jual').value) || 0;
            const hargaBeli = parseFloat(document.getElementById('harga_beli').value) || 0;
            document.getElementById('selisih').value = hargaJual - hargaBeli;
        }

        function updateTotalStock() {
            const sisaStock = parseInt(document.getElementById('sisa_stock').value) || 0;
            const restockQuantity = parseInt(document.getElementById('restock_quantity').value) || 0;
            document.getElementById('total_stock').value = sisaStock + restockQuantity;
        }

        // Event listeners for form inputs
        document.getElementById('harga_jual').addEventListener('input', updateSelisih);
        document.getElementById('harga_beli').addEventListener('input', updateSelisih);
        document.getElementById('restock_quantity').addEventListener('input', updateTotalStock);

        // closeModalBtn
        const closeModalBtn = document.getElementById('closeModalBtn');
        closeModalBtn.addEventListener('click', () => {
            const modal = document.getElementById('RestockModal');
            modal.style.display = 'none';
        });

        // createProductBtn
        const createProductBtn = document.getElementById('createProductBtn');
        createProductBtn.addEventListener('click', () => {
            const modal = document.getElementById('RestockModal');
            modal.style.display = 'flex';
        });

        // saveRestockBtn
        const saveRestockBtn = document.getElementById('saveRestockBtn');
        saveRestockBtn.addEventListener('click', async () => {
            try {
                // Collect form data
                const formData = {
                    tanggal: document.getElementById('tanggal_pembelian').value,
                    supplier_id: document.getElementById('supplier_id').value,
                    product_id: document.getElementById('product_id').value,
                    harga_beli: document.getElementById('harga_beli').value,
                    harga_jual: document.getElementById('harga_jual').value,
                    restock_quantity: document.getElementById('restock_quantity').value,
                    tax: document.getElementById('tax').value || 0
                };

                // Validate required fields
                if (!formData.tanggal || !formData.supplier_id || !formData.product_id || 
                    !formData.harga_beli || !formData.harga_jual || !formData.restock_quantity) {
                    alert('Semua field wajib diisi!');
                    return;
                }

                // Send data to API
                const result = await callAPI({ 
                    url: '../api/restock.php', 
                    body: { method: 'create', ...formData } 
                });
                
                if (result.data.success) {
                    alert('Restock berhasil disimpan!');
                    // Close modal
                    document.getElementById('RestockModal').style.display = 'none';
                    // Reset form
                    document.getElementById('restockForm').reset();
                    // Reset select2 value
                    $('#supplier_id').val(null).trigger('change');
                    $('#product_id').val(null).trigger('change');
                    // Refresh table
                    fetchPembelian();
                    // Refresh product list (stok/harga terbaru)
                    fetchProduct();
                } else {
                    alert('Gagal menyimpan restock: ' + (result.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error saving restock:', error);
                alert('Terjadi kesalahan saat menyimpan restock');
            }
        });

        window.addEventListener('click', function (event) {
            const modal = document.getElementById('RestockModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // keyboard esc
        window.addEventListener('keydown', function (event) {
            const modal = document.getElementById('RestockModal');
            if (event.key === 'Escape') {
                modal.style.display = 'none';
            }
        });
    });
</script>

<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/library/select2.min.js"></script>
<script src="../assets/js/admin/global.js"></script>