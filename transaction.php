<?php
include 'config.php';
session_start();

// Pastikan user_id ada dalam sesi
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Pengecekan dan penambahan kolom user_id jika belum ada
$check_column_query = "SHOW COLUMNS FROM transactions LIKE 'user_id'";
$result = mysqli_query($conn, $check_column_query);

if (mysqli_num_rows($result) == 0) {
    // Kolom tidak ada, jadi kita tambahkan
    $add_column_query = "ALTER TABLE transactions ADD COLUMN user_id INT NOT NULL";
    if (!mysqli_query($conn, $add_column_query)) {
        die('Error adding column: ' . mysqli_error($conn));
    }
}

// Ambil data pengguna berdasarkan user_id
$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('Query gagal!');
if (mysqli_num_rows($select_user) > 0) {
    $fetch_user = mysqli_fetch_assoc($select_user); 
    $name = $fetch_user['name'];
    $email = $fetch_user['email'];
} else {
    $name = '';
    $email = '';
}

// Hitung total harga dari cart
$total_price = 0;
$select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
while ($cart_item = mysqli_fetch_assoc($select_cart)) {
    $total_price += $cart_item['price'] * $cart_item['quantity']; // Asumsi ada kolom price dan quantity
}

// Proses ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = $_POST['address'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Masukkan data transaksi ke tabel transactions
    $sql = "INSERT INTO transactions (user_id, name, email, address, card_number, expiry_date, cvv, total_price) 
            VALUES ('$user_id', '$name', '$email', '$address', '$card_number', '$expiry_date', '$cvv', '$total_price')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Transaksi berhasil!');</script>";
    } else {
        echo "<script>alert('Transaksi gagal: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Transaksi</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Form Transaksi</h2>
        <div class="user-info">
            <p><strong>Nama:</strong> <?= $name ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
        </div>
        <form method="POST" action="">
            <div class="form-group">
                <label for="address">Alamat</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="card_number">Nomor Kartu</label>
                <input type="text" id="card_number" name="card_number" required>
            </div>
            <div class="form-group">
                <label for="expiry_date">Tanggal Kadaluarsa</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YYYY" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" required>
            </div>
            <div class="form-group">
                <label for="total_price">Total Harga</label>
                <input type="text" id="total_price" name="total_price" value="<?= number_format($total_price, 2) ?>" readonly>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
