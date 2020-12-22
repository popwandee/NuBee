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

     $coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : "";
     if($coupon_id!==""){
         $coupon_id= $_POST['coupon_id'];
         $obj = '{"dateGetCoupon":"'.$dateGetCoupon.'","coupon_id":'.$coupon_id.'}';
     }else{
         $obj = '{"dateGetCoupon":"'.$dateGetCoupon.'"}';
     }

	?>

    <!-- container -->

    <div class="container">

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<div class="form-row align-items-center">
    <div class="form-group row">
      <div class="form-group col-md-2">
        <label class="sr-only" for="dateGetCoupon">ค้นหาวันที่ (yyyy-mm-dd)</label>
        <input type="text" class="form-control" name="dateGetCoupon" id="dateGetCoupon" placeholder="ค้นหาวันที่ (yyyy-mm-dd)">
      </div>
      <div class="form-group col-md-2">
        <label class="sr-only" for="coupon_id">Coupon</label>
        <input type="text" class="form-control" name="coupon_id" id="coupon_id" placeholder="Coupon">
      </div>
      <div class="form-group col-md-2">
        <button type="submit" class="btn btn-primary mb-2">Submit</button>
      </div>
    </div>
</div>
</form>

	    <!-- PHP code to read records will be here -->
        <?php
        $message = isset($_GET['message']) ? $_GET['message'] : "";
	    echo $message;
        $collectionName = "coupon";
        $sort= 'coupon_id';
        $coupon = new RestDB();
        $res = $coupon->selectDocument($collectionName,$obj,$sort);

  if($res){

      echo "<table class='table table-hover table-responsive table-bordered'>";//start table
    //creating our table heading
    echo "<tr>";
        echo "<th>ลำดับ</th>";
        echo "<th>ยศ ชื่อ สกุล</th>";
        echo "<th>หมายเลขคูปอง</th>";
        echo "<th>วันที่รับคูปอง</th>";
        echo "<th>สถานะคูปอง</th>";
        echo "<th>ส่งคืนคูปอง</th>";
        echo "<th>ผิดพลาดลบออก</th>";
    echo "</tr>";

    // retrieve our table contents
    $id=0;
foreach($res as $rec){
        $_id=$rec['_id'];
        $id++;
        $name=$rec['name'];
		$coupon_id=$rec['coupon_id'];
		$statusCoupon=$rec['statusCoupon'];
		$dateGetCoupon=$rec['dateGetCoupon'];

    // creating new table row per record
    echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$name}</td>";
        echo "<td>{$coupon_id}</td>";
        echo "<td>{$dateGetCoupon}</td>";
        if($statusCoupon=="ยังไม่ส่งคืน"){
            echo "<td bgcolor='Red'><font color='white'>ยังไม่ส่งคืน Coupon</font></td>";
        }else{
            echo "<td bgcolor='Green'><font color='white'>ส่งคืนแล้ว</font></td>";
        }
        echo "<td>";
        if($statusCoupon=="ยังไม่ส่งคืน"){
            $deactive_url="deactive.php?statusCoupon=ส่งคืนแล้ว&id=".$_id;
            echo "<a href='$deactive_url'><font color='Green'>ส่งคืน Coupon แล้ว</color></font></a>";
        }else{
            $deactive_url="deactive.php?statusCoupon=ยังไม่ส่งคืน&id=".$_id;
            echo "<a href='$deactive_url'><font color='Orange'>แก้ไข/ยังไม่ส่งคืน</font></a>";
        }
        echo "</td>";
            // we will use this links on next part of this post
	$del_url="delete.php?id=".$_id;
            echo "<td><a href='$del_url'><font color='Red'>ลบออก</font></a></td>";
    echo "</tr>";
}
// end table
echo "</table>";

  }// if no records found
else{
    echo "<div align='center' class='alert alert-danger'>ยังไม่มีใครได้รับคูปองในวันนี้.</div>";
}
?>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
