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
    <style>
        table {
            width: 85%;
        }

        th {
            height: 20px;
        }

        .form-row {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 10px;
        }

        .form-row label {
            display: block;
            margin-bottom: 5px;
        }

        .form-row select {
            width: 150px;
        }
    </style>
</head>

<body>
    <div id="grad2"><b>ABC School, XYZ</b></div>
    <br>
    <table>
        <tr>
            <td>
                <font color="blue" id="a1" size="7"><b class="subject-enrichment-title">CLASSWISE REPORT</b></font>
                <br>
            </td>
        </tr>
    </table>
    <br>
    <h2 class="imp-notice">SELECT CLASS AND CLICK ON SUBMIT BUTTON TO SEE THE CLASS REPORT</h2>
    <br>
    <center>
        <input type="button" name="back" value="BACK" class="btnSubmit" onclick="window.location.href='../index.html';">
    </center>
    <br>
    <br>
    <form method="post">
        <b class="note">Select Class and click on Submit Button</b>
        <br> <br>
        <div class="form-row">
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
        </div>
        <div class="form-row">
            <label for="month">MONTH: </label>
            <select id="month" name="month">
                <option value="" disabled selected>Select</option>
                <option value="APRIL-2024">APRIL-2024</option>
                <option value="MAY-2024">MAY-2024</option>
                <option value="JUNE-2024">JUNE-2024</option>
                <option value="JULY-2024">JULY-2024</option>
                <option value="AUGUST-2024">AUGUST-2024</option>
                <option value="SEPTEMBER-2024">SEPTEMBER-2024</option>
                <option value="OCTOBER-2024">OCTOBER-2024</option>
                <option value="NOVEMBER-2024">NOVEMBER-2024 </option>
                <option value="DECEMBER-2024">DECEMBER-2024</option>
                <option value="JANUARY-2025">JANUARY-2025</option>
                <option value="FEBRUARY-2025">FEBRUARY-2025</option>
                <option value="MARCH-2025">MARCH-2025</option>
            </select>
        </div>
        <br>
        <input type="submit" name="sub1" value="Submit">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sub1'])) {
        if (isset($_POST['class']) && isset($_POST['month'])) {
            $selectedClass = $_POST['class'];
            $selectedMonth = $_POST['month'];

            // Query to fetch student details based on selected class and month
            $sql = "SELECT students.student_name, attendance_records.attendance,
                    attendance_records.total_days, attendance_records.attendance_percentage
                    FROM attendance_records
                    JOIN students ON attendance_records.roll_number = students.roll_number
                    WHERE students.class = ? AND attendance_records.month = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $selectedClass, $selectedMonth);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h3 class='cls-report-h3'>Class Report for $selectedClass - $selectedMonth</h3>";
                echo "<div class='table-container'>";
                echo "<table border='1'>
                    <tr>
                        <th>Sr. No.</th>
                        <th>NAME OF STUDENT</th>
                        <th>$selectedMonth ATTENDANCE</th>
                        <th>$selectedMonth TOTAL MEETING</th>
                        <th>$selectedMonth PERCENTAGE</th>
                    </tr>";

                // Initialize the counter
                $srNo = 1;

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $srNo . "</td>
                        <td>" . $row['student_name'] . "</td>
                        <td>" . $row['attendance'] . "</td>
                        <td>" . $row['total_days'] . "</td>
                        <td>" . $row['attendance_percentage'] . "%</td>
                      </tr>";

                    // Increment the counter
                    $srNo++;
                }

                echo "</table>";
                echo "</div>";
                echo "<p class='swipe-notice'>Swipe to view &#x1F449;</p>";
            } else {
                echo "<p class='styled-text'>No records found for $selectedClass - $selectedMonth</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='styled-text'>Please select a class and month to view the report.</p>";
        }
    }

    $conn->close();
    ?>

    <footer>
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>