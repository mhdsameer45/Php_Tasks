<?php
require 'dbcon.php';
header('Content-Type: application/json');


$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// DELETE Student by ID (via JSON)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str($_SERVER['QUERY_STRING'], $params);
    if (isset($params['id'])) {
        $student_id = mysqli_real_escape_string($con, $params['id']);

        $query = "DELETE FROM students WHERE id='$student_id'";
        $query_run = mysqli_query($con, $query);

        echo json_encode([
            'success' => $query_run ? true : false,
            'message' => $query_run ? 'Student Deleted Successfully' : 'Student Not Deleted'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID is required for deletion']);
    }
    exit;
}

// UPDATE Student
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (
        isset($data['student_id'], $data['name'], $data['email'], $data['phone'], $data['course'])
    ) {
        $student_id = mysqli_real_escape_string($con, $data['student_id']);
        $name = mysqli_real_escape_string($con, $data['name']);
        $email = mysqli_real_escape_string($con, $data['email']);
        $phone = mysqli_real_escape_string($con, $data['phone']);
        $course = mysqli_real_escape_string($con, $data['course']);

        $query = "UPDATE students SET name='$name', email='$email', phone='$phone', course='$course' WHERE id='$student_id'";
        $query_run = mysqli_query($con, $query);

        echo json_encode([
            'success' => $query_run ? true : false,
            'message' => $query_run ? 'Student Updated Successfully' : 'Student Not Updated'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing fields for update']);
    }
    exit;
}

// CREATE Student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($data['name'], $data['email'], $data['phone'], $data['course'])
    ) {
        $name = mysqli_real_escape_string($con, $data['name']);
        $email = mysqli_real_escape_string($con, $data['email']);
        $phone = mysqli_real_escape_string($con, $data['phone']);
        $course = mysqli_real_escape_string($con, $data['course']);

        $query = "INSERT INTO students (name, email, phone, course) VALUES ('$name', '$email', '$phone', '$course')";
        $query_run = mysqli_query($con, $query);

        echo json_encode([
            'success' => $query_run ? true : false,
            'message' => $query_run ? 'Student Created Successfully' : 'Student Not Created'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing fields for creation']);
    }
    exit;
}

// FETCH single student
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $student_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM students WHERE id='$student_id' LIMIT 1";
    $query_run = mysqli_query($con, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $student = mysqli_fetch_assoc($query_run);
        echo json_encode(['success' => true, 'student' => $student]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No Such Id Found']);
    }
    exit;
}

// FETCH all students
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['all']) && $_GET['all'] == 'true') {
    $query = "SELECT * FROM students";
    $query_run = mysqli_query($con, $query);

    $students = [];
    while ($row = mysqli_fetch_assoc($query_run)) {
        $students[] = $row;
    }

    echo json_encode(['success' => true, 'students' => $students]);
    exit;
}

// Fallback
echo json_encode(['success' => false, 'message' => 'Invalid request']);
