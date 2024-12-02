<?php
include('../config.php');

// Mengambil data transaksi dari database berdasarkan id
$result = mysqli_query($conn, "SELECT * FROM transactions ORDER BY id DESC");

// Menghitung total pendapatan dari semua transaksi
$total_revenue_query = mysqli_query($conn, "SELECT SUM(total_price) AS total_revenue FROM transactions");
$total_revenue_result = mysqli_fetch_assoc($total_revenue_query);
$total_revenue = $total_revenue_result['total_revenue'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 bg-dark text-white min-vh-100 p-4" id="sidebar">
            <h2 class="text-center mb-4">Admin Dashboard</h2>
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

        <div class="col-md-9 col-lg-10 p-4" id="main-content">
            <h1 class="display-4 mb-4">Orders Management</h1>

            <!-- Total Revenue -->
            <p class="text-success">Total Revenue: Rp <?php echo number_format($total_revenue, 2, ',', '.'); ?></p>

            <table id="ordersTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo number_format($row['total_price'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
