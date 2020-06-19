<?php
// Initialize the session
session_start();


// Cloudinary
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "config.php";// mlab
require_once "vendor/autoload.php";
require_once "vendor/function.php";
/*
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
*/
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="AFAPS40-CRMA51 Website">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>MI Bn Virtualrun</title>
    <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

      <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
      <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
      <script src="vendor/bootstrap/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
  <?php include 'navigation.html';?>
  <?php
// Define variables and initialize with empty values
$username_err = $old_password_err = $password_err = $confirm_password_err = "";
$user_info = isset($_SESSION["user_info"]) ? $_SESSION["user_info"] : "";
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";
//echo "User info is ".$user_info; echo "\n Username is ".$username;
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  //echo "\n Validate username"; // username is telephone number store as field Tel1
  if(empty(trim($_POST["username"]))){
      $username_err = "ไม่มี username ครับ";
  }else{
      //echo "ตรวจสอบว่ามีชื่อผู้ใช้นี้อยู่แล้วหรือไม่";
      $param_username = trim($_POST["username"]);//echo "\n Set parameters username is";echo $param_username;
      // Prepare a select statement
      $json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/user?apiKey='.MLAB_API_KEY.'&q={"Tel1":{"$regex":"'.$param_username.'"}}');
      $data = json_decode($json);
      $isData=sizeof($data);

      if($isData >0){
          //echo "\n Got data form db";
          foreach($data as $rec){
            $password_db=$rec->password;
            $_id=$rec->_id;
          foreach($_id as $rec_id){
            $user_id=$rec_id;//echo "user_id is ".$user_id;
          }
        }
       }else{ //"\n ไม่มี username นี้ในฐานข้อมูลครับ";
          $username_err = "\n ไม่มี username นี้ในฐานข้อมูลครับ";
            }

        }
        //echo "ตรวจสอบ username แล้ว ต่อไป";
        //echo "\n Validate old password and compare with password from db";
        if(empty(trim($_POST["old_password"]))){
          $old_password_err = "กรุณากรองรหัสผ่านเดิมด้วยค่ะ";//echo $old_password_err;
        }else{
          $old_password=trim($_POST['old_password']);
          //echo "\n Compare password_db with old_password";
          //echo "\n รหัสผ่านจากฐานข้อมูลคือ ".$password_db;
          //echo "\n รหัสผ่าน Old Password จากผู้ใช้คือ ".$old_password;
          if(password_verify($old_password, $password_db)){
            //echo "รหัสผ่านเดิม ถูกต้อง ตรงกับฐานข้อมูล";
          }else{
            $old_password_err="รหัสผ่านเดิมไม่ถูกต้องค่ะ";
            //$old_password=$old_password_post;// คืนค่ารหัสผ่านเดิม (ไม่ hash)
          }
        }
        //
  //echo "\nValidate password";
    if(empty(trim($_POST["password"]))){
        $password_err = "กรุณากรอก password ด้วยครับ";
    }elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "รหัสผ่านจะต้องมีอย่างน้อย 6 ตัวอักษร";
    }else{
        $password = trim($_POST["password"]);
    }
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "กรุณา confirm password.";
    }else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password ไม่ตรงกัน";
        }
    }
    // Check input errors before inserting in database
    if(empty($username_err) && isset($user_id) && empty($old_password_err) && empty($password_err) && empty($confirm_password_err)){
            //echo "\n Everything pass,next Set parameters";
            $param_user_id = $user_id;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            //echo "\n Attempt to execute the prepared statement";
            $newData = '{ "$set" : { "password" : "'.$param_password.'" } }';

            $opts = array('http' => array( 'method' => "PUT",
                                           'header' => "Content-type: application/json",
                                           'content' => $newData
                                                       )
                                                    );

            $url = 'https://api.mlab.com/api/1/databases/virtualrun/collections/user/'.$user_id.'?apiKey='.MLAB_API_KEY.'';
                    $context = stream_context_create($opts);
                    $returnValue = file_get_contents($url,false,$context);
                    if($returnValue){
                      $_SESSION['message']='=>เปลี่ยนรหัสผ่านสำเร็จ.';
                      $old_password=$password=$confirm_password='';
                   		   header("location: logout.php");
            	        }else{
                      $_SESSION['message']='=>เปลี่ยนรหัสผ่าน ไม่สำเร็จ.';
                             }

      }//end check parameter
}
?>


  <div class="container theme-showcase" role="main">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>MI Bn Virtualrun</h1>
      <p>ชมรมวิ่งเพื่อสุขภาพ</p>

      <p><?php $message= isset($_SESSION['message']) ? $_SESSION['message'] :"" ;?></p>
      <div class="page-header">
        <div class="panel panel-info">
          <div class="panel-heading">
              <h2>แก้ไขรหัสผ่าน </h2>
              <p><?php echo $message;$_SESSION['message']='';?></p>
          </div>
          <div class="row">
            <div class="col-sm-4" >
          <div class="panel-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group <?php echo (!empty($user_info_err)) ? 'has-error' : ''; ?>">
              <label>ชื่อ นามสกุล <?php echo $user_info; ?></label>
          </div>
          <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username <?php echo $username; ?></label>
                <input type="hidden" name="username" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($old_password_err)) ? 'has-error' : ''; ?>">
                <label>Old Password:</label>
                <input type="password" name="old_password" class="form-control" value="<?php echo $old_password; ?>">
                <span class="help-block"><?php echo $old_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>New Password: อย่างน้อย 6 ตัวอักษร</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm new Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
              </div>
        </form>
          </div> <!-- panel-body -->
        </div> <!-- col-sm-4 -->
    </div> <!-- panel panel-info -->
      </div> <!-- page-header -->
    </div> <!-- jumbotron -->
      </div> <!-- container theme-showcase -->
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

  <!-- Latest compiled and minified Bootstrap JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
