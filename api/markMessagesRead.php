<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$receiver_id = $data["receiver_id"] ?? null;
$sender_id = $data["sender_id"] ?? null;

if (!$receiver_id || !$sender_id) {
    echo json_encode([
        "success" => false,
        "message" => "Missing fields"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE private_messages
        SET is_read = 1
        WHERE receiver_id = ? AND sender_id = ? AND is_read = 0
    ");
    $stmt->execute([$receiver_id, $sender_id]);

    echo json_encode([
        "success" => true,
        "message" => "Messages marked as read"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>