<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once 'db.php';

$id = $_GET['id'] ?? 0;
// WP3: Fetch specific student data using Prepared Statements
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) { die("Student not found."); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile | <?= htmlspecialchars($student['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="card shadow mx-auto" style="max-width: 600px; border-radius: 15px;">
            <div class="card-header bg-dark text-white text-center py-3">
                <h4 class="mb-0">Student Information Card</h4>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                        <?= strtoupper(substr($student['name'], 0, 1)) ?>
                    </div>
                </div>
                <table class="table table-borderless">
                    <tr><th width="40%">Student ID:</th><td>#<?= $student['student_id'] ?></td></tr>
                    <tr><th>Full Name:</th><td><strong><?= htmlspecialchars($student['name']) ?></strong></td></tr>
                    <tr><th>Class:</th><td><?= htmlspecialchars($student['class']) ?></td></tr>
                    <tr><th>Contact No:</th><td><?= htmlspecialchars($student['contact_no'] ?? 'No Contact') ?></td></tr>
                </table>
                <hr>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="students.php" class="btn btn-outline-secondary">Back</a>
                    <a href="edit_student.php?id=<?= $id ?>" class="btn btn-primary">Edit Details</a>
                    <a href="delete_student.php?id=<?= $id ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>