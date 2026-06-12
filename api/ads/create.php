<?php
// api/ads/create.php - Endpoint untuk membuat iklan baru

header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../middleware/auth.php';

// Cek autentikasi
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
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    $price = $_POST['price'] ?? 0;
    $contact_phone = $_POST['contact_phone'] ?? '';
    $contact_email = $_POST['contact_email'] ?? '';
    $location = $_POST['location'] ?? '';
    $status = $_POST['status'] ?? 'draft';

    // Validasi input
    if (empty($title) || empty($description)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Title dan description wajib diisi']);
        exit;
    }

    // Handle upload gambar
    $images = [null, null, null];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["image_$i"])) {
            $uploadDir = '../uploads/ads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $file = $_FILES["image_$i"];
            $filename = uniqid() . '_' . basename($file['name']);
            $filepath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $images[$i - 1] = 'uploads/ads/' . $filename;
            }
        }
    }

    // Hitung expires_at (30 hari dari sekarang jika published)
    $expires_at = null;
    if ($status === 'published') {
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));
    }

    // Insert ke database
    $stmt = $conn->prepare("
        INSERT INTO ads (user_id, title, description, category_id, price, 
                        contact_phone, contact_email, location, 
                        image_1, image_2, image_3, status, published_at, expires_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;

    $stmt->bind_param(
        "issiidsssssiss",
        $user_id, $title, $description, $category_id, $price,
        $contact_phone, $contact_email, $location,
        $images[0], $images[1], $images[2], $status, $published_at, $expires_at
    );

    if ($stmt->execute()) {
        $ad_id = $stmt->insert_id;
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Iklan berhasil dibuat',
            'ad_id' => $ad_id
        ]);
    } else {
        throw new Exception($stmt->error);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
