<?php
require 'dbcon.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student View</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav aria-label="breadcrumb" class="mt-3">
        <ol class="breadcrumb container">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Student</li>
        </ol>
    </nav>

    <div class="container view-user p-5">
        <?php
        if (isset($_GET['id'])) {
            $student_id = mysqli_real_escape_string($con, $_GET['id']);
            $query = "SELECT * FROM students WHERE id='$student_id'";
            $query_run = mysqli_query($con, $query);

            if (mysqli_num_rows($query_run) > 0) {
                $student = mysqli_fetch_array($query_run);
                ?>

                <div class="row mb-5">
                    <div class="col text-center">
                        <h3><?= $student['name']; ?></h3>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Course</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $student['name']; ?></td>
                                    <td><?= $student['email']; ?></td>
                                    <td><?= $student['phone']; ?></td>
                                    <td><?= $student['course']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <a href="index.php" class="btn btn-danger">Back</a>
                    </div>
                </div>

                <?php
            } else {
                echo "<h4>No Such Id Found</h4>";
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>