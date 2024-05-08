
<?php
session_start();
// if(empty($_SESSION['user_id']))
// {
//    die('connection failed');
// }

// function to generate OTP:
function generateOTP() {
   $otp = "";
   $digits = 6; // Number of digits in the OTP

   for ($i = 0; $i < $digits; $i++) {
       $otp .= random_int(0, 9); // Append a random digit (0-9)
   }

   return $otp;
}
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if(isset($_POST['pwdrst'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $otp = generateOTP();
   // $otp_value=$_POST['otp'];
   // if($otp===$otp_value)
   // {
   //    header('location: verifypasswordotp.php');
   // }
   $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email = '$email'");

   $emailcount=mysqli_num_rows($select);
   if($emailcount>0)
   {
      $email = $_POST['email'];
      $subject = 'You have successfully logged In: Here is your credentials';
      $message1 = "<html>
      <head>
        <title>Email Verification</title>
      </head>
      <body>
        <p>You have asked for re-password generation</p>
        <p>Your registered email is: $email</p>
        <p>Your <strong>One-Time-Password is: $otp</strong> </p>
     <br>
     <p>If you did not request a password reset, no further action is required.</p>
      </body>
      </html>";

    $errors = [];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'please enter a valid email address';
    }
    if ($subject == '') {
        $errors[] = 'please enter a subject';
    }
    if ($message1 == '') {
        $errors[] = "please enter a message";
    }

    if (empty($errors)) {

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = "true";
            $mail->Username = "adhaaditya77@gmail.com";
            $mail->Password = "rudvvixclycjpmnh";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->isHTML(true);
            $mail->setFrom('adhaaditya77@gmail.com');
            $mail->addAddress($_POST["email"]);
            $mail->addReplyTo($email);
            $mail->Subject = $subject;
            $mail->Body = $message1;
            $mail->send();
            $sent = true;
            $_SESSION['email']=$email;
            $select = mysqli_query($conn, "SELECT * FROM `otpverify` WHERE email = '$email'") or die('query failed');

            if(mysqli_num_rows($select) > 0){
            
               $sql="UPDATE `otpverify` SET otp='$otp', created_at=DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email='$email'";
               $result=mysqli_query($conn, $sql);
            }
            else{

               $sql="INSERT INTO `otpverify`(user_id, OTP, email, created_at) VALUES((SELECT user_id FROM user_form WHERE email='$email'), '$otp','$email',DATE_ADD(NOW(), INTERVAL 15 MINUTE))";

               $result=mysqli_query($conn, $sql);
               
            }  
            header("location: otpverify.php");
            echo '<script>';
            echo 'alert("Email is sent to your registered account!");';
            echo '</script>';
        } catch (Exception $e) {
            $errors[] = $mail->ErrorInfo;
        }
    }
    else
    {
      $message[] = 'incorrect email or password!';
    }
   }
   else
   {
      echo '<script>';
echo 'alert("No Registered account linked to this Email!");';
echo '</script>';
   }
}
  

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Forgot password</title>
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
      
      <input type="submit" id="login" name="pwdrst" value="Generate OTP" class="btn btn-success" />
      
       </div>
       
       <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
      
   </form>
</div>
</body>
</html>