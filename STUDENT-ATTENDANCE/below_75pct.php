<?php include("../connect.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16">
    <title>Attendance Below 75</title>
    <link rel="stylesheet" href="styles5.css">
</head>

<body>
    <div id="grad2">
        <img src="../img/logo.png" alt="Logo 1" class="logo-left">
        <b>ABC School, XYZ</b>
        <img src="../img/logo_2.png" alt="Logo 2" class="logo-right">
    </div>
    <br>
    <table>
        <tr>
            <td>
                <font color="blue" id="a1" size="7"><b class="subject-enrichment-title">Monthly Attendance Analysis below 75%</b>
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

    // Calculating the previous month and year in "MONTH-YEAR" format
    $prev_month_year = strtoupper(date("F-Y", strtotime("first day of previous month")));

    // Query to fetch all data
    $sql = "SELECT students.class, students.student_name, attendance_records.month,
        attendance_records.attendance, attendance_records.attendance_percentage
        FROM attendance_records
        JOIN students ON attendance_records.roll_number = students.roll_number
        WHERE attendance_percentage < 75 AND month = '$prev_month_year'";
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

            // Initializing the counter
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

            // Incrementing the counter
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
