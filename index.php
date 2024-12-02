<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit(); 
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
    exit(); 
}

$message = []; 

// Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed!');

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'Produk sudah ditambahkan ke keranjang';
    } else {
        mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, image, quantity) VALUES ('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('Query failed');
        $message[] = "Produk ditambahkan ke keranjang";  
    }
}

// Update Cart functionality
if (isset($_POST['update_cart'])) {
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('Query failed!');
    $message[] = 'Update jumlah keranjang berhasil!';
}

// Remove a product from the cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('Query failed!');
    header('location:index.php'); 
    exit(); 
}

// Delete all products from the cart
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Query failed!');
    header('location:index.php'); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .user-profile span {
            color: #00b894;
        }
    </style>
</head>
<body>

<?php if (!empty($message)): ?>
    <?php foreach ($message as $msg): ?>
        <div class="message" onclick="this.remove();"><?= $msg ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="container">

    <div class="user-profile">
        <?php 
        $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('Query failed!');
        if (mysqli_num_rows($select_user) > 0) {
            $fetch_user = mysqli_fetch_assoc($select_user); 
        }
        ?>
        <p style="color : white;"> Username: <span><?= $fetch_user['name'] ?></span></p>
        <p style="color : white;"> Email: <span><?= $fetch_user['email'] ?></span></p>
        <p style="color : white;"> ID Pengguna: <span><?= $fetch_user['id'] ?></span></p> <!-- Menampilkan ID pengguna -->
        <div class="flex">
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="option-btn">Register</a>
            <a href="index.php?logout=<?= $user_id ?>" onclick="return confirm('Apakah kamu yakin untuk logout?');" class="delete-btn">Logout</a>
        </div>
    </div>

    <div class="products">
        <h1 class="heading">Barang terbaru</h1>
        <div class="box-container">
            <?php 
            $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('Query failed!');
            if (mysqli_num_rows($select_product) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                    $image_path = "images/" . $fetch_product['image'];
            ?>
            <form method="post" class="box" action="">
                <div class="product-details">
                    <div class="name"><?= $fetch_product['name'] ?></div>
                    <div class="category"><?= $fetch_product['category'] ?></div>
                    <div class="price">$<?= $fetch_product['price'] ?>/-</div>
                    <div class="description"><?= $fetch_product['description'] ?></div>
                </div>
                <div class="quantity-add">
                    <input type="number" min="1" name="product_quantity" value="1">
                    <input type="hidden" name="product_image" value="<?= $fetch_product['image'] ?>">
                    <input type="hidden" name="product_name" value="<?= $fetch_product['name'] ?>">
                    <input type="hidden" name="product_price" value="<?= $fetch_product['price'] ?>">
                    <input type="submit" value="Tambah ke Keranjang" name="add_to_cart" class="btn">
                </div>
            </form>
            <?php 
                }
            }
            ?>
        </div>
    </div>

    <div class="shopping-cart">
        <h1 class="heading">Keranjang Belanja</h1>
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Kuantitas</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total = 0;

                $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed!');
                if (mysqli_num_rows($cart_query) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                        $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                        $grand_total += $sub_total;
                ?>
                        <tr>
                            <td><img src="images/<?= $fetch_cart['image'] ?>" height="100" alt=""></td>
                            <td><?= $fetch_cart['name'] ?></td>
                            <td>Rp<?= $fetch_cart['price'] ?>/-</td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="cart_id" value="<?= $fetch_cart['id'] ?>">
                                    <input type="number" min="1" name="cart_quantity" value="<?= $fetch_cart['quantity'] ?>">
                                    <input type="submit" name="update_cart" value="Update" class="option-btn">
                                </form>
                            </td>
                            <td>Rp<?= $sub_total ?>/-</td>
                            <td><a href="index.php?remove=<?= $fetch_cart['id'] ?>" onclick="return confirm('Apakah anda yakin ingin menghapus barang ini?');" class="delete-btn">Hapus</a></td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6">Tidak ada barang yang ditambahkan</td></tr>';
                }
                ?>
                <tr class="table-bottom">
                    <td colspan="4">Grand Total :</td>
                    <td>Rp<?= $grand_total ?>/-</td>
                    <td><a href="index.php?delete_all" onclick="return confirm('Apakah anda yakin ingin menghapus semua barang?');" class="delete-btn <?= ($grand_total > 0) ? '' : 'disabled' ?>">Hapus semua</a></td>
                </tr>
            </tbody>
        </table>
        <div class="cart-btn">
            <a href="transaction.php" class="btn <?= ($grand_total > 0) ? '' : 'disabled' ?>">Proses untuk Pembayaran</a>
        </div>
    </div>

</div>

</body>
</html>
