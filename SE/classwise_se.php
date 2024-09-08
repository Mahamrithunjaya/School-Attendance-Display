<?php include("../connect.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classwise Subject Enrichment</title>
    <link rel="stylesheet" href="se_classwise_styles.css">
</head>

<body>
    <div id="grad2"><b>ABC School, XYZ</b></div>
    <br>
    <table>
        <tr>
            <td>
                <font color="blue" id="a1" size="15"><b class="subject-enrichment-title">CLASSWISE SUBJECT ENRICHMENT : 2024-25</b></font><br>
            </td>
        </tr>
    </table>
    <br>
    <h2 class="notice-h2">SELECT CLASS AND CLICK ON SUBMIT BUTTON TO SEE THE CLASSWISE SUBJECT ENRICHMENT DETAIL</h2>
    <br><br>
    <center>
        <input type="button" name="back" value="BACK" class="btnSubmit" onclick="window.location.href='../index.html';">
    </center>
    <br>
    <form method="post">
        <label for="class">Select Class and Click on Submit:</label><br>
        <select name="class" id="class" size="5">
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

            // Query to fetch data based on selected class
            $sql = "SELECT * FROM se_submissions WHERE class='$selected_class'";
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
                    <td>" . htmlspecialchars($row['teacher_name']) . "</td>
                    <td>" . htmlspecialchars($row['designation']) . "</td>
                    <td>" . htmlspecialchars($row['month']) . "</td>
                    <td>" . htmlspecialchars($row['class']) . "</td>
                    <td>" . htmlspecialchars($row['subject']) . "</td>
                    <td>" . htmlspecialchars($row['topic']) . "</td>
                  </tr>";
                }
                echo "</table>";
                echo "</div>";
                echo "<p class='swipe-notice'>Swipe to view &#x1F449;</p>";
            } else {
                echo "<p class='styled-text'>No records found for " . htmlspecialchars($selected_class) . " class.</p>";
            }
        } else {
            echo "<p class='styled-text'>No class selected. Please choose a class and submit.</p>";
        }

        $conn->close();
    }
    ?>

    <footer>
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>
