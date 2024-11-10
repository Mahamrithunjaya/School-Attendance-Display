<?php
session_start(); // Start or resume session

include("../connect.php"); // Include your database connection file

$authenticated = false; // Initialize authentication status
$options_html = ""; // Initialize options HTML

// Processing form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['pwd'])) {
    $username = $_POST['username'];
    $password = $_POST['pwd'];
    $selected_class = $_POST['class'];

    $_SESSION['username'] = $username;
    $_SESSION['class'] = $selected_class;

    // Validating username and password (sanitize input as needed)
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Preparing statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);

    // Executing the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, check password
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];

        // Verifying hashed password
        if (password_verify($password, $hashed_password)) {

            $authorized_class = $user['class'];
            $is_class_teacher = $user['is_class_teacher'];
            if ($selected_class !== $authorized_class) {
                echo "<script>alert('You are not authorized to access this class.');
                  window.history.back();</script>";
                exit;
            }

            if ($user['is_principal'] == 1) {
                // Principal authenticated
                $_SESSION['loggedin'] = true;
                $_SESSION['role'] = 'principal';
                $authenticated = true;

                $options_html .= "<h2>Welcome, Principal!</h2> <img src='../img/Nerd Face.png' alt='Nerd Face' />";
                $options_html .= "<div class='options'>";
                $options_html .= "<h3>Select an option:</h3>";
                $options_html .= "<ul>";
                $options_html .= "<li><a href='download_all_classes.php'>DOWNLOAD ALL CLASSES ATTENDANCE</a></li>";
                $options_html .= "</ul>";
                $options_html .= "</div>";
                $options_html .= "<h3 class='blinking-message'> Due to the lack of records prior to August 2024, kindly choose the months from that month.</h3>";
                $options_html .= "</div>";
            } else {
                // Checking if the user is a class teacher
                if ($is_class_teacher != 1) {
                    echo "<script>alert('Only class teachers are allowed to access this section.');
                        window.history.back();</script>";
                    exit;
                } else {
                    $_SESSION['loggedin'] = true;

                    // Password verified, set authenticated flag and options HTML
                    $authenticated = true;

                    $options_html .= "<h2>Welcome, $username!</h2> <img src='../img/Nerd Face.png' alt='Nerd Face' />";
                    $options_html .= "<div class='options'>";
                    $options_html .= "<h3>Select an option:</h3>";
                    $options_html .= "<ul>";
                    $options_html .= "<li><a href='insert_students.php'>INSERT NEW STUDENTS<span></span><span></span><span></span><span></span></a></li>";
                    $options_html .= "<li><a href='update_class_attendance.php'>UPDATE ATTENDANCE<span></span><span></span><span></span><span></span><div></div></a></li>";
                    $options_html .= "<li><a href='delete_records.php'>DELETE STUDENTS RECORDS<span></span><span></span><span></span><span></span></a></li>";
                    $options_html .= "<li><a href='export_attendance.php'>EXPORT ATTENDANCE TO EXCEL<span></span><span></span><span></span><span></span></a></li>";
                    $options_html .= "</ul>";
                    $options_html .= "</div>";
                }
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
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16">
    <title>Attendance Details</title>
    <link rel="stylesheet" href="styles4.css">

    <style>
        .blinking-message {
            color: red;
            /* Text color */
            text-align: center;
            /* Center the text */
            animation: blink-animation 1s steps(5, start) infinite;
            /* Apply blinking effect */
        }

        /* Keyframes for the blinking effect */
        @keyframes blink-animation {
            to {
                visibility: hidden;
                /* Make the text invisible */
            }
        }

        .logout {
            position: absolute;
        }

        h2 {
            display: inline-block;
            vertical-align: middle;
            margin: 0;
        }

        img {
            display: inline-block;
            transition: 0.5s;
            width: 3.5rem;
            height: 3.5rem;
            vertical-align: middle;
            margin-left: 10px;
        }

        @media (max-width: 480px) {
            .logout {
                right: 35%;
                margin-top: 15px;
            }

            .options {
                padding-top: 54px;
            }

            .title-header {
                margin-top: 60px;
                margin-bottom: 5px;
            }

            .school-name {
                margin-top: 10px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="myDiv">
        <img src="../img/logo.png" alt="Logo 1" class="logo-left">
        <h2>ABC School, XYZ</h2>
        <h3>STUDENT'S ATTENDANCE UPLOAD BY TEACHER</h3>
        <img src="../img/logo2.png" alt="Logo 2" class="logo-right">
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