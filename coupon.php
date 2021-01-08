<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Include config file
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>    <?php include 'navigation.php';?>
    <div class="page-header">
        <table><tr><td><img src="mibnlogo.png" width="50"></td><td><h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. ยินดีต้อนรับครับ</h1></td></tr></table>
    </div>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
    </p>
</body>
</html>
