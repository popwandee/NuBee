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

	?>

    <!-- container -->
    <div class="container">

        <div class="page-header">
		<table><tr><td><img src="mibnlogo.png" width="50"></td><td> <h1>รายชื่อกำลังพลของหน่วย </h1></td></tr></table>
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
        $collectionName = "mibnpeople";
        $obj = '';

         $coupon = new RestDB();
         $res = $coupon->selectDocument($collectionName,$obj);

  if($res){

      echo "<table class='table table-hover table-responsive table-bordered'>";//start table
    //creating our table heading
    echo "<tr>";
        echo "<th>ลำดับ</th>";
        echo "<th>ยศ ชื่อ สกุล</th>";
    echo "</tr>";

    // retrieve our table contents
foreach($res as $rec){
        $id=$rec['id'];
        $name=$rec['rank'].' '.$rec['name'].' '.$rec['lastname'];


    // creating new table row per record
    echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$name}</td>";
    echo "</tr>";
}

// end table
echo "</table>";
}
         ?>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
