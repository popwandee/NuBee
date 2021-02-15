<?php
// Include config file
require_once "../config.php";
require_once "../vendor/restdbclass.php";
// Define variables and initialize with empty values
$merchant_email = $merchant_name = $merchant_telephone = $merchant_password = $confirm_merchant_password = "";
$merchant_email_err = $merchant_name_err = $merchant_telephone_err =$merchant_password_err = $confirm_merchant_password_err =  $sys_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["merchant_email"]))){
        $merchant_email_err = "กรุณากรอก Email ด้วยครับ";
    } else{
        // Set parameters
        $merchant_email = trim($_POST["merchant_email"]);
        // Prepare a select statement
        $collectionName = "merchant";
        $obj =  '{"merchant_email":"'.$merchant_email.'"}';
        $merchant = new RestDB();

             $res = $merchant->selectDocument($collectionName,$obj);

         if($res){
            $merchant_email_err = "Email นี้ถูกใช้สำหรับบัญชีอื่นแล้วครับ กรุณาติดต่อผู้ดูแลระบบ หรือหากลืมรหัสผ่านกรุณาติดต่อผู้ดูแลระบบ";
        }

          }

    // Validate password
    if(empty(trim($_POST["merchant_password"]))){
        $merchant_password_err = "กรุณากรอก password ด้วยครับ";
    } elseif(strlen(trim($_POST["merchant_password"])) < 6){
        $merchant_password_err = "รหัสผ่านจะต้องมีอย่างน้อย 6 ตัวอักษร";
    } else{
        $merchant_password = trim($_POST["merchant_password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_merchant_password"]))){
        $confirm_merchant_password_err = "กรุณา confirm password.";
    } else{
        $confirm_merchant_password = trim($_POST["confirm_merchant_password"]);
        if(empty($merchant_password_err) && ($merchant_password != $confirm_merchant_password)){
            $confirm_merchant_password_err = "Password ไม่ตรงกัน";
        }
    }

    // Check input errors before inserting in database
    if(empty($merchant_email_err) && empty($merchant_password_err) && empty($merchant_confirm_password_err)){

            // Set parameters
            $param_email = $merchant_email;
            $param_password = password_hash($merchant_password, PASSWORD_DEFAULT); // Creates a password hash
            $merchant_name = $_POST['merchant_name'];
            $merchant_telephone=$_POST['merchant_telephone'];
            $merchant_status=$_POST['merchant_status'];
            // Attempt to execute the prepared statement
            $collectionName = "merchant";
            $obj =   '{ "merchant_email":"'.$param_email.'",
                        "merchant_password":"'.$param_password.'",
                        "merchant_name":"'.$merchant_name.'",
                        "merchant_telephone":"'.$merchant_telephone.'",
                        "merchant_status":"'.$merchant_status.'"
                        }';

                    $merchant = new RestDB;
try
 {

     $merchant = new RestDB();
     $res = $merchant->insertDocument($collectionName,$obj);
     if($res){
       $message = $_SESSION['message'] = "ลงทะเบียนเรียบร้อย";
    ?>
       <div class="page-header bg-success"> <br>
               <h2><?php echo $message;?><a href="merchant.php">คลิก เพื่อล็อกอิน</a></h2>
           </div>
    <?php
     }else{
        throw new Exception();
        }
}catch(Exception $e){

   $message = $_SESSION['message'] = "ไม่สามารถลงทะเบียนได้ กรุณาติดต่อผู้ดูแลระบบ ";

    $system_log = $timeStamp.": User try to register with ID $merchant_name and Email $merchant_email ไม่สามารถลงทะเบียนได้ ".$e->getMessage();
   error_log($system_log."\n",3,"sys-errors.log");
   echo "<div align='center' class='alert alert-danger'>$message</div>";
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
    <meta name="description" content="MIBn Website">
    <title>Smart MIBn</title>
    <link rel="icon" href="mibnlogo.png">
    <!-- Latest compiled and minified Bootstrap CSS -->
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

    <!-- custom css -->
    <style>
    .m-r-1em{ margin-right:1em; }
    .m-b-1em{ margin-bottom:1em; }
    .m-l-1em{ margin-left:1em; }
    .mt0{ margin-top:0; }
    </style>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<?php include 'navigation.php';?>

    <div class="container" align="center" background-color="#3399ff">
<div class="page-header bg-info"> <br>
        <h2>Sign Up</h2>
    </div>
            <div class="wrapper bg-info" align="left">
        <p>กรุณากรอกข้อมูลเพื่อสมัครบัญชีร้านค้า</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($merchant_name_err)) ? 'has-error' : ''; ?>">
                <label>ชื่อร้านค้า</label>
                <input type="text" name="merchant_name" class="form-control" value="<?php echo $merchant_name; ?>">
                <span class="help-block"><?php echo $merchant_name_err; ?></span>
            </div>
                <div class="form-group <?php echo (!empty($merchant_telephone_err)) ? 'has-error' : ''; ?>">
                    <label>เบอร์โทร ร้านค้า</label>
                    <input type="text" name="merchant_telephone" class="form-control" value="<?php echo $merchant_telephone; ?>">
                    <span class="help-block"><?php echo $merchant_telephone_err; ?></span>
                </div>
            <div class="form-group <?php echo (!empty($merchant_email_err)) ? 'has-error' : ''; ?>">
                <label>Email ร้านค้า</label>
                <input type="text" name="merchant_email" class="form-control" value="<?php echo $merchant_email; ?>">
                <span class="help-block"><?php echo $merchant_email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($merchant_password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="merchant_password" class="form-control" value="<?php echo $merchant_password; ?>">
                <span class="help-block"><?php echo $merchant_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_merchant_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_merchant_password" class="form-control" value="<?php echo $confirm_merchant_password; ?>">
                <span class="help-block"><?php echo $confirm_merchant_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="hidden" class="btn btn-primary" name="merchant_status" value="Inactive">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>มี account อยู่แล้วใช่ไหม? <a href="merchant.php">Login ที่นี่</a>.</p>
        </form>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
