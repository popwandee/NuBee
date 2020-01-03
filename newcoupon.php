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
         $datetime = new DateTime();
         $datetime->setTimezone($tz_object);
         $dateTimeNow = $datetime->format('Y\-m\-d\ H:i:s');
    $name='name'; $government_id='รหัสประจำตัว 10 หลัก';
    if((!isset($_POST['coupon_id']))&&(isset($_POST['government_id']))){
        $government_id=htmlspecialchars(strip_tags($_POST['government_id']));
        $json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/personel?apiKey='.MLAB_API_KEY.'&q={"government_id":'.$government_id.'}');
                                     $data = json_decode($json);
                                     $isData=sizeof($data);
                                     if($isData >0){
                                       foreach($data as $rec){
                                         $name=$rec->name;
				       }
				     }
    }
	    echo $name;
	    ?>
      
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ยศ ชื่อ สกุล</td>
            <td><input type='text' name='name' value="<?php echo $name;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>รหัสประจำตัวข้าราชการทหาร</td>
            <td><input type='text' name='government_id' value="<?php echo $government_id;?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>รหัสคูปอง</td>
            <td><input type='text' name='coupon_id' class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />
            </td>
        </tr>
    </table>
</form>
          
    </div> <!-- end .container -->
    <?php
if((isset($_POST['coupon_id']))&&(isset($_POST['government_id']))){    
 $name=htmlspecialchars(strip_tags($_POST['name']));
 $government_id=htmlspecialchars(strip_tags($_POST['government_id']));
 $coupon_id=htmlspecialchars(strip_tags($_POST['coupon_id']));
echo "NAME :".$name;
echo "Government ID :".$government_id;
echo "COUPON ID :".$coupon_id;
     $newData = json_encode(array('government_id' => $government_id,'name'=> $name,'coupon_id'=>$coupon_id) );
                                $opts = array('http' => array( 'method' => "POST",
                                          'header' => "Content-type: application/json",
                                          'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/nubee/collections/coupon?apiKey='.MLAB_API_KEY.'';
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);
        if($returnValue){
		    $textReplyMessage= "บันทึกการรับคูปองเรียบร้อย";
	        }else{ $textReplyMessage= "ไม่สามารถบันทึกได้";
                 }
echo $textReplyMessage;
        // ยังไม่มีการโพสต์ข้อมูลจากแบบฟอร์ม
    }else{
        echo $dateTimeNow;
    }
      ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
   
<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</body>
</html>
