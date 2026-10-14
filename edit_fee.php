<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 1. Fetch the record to be edited [cite: 4]
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT f.*, s.name FROM fees f JOIN students s ON f.student_id = s.student_id WHERE f.fee_id = $id";
    $res = $conn->query($sql);
    $data = $res->fetch_assoc();
}

// 2. Process the update when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fee = $_POST['total_fee'];
    $fee_id = $_POST['fee_id'];
    
    $update_sql = "UPDATE fees SET total_fee = '$new_fee' WHERE fee_id = '$fee_id'";
    
    if ($conn->query($update_sql) === TRUE) {
        header("Location: fees.php?msg=Record Updated Successfully");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Fee Amount</title>
    <style>
        body { font-family: Arial; padding: 50px; background: #f4f7f6; }
        .edit-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 450px; margin: auto; }
        input[type="number"] { width: 100%; padding: 12px; margin: 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background: #007bff; color: white; padding: 12px; border: none; width: 100%; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .cancel-btn { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="edit-card">
        <h3>Update Fee: <?php echo htmlspecialchars($data['name']); ?></h3>
        <form method="POST">
            <input type="hidden" name="fee_id" value="<?php echo $data['fee_id']; ?>">
            <label>Current Amount (PKR):</label>
            <input type="number" name="total_fee" value="<?php echo $data['total_fee']; ?>" required>
            <input type="submit" value="Save Changes">
        </form>
        <a href="fees.php" class="cancel-btn">Cancel and Go Back</a>
    </div>
</body>
</html>