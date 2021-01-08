<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$userinfo = isset($_SESSION["userinfo"]) ? $_SESSION["userinfo"] : "";
$userid = isset($userinfo["userid"]) ? $userinfo["userid"] : "";
$name=isset($userinfo["name"]) ? $userinfo["name"] : "";
$email=isset($userinfo["email"]) ? $userinfo["email"] : "";
$admin=isset($userinfo['user_autho']["admin"]) ? $userinfo['user_autho']["admin"] : false;
$coupon_manager=isset($userinfo['user_autho']["coupon_manager"]) ? $userinfo['user_autho']["coupon_manager"] : false;
$virtualrun_manager=isset($userinfo['user_autho']["virtualrun_manager"]) ? $userinfo['user_autho']["virtualrun_manager"] : false;
$brkfund_manager=isset($userinfo['user_autho']["brkfund_manager"]) ? $userinfo['user_autho']["brkfund_manager"] :false;
$club_manager=isset($userinfo['user_autho']["club_manager"]) ? $userinfo['user_autho']["club_manager"] : false;
// Include config file
require_once "config.php";
require_once "vendor/restdbclass.php";
require_once "vendor/autoload.php";
require_once "vendor/function.php";
// Cloudinary
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';

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
    <?php include 'navigation.html';?>
    <div class="container theme-showcase" role="main">
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Smart MIBn</h1>
        <table><tr><td><img src="mibnlogo.png" width="50"></td>
            <td><h3>ระบบสารสนเทศเพื่อการสื่อสาร และประชาสัมพันธ์ ภายในหน่วย พัน.ขกท.</h3></td></tr></table>
      </div>
  </div>
 <?php
$tz_object = new DateTimeZone('Asia/Bangkok');
         $datetime = new DateTime();
         $datetime->setTimezone($tz_object);
         $dateTimeToday = $datetime->format('Y-m-d');
	 if(isset($_POST['dateGetCoupon'])){
    	$dateGetCoupon = $_POST['dateGetCoupon'];
        $_SESSION['dateGetCoupon'] = $dateGetCoupon;
	 }elseif(isset($_SESSION['dateGetCoupon'])){
        $dateGetCoupon = $_SESSION['dateGetCoupon'];
     }else{
        $dateGetCoupon = $dateTimeToday;
	 }
	?>

    <!-- container -->
    <div class="container">
        <div class="page-header">

        </div>


<div class="page-header">
  <h1>ยินดีต้อนรับกำลังพล กองพันข่าวกรองทางทหารทุกท่าน</h1>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="list-group">
      <span class="list-group-item active"><?php echo $name;?></span>
        <span class="list-group-item active"><?php echo $email;?></span>
      <span class="list-group-item">โทรศัพท์ <?php //echo $telephone;?></span>
      <?php if($admin){ ?>
        <span class="list-group-item">คุณคือ Admin ของระบบ</span>
      <?php }?>
      <?php if($coupon_manager){ ?>
        <span class="list-group-item">คุณได้รับสิทธิ์ผู้จัดการระบบคูปองอาหารกลางวัน</span>

      <?php }?>
      <?php if($virtualrun_manager){ ?>
        <span class="list-group-item">คุณได้รับสิทธิ์ผู้จัดการระบบ Virtual Run</span>

      <?php }?>
      <?php if($brkfund_manager){ ?>
        <span class="list-group-item">คุณได้รับสิทธิ์ผู้จัดการระบบ กองทุน บรข.พัน.ขกท.</span>

      <?php }?>
      <?php if($club_manager){ ?>
        <span class="list-group-item">คุณได้รับสิทธิ์ผู้จัดการระบบชมรม พัน.ขกท.</span>

      <?php }?>

      <a href="reset_password.php?userid=<?php echo $userid;?>" class="list-group-item">เปลี่ยนรหัสผ่าน</a>
    </div>
  </div><!-- /.col-sm-4 -->
  <div class="col-sm-4">
    <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">สถานะระยะทางวิ่ง</h3>
      </div>
      <div class="panel-body">

        <span class="list-group-item"> <?php //$sum=number_format($sum, 2);echo $sum;?> บาท</span>
      </div>
    </div>
  </div><!-- /.col-sm-4 -->
  <div class="col-sm-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">ประกาศ</h3>
      </div>
      <div class="panel-body">
        ข้อมูล
      </div>
    </div>
  </div><!-- /.col-sm-4 -->
</div>

    </div> <!-- end .container -->


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
