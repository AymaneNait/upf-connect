<?php
header("Content-Type: application/json");
require_once "../config/db.php";

try {
    $stmt = $pdo->query("SELECT * FROM exams ORDER BY id DESC");
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "exams" => $exams
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>