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

</head>
<body>

    <!-- container -->
    <div class="container">

        <div class="page-header">
            <table><tr><td><img src="mibnlogo.png" width="50"></td><td> <h1>ลงทะเบียนรับคูปอง</h1></td></tr></table>
        </div>
	    <?php
	    $government_id='';
	    $name='';
	    $national_id='';
// กรณีได้รับข้อมูลหมายเลขประจำตัว และหมายเลขคูปองมาแล้ว
if(isset($_POST['coupon_id'])&&(isset($_POST['name']))){
	// รับค่าข้อมูลจาก POST ให้ตัวแปร
 $name =	htmlspecialchars(strip_tags($_POST['name']));
 $coupon_id =	htmlspecialchars(strip_tags($_POST['coupon_id']));

// นำข้อมูลเข้าเก็บในฐานข้อมูล
$collectionName = "coupon";
$obj =   '{"name":"'.$name.'","coupon_id":"'.$coupon_id.'","dateGetCoupon":"'.$dateTimeToday.'"}';

$coupon = new RestDB();
$returnValue = $coupon->insertDocument($collectionName,$obj);

        if($returnValue){
		   $message= "<div align='center' class='alert alert-success'>บันทึกการรับคูปองของ ".$name." หมายเลขคูปอง ".$coupon_id." เรียบร้อย</div>";
		   echo $message;

	        }else{
		   $message= "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกได้</div>";
		echo $message;
                 }
			$_SESSION["message"]=$message;
		   	header("location: search.php");
    			exit;
        // ยังไม่มีการโพสต์ข้อมูลจากแบบฟอร์ม
    }else{
        echo "<div align='center' class='alert alert-success'>".$dateTimeToday."</div>";

	// กรณีค้นหาจากหมายเลข
	if((isset($_POST['personel_id']))&&(!empty($_POST['personel_id']))){
		//echo " รับตัวแปรหมายเลข personel_id";
		$personel_id=htmlspecialchars(strip_tags($_POST['personel_id']));

		//echo " ดึงข้อมูลจากฐานข้อมูล by personel_id";
        $collectionName = "mibnpeople";
        $obj =  '{"id":'.$personel_id.'}';

         $coupon = new RestDB();
         $res = $coupon->selectDocument($collectionName,$obj);
		//ตรวจสอบว่าได้รับข้อมูลมาหรือไม่
		if($res){
			// ได้รับข้อมูลมาแล้ว - แยกข้อมูลลงอะเรย์
			foreach($res as $rec){
        		$name=$rec['rank']." ".$rec['name']." ".$rec['lastname'];
			}
		}else{
			$_SESSION["message"] = "<div align='center' class='alert alert-danger'>ไม่พบคนที่คุณค้นหา</div>";
   			 header("location: search.php");
    			exit;
		}// if($isData >0)

	}// end of if((isset($_POST['personel_id']))&&(!empty($_POST['personel_id']))){
        elseif((isset($_POST['name']))&&(!empty($_POST['name']))){
            $name=htmlspecialchars(strip_tags($_POST['name']));
            //echo " ดึงข้อมูลจากฐานข้อมูล by personel_id";
            $collectionName = "mibnpeople";
            $obj =  '{"name":{"$regex":"'.$name.'"}}';

             $coupon = new RestDB();
             $res = $coupon->selectDocument($collectionName,$obj);
            //ตรวจสอบว่าได้รับข้อมูลมาหรือไม่
            if($res){
                // ได้รับข้อมูลมาแล้ว - แยกข้อมูลลงอะเรย์
                foreach($res as $rec){
                    $name=$rec['rank']." ".$rec['name']." ".$rec['lastname'];
                }
            }else{
                $_SESSION["message"] = "<div align='center' class='alert alert-danger'>ไม่พบคนที่คุณค้นหา</div>";
                 header("location: search.php");
                    exit;
            }// if($isData >0)

        }

}  // end of if(isset($_POST['coupon_id'])&&isset($_POST['name']))

?>


<a href='listman.php' class='btn btn-primary m-r-1em'>รายชื่อกำลังพล</a>
      <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหา</a>
	    <a href='listcoupon.php' class='btn btn-primary m-r-1em'>คูปองที่รับไปแล้ว</a>
	    <a href='logout.php' class='btn btn-danger'>Logout</a>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>


        <tr>
            <td>ยศ ชื่อ สกุล</td>
            <td><input type='text' name='name' value="<?php echo $name;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>รหัสคูปอง</td>
            <td><input type='text' name='coupon_id' class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหาใหม่</a>

            </td>
        </tr>
    </table>
</form>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
