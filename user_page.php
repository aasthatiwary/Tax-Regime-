<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}

// Fetch user details from the database based on the user's session
$user_name = $_SESSION['user_name'];
$select_user_details = $conn->prepare("SELECT userid, name, email, contactno FROM user_form WHERE name = ?");
$select_user_details->bind_param("s", $user_name);
$select_user_details->execute();
$result_user_details = $select_user_details->get_result();
$user_details = $result_user_details->fetch_assoc();

// Function to capitalize the first letter and convert the rest to lowercase
function capitalizeName($name) {
    return ucfirst(strtolower($name));
}

// Check if "Generate PDF" button is clicked
if (isset($_GET['generate_pdf'])) {
    // Redirect user to the PDF file URL
    header('Location: generate_pdf.php');
    exit; // Ensure that no further code is executed after redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Page</title>
   <link rel="stylesheet" href="css/style.css">
   <style>
      body {
         font-family: Arial, sans-serif;
         background-color: #f4f4f9;
         margin: 0;
         padding: 0;
         position: relative; /* Added for positioning of navigation bars */
      }

      .container {
         max-width: 1500px;
         margin: 20px auto;
         padding: 20px;
         background-image: url('https://www.shutterstock.com/image-photo/busines-using-computer-complete-individual-260nw-2018230646.jpg');
         background-size: cover;
         box-shadow: 0 0 10px rgba(0,0,0,0.1);
         border-radius: 10px;
         position: relative;
         display: flex;
         flex-direction: column;
         align-items: center;
      }

      .heading {
         text-align: center;
         font-size: 20px;
         font-weight: bold;
         margin-top: 1px;
         margin-bottom: 0.0000000000001px;
         color: #333;
         background-color: yellow; /* Yellow background color for the heading */
         width: 100%; /* Makes the heading span across the entire container */
         padding: 10px;
         border-radius: 5px;
      }

      .content {
         display: flex;
         justify-content: space-between;
         width: 100%;
      }

      .welcome {
         position: absolute;
         top: 5px;
         left: 10px;
         font-size: 10px;
         color: white; /* Change welcome statement color to green */
         background-color: black;
      }

      .details {
         margin-top: 80px; /* Increase margin to accommodate welcome message */
         padding: 20px;
         border: 2px solid #ddd;
         border-radius: 5px;
         background-color: skyblue;
         width: 30%; /* Set width for the box */
      }

      .details h2 {
         margin-top: 0; /* Remove top margin */
         color: green; /* Change record details heading color to green */
         background-color: black;
      }

      .details p {
         font-size: 16px;
         color: #555;
         margin: 10px 0;
      }

      .tax-switch {
         margin-top: 80px;
         width: 45%; /* Set width for the box */
      }

      .tax-switch h2 {
         color: black;
         background-color: yellow;
         margin-top: 0;
      }

      .tax-switch p, .tax-switch label {
         font-size: 0.0001px; /* Decreased font size */
         color: white;
         background-color: black;
      }

      .tax-switch select {
         font-size: 18px; /* Decreased font size */
         margin-top: 6px;
         color: brown;
         font-weight: bold;
      }

      .tax-switch .form-group {
         display: flex;
         justify-content: space-between;
         align-items: center;
      }

      .actions {
         margin-top: 20px;
         display: flex;
         flex-wrap: wrap;
         gap: 10px;
      }

      .actions button, .actions a {
         padding: 10px 20px;
         font-size: 14px;
         color: #fff;
         background-color: #007bff;
         border: none;
         border-radius: 5px;
         text-decoration: none;
         cursor: pointer;
      }

      .actions button:hover, .actions a:hover {
         background-color: #0056b3;
      }

      .logout {
         position: absolute;
         bottom: 10px;
         right: 10px;
         text-decoration: none;
         color: #ff0000;
         font-weight: bold;
         background-color: white;
      }

      /* Styling for navigation bars */
      .topnav {
         position: absolute;
         top: 0.1px;
         right: 10px; /* Adjusted right position */
         background-color: skyblue;
         color: black;
         overflow: hidden;
         padding: 10px;
         border-radius: 5px;
         font-size: 12px; /* Match font size with welcome statement */
         z-index: 1; /* Ensure the bars are above other content */
      }

      .topnav a {
         float: left;
         color: black;
         text-align: center;
         padding: 14px 16px;
         text-decoration: none;
      }

      .topnav a:hover {
         background-color: #ddd;
         color: black;
      }

      .checkbox-container label {
         color: blue; /* Change checkbox statement color to blue */
         display: block;
         size: 1px;
         background-color: yellow;
      }

      .checkbox-container input {
         display: inline-block;
         width: 20px;
         height: 20px;
         margin-right: 10px; /* Space between checkbox and text */
      }
   </style>
</head>
<body>
   
<div class="container">
   <!-- Navigation bars -->
   <div class="topnav">
   <a href="https://cgda.nic.in/adm/circular/Income%20Tax-circular-10-01-2023.pdf" target="_blank" class="btn">Generate PDF</a>
      <a href="generate_report.php" class="btn">Generate Report</a>
   </div>

   <div class="heading">
      Tax Regime Switch Option Facility from default (New Tax Regime) to Old Tax Regime for Financial Year 2023-2024
   </div>

   <div class="welcome">
      <h1>Welcome, <span><?php echo htmlspecialchars(capitalizeName($user_details['name'])); ?></span></h1>
   </div>

   <div class="content">
      <div class="details">
         <h2>Record Details</h2>
         <p><strong>User ID:</strong> <?php echo htmlspecialchars($user_details['userid']); ?></p>
         <p><strong>Name:</strong> <?php echo htmlspecialchars(capitalizeName($user_details['name'])); ?></p>
         <p><strong>Email:</strong> <?php echo htmlspecialchars($user_details['email']); ?></p>
         <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user_details['contactno']); ?></p>
      </div>

      <div class="tax-switch">
         <h2>Tax Regime Switch Option</h2>
         <p style="font-size :20px;">You are currently under Income Tax default Option-1 (New Tax Regime).</p>
         <form method="post" action="switch_tax_regime.php">
         <div class="form-group">
            <p style="font-size: 20px;">Do you want to switch from Option-I (New Tax Regime) to Option-II (Old Tax Regime)?</p>
            <select name="switch_option" id="switch_option">
               <option value="no">No</option>
               <option value="yes">Yes</option>
            </select>
         </div>
         <form method="post" action="switch_tax_regime.php">
            <div class="checkbox-container">
               <label>
                  <input type="checkbox" name="read_documents" required>
                  <p style="color: black; font-size:15px ; background-color:skyblue;">I have read the enclosed documents attached carefully before exercising the switching option.</p> <!-- Changed color to green -->
               </label>
               <br>
               <label>
                  <input type="checkbox" name="switch_once" required>
                  <p style="color: black; font-size: 15px; background-color:skyblue;">I understand that I can switch only once in a financial year.</p> <!-- Changed color to red -->
               </label>
            </div>
            <div class="actions">
               <button type="submit">Submit</button>
               <a href="chrome-extension://efaidnbmnnnibpcajpcglclefindmkaj/https://cgda.nic.in/adm/circular/Income%20Tax-circular-10-01-2023.pdf" target="_blank" class="btn">CBDT Clarification for TDS - 1965/AC</a>
               <a href="tax_calculator.php" class="btn">Tax Calculator</a>
            </div>
         </form>
      </div>
   </div>

   <a href="logout.php" class="logout">Logout</a>
</div>

</body>
</html>
