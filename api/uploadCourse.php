<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$title = $_POST["title"] ?? "";
$subject = $_POST["subject"] ?? "";
$teacher = $_POST["teacher"] ?? "";
$semester = $_POST["semester"] ?? "";
$track = $_POST["track"] ?? "";
$level = $_POST["level"] ?? "";
$description = $_POST["description"] ?? "";

if (!$title || !$subject || !$teacher || !$semester || !$track || !$description || !isset($_FILES["file"])) {
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
$uploadDir = "../uploads/courses/";
$fullPath = $uploadDir . $newName;
$dbPath = "uploads/courses/" . $newName;

if (!move_uploaded_file($file["tmp_name"], $fullPath)) {
    echo json_encode(["success" => false, "message" => "Échec de l’upload"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO courses (title, subject, teacher, semester, track, level, description, file_name, file_path, download_count)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)
    ");
    $stmt->execute([
        $title,
        $subject,
        $teacher,
        $semester,
        $track,
        $level,
        $description,
        $file["name"],
        $dbPath
    ]);

    echo json_encode(["success" => true, "message" => "Cours ajouté avec succès"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>