<?php
// api/ads/favorite.php - Endpoint untuk menambah/menghapus favorit

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
    $action = $_POST['action'] ?? 'add'; // add atau remove

    if (!$ad_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ad ID required']);
        exit;
    }

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT IGNORE INTO ad_favorites (user_id, ad_id) VALUES (?, ?)");
        $stmt->bind_param('ii', $user_id, $ad_id);
        $stmt->execute();
        $message = 'Iklan ditambahkan ke favorit';
    } else {
        $stmt = $conn->prepare("DELETE FROM ad_favorites WHERE user_id = ? AND ad_id = ?");
        $stmt->bind_param('ii', $user_id, $ad_id);
        $stmt->execute();
        $message = 'Iklan dihapus dari favorit';
    }

    // Cek status favorit
    $check_stmt = $conn->prepare("SELECT id FROM ad_favorites WHERE user_id = ? AND ad_id = ?");
    $check_stmt->bind_param('ii', $user_id, $ad_id);
    $check_stmt->execute();
    $is_favorited = $check_stmt->get_result()->num_rows > 0;

    echo json_encode([
        'success' => true,
        'message' => $message,
        'is_favorited' => $is_favorited
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
