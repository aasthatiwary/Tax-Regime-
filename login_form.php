<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $user_type = $_POST['user_type'];

   $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $row = mysqli_fetch_array($result);

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_name'] = $row['name'];
         header('location:admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_name'] = $row['name'];
         header('location:user_page.php');

      }
     
   }else{
      $error[] = 'incorrect email or password!';
   }

};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title style ="color=blue;">Login Form</title>

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* Additional CSS */
      .main-heading {
         position: absolute;
         top: 20px;
         left: 20px;
         font-family: 'Arial', sans-serif; /* Change the font family */
         color: yellow; /* Change the color to yellow */
         z-index: 1; /* Ensure the heading stays above the background image */
         background-color: black;
      }

      .form-container {
         background-image: url('https://www.shutterstock.com/image-photo/financial-researchgovernment-taxes-calculation-tax-260nw-2072821730.jpg'); /* Add the URL of your background image */
         background-size: cover;
         background-position: center;
         padding: 20px;
         border-radius: 10px;
         height: 100vh; /* Set the height to fill the viewport */
      }
   </style>
</head>
<body>
   
<div class="form-container">
   <h1 class="main-heading">TAX REGIME SYSTEM</h1> <!-- New heading added here -->
   <form action="" method="post">
      <h3 style ="color=blue;">Login Now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="email" name="email" required placeholder="Enter your email">
      <input type="password" name="password" required placeholder="Enter your password">
      <input type="submit" name="submit" value="Login Now" class="form-btn">
      <p>Don't have an account? <a href="register_form.php">Register Now</a></p>
   </form>
</div>

</body>
</html>
