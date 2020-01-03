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


?>
 </div>
 </html>
