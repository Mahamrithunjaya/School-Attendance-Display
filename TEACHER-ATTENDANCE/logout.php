<?php
session_start();
session_unset();
unset($_SESSION['username']);
session_destroy();
header("Location: upload_attendance.php");
exit();
?>