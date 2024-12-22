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

try {
    $stmt = $db->prepare("
        SELECT v.comment, v.created_at, u.username, u.profile_picture 
        FROM voting v 
        JOIN users u ON v.user_id = u.id 
        WHERE v.komunitas_id = ? AND v.comment IS NOT NULL 
        ORDER BY v.created_at DESC
    ");
    $stmt->bind_param("i", $komunitas_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = [
            'text' => $row['comment'],
            'author' => $row['username'],
            'avatar' => $row['profile_picture'] ?? '/placeholder.svg?height=40&width=40',
            'created_at' => $row['created_at']
        ];
    }

    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load comments']);
}