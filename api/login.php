<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data["email"] ?? "");
$password = trim($data["password"] ?? "");

if (!$email || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "Missing fields"
    ]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user["password_hash"])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password"
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Login successful",
    "user" => [
        "id" => $user["id"],
        "full_name" => $user["full_name"],
        "email" => $user["email"],
        "filiere" => $user["filiere"],
        "study_year" => $user["study_year"],
        "bio" => $user["bio"],
        "skills" => $user["skills"],
        "avatar" => $user["avatar"]
    ]
]);