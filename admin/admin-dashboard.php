<?php
include('../config.php');

// Query untuk statistik pengguna
$result_users = mysqli_query($conn, "SELECT COUNT(*) as total_users FROM user_form");

// Query untuk statistik produk
$result_products = mysqli_query($conn, "SELECT COUNT(*) as total_products FROM products");

// Query untuk total transaksi, dengan asumsi menambahkan kolom total_price di transactions
$result_transactions = mysqli_query($conn, "SELECT SUM(total_price) as total_transactions FROM transactions");

// Periksa apakah query berhasil
if (!$result_users || !$result_products || !$result_transactions) {
    die('Query failed: ' . mysqli_error($conn));
}

// Mengambil hasil statistik dari query
$stats_users = mysqli_fetch_assoc($result_users);
$stats_products = mysqli_fetch_assoc($result_products);
$stats_transactions = mysqli_fetch_assoc($result_transactions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Link to Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<div class="d-flex" id="wrapper">
    <div class="bg-dark text-white" id="sidebar" style="width: 250px; height: 100vh;">
        <div class="sidebar-header text-center py-4">
            <h2>Admin Dashboard</h2>
        </div>
        <div class="list-group list-group-flush">
            <a href="admin-dashboard.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
            <a href="admin-products.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="bi bi-box me-2"></i> Products
            </a>
            <a href="admin-users.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="bi bi-person-lines-fill me-2"></i> Users
            </a>
            <a href="admin-orders.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="bi bi-cart-check me-2"></i> Orders
            </a>
            <a href="logout.php" class="list-group-item list-group-item-action bg-dark text-white mt-3">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div id="page-content-wrapper" class="w-100">
        <div class="container-fluid">
            <div class="container my-5">
                <h1 class="display-4">Welcome to Admin Dashboard</h1>

                <!-- Statistics Section -->
                <div class="row my-4">
                    <!-- Total Users Card -->
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <i class="bi bi-person-circle fs-3 me-3"></i>
                                <div>
                                    <h5 class="card-title">Total Users</h5>
                                    <p class="card-text fs-3"><?php echo $stats_users['total_users']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Products Card -->
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <i class="bi bi-box fs-3 me-3"></i>
                                <div>
                                    <h5 class="card-title">Total Products</h5>
                                    <p class="card-text fs-3"><?php echo $stats_products['total_products']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Transactions Card -->
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <i class="bi bi-wallet fs-3 me-3"></i>
                                <div>
                                    <h5 class="card-title">Total Transactions</h5>
                                    <p class="card-text fs-3">$<?php echo number_format($stats_transactions['total_transactions'], 2); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Include these files for Bootstrap functionality) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybKA3sEQUe6o8k+mR9P1bziR3z2yYhXeeA3b/5kT6pF5GojeG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-0evHe/X7DpJdWk9Hwr7jRSa1-8zFJ1eIqMm3VoM7ntz7mt11l3kFm7A+Lq6HY8Mv" crossorigin="anonymous"></script>

</body>
</html>
