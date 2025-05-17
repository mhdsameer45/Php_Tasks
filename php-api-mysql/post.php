<?php
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    // Check if input is an array (multiple users) or a single user
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Expected an array of users']);
        exit;
    }

    $insertedIds = [];
    $errors = [];

    foreach ($input as $index => $user) {
        $name = $user['name'] ?? '';
        $email = $user['email'] ?? '';

        if (empty($name) || empty($email)) {
            $errors[] = "User at index $index missing name or email";
            continue;
        }

        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        try {
            $stmt->execute([$name, $email]);
            $insertedIds[] = $pdo->lastInsertId();
        } catch (PDOException $e) {
            $errors[] = "Insert failed for user at index $index: " . $e->getMessage();
        }
    }

    $response = ['inserted_ids' => $insertedIds];
    if (!empty($errors)) {
        $response['errors'] = $errors;
    }
    
    echo json_encode($response);

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid method']);
}
?>
