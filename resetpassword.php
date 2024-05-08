
<?php
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
include 'config.php';

if(isset($_POST['pwdrst'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password=$_POST['password'];
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   
   if($pass != $cpass){
    $message[] = 'confirm password not matched!';
 }else{
    $sql="UPDATE `user_form` SET password='$pass' WHERE email='$email' ";

    $insert=mysqli_query($conn, $sql);

    if($insert){
       header('location:login.php');
       echo '<script>';
      echo 'alert("Password updated successfully");';
      echo '</script>';
    }else{
       $message[] = 'registeration failed!';
    }
 }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Recover Password</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="email" name="email" placeholder="enter email" class="box" required  data-parsley-type="email" data-parsley-trigger="keyup">
      <input type="password" name="password" placeholder="enter new password" class="box" required>
      <input type="password" name="cpassword" placeholder="confirm new password" class="box" required>
      <input type="submit" id="login" name="pwdrst" value="Change Password" class="btn btn-success" />
      
       </div>
       
       <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
      
   </form>
</div>
</body>
</html>