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
            <h1>ลงทะเบียนรับคูปอง</h1>
        </div>
<?php
define("MLAB_API_KEY", '6QxfLc4uRn3vWrlgzsWtzTXBW7CYVsQv');
$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime(); $datetime->setTimezone($tz_object); $dateTimeToday = $datetime->format('Y-m-d');
$name = "ยศ ชื่อ นามสกุล"; $government_id = "รหัสประจำตัว 10 หลัก" ; $org = "สังกัด" ;

// กรณียังไม่ได้รับหมายเลขคูปอง แต่ได้หมายเลขประจำตัวจากการค้นหามาแล้ว

if((!isset($_POST['coupon_id']))&&(isset($_POST['government_id']))){
// รับตัวแปรหมายเลขประจำตัว
$government_id=htmlspecialchars(strip_tags($_POST['government_id']));

// ดึงข้อมูลจากฐานข้อมูล
$json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/personel?apiKey='.MLAB_API_KEY.'&q={"government_id":'.$government_id.'}');
$data = json_decode($json);
$isData=sizeof($data);

//ตรวจสอบว่าได้รับข้อมูลมาหรือไม่
if($isData >0){
// ได้รับข้อมูลมาแล้ว - แยกข้อมูลลงอะเรย์
foreach($data as $rec){
        $name=$rec->name;
$government=$rec->government_id;
$org=$rec->org;
}
}else{
echo "ไม่พบข้อมูลหมายเลชประจำตัวนี้ กรุณาตรวจสอบอีกครั้ง";
}
}else{
//echo "อาจจะได้คูปองไอดีมาแล้ว หรือยังไม่ได้หมายเลขประจำตัวก็ได้";    
}
?>

      <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหา</a><a href='listcoupon.php' class='btn btn-primary m-r-1em'>คูปองที่รับไปแล้ว</a>
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
            <td>สังกัด</td>
            <td><input type='text' name='org' value="<?php echo $org;?>" class='form-control' /></td>
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
    <?php
// กรณีได้รับข้อมูลหมายเลขประจำตัว และหมายเลขคูปองมาแล้ว
if((isset($_POST['coupon_id']))&&(isset($_POST['government_id']))){
	// รับค่าข้อมูลจาก POST ให้ตัวแปร
 $name =	htmlspecialchars(strip_tags($_POST['name']));
 $government_id=htmlspecialchars(strip_tags($_POST['government_id']));
 $org =		htmlspecialchars(strip_tags($_POST['org']));
 $coupon_id =	htmlspecialchars(strip_tags($_POST['coupon_id']));

// นำข้อมูลเข้าเก็บในฐานข้อมูล
$newData = json_encode(array('government_id' => $government_id,
			     'name' => $name,
			     'org' => $org,
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
		   echo "<div align='center' class='alert alert-success'>บันทึกการรับคูปองเรียบร้อย</div>";
	        }else{
		   echo "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกได้</div>";
                 }
	
        // ยังไม่มีการโพสต์ข้อมูลจากแบบฟอร์ม
    }else{
        echo "<div align='center' class='alert alert-danger'>".$dateTimeToday."</div>";
    }
      ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
