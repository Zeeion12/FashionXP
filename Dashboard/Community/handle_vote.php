<?php
session_start();
include '../../Authentication/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please login to vote']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$komunitas_id = isset($data['komunitas_id']) ? (int)$data['komunitas_id'] : 0;
$vote_type = isset($data['vote_type']) ? $data['vote_type'] : '';

if (!$komunitas_id || !in_array($vote_type, ['like', 'dislike'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

try {
    // Start transaction
    $db->begin_transaction();

    // Check if user has already voted
    $stmt = $db->prepare("SELECT id, vote_type FROM voting WHERE komunitas_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $komunitas_id, $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_vote = $result->fetch_assoc();

    if ($existing_vote) {
        // Update existing vote if different
        if ($existing_vote['vote_type'] !== $vote_type) {
            $stmt = $db->prepare("UPDATE voting SET vote_type = ? WHERE id = ?");
            $stmt->bind_param("si", $vote_type, $existing_vote['id']);
            $stmt->execute();
        }
    } else {
        // Insert new vote
        $stmt = $db->prepare("INSERT INTO voting (komunitas_id, user_id, vote_type) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $komunitas_id, $_SESSION["user_id"], $vote_type);
        $stmt->execute();
    }

    // Get updated vote counts
    $stmt = $db->prepare("SELECT 
        COUNT(CASE WHEN vote_type = 'like' THEN 1 END) as likes,
        COUNT(CASE WHEN vote_type = 'dislike' THEN 1 END) as dislikes
        FROM voting 
        WHERE komunitas_id = ?");
    $stmt->bind_param("i", $komunitas_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $counts = $result->fetch_assoc();

    $db->commit();

    $total = $counts['likes'] + $counts['dislikes'];
    $like_percentage = $total > 0 ? round(($counts['likes'] / $total) * 100) : 0;
    $dislike_percentage = $total > 0 ? round(($counts['dislikes'] / $total) * 100) : 0;

    echo json_encode([
        'success' => true,
        'likes' => $like_percentage,
        'dislikes' => $dislike_percentage,
        'user_vote' => $vote_type
    ]);

} catch (Exception $e) {
    $db->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to process vote']);
}