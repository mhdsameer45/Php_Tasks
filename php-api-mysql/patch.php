<?php
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $input = json_decode(file_get_contents("php://input"), true);

    $id = $input['id'] ?? 0;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID is required']);
        exit;
    }

    // Build query dynamically for partial update
    $fields = [];
    $values = [];

    if (isset($input['name'])) {
        $fields[] = "name = ?";
        $values[] = $input['name'];
    }
    if (isset($input['email'])) {
        $fields[] = "email = ?";
        $values[] = $input['email'];
    }

    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(['error' => 'No fields to update']);
        exit;
    }

    $values[] = $id;

    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);

    if ($stmt->rowCount()) {
        echo json_encode(['message' => 'User partially updated']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found or no changes made']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid method']);
}
?>
