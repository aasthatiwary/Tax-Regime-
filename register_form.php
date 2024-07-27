<?php
@include 'config.php';

if (isset($_POST['submit'])) {

    // Capture form data and escape special characters to prevent SQL injection
    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contactno = mysqli_real_escape_string($conn, $_POST['phoneno']);
    $pass = md5($_POST['password']); // Hash the password using md5
    $cpass = md5($_POST['cpassword']);
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);

    // Debugging: Check the captured contact number
    error_log("Captured Contact Number: $contactno");

    // Additional Debugging: Print all POST data to the log
    error_log("POST Data: " . print_r($_POST, true));

    // Check if the email already exists in the database
    $select_email = "SELECT * FROM user_form WHERE email = '$email'";
    $result_email = mysqli_query($conn, $select_email);

    if (mysqli_num_rows($result_email) > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($pass != $cpass) {
            $error[] = 'Passwords do not match!';
        } else {
            // Check if the contact number already exists in the database
            $select_contact = "SELECT * FROM user_form WHERE contactno = '$contactno'";
            $result_contact = mysqli_query($conn, $select_contact);

            if (mysqli_num_rows($result_contact) > 0) {
                $error[] = 'Contact number already exists!';
            } else {
                // Insert user data into the database
                $insert = "INSERT INTO user_form (userid, name, email, contactno, password, user_type) VALUES ('$userid','$name','$email','$contactno','$pass','$user_type')";
                if (mysqli_query($conn, $insert)) {
                    header('location: login_form.php'); // Redirect to login page after successful registration
                    exit;
                } else {
                    $error[] = 'Error: ' . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tax Regime System - Register Form</title>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      .main-heading {
         position: absolute;
         top: 20px;
         left: 20px;
         font-family: 'Arial', sans-serif;
         color: yellow;
         background-color: black;
      }

      .form-container {
         background-image: url('https://c8.alamy.com/comp/H676KK/businessman-showing-concept-of-taxes-paid-by-individuals-and-corporations-H676KK.jpg');
         background-size: cover;
         background-position: center;
         padding: 20px;
         border-radius: 10px;
         height: 100vh;
      }

      .password-requirements {
         font-size: 0.9em;
         color: red;
         margin-bottom: 10px;
      }
   </style>
</head>
<body>
   
<div class="form-container">
   <h1 class="main-heading">TAX REGIME SYSTEM</h1>
   <form action="" method="post">
      <h3 style="color : blue;">Register Now</h3>
      <?php
      if (isset($error)) {
         foreach ($error as $error) {
            echo '<span class="error-msg">'.$error.'</span>';
         }
      }
      ?>
      <input type="text" name="userid" required placeholder="Enter your User ID">
      <input type="text" name="name" required placeholder="Enter your Name">
      <input type="email" name="email" required placeholder="Enter your Email">
      <input type="text" name="phoneno" required placeholder="Enter your Contact Number">
      <div class="password-requirements">
         Password must be at least 6 characters long.
      </div>
      <input type="password" name="password" id="password" required placeholder="Enter your Password" minlength="6">
      <input type="password" name="cpassword" id="cpassword" required placeholder="Confirm your Password" minlength="6">
      <select name="user_type">
         <option value="user">User</option>
         <option value="admin">Admin</option>
      </select>
      <input type="submit" name="submit" value="Register Now" class="form-btn">
      <p>Already have an account? <a href="login_form.php">Login Now</a></p>
   </form>
</div>

<script>
   // JavaScript to validate password
   const passwordInput = document.getElementById('password');
   const cpasswordInput = document.getElementById('cpassword');

   passwordInput.addEventListener('input', function () {
      if (passwordInput.value.length < 6) {
         passwordInput.setCustomValidity("Password must be at least 6 characters long.");
      } else {
         passwordInput.setCustomValidity("");
      }
   });

   cpasswordInput.addEventListener('input', function () {
      if (cpasswordInput.value.length < 6) {
         cpasswordInput.setCustomValidity("Password must be at least 6 characters long.");
      } else {
         cpasswordInput.setCustomValidity("");
      }
   });
</script>

</body>
</html>
