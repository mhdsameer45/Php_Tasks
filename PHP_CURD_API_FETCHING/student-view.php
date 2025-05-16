<?php
// Just load dbcon.php if you want (optional if code.php handles DB)
require 'dbcon.php';
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student View</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>

  <div class="container view-user p-5">
    <div id="studentContent" class="d-none">
      <div class="row mb-5">
        <div class="col text-center">
          <h3 id="studentName"></h3>
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
                <td id="name"></td>
                <td id="email"></td>
                <td id="phone"></td>
                <td id="course"></td>
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
    </div>

    <div id="message" class="text-center text-danger"></div>
  </div>

  <script>
    // Get the student id from URL params
    const params = new URLSearchParams(window.location.search);
    const studentId = params.get('id');

    if (!studentId) {
      document.getElementById('message').textContent = "No student ID provided.";
    } else {
      fetch(`code.php?id=${studentId}`)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not OK');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            
            document.getElementById('studentContent').classList.remove('d-none');
            document.getElementById('message').textContent = '';

           
            document.getElementById('studentName').textContent = data.student.name;
            document.getElementById('name').textContent = data.student.name;
            document.getElementById('email').textContent = data.student.email;
            document.getElementById('phone').textContent = data.student.phone;
            document.getElementById('course').textContent = data.student.course;
          } else {
            document.getElementById('message').textContent = data.message || "No Such Id Found";
          }
        })
        .catch(error => {
          document.getElementById('message').textContent = "Error fetching student data.";
          console.error('Fetch error:', error);
        });
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>