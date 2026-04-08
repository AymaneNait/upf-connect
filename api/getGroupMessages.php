<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$group_id = $_GET["group_id"] ?? null;

if (!$group_id) {
    echo json_encode([
        "success" => false,
        "message" => "group_id manquant"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT gm.*, u.full_name
        FROM group_messages gm
        JOIN users u ON gm.user_id = u.id
        WHERE gm.group_id = ?
        ORDER BY gm.created_at ASC
    ");
    $stmt->execute([$group_id]);

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "messages" => $messages
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>