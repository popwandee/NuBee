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
	 if(!isset($_POST['dateGetCoupon'])){
		 $dateGetCoupon=$dateTimeToday;
	 }else{
		$dateGetCoupon=$_POST['dateGetCoupon'];	 
	 }
	?>
	
    <!-- container -->
    <div class="container">
  
        <div class="page-header">
		<table><tr><td><img src="mibnlogo.png" width="50"></td><td> <h1>รายการรับคูปองสำหรับวันที่ <?php echo $dateGetCoupon;?> </h1></td></tr></table>
        </div>
     <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหา</a>
	    <a href='newcoupon.php' class='btn btn-primary m-r-1em'>ลงทะเบียนรับคูปอง</a>
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
 $json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/coupon?q={"dateGetCoupon":{"$regex":"'.$dateGetCoupon.'"}}&apiKey='.MLAB_API_KEY);
 $data = json_decode($json);
 $isData=sizeof($data);
  if($isData >0){
      
      echo "<table class='table table-hover table-responsive table-bordered'>";//start table
    //creating our table heading
    echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>ยศ ชื่อ สกุล</th>";
        echo "<th>หมายเลขประจำตัว</th>";
        echo "<th>สังกัด</th>";
        echo "<th>คูปอง</th>";
        echo "<th>Action</th>";
    echo "</tr>";
     
    // retrieve our table contents
$id=0;
foreach($data as $rec){
	$id++;
                 $_id=$rec->_id;
	
	foreach($_id as $rec_id){
		$_id=$rec_id;
		
	}
                 $name=$rec->name;
		$government_id=$rec->government_id;
		$org=$rec->org;
		$coupon_id=$rec->coupon_id;
		$dateGetCoupon=$rec->dateGetCoupon;
			
     
    // creating new table row per record
    echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$name}</td>";
        echo "<td>{$government_id}</td>";
        echo "<td>{$org}</td>";
        echo "<td>{$coupon_id}</td>";
        echo "<td>";
            // we will use this links on next part of this post
	$del_url="delete.php?id=".$_id;
            echo "<a href='$del_url'>Delete</a>";
        echo "</td>";
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
