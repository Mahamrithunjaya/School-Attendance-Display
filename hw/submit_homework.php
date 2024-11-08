<?php include("../connect.php"); ?>

<?php

// Checking if all required form fields are set
if (isset($_POST['username'], $_POST['pwd'], $_POST['n'], $_POST['d'], $_POST['c'], $_POST['s'], $_POST['t1'], $_POST['t2'], $_POST['t3'], $_POST['t4'])) {
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
  $pdf = $_FILES['pdf'];
  $picture = $_FILES['picture'];

  // Defining upload directories for PDF and picture files
  $pdfDir = "uploads/pdfs/";
  $pictureDir = "uploads/pictures/";

  // Creating directories if they do not exist
  if (!is_dir($pdfDir)) mkdir($pdfDir, 0777, true);
  if (!is_dir($pictureDir)) mkdir($pictureDir, 0777, true);

  // Preparing statement to retrieve user data
  $stmt = $conn->prepare("SELECT id, password, class, designation, subject FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    // User exists, fetch the password
    $stmt->bind_result($user_id, $hashed_password, $user_class, $user_designation, $user_subject);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashed_password)) {

      // Checking if the submitted class, designation, and subject matches the user's class

      if ($designation !== $user_designation) {
        echo "<script>alert('You are not authorized to submit homework with this designation.');
                  window.history.back();</script>";
        exit; // Stop further execution
      }

      if ($class !== $user_class) {
        echo "<script>alert('You are not authorized to submit homework for this class.');
                  window.history.back();</script>";
        exit; // Stop further execution
      }

      if ($subject !== $user_subject) {
        echo "<script>alert('You are not authorized to submit homework for this subject.');
                    window.history.back();</script>";
        exit; // Stop further execution
      }

      // Password is correct, insert homework details
      $homework_date = date('Y-m-d');  // Assuming today's date

      // File size limit in bytes (2MB)
      $maxFileSize = 2 * 1024 * 1024;

      // Initializing file paths
      $pdfPath = null;
      $picturePath = null;

      // Handling PDF validation and upload
      if ($pdf['size'] > 0) {
        // Checking file size
        if ($pdf['size'] > $maxFileSize) {
          echo "<p>PDF file size must be under 2MB.</p>";
          exit;
        }
        // Checking file type
        if (mime_content_type($pdf['tmp_name']) !== 'application/pdf') {
          echo "<p>Only PDF files are allowed for Homework PDF.</p>";
          exit;
        }
        $pdfPath = "uploads/pdfs/" . basename($pdf['name']);
        move_uploaded_file($pdf['tmp_name'], $pdfPath);
      }

      // Handling picture validation and upload
      if ($picture['size'] > 0) {
        // Check file size
        if ($picture['size'] > $maxFileSize) {
          echo "<p>Picture file size must be under 2MB.</p>";
          exit;
        }
        // Checking file type
        $allowedImageTypes = ["image/jpeg", "image/png", "image/jpg"];
        if (!in_array(mime_content_type($picture['tmp_name']), $allowedImageTypes)) {
          echo "<p>Only JPEG, PNG, or JPG images are allowed for Homework Picture.</p>";
          exit;
        }
        $picturePath = "uploads/pictures/" . basename($picture['name']);
        move_uploaded_file($picture['tmp_name'], $picturePath);
      }


      $insert_stmt = $conn->prepare("INSERT INTO homeworks (user_id, homework_date, teacher_name, designation, class, subject, homework1, homework2, homework3, homework4, pdf_path, picture_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $insert_stmt->bind_param("isssssssssss", $user_id, $homework_date, $teacherName, $designation, $class, $subject, $homework1, $homework2, $homework3, $homework4, $pdfPath, $picturePath);

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