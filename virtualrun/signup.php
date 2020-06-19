<?php

// Cloudinary
require '../vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require '../vendor/cloudinary/cloudinary_php/src/Uploader.php';
require '../vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "../config.php";// mlab
require_once "../vendor/autoload.php";
require_once "../vendor/function.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $telephone = "";
$username_err = $password_err = $confirm_password_err = $telephone_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  // get Fullname
  if(empty(trim($_POST["fullname"]))){$fullname = "";}else{$fullname=$_POST['fullname'];}
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "กรุณากรอก username ด้วยครับ";
    } else{
        // Set parameters
        $param_username = trim($_POST["username"]);
        // Prepare a select statement
        $json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/user?apiKey='.MLAB_API_KEY.'&q={"username":{"$regex":"'.$param_username.'"}}');
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
    // Validate telephone
    if(empty(trim($_POST["telephone"]))){
        $telephone_err = "กรุณากรอก หมายเลขโทรศัพท์ ด้วยครับ";
    } elseif(strlen(trim($_POST["telephone"])) < 10){
        $telephone_err = "รหัสผ่านจะต้องมี 10 ตัวเลข";
    } else{
        $telephone = trim($_POST["telephone"]);
    }
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($telephone_err)){


            // Set parameters
            $param_fullname = $fullname;
            $param_username = $username;
            $param_telephone = $telephone;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
           $newData = json_encode(array('fullname' => $param_fullname,
           'username' => $param_username,
			        'telephone' => $param_telephone,
			        'password' => $param_password,
   			        'type' => "สมาชิกทั่วไป"
			        ) );
           $opts = array('http' => array( 'method' => "POST",
                               'header' => "Content-type: application/json",
                               'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/virtualrun/collections/user?apiKey='.MLAB_API_KEY.'';
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="MI Bn Virtual Run">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>MI Bn virtual Run</title>
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
<script data-ad-client="ca-pub-0730772505870150" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body>
<?php include 'navigation.html';?>

  <div class="container theme-showcase" role="main">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>Sign Up : ลงทะเบียนเข้าใช้งานระบบ</h1>
      <p>Mi Bn Virtualrun</p>
    </div>

    <div class="page-header">
      <div class="panel panel-info">
        <div class="panel-heading">
        <h2></h2>
        <p>กรุณากรอกข้อมูลเพื่อสร้าง account.</p>
      </div>

        <div class="col-sm-4" >
      <div class="panel-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group <?php echo (!empty($fullname_err)) ? 'has-error' : ''; ?>">
              <label>ชื่อ นามสกุล</label>
              <input type="text" name="fullname" class="form-control" value="<?php echo $fullname; ?>">
              <span class="help-block"><?php echo $fullname_err; ?></span>
          </div>
          <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                  <label>หมายเลขโทรศัพท์</label>
                  <input type="text" name="telephone" class="form-control" value="<?php echo $telephone; ?>">
                  <span class="help-block"><?php echo $telephone_err; ?></span>
              </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password: อย่างน้อย 6 ตัวอักษร</label>
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
    </div> <!-- panel-body -->
</div> <!-- panel panel-primary-->
</div> <!-- col-sm-4 -->

    </div> <!-- panel panel-info -->
      </div> <!-- page-header -->

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

  <!-- Latest compiled and minified Bootstrap JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
