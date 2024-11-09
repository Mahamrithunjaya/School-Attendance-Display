<?php
// Start or resume session
session_start();

// Checking if user is authenticated
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: upload_attendance.php"); // Redirect to login page if not authenticated
    exit();
}

include("../connect.php");

// Initialize variables
$success_message = $error_message = "";

// Function to sanitize input
function sanitize_input($conn, $data)
{
    return mysqli_real_escape_string($conn, $data);
}

// Step 1: Asking for the total number of students and class
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['total_students']) && isset($_POST['class'])) {
    $total_students = (int)$_POST['total_students'];
    $class = sanitize_input($conn, $_POST['class']);

    if ($total_students > 0 && $total_students <= 100 && !empty($class)) {
        // Check if the class matches the logged-in user's class
        if (isset($_SESSION['class']) && $_SESSION['class'] !== $class) {
            $error_message = "You are not authorized to enter data for this class.";
        } else {
            // Store total students and class in session variables
            $_SESSION['total_students'] = $total_students;
            $_SESSION['class'] = $class;
        }
    } else {
        $error_message = "Please enter a valid number of students (<=100) and select a class.";
    }
}

// Inserting student records into database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['roll_number']) && isset($_POST['student_name']) && isset($_POST['gender'])) {
    $roll_numbers = $_POST['roll_number'];
    $student_names = $_POST['student_name'];
    $classes = $_POST['class'];
    $genders = $_POST['gender'];

    // Prepare and execute insert statements
    $stmt = $conn->prepare("INSERT INTO students (roll_number, student_name, class, gender) VALUES (?, ?, ?, ?)");

    for ($i = 0; $i < count($roll_numbers); $i++) {
        $roll_number = sanitize_input($conn, $roll_numbers[$i]);
        $student_name = sanitize_input($conn, $student_names[$i]);
        $class = sanitize_input($conn, $classes[$i]);
        $gender = sanitize_input($conn, $genders[$i]);

        $stmt->bind_param("ssss", $roll_number, $student_name, $class, $gender);
        $stmt->execute();
    }

    // Checking if all inserts were successful
    if ($stmt->affected_rows > 0) {
        $success_message = "All student records inserted successfully!!!!";
        // Unset session variables after successful insertion
        unset($_SESSION['total_students']);
        unset($_SESSION['class']);
    } else {
        $error_message = "Error inserting student records: " . $stmt->error;
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
    <title>Insert New Students</title>
    <link rel="stylesheet" href="styles4.css">
    <style>
        .container {
            width: 100%;
            margin: 0 auto;
        }

        form {
            max-width: 800px;
        }

        .myDiv {
            text-align: center;
            margin-bottom: 20px;
        }

        .insert-form,
        .student-details-form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        .name-column {
            width: 47%;
        }

        .class-column {
            width: 13%;
        }

        .success-message,
        .error-message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            font-size: 30px;
            animation: blink-animation 1s steps(5, start) infinite;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }

        .error-message {
            color: red;
        }

        .logout {
            position: static;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="myDiv">
            <img src="../img/logo.png" alt="Logo 1" class="logo-left">
            <h2>ABC School, XYZ</h2>
            <h3>UPLOAD STUDENT'S ATTENDANCE BY TEACHER</h3>
            <img src="../img/logo2.png" alt="Logo 2" class="logo-right">
        </div>

        <!-- Logout Button -->
        <div class="logout">
            <form method="post" action="logout.php" style="width: fit-content;">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>

        <?php if (!isset($_SESSION['total_students']) || !isset($_SESSION['class'])): ?>

            <!-- Display Form to Enter Total Students and Class -->
            <div class="insert-form">
                <h2>Enter Total Number of Students and Class</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label>Total Students:</label>
                    <input type="number" name="total_students" min="1" required><br><br>

                    <label>Class:</label>
                    <select name="class" required>
                        <option value="" disabled selected>Select Class</option>
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

                    <input type="submit" name="submit" value="Next">
                </form>
            </div>

        <?php else: ?>
            <!-- Displaying Form to Enter Student Details -->
            <div class="student-details-form">
                <h2>Enter Students Details</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <table>
                        <tr>
                            <th>Admission Number</th>
                            <th class="name-column">Name</th>
                            <th class="class-column">Class</th>
                            <th>Gender</th>
                        </tr>
                        <?php for ($i = 1; $i <= $_SESSION['total_students']; $i++): ?>
                            <tr>
                                <td><input type='text' name='roll_number[]' required></td>
                                <td><input type='text' name='student_name[]' required></td>
                                <td><input type='text' name='class[]' value='<?php echo $_SESSION['class']; ?>' readonly></td>
                                <td>
                                    <select name='gender[]' required>
                                        <option value='' disabled selected>Select</option>
                                        <option value='Male'>Male</option>
                                        <option value='Female'>Female</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </table>
                    <input type='submit' name='submit' value='Insert Students'>
                </form>
            </div>
        <?php endif; ?>

        <!-- Displaying Success or Error Messages -->
        <?php if (!empty($success_message)) : ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

    </div>

    <footer class="footer">
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>
</body>

</html>