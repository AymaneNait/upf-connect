<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$user_id = $_GET["user_id"] ?? 0;

try {
    $stmt = $pdo->prepare("
        SELECT 
            g.id,
            g.name,
            g.type,
            g.topic,
            g.created_at,
            COUNT(DISTINCT gm.user_id) AS members_count,
            CASE 
                WHEN MAX(CASE WHEN gm2.user_id IS NOT NULL THEN 1 ELSE 0 END) = 1 THEN 1
                ELSE 0
            END AS joined
        FROM groups g
        LEFT JOIN group_members gm ON g.id = gm.group_id
        LEFT JOIN group_members gm2 ON g.id = gm2.group_id AND gm2.user_id = ?
        GROUP BY g.id, g.name, g.type, g.topic, g.created_at
        ORDER BY g.created_at DESC
    ");
    $stmt->execute([$user_id]);

    echo json_encode([
        "success" => true,
        "groups" => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>