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
$brkfund_manager = isset($userinfo['user_autho']['brkfund_manager']) ? $userinfo['user_autho']['brkfund_manager'] : false ;
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
    <title>กองทุน บรข.พัน.ขกท.</title>
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
            <h1>กองทุน บรข.พัน.ขกท.</h1>
            <table><tr><td><img src="../mibnlogo.png" width="50"></td>
            <td><h3>ระบบสารสนเทศเพื่อการสื่อสาร และประชาสัมพันธ์ ภายในหน่วย พัน.ขกท.</h3></td></tr></table>
        </div>
    </div>

    <!-- container -->
    <div class="container">
	   <?php
       if(isset($_SESSION['message'])){
           $message = $_SESSION['message'];
       }else{
           $message = isset($_GET['message']) ? $_GET['message'] : "";
       }

      echo $message;
      $collectionName = "brkfund";
      $obj = '{}';
      $sort= 'date';
      $coupon = new RestDB();
      $res = $coupon->selectDocument($collectionName,$obj,$sort);
      if($res){

          echo "<table class='table table-hover table-responsive table-bordered'>";//start table
        //creating our table heading
        echo "<tr>";
            echo "<th>ลำดับ</th>";
            echo "<th>วันเวลา</th>";
            echo "<th>รายการ</th>";
            echo "<th>รับ</th>";
            echo "<th>จ่าย</th>";
            echo "<th>คงเหลือ</th>";
            echo "<th>รายละเอียด</th>";
            if($admin||$brkfund_manager){
                echo "<th>Update</th>";
                }
        echo "</tr>";
        // retrieve our table contents
        $id = $summary = 0;
    foreach($res as $rec){
        // creating new table row per record

            $deposit = is_numeric($rec['deposit']) ? $rec['deposit'] : 0;
            $withdraw = is_numeric($rec['withdraw']) ? $rec['withdraw'] : 0;
            $date = date('Y-m-d', strtotime($rec['date']));
        echo "<tr>"; ?>
                <td><?php $id++; echo $id;?></td>
                <td><?php echo $date;?></td>
                <td><?php echo $rec['title'];?></td>
                <td><?php echo number_format($deposit);?></td>
                <td><?php echo number_format($withdraw);?></td>
                <td><?php $summary = $summary + $deposit - $withdraw; echo number_format($summary);?></td>
                <td><a href='recorddetail.php?action=showDetail&_id=<?php echo $rec['_id'];?>'>รายละเอียด</a></td>
                <?php if($admin||$brkfund_manager){ ?>
                    <td><a href='recorddetail.php?action=editRecord&_id=<?php echo $rec['_id'];?>'>Update</a></td>
                    <?php
            }
        echo "</tr>";
    }// end foreache people

    // end table
    echo "</table>";
    }// end of get data from databases

    // add new people
        // กรณีได้รับข้อมูลหมายเลขประจำตัว และหมายเลขคูปองมาแล้ว
        if(isset($_POST['newrecord'])&&(isset($_POST['title']))){
	           // รับค่าข้อมูลจาก POST ให้ตัวแปร
            $id = isset($_POST['id']) ? htmlspecialchars(strip_tags($_POST['id'])) : "";
            $date = isset($_POST['date']) ? htmlspecialchars(strip_tags($_POST['date'])) : "";
            $title = isset($_POST['title']) ? htmlspecialchars(strip_tags($_POST['title'])) : "";
            $detail = isset($_POST['detail']) ? htmlspecialchars(strip_tags($_POST['detail'])) : "";
            $deposit = isset($_POST['deposit']) ? htmlspecialchars(strip_tags($_POST['deposit'])) : "";
            $withdraw = isset($_POST['withdraw']) ? htmlspecialchars(strip_tags($_POST['withdraw'])) : "";
            $file = isset($_POST['file']) ? htmlspecialchars(strip_tags($_POST['telephone'])) : "";
            $summary = isset($_POST['summary']) ? htmlspecialchars(strip_tags($_POST['summary'])) : "";
            $recorder = isset($_POST['recorder']) ? htmlspecialchars(strip_tags($_POST['recorder'])) : "";
            // นำข้อมูลเข้าเก็บในฐานข้อมูล
            $collectionName = "brkfund";
            $obj =   '{"id":"'.$id.'","date":"'.$date.'","title":"'.$title.'", "detail":"'.$detail.'","deposit":"'.$deposit.'",
                "withdraw":"'.$withdraw.'", "file":"'.$file.'", "summary":"'.$summary.'", "recorder":"'.$recorder.'"}';

            $coupon = new RestDB();
            $returnValue = $coupon->insertDocument($collectionName,$obj);

            if($returnValue){
		          $message= "<div align='center' class='alert alert-success'>บันทึกข้อมูล เรียบร้อย</div>";
		         echo $message;
	            }else{
		            $message= "<div align='center' class='alert alert-danger'>ไม่สามารถบันทึกได้</div>";
		            echo $message;
                 }
			              $_SESSION["message"]=$message;


        }  // end of if(isset($_POST['coupon_id'])&&isset($_POST['name']))
        else{ // not submit form
            ?>
                        <div class="card bg-info px-md-5 border" align="center" style="max-width: 120rem;">
                            <div class="card border-success md-12" style="max-width: 100rem;">
                                <div class="card-header"align="left">ข้อมูลรายรับรายจ่าย กองทุน บรข.พัน.ขกท.</div>
                                <div class="card-body" align="left">
                                    <p class="card-text">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                            <input type="hidden" name="newrecord" value="true">
                                            <input type="hidden" name="recorder" value="<?php echo $userinfo['name'];?>">
                                            <div class="form-group row">
                                                <div class="form-group col-md-4">
                                                    <label for="id" class="col-sm-6 col-form-label">วัน เวลา</label>
                                                    <input class="form-control" name="date" data-type="date" type="date">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="form-group col-md-6">
                                                    <label for="title" class="col-sm-6 col-form-label">รายการ</label>
                                                    <input type="text" class="form-control" name="title" id="title">
                                                </div>
                                                </div>
                                            <div class="form-group row">
                                                <div class="form-group col-md-6">
                                                    <label for="detail" class="col-sm-6 col-form-label">รายละเอียด</label>
                                                    <input type="textarea" class="form-control" name="detail" id="detail">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="form-group col-md-6">
                                                    <label for="deposit" class="col-sm-6 col-form-label">ฝาก/นำส่งเงินเข้ากองทุน</label>
                                                    <input type="text" class="form-control" name="deposit" id="deposit">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="form-group col-md-6">
                                                    <label for="withdraw" class="col-sm-6 col-form-label">ถอน/จ่ายเงิน</label>
                                                    <input type="text" class="form-control" name="withdraw" id="withdraw">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
        ?>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </body>
</html>
