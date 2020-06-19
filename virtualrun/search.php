<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Cloudinary
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "config.php";// mlab
require_once "vendor/autoload.php";
require_once "vendor/function.php";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="AFAPS40-CRMA51 Website">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>AFAPS40-CRMA51</title>
    <!-- Latest compiled and minified CSS -->
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
<script data-ad-client="ca-pub-0730772505870150" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body>
  <?php include 'navigation.html';?>

  <div class="container theme-showcase" role="main">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>ค้นหาชื่อเพื่อนสมาชิก</h1>
      <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ชื่อ <input type='text' name='name' class='form-control' /></td>
            <td><input type='hidden' name='from_form' value='search_name'/>
              <input type='submit' value='ค้นหา' class='btn btn-primary' /></td>
        </tr>
    </table>
</form>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<?php select_province(); ?>
<input type='hidden' name='from_form' value='search_province'/>
<input type='submit' value='ค้นหา' class='btn btn-primary' />
</form>
<?php  $message = isset($_GET['message']) ? $_GET['message'] : ""; echo $message;

if(isset($_POST['from_form'])){
  if(($_POST['from_form'])=='search_name'){
    	$name = isset($_POST['name']) ? $_POST['name'] : "";
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"name":{"$regex":"'.$name.'"}}');
    }elseif(($_POST['from_form'])=='search_province'){
      $province = isset($_POST['province']) ? $_POST['province'] : "";
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY.'&q={"province":{"$regex":"'.$province.'"}}');
    }else{
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);
    } // if from_form == search

    }//end if isset _POST['name']
  else{// not from search form
      $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);
      }
 $data = json_decode($json);
 $isData=sizeof($data);
  if($isData >0){
  show_search_result($data);
  }// if no records found
else{
    echo "<div align='center' class='alert alert-danger'>ยังไม่มีข้อมูลค่ะ</div>";
}
         ?>
         <div>
         <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- lisa_echo_bot_website -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-0730772505870150"
             data-ad-slot="7819708405"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div>
  </div> <!-- jumbotron -->
</div> <!--main -->

<?php
function show_search_result($data){ ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped">
          <thead>
   <tr><th>ลำดับ</th><th>ยศ ชื่อ สกุล</th><th>ตำแหน่ง</th>
      <th>จังหวัด</th><th>Email</th><th>Tel.</th><th>ID LINE</th></tr>
    </thead><tbody>
<?php
$id=0;
foreach($data as $rec){
$id++;
$_id=$rec->_id;
foreach($_id as $rec_id){
  $_id=$rec_id;
}
$rank=$rec->rank;$name=$rec->name;$lastname=$rec->lastname;
  $position=$rec->position;
  $province=$rec->province;
  $Email=$rec->Email;
  $Tel1=$rec->Tel1;
  $LineID=$rec->LineID;
?>
<tr><td><?php echo "{$id}";?></td>
    <td class='text-nowrap'><a href='friend_preview.php?_id=<?php echo $_id;?>&action=preview'>
      <?php echo "{$rank} "; ?> <?php echo "{$name} "; ?> <?php echo "{$lastname} "; ?></a></td>
    <td><?php echo "{$position}";?></td>
    <td><?php echo "{$province}";?></td>
    <td><?php echo "{$Email}";?></td>
    <td><?php echo "{$Tel1}";?></td>
    <td><?php echo "{$LineID}";?></td>
</tr>
<?php } // end foreach $data ?>
</tbody>
</table>
</div><!-- class='table-responsive'-->
<?php  } // end function ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
