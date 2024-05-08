
<?php

include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
include 'config.php';

session_start();
$email=$_SESSION['email'];

if(isset($_POST['submit'])){

   $otp = mysqli_real_escape_string($conn, $_POST['otp']);

   $select = mysqli_query($conn, "SELECT * FROM `otpverify` WHERE email = '$email'") or die('query failed');
    if(mysqli_num_rows($select) > 0){
    $fetch = mysqli_fetch_assoc($select);
    $ur_otp=$fetch['otp'];
    $ur_time=$fetch['created_at'];
    date_default_timezone_set('Asia/Kolkata');
    $current_time = date("H:i:s");
    echo "$current_time";
    $ur_time = ($ur_time);
    if($ur_time>=$current_time)
    {
        if($otp==$ur_otp)
        {
            header("location: resetpassword.php");
        }
        else{
            $message[] = 'OTP NOT MATCHED'; 
        }
    }
    else
    {
        echo '<script>';
        echo 'alert("Your session has expired");';
        echo '</script>';
    }
    }
    else {
        $message[] = 'NOT ASKED FOR OTP';
    }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>OTP Validation</title>

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Enter OTP</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="text" name="otp" placeholder="enter OTP" class="box" required>
      <input type="submit" name="submit" value="Validate OTP" class="btn">
      <p>Back to Login? <a href="login.php">login now</a></p>
   </form>

</div>

</body>
</html>


