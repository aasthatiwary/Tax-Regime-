<?php

// Include the database configuration file
@include 'config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the start and end dates from the form
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Prepare and execute the SQL query to retrieve user details within the specified date range
    $query = "SELECT userid, name, email, decision, date FROM user_form WHERE date BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any records are found
    if ($result->num_rows > 0) {
        // Store the report in a variable
        $report = "<h2>User Details Report</h2>";
        $report .= "<table class='report-table'>";
        $report .= "<tr><th>User ID</th><th>Name</th><th>Email</th><th>Decision</th><th>Date</th></tr>";

        // Loop through each row of the result set
        while ($row = $result->fetch_assoc()) {
            // Add each row to the report
            $report .= "<tr>";
            $report .= "<td>" . $row['userid'] . "</td>";
            $report .= "<td>" . $row['name'] . "</td>";
            $report .= "<td>" . $row['email'] . "</td>";
            $report .= "<td>" . $row['decision'] . "</td>";
            $report .= "<td>" . $row['date'] . "</td>";
            $report .= "</tr>";
        }

        // Close the table
        $report .= "</table>";

        // Output the report in a hidden div
        echo "<div id='report' style='display:none;'>$report</div>";
    } else {
        // Output a message if no records are found
        echo "<p class='no-records'>No records found.</p>";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://media.istockphoto.com/id/1489003364/photo/tax-deduction-planning-concept-businessman-calculating-business-balance-prepare-tax-reduction.webp?b=1&s=170667a&w=0&k=20&c=eS6Bv0oWc2izZRAQMwLCLKGfWZImdtp6tYZYgdhQq2g=') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: black;
            background-color: yellow;
            padding: 10px;
            border-radius: 5px;
            width :auto;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="date"],
        button {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #reportActions {
            display: flex; /* Use flexbox to align items horizontally */
            justify-content: center; /* Align items horizontally to the center */
            margin: 20px 600px;
            flex-wrap: wrap; /* Allow buttons to wrap to the next line if needed */
            background-color: black;
        }
        #reportActions button {
            margin: 5px;
            padding: 8px 16px; /* Adjust padding to reduce button size */
            font-size: 14px; /* Adjust font size to reduce button size */
            background-color: brown;
            color: white;
            border: none;
            cursor: pointer;
            width: auto; /* Set button width to auto to fit all buttons in the same row */
        }
        }
        #reportActions button:hover {
            background-color: #333;
        }
        #logoutButton {
            position: absolute; /* Position the button absolutely */
            top: 10px; /* Align the button to the top */
            right: 10px; /* Align the button to the right */
            padding: 10px 20px;
            font-size: 16px;
            background-color: black;
            color: white;
            border: red;
            cursor: pointer;
        }

        #logoutButton:hover {
            background-color: black;
        }
        #reportContainer {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transform-origin: 0 0;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-table th,
        .report-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .report-table th {
            background-color: #007BFF;
            color: white;
        }
        .no-records {
            text-align: center;
               color: red;
        }
        #taxRegimeHeading {
            position: absolute;
            top: 1px;
            left: 20px;
            color: black; /* Change the color as needed */
            font-size: 24px; /* Adjust the font size */
            font-weight: bold;
            z-index: 2; /* Ensure the heading is above other content */
            background-color: blue;
        }
    </style>
</head>
<body>
    <h2 id="taxRegimeHeading">Tax Regime System</h2>
    <h2>Generate Report</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>
        <button type="submit">Generate Report</button>
    </form>

    <div id="reportActions" style="display:none;">
        <button onclick="printReport()">Print Report</button>
        <button onclick="zoomReport(1.1)">Zoom In</button>
        <button onclick="zoomReport(0.9)">Zoom Out</button>
        <button onclick="saveReport()">Save Report</button>
        <button id="logoutButton" onclick="logout()">Logout</button>
    </div>
    
    <div id="reportContainer"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const report = document.getElementById('report');
            if (report) {
                const reportContainer = document.getElementById('reportContainer');
                reportContainer.innerHTML = report.innerHTML;
                document.getElementById('reportActions').style.display = 'block';
            }
        });

        function printReport() {
            const printContent = document.getElementById('reportContainer').innerHTML;
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }

        function zoomReport(scaleFactor) {
            const reportContainer = document.getElementById('reportContainer');
            const currentScale = reportContainer.style.transform.match(/scale\(([^)]+)\)/);
            const scale = currentScale ? parseFloat(currentScale[1]) : 1;
            reportContainer.style.transform = `scale(${scale * scaleFactor})`;
        }

        function saveReport() {
            const reportContent = document.getElementById('reportContainer').innerHTML;
            const blob = new Blob([reportContent], { type: 'text/html' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'user_details_report.html';
            link.click();
        }

        function logout() {
            window.location.href = 'logout.php';
        }
    </script>

</body>
</html>
