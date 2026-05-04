<?php
require_once("../connection.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['method'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Method tidak disertakan']);
    exit;
}

$method = $data['method'];

switch ($method) {
    case 'read':
        $query_data = $data;
        $action = isset($query_data['action']) ? $query_data['action'] : '';

        // data pembelian detail
        $sql = "SELECT dp.id_detail_pembelian, dp.id_pembelian, dp.id_produk, p.nama_product, s.nama_satuan, sup.nama_supplier, dp.jumlah_pembelian, dp.harga_pembelian, dp.harga_jual, b.created_at
                FROM detail_pembelian dp
                JOIN pembelian b ON dp.id_pembelian = b.id_pembelian
                JOIN product p ON dp.id_produk = p.id_product
                JOIN satuan s ON p.id_satuan = s.id_satuan
                JOIN supplier sup ON p.id_supplier = sup.id_supplier
                WHERE dp.status = 'Y'";

        if (isset($query_data['product_id']) && !empty($query_data['product_id'])) {
            $product_ids = explode(',', $query_data['product_id']);
            $product_ids = array_map('intval', $product_ids);
            $product_ids = array_filter($product_ids, function ($id) { return $id > 0; });
            if (!empty($product_ids)) {
                $sql .= " AND dp.id_produk IN (" . implode(',', $product_ids) . ")";
            }
        }

        if (isset($query_data['from_date']) && isset($query_data['to_date'])) {
            $from_date = $conn->real_escape_string($query_data['from_date']);
            $to_date = $conn->real_escape_string($query_data['to_date']);
            $sql .= " AND DATE(b.created_at) BETWEEN '$from_date' AND '$to_date'";
        }

        $sql .= " ORDER BY b.created_at DESC";

        if ($action === 'detail') {
            // Ambil detail berdasarkan ID detail
            if (isset($query_data['id_detail'])) {
                $id_detail = (int)$query_data['id_detail'];
                $sql = "SELECT dp.*, p.nama_product, s.nama_satuan, sup.nama_supplier, dp.harga_jual, p.stok_product, dp.tax, b.created_at
                        FROM detail_pembelian dp
                        JOIN pembelian b ON dp.id_pembelian = b.id_pembelian
                        JOIN product p ON dp.id_produk = p.id_product
                        JOIN satuan s ON p.id_satuan = s.id_satuan
                        JOIN supplier sup ON b.id_supplier = sup.id_supplier
                        WHERE dp.id_detail_pembelian = $id_detail";
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID detail tidak diberikan']);
                exit;
            }
        }
        $result = $conn->query($sql);
        if (!$result) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mengambil data']);
            exit;
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    case 'create':
        // Data dari form
        $tanggal = isset($data['tanggal']) ? $conn->real_escape_string($data['tanggal']) : date('Y-m-d');
        $supplier_id = (int)$data['supplier_id'];
        $product_id = (int)$data['product_id'];
        $harga_beli = (float)$data['harga_beli'];
        $harga_jual = isset($data['harga_jual']) ? (float)$data['harga_jual'] : 0;
        $restock_quantity = (int)$data['restock_quantity'];
        $tax = isset($data['tax']) ? (float)$data['tax'] : 0;

        // insert pembelian
        $id_user = 1; // Ganti dengan ID user yang sesuai
        $sql = "INSERT INTO pembelian (id_user, id_supplier, jumlah_pembelian, status, created_by, created_at) VALUES ($id_user, $supplier_id, $restock_quantity, 'Y', $id_user, '$tanggal')";
        if ($conn->query($sql) === FALSE) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menambah pembelian']);
            exit;
        }
        $id_pembelian = $conn->insert_id;

        // insert detail
        $sql = "INSERT INTO detail_pembelian (id_pembelian, id_produk, jumlah_pembelian, harga_pembelian, tax, harga_jual, status, created_at) VALUES ($id_pembelian, $product_id, $restock_quantity, $harga_beli, $tax, $harga_jual, 'Y', '$tanggal')";
        if ($conn->query($sql) === FALSE) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menambah detail pembelian']);
            exit;
        }

        // Update stok product
        $sql = "UPDATE product SET stok_product = stok_product + $restock_quantity, harga_beli_product = $harga_beli, harga_jual_product = $harga_jual WHERE id_product = $product_id";
        if ($conn->query($sql) === FALSE) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal update stok product']);
            exit;
        }

        echo json_encode(['success' => true]);
        break;

    case 'delete':
        $id = (int)$data['id'];

        // Get the detail to know quantity and product
        $sql = "SELECT id_produk, jumlah_pembelian FROM detail_pembelian WHERE id_detail_pembelian = $id";
        $result = $conn->query($sql);
        if (!$result || $result->num_rows == 0) {
            http_response_code(404);
            echo json_encode(['error' => 'Detail pembelian tidak ditemukan']);
            exit;
        }
        $detail = $result->fetch_assoc();
        $id_produk = $detail['id_produk'];
        $jumlah = $detail['jumlah_pembelian'];

        // Update stock back
        $sql = "UPDATE product SET stok_product = stok_product - $jumlah WHERE id_product = $id_produk";
        if ($conn->query($sql) === FALSE) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal update stok product']);
            exit;
        }

        // Delete the detail
        $sql = "DELETE FROM detail_pembelian WHERE id_detail_pembelian = $id";
        if ($conn->query($sql) === FALSE) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menghapus detail pembelian']);
            exit;
        }

        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Method tidak valid']);
        break;
}
