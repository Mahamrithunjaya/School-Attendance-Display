<?php include("../connect.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16">
    <title>Attendance Analysis</title>
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
                <font color="blue" id="a1" size="7"><b class="subject-enrichment-title">Attendance Analysis</b></font>
                <br>
            </td>
        </tr>
    </table>
    <br>
    <h2 class="imp-notice">SELECT CLASS AND CLICK ON SUBMIT BUTTON TO SEE THE ANALYSIS OF ATTENDANCE</h2>
    <br>
    <center>
        <input type="button" name="back" value="BACK" class="btnSubmit" onclick="window.location.href='../index.html';">
    </center>
    <br>
    <br>
    <form method="post">
        <b class="note">Select the Class and Click on Submit</b>
        <br> <br>
        <label for="class">CLASS: </label>
        <select id="class" name="class">
            <option value="" disabled selected>Select</option>
            <option value="" disabled selected>Select</option>
                <option value="1A">1A</option><br>
                <option value="1B">1B</option><br>
                <option value="2A">2A</option><br>
                <option value="2B">2B</option><br>
                <option value="3A">3A</option><br>
                <option value="3B">3B</option><br>
                <option value="BALVATIKA-III">BALVATIKA-III</option><br>
                <option value="4A">4A</option><br>
                <option value="4B">4B</option><br>
                <option value="5A">5A</option><br>
                <option value="5B">5B</option><br>
                <option value="6A">6A</option><br>
                <option value="6B">6B</option><br>
                <option value="7A">7A</option><br>
                <option value="7B">7B</option><br>
                <option value="8A">8A</option><br>
                <option value="8B">8B</option><br>
                <option value="8C">8C</option><br>
                <option value="9A">9A</option><br>
                <option value="9B">9B</option><br>
                <option value="9C">9C</option><br>
                <option value="10A">10A</option><br>
                <option value="10B">10B</option><br>
                <option value="10C">10C</option><br>
                <option value="11-SCI">11-SCI</option><br>
                <option value="11-COMM">11-COMM</option><br>
                <option value="12-SCI">12-SCI</option><br>
                <option value="12-COMM">12-COMM</option><br>
        </select>
        <br>
        <input type="submit" name="sub1" value="Submit">
    </form>

    <?php
    if (isset($_POST['sub1']) && $_SERVER["REQUEST_METHOD"] == "POST") {

        if (!empty($_POST['class'])) {

            // Fetch selected class from form
            $selected_class = $_POST['class'];

            // Calculate the previous month and year in "MONTH-YEAR" format
            $prev_month_year = strtoupper(date("F-Y", strtotime("first day of previous month")));

            // Fetching students below 75%
            $sql_below_75 = "SELECT * FROM attendance_records
                    JOIN students ON attendance_records.roll_number = students.roll_number
                    WHERE class = '$selected_class' AND attendance_percentage < 75 AND month = '$prev_month_year'";
            $result_below_75 = $conn->query($sql_below_75);

            // Fetching students above 75%
            $sql_above_75 = "SELECT * FROM attendance_records
                    JOIN students ON attendance_records.roll_number = students.roll_number
                    WHERE class = '$selected_class' AND attendance_percentage >= 75 AND month = '$prev_month_year'";
            $result_above_75 = $conn->query($sql_above_75);

            // Output class-wise statistics table
            echo '<b class="table-tag">Class-wise Attendance Statistics</b>';
            echo '<table border="1" ALIGN="TOP" bgcolor="#FFFFCC">';
            echo '<tr> <th> CLASS </th><th> NO OF STUDENT BELOW 75% TILL ' . $prev_month_year . ' </th><th> NO OF STUDENT ABOVE 75% TILL ' . $prev_month_year . ' </th> </tr>';
            echo '<tr align="top">';
            echo '<td>' . htmlspecialchars($selected_class) . '</td>';
            echo '<td>' . $result_below_75->num_rows . '</td>';
            echo '<td>' . $result_above_75->num_rows . '</td>';
            echo '</tr>';
            echo '</table><br>';

            // Output students below 75% table
            echo '<b class="table-tag">Students Below 75% Till ' . $prev_month_year . '</b>';
            echo '<table border="1" ALIGN="TOP" bgcolor="#FFFFCC">';
            echo '<tr> <th> NAME OF STUDENT </th><th> ATTENDANCE </th><th> PERCENTAGE </th> </tr>';

            while ($row_below_75 = $result_below_75->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row_below_75['student_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row_below_75['attendance']) . '</td>';
                echo '<td>' . htmlspecialchars($row_below_75['attendance_percentage']) . ' %</td>';
                echo '</tr>';
            }

            echo '</table><br>';

            // Output students above 75% table
            echo '<b class="table-tag">Students Above 75% Till ' . $prev_month_year . '</b>';
            echo '<table border="1" ALIGN="TOP" bgcolor="#FFFFCC">';
            echo '<tr> <th> NAME OF STUDENT </th><th> ATTENDANCE </th><th> PERCENTAGE </th> </tr>';

            while ($row_above_75 = $result_above_75->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row_above_75['student_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row_above_75['attendance']) . '</td>';
                echo '<td>' . htmlspecialchars($row_above_75['attendance_percentage']) . ' %</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "<p class='styled-text'>No class selected. Please choose a class and submit.</p>";
        }
    }

    $conn->close();
    ?>

    <footer>
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>