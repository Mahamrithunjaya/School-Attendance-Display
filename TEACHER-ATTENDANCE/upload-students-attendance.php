<?php
session_start(); // Start or resume session

include("../connect.php"); // Include your database connection file

$authenticated = false; // Initialize authentication status
$options_html = ""; // Initialize options HTML

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['pwd'])) {
    $username = $_POST['username'];
    $password = $_POST['pwd'];
    $selected_class = $_POST['class'];

    $_SESSION['username'] = $username;
    $_SESSION['class'] = $selected_class;

    // Validate username and password (sanitize input as needed)
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Example of using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, check password
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];

        // Verify hashed password
        if (password_verify($password, $hashed_password)) {

            $authorized_class = $user['class'];
            if ($selected_class !== $authorized_class) {
                echo "<script>alert('You are not authorized to access this class.');
                  window.history.back();</script>";
                exit;
            } else {
                $_SESSION['loggedin'] = true;

                // Password verified, set authenticated flag and options HTML
                $authenticated = true;

                $options_html .= "<h2>Welcome, $username!</h2>";
                $options_html .= "<div class='options'>";
                $options_html .= "<h3>Select an option:</h3>";
                $options_html .= "<ul>";
                $options_html .= "<li><a href='insert_students.php'>INSERT NEW STUDENTS</a></li>";
                $options_html .= "<li><a href='update_class_attendance.php'>UPDATE ATTENDANCE</a></li>";
                $options_html .= "<li><a href='delete_records.php'>DELETE STUDENTS RECORDS</a></li>";
                $options_html .= "<li><a href='export_attendance.php'>EXPORT ATTENDANCE TO EXCEL</a></li>";
                $options_html .= "</ul>";
                $options_html .= "</div>";
            }
        } else {
            echo "<script>alert('Invalid password.');
                  window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Invalid username.');
              window.history.back();</script>";
        exit; // Stop further execution
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Details</title>
    <link rel="stylesheet" href="styles4.css">
</head>

<body>
    <div class="myDiv">
        <h2>ABC School, XYZ</h2>
        <h3>STUDENT'S ATTENDANCE UPLOAD BY TEACHER</h3>
    </div>

    <div class="my-container">
        <?php
        // Display login form if not authenticated
        if (!$authenticated) {
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label>ENTER USERNAME</label>
            <input type="text" id="username" name="username" required><br><br>
            <label>ENTER PASSWORD</label>
            <input type="password" id="pwd" name="pwd" required><br><br>

            <label for="class-name">CLASS</label>
            <select id="class-name" name="class">
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
            </select><br><br>

            <input type="submit" name="sub1" value="Submit">
        </form>
        <?php } ?>

        <?php
        // Display options if authenticated
        if ($authenticated) {
            echo $options_html;
        ?>
        <div class="logout">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
        <?php } ?>

        <center>
            <input type="button" name="back" value="BACK" onclick="window.location.href='../index.html';">
        </center>
    </div>

    <footer class="footer">
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>
</body>

</html>