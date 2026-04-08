<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$group_id = $data["group_id"] ?? null;
$user_id = $data["user_id"] ?? null;
$message = trim($data["message"] ?? "");

if (!$group_id || !$user_id || !$message) {
    echo json_encode([
        "success" => false,
        "message" => "Champs manquants"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO group_messages (group_id, user_id, message)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$group_id, $user_id, $message]);

    echo json_encode([
        "success" => true,
        "message" => "Message envoyé"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>