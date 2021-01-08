<?php
// Include config file
require_once "config.php";
require_once "vendor/restdbclass.php";
// Define variables and initialize with empty values
$email = $password = $confirm_password = $account_id= "";
$email_err = $password_err = $confirm_password_err = $account_id_err = $sys_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate account id
    if(empty(trim($_POST["account_id"]))){
        $sys_error = $account_id_err = "กรุณากรอก id ด้วยครับ หากไม่ทราบ กรุณาติดต่อผู้ดูแลระบบ";
    } else{
        // Set parameters
        $param_account_id = trim($_POST["account_id"]);
        // Prepare a select statement
        $collectionName = "mibnpeople";
        $obj =  '{"id":'.$param_account_id.'}';
         $man = new RestDB();
         try{
             $res = $man->selectDocument($collectionName,$obj);
             throw new Exception("เลือกข้อมูลไม่ได้");
         }catch (Exception $e){

         }

         $objectId=0;
        //ตรวจสอบว่ามีชื่อผู้ใช้นี้อยู่แล้วหรือไม่
        if(!$res){
            // ไม่มีชื่อผู้ใช้นี้อยู่ -
            $account_id_err = "ไม่มีหมายเลขนี้ กรุณาติดต่อผู้ดูแลระบบ";
         }else{
             // ไม่มีชื่อผู้ใช้นี้อยู่ - ตรวจสอบ Email ว่ามีอยู่หรือไม่
             foreach($res as $rec){
                 if(empty($rec['email'])){
                     $objectId=$rec['_id'];
                 }else{
                         $account_id_err = "มี Email สำหรับบัญชีนี้อยู่แล้ว กรุณาติดต่อผู้ดูแลระบบ";
                 }
             }
            $account_id = trim($_POST["account_id"]);
              }
          }

    // Validate username
    if(empty(trim($_POST["email"]))){
        $email_err = "กรุณากรอก Email ด้วยครับ";
    } else{
        // Set parameters
        $email = trim($_POST["email"]);
        // Prepare a select statement
        $collectionName = "mibnpeople";
        $obj =  '{"email":"'.$email.'"}';
         $man = new RestDB();

             $res = $man->selectDocument($collectionName,$obj);

         if($res){
            $email_err = "Email นี้ถูกใช้สำหรับบัญชีอื่นแล้วครับ กรุณาติดต่อผู้ดูแลระบบ หรือหากลืมรหัสผ่านกรุณาคลิก <a href=forgotpassword.php></a>";
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
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){

            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            $collectionName = "mibnpeople";
                    $obj =  array(
                    "email" => $param_email,
                    "password" => $param_password
                    );
                    $updateman = new RestDB;
try
 {
     $res = $updateman->updateDocument($collectionName, $objectId, $obj);
     if($res){
       $_SESSION['message'] = "ลงทะเบียนเรียบร้อย";
        // Redirect to login page
        header("location: login.php");
     }else{
        throw new Exception();
        }
}catch(Exception $e){

   $message = $_SESSION['message'] = "ไม่สามารถลงทะเบียนได้ กรุณาติดต่อผู้ดูแลระบบ ";

    $system_log = $timeStamp.": User try to register with ID $account_id and Email $email ไม่สามารถลงทะเบียนได้ ".$e->getMessage();
   error_log($system_log."\n",3,"sys-errors.log");
   echo "<div align='center' class='alert alert-danger'>$message</div>";
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
        div{
            background-color: "#3399ff";
        }
    </style>
</head>
<body><div class="container" align="center" background-color="#3399ff">
<div class="page-header bg-info"> <br>
        <h2>Sign Up</h2>
    </div>
            <div class="wrapper bg-info" align="left">
        <p>กรุณากรอกข้อมูลเพื่อสร้าง account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($account_id_err)) ? 'has-error' : ''; ?>">
                    <label>หมายเลข</label>
                    <input type="text" name="account_id" class="form-control" value="<?php echo $account_id; ?>">
                    <span class="help-block"><?php echo $account_id_err; ?></span>
                </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
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
</div>
</body>
</html>
