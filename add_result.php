<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $marks = $_POST['marks'];
    $total = $_POST['total'];

    $sql = "INSERT INTO results (student_id, course_id, marks_obtained, total_marks) 
            VALUES ('$student_id', '$course_id', '$marks', '$total')";
    
    if ($conn->query($sql)) {
        header("Location: results.php");
    }
}

$students = $conn->query("SELECT student_id, name FROM students");
$courses = $conn->query("SELECT course_id, course_name FROM courses");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container bg-white p-4 shadow rounded" style="max-width: 500px;">
        <h3>Enter Exam Marks</h3>
        <form method="POST">
            <label>Student:</label>
            <select name="student_id" class="form-control mb-3">
                <?php while($s = $students->fetch_assoc()) echo "<option value='".$s['student_id']."'>".$s['name']."</option>"; ?>
            </select>
            
            <label>Course:</label>
            <select name="course_id" class="form-control mb-3">
                <?php while($c = $courses->fetch_assoc()) echo "<option value='".$c['course_id']."'>".$c['course_name']."</option>"; ?>
            </select>
            
            <label>Marks Obtained:</label>
            <input type="number" name="marks" class="form-control mb-3" required>
            
            <label>Total Marks:</label>
            <input type="number" name="total" class="form-control mb-3" value="100" required>
            
            <button type="submit" class="btn btn-success w-100">Save Result</button>
        </form>
    </div>
</body>
</html>