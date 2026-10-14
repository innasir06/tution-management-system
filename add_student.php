<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $contact = $_POST['contact_no']; // This field fixes the "No Contact" issue

    // Securely insert the new student data
    $stmt = $conn->prepare("INSERT INTO students (name, class, contact_no) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $class, $contact);
    
    if ($stmt->execute()) {
        header("Location: students.php?msg=success");
        exit;
    } else {
        $error = "Database Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Student | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .registration-card { max-width: 500px; margin-top: 50px; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .card-header { background-color: #28a745; color: white; text-align: center; padding: 20px; }
        .btn-save { background-color: #28a745; color: white; border: none; }
        .btn-save:hover { background-color: #218838; color: white; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="card registration-card w-100">
        <div class="card-header">
            <h3>Register New Student</h3>
        </div>
        <div class="card-body p-4">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Bilal Ahmed" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Class/Grade</label>
                    <input type="text" name="class" class="form-control" placeholder="e.g. Grade 10" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_no" class="form-control" placeholder="Enter phone number">
                    <small class="text-muted">Fixes the "No Contact" display in the list.</small>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-save py-2">Save Student Record</button>
                    <a href="students.php" class="btn btn-outline-secondary">Back to Student List</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>