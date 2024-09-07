<?php include("../connect.php"); ?>

<?php

// Check if all required form fields are set
if (isset($_POST['username'], $_POST['pwd'], $_POST['n'], $_POST['d'], $_POST['c'], $_POST['s'], $_POST['t1'], $_POST['t2'], $_POST['t3'], $_POST['t4'], $_POST['t5'], $_POST['t6'], $_POST['t7'])) {
    $username = $_POST['username'];
    $password = $_POST['pwd'];
    $teacherName = $_POST['n'];
    $designation = $_POST['d'];
    $class = $_POST['c'];
    $subject = $_POST['s'];
    $homework1 = $_POST['t1'];
    $homework2 = $_POST['t2'];
    $homework3 = $_POST['t3'];
    $homework4 = $_POST['t4'];
    $homework5 = $_POST['t5'];
    $homework6 = $_POST['t6'];
    $homework7 = $_POST['t7'];

    // Prepare statement to retrieve user data
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User exists, fetch the password
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, insert homework details
            $homework_date = date('Y-m-d');  // Assuming today's date

            $insert_stmt = $conn->prepare("INSERT INTO homeworks (user_id, homework_date, teacher_name, designation, class, subject, homework1, homework2, homework3, homework4, homework5, homework6, homework7) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("issssssssssss", $user_id, $homework_date, $teacherName, $designation, $class, $subject, $homework1, $homework2, $homework3, $homework4, $homework5, $homework6, $homework7);

            if ($insert_stmt->execute()) {
                echo "<script>alert('Homework submitted successfully.');
                  window.location = 'homework_form.php';</script>";
                exit; // Stop further execution
            } else {
                echo "Error: " . $insert_stmt->error;
            }

            $insert_stmt->close();
        } else {
            echo "<script>alert('Invalid password.');
              window.history.back();</script>";
            exit; // Stop further execution
        }
    } else {
        echo "<script>alert('Invalid username.');
          window.history.back();</script>";
        exit; // Stop further execution
    }

    $stmt->close();
} else {
    // Alert for missing form fields
    echo "<script>alert('Please fill in all required fields.');
      window.history.back();</script>";
    exit; // Stop further execution
}

// Close database connection
$conn->close();
?>