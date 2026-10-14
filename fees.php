<?php
session_start();
require_once 'db.php';

// Handle Simple Payment Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay_now'])) {
    $sid = mysqli_real_escape_string($conn, $_POST['student_id']);
    $amount = floatval($_POST['pay_amount']);

    if ($amount > 0) {
        // Update balance and paid_amount based on your table columns
        $sql = "UPDATE students 
                SET balance = balance - $amount, 
                    paid_amount = paid_amount + $amount 
                WHERE student_id = '$sid'";
        
        if ($conn->query($sql)) {
            $msg = "Payment of $$amount processed successfully!";
        }
    }
}

$query = "SELECT * FROM students";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-dark { background: #212529; color: white; padding: 15px; }
        .table-card { background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-light">

<div class="header-dark mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h4 class="m-0">Financial Dashboard</h4>
        <a href="dashboard.php" class="btn btn-outline-light btn-sm">Back to Analytics</a>
    </div>
</div>

<div class="container">
    <?php if(isset($msg)): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card table-card p-4">
        <h5 class="text-primary fw-bold mb-4">Student Fee Records</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                        <th width="250">Quick Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="fw-bold"><?= $row['name'] ?></td>
                        <td><?= $row['class'] ?></td>
                        <td>$<?= number_format($row['paid_amount'], 2) ?></td>
                        <td class="text-danger fw-bold">$<?= number_format($row['balance'], 2) ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
                                <input type="number" name="pay_amount" class="form-control form-control-sm" placeholder="Amt" required>
                                <button type="submit" name="pay_now" class="btn btn-success btn-sm fw-bold">Pay</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>