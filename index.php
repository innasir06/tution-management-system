<?php
session_start();
require_once 'db.php'; 

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs to prevent SQL injection
    $user_input = mysqli_real_escape_string($conn, $_POST['user_id']);
    $password_input = mysqli_real_escape_string($conn, $_POST['password']);

    // 1. Check Admin Table (users)
    $admin_query = "SELECT * FROM users WHERE username='$user_input' AND password='$password_input' AND role='admin'";
    $admin_result = $conn->query($admin_query);

    if ($admin_result && $admin_result->num_rows > 0) {
        $admin_data = $admin_result->fetch_assoc();
        $_SESSION['user_id'] = $admin_data['username'];
        $_SESSION['role'] = 'admin';
        header("Location: dashboard.php");
        exit;
    }

    // 2. Check Student Table
    // MATCHING YOUR DB: We check against the 'student_id' column
    $student_query = "SELECT * FROM students WHERE student_id='$user_input' AND password='$password_input'";
    $student_result = $conn->query($student_query);

    if ($student_result && $student_result->num_rows > 0) {
        $student_data = $student_result->fetch_assoc();
        
        // Save session data to fix the "Undefined array key" error
        $_SESSION['student_id'] = $student_data['student_id'];
        $_SESSION['student_name'] = $student_data['name']; 
        $_SESSION['role'] = 'student';
        
        header("Location: student_portal.php");
        exit;
    } else {
        // Displays the red error box from your screenshot
        $error = "Invalid ID or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Tuition Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Restores your blue background and centered card */
        body { background-color: #0d6efd; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .login-card { background: white; padding: 45px; border-radius: 20px; width: 420px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
    </style>
</head>
<body>
    <div class="login-card text-center">
        <h1 class="fw-bold mb-4">Login</h1>
        
        <?php if($error): ?>
            <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="text-start mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">User ID / Student ID</label>
                <input type="text" name="user_id" class="form-control form-control-lg bg-light border-0" placeholder="Enter ID" required>
            </div>
            <div class="text-start mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="••••••" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Sign In</button>
        </form>
        <p class="mt-4 small text-muted">Need help? <a href="#" class="text-decoration-none">Contact Admin</a></p>
    </div>
</body>
</html>