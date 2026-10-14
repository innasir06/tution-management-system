<?php
$conn = new mysqli("localhost", "root", "", "tuition_db");
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

function getGrade($marks, $total) {
    if ($total <= 0) return "N/A";
    $p = ($marks / $total) * 100;
    if ($p >= 90) return "A+";
    if ($p >= 80) return "A";
    if ($p >= 70) return "B";
    if ($p >= 60) return "C";
    if ($p >= 50) return "D";
    return "Fail";
}
?>