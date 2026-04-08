<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$sender_id = $data["sender_id"] ?? null;
$receiver_id = $data["receiver_id"] ?? null;
$message = trim($data["message"] ?? "");

if (!$sender_id || !$receiver_id || !$message) {
    echo json_encode([
        "success" => false,
        "message" => "Missing fields"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO private_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$sender_id, $receiver_id, $message]);

    echo json_encode([
        "success" => true,
        "message" => "Message sent"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>