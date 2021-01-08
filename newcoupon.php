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
$obj =   '{"name":"'.$name.'","coupon_id":"'.$coupon_id.'","dateGetCoupon":"'.$dateTimeToday.'", "statusCoupon":"ยังไม่ส่งคืน"}';

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
		   	echo "<br><a href='search.php'>ค้นหาเพื่อลงทะเบียนใหม่</a>";
    			//exit;
        // ยังไม่มีการโพสต์ข้อมูลจากแบบฟอร์ม
    }else{

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

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>

<tr>
    <td>วันที่</td>
    <td><input type='text' name='name' value="<?php echo $dateTimeToday;?>" class='form-control' /></td>
</tr>
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
