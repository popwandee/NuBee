<?php
// Initialize the session
session_start();

$debug_log='';

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// ตรวจสอบ ประเภทสมาชิก
if(isset($_SESSION['type'])){    $user_type = $_SESSION['type'];
}else{                           $user_type = "";
}

$debug_log=$debug_log.'type '.$user_type;

// ตรวจสอบ $_id จาก _GET และ _POST
      if(isset($_GET['id'])){         $id = $_GET['id'];
      }elseif(isset($_POST['id'])){   $id = $_POST['id'];
      }elseif(isset($_SESSION['user_id'])){   $id = $_SESSION['user_id'];
      }else{                           $id = "";
      }

      $debug_log=$debug_log.'<br>ID '.$id;

      // ตรวจสอบ $username จาก _GET และ _POST
if(isset($_SESSION['username'])){   $username = $_SESSION['username'];
}else{                              $username= "";
  }

            $debug_log=$debug_log.'<br>username '.$username;

//      ตรวจสอบ Action จาก _GET หรือ _POST
      if(isset($_GET['action'])){         $action = $_GET['action'];
      }elseif(isset($_POST['action'])){   $action = $_POST['action'];
      }else{                              $action = "review";
      }

      $debug_log=$debug_log.'<br>Action '.$action;
// Cloudinary
require '../vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require '../vendor/cloudinary/cloudinary_php/src/Uploader.php';
require '../vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "../config.php";// mlab
require_once "../vendor/autoload.php";
require_once "../vendor/function.php";

 // core logic
 if(isset($username)){
 $debug_log=$debug_log."<br>Username ".$username;
 $json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020?apiKey='.MLAB_API_KEY.'&q={"username":{"$regex":"'.$username.'"}}');
 $user_data = json_decode($json);
 $is_found_event_user=sizeof($user_data);$debug_log=$debug_log."<br>isData".$isData;
}

switch ($action) {
case 'new_running' :
$range_log= isset($_POST['range']) ? $_POST['range'] : '';$debug_log=$debug_log."<br>Range ".$range;
$time = date("Ymd-His");$debug_log=$debug_log."<br>Time ".$time;
if (!empty($_FILES['record_image'])) { //record_image
$files=$_FILES["record_image"]['tmp_name'];
$target_file = basename($_FILES["record_image"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  if(!empty($imageFileType)){
    $public_id =$username.$time;
    $option=array("folder"=> "vrun2020","public_id" => $public_id);
    $file_name =$public_id.".".$imageFileType;
    $cloudUpload = \Cloudinary\Uploader::upload($files,$option);
    $debug_log=$debug_log."<br>File name ".$file_name;
  }

   }// end if !empty _FILES

if($is_found_event_user >0){ // got user record to update

      $debug_log=$debug_log."<br>Call function update_run ";

      $newData = ' { "username" : "'.$username.'","range_log":"'.$range_log.'","time":"'.$time.'","file_name":"'.$file_name.'","approved":"NO"} ';
      $debug_log=$debug_log."<br>newData is ".$newData;

      $res = new_run_log($newData);

      $debug_log=$debug_log.$res."<br>Return from function update_run ";


}// end if $isData>0
header("location: reportvrun2020.php?action=review");
exit;
break; // end case newrequest
case 'review' :
$debug_log=$debug_log."<br>In review ";
if($is_found_event_user >0){$debug_log=$debug_log."<br>found_event_user ".$is_found_event_user;
  foreach ($user_data as $runner){
    $_id = $runner->_id;
    foreach ($_id as $log_id){$runner_id=$log_id;}
                                        $debug_log=$debug_log."<br>Log Id ".$log_id;
      $fullname=$runner->fullname;      $debug_log=$debug_log."<br>fullname ".$fullname;
      $username=$runner->username;      $debug_log=$debug_log."<br>username ".$username;
      $total_range=$runner->total_range;$debug_log=$debug_log."<br>total_range ".$total_range;
      $url='https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020log?apiKey='.MLAB_API_KEY.'&q={"username":{"$regex":"'.$username.'"}}';
      $json = file_get_contents($url);$debug_log=$debug_log."<br>URL of running log is ".$url;
      $running_log = json_decode($json);
      $is_found_running_log=sizeof($running_log);$debug_log=$debug_log."<br>Found size of running log is ".$is_found_running_log;
      }// end foreach $data

}// end isData >0 found user $id
break;
default:

break;
}//end switch action

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="MI Bn Virtual Run">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>MI Bn virtual Run</title>
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
<?php include 'navigation.html';?>

  <div class="container theme-showcase" role="main">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>Mi Bn Virtual Run</h1>
      <div class="page-header">
        <div class="panel panel-primary">
          <div class="panel-heading">
              <h3>แจ้งระยะทางวิ่ง</h3>
</div>
 <div class="panel-body">
<?php  //show_all_running();
  new_running_form();

?>
<!-- log session -->
<?php   //echo $debug_log; ?>
</div> <!-- panel-body -->
</div><!-- pane-primary -->
</div> <!-- page-header -->

<div class="page-header">
  <div class="panel panel-primary">
    <div class="panel-heading">
        <h3>ระยะทางวิ่งสะสม</h3>
</div>
<div class="panel-body">
<?php if($is_found_running_log){ show_running_log($running_log);}else{echo "ยังไม่มีผลการวิ่งค่ะ";} ?>
</div> <!-- panel-body -->
</div><!-- pane-primary -->
</div> <!-- page-header -->
</div><!-- jumbotron-->

</div><!-- container theme-showcase-->

<?php
function new_running_form(){ ?>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
  <table class='table table-responsive table-bordered' width="300">
    <tr><td>ระยะทาง </td><td><input type='text' name='range' class='form-control' /></td></tr>
      <tr><td colspan="2">แนบรูปภาพ<input type='file' name='record_image' class='form-control' /></td></tr>
      <tr><td colspan="2" align="center"><input type="hidden"name="action" value="new_running">
              <input type='submit' value='Save' class='btn btn-primary' /></td></tr>
      </table>
    </form>
<?php } // end new_running_form ?>
<?php
function show_running_log($running_log){ ?>

  <table class='table table-responsive table-bordered' width="300">
    <?php
    $i=0;
  foreach ($running_log as $log){
    $i++;
    $range_log=$log->range_log; $debug_log=$debug_log."<br>Range_log ".$range_log;
    $time= $log->time;          $debug_log=$debug_log."<br>Time".$time;
    $file_name=$log->file_name; $debug_log=$debug_log."<br>file_name ".$file_name;
?>
    <tr><td><?php echo $i;?> </td><td><?php echo "{$time}"?></td><td><?php echo"{$range_log}"?>km.</td>
<td colspan="3"><img src='https://res.cloudinary.com/dly6ftryr/image/upload/v1591955769/vrun2020/<?php echo "{$file_name}";?>' width='100'></td></tr>
          <?php  }// end foreach ?>
      </table>

<?php } // end show_running_log ?>

<?php function new_run_log($newData){
$opts = array('http' => array( 'method' => "POST",
                             'header' => "Content-type: application/json",
                             'content' => $newData
                                         )
                                      );
$url = 'https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020log?apiKey='.MLAB_API_KEY.'';
      $context = stream_context_create($opts);
      $returnValue = file_get_contents($url,false,$context);

      if($returnValue){
        $debug_log=$debug_log."<br><br>INSERt OK";
      }else{
        $debug_log=$debug_log."<br><br>NOT PASS";
      }
      return $debug_log;
}//end function insert_request
 ?>
 <?php
 function update_run($id,$range_log,$time,$file_name){

         $debug_log="<br>in function Update_run ";

         $newData = ' { "log" : "range_log":"'.$range_log.'","time":"'.$time.'","file_name":"'.$file_name.'"} ';
         $opts = array('http' => array( 'method' => "PUT",
                                        'header' => "Content-type: application/json",
                                        'content' => $newData
                                                    )
                                                 );
        $debug_log=$debug_log."<br>New Data ".$newData;
         $url = 'https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020/'.$id.'?apiKey='.MLAB_API_KEY;
         $debug_log=$debug_log."<br> URL ".$url;
                 $context = stream_context_create($opts);
                 $returnValue = file_get_contents($url,false,$context);
                 if($returnValue){
                   $debug_log=$debug_log."<br><br>INSERt OK";
                 }else{
                   $debug_log=$debug_log."<br><br>NOT PASS";
                 }
                 return $debug_log;
 }// end function update_run
  ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
