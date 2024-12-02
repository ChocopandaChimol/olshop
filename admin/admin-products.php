<?php
include('../config.php');

// Definisikan target directory untuk gambar
$target_dir = "uploads/";

// Menangani form penambahan produk
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Path folder uploads
    $target_file = $target_dir . basename($image);

    // Cek apakah file yang diupload adalah gambar
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        // Memindahkan file yang diupload ke folder uploads
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "The file " . htmlspecialchars(basename($image)) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
    }

    // Query untuk menambahkan produk ke database
    $query = "INSERT INTO products (name, price, image, description, category) 
              VALUES ('$name', '$price', '$image', '$description', '$category')";
    mysqli_query($conn, $query);
}

// Mengambil data produk dari database
$result = mysqli_query($conn, "SELECT * FROM products");

// Menghapus produk
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query_delete = "DELETE FROM products WHERE id = $id";
    mysqli_query($conn, $query_delete);
    header("Location: admin-products.php");
}

// Mengedit produk
if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Jika ada gambar baru yang diupload
    if ($image) {
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $query = "UPDATE products SET name = '$name', price = '$price', image = '$image', description = '$description', category = '$category' WHERE id = $id";
    } else {
        // Jika tidak ada gambar baru, gunakan gambar lama
        $query = "UPDATE products SET name = '$name', price = '$price', description = '$description', category = '$category' WHERE id = $id";
    }

    // Eksekusi query update
    if (mysqli_query($conn, $query)) {
        header("Location: admin-products.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            <h1 class="display-4 mb-4">Product Management</h1>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add New Product</button>

            <table id="productsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr data-id="<?php echo $row['id']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td class="product-name"><?php echo $row['name']; ?></td>
                        <td class="product-price"><?php echo $row['price']; ?></td>
                        <td><img src="uploads/<?php echo $row['image']; ?>" width="100" alt="Product Image" class="product-image"></td>
                        <td class="product-description"><?php echo $row['description']; ?></td>
                        <td class="product-category"><?php echo $row['category']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="editProduct(<?php echo $row['id']; ?>)">Edit</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Add Product Modal -->
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="admin-products.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="category" name="category" required>
                                </div>
                                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Product Modal -->
            <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="admin-products.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="editProductId">
                                <div class="mb-3">
                                    <label for="editName" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editPrice" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="editPrice" name="price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editImage" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="editImage" name="image">
                                </div>
                                <div class="mb-3">
                                    <label for="editDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="editDescription" name="description" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editCategory" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="editCategory" name="category" required>
                                </div>
                                <button type="submit" name="edit_product" class="btn btn-warning">Update Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this product?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a id="deleteLink" href="#" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script>
    // Fungsi untuk mengedit produk
    function editProduct(id) {
        var row = document.querySelector(`tr[data-id='${id}']`);
        document.getElementById('editProductId').value = id;
        document.getElementById('editName').value = row.querySelector('.product-name').textContent;
        document.getElementById('editPrice').value = row.querySelector('.product-price').textContent;
        document.getElementById('editDescription').value = row.querySelector('.product-description').textContent;
        document.getElementById('editCategory').value = row.querySelector('.product-category').textContent;
    }

    // Fungsi untuk mengonfirmasi penghapusan produk
    function confirmDelete(id) {
        document.getElementById('deleteLink').href = "admin-products.php?delete=" + id;
    }

    // Inisialisasi DataTable
    $(document).ready(function() {
        $('#productsTable').DataTable();
    });
</script>
</body>
</html>
