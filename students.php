<?php
session_start();
// Security: Redirect to login if not authenticated
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
require_once 'db.php';

// WP3: Search logic to filter the student list
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM students WHERE name LIKE '%$search%' ORDER BY student_id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { background: #212529; min-vh-100; color: white; padding: 20px; position: fixed; width: 16.6%; }
        .nav-link { color: #adb5bd; padding: 12px; transition: 0.3s; }
        .nav-link:hover, .active-link { color: white; background: #343a40; border-radius: 5px; }
        .active-link { background: #0d6efd !important; color: white !important; }
        .main-content { margin-left: 16.6%; padding: 30px; }
        .table-container { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <div class="sidebar">
                <h4 class="text-center py-3 border-bottom mb-4">Admin Panel</h4>
                <nav class="nav flex-column px-3">
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                    <a href="students.php" class="nav-link active-link">Students</a>
                    <a href="attendance.php" class="nav-link">Attendance</a>
                    <a href="fees.php" class="nav-link">Fees</a>
                    <a href="results.php" class="nav-link">Exam Results</a>
                    <hr class="bg-secondary">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </nav>
            </div>
        </div>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Student Management</h2>
                <div class="d-flex gap-2">
                    <form class="d-flex" method="GET">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search students..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                    <a href="add_student.php" class="btn btn-success">+ Add New Student</a>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Contact No</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['student_id'] ?></td>
                                <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                                <td><?= htmlspecialchars($row['class']) ?></td>
                                <td>
                                    <?php 
                                        if (!empty($row['contact_no'])) {
                                            echo htmlspecialchars($row['contact_no']);
                                        } else {
                                            echo '<span class="text-muted small">No Contact</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="edit_student.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-info text-white">Edit</a>
                                        <a href="view_profile.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-secondary">View Profile</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">No students found matching your criteria.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>