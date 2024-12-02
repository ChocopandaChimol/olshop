<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, md5($_POST['password']));

   // Cek apakah email dan password sesuai dengan admin
   if ($email == 'admin@gmail.com' && $password == md5('admin123')) {
      $_SESSION['admin_logged_in'] = true;
      header('location: admin/admin-dashboard.php');  
      exit();
   }

   // Cek di database untuk user biasa
   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$password'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);

      // Cek status pengguna (aktif atau tidak)
      if ($row['status'] == 'inactive') {
         $message[] = 'Akun Anda dinonaktifkan. Silakan hubungi admin untuk aktivasi.';
      } else {
         $_SESSION['user_id'] = $row['id'];
         header('location: index.php');  // Redirect ke halaman user biasa
         exit();
      }
   } else {
      $message[] = 'Password dan email salah!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <input type="email" name="email" required placeholder="Masukkan email" class="box">
      <input type="password" name="password" required placeholder="Masukkan password" class="box">
      <input type="submit" name="submit" class="btn" value="Login">
      <p>Belum mempunyai akun? <a href="register.php">Register sekarang</a></p>
   </form>

</div>

</body>
</html>
