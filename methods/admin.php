<?php
    session_start();

    require_once './dbconnect.php';

    if(!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
        header("Location: ../index.php");
        exit;
    }

    if(isset($_GET['action']) && isset($_GET['id'])) {
        $action = $_GET['action'];
        $id = mysqli_real_escape_string($conn, $_GET['id']);

        // Handle user deletion
        if($action == 'delete_user') {
            $delete_sql = "DELETE FROM users WHERE user_registration_number = '$id' AND user_role = 'user'";
            mysqli_query($conn, $delete_sql);
            header("Location: admin.php");
            exit;
        }

        // Handle claim approval
        if($action == 'approve_claim') {
            $item_id = mysqli_real_escape_string($conn, $_GET['item_id']);
            // 1. Update the claim status to 'approved'
            $approve_claim_sql = "UPDATE claims SET status = 'approved' WHERE claim_id = '$id'";
            mysqli_query($conn, $approve_claim_sql);
            // 2. Update the item status to 'resolved'
            $resolve_item_sql = "UPDATE items SET status = 'resolved' WHERE item_id = '$item_id'";
            mysqli_query($conn, $resolve_item_sql);
            header("Location: admin.php");
            exit;
        }

        // Handle claim rejection
        if($action == 'reject_claim') {
            $reject_sql = "UPDATE claims SET status = 'rejected' WHERE claim_id = '$id'";
            mysqli_query($conn, $reject_sql);
            header("Location: admin.php");
            exit;
        }

        // Handle marking an item as resolved
        if($action == 'resolve_item') {
            $resolve_item_sql = "UPDATE items SET status = 'resolved' WHERE item_id = '$id'";
            mysqli_query($conn, $resolve_item_sql);
            header("Location: admin.php");
            exit;
        }
    }

    $users_sql = "SELECT user_registration_number, user_name, user_role FROM users";
    $users_result = mysqli_query($conn, $users_sql);

    // Fetch all claims and join with items table to get item title
    $claims_sql = "SELECT claims.*, items.title FROM claims JOIN items ON claims.item_id = items.item_id ORDER BY claims.claim_date DESC";
    $claims_result = mysqli_query($conn, $claims_sql);

    // Fetch all items for the Manage Items table
    $all_items_sql = "SELECT * FROM items ORDER BY date_reported DESC";
    $all_items_result = mysqli_query($conn, $all_items_sql);
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
                                    <?php if ($all_items_result && mysqli_num_rows($all_items_result) > 0): ?>
                                        <?php while($item = mysqli_fetch_assoc($all_items_result)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['item_id']); ?></td>
                                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                                <td><?php echo htmlspecialchars($item['category']); ?></td>
                                                <td>
                                                    <?php 
                                                        $type_class = $item['type'] == 'found' ? 'info' : 'warning';
                                                        echo "<span class='badge bg-$type_class'>" . ucfirst($item['type']) . "</span>";
                                                    ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($item['user_registration_number']); ?></td>
                                                <td><?php echo date("Y-m-d", strtotime($item['date_reported'])); ?></td>
                                                <td>
                                                    <?php 
                                                        $status_class = $item['status'] == 'active' ? 'success' : 'secondary';
                                                        echo "<span class='badge bg-$status_class'>" . ucfirst($item['status']) . "</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($item['status'] == 'active'): ?>
                                                        <a href="admin.php?action=resolve_item&id=<?php echo $item['item_id']; ?>" class="btn btn-warning btn-sm">Mark Resolved</a>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No items found.</td>
                                        </tr>
                                    <?php endif; ?>
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
                                    <?php if ($claims_result && mysqli_num_rows($claims_result) > 0): ?>
                                        <?php while($claim = mysqli_fetch_assoc($claims_result)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($claim['claim_id']); ?></td>
                                                <td><?php echo htmlspecialchars($claim['item_id']); ?></td>
                                                <td><?php echo htmlspecialchars($claim['title']); ?></td>
                                                <td><?php echo htmlspecialchars($claim['user_registration_number']); ?></td>
                                                <td><?php echo date("F j, Y, g:i a", strtotime($claim['claim_date'])); ?></td>
                                                <td>
                                                    <?php 
                                                        $status_class = 'secondary';
                                                        if ($claim['status'] == 'approved') $status_class = 'success';
                                                        if ($claim['status'] == 'rejected') $status_class = 'danger';
                                                        if ($claim['status'] == 'pending') $status_class = 'warning';
                                                        echo "<span class='badge bg-$status_class'>" . ucfirst($claim['status']) . "</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($claim['status'] == 'pending'): ?>
                                                        <a href="admin.php?action=approve_claim&id=<?php echo $claim['claim_id']; ?>&item_id=<?php echo $claim['item_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                                        <a href="admin.php?action=reject_claim&id=<?php echo $claim['claim_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No claims found.</td>
                                        </tr>
                                    <?php endif; ?>
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