<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$user1 = $_GET["user1"] ?? null;
$user2 = $_GET["user2"] ?? null;

if (!$user1 || !$user2) {
    echo json_encode([
        "success" => false,
        "message" => "Missing users"
    ]);
    exit;
}

try {
   $stmt = $pdo->prepare("
    SELECT pm.*, u.full_name AS sender_name
    FROM private_messages pm
    JOIN users u ON pm.sender_id = u.id
    WHERE (pm.sender_id = ? AND pm.receiver_id = ?)
       OR (pm.sender_id = ? AND pm.receiver_id = ?)
    ORDER BY pm.created_at ASC
");
    $stmt->execute([$user1, $user2, $user2, $user1]);

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "messages" => $messages
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>