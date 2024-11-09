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

// Function to sanitize input
function sanitize_input($conn, $data)
{
    return mysqli_real_escape_string($conn, $data);
}

// Step 1: Ask for the action (delete single or drop class)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handle single student record deletion
    if (isset($_POST['delete_single'])) {
        $roll_number = sanitize_input($conn, $_POST['roll_number']);

        // Retrieve the class for the entered roll number
        $sql_get_class = "SELECT class FROM students WHERE roll_number = '$roll_number'";
        $result = $conn->query($sql_get_class);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $class = $row['class'];

            // Validate class against stored session class
            if (!isset($_SESSION['class']) || $class !== $_SESSION['class']) {
                $error_message = "Error: You are not authorized to delete records for this class.";
            } else {
                // Deletion in both tables
                $sql_delete_attendance = "DELETE FROM attendance_records WHERE roll_number = '$roll_number'";
                $sql_delete_single = "DELETE FROM students WHERE roll_number = '$roll_number' AND class = '$class'";
                if ($conn->query($sql_delete_attendance) === TRUE && $conn->query($sql_delete_single) === TRUE) {
                    $success_message_single = "Student record and associated attendance records deleted successfully.";
                } else {
                    $error_message_single = "Error deleting student record: " . $conn->error;
                }
            }
        } else {
            $error_message_single = "Error: Roll number not found.";
        }
    }

    // Handling for dropping entire class records
    if (isset($_POST['delete_all'])) {
        // Checking if class session variable is set and not empty
        if (!isset($_SESSION['class']) || empty($_SESSION['class'])) {
            $error_message = "Class session variable is not set.";
        } else {
            $class = $_SESSION['class'];
            $confirm_delete = sanitize_input($conn, $_POST['delete_all_confirm']);

            if ($confirm_delete === "yes") {

                // Checking if there are any students in the specified class
                $sql_check_students = "SELECT COUNT(*) AS student_count FROM students WHERE class = '$class'";
                $result_check = $conn->query($sql_check_students);
                $row_check = $result_check->fetch_assoc();

                if ($row_check['student_count'] > 0) {
                    // Performing deletion in both tables
                    $sql_delete_attendance = "DELETE FROM attendance_records WHERE roll_number IN (SELECT roll_number FROM students WHERE class = '$class')";
                    $sql_drop_class = "DELETE FROM students WHERE class = '$class'";
                    if ($conn->query($sql_delete_attendance) === TRUE && $conn->query($sql_drop_class) === TRUE) {
                        $success_message_all = "All student and associated attendance records for class $class dropped successfully.";
                    } else {
                        $error_message_all = "Error dropping class records: " . $conn->error;
                    }
                } else {
                    $error_message_all = "No student records found for class $class.";
                }
            } else {
                $error_message_all = "Deletion cancelled.";
            }
        }
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
    <title>Delete Records</title>
    <link rel="stylesheet" href="styles4.css">
    <style>
        form {
            max-width: 430px;
        }

        input[type='text'] {
            width: 80%;
            box-sizing: border-box;
        }

        input[type='submit'] {
            width: 80%;
            box-sizing: border-box;
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
            text-align: center;
            position: static;
            margin-top: 20px;
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
            .delete-form {
                margin-top: 35px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="myDiv">
            <img src="../img/logo.png" alt="Logo 1" class="logo-left">
            <h2>ABC School, XYZ</h2>
            <h3>DELETE STUDENT RECORDS</h3>
            <img src="../img/logo2.png" alt="Logo 2" class="logo-right">
        </div>

        <h2 style="text-align: center;">Hi, <?php echo $_SESSION['username']; ?></h2><img src='../img/Nerd Face.png' alt='Nerd Face' />

        <!-- Display Form for Deleting Records -->
        <div class="delete-form">
            <h2>Delete Student Record</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                class="delete-form-font-sizes">
                <label>Enter Roll Number to Delete:</label>
                <input type="text" name="roll_number" required>
                <input type="submit" name="delete_single" value="Delete" style="font-size: 1.3rem;">
            </form>

            <!-- Displaying Success or Error Messages for Single Record Deletion -->
            <?php if (!empty($success_message_single)) : ?>
                <div class="success-message"><?php echo $success_message_single; ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message_single)) : ?>
                <div class="error-message"><?php echo $error_message_single; ?></div>
            <?php endif; ?>

        </div>

        <div class="delete-form">
            <h2>Delete All Records for Class <?php echo htmlspecialchars($_SESSION['class']); ?></h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                class="delete-form-font-sizes">
                <p>Are you sure you want to delete all records for this class?</p>
                <input type="radio" id="yes" name="delete_all_confirm" value="yes" required>
                <label for="yes">Yes</label>
                <input type="radio" id="no" name="delete_all_confirm" value="no" required>
                <label for="no">No</label><br><br>
                <input type="submit" name="delete_all" value="Delete All Records" style="font-size: 1.3rem;">
            </form>

            <!-- Displaying Success or Error Messages for Class Deletion -->
            <?php if (!empty($success_message_all)) : ?>
                <div class="success-message"><?php echo $success_message_all; ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message_all)) : ?>
                <div class="error-message"><?php echo $error_message_all; ?></div>
            <?php endif; ?>

        </div>

        <!-- Logout Button -->
        <div class="logout">
            <form method="post" action="logout.php">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>