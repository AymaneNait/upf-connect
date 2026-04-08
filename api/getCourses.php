<?php
header("Content-Type: application/json");
require_once "../config/db.php";

try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "courses" => $courses
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching courses"
    ]);
}
?>