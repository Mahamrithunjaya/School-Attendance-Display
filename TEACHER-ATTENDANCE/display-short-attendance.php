<?php
session_start(); // Start or resume session

include("../connect.php"); // Include your database connection file

$authenticated = false; // Initialize authentication status
$options_html = ""; // Initialize options HTML
$form_html = ""; // Initialize form HTML

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
                $authenticated = true;

                // Display form to enter attendance details
                $form_html .= "<h2>Lack of Attendance Details</h2>";
                $form_html .= "<form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>";
                $form_html .= "<label>Date: </label>";
                $form_html .= "<input type='date' name='date' required><br><br>";
                $form_html .= "<label>Teacher Name</label>";
                $form_html .= "<input type='text' name='teacher_name' required><br><br>";
                $form_html .= "<label>Designation</label>";
                $form_html .= "<input type='text' name='designation' required><br><br>";
                $form_html .= "<label>Class</label>";
                $form_html .= "<select name='class' required>";
                $form_html .= "<option value='$selected_class'>$selected_class</option>"; // Selected class
                $form_html .= "<option value='1A'>1A</option>";
                $form_html .= "<option value='2A'>2A</option>";
                $form_html .= "<option value='3A'>3A</option>";
                $form_html .= "<option value='4A'>4A</option>";
                $form_html .= "<option value='5A'>5A</option>";
                $form_html .= "<option value='6A'>6A</option>";
                $form_html .= "<option value='7A'>7A</option>";
                $form_html .= "<option value='8A'>8A</option>";
                $form_html .= "<option value='9A'>9A</option>";
                $form_html .= "<option value='10A'>10A</option>";
                $form_html .= "<option value='11A'>11SCI</option>";
                $form_html .= "<option value='12A'>12A</option>";
                $form_html .= "</select><br><br>";
                $form_html .= "<label>Topic (MAX 500 WORDS)</label>";
                $form_html .= "<textarea name='topic' rows='6' cols='60' required></textarea><br><br>";
                
                $form_html .= "<input type='submit' name='submit' value='Submit'>";
                $form_html .= "</form>";
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

// Handle attendance details submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date']) && isset($_POST['teacher_name']) && isset($_POST['designation']) && isset($_POST['class']) && isset($_POST['topic'])) {
    $date = $_POST['date'];
    $teacher_name = $_POST['teacher_name'];
    $designation = $_POST['designation'];
    $class = $_POST['class'];
    $topic = $_POST['topic'];
    

    // Sanitize inputs
    $date = mysqli_real_escape_string($conn, $date);
    $teacher_name = mysqli_real_escape_string($conn, $teacher_name);
    $designation = mysqli_real_escape_string($conn, $designation);
    $topic = mysqli_real_escape_string($conn, $topic);

    // Insert attendance details into database
    $insert_query = "INSERT INTO attendance (date, teacher_name, designation, class, topic) VALUES ('$date', '$teacher_name', '$designation', '$class', '$topic')";
    if ($conn->query($insert_query) === TRUE) {
        echo "<script>alert('Attendance details successfully stored.');
              window.location.href = '".$_SERVER["PHP_SELF"]."';</script>";
        exit;
    } else {
        echo "<script>alert('Error storing attendance details: ".$conn->error."');
              window.history.back();</script>";
        exit;
    }
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
    <style>
      
input[type=date] {
    padding: 8px;
    width: calc(100% - 24px);
    margin-bottom: 15px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    background-color: #eff0a6;
    border-radius: 4px;
    font-size: 16px;
}

/* Styles for textarea */
textarea {
    width: calc(100% - 24px);
    padding: 12px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    background-color: #eff0a6;
    border-radius: 4px;
    resize: vertical; /* Allow vertical resizing of textarea */
    font-size: 16px;
    line-height: 1.5;
}

/* Optional: Styles for form labels */
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

    </style>
</head>
<body>
    <div class="myDiv">
        <h2>ABC School, XYZ</h2>
        <h3>DETAILS OF CLASS ABSENT</h3>
    </div>

    <?php
    // Display "Hi, username" only if authenticated
    if ($authenticated) {
        echo "<h2>Hi, " . $_SESSION['username'] . "</h2>";
    }
    ?>

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
        // Display form to enter attendance details if authenticated
        if ($authenticated) {
            echo $form_html;
        ?>

        <div class="logout">
            <form method="post" action="logout.php">
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
