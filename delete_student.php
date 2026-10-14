<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // WP3: Data Integrity - Delete student record
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: students.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>