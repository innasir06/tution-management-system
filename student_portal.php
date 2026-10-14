<?php
session_start();
require_once 'db.php'; 

// Check if student is actually logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$sid = $_SESSION['student_id'];

// Fetch balance and details from students table
$student_res = $conn->query("SELECT * FROM students WHERE student_id = '$sid'");
$student = $student_res->fetch_assoc();

// Attendance logic
$att_query = "SELECT COUNT(*) as total, SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as pres 
              FROM attendance WHERE student_id = '$sid'";
$att_res = $conn->query($att_query);
$att_data = $att_res->fetch_assoc();

$total_days = $att_data['total'] ?? 0;
$present_days = $att_data['pres'] ?? 0;
$attendance_pct = ($total_days > 0) ? ($present_days / $total_days) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .portal-header { background: #0d6efd; color: white; padding: 20px; }
        .stat-card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="bg-light">

<div class="portal-header mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h4 class="m-0"><i class="fas fa-graduation-cap me-2"></i> Welcome, <?php echo htmlspecialchars($_SESSION['student_name'] ?? 'Student'); ?></h4>
        <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card stat-card p-4">
                <h6 class="text-muted small fw-bold">MY ATTENDANCE</h6>
                <h2 class="text-primary"><?php echo number_format($attendance_pct, 1); ?>%</h2>
                <div class="progress mt-2" style="height: 10px;">
                    <div class="progress-bar" style="width: <?php echo $attendance_pct; ?>%"></div>
                </div>
                <p class="mt-2 text-muted mb-0 small">Present: <?php echo $present_days; ?> / <?php echo $total_days; ?> Days</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card stat-card p-4">
                <h6 class="text-muted small fw-bold">OUTSTANDING BALANCE</h6>
                <h2 class="text-danger">$<?php echo number_format($student['balance'] ?? 0, 2); ?></h2>
                <div>
                    <span class="badge bg-light text-dark border">Total Paid: $<?php echo number_format($student['paid_amount'] ?? 0, 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>