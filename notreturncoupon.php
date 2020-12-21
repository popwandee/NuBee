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
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>ระบบการจ่ายคูปองค่าอาหารกลางวัน พัน.ขกท.</title>

    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

    <!-- custom css -->
    <style>
    .m-r-1em{ margin-right:1em; }
    .m-b-1em{ margin-bottom:1em; }
    .m-l-1em{ margin-left:1em; }
    .mt0{ margin-top:0; }
    </style>

</head>
<body>
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
		<table><tr><td><img src="mibnlogo.png" width="50"></td><td> <h1>รายการรับคูปองสำหรับวันที่ <?php echo $dateGetCoupon;?> </h1></td></tr></table>
        </div>
        <a href='listman.php' class='btn btn-primary m-r-1em'>รายชื่อกำลังพล</a>
        <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหา</a>
        <a href='newcoupon.php' class='btn btn-primary m-r-1em'>ลงทะเบียนรับคูปอง</a>
        <a href='listcoupon.php' class='btn btn-primary m-r-1em'>คูปองที่รับไปแล้ว</a>
        <a href='notreturncoupon.php' class='btn btn-primary m-r-1em'>คูปองที่ยังไม่ส่งคืน</a>
        <a href='logout.php' class='btn btn-danger'>Logout</a>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ค้นหาวันที่ (yyyy-mm-dd)</td>
            <td><input type='text' name='dateGetCoupon' class='form-control' /></td>
            <td><input type='submit' value='ค้นหา' class='btn btn-primary' /></td>
        </tr>
    </table>
</form>

	    <!-- PHP code to read records will be here -->
        <?php
        $message = isset($_GET['message']) ? $_GET['message'] : "";
	    echo $message;
        $collectionName = "coupon";
        $obj = '{"statusCoupon":"ยังไม่ส่งคืน"}';

        $coupon = new RestDB();
        $res = $coupon->selectDocument($collectionName,$obj);

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
            $deactive_url="deactive.php?statusCoupon=ส่งคืนแล้ว&id=".$_id."&target=notreturncoupon";
            echo "<a href='$deactive_url'><font color='Green'>ส่งคืน Coupon แล้ว</color></font></a>";
        }else{
            $deactive_url="deactive.php?statusCoupon=ยังไม่ส่งคืน&id=".$_id."&target=notreturncoupon";
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
