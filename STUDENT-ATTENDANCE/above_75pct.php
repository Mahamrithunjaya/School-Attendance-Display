<?php include("../connect.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Above 75</title>
    <link rel="stylesheet" href="styles5.css">
</head>

<body>
    <div id="grad2"><b>ABC School, XYZ</b></div>
    <br>
    <table>
        <tr>
            <td>
                <font color="blue" id="a1" size="7"><b class="subject-enrichment-title">Monthly Attendance Register</b>
                </font>
                <br>
            </td>
        </tr>
    </table>
    <br>
    <h2 class="imp-notice">REPORT ON ATTENDANCE BELOW 75% OF VIDYALAYA</h2>
    <br>
    <center>
        <input type="button" name="back" value="BACK" class="btnSubmit" onclick="window.location.href='../index.html';">
    </center>
    <br>
    <br>

    <?php

    // Calculate the previous month and year in "MONTH-YEAR" format
    $prev_month_year = strtoupper(date("F-Y", strtotime("first day of previous month")));


    // Query to fetch all data 
    $sql = "SELECT class, student_name, month, attendance, attendance_percentage FROM students WHERE attendance_percentage >= 75 AND month = '$prev_month_year'";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table border='1'>
            <tr>
            <th>Sl. No.</th>
            <th>CLASS</th>
            <th>NAME OF STUDENT</th>
            <th>MONTH</th>
            <th>ATTENDANCE </th>
            <th>PERCENTAGE </th>
            </tr>";

        // Initialize the counter
        $srNo = 1;

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
        <td>" . $srNo . "</td>
                <td>" . $row['class'] . "</td>
            <td>" . $row['student_name'] . "</td>
            <td>" . $row['month'] . "</td>
            <td>" . $row['attendance'] . "</td>
            <td>" . $row['attendance_percentage'] . "%</td>
              </tr>";

            // Increment the counter
            $srNo++;
        }
        echo "</table>";
        echo "</div>";
        echo "<p class='swipe-notice'>Swipe to view &#x1F449;</p>";
    } else {
        echo "<p class='styled-text'>No records found for " . $prev_month_year . "!!!!!!!!</p>";
    }

    $conn->close();

    ?>
    <footer>
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>