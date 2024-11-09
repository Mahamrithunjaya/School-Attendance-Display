<?php
session_start();

// Checking if user is authenticated
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: upload_attendance.php"); // Redirect to login page if not authenticated
    exit();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

include("../connect.php");

// Initialize variables
$success_message = $error_message = "";
$total_days = $month = $class = "";

// Function to sanitize input
function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, $data);
}

// Function to calculate attendance percentage
function calculateAttendancePercentage($attendance, $totalDays) {
    if ($totalDays > 0) {
        $percentage = ($attendance / $totalDays) * 100;
        return number_format($percentage, 2);
    } else {
        return '0.00';
    }
}

// Step 1: Ask for the total number of days, month, and class
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['total_days'])) {
    // Sanitize inputs
    $total_days = (int)$_POST['total_days'];
    $month = sanitize_input($conn, $_POST['month']);
    $class = sanitize_input($conn, $_POST['class']);

    // Validate class against stored session class
    if (!isset($_SESSION['class']) || $class !== $_SESSION['class']) {
        $error_message = "Error: You are not authorized to access data for this class.";
    } else if ($total_days > 0 && !empty($month) && !empty($class)) {
        // Store total days, month, and class in session variables
        $_SESSION['total_days'] = $total_days;
        $_SESSION['month'] = $month;
        $_SESSION['class'] = $class;

        // Fetching student names for selected class
        $sql_students = "SELECT DISTINCT roll_number, student_name FROM students WHERE class = '$class'";
        $result_students = $conn->query($sql_students);

        if ($result_students->num_rows > 0) {
            while ($row = $result_students->fetch_assoc()) {
                $roll_number = $row['roll_number'];
                $student_name = $row['student_name'];

                // Proceed to enter attendance details
                $success_message = "Proceed to enter attendance details.";
            }
        } else {
            $error_message = "No students found for the selected class.";
        }
    } else {
        $error_message = "Please enter a valid number of days, select a month, and select a class.";
    }
}

// Updating attendance records in attendance_records table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['roll_number']) && isset($_POST['attendance'])) {
    $roll_numbers = $_POST['roll_number'];
    $attendances = $_POST['attendance'];

    // Checking if session variables are set before using them
    if (isset($_SESSION['class']) && isset($_SESSION['month'])) {
        $class = $_SESSION['class'];
        $month = $_SESSION['month'];

        // Loop through each student's attendance data
        for ($i = 0; $i < count($roll_numbers); $i++) {
            $roll_number = sanitize_input($conn, $roll_numbers[$i]);
            $attendance = (int)sanitize_input($conn, $attendances[$i]); // Ensure attendance is an integer

            // Calculating attendance percentage
            $attendance_percentage = calculateAttendancePercentage($attendance, $_SESSION['total_days']);

            // Checking if record exists in attendance_records table
            $check_sql = "SELECT * FROM attendance_records WHERE roll_number = '$roll_number' AND month = '$month'";
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows > 0) {
                // Updating existing record
                $update_sql = "UPDATE attendance_records SET attendance = $attendance, attendance_percentage = $attendance_percentage, total_days = {$_SESSION['total_days']} WHERE roll_number = '$roll_number' AND month = '$month'";
                if ($conn->query($update_sql) === TRUE) {
                    $success_message = "Attendance updated successfully.";
                } else {
                    $error_message = "Error updating attendance records: " . $conn->error;
                }
            } else {
                // Inserting new record
                $insert_sql = "INSERT INTO attendance_records (roll_number, month, attendance, attendance_percentage, total_days) VALUES ('$roll_number','$month', $attendance, $attendance_percentage, {$_SESSION['total_days']})";
                if ($conn->query($insert_sql) === TRUE) {
                    $success_message = "Attendance inserted successfully.";
                } else {
                    $error_message = "Error inserting attendance records: " . $conn->error;
                }
            }
        }

        // Unset session variables after successful update
        unset($_SESSION['total_days']);
        unset($_SESSION['month']);
        unset($_SESSION['class']);
    } else {
        $error_message = "Session data (class or month) not set correctly.";
    }
}

// Fetching student roll numbers for selected class
if (isset($_SESSION['class'])) {
    $class = $_SESSION['class'];

    $sql = "SELECT roll_number, student_name FROM students WHERE class = '$class'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $students = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $error_message = "No students found for the selected class.";
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
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16">
    <title>Update Attendance</title>
    <link rel="stylesheet" href="styles4.css"> <!-- Ensure your external stylesheet is linked -->
    <style>
    .container {
        width: 100%;
        margin: 0 auto;
    }

    .myDiv {
        text-align: center;
        margin-bottom: 20px;
    }

    .update-form {
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
        padding: 10px;
        text-align: left;
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        box-sizing: border-box;
    }

    .name-column {
        width: 40%;
        /* Adjust the width of the name column */
    }

    .attendance-column {
        width: 30%;
        /* Adjust the width of the attendance input column */
    }

    .percentage-column {
        width: 20%;
        /* Adjust the width of the percentage column */
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
        text-align: center;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="myDiv">
            <img src="../img/logo.png" alt="Logo 1" class="logo-left">
            <h2>ABC School, XYZ</h2>
            <h3>UPDATE CLASS ATTENDANCE OF STUDENTS</h3>
            <img src="../img/logo2.png" alt="Logo 2" class="logo-right">
        </div>

        <!-- Display Form to Enter Attendance Details -->
        <?php if (!isset($_SESSION['total_days']) || !isset($_SESSION['class']) || !isset($_SESSION['month'])): ?>
        <h2>Hello, <?php echo $_SESSION['username']; ?></h2>
        <!-- Logout Button -->
        <div class="logout">
            <form method="post" action="logout.php" style="width: fit-content;">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
        <div class="update-form">
            <h2>Enter Total Days and Select Month & Class</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label>Total Days:</label>
                <input type="number" name="total_days" min="1" required><br><br>

                <label>Month:</label>
                <select name="month" required>
                    <option value="" disabled selected>Select Month</option>
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
                </select><br><br>

                <label>Class:</label>
                <select name="class" required>
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

                <input type="submit" name="submit" value="Next">
            </form>
        </div>
        <?php endif; ?>

        <!-- Display Attendance Form -->
        <?php if (isset($students) && isset($_SESSION['total_days']) && isset($_SESSION['class']) && isset($_SESSION['month'])): ?>
        <h2>Hello, <?php echo $_SESSION['username']; ?></h2>
        <!-- Logout Button -->
        <div class="logout">
            <form method="post" action="logout.php" style="width: fit-content;">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
        <div class="attendance-form">
            <h3>Enter attendance details for <?php echo $_SESSION['class']; ?> class in
                <?php echo $_SESSION['month']; ?> (Total Days: <?php echo $_SESSION['total_days']; ?>)</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <table>
                    <tr>
                        <th>Roll Number</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Attendance</th>
                        <th>Percentage</th>
                    </tr>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['roll_number']; ?></td>
                        <td><?php echo $student['student_name']; ?></td>
                        <td><?php echo $_SESSION['class']; ?></td>
                        <td>
                            <input type="number" name="attendance[]" min="0"
                                max="<?php echo $_SESSION['total_days']; ?>" required
                                value="<?php echo isset($student['attendance']) ? $student['attendance'] : ''; ?>"
                                oninput="updatePercentage(this)">
                        </td>
                        <td>
                            <span id="percentage_<?php echo $student['roll_number']; ?>">
                                <?php 
                if (isset($student['attendance'])) {
                    echo calculateAttendancePercentage($student['attendance'], $_SESSION['total_days']) . '%';
                } else {
                    echo '0%'; // or handle appropriately if attendance is not set
                }
                ?>
                            </span>
                        </td>
                        <input type="hidden" name="roll_number[]" value="<?php echo $student['roll_number']; ?>">
                    </tr>
                    <?php endforeach; ?>

                </table>
                <input type="submit" name="submit" value="Update Attendance">
            </form>
        </div>
        <?php endif; ?>

        <!-- Display Success or Error Messages -->
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

    <script>
    function updatePercentage(input) {
        var totalDays = <?php echo $_SESSION['total_days']; ?>;
        var attendance = parseInt(input.value);
        var percentage = (attendance / totalDays) * 100;
        var formattedPercentage = percentage.toFixed(2);
        var rollNumber = input.parentNode.nextElementSibling.querySelector('span').id.split('_')[
            1]; // Extract roll number
        document.getElementById('percentage_' + rollNumber).textContent = formattedPercentage + '%';
    }
    </script>

</body>

</html>