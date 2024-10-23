
<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, md5($_POST['password']));
   $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));
   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$password'") or die('query failed');


   if(mysqli_num_rows($select) > 0){
      $message[] = 'User telah terdaftar!';
   }else{
      mysqli_query($conn, "INSERT INTO user_form (name, email, password) VALUES('$name', '$email', '$password')") or die('query failed');
      $message[] = 'Register berhasil!';
      header('location:login.php');

   }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php 
if (isset($message)) {
    foreach ($message as $message) {
        echo '<div class = "message" onclick = "this.remove();">'.$message.'</div>';
    }
}



?>
    <div class="form-container">
        <form action="" method="post">
            <h3>Daftar Registrasi</h3>
            <input type="text" name="name" required placeholder="Masukkan username" class="box">
            <input type="email" name="email" required placeholder="Masukkan email" class="box">
            <input type="password" name="password" required placeholder="Masukkan password" class="box">
            <input type="password" name="confirm_password" required placeholder="Konfirmasi password" class="box">
            <input type="submit" name="submit" class="btn" value="Registrasi!">
            <p>Punya sebuah akun? <a href="login.php">Login sekarang</a></p>
        </form>
    </div>
</body>
</html>
