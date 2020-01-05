<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "กรุณากรอกข้อมูล username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "กรุณากรอกข้อมูล password.";
    } else{
        $password = trim($_POST["password"]);
        //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
     
     // Set parameters
     $param_username = $username;
     
     // Check if username exists, if yes then verify password
     $json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/manager?apiKey='.MLAB_API_KEY.'&q={"username":{"$regex":"'.$param_username.'"}}');
     $data = json_decode($json);
     $isData=sizeof($data);
     if($isData >0){
        // มีข้อมูลผู้ใช้อยู่
     foreach($data as $rec){
        $username=$rec->username;
        $hashed_password=$rec->password;
         }
       if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: listcoupon.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "รหัสผ่านไม่ถูกต้อง";
                        }
      }else{
      $username_err = "ไม่มีข้อมูล Username นี้ครับ";
}
     
       
    }else{
     echo "กรุณากรอกข้อมูล Username และ Password";
    }
    
   
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login เข้าสู่ระบบจ่ายคูปองอาหารกลางวัน พัน.ขกท.</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
 <div class="container">
 <div class="page-header">
            <table><tr><td><img src="mibnlogo.png" width="50"></td><td><h2>Login เข้าสู่ระบบจ่ายคูปองอาหารกลางวัน พัน.ขกท.</h2></td></tr></table>
        </div>
    <div class="wrapper">

        <p>กรุณากรอกข้อมูลเพื่อ login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            
        </form>
    </div>  
 </div>
</body>
</html>
