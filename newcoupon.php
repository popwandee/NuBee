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
$datetime = new DateTime(); $datetime->setTimezone($tz_object); $dateTimeToday = $datetime->format('Y\-m\-d\');
$name = "ยศ ชื่อ นามสกุล"; $government_id = "รหัสประจำตัว 10 หลัก" ; $organize = "สังกัด" ;

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
	      echo "No data from database";
      }
    }else{
	echo "อาจจะได้คูปองไอดีมาแล้ว หรือยังไม่ได้หมายเลขประจำตัวก็ได้";    
    }
?>
