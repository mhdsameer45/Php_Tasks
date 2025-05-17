<?php
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents("php://input"), true);

    $id = $input['id'] ?? 0;
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';

    if (!$id || empty($name) || empty($email)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID, name, and email are required']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $id]);

    if ($stmt->rowCount()) {
        echo json_encode(['message' => 'User updated successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found or data unchanged']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid method']);
}
?>
