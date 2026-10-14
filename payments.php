<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
require 'config/db.php';
$payments = $conn->query("SELECT p.payment_id, s.name AS student_name, co.course_name, p.amount_paid, p.payment_date, p.payment_method
                          FROM payments p
                          JOIN fees f ON p.fee_id = f.fee_id
                          JOIN students s ON f.student_id = s.student_id
                          JOIN courses co ON f.course_id = co.course_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payments</title>
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
            <h2>Payments List</h2>
            <table class="table table-bordered mt-3">
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Amount Paid</th>
                    <th>Payment Date</th>
                    <th>Method</th>
                </tr>
                <?php while($row = $payments->fetch_assoc()){ ?>
                <tr>
                    <td><?= $row['payment_id'] ?></td>
                    <td><?= $row['student_name'] ?></td>
                    <td><?= $row['course_name'] ?></td>
                    <td><?= $row['amount_paid'] ?></td>
                    <td><?= $row['payment_date'] ?></td>
                    <td><?= $row['payment_method'] ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
