<?php
session_start();
require_once 'db.php';

// Security: Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch Real-time metrics for the Overview
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$revenue = $conn->query("SELECT SUM(paid_amount) as total FROM students")->fetch_assoc()['total'];

// Mock Today's Attendance for display
$attendance_today = "30%"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>STMS Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background: #212529; color: white; min-height: 100vh; padding-top: 20px; }
        .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 0; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #0d6efd; color: white; }
        .metric-card { border: none; border-radius: 12px; color: white; transition: transform 0.2s; }
        .metric-card:hover { transform: translateY(-5px); }
        .bg-enrollment { background: #0d6efd; }
        .bg-attendance { background: #198754; }
        .alert-section { background: white; border-radius: 8px; border-left: 5px solid #dc3545; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar shadow">
            <h4 class="px-3 mb-4 fw-bold text-white">STMS Admin</h4>
            <div class="nav flex-column">
                <a href="dashboard.php" class="nav-link active"><i class="fas fa-home me-2"></i> Dashboard</a>
                <a href="students.php" class="nav-link"><i class="fas fa-user-graduate me-2"></i> Students</a>
                <a href="attendance.php" class="nav-link"><i class="fas fa-calendar-check me-2"></i> Attendance</a>
                <a href="fees.php" class="nav-link"><i class="fas fa-money-bill-wave me-2"></i> Fees</a>
                <a href="results.php" class="nav-link"><i class="fas fa-file-invoice me-2"></i> Exam Results</a>
                <hr class="mx-3">
                <a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 pt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Institute Overview</h2>
                <div class="badge bg-dark p-2"><?php echo date('l, d M Y'); ?></div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card metric-card bg-enrollment p-4">
                        <h6 class="text-uppercase small opacity-75">Total Enrollment</h6>
                        <h1 class="fw-bold mb-1"><?php echo $total_students; ?></h1>
                        <p class="small mb-0">Active Students</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card metric-card bg-attendance p-4">
                        <h6 class="text-uppercase small opacity-75">Today's Attendance</h6>
                        <h1 class="fw-bold mb-1"><?php echo $attendance_today; ?></h1>
                        <div class="progress bg-white bg-opacity-25" style="height: 5px;">
                            <div class="progress-bar bg-white" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4" style="border-left: 5px solid #ffc107;">
                        <h6 class="text-muted text-uppercase small">Total Revenue</h6>
                        <h1 class="fw-bold mb-1">Rs. <?php echo number_format($revenue ?? 0); ?></h1>
                        <p class="small text-success mb-0">Total Fees Collected</p>
                    </div>
                </div>
            </div>

            <div class="alert-section shadow-sm p-3 mb-4">
                <h6 class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Student Performance Alerts</h6>
                <div class="bg-light p-3 mt-2 rounded text-center text-success small">
                    <i class="fas fa-check-circle me-2"></i> No students currently meet the low-attendance threshold.
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold">Welcome, admin</h5>
                <p class="text-muted small mb-0">Your management system is operational. All student records are synchronized.</p>
            </div>
        </main>
    </div>
</div>

</body>
</html>