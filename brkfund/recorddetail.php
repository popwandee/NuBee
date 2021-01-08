<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$userinfo = isset($_SESSION["userinfo"]) ? $_SESSION["userinfo"] : "";
$userinfo = isset($_SESSION["userinfo"]) ? $_SESSION["userinfo"] : "";
$admin = isset($userinfo['user_autho']['admin']) ? $userinfo['user_autho']['admin'] : false ;
$brkfund_manager = isset($userinfo['user_autho']['brkfund_manager']) ? $userinfo['user_autho']['brkfund_manager'] : false ;

$_id = isset($_GET["_id"]) ? $_GET["_id"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "";
$action = isset($_POST["action"]) ? $_POST["action"] : $action;
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
    <?php include 'navigation.html';?>
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
           $_SESSION['message'] = "";
       }else{
           $message = isset($_GET['message']) ? $_GET['message'] : "";
       }
      echo $message;
     $id = isset($id) ? $id : 0;
     echo "action is".$action;
switch ($action) {
    case "showdetail":
        showDetail($id);
    break;
    case "editRecord":
        $isPost = isset($_POST['isPost']) ? $_POST['isPost'] : FALSE;

        if($isPost){
            echo "yes post";
            $_id = isset($_POST['_id']) ? $_POST['_id'] : "";
            $date = isset($_POST['date']) ? $_POST['date'] : "";
            $title = isset($_POST['title']) ? $_POST['title'] : "";
            $detail = isset($_POST['detail']) ? $_POST['detail'] : "";
            if(isset($_POST['deposit'])){
                $deposit = is_numeric($_POST['deposit']) ? number_format($_POST['deposit']) : "";
            }else{$deposit = '';}
            if(isset($rec['withdraw'])){
                $withdraw = is_numeric($_POST['withdraw']) ? number_format($_POST['withdraw']) : "";
            }else{$withdraw =''; }
            $file = isset($_POST['file']) ? $_POST['file'] : "";
            $recorder = isset($_POST['recorder']) ? $_POST['recorder'] : "";
            $obj =  array(  "date" => $date,
            "title" => $title,
            "detail" => $detail,
            "deposit" => $deposit,
                            "withdraw" => $withdraw,
                            "file" => $file,
                            "recorder" => $recorder
                            );
echo "\obj is"; print_r($obj);
            $updateman = new RestDB;
            $res = $updateman->updateDocument($collectionName, $objectId, $obj);
            if($res){
                echo "Update complete";
            }
        }else{ echo "No, not from Form. Just form record list page";
            if(isset($_id)){
                echo "\n_id is ".$_id;
                $collectionName = "brkfund";
                $obj = '{"_id":"'.$_id.'"}';
                $coupon = new RestDB();
                $res = $coupon->selectDocument($collectionName,$obj);
                if($res){
                    showForm($res);
                }

            }else{
echo "\n No ID to edit";
            }
        }

    break;
    default:

    break;
}


        ?>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </body>
</html>
<?php
function showDetail($id){
    $collectionName = "brkfund";
    $obj = '{"_id":"'.$id.'"}';
    $sort= 'date';
    $coupon = new RestDB();
    $res = $coupon->selectDocument($collectionName,$obj,$sort);
    if($res){
        foreach($res as $rec){
          $date = isset($rec['date']) ? $rec['date'] : "";
          $title = isset($rec['title']) ? $rec['title'] : "";
          $detail = isset($rec['detail']) ? $rec['detail'] : "";
          if(isset($rec['deposit'])){
              $deposit = is_numeric($rec['deposit']) ? number_format($rec['deposit']) : "";
          }
          if(isset($rec['withdraw'])){
              $withdraw = is_numeric($rec['withdraw']) ? number_format($rec['withdraw']) : "";
          }
          $file = isset($rec['file']) ? $rec['file'] : "";
          $recorder = isset($rec['recorder']) ? $rec['recorder'] : "";
        ?>
        <div class="container">
            <div class="row align-items-start">
                <div class="col">
                    วันเวลา : <?php echo date('Y-m-d', strtotime($date));?>
                </div>
                <div class="col">
                    รายการ : <?php echo $title;?>
                </div>
                <div class="col">
                    รับ : <?php echo $deposit;?><br>
                    จ่าย : <?php echo $withdraw;?>
                </div>
            </div>
                <div class="row align-items-start">
                    <div class="col">
                        รายละเอียด : <?php echo $detail;?>
                    </div>
                    <div class="col">
                        รูปภาพ / ไฟล์ : <?php echo $file;?>
                    </div>
                    <div class="col">
                        ผู้บันทึก : <?php print_r($recorder);?>
                    </div>
                </div>
        </div>
                  <?php

  }// end foreache people

  }// end of get data from databases
}// end function showDetail

function showForm($res){
    echo "\n in showForm function";
    foreach ($res as $rec){
        $date_err=$title_err=$detail_err=$deposit_err=$withdraw_err="";
    ?>
    <div class="card bg-info px-md-5 border" align="center" style="max-width: 120rem;">
        <div class="card border-success md-12" style="max-width: 100rem;">
            <div class="card-header"align="left">ข้อมูลรายรับรายจ่าย กองทุน บรข.พัน.ขกท.</div>
            <div class="card-body" align="left">
                <p class="card-text">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <input type="hidden" name="newrecord" value="true">
                        <input type="hidden" name="recorder" value="<?php echo $userinfo['name'];?>">
                        <input type="hidden" name="_id" value="<?php echo $_id;?>">
                        <input type="hidden" name="isPost" value="true">
                        <input type="hidden" name="action" value="editRecord">
                        <div class="form-group row">
                            <div class="form-group col-md-4 <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
                                <label for="id" class="col-sm-6 col-form-label">วัน เวลา</label>
                                <input class="form-control" name="date" data-type="date" type="date" value="<?php echo $rec['date']; ?>">
                                <span class="help-block"><?php echo $date_err; ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-6 <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                                <label for="title" class="col-sm-6 col-form-label">รายการ</label>
                                <input type="text" class="form-control" name="title" id="title" value="<?php echo $rec['title']; ?>">
                                <span class="help-block"><?php echo $title_err; ?></span>
                            </div>
                            </div>
                        <div class="form-group row">
                            <div class="form-group col-md-6 <?php echo (!empty($detail_err)) ? 'has-error' : ''; ?>">
                                <label for="detail" class="col-sm-6 col-form-label">รายละเอียด</label>
                                <input type="textarea" class="form-control" name="detail" id="detail" value="<?php echo $rec['detail']; ?>">
                                <span class="help-block"><?php echo $detail_err; ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-6 <?php echo (!empty($deposit_err)) ? 'has-error' : ''; ?>">
                                <label for="deposit" class="col-sm-6 col-form-label">ฝาก/นำส่งเงินเข้ากองทุน</label>
                                <input type="text" class="form-control" name="deposit" id="deposit" value="<?php echo $rec['deposit']; ?>">
                                <span class="help-block"><?php echo $deposit_err; ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-6 <?php echo (!empty($withdraw_err)) ? 'has-error' : ''; ?>">
                                <label for="withdraw" class="col-sm-6 col-form-label">ถอน/จ่ายเงิน</label>
                                <input type="text" class="form-control" name="withdraw" id="withdraw" value="<?php echo $rec['withdraw']; ?>">
                                <span class="help-block"><?php echo $withdraw_err; ?></span>
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
}//end foreach
}// end function
?>
