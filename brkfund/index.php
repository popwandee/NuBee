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
                    <?php
              echo "</tr>";
            }// end foreache people

            // end table
            echo "</table>";
          }// end of get data from databases
          ?>
        </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </body>
</html>
