<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "Missing user id"
    ]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, full_name, email, filiere, study_year FROM users WHERE id = ?");
$stmt->execute([$id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode([
        "success" => true,
        "user" => $user
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
}
?>