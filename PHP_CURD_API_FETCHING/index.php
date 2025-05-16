<?php
session_start();
require 'dbcon.php';
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <title>Student CRUD</title>
</head>

<body>
    <div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row mb-3">
            <div class="col-6">
                <h1>Students Details</h1>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="student-create.php" class="btn btn-primary align-self-center">+ add user</a>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Course</th>
                    <th scope="col" class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM students";
                $query_run = mysqli_query($con, $query);

                if (mysqli_num_rows($query_run) > 0):
                    foreach ($query_run as $student):
                        ?>
                        <tr>
                            <th scope="row"><?= $student['id']; ?></th>
                            <td><?= htmlspecialchars($student['name']); ?></td>
                            <td><?= htmlspecialchars($student['email']); ?></td>
                            <td><?= htmlspecialchars($student['phone']); ?></td>
                            <td><?= htmlspecialchars($student['course']); ?></td>
                            <td class="text-end">
                                <a href="student-view.php?id=<?= $student['id']; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="student-edit.php?id=<?= $student['id']; ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button onclick="deleteStudent(<?= $student['id']; ?>)" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-person-x"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                else:
                    echo "<tr>
                            <td colspan='6' class='text-center'>No Record Found</td>
                        </tr>";
                endif;
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function deleteStudent(id) {
            if (confirm("Are you sure you want to delete this student?")) {
                fetch(`code.php?id=${id}`, {
                    method: "DELETE",
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            location.reload(); // Refresh the list
                        }
                    })
                    .catch(error => {
                        console.error("Error deleting student:", error);
                        alert("Something went wrong. Please try again.");
                    });
            }
        }
    </script>
</body>