<?php
header("Content-Type: application/json");
require_once "../config/db.php";

try {
    $stmt = $pdo->query("SELECT id, full_name, email, filiere, study_year, bio, skills, avatar, created_at FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "users_type" => gettype($users),
        "first_user_type" => isset($users[0]) ? gettype($users[0]) : "none",
        "users" => $users
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>