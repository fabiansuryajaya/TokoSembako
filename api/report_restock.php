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
        $product_id = isset($data['product_id']) ? explode(',', $data['product_id']) : [];
        $product_id = array_map('intval', $product_id);
        $product_id = array_filter($product_id, function ($id) { return $id > 0; });
        $from_date  = isset($data['from_date'])  ? $conn->real_escape_string($data['from_date']) : ''; // YYYY-MM-DD
        $to_date    = isset($data['to_date'])    ? $conn->real_escape_string($data['to_date'])   : ''; // YYYY-MM-DD
        
        // data restock - detail per pembelian
        $sql = "SELECT p.id_pembelian, p.created_at, pr.nama_product, dp.jumlah_pembelian, dp.harga_pembelian, 
                (dp.harga_pembelian * dp.jumlah_pembelian) as total_harga, s.nama_satuan, su.nama_supplier
                FROM pembelian p 
                JOIN detail_pembelian dp ON p.id_pembelian = dp.id_pembelian
                JOIN product pr ON dp.id_produk = pr.id_product
                JOIN satuan s ON pr.id_satuan = s.id_satuan
                JOIN supplier su ON p.id_supplier = su.id_supplier
                WHERE dp.status = 'Y'";
        if (!empty($product_id)) $sql .= " AND dp.id_produk IN (" . implode(',', $product_id) . ")";
        if (!empty($from_date))  $sql .= " AND DATE_FORMAT(p.created_at, '%Y-%m-%d') >= '$from_date'"; // YYYY-MM-DD
        if (!empty($to_date))    $sql .= " AND DATE_FORMAT(p.created_at, '%Y-%m-%d') <= '$to_date'"; // YYYY-MM-DD
        $sql .= " ORDER BY p.created_at ASC, p.id_pembelian ASC, pr.nama_product ASC";

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

    case 'delete':
        $sql = 'DELETE FROM pembelian';
        $result = $conn->query($sql);
        if (!$result) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menghapus data']);
            exit;
        }
        $sql = 'DELETE FROM detail_pembelian';
        $result = $conn->query($sql);
        if (!$result) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menghapus data']);
            exit;
        }
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Method tidak valid']);
        break;
}
?>