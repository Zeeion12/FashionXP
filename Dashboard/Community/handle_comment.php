<?php
session_start();
include '../../Authentication/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please login to comment']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$komunitas_id = isset($data['komunitas_id']) ? (int)$data['komunitas_id'] : 0;
$comment = isset($data['comment']) ? trim($data['comment']) : '';

if (!$komunitas_id || empty($comment)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

try {
    // Insert comment
    $stmt = $db->prepare("UPDATE voting SET comment = ? WHERE komunitas_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $comment, $komunitas_id, $_SESSION["user_id"]);
    
    if (!$stmt->execute()) {
        // If no existing record to update, insert new one
        $stmt = $db->prepare("INSERT INTO voting (komunitas_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $komunitas_id, $_SESSION["user_id"], $comment);
        $stmt->execute();
    }

    // Fetch user details for the response
    $stmt = $db->prepare("SELECT u.username, u.profile_picture FROM users u WHERE u.id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    echo json_encode([
        'success' => true,
        'comment' => [
            'text' => $comment,
            'author' => $user['username'],
            'avatar' => $user['profile_picture'] ?? '/placeholder.svg?height=40&width=40',
            'created_at' => date('Y-m-d H:i:s')
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save comment']);
}