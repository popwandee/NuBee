<?php
// Initialize the session
session_start();

$merchant_loggedin = isset($_SESSION["merchant_loggedin"]) ? $_SESSION["merchant_loggedin"] : FALSE ;
if($merchant_loggedin){
    echo "Merchant loggedin is TRUE<br>";
}else{
    echo "Merchant loggedin is FALSE<br>";
}
if(isset($_SESSION["merchant_loggedin"])){
    echo "Merchant loggedin SESSION is set<br>";
}else{
    echo "Merchant loggedin SESSION is Not Set<br>";
}
// Include config file
require_once "../config.php";
require_once "../vendor/restdbclass.php";

// Define variables and initialize with empty values
$merchant_email = $merchant_password = "";
$merchant_email_err = $merchant_password_err = "";
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

</head>
<body>
<?php include 'navigation.php';?>
 <div class="container" align="left" background-color="#3399ff">
<?php
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    echo "Server Request method POST -> Check if username is empty <br>";
    if(empty(trim($_POST["merchant_email"]))){
        echo "Empty Email <br>";
        $merchant_email_err = "กรุณากรอกข้อมูล Email.";
    } else{
        echo "POST Email : ".$_POST["merchant_email"]."<br>";
        $merchant_email = trim($_POST["merchant_email"]);
    }

    echo "Check is password empty<br>";
    if(empty(trim($_POST["merchant_password"]))){
        echo "Empty password <br>";
        $merchant_password_err = "กรุณากรอกข้อมูล password.";
    } else{
        $merchant_password = trim($_POST["merchant_password"]);
        echo "POST password : ".$merchant_password."\n";
        //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    echo "Check is email and password error<br>";
    if(empty($merchant_email_err) && empty($merchant_password_err)){
        echo "Email and Password not empty<br>";
        echo "Set parameters email : $merchant_email<br>";
        $param_email = $merchant_email;

        echo "Check is email exists<br>";
        $collectionName = "merchant";
        $obj =  '{"merchant_email":{"$regex":"'.$param_email.'"}}';
        echo "Query : $obj<br>";
        $man = new RestDB();
        $res = $man->selectDocument($collectionName,$obj);

        if($res){
            echo "Get User form database <br>";
            foreach($res as $rec){
                $hashed_password=$rec['merchant_password'];
                $merchant_status=$rec['merchant_status'];
                $merchant_info['merchant_name']=$rec['merchant_email'];
                $merchant_info['merchant_name']=$rec['merchant_name'];
                $merchant_info['merchant_telephone']=$rec['merchant_telephone'];
                }
            echo "Validate credentials: Check is password correct<br>";
            if(password_verify($merchant_password, $hashed_password)){
                echo "OK, Password is correct<br>";
                $_SESSION["merchant_loggedin"] = true;
                $_SESSION["merchant_info"] = $merchant_info;
                if($merchant_status=="Active"){
                    echo "Merchant status is Active<br>";
                    echo "Store data in session variables<br>";
                    echo "Merchant info is ";print_r($merchant_info);echo "<br>";
                    echo "Next <a href=merchant.php>Click for merchant page</a><br>";
                    }else{
                        $merchant_email_err = "ร้านค้านี้ยังไม่ได้รับอนุมัติครับ";
                        echo "Merchant status is Inactive<br>";
                        echo "ร้านค้านี้ยังไม่ได้รับอนุมัติครับ, กรุณาติดต่อผู้ดูแลระบบ<br>";

                        echo "Next <a href=merchant.php>Click for merchant page</a><br>";
                        showMerchantInfo($merchant_info);
                    }
                }else{ echo "รหัสผ่านไม่ถูกต้อง<br>";
                    echo "Display an error message: password is not valid<br>";
                    $merchant_password_err = "รหัสผ่านไม่ถูกต้อง";
                    } // สิ้นสุดรหัสผ่านไม่ถูกต้อง
            }else{
                echo "ไม่พบ Email นี้ในฐานข้อมูล<br>";
                $merchant_email_err = "ไม่มีข้อมูล Email สำหรับร้านค้านี้ครับ";
                echo "Next <a href=merchant.php>Click for merchant page</a><br>";
                }
        }else{
            echo "กรุณากรอกข้อมูล Email และ Password";
            }
    }// end if $_POST
    elseif($merchant_loggedin){
        echo "Not from POST But Loggedin<br>";
        $merchant_info=$_SESSION['merchant_info'];
        echo "Show merchant information<br>";print_r($merchant_info);echo "<br>";
        showMerchantInfo($merchant_info);
        }// end elseif isset merchant_loggedin
    else{
        echo "Not form POST and Not Loggedin <br>";
        echo "show login form<br>";
        ?>
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
               <div class="form-group <?php echo (!empty($merchant_email_err)) ? 'has-error' : ''; ?>">
                   <label>Email ร้านค้า</label>
                   <input type="text" name="merchant_email" class="form-control" value="<?php echo $merchant_email; ?>">
                   <span class="help-block"><?php echo $merchant_email_err; ?></span>
               </div>
               <div class="form-group <?php echo (!empty($merchant_password_err)) ? 'has-error' : ''; ?>">
                   <label>รหัสผ่าน</label>
                   <input type="password" name="merchant_password" class="form-control">
                   <span class="help-block"><?php echo $merchant_password_err; ?></span>
               </div>
               <div class="form-group">
                   <input type="submit" class="btn btn-primary" value="Login">
               </div>
               <div class="wrapper" align="left">
                   <p>ยังไม่มีบัญชีผู้ใช้ <a href="merchant_signup.php">สมัครเข้าใช้ระบบ</a></p>
               </div>
           </form>
       </div>
<?php } ?> <!-- end show login form-->

<?php function showMerchantInfo($merchant_info){

    echo "in the show Merchant Info<br>";
print_r($merchant_info);

} // end function Merchant
?>
 </div>

 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

 <!-- Latest compiled and minified Bootstrap JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

 </body>
 </html>
