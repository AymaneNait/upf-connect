<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$title = $_POST["title"] ?? "";
$subject = $_POST["subject"] ?? "";
$year = $_POST["year"] ?? "";
$type = $_POST["type"] ?? "";
$author = $_POST["author"] ?? "";

if (!$title || !$subject || !$year || !$type || !$author || !isset($_FILES["file"])) {
    echo json_encode(["success" => false, "message" => "Champs manquants"]);
    exit;
}

$file = $_FILES["file"];
$ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

if ($ext !== "pdf") {
    echo json_encode(["success" => false, "message" => "Seuls les PDF sont autorisés"]);
    exit;
}

$newName = time() . "_" . preg_replace("/[^A-Za-z0-9_\.-]/", "_", $file["name"]);
$uploadDir = "../uploads/exams/";
$fullPath = $uploadDir . $newName;
$dbPath = "uploads/exams/" . $newName;

if (!move_uploaded_file($file["tmp_name"], $fullPath)) {
    echo json_encode(["success" => false, "message" => "Échec upload"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO exams (title, subject, year, type, author, file_name, file_path, likes, reported, downloaded)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, 0)
    ");
    $stmt->execute([$title, $subject, $year, $type, $author, $file["name"], $dbPath]);

    echo json_encode(["success" => true, "message" => "Épreuve ajoutée"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>