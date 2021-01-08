<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
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
    <?php include 'navigation.php';?>
    <div class="container theme-showcase" role="main">
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Smart MIBn</h1>
        <table><tr><td><img src="mibnlogo.png" width="50"></td>
            <td><h3>ระบบสารสนเทศเพื่อการสื่อสาร และประชาสัมพันธ์ ภายในหน่วย พัน.ขกท.</h3></td></tr></table>
      </div>
  </div>

    <!-- container -->
    <div class="container">
	    <?php
// กรณีได้รับข้อมูลหมายเลขประจำตัว และหมายเลขคูปองมาแล้ว
if(isset($_POST['man_id'])&&(isset($_POST['name']))){
	// รับค่าข้อมูลจาก POST ให้ตัวแปร
$man_id = isset($_POST['man_id']) ? htmlspecialchars(strip_tags($_POST['man_id'])) : "";
$rank = isset($_POST['rank']) ? htmlspecialchars(strip_tags($_POST['rank'])) : "";
$name = isset($_POST['name']) ? htmlspecialchars(strip_tags($_POST['name'])) : "";
$lastname = isset($_POST['lastname']) ? htmlspecialchars(strip_tags($_POST['lastname'])) : "";
$org = isset($_POST['org']) ? htmlspecialchars(strip_tags($_POST['org'])) : "";
$telephone = isset($_POST['telephone']) ? htmlspecialchars(strip_tags($_POST['telephone'])) : "";
$idline = isset($_POST['idline']) ? htmlspecialchars(strip_tags($_POST['idline'])) : "";
$twitter = isset($_POST['twitter']) ? htmlspecialchars(strip_tags($_POST['twitter'])) : "";
$email = isset($_POST['email']) ? htmlspecialchars(strip_tags($_POST['email'])) : "";
$password = isset($_POST['password']) ? htmlspecialchars(strip_tags($_POST['password'])) : "";
$hash_password = password_hash($password, PASSWORD_DEFAULT);
// นำข้อมูลเข้าเก็บในฐานข้อมูล
$collectionName = "mibnpeople";
$obj =   '{"id":"'.$man_id.'","rank":"'.$rank.'","name":"'.$name.'", "lastname":"'.$lastname.'","org":"'.$org.'",
    "telephone":"'.$telephone.'", "idline":"'.$idline.'", "twitter":"'.$twitter.'", "email":"'.$email.'", "password":"'.$password.'"}';

$coupon = new RestDB();
$returnValue = $coupon->insertDocument($collectionName,$obj);

        if($returnValue){
		          $message= "<div align='center' class='alert alert-success'>บันทึกข้อมูล ".$name." เรียบร้อย</div>";
		         echo $message;
	        }else{
		   $message= "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกได้</div>";
		         echo $message;
                 }
			$_SESSION["message"]=$message;


}  // end of if(isset($_POST['coupon_id'])&&isset($_POST['name']))

?>

    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
