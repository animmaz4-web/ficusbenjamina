<?php
// api/ads/comment.php - Endpoint untuk menambah komentar pada iklan

header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../middleware/auth.php';

$user_id = authenticateUser();
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $ad_id = $_POST['ad_id'] ?? null;
    $comment = $_POST['comment'] ?? '';

    if (!$ad_id || empty($comment)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ad ID dan comment wajib diisi']);
        exit;
    }

    // Cek iklan ada
    $ad_check = $conn->prepare("SELECT id FROM ads WHERE id = ?");
    $ad_check->bind_param('i', $ad_id);
    $ad_check->execute();
    if ($ad_check->get_result()->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Iklan tidak ditemukan']);
        exit;
    }

    // Insert komentar
    $stmt = $conn->prepare("INSERT INTO ad_comments (ad_id, user_id, comment, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param('iis', $ad_id, $user_id, $comment);

    if ($stmt->execute()) {
        $comment_id = $stmt->insert_id;
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan dan menunggu persetujuan',
            'comment_id' => $comment_id
        ]);
    } else {
        throw new Exception($stmt->error);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
