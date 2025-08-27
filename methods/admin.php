<?php
    session_start();

    require_once './dbconnect.php';

    if(!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
        header("Location: ../index.php");
        exit;
    }

    if(isset($_GET['action']) && $_GET['action'] == 'delete_user' && isset($_GET['id'])) {
        $user_id_to_delete = mysqli_real_escape_string($conn, $_GET['id']);
        $delete_sql = "DELETE FROM users WHERE user_registration_number = '$user_id_to_delete' AND user_role = 'user'";
        mysqli_query($conn, $delete_sql);
        header("Location: admin.php");
        exit;
    }

    $users_sql = "SELECT user_registration_number, user_name, user_role FROM users";
    $users_result = mysqli_query($conn, $users_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UniFind</title>
    <!-- Bootstrap CSS CDN -->
    <link href="../css/bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">UniFind - Admin Panel</a>
            <div class="ms-auto">
                <span class="navbar-text me-3">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> !</span>
                <a href="./logout.php" class="btn btn-danger btn-sm rounded-pill px-3">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Dashboard Content -->
    <div class="container py-5">

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="manage-items-tab" data-bs-toggle="tab" data-bs-target="#manageItems" type="button" role="tab">Manage Items</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="manage-claims-tab" data-bs-toggle="tab" data-bs-target="#manageClaims" type="button" role="tab">Manage Claims</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="manage-users-tab" data-bs-toggle="tab" data-bs-target="#manageUsers" type="button" role="tab">Manage Users</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content pt-4">
            
            <!-- 1. Manage Items Tab -->
            <div class="tab-pane fade show active" id="manageItems" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title h4 mb-4">All Reported Items</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Item ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Reported By (User ID)</th>
                                        <th>Date Reported</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Row 1 -->
                                    <tr>
                                        <td>101</td>
                                        <td>iPhone 14 Pro</td>
                                        <td>Electronics</td>
                                        <td><span class="badge bg-info">Found</span></td>
                                        <td>12345678</td>
                                        <td>2025-08-26</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td><button class="btn btn-warning btn-sm">Mark Resolved</button></td>
                                    </tr>
                                    <!-- Sample Row 2 -->
                                    <tr>
                                        <td>102</td>
                                        <td>Black Leather Wallet</td>
                                        <td>Accessories</td>
                                        <td><span class="badge bg-warning">Lost</span></td>
                                        <td>87654321</td>
                                        <td>2025-08-25</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td><button class="btn btn-warning btn-sm">Mark Resolved</button></td>
                                    </tr>
                                    <!-- Add more sample rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Manage Claims Tab -->
            <div class="tab-pane fade" id="manageClaims" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title h4 mb-4">User Claims</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Claim ID</th>
                                        <th>Item ID</th>
                                        <th>Item Title</th>
                                        <th>Claimed By (User ID)</th>
                                        <th>Date Claimed</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Row 1 -->
                                    <tr>
                                        <td>501</td>
                                        <td>101</td>
                                        <td>iPhone 14 Pro</td>
                                        <td>87654321</td>
                                        <td>2025-08-26</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-success btn-sm">Approve</button>
                                            <button class="btn btn-danger btn-sm">Reject</button>
                                        </td>
                                    </tr>
                                    <!-- Sample Row 2 -->
                                    <tr>
                                        <td>502</td>
                                        <td>98</td>
                                        <td>University ID Card</td>
                                        <td>11223344</td>
                                        <td>2025-08-24</td>
                                        <td><span class="badge bg-success">Approved</span></td>
                                        <td>-</td>
                                    </tr>
                                    <!-- Add more sample rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Manage Users Tab -->
            <div class="tab-pane fade" id="manageUsers" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title h4 mb-4">All Users</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Registration Number</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($users_result && mysqli_num_rows($users_result) > 0): ?>
                                        <?php while($user = mysqli_fetch_assoc($users_result)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['user_registration_number']); ?></td>
                                                <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['user_role']); ?></td>
                                                <td>
                                                    <?php if ($user['user_role'] == 'user'): ?>
                                                        <a href="admin.php?action=delete_user&id=<?php echo $user['user_registration_number']; ?>" 
                                                           class="btn btn-danger btn-sm" 
                                                           onclick="return confirm('Are you sure you want to delete this user?');">Delete User</a>
                                                    <?php else: ?>
                                                        <h6> - </h6>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No users found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="../js/bootstrap5.min.js"></script>

</body>
</html>