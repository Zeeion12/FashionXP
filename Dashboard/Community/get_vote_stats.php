<?php
session_start();
include '../../Authentication/db_connect.php';

header('Content-Type: application/json');

$komunitas_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$komunitas_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

// Get vote counts
$stmt = $db->prepare("SELECT 
    COUNT(CASE WHEN vote_type = 'like' THEN 1 END) as likes,
    COUNT(CASE WHEN vote_type = 'dislike' THEN 1 END) as dislikes
    FROM voting 
    WHERE komunitas_id = ?");
$stmt->bind_param("i", $komunitas_id);
$stmt->execute();
$result = $stmt->get_result();
$counts = $result->fetch_assoc();

// Get user's current vote if logged in
$user_vote = null;
if (isset($_SESSION["user_id"])) {
    $stmt = $db->prepare("SELECT vote_type FROM voting WHERE komunitas_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $komunitas_id, $_SESSION["user_id"]);
    $stmt->execute();
    $vote_result = $stmt->get_result();
    if ($vote = $vote_result->fetch_assoc()) {
        $user_vote = $vote['vote_type'];
    }
}

$total = $counts['likes'] + $counts['dislikes'];
$like_percentage = $total > 0 ? round(($counts['likes'] / $total) * 100) : 0;
$dislike_percentage = $total > 0 ? round(($counts['dislikes'] / $total) * 100) : 0;

echo json_encode([
    'success' => true,
    'likes' => $like_percentage,
    'dislikes' => $dislike_percentage,
    'user_vote' => $user_vote
]);