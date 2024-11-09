<?php include("../connect.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16">
    <title>Check Attendance (Student) </title>
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
                <font color="blue" id="a1" size="7"><b class="subject-enrichment-title">Attendance Details</b></font>
                <br>
            </td>
        </tr>
    </table>
    <br>
    <h2 class="imp-notice">ENTER ADMISSION NO AND CLICK ON SUBMIT BUTTON TO SEE THE ATTENDANCE POSITION MONTHWISE</h2>
    <br>
    <center>
        <input type="button" name="back" value="BACK" class="btnSubmit" onclick="window.location.href='../index.html';">
    </center>
    <br>
    <br>

    <form method="post">
        <b class="note">Enter Your Admission Number and Click on Submit button</b>
        <br> <br>
        <label for="admission-no">ENTER ADMISSION NO. : </label><input type="text" name="admission-no">
        <br>
        <input type="submit" name="sub1" value="Submit">
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sub1'])) {
        $admissionNo = $_POST['admission-no'];

        // Query to fetch student data based on admission number
        $sql = "SELECT students.class, students.student_name, attendance_records.month, 
                attendance_records.attendance, attendance_records.attendance_percentage
                FROM attendance_records
                JOIN students ON attendance_records.roll_number = students.roll_number
                WHERE students.roll_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $admissionNo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            echo "<p class='search-adm-no'>Your Admission No.: <strong>$admissionNo</strong></p>";
            echo "<div class='table-container'>";
            echo "<table border='1'>
                    <tr>
                        <th>Sr. No.</th>
                        <th>CLASS</th>
                        <th>NAME OF STUDENT</th>
                        <th>MONTH</th>
                        <th>ATTENDANCE</th>
                        <th>PERCENTAGE</th>
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
            echo "<p class='swipe-notice'>Swipe to view &#x1F872;</p>";
        } else {
            echo "<p class='styled-text'>No records found!!!!!!!!</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
    <footer>
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>