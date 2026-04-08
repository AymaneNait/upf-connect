<?php
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "upf-connect");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB error"]);
    exit;
}

$feed = [];

/* =======================
   🔹 Latest COURSE
======================= */
$courseQuery = "SELECT title, track, semester, download_count, created_at 
                FROM courses 
                ORDER BY created_at DESC 
                LIMIT 1";

$courseRes = $conn->query($courseQuery);

if ($courseRes && $courseRes->num_rows > 0) {
    $c = $courseRes->fetch_assoc();

    $feed[] = [
        "type" => "course",
        "title" => $c["title"],
        "track" => $c["track"],
        "semester" => $c["semester"],
        "downloads" => $c["download_count"],
        "time" => $c["created_at"]
    ];
}

/* =======================
   🔹 Latest EXAM
======================= */
$examQuery = "SELECT title, subject, year, type, likes, download_count, created_at 
              FROM exams 
              ORDER BY created_at DESC 
              LIMIT 1";

$examRes = $conn->query($examQuery);

if ($examRes && $examRes->num_rows > 0) {
    $e = $examRes->fetch_assoc();

    $feed[] = [
        "type" => "exam",
        "title" => $e["title"],
        "subject" => $e["subject"],
        "year" => $e["year"],
        "exam_type" => $e["type"],
        "likes" => $e["likes"],
        "downloads" => $e["download_count"],
        "time" => $e["created_at"]
    ];
}

/* =======================
   🔹 Latest GROUP
======================= */
$groupQuery = "SELECT name, type, created_at 
               FROM groups 
               ORDER BY created_at DESC 
               LIMIT 1";

$groupRes = $conn->query($groupQuery);

if ($groupRes && $groupRes->num_rows > 0) {
    $g = $groupRes->fetch_assoc();

    $feed[] = [
        "type" => "group",
        "name" => $g["name"],
        "group_type" => $g["type"],
        "time" => $g["created_at"]
    ];
}

/* =======================
   🔥 Final response
======================= */
echo json_encode([
    "success" => true,
    "feed" => $feed
]);