<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
require_once 'db.php';

// WP4: Handling Form Submission Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['attendance_date'];
    
    // Loop through the status array sent from the form
    foreach ($_POST['status'] as $student_id => $status) {
        // WP3: In-depth Analysis - Preventing duplicate entries for the same day
        $check = $conn->query("SELECT * FROM attendance WHERE student_id = '$student_id' AND date = '$date'");
        
        if($check->num_rows > 0) {
            $conn->query("UPDATE attendance SET status = '$status' WHERE student_id = '$student_id' AND date = '$date'");
        } else {
            $conn->query("INSERT INTO attendance (student_id, date, status) VALUES ('$student_id', '$date', '$status')");
        }
    }
    header("Location: attendance.php"); // Redirect back to History
    exit;
}

// Fetch all registered students to display in the marking sheet
$students = $conn->query("SELECT student_id, name, class FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Daily Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Daily Attendance Sheet</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3 col-md-4">
                    <label class="form-label fw-bold">Select Date:</label>
                    <input type="date" name="attendance_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>

                <table class="table table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($s = $students->fetch_assoc()){ ?>
                        <tr>
                            <td><?= htmlspecialchars($s['name']) ?></td>
                            <td><?= htmlspecialchars($s['class']) ?></td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status[<?= $s['student_id'] ?>]" value="Present" checked>
                                    <label class="form-check-label">Present</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status[<?= $s['student_id'] ?>]" value="Absent">
                                    <label class="form-check-label text-danger">Absent</label>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success px-5">Save Attendance</button>
                    <a href="attendance.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>