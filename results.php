<?php
session_start();
require_once 'db.php';

// Simulate SMS Function (Replace with your Twilio/Nexmo API key)
function sendSmsAlert($number, $msg) {
    // In a real app: curl call to SMS gateway
    file_put_contents("sms_log.txt", "To $number: $msg\n", FILE_APPEND);
    return true;
}

if (isset($_POST['submit_result'])) {
    $sid = $_POST['student_id'];
    $marks = $_POST['marks'];
    $total = $_POST['total'];
    $subject = $_POST['subject'];

    $stmt = $conn->prepare("INSERT INTO results (student_id, marks_obtained, total_marks) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $sid, $marks, $total);
    
    if ($stmt->execute()) {
        $grade = getGrade($marks, $total);
        $student = $conn->query("SELECT name, contact_no FROM students WHERE student_id = $sid")->fetch_assoc();
        
        $msg = "Exam Result: " . $student['name'] . " scored $marks/$total ($grade) in $subject.";
        sendSmsAlert($student['contact_no'], $msg);
        $success = "Result saved and SMS sent to " . $student['name'];
    }
}

$results = $conn->query("SELECT r.*, s.name, s.class FROM results r JOIN students s ON r.student_id = s.student_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Exam Results Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 p-4">
                <h2>Result Analysis & Grading</h2>
                <?php if(isset($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
                
                <div class="card p-4 shadow-sm mb-4">
                    <form method="POST" class="row g-3">
                        <div class="col-md-3">
                            <input type="number" name="student_id" class="form-control" placeholder="Student ID" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="subject" class="form-control" placeholder="Subject Name" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="marks" class="form-control" placeholder="Marks" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="total" class="form-control" value="100" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="submit_result" class="btn btn-primary w-100">Add & SMS</button>
                        </div>
                    </form>
                </div>

                <div class="card shadow-sm p-4">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Student</th>
                                <th>Score</th>
                                <th>Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $results->fetch_assoc()): 
                                $grade = getGrade($row['marks_obtained'], $row['total_marks']);
                            ?>
                            <tr>
                                <td><strong><?= $row['name'] ?></strong></td>
                                <td><?= $row['marks_obtained'] ?> / <?= $row['total_marks'] ?></td>
                                <td><span class="badge bg-info text-dark"><?= $grade ?></span></td>
                                <td>
                                    <span class="badge bg-<?= ($grade == 'Fail' ? 'danger' : 'success') ?>">
                                        <?= ($grade == 'Fail' ? 'Action Required' : 'Passed') ?>
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