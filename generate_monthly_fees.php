<?php
session_start();
require_once 'db.php';

// Set the current month and the default fee amount
$current_month = date('F Y'); // e.g., "December 2025"
$default_fee = 5000.00; // Change this to your standard tuition fee

// Fetch all active students
$students = $conn->query("SELECT student_id FROM students");

$new_records = 0;

while($row = $students->fetch_assoc()) {
    $student_id = $row['student_id'];
    
    // Check if a fee record already exists for this student this month
    $check = $conn->query("SELECT fee_id FROM fees WHERE student_id = $student_id AND billing_month = '$current_month'");
    
    if($check->num_rows == 0) {
        // Insert new fee record
        $stmt = $conn->prepare("INSERT INTO fees (student_id, total_fee, billing_month) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $student_id, $default_fee, $current_month);
        $stmt->execute();
        $new_records++;
    }
}

header("Location: fees.php?generated=" . $new_records);
exit();
?>