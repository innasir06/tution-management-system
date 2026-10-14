<?php
// 1. FORCE ERROR DISPLAY - THIS STOPS THE BLANK PAGE
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. CHECK DB CONNECTION
if (!file_exists('db.php')) {
    die("CRITICAL ERROR: db.php file is missing from your folder!");
}
require_once 'db.php';

// 3. VERIFY SESSION
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    die("ACCESS DENIED: You are not logged in as a teacher. <a href='index.php'>Go to Login</a>");
}

$username = $_SESSION['user_id'];

// 4. FETCH TEACHER DATA
// We use a try-catch block to catch database-specific crashes
try {
    $sql = "SELECT t.* FROM teachers t 
            JOIN users u ON t.user_id = u.id 
            WHERE u.username = '$username' AND t.status = 'active'";
    
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Database Query Failed: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
    } else {
        die("DATA ERROR: No 'active' profile found for user: <b>$username</b>. Check teachers table!");
    }
} catch (Exception $e) {
    die("PHP CRASHED: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Welcome, <?= htmlspecialchars($teacher['name']) ?></h4>
            </div>
            <div class="card-body">
                <p><strong>Staff ID:</strong> <?= htmlspecialchars($teacher['teacher_id']) ?></p>
                <p><strong>Subject:</strong> <?= htmlspecialchars($teacher['subject']) ?></p>
                <hr>
                <a href="attendance.php" class="btn btn-primary">Mark Attendance</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>