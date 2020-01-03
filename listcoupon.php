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
 
    <!-- container -->
    <div class="container">
  
        <div class="page-header">
            <h1>รายการรับคูปองสำหรับวันนี้</h1>
        </div>
     <a href='search.php' class='btn btn-primary m-r-1em'>ค้นหา</a><a href='listcoupon.php' class='btn btn-primary m-r-1em'>คูปองที่รับไปแล้ว</a>
        <!-- PHP code to read records will be here -->
         <?php
         define("MLAB_API_KEY", '6QxfLc4uRn3vWrlgzsWtzTXBW7CYVsQv');
$tz_object = new DateTimeZone('Asia/Bangkok');
         $datetime = new DateTime();
         $datetime->setTimezone($tz_object);
         $dateTimeNow = $datetime->format('Y\-m\-d\ H:i:s');
 $json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/coupon?apiKey='.MLAB_API_KEY);
 $data = json_decode($json);
 $isData=sizeof($data);
  if($isData >0){
      echo"System has data.".$dateTimeNow;
      
      echo "<table class='table table-hover table-responsive table-bordered'>";//start table
 
    //creating our table heading
    echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>ยศ ชื่อ สกุล</th>";
        echo "<th>หมายเลขประจำตัว</th>";
        echo "<th>คูปอง</th>";
        echo "<th>Action</th>";
    echo "</tr>";
     
    // retrieve our table contents
$id=0;
foreach($data as $rec){
                 $id++;
                 $name=$rec->name;
				         $government_id=$rec->government_id;
				         $coupon_id=$rec->coupon_id;
				         
     
    // creating new table row per record
    echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$name}</td>";
        echo "<td>{$government_id}</td>";
        echo "<td>{$coupon_id}</td>";
        echo "<td>";
            // read one record 
            echo "<a href='read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";
             
            // we will use this links on next part of this post
            echo "<a href='update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";
 
            // we will use this links on next part of this post
            echo "<a href='#' onclick='delete_user({$id});'  class='btn btn-danger'>Delete</a>";
        echo "</td>";
    echo "</tr>";
}
 
// end table
echo "</table>";

  }// if no records found
else{
    echo "<div class='alert alert-danger'>ยังไม่มีใครได้รับคูปองในวันนี้.</div>";
}

         ?>
    </div> <!-- end .container -->
     
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
   
<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
<!-- confirm delete record will be here -->
 
</body>
</html>
