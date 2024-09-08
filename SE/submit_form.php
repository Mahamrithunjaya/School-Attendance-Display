<?php include("../connect.php"); ?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['pwd'];
    $teacher_name = $_POST['name'];
    $designation = $_POST['des'];
    $month = $_POST['m'];
    $class = $_POST['c'];
    $subject = $_POST['s'];
    $topic = $_POST['t'];

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
            // Password is correct, insert the form data into the submissions table
            $submission_date = date('Y-m-d');
            $sql = "INSERT INTO se_submissions (user_id, teacher_name, designation, month, class, subject, topic, submission_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssss", $user_id, $teacher_name, $designation, $month, $class, $subject, $topic, $submission_date);

            if ($stmt->execute()) {
                echo "<script>alert('Homework submitted successfully.');
                  window.location = 'se_entry_teacher.php';</script>";
                exit; // Stop further execution
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
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