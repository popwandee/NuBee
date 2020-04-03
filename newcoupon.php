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
if(isset($_POST['coupon_id'])&&(isset($_POST['government_id']))&&(isset($_POST['name']))){
	// รับค่าข้อมูลจาก POST ให้ตัวแปร
 $name =	htmlspecialchars(strip_tags($_POST['name']));
 $government_id=htmlspecialchars(strip_tags($_POST['government_id']));
 $coupon_id =	htmlspecialchars(strip_tags($_POST['coupon_id']));
 
// นำข้อมูลเข้าเก็บในฐานข้อมูล
$newData = json_encode(array('government_id' => $government_id,
			     'name' => $name,
			     'coupon_id' => $coupon_id,
			     'dateGetCoupon' => $dateTimeToday) );
$opts = array('http' => array( 'method' => "POST",
                               'header' => "Content-type: application/json",
                               'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/nubee/collections/coupon?apiKey='.MLAB_API_KEY.'';
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);

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

	// กรณีค้นหาจากหมายเลข 10 หลัก
	if((isset($_POST['government_id']))&&(!empty($_POST['government_id']))){
		//echo " รับตัวแปรหมายเลขประจำตัว";
		$government_id=htmlspecialchars(strip_tags($_POST['government_id']));

		//echo " ดึงข้อมูลจากฐานข้อมูล by government_id";
		$json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/personel?apiKey='.MLAB_API_KEY.'&q={"government_id":'.$government_id.'}');
		$data = json_decode($json);
		$isData=sizeof($data);
		//ตรวจสอบว่าได้รับข้อมูลมาหรือไม่
		if($isData > 0){
			// ได้รับข้อมูลมาแล้ว - แยกข้อมูลลงอะเรย์
			foreach($data as $rec){
        			$name=$rec->rank." ".$rec->name." ".$rec->lastname;
				$government_id=$rec->government_id;
				$national_id=$rec->national_id;

				}
			}else{
			$_SESSION["message"] = "<div align='center' class='alert alert-danger'>ไม่พบคนที่คุณค้นหา</div>";
   			 header("location: search.php");
    			exit;
		}// จบกรณีif($isData > 0)
	}elseif((isset($_POST['personel_id']))&&(!empty($_POST['personel_id']))){
		//echo " รับตัวแปรหมายเลข personel_id";
		$personel_id=htmlspecialchars(strip_tags($_POST['personel_id']));

		//echo " ดึงข้อมูลจากฐานข้อมูล by personel_id";
		$json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/personel?apiKey='.MLAB_API_KEY.'&q={"id":'.$personel_id.'}');
		$data = json_decode($json);
		$isData=sizeof($data);
		//ตรวจสอบว่าได้รับข้อมูลมาหรือไม่
		if($isData >0){
			// ได้รับข้อมูลมาแล้ว - แยกข้อมูลลงอะเรย์
			foreach($data as $rec){
        		$name=$rec->rank." ".$rec->name." ".$rec->lastname;
			$government_id=$rec->government_id;
			$national_id=$rec->national_id;

			}
		}else{
			$_SESSION["message"] = "<div align='center' class='alert alert-danger'>ไม่พบคนที่คุณค้นหา</div>";
   			 header("location: search.php");
    			exit;
		}// if($isData >0)
		
	}// end of if((isset($_POST['government_id']))&&(!empty($_POST['government_id']))){

}  // end of if(isset($_POST['coupon_id'])&&isset($_POST['government_id'])&&isset($_POST['name']))

?>


      <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหา</a>
	    <a href='listcoupon.php' class='btn btn-primary m-r-1em'>คูปองที่รับไปแล้ว</a>
	    <a href='logout.php' class='btn btn-danger'>Logout</a>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>รหัสประจำตัวข้าราชการทหาร</td>
            <td><input type='text' name='government_id' value="<?php echo $government_id;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>ยศ ชื่อ สกุล</td>
            <td><input type='text' name='name' value="<?php echo $name;?>" class='form-control' /></td>
        </tr>        
	<tr>
            <td>รหัสประจำตัวประชาชน</td>
            <td><?php echo $national_id;?></td>
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
