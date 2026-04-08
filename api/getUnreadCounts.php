<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$user_id = $_GET["user_id"] ?? null;

if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "Missing user_id"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT sender_id, COUNT(*) AS unread_count
        FROM private_messages
        WHERE receiver_id = ? AND is_read = 0
        GROUP BY sender_id
    ");
    $stmt->execute([$user_id]);
    $counts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "counts" => $counts
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>