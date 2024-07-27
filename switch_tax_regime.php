<?php
// Include the database configuration file
@include 'config.php';

// Start the session
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user's decision from the form
    $switch_option = $_POST['switch_option'];

    // Get the current date
    $date = date("Y-m-d");

    // Fetch user details from the session
    $user_name = $_SESSION['user_name'];

    // Prepare and execute the SQL statement to update the user's decision in the database
    $update_user_decision = $conn->prepare("UPDATE user_form SET decision = ?, date = ? WHERE name = ?");
    if ($update_user_decision) {
        $update_user_decision->bind_param("sss", $switch_option, $date, $user_name);
        if ($update_user_decision->execute()) {
            // Redirect back to the user page after successful update
            header('Location: user_page.php');
            exit;
        } else {
            // Handle execution error
            echo "Error executing query: " . $update_user_decision->error;
        }
        $update_user_decision->close();
    } else {
        // Handle preparation error
        echo "Error preparing query: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
