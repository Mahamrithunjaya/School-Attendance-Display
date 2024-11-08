<?php include("../connect.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16">
    <title>Monthly Subject Enrichment</title>
    <link rel="stylesheet" href="se_month_all_styles.css">
</head>

<body>
    <div id="grad2"><b>ABC School, XYZ</b></div>
    <br>
    <table>
        <tr>
            <td>
                <font color="blue" id="a1" size="7"><b class="subject-enrichment-title">Month Subject Enrichment:
                        2024-25</b></font>
                <br>
            </td>
        </tr>
    </table>
    <br>
    <h2 class="imp-notice">SELECT MONTH AND CLICK ON SUBMIT BUTTON TO SEE THE MONTHWISE SUBJECT ENRICHMENT DETAIL</h2>
    <br>
    <center>
        <input type="button" name="back" value="BACK" class="btnSubmit" onclick="window.location.href='../index.html';">
    </center>
    <br>
    <br>
    <form method="post">
        <b class="note">Select Month and click on Submit:</b>
        <br>
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
            <option value="NOVEMBER-2024">NOVEMBER-2024</option>
            <option value="DECEMBER-2024">DECEMBER-2024</option>
            <option value="JANUARY-2025">JANUARY-2025</option>
            <option value="FEBRUARY-2025">FEBRUARY-2025</option>
            <option value="MARCH-2025">MARCH-2025</option>
        </select>
        <br>
        <input type="submit" name="sub1" value="Submit">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['sub1']) && !empty($_POST['month'])) {
            // Fetch selected month from form
            $selected_month = $_POST['month'];

            // Query to fetch data based on selected month
            $sql = "SELECT * FROM se_submissions WHERE month='$selected_month'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='table-container'>";
                echo "<table border='1'>
                <tr>
                    <th>NAME OF TEACHER</th>
                    <th>DESIGNATION</th>
                    <th>MONTH</th>
                    <th>CLASS</th>
                    <th>SUBJECT</th>
                    <th>TOPIC</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>" . $row['teacher_name'] . "</td>
                    <td>" . $row['designation'] . "</td>
                    <td>" . $row['month'] . "</td>
                    <td>" . $row['class'] . "</td>
                    <td>" . $row['subject'] . "</td>
                    <td>" . $row['topic'] . "</td>
                    </tr>";
                }
                echo "</table>";
                echo "</div>";
                echo "<p class='swipe-notice'>Swipe to view &#x1F449;</p>";
            } else {
                echo "<p class='styled-text'>No records found for " . $selected_month . ".</p>";
            }
        } else {
            echo "<p class='styled-text'>No month selected. Please choose a month and submit.</p>";
        }

        $conn->close();
    }
    ?>

    <footer>
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>