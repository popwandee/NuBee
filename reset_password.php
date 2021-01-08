<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(isset($_GET["userid"])){
    $userid = $_GET["userid"];
}elseif(isset($_POST["userid"])) {
    $userid = $_POST["userid"];
}else{
    $userid = "";
    $new_password_err = "Don't know user ID to update.";
}

// Include config file
require_once "config.php";
require_once "vendor/restdbclass.php";
require_once "vendor/autoload.php";
require_once "vendor/function.php";

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){


    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
echo "new password is ".$new_password;
        // Set parameters
        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
        echo "<br>Hash password is ".$param_password;echo "<br>Userid is ".$userid;
        // Prepare an update statement
        $obj =  array( "password" => $param_password);
        $collectionName = "mibnpeople";
        $updateman = new RestDB;
        $res = $updateman->updateDocument($collectionName, $userid, $obj);
        if($res){
            $_SESSION['message'] = $message = "Update password completed.";
            echo "Update password completed. Please logout and login again. <a href=logout.php>Logout </a>";
        }else{
            $_SESSION['message'] = $message = "Cannot update password. Try again or contact admin.";
        }
        echo $message;
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
      <?php include 'navigation.php';?>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="hidden" name="userid" value="<?php echo $userid;?>">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
