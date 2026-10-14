<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once 'db.php';

// Logic to calculate percentages: (P + L) / Total Records
$query = "SELECT s.name, s.class, 
          COUNT(a.attendance_id) as total_days,
          SUM(CASE WHEN a.status = 'Present' OR a.status = 'Late' THEN 1 ELSE 0 END) as days_attended
          FROM students s
          LEFT JOIN attendance a ON s.student_id = a.student_id
          GROUP BY s.student_id";

$report_data = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Attendance Report | STMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { background: #212529; min-vh-100; color: white; padding: 20px; position: fixed; width: 16.6%; }
        .main-content { margin-left: 16.6%; padding: 40px; }
        .progress { height: 10px; border-radius: 5px; }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <div class="sidebar">
                <h4 class="text-center py-3 border-bottom mb-4">Admin Panel</h4>
                <nav class="nav flex-column px-3">
                    <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                    <a href="students.php" class="nav-link text-white">Students</a>
                    <a href="attendance.php" class="nav-link active bg-primary rounded">Attendance</a>
                    <a href="fees.php" class="nav-link text-white">Fees</a>
                    <hr>
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </nav>
            </div>
        </div>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between mb-4">
                <h2>Attendance Analytics</h2>
                <a href="attendance.php" class="btn btn-secondary">Back to Roll Call</a>
            </div>

            <div class="card shadow-sm p-4">
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Days Tracked</th>
                            <th>Attendance %</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $report_data->fetch_assoc()): 
                            $percentage = ($row['total_days'] > 0) ? ($row['days_attended'] / $row['total_days']) * 100 : 0;
                            $color = ($percentage >= 75) ? 'success' : (($percentage >= 50) ? 'warning' : 'danger');
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['class']) ?></td>
                            <td><?= $row['total_days'] ?> Days</td>
                            <td width="30%">
                                <div class="d-flex align-items-center">
                                    <div class="progress w-100 me-2">
                                        <div class="progress-bar bg-<?= $color ?>" style="width: <?= $percentage ?>%"></div>
                                    </div>
                                    <span><?= round($percentage) ?>%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-<?= $color ?>">
                                    <?= ($percentage >= 75) ? 'Excellent' : (($percentage >= 50) ? 'Average' : 'Low Consistency') ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>