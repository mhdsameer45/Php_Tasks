<?php
session_start();
require 'dbcon.php';
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <title>Student Edit</title>
</head>

<body>
  <div class="container mt-5">


    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-<?= $_SESSION['msg_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php
      unset($_SESSION['message']);
      unset($_SESSION['msg_type']);
      ?>
    <?php endif; ?>



    <div class="row">
      <div class="col-md-12">
        <div class="card">

          <div class="card-header">
            <h4>
              Student Edit
              <a href="index.php" class="btn btn-danger float-end">BACK</a>
            </h4>
          </div>

          <div class="card-body">
            <form id="editStudentForm">
              <input type="hidden" name="student_id" id="student_id" />

              <div class="mb-3">
                <label>Student Name</label>
                <input type="text" name="name" id="name" class="form-control" />
              </div>
              <div class="mb-3">
                <label>Student Email</label>
                <input type="email" name="email" id="email" class="form-control" />
              </div>
              <div class="mb-3">
                <label>Student Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" />
              </div>
              <div class="mb-3">
                <label>Student Course</label>
                <input type="text" name="course" id="course" class="form-control" />
              </div>
              <div class="mb-3">
                <button type="submit" name="update_student" class="btn btn-primary">
                  Update Student
                </button>
              </div>
            </form>


            <div id="errorMessage" class="alert alert-danger d-none">No Such Id Found</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const urlParams = new URLSearchParams(window.location.search);
      const studentId = urlParams.get("id");


      if (studentId) {
        fetch(`code.php?id=${studentId}`)
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              document.getElementById("student_id").value = data.student.id;
              document.getElementById("name").value = data.student.name;
              document.getElementById("email").value = data.student.email;
              document.getElementById("phone").value = data.student.phone;
              document.getElementById("course").value = data.student.course;
            } else {
              document.getElementById("editStudentForm").style.display = "none";
              const err = document.getElementById("errorMessage");
              err.textContent = data.message || "No Such Id Found";
              err.classList.remove("d-none");
            }
          });
      }


      document.getElementById("editStudentForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = {
          student_id: document.getElementById("student_id").value,
          name: document.getElementById("name").value,
          email: document.getElementById("email").value,
          phone: document.getElementById("phone").value,
          course: document.getElementById("course").value
        };

        fetch("code.php", {
          method: "PUT",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(formData)
        })
          .then(res => res.json())
          .then(data => {

            const oldAlert = document.querySelector('.dynamic-alert');
            if (oldAlert) oldAlert.remove();


            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${data.success ? 'success' : 'danger'} alert-dismissible fade show dynamic-alert`;
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
              ${data.message}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;


            const container = document.querySelector('.container.mt-5');
            container.insertBefore(alertDiv, container.firstChild);

            if (data.success) {
              setTimeout(() => {
                window.location.href = "index.php";
              }, 1500);
            }
          })
          .catch(err => {
            console.error("Update Error:", err);
          });
      });
    });
  </script>
</body>

</html>