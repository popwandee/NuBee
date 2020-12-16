<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    //header("location: index.php");
 echo "<br>go to index.php";
    exit;
}
 
// Include config file
require_once "config.php";
include "vendor/restdbclass.php";
 
echo "<br>Define variables and initialize with empty values";
$username = $password = "";
$username_err = $password_err = "";
 
echo "<br>Processing form data when form is submitted";
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    echo " <br> Check if username is empty";
     if(empty(trim($_POST["username"]))){
        $username_err = "กรุณากรอกข้อมูล username."; echo "<br>empty username, username_err is ".$username_err;
    } else{
        $username = trim($_POST["username"]);echo "<br>username is ".$username;
    }
 
 echo "<br>Check if password is empty"; 
    if(empty(trim($_POST["password"]))){
        $password_err = "กรุณากรอกข้อมูล password."; echo "<br>empty password, password_err is ".$password_err;
    } else{
        $password = trim($_POST["password"]);echo "<br>password is ".$password;
        //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

 echo " Validate credentials";
    if(empty($username_err) && empty($password_err)){
     
     echo "<br>nothing error, Set parameters";
     $param_username = $username;
     
     echo"<br>Check if username exists, if yes then verify password.";  
     
     $collectionName = "mibnmanager"; echo "<br>collection mibnmanager.";
     $obj ="";//  array("username" => "admin");
     $db = new RestDB();
     $res = $db->selectDocument($collectionName, $obj);
     print_r($res);
     $isData=sizeof($res);
     if($isData >0){
        echo "<br>มีข้อมูลผู้ใช้อยู่";
      
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
        $username_err = "ไม่มีข้อมูล Username นี้ในฐานข้อมูลครับ"; 
        echo $username_err;
     }

    }else{ echo "<br> กรุณากรอกข้อมูล Username และ Password"; }


} // end if server request method

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
