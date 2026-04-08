<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$group_id = $data["group_id"] ?? null;
$user_id = $data["user_id"] ?? null;

if (!$group_id || !$user_id) {
    echo json_encode(["success" => false, "message" => "Champs manquants"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
    $stmt->execute([$group_id, $user_id]);

    echo json_encode(["success" => true, "message" => "Groupe quitté"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>