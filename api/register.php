<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}
header("Content-Type: application/json");
require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$full_name = trim($data["full_name"] ?? "");
$email = trim($data["email"] ?? "");
$password = trim($data["password"] ?? "");
$filiere = trim($data["filiere"] ?? "");
$study_year = trim($data["study_year"] ?? "");

if (!$full_name || !$email || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "Missing fields"
    ]);
    exit;
}

$check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$check->execute([$email]);

if ($check->fetch()) {
    echo json_encode([
        "success" => false,
        "message" => "Email already exists"
    ]);
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO users (full_name, email, password_hash, filiere, study_year)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([$full_name, $email, $password_hash, $filiere, $study_year]);

echo json_encode([
    "success" => true,
    "message" => "User created successfully"
]);
?>