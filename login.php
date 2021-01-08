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
require_once "vendor/restdbclass.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "กรุณากรอกข้อมูล Email.";
    } else{
        $email = trim($_POST["email"]);
        //echo "<br>Email is ".$email;
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "กรุณากรอกข้อมูล password.";
        //echo "<br>empty password, password_err is ".$password_err;
    } else{
        $password = trim($_POST["password"]);
        //echo "<br>password is ".$password;
        //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Validate credentials
    if(empty($email_err) && empty($password_err)){

     // Set parameters
     $param_email = $email;

     // Check if username exists, if yes then verify password
     $collectionName = "mibnpeople";
     $obj =  '{"email":{"$regex":"'.$param_email.'"}}';
      $man = new RestDB();
      $res = $man->selectDocument($collectionName,$obj);

    // $json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/manager?apiKey='.MLAB_API_KEY.'&q={"username":{"$regex":"'.$param_username.'"}}');
    // $data = json_decode($json);
    // $isData=sizeof($data);
     if($res){
        // มีข้อมูลผู้ใช้อยู่
     foreach($res as $rec){
        $email=$rec['email'];
        $hashed_password=$rec['password'];
        $userinfo['name']=$rec['rank'].' '.$rec['name'].' '.$rec['lastname'];
        $userinfo['userid']=$rec['_id'];
        $userinfo['email']=$rec['email'];
        $userinfo['telephone']=$rec['telephone'];
        $userinfo['user_autho']['admin']=$rec['admin'];
        $userinfo['user_autho']['coupon_manager']=$rec['coupon_manager'];
        $userinfo['user_autho']['virtualrun_manager']=$rec['virtualrun_manager'];
        $userinfo['user_autho']['brkfund_manager']=$rec['brkfund_manager'];
        $userinfo['user_autho']['club_manager']=$rec['club_manager'];
         }
       if(password_verify($password, $hashed_password)){

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["email"] = $email;
                            $_SESSION["userinfo"] = $userinfo;

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "รหัสผ่านไม่ถูกต้อง";
                        }
      }else{
      $email_err = "ไม่มีข้อมูล Email นี้ครับ";
}


    }else{
     echo "กรุณากรอกข้อมูล Email และ Password";
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart OFFICE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
 <div class="container" align="center" background-color="#3399ff">
 <div class="page-header">
            <table><tr><td></td><td><h2>Login please!!!</h2></td></tr></table>
        </div>
    <div class="wrapper bg-info" align="left">
<?php
$message = isset($_SESSION['message']) ? $_SESSION['message'] : "";
echo $message;
?>
        <p>กรุณากรอกข้อมูลเพื่อ login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <div class="wrapper" align="left">
                <p>ยังไม่มีบัญชีผู้ใช้ <a href="signup.php">สมัครเข้าใช้ระบบ</a></p>
            </div>
        </form>
    </div>
 </div>
</body>
</html>
