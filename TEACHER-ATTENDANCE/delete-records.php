<?php
session_start();

// Check if user is authenticated
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
                // Perform deletion
                $sql_delete_single = "DELETE FROM students WHERE roll_number = '$roll_number' AND class = '$class'";
                if ($conn->query($sql_delete_single) === TRUE) {
                    $success_message = "Student record deleted successfully.";
                } else {
                    $error_message = "Error deleting student record: " . $conn->error;
                }
            }
        } else {
            $error_message = "Error: Roll number not found.";
        }
    }

    // Handle dropping entire class records
    if (isset($_POST['delete_all'])) {
        // Check if class session variable is set and not empty
        if (!isset($_SESSION['class']) || empty($_SESSION['class'])) {
            $error_message = "Class session variable is not set.";
        } else {
            $class = $_SESSION['class'];
            $confirm_delete = sanitize_input($conn, $_POST['delete_all_confirm']);

            if ($confirm_delete === "yes") {
                // Perform deletion
                $sql_drop_class = "DELETE FROM students WHERE class = '$class'";
                if ($conn->query($sql_drop_class) === TRUE) {
                    $success_message = "All student records for class $class dropped successfully.";
                } else {
                    $error_message = "Error dropping class records: " . $conn->error;
                }
            } else {
                $error_message = "Deletion cancelled.";
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
    <title>Delete Records</title>
    <link rel="stylesheet" href="styles4.css"> <!-- Ensure your external stylesheet is linked -->
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
    </style>
</head>

<body>
    <div class="container">
        <div class="myDiv">
            <h2>ABC School, XYZ</h2>
            <h3>DELETE STUDENT RECORDS</h3>
        </div>

        <h2 style="text-align: center;">Hi, <?php echo $_SESSION['username']; ?></h2>

        <!-- Display Form for Deleting Records -->
        <div class="delete-form">
            <h2>Delete Student Record</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                class="delete-form-font-sizes">
                <label>Enter Roll Number to Delete:</label>
                <input type="text" name="roll_number" required>
                <input type="submit" name="delete_single" value="Delete" style="font-size: 1.3rem;">
            </form>
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
        </div>

        <!-- Display Success or Error Messages -->
        <?php if (!empty($success_message)) : ?>
        <div class="success-message"><?php echo $success_message; ?></div>
        <?php
        endif; ?>

        <?php if (!empty($error_message)) : ?>
        <div class="error-message"><?php echo $error_message; ?></div>
        <?php
        endif; ?>

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