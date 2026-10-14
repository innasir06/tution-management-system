<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once 'db.php';

$id = $_GET['id'] ?? 0;

// Fetch existing data for the student
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) { die("Student record not found."); }

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $contact = $_POST['contact_no'];

    $update_stmt = $conn->prepare("UPDATE students SET name=?, class=?, contact_no=? WHERE student_id=?");
    $update_stmt->bind_param("sssi", $name, $class, $contact, $id);
    
    if ($update_stmt->execute()) {
        header("Location: students.php?msg=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Student | STMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="card shadow mx-auto" style="max-width: 500px;">
            <div class="card-header bg-primary text-white"><h4>Edit Student Details</h4></div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Class</label>
                        <input type="text" name="class" class="form-control" value="<?= htmlspecialchars($student['class']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Contact Number</label>
                        <input type="text" name="contact_no" class="form-control" value="<?= htmlspecialchars($student['contact_no'] ?? '') ?>" placeholder="Enter contact number">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Update Student Information</button>
                    <a href="students.php" class="btn btn-link w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>