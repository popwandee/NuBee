<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$userinfo = isset($_SESSION["userinfo"]) ? $_SESSION["userinfo"] : "";
if(!isset($userinfo['user_autho']['admin']) || $userinfo['user_autho']['admin']!==true){
    $_SESSION['message']="You don't have right to access page.";
    header("location : index.php");
    exit;
}
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
    <?php include 'navigation.php';?>
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
	    <!-- PHP code to read records will be here -->
         <?php  $message = isset($_GET['message']) ? $_GET['message'] : ""; echo $message;
        $collectionName = "mibnpeople";
        $source = isset($_POST['source']) ? $_POST['source'] : "listman";
        $objectId = isset($_POST['objectId']) ? $_POST['objectId'] : "";
if(isset($objectId)&isset($_POST['submit'])){
    if(!empty($objectId)){

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $rank = isset($_POST['rank']) ? $_POST['rank'] : "";
        $name = isset($_POST['name']) ? $_POST['name'] : "";
        $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
        $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : "";
        $idline = isset($_POST['idline']) ? $_POST['idline'] : "";
        $twitter = isset($_POST['twitter']) ? $_POST['twitter'] : "";
        $brkfund_id = isset($_POST['brkfund_id']) ? $_POST['brkfund_id'] : "";
        $org = isset($_POST['org']) ? $_POST['org'] : "";
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        if(isset($_POST['isadmin'])){
            $isadmin = $_POST['isadmin'];
            if($isadmin){$isadmin=true;}else{$isadmin=false;}
        }else{$isadmin=false;}
        if(isset($_POST['coupon_manager'])){
            $coupon_manager = $_POST['coupon_manager'];
            if($coupon_manager){$coupon_manager=true;}else{$coupon_manager=false;}
        }else{$coupon_manager=false;}
        if(isset($_POST['virtualrun_manager'])){
            $virtualrun_manager = $_POST['virtualrun_manager'];
            if($virtualrun_manager){$virtualrun_manager=true;}else{$virtualrun_manager=false;}
        }else{$virtualrun_manager=false;}
        if(isset($_POST['brkfund_manager'])){
            $brkfund_manager = $_POST['brkfund_manager'];
            if($brkfund_manager){$brkfund_manager=true;}else{$brkfund_manager=false;}
        }else{$brkfund_manager=false;}
        if(isset($_POST['club_manager'])){
            $club_manager = $_POST['club_manager'];
            if($club_manager){$club_manager=true;}else{$club_manager=false;}
        }else{$club_manager=false;}
        $obj =  array(  "id" => $id,
        "rank" => $rank,
        "name" => $name,
        "lastname" => $lastname,
                        "telephone" => $telephone,
                        "idline" => $idline,
                        "twitter" => $twitter,
                        "org" => $org,
                        "brkfund_id" => $brkfund_id,
                        "admin" => $isadmin,
                        "coupon_manager" => $coupon_manager,
                        "virtualrun_manager" => $virtualrun_manager,
                        "brkfund_manager" => $brkfund_manager,
                        "club_manager" => $club_manager,
                        "email" => $email);

        $updateman = new RestDB;
        $res = $updateman->updateDocument($collectionName, $objectId, $obj);

        if($res){
            $_SESSION['message'] = $message = "Update people Completed";
            echo $message;
        }else{
            $_SESSION['message']= $message = "Can not update people !!!";
            echo $message;
        }
        showListman();
}

}else{ // not submit form
    if(isset($_GET['userid'])){
        $userid = $_GET['userid'];
        $collectionName = "mibnpeople";
        $obj = '{"_id":"'.$userid.'"}';
        $coupon = new RestDB();
        $res = $coupon->selectDocument($collectionName,$obj);

        if($res){
            foreach ($res as $rec){
                $id = isset($rec['id']) ? $rec['id'] : "";
                $rank = isset($rec['rank']) ? $rec['rank'] : "";
                $name = isset($rec['name']) ? $rec['name'] : "";
                $lastname = isset($rec['lastname']) ? $rec['lastname'] : "";
                $telephone = isset($rec['telephone']) ? $rec['telephone'] : "";
                $idline = isset($rec['idline']) ? $rec['idline'] : "";
                $twitter = isset($rec['twitter']) ? $rec['twitter'] : "";
                $brkfund_id = isset($rec['brkfund_id']) ? $rec['brkfund_id'] : "";
                $org = isset($rec['org']) ? $rec['org'] : "";
                $isadmin = isset($rec['isadmin']) ? $rec['isadmin'] : false;
                $coupon_manager = isset($rec['coupon_manager']) ? $rec['coupon_manager'] : false;
                $virtualrun_manager = isset($rec['virtualrun_manager']) ? $rec['virtualrun_manager'] : false;
                $brkfund_manager = isset($rec['brkfund_manager']) ? $rec['brkfund_manager'] : false;
                $club_manager = isset($rec['club_manager']) ? $rec['club_manager'] : false;
                $email = isset($rec['email']) ? $rec['email'] : "";

                // show form
                ?>
<div class="card bg-info px-md-5 border" style="max-width: 150rem;" align="center">
<div class="card border-success md-12" style="max-width: 100rem;">
  <div class="card-header">ข้อมูลกำลังพล</div>
  <div class="card-body">
    <h5 class="card-title">การแก้ไขข้อมูลกำลังพล</h5>
    <p class="card-text">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <input type="hidden" name="source" value="listman">
    <input type="hidden" name="objectId" value="<?php echo $rec['_id'];?>">
  <div class="form-group row">
    <div class="form-group col-md-1">
      <label for="id">ID</label>
      <input type="text" class="form-control" name="id" id="id" value="<?php echo $id;?>"placeholder="<?php echo $rec['id'];?>">
    </div>
    <div class="form-group col-md-1">
      <label for="rank">Rank</label>
      <input type="text" class="form-control" name="rank" id="rank" value="<?php echo $rank;?>">
    </div>
    <div class="form-group col-md-2">
      <label for="name">Name</label>
      <input type="text" class="form-control" name="name" id="name" value="<?php echo $name;?>">
    </div>
    <div class="form-group col-md-2">
      <label for="lastname">Lastname</label>
      <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $lastname;?>">
    </div>
  </div>
  <div class="form-group row">
      <div class="form-group col-md-6">
      <label for="org" class="col-sm-2 col-form-label">Organization</label>
      <input type="text" class="form-control" name="org" id="org" value="<?php echo $org;?>">
    </div>
  </div>
  <div class="form-group row">
      <div class="form-group col-md-6">
      <label for="telephone" class="col-sm-2 col-form-label">Telephone</label>
      <input type="text" class="form-control" name ="telephone" id="telephone" value="<?php echo $telephone;?>" maxlength="10" size="10">
    </div>
  </div>
  <div class="form-group row">
      <div class="form-group col-md-6">
    <label for="idline" class="col-sm-2 col-form-label">ID Line</label>
        <input type="text" class="form-control" name ="idline" id="idline" value="<?php echo $idline;?>" maxlength="10" size="10">
    </div>
  </div>
  <div class="form-group row">
      <div class="form-group col-md-6">
    <label for="twitter" class="col-sm-2 col-form-label">Twitter</label>
        <input type="text" class="form-control" name ="twitter" id="twitter" value="<?php echo $twitter;?>" maxlength="10" size="10">
    </div>
  </div>
  <div class="form-group row">
      <div class="form-group col-md-6">
    <label for="brkfund_id" class="col-sm-2 col-form-label">brkfund_id</label>
        <input type="text" class="form-control" name ="brkfund_id" id="brkfund_id" value="<?php echo $brkfund_id;?>" maxlength="10" size="10">
    </div>
  </div>
  <div class="form-group row">
    <div class="form-group col-md-3">
      <label for="Email">Email</label>
      <input type="email" class="form-control" name="email" id="email" value="<?php echo $email;?>" placeholder="Email">
    </div>
  </div>
  <div class="form-group row">
  <fieldset class="form-group">
    <div class="row">
      <legend class="col-form-label col-sm-2 pt-0">Amin</legend>
      <div class="col-sm-10">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="isadmin" id="isadmin" <?php if($rec['admin']){ echo "checked";}?>>
          <label class="form-check-label" for="isadmin"> System Admin</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="coupon_manager" id="coupon_manager" <?php if($rec['coupon_manager']){ echo "checked";}?>>
            <label class="form-check-label" for="coupon_manager"> coupon_manager</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="virtualrun_manager" id="virtualrun_manager"  <?php if($rec['virtualrun_manager']){ echo "checked";}?>>
            <label class="form-check-label" for="virtualrun_manager"> virtualrun_manager</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="brkfund_manager" id="brkfund_manager" <?php if($rec['brkfund_manager']){ echo "checked";}?>>
            <label class="form-check-label" for="brkfund_manager"> brkfund_manager</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="club_manager" id="club_manager" <?php if($rec['club_manager']){ echo "checked";}?>>
            <label class="form-check-label" for="club_manager"> club_manager</label>
        </div>
      </div>
    </div>
  </fieldset>
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
        }// end foreach

}// end is $res from database

}// end is get userid

} // end is not submit form
  ?>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<?php
function showListman(){
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
    echo "<th>Telephone</th>";
    echo "<th>ID Line</th>";
    echo "<th>Twitter</th>";
    echo "<th>เลขสมาชิก บรข.</th>";
    echo "<th>Email</th>";
    echo "<th>Admin</th><th>Coupon Manager</th><th>Vrun Manager</th><th>BRK Manager</th><th>Club Manager</th>";
    echo "<th>Update</th>";
echo "</tr>";
// retrieve our table contents
foreach($res as $rec){
    $objectId=$rec['_id'];
    $id=$rec['id'];
    $name=$rec['rank'].' '.$rec['name'].' '.$rec['lastname'];
    $org=$rec['org'];
    $telephone=$rec['telephone'];
    $idline=$rec['idline'];
    $twitter=$rec['twitter'];
    $brkfund_id = isset($rec['brkfund_id']) ? $rec['brkfund_id'] : "";
    $username = isset($rec['username']) ? $rec['username'] : "";
    $isadmin = isset($rec['admin']) ? $rec['admin'] : false;
    $coupon_manager = isset($rec['coupon_manager']) ? $rec['coupon_manager'] : false;
    $virtualrun_manager = isset($rec['virtualrun_manager']) ? $rec['virtualrun_manager'] : false;
    $brkfund_manager = isset($rec['brkfund_manager']) ? $rec['brkfund_manager'] : false;
    $club_manager = isset($rec['club_manager']) ? $rec['club_manager'] : false;
    $email = isset($rec['email']) ? $rec['email'] : "";
// creating new table row per record

echo "<tr>"; ?>

        <td><?php echo $id;?></td>
        <td><?php echo $name;?></td>
        <td><?php echo $org;?></td>
        <td><?php echo $telephone;?></td>
        <td><?php echo $idline;?></td>
        <td><?php echo $twitter;?></td>
        <td><?php echo $brkfund_id;?></td>
        <td><?php echo $email;?></td>
        <td><input type="checkbox" name="isadmin" <?php if($isadmin){ echo "checked";}?>></td>
        <td><input type="checkbox" name="coupon_manager" <?php if($coupon_manager){ echo "checked";}?>></td>
        <td><input type="checkbox" name="virtualrun_manager" <?php if($virtualrun_manager){ echo "checked";}?>></td>
        <td><input type="checkbox" name="brkfund_manager" <?php if($brkfund_manager){ echo "checked";}?>></td>
        <td><input type="checkbox" name="club_manager" <?php if($club_manager){ echo "checked";}?>></td>
        <td><a href='updateman.php?userid=<?php echo $objectId;?>'>Update</a></td>
<?php
echo "</tr>";
}// end foreache people

// end table
echo "</table>";
}// end of get data from databases

}

?>
</body>
</html>
