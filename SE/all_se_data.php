<?php include("../connect.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16">
    <title>All Subject Enrichment</title>
    <link rel="stylesheet" href="se_month_all_styles.css">
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
                <font color="blue" id="a1" size="7"><b class="subject-enrichment-title">Subject Enrichment : 2024-25</b></font>
                <br>
            </td>
        </tr>
    </table>
    <br>
    <h2 class="imp-notice" style="height: 30px;">SUBJECT ENRICHMENT DETAILS</h2>
    <br>
    <center>
        <input type="button" name="back" value="BACK" class="btnSubmit" onclick="window.location.href='../index.html';">
    </center>
    <br>
    <br>

    <?php

    // Query to fetch all data 
    $sql = "SELECT teacher_name, designation, month, class, subject, topic FROM se_submissions";
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
        echo "<p class='swipe-notice'>Swipe to view &#x1F872;</p>";
    } else {
        echo "<p class='styled-text'>No records found!!!!!!!!</p>";
    }

    $conn->close();

?>

    <footer>
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>