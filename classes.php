<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
require 'config/db.php';
$classes = $conn->query("SELECT c.class_id, co.course_name, t.name AS teacher_name, c.class_time, c.room
                         FROM classes c
                         JOIN courses co ON c.course_id = co.course_id
                         JOIN teachers t ON c.teacher_id = t.teacher_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Classes</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-dark text-white min-vh-100">
            <h4 class="text-center py-3">Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="index.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link text-white">Users</a></li>
                <li class="nav-item"><a href="students.php" class="nav-link text-white">Students</a></li>
                <li class="nav-item"><a href="teachers.php" class="nav-link text-white">Teachers</a></li>
                <li class="nav-item"><a href="courses.php" class="nav-link text-white">Courses</a></li>
                <li class="nav-item"><a href="classes.php" class="nav-link text-white">Classes</a></li>
                <li class="nav-item"><a href="enrollments.php" class="nav-link text-white">Enrollments</a></li>
                <li class="nav-item"><a href="attendance.php" class="nav-link text-white">Attendance</a></li>
                <li class="nav-item"><a href="fees.php" class="nav-link text-white">Fees</a></li>
                <li class="nav-item"><a href="payments.php" class="nav-link text-white">Payments</a></li>
                <li class="nav-item"><a href="exams.php" class="nav-link text-white">Exams</a></li>
                <li class="nav-item"><a href="results.php" class="nav-link text-white">Results</a></li>
                <li class="nav-item"><a href="notifications.php" class="nav-link text-white">Notifications</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link text-white">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 mt-4">
            <h2>Classes List</h2>
            <table class="table table-bordered mt-3">
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Teacher</th>
                    <th>Class Time</th>
                    <th>Room</th>
                </tr>
                <?php while($row = $classes->fetch_assoc()){ ?>
                <tr>
                    <td><?= $row['class_id'] ?></td>
                    <td><?= $row['course_name'] ?></td>
                    <td><?= $row['teacher_name'] ?></td>
                    <td><?= $row['class_time'] ?></td>
                    <td><?= $row['room'] ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
