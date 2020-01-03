<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "กรุณากรอก username ด้วยครับ";
    } else{
        // Set parameters
        $param_username = trim($_POST["username"]);
        // Prepare a select statement
        $json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/manager?apiKey='.MLAB_API_KEY.'&q={"username":{"$regex":"'.$param_username.'"}}');
        $data = json_decode($json);
        $isData=sizeof($data);
        //ตรวจสอบว่ามีชื่อผู้ใช้นี้อยู่แล้วหรือไม่
        if($isData >0){
            // มีชื่อผู้ใช้นี้อยู่แล้ว - 
            $username_err = "Username นี้มีอยู่แล้ว";
         }else{
            $username = trim($_POST["username"]);
              } 
          }
        
       
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "กรุณากรอก password ด้วยครับ";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "รหัสผ่านจะต้องมีอย่างน้อย 6 ตัวอักษร";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "กรุณา confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password ไม่ตรงกัน";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
       
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
           $newData = json_encode(array('username' => $param_username,
			        'password' => $param_password
			        ) );
           $opts = array('http' => array( 'method' => "POST",
                               'header' => "Content-type: application/json",
                               'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/nubee/collections/manager?apiKey='.MLAB_API_KEY.'';
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);
        if($returnValue){
		   echo "<div align='center' class='alert alert-success'>ลงทะเบียนเรียบร้อย</div>";
	        // Redirect to login page
                header("location: login.php");
        }else{
		           echo "<div align='center' class='alert alert-danger'>ไม่สามารถลงทะเบียนได้</div>";
                 }
               
           
        }
         
      
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>กรุณากรอกข้อมูลเพื่อสร้าง account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>มี account อยู่แล้วใช่ไหม? <a href="login.php">Login ที่นี่</a>.</p>
        </form>
    </div>    
</body>
</html>
