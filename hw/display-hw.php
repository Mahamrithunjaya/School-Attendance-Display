<?php include("../connect.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="img/logo.png" sizes="16x16">
    <title>Winter Break Homework Details</title>
    <link rel="stylesheet" href="styles3.css">
</head>

<body>
    <header class="header">
        <h1 class="title">ABC SCHOOL, XYZ</h1>
    </header>
    <main class="container">
        <section class="content">

            <aside class="sidebar">
                <img src="img/1.gif" alt="Image 1" class="sidebar-image">
                <img src="img/2.gif" alt="Image 2" class="sidebar-image">
                <img src="img/3.gif" alt="Image 3" class="sidebar-image">
            </aside>

            <div class="announcement">
                <h2 class="announcement-title">Homework for Winter Vaccation 2024-25</h2>
                <p class="announcement-description">SELECT CLASS AND CLICK ON SUBMIT BUTTON TO SEE THE WINTER BREAK
                    HOLIDAY HOMEWORK 2023-24</p>
            </div>

            <div class="visitor-counter">
                <span>VISITORS:</span>
                <!-- hitwebcounter Code START -->
<a href="https://www.hitwebcounter.com" target="_blank">
<img src="https://hitwebcounter.com/counter/counter.php?page=16133218&style=0002&nbdigits=6&type=page&initCount=0" title="Counter Widget" Alt="Visit counter For Websites"   border="0" /></a>
            </div>

            <button class="back-button" onclick="window.location.href='../index.html';">BACK</button>

            <form method="post" class="form">
                <label for="class">Class</label>
                <select id="class" name="c">
                    <option value="" disabled selected>SELECT</option>
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
                <input type="submit" name="sub1" value="Submit">
            </form>



        </section>
    </main>

    <?php
if (isset($_POST['c']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $class = $_POST['c'];

    // Prepare a SQL query to fetch data based on the selected class
    $sql = "SELECT * FROM homeworks WHERE class = '$class'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row in a table
        echo "<div class='table-container'>";
        echo "<table border='1' table-layout:auto>
                <tr>
                    <th>CLASS</th>
                    <th  style= 'width:5%'>SUBJECT</th>
                    <th  style= 'width:5%'>NAME OF TEACHER</th>
                    <th  style= 'width:15%'>HOMEWORK-1</th>
                    <th  style= 'width:15%'>HOMEWORK-2</th>
                    <th  style= 'width:15%'>HOMEWORK-3</th>
                    <th  style= 'width:15%'>HOMEWORK-4</th>
                    <th  style= 'width:10%'>PDF FILE</th>
                    <th  style= 'width:10%'>IMAGE FILE</th>
                </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["class"]."</td>
                    <td>".$row["subject"]."</td>
                    <td>".$row["teacher_name"]."</td>
                    <td>".$row["homework1"]."</td>
                    <td>".$row["homework2"]."</td>
                    <td>".$row["homework3"]."</td>
                    <td>".$row["homework4"]."</td>
                    <td>";

                    // Checking if the PDF path is not empty before displaying
                    if (!empty($row["pdf_path"])) {
                        echo "<a href='" . $row["pdf_path"] . "' download>Download PDF</a>";
                    } else {
                        echo "No PDF uploaded";
                    }

                    echo "</td>
                        <td>";

                    // Checking if the picture path is not empty before displaying
                    if (!empty($row["picture_path"])) {
                        echo "<img src='" . $row["picture_path"] . "' alt='Homework Picture' width='100'>";
                    } else {
                        echo "No image uploaded";
                    }

                    echo "</td>
                    </tr>";
        }
        echo "</table>";
        echo "</div>";
        echo "<p class='swipe-notice'>Swipe to view &#x1F449;</p>";
    } else {
        echo "<p class='styled-text'>No homework details found for class: " . $class ."</p>";
    }

    $conn->close();
}
?>
    <footer class="footer">
        &copy; <?php echo date("Y"); ?> ABC SCHOOL, XYZ. All rights reserved.
    </footer>
</body>

</html>