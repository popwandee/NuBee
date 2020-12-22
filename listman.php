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
    <?php include 'navigation.html';?>
    <div class="container theme-showcase" role="main">
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Smart MIBn</h1>
        <table><tr><td><img src="mibnlogo.png" width="50"></td>
            <td><h3>ระบบสารสนเทศเพื่อการสื่อสาร และประชาสัมพันธ์ ภายในหน่วย พัน.ขกท.</h3></td></tr></table>
      </div>
  </div>
 <?php
$tz_object = new DateTimeZone('Asia/Bangkok');
         $datetime = new DateTime();
         $datetime->setTimezone($tz_object);
         $dateTimeToday = $datetime->format('Y-m-d');

	?>

    <!-- container -->
    <div class="container">
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ค้นหาวันที่ (yyyy-mm-dd)</td>
            <td><input type='text' name='dateGetCoupon' class='form-control' /></td>
            <td><input type='submit' value='ค้นหา' class='btn btn-primary' /></td>
        </tr>
    </table>
</form>

	    <!-- PHP code to read records will be here -->
         <?php
         $message = isset($_GET['message']) ? $_GET['message'] : "";
	    echo $message;
        $collectionName = "mibnpeople";
        $obj = '{}';
        $sort= 'id';
        $coupon = new RestDB();
        $res = $coupon->selectDocument($collectionName,$obj,$sort);

  if($res){

      echo "<table class='table table-hover table-responsive table-bordered'>";//start table
    //creating our table heading
    echo "<tr>";
        echo "<th>ลำดับ</th>";
        echo "<th>ยศ ชื่อ สกุล</th>";
        echo "<th>สังกัด</th>";
        echo "<th>หมายเลขข้าราชการ</th>";
        echo "<th>หมายเลขประชาชน</th>";
        echo "<th>เลขสมาชิก บรข.</th>";
        echo "<th>Email</th>";
        echo "<th>Admin</th><th>Coupon Manager</th><th>Vrun Manager</th><th>BRK Manager</th><th>Club Manager</th>";

        if($admin){
            echo "<th>Update</th>";
            }
    echo "</tr>";
    // retrieve our table contents
foreach($res as $rec){
        $objectId=$rec['_id'];
        $id=$rec['id'];
        $name=$rec['rank'].' '.$rec['name'].' '.$rec['lastname'];
        $org=$rec['org'];
        $government_id=$rec['government_id'];
        $national_id=$rec['national_id'];
        $brkfund_id = isset($rec['brkfund_id']) ? $rec['brkfund_id'] : "";
        $username = isset($rec['username']) ? $rec['username'] : "";
        $isadmin = isset($rec['admin']) ? $rec['admin'] : false;
        $coupon_manager = isset($rec['coupon_manager']) ? $rec['coupon_manager'] : false;
        $virtualrun_manager = isset($rec['virtualrun_manager']) ? $rec['virtualrun_manager'] : false;
        $brkfund_manager = isset($rec['brkfund_manager']) ? $rec['brkfund_manager'] : false;
        $club_manager = isset($rec['club_manager']) ? $rec['club_manager'] : false;
        $email = isset($rec['email']) ? $rec['email'] : "";
        $password = isset($rec['password']) ? $rec['password'] : "";
    // creating new table row per record

    echo "<tr>"; ?>

            <td><?php echo $id;?></td>
            <td><?php echo $name;?></td>
            <td><?php echo $org;?></td>
            <td><?php echo $government_id;?></td>
            <td><?php echo $national_id;?></td>
            <td><?php echo $brkfund_id;?></td>
            <td><?php echo $email;?></td>
            <td><input type="checkbox" name="isadmin" <?php if($isadmin){ echo "checked";}?>></td>
            <td><input type="checkbox" name="coupon_manager" <?php if($coupon_manager){ echo "checked";}?>></td>
            <td><input type="checkbox" name="virtualrun_manager" <?php if($virtualrun_manager){ echo "checked";}?>></td>
            <td><input type="checkbox" name="brkfund_manager" <?php if($brkfund_manager){ echo "checked";}?>></td>
            <td><input type="checkbox" name="club_manager" <?php if($club_manager){ echo "checked";}?>></td>
            <?php if($admin){ ?>
                <td><a href='updateman.php?userid=<?php echo $objectId;?>'>Update</a></td>
                <?php
        }
    echo "</tr>";
}// end foreache people

// end table
echo "</table>";
}// end of get data from databases

// add new people
$id++;
if($userinfo['user_autho']['admin']=='admin'){
?>
<form action="newman.php" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr align='center'>
            <td>ลำดับที่</td><td>ยศ</td><td>ชื่อ</td><td>นามสกุล</td><td>สังกัด</td>
            <td>หมายเลขข้าราชการ</td><td>หมายเลขประชาชน</td><td>Email</td><td>Password</td><td>บันทึก</td></tr>
            <tr>
            <td><input type='text' name='man_id' value='<?php echo $id;?>' class='form-control' /></td>
            <td><input type='text' name='rank' class='form-control' /></td>
            <td><input type='text' name='name' class='form-control' /></td>
            <td><input type='text' name='lastname' class='form-control' /></td>
            <td><input type='text' name='org' class='form-control' /></td>
            <td><input type='text' name='government_id' class='form-control' /></td>
            <td><input type='text' name='national_id' class='form-control' /></td>
            <td><input type='text' name='email' class='form-control' /></td>
            <td><input type='text' name='password' class='form-control' /></td>
            <td><input type='submit' value='บันทึก' class='btn btn-primary' /></td>
        </tr>
    </table>
</form>
<?php
} // end is admin or not
 ?>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
