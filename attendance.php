<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once 'db.php';

// --- SMS HELPER FUNCTION ---
function sendSmsAlert($number, $msg) {
    // In a real production environment, you would use Twilio, Nexmo, or a local SMS Gateway API here.
    // For now, it logs the SMS to a file so you can verify it works.
    $log = "[" . date('Y-m-d H:i:s') . "] SMS to $number: $msg" . PHP_EOL;
    file_put_contents("sms_log.txt", $log, FILE_APPEND);
    return true;
}

// --- HANDLE ATTENDANCE SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attendance_status'])) {
    $current_date = date('Y-m-d');
    
    foreach ($_POST['attendance_status'] as $student_id => $status) {
        $student_id = intval($student_id);
        
        // 1. Check if attendance already exists for this student today
        $check_stmt = $conn->prepare("SELECT attendance_id FROM attendance WHERE student_id = ? AND date = ?");
        $check_stmt->bind_param("is", $student_id, $current_date);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing record
            $update_stmt = $conn->prepare("UPDATE attendance SET status = ? WHERE student_id = ? AND date = ?");
            $update_stmt->bind_param("sis", $status, $student_id, $current_date);
            $update_stmt->execute();
        } else {
            // Insert new record
            $insert_stmt = $conn->prepare("INSERT INTO attendance (student_id, status, date) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("iss", $student_id, $status, $current_date);
            $insert_stmt->execute();
        }

        // 2. TRIGGER SMS ALERT IF ABSENT
        if ($status == 'absent') {
            $st_query = $conn->query("SELECT name, contact_no FROM students WHERE student_id = $student_id");
            if ($st_data = $st_query->fetch_assoc()) {
                $phone = $st_data['contact_no'];
                $name = $st_data['name'];
                $message = "Attendance Alert: Your child $name was marked ABSENT today ($current_date).";
                
                sendSmsAlert($phone, $message);
            }
        }
    }
    $success = "Attendance saved and alerts sent to absent students!";
}

// Fetch students to display in the list
$students = $conn->query("SELECT * FROM students ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Attendance | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { background: #212529; min-vh-100; color: white; padding: 20px; position: fixed; width: 250px; }
        .main-content { margin-left: 250px; padding: 40px; }
        .nav-link { color: #adb5bd; margin-bottom: 10px; }
        .nav-link.active { background: #0d6efd; color: white; border-radius: 5px; }
    </style>
</head>
<body class="bg-light">

<div class="sidebar">
    <h4 class="text-center mb-4">Admin Panel</h4>
    <nav class="nav flex-column">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="students.php" class="nav-link">Students</a>
        <a href="attendance.php" class="nav-link active">Attendance</a>
        <a href="fees.php" class="nav-link">Fees</a>
        <a href="results.php" class="nav-link">Exam Results</a>
        <hr>
        <a href="logout.php" class="nav-link text-danger">Logout</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daily Roll Call <span class="badge bg-secondary fs-6"><?= date('D, d M Y') ?></span></h2>
    </div>

    <?php if(isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Student Name</th>
                            <th>Class</th>
                            <th class="text-center">Status (P / A)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4"><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['class']) ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="attendance_status[<?= $row['student_id'] ?>]" id="p_<?= $row['student_id'] ?>" value="present" checked>
                                    <label class="btn btn-outline-success px-4" for="p_<?= $row['student_id'] ?>">P</label>

                                    <input type="radio" class="btn-check" name="attendance_status[<?= $row['student_id'] ?>]" id="a_<?= $row['student_id'] ?>" value="absent">
                                    <label class="btn btn-outline-danger px-4" for="a_<?= $row['student_id'] ?>">A</label>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-end py-3">
                <button type="submit" class="btn btn-primary btn-lg px-5">Save & Send Alerts</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>