<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$userinfo = isset($_SESSION["userinfo"]) ? $_SESSION["userinfo"] : "";
$admin = isset($userinfo['user_autho']['admin']) ? $userinfo['user_autho']['admin'] : false ;
// Include config file
require_once "../config.php";
require_once "../vendor/restdbclass.php";
require_once "../vendor/autoload.php";
require_once "../vendor/function.php";

// Cloudinary
require '../vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require '../vendor/cloudinary/cloudinary_php/src/Uploader.php';
require '../vendor/cloudinary/cloudinary_php/src/Api.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="MIBn Website">
    <title>ระบบคูปองอิเล็กทรอนิกส์ พัน.ขกท.</title>
    <link rel="icon" href="../mibnlogo.png">
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
    <?php include '../navigation.php';?>
    <div class="container theme-showcase" role="main">
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <h1>ระบบคูปองอิเล็กทรอนิกส์ พัน.ขกท.</h1>
            <table><tr><td><img src="../mibnlogo.png" width="50"></td>
            <td><h3>ระบบสารสนเทศเพื่อการสื่อสาร และประชาสัมพันธ์ ภายในหน่วย พัน.ขกท.</h3></td></tr></table>
        </div>
    </div>

    <!-- container -->
    <div class="container">
	   <?php
       if(isset($_SESSION['message'])){
           $message = $_SESSION['message'];
           $_SESSION['message']="";
       }else{
           $message = isset($_GET['message']) ? $_GET['message'] : "";
       }
      echo $message;


              if($admin||$coupon_manager){ // not submit form
                  ?>
                              <div class="card bg-info px-md-5 border" align="center" style="max-width: 120rem;">
                                  <div class="card border-success md-12" style="max-width: 100rem;">
                                      <div class="card-header"align="left">สร้าง/แจกจ่าย คูปองอิเล็กทรอนิกส์ พัน.ขกท.</div>
                                      <div class="card-body" align="left">
                                          <p class="card-text">
                                              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                                  <input type="hidden" name="newCoupon" value="true">
                                                  <div class="form-group row">
                                                      <div class="form-group col-md-6">
                                                          <label for="customer_name" class="col-sm-6 col-form-label">กำลังพลที่ได้รับสิทธิ์คูปอง</label>
                                                          <input class="form-control" name="customer_name" type="text">
                                                      </div>
                                                      <div class="form-group col-md-2">
                                                          <label for="value" class="col-sm-6 col-form-label">มูลค่า</label>
                                                          <input type="text" class="form-control" name="value" id="value" placeholder='35'>
                                                      </div>
                                                      <div class="form-group col-md-1">
                                                          <div class="col-sm-10">
                                                           <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                                           </div>
                                                      </div>
                                                  </div>
                                              </form>
                                              <!-- end of card div -->
                                          </p>
                                      </div>
                                  </div>
                              </div>
                              <?php

              } // end is not submit form

      $collectionName = "ecoupon";
      $obj = '{}';
      $sort= 'merchant_name';
      $coupon = new RestDB();
      $res = $coupon->selectDocument($collectionName,$obj,$sort);
      if($res){
          echo "<table class='table table-hover table-responsive table-bordered'>";//start table
          //creating our table heading
            echo "<tr>";
            echo "<th>ลำดับ</th>";
            echo "<th>วันเวลารับคูปอง</th>";
            echo "<th>ผู้ได้รับสิทธิ์คูปอง</th>";
            echo "<th>มูลค่า</th>";
            echo "<th>ชื่อร้านค้า</th>";
            echo "<th>วันเวลาที่ใช้คูปอง</th>";
            echo "<th>วันเวลาที่จ่ายคืนคูปอง</th>";
            echo "<th>ผู้สร้างและแจกจ่ายคูปอง</th>";
            echo "<th>ผู้บันทึกว่าจ่ายแล้ว</th>";
            echo "<th>สถานะคูปอง</th>";
            if($admin||$brkfund_manager){
                echo "<th>Update</th>";
                }
        echo "</tr>";
        // retrieve our table contents
        $id = $summary = 0;
    foreach($res as $rec){
        // creating new table row per record

        $datetime_create_coupon = date('Y-m-d H:m:s', strtotime($rec['datetime_create_coupon']));
        $customer_name = isset($rec['customer_name']) ? $rec['customer_name'] : "";
        $value = isset($rec['value']) ? $rec['value'] : 35;
        $merchant_name = isset($rec['merchant_name']) ? $rec['merchant_name'] : "";
        $coupon_status = isset($rec['coupon_status']) ? $rec['coupon_status'] : "Inactive";
        $datetime_used_coupon = isset($rec['datetime_used_coupon']) ? date('Y-m-d H:m:s', strtotime($rec['datetime_used_coupon'])):"";
        $datetime_paid_coupon = isset($rec['datetime_paid_coupon']) ? date('Y-m-d H:m:s', strtotime($rec['datetime_paid_coupon'])):"";
        $admin_create_coupon = isset($rec['admin_create_coupon']) ? $rec['admin_create_coupon'] : "";
        $admin_paid_coupon = isset($rec['admin_paid_coupon']) ? $rec['admin_paid_coupon'] : "";
        if($coupon_status=="Active"){
            $bgcolor="#85C1E9";
        }elseif($coupon_status=="Used"){
            $bgcolor="#717D7E";
        }else{
            $bgcolor="#EC7063";
        }
        echo "<tr bgcolor=$bgcolor>"; ?>
              <td><?php $id++; echo $id;?></td>
              <td><?php echo $datetime_create_coupon;?></td>
              <td><?php echo $customer_name;?></td>
              <td><?php echo $value;?></td>
              <td><?php echo $merchant_name;?></td>
              <td><?php echo $datetime_used_coupon;?></td>
              <td><?php echo $datetime_paid_coupon;?></td>
              <td><?php echo $admin_create_coupon;?></td>
              <td><?php echo $admin_paid_coupon;?></td>
              <td ><?php echo $coupon_status;?></td>
            <?php if($admin||$coupon_manager){ ?>
                <td><a href='coupondetail.php?action=editCoupon&_id=<?php echo $rec['_id'];?>'>Update</a></td>
                <?php
            }
        echo "</tr>";
    }// end foreache people

    // end table
    echo "</table>";
    }// end of get data from databases

    // add new Coupon
        // กรณีได้รับข้อมูลหมายเลขประจำตัว และหมายเลขคูปองมาแล้ว
        if(isset($_POST['newCoupon'])&($admin||$coupon_manager)){
	           // รับค่าข้อมูลจาก POST ให้ตัวแปร
            $datetime_create_coupon = $timeStamp;
            $value = isset($_POST['value']) ? $_POST['value'] : "";
            if(empty($value)){ $value = 35;}
            $coupon_status = "Active";
            $admin_create_coupon = $userinfo['name'];
            $customer_name = isset($_POST['customer_name']) ? htmlspecialchars(strip_tags($_POST['customer_name'])) : "";
            // นำข้อมูลเข้าเก็บในฐานข้อมูล
            $collectionName = "ecoupon";
            $obj =   '{ "datetime_create_coupon":"'.$datetime_create_coupon.'",
                        "value":"'.$value.'",
                        "admin_create_coupon":"'.$admin_create_coupon.'",
                        "coupon_status":"'.$coupon_status.'",
                        "customer_name":"'.$customer_name.'"
                        }';
            $coupon = new RestDB();
            $returnValue = $coupon->insertDocument($collectionName,$obj);

            if($returnValue){
		          $_SESSION['message']= "<div align='center' class='alert alert-success'>บันทึกข้อมูล เรียบร้อย</div>";

	            }else{
		            $_SESSION['message']= "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกได้</div>";
                 }
                 // Redirect user to welcome page

        }  // end of if(isset($_POST['coupon_id'])&&isset($_POST['name']))

        ?>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </body>
</html>
