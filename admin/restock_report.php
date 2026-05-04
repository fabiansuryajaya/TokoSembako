<div class="page product-page">
    <h1>Report Restock</h1>

    <div class="filter-container">
        <div class="filter">
            <label for="from_date">Dari :</label>
            <input type="date" id="from_date" name="from_date">

            <label for="to_date">Sampai :</label>
            <input type="date" id="to_date" name="to_date">

            <!-- product -->
            <label for="product_id">Nama Barang:</label>
            <select id="product_id" name="product_id" style="width: 200px;" multiple>
                <option value="">Semua</option>
            </select>
        </div>
        <!-- export excel -->
        <div style="margin-left: auto;">
            <button id="export_excel_btn">Export to Excel</button>
            <button id="filter_btn">Filter</button>
        </div>
    </div>

    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Satuan Produk</th>
                <th>Nama Supplier</th>
                <th>Modal Produk</th>
                <th>Tambahan Stock</th>
                <th>Total Modal</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Tambahkan Select2 CSS dan JS -->
<link href="../assets/css/library/select2.min.css" rel="stylesheet" />
<script src="../assets/js/library/select2.min.js"></script>
<!-- XLSX Library for Excel Export -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@latest/dist/xlsx.full.min.js"></script>

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

        async function fetchProduct() {
            try {
                const result = await callAPI({ url: '../api/product.php', body: { method: 'read' } });
                const productSelect = document.getElementById('product_id');
                productSelect.innerHTML = '';
                const optionDefault = document.createElement('option');
                optionDefault.value = '';
                optionDefault.textContent = 'Semua';
                productSelect.appendChild(optionDefault);
                result.data.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id_product;
                    option.textContent = product.nama_product;
                    productSelect.appendChild(option);

                    product_list[product.id_product] = product;
                });

                // Initialize Select2 for product select
                $("#product_id").select2({
                    placeholder: "Pilih Barang",
                    allowClear: true
                });
            } catch (error) {
                console.error('Gagal memuat barang:', error);
            }
        }
        fetchProduct();

        const reportTable = document.querySelector('.product-page tbody');
        let reportData = []; // Store data for export

        async function fetchReport() {
            try {
                const body = {
                    method: 'read',
                    from_date: start_date.value,
                    to_date: to_date.value
                }
                if (document.getElementById('product_id').value)
                    body.product_id = $("#product_id").val().join(",");

                const result = await callAPI({ url: '../api/report_restock.php', body });
                reportData = result.data; // Store for export
                reportTable.innerHTML = '';
                
                // Group by date and purchase ID
                const groupedData = {};
                result.data.forEach(restock => {
                    const dateKey = restock.created_at.split(' ')[0];
                    const pembelianKey = `${dateKey}_${restock.id_pembelian}`;
                    if (!groupedData[pembelianKey]) {
                        groupedData[pembelianKey] = {
                            tanggal: dateKey,
                            id_pembelian: restock.id_pembelian,
                            items: []
                        };
                    }
                    groupedData[pembelianKey].items.push(restock);
                });

                let rowNum = 1;
                Object.values(groupedData).forEach(group => {
                    group.items.forEach((restock, idx) => {
                        const tr = document.createElement('tr');
                        const tanggal = new Date(restock.created_at).toLocaleDateString('id-ID');
                        const displayNum = idx === 0 ? rowNum : '';
                        tr.innerHTML = `
                            <td>${tanggal}</td>
                            <td>${restock.nama_product}</td>
                            <td>${restock.nama_satuan}</td>
                            <td>${restock.nama_supplier}</td>
                            <td>${formatCurrencyIDR(1 * restock.harga_pembelian)}</td>
                            <td>${restock.jumlah_pembelian}</td>
                            <td>${formatCurrencyIDR(1 * restock.total_harga)}</td>
                        `;
                        reportTable.appendChild(tr);
                    });
                    rowNum++;
                });
            } catch (error) {
                console.error('Gagal memuat laporan:', error);
            }
        }

        document.getElementById('filter_btn').addEventListener('click', fetchReport);

        // Export to Excel - Format Buku Pembelian
        document.getElementById('export_excel_btn').addEventListener('click', function() {
            if (reportData.length === 0) {
                alert('Tidak ada data untuk diekspor');
                return;
            }

            // Group data by date and purchase ID
            const groupedData = {};
            reportData.forEach(restock => {
                const dateKey = restock.created_at.split(' ')[0];
                const pembelianKey = `${dateKey}`;
                if (!groupedData[pembelianKey]) {
                    groupedData[pembelianKey] = {
                        tanggal: dateKey,
                        id_pembelian: restock.id_pembelian,
                        items: []
                    };
                }
                groupedData[pembelianKey].items.push(restock);
            });

            // Get date range for header
            let monthYear = '';
            if (reportData.length > 0) {
                const firstDate = new Date(reportData[0].created_at);
                monthYear = firstDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }).toUpperCase();
            }

            // Create HTML table for Excel
            let tableHTML = `
                <table>
                    <tr>
                        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 14px;">BUKU PEMBELIAN (CENTER)</td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 12px;">MASA PAJAK : ${monthYear} (CENTER)</td>
                    </tr>
                    <tr style="height: 10px;"></tr>
                    <tr style="background-color: #CCCCCC; font-weight: bold;">
                        <td style="border: 1px solid black; padding: 5px;">NO</td>
                        <td style="border: 1px solid black; padding: 5px;">TANGGAL FAKTUR PAJAK</td>
                        <td style="border: 1px solid black; padding: 5px;">NO. FAKTUR PAJAK</td>
                        <td style="border: 1px solid black; padding: 5px;">NAMA BARANG</td>
                        <td style="border: 1px solid black; padding: 5px;">KUANTITI</td>
                        <td style="border: 1px solid black; padding: 5px;">HARGA BELI</td>
                        <td style="border: 1px solid black; padding: 5px;">TOTAL PAJAK</td>
                    </tr>
            `;
            

            let rowNum = 1;
            Object.values(groupedData).forEach(group => {
                group.items.forEach((item, idx) => {
                    const tanggal = new Date(item.created_at).toLocaleDateString('id-ID');
                    const displayNum = idx === 0 ? rowNum : '';
                    const noFaktur = idx === 0 ? item.id_pembelian : '';
                    const displayTanggal = idx === 0 ? tanggal : '';
                    
                    
                    tableHTML += `
                        <tr>
                            <td style="border: 1px solid black; padding: 5px; text-align: center;">${displayNum}</td>
                            <td style="border: 1px solid black; padding: 5px;">${displayTanggal}</td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;">${item.nama_product}</td>
                            <td style="border: 1px solid black; padding: 5px; text-align: right;">${item.jumlah_pembelian}</td>
                            <td style="border: 1px solid black; padding: 5px; text-align: right;">Rp. ${parseInt(item.harga_pembelian).toLocaleString('id-ID')}</td>
                            <td style="border: 1px solid black; padding: 5px; text-align: right;">Rp. ${parseInt(item.total_harga).toLocaleString('id-ID')}</td>
                        </tr>
                    `;
                });
                rowNum++;
            });

            tableHTML += '</table>';

            // Convert to xlsx format
            const wb = XLSX.utils.table_to_book(new DOMParser().parseFromString(tableHTML, 'text/html').querySelector('table'));
            XLSX.writeFile(wb, `buku_pembelian_${start_date.value}.xlsx`);
        });
        // Initial fetch
        fetchReport();
    });
</script>