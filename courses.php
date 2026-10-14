<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
require_once 'db.php';
$courses = $conn->query("SELECT course_id, course_name, duration, fee FROM courses");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Courses</title>
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
            <h2>Courses List</h2>
            <table class="table table-bordered mt-3">
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Duration</th>
                    <th>Fee</th>
                </tr>
                <?php while($row = $courses->fetch_assoc()){ ?>
                <tr>
                    <td><?= $row['course_id'] ?></td>
                    <td><?= $row['course_name'] ?></td>
                    <td><?= $row['duration'] ?></td>
                    <td><?= $row['fee'] ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
