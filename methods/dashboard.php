<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();

    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../index.php");
        exit;
    }

    require_once './dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

        // --- A. HANDLE "REPORT LOST ITEM" SUBMISSION ---
        if ($_POST['action'] == 'report_lost') {
            $user_id = $_SESSION['user_id'];
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $location = mysqli_real_escape_string($conn, $_POST['location']);
            $date_lost = mysqli_real_escape_string($conn, $_POST['date_lost']);
            $type = 'lost';
            $status = 'active';

            $sql = "INSERT INTO items (user_registration_number, title, category, description, location, date_reported, type, status) 
                    VALUES ('$user_id', '$title', '$category', '$description', '$location', '$date_lost', '$type', '$status')";
            
            if (mysqli_query($conn, $sql)) {
                $message = "<div class='alert alert-success'>Lost item reported successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        }

        // --- B. HANDLE "REPORT FOUND ITEM" SUBMISSION ---
        elseif ($_POST['action'] == 'report_found') {
            $user_id = $_SESSION['user_id'];
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $location = mysqli_real_escape_string($conn, $_POST['location']);
            $date_found = mysqli_real_escape_string($conn, $_POST['date_found']);
            $type = 'found';
            $status = 'active';

            $sql = "INSERT INTO items (user_registration_number, title, category, description, location, date_reported, type, status) 
                    VALUES ('$user_id', '$title', '$category', '$description', '$location', '$date_found', '$type', '$status')";
            
            if (mysqli_query($conn, $sql)) {
                $message = "<div class='alert alert-success'>Found item reported successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    }

    $user_id = $_SESSION['user_id'];
    $filter_type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';
    $filter_category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
    $search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

    $items_sql = "SELECT * FROM items WHERE status = 'active' AND user_registration_number != $user_id";

    if (!empty($filter_type)) {
    $items_sql .= " AND type = '$filter_type'";
    }
    if (!empty($filter_category)) {
        $items_sql .= " AND category = '$filter_category'";
    }
    if (!empty($search_term)) {
        $items_sql .= " AND (title LIKE '%$search_term%' OR description LIKE '%$search_term%' OR location LIKE '%$search_term%')";
    }

    $items_sql .= " ORDER BY date_reported DESC";

    $items_result = mysqli_query($conn, $items_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Dashboard - UniFind</title>
    <!-- Bootstrap CSS CDN -->
    <link href="../css/bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">UniFind</a>
            <div class="ms-auto d-flex align-items-center">
                <span class="navbar-text me-3">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> !</span>
                <a href="./logout.php" class="btn btn-danger btn-sm rounded-pill px-3">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Dashboard Content -->
    <div class="container py-5">

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="view-items-tab" data-bs-toggle="tab" data-bs-target="#viewItems" type="button" role="tab">View Items</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="report-lost-tab" data-bs-toggle="tab" data-bs-target="#reportLost" type="button" role="tab">Report Lost Item</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="report-found-tab" data-bs-toggle="tab" data-bs-target="#reportFound" type="button" role="tab">Report Found Item</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content pt-4">
            <!-- 1. View Items Tab -->
            <div class="tab-pane fade show active" id="viewItems" role="tabpanel">
                <div class="card">
                    <div class="card-body p-4">
                        <h2 class="card-title h4 mb-4">Community Listings</h2>
                        <!-- Filter Form -->
                        <form class="row g-3 mb-4">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search by keyword..." value="<?php echo htmlspecialchars($search_term); ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select">
                                    <option selected disabled>All Categories</option>
                                    <option value="Mouse">Mouse</option>
                                    <option value="Charger">Charger</option>
                                    <option value="Mobile Phone">Mobile Phone</option>
                                    <option value="Laptop">Laptop</option>
                                    <option value="Earphones">Earphones</option>
                                    <option value="Books & Notes">Books & Notes</option>
                                    <option value="ID Cards">ID Cards</option>
                                </select>
                            </div>
                             <div class="col-md-3">
                                <select name="type" class="form-select">
                                    <option selected disabled>All Types</option>
                                    <option value="lost">Lost</option>
                                    <option value="found">Found</option>
                                </select>
                            </div>
                            <div class="col-3 text-center">
                                <button type="submit" class="btn btn-primary">Filter Results</button>
                            </div>
                        </form>
                        
                        <!-- Items Grid -->
                        <div class="row g-4">
                            <?php if ($items_result && mysqli_num_rows($items_result) > 0) : ?>
                                <?php while ($item = mysqli_fetch_assoc($items_result)) : ?>
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <img src="https://placehold.co/600x400/<?php echo $item['type'] == 'found' ? '0D6EFD' : 'FFC107'; ?>/FFFFFF?text=<?php echo ucfirst($item['type']); ?>+Item" class="card-img-top" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                                <p class="card-text small text-muted">
                                                    <strong>Registration Number:</strong> <?php echo htmlspecialchars($item['user_registration_number']); ?><br>
                                                    <strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?><br>
                                                    <strong>Location:</strong> <?php echo htmlspecialchars($item['location']); ?><br>
                                                    <strong>Date:</strong> <?php echo date("F j, Y", strtotime($item['date_reported'])); ?>
                                                </p>
                                                <?php if ($item['type'] == 'found'): ?>
                                                    <button class="btn btn-success w-100 mt-2">Claim Item</button>
                                                <?php else: ?>
                                                    <div class="alert alert-warning text-center mt-2 p-2">Status: Lost</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <div class="col-12">
                                    <p class="text-center text-muted">No items found matching your criteria.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Report Lost Item Tab -->
            <div class="tab-pane fade" id="reportLost" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body p-4">
                                <h2 class="card-title h4 mb-4">Report a Lost Item</h2>
                                <form action="dashboard.php" method="POST">
                                    <input type="hidden" name="action" value="report_lost">
                                    <div class="mb-3">
                                        <label class="form-label">Item Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category" required>
                                            <option value="" selected disabled>Select a category</option>
                                            <option value="Mouse">Mouse</option>
                                            <option value="Charger">Charger</option>
                                            <option value="Mobile Phone">Mobile Phone</option>
                                            <option value="Laptop">Laptop</option>
                                            <option value="Earphones">Earphones</option>
                                            <option value="Books & Notes">Books & Notes</option>
                                            <option value="ID Cards">ID Cards</option>
                                            <option value="ID Cards">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Last Seen Location</label>
                                        <input type="text" name="location" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Date Lost</label>
                                        <input type="date" name="date_lost" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" rows="4" class="form-control" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Submit Lost Report</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Report Found Item Tab -->
            <div class="tab-pane fade" id="reportFound" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body p-4">
                                <h2 class="card-title h4 mb-4">Report a Found Item</h2>
                                <form action="dashboard.php" method="POST">
                                    <input type="hidden" name="action" value="report_found">
                                    <div class="mb-3">
                                        <label class="form-label">Item Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category" required>
                                            <option value="" selected disabled>Select a category</option>
                                            <option value="Mouse">Mouse</option>
                                            <option value="Charger">Charger</option>
                                            <option value="Mobile Phone">Mobile Phone</option>
                                            <option value="Laptop">Laptop</option>
                                            <option value="Earphones">Earphones</option>
                                            <option value="Books & Notes">Books & Notes</option>
                                            <option value="ID Cards">ID Cards</option>
                                            <option value="ID Cards">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Location Found</label>
                                        <input type="text" name="location" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Date Found</label>
                                        <input type="date" name="date_found" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" rows="4" class="form-control" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Submit Found Report</button>
                                </form>
                            </div>
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