<?php
include('../config.php');

// Mengambil data pengguna dari database
$result = mysqli_query($conn, "SELECT * FROM user_form");

// Aktifkan pengguna
if (isset($_GET['activate'])) {
    $id = $_GET['activate'];
    $query = "UPDATE user_form SET status = 'active' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: admin-users.php");
}

// Nonaktifkan pengguna
if (isset($_GET['deactivate'])) {
    $id = $_GET['deactivate'];
    $query = "UPDATE user_form SET status = 'inactive' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: admin-users.php");
}

// Hapus pengguna
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Menghapus pengguna berdasarkan ID
    $query = "DELETE FROM user_form WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: admin-users.php"); // Redirect setelah penghapusan
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4" id="main-content">
            <h1 class="display-4 mb-4">User Management</h1>
            
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr data-id="<?php echo $row['id']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <?php if ($row['status'] == 'active') { ?>
                                <a href="?deactivate=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Deactivate</a>
                            <?php } else { ?>
                                <a href="?activate=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Activate</a>
                            <?php } ?>
                            
                            <!-- Tombol Hapus -->
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
