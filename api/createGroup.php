<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"] ?? "";
$type = $data["type"] ?? "public";
$topic = $data["topic"] ?? "";
$user_id = $data["user_id"] ?? 0;

if (!$name || !$user_id) {
  echo json_encode(["success" => false]);
  exit;
}

$stmt = $conn->prepare("INSERT INTO groups (name, type, topic, creator_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $name, $type, $topic, $user_id);

if ($stmt->execute()) {
  echo json_encode([
    "success" => true,
    "group_id" => $conn->insert_id
  ]);
} else {
  echo json_encode(["success" => false, "error" => $conn->error]);
}
?>