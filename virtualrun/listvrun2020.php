<?php
// Initialize the session
session_start();


// Cloudinary
require '../vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require '../vendor/cloudinary/cloudinary_php/src/Uploader.php';
require '../vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "../config.php";// mlab
require_once "../vendor/autoload.php";
require_once "../vendor/function.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 // เตรียมตัวแปร
$vrunner=''; // ข้อมูลนักวิ่ง

if(isset($_POST['form_no'])){
$form_no=$_POST['form_no']; //echo "form ".$form_no;
 switch ($form_no){
  case "run_approved" : //echo "\nCase approve user";
       $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : "";
       $approved = isset($_POST['approved']) ? $_POST['approved'] : 0;
       run_approved($user_id,$approved);
       $json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020?apiKey='.MLAB_API_KEY);

       break;
  case "run_edit" : //echo "\nCase edit user";
  $_id = isset($_POST['_id']) ? $_POST['_id'] : "";
  $edited = isset($_POST['edited']) ? $_POST['edited'] : 0;
      if($edited){
        //echo "\nGet data from edit user form.";
        // Get data from database to Compare
      $json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020/'.$_id.'?apiKey='.MLAB_API_KEY);
      $data = json_decode($json);
      $isData=sizeof($data);
      if($isData >0){
        //echo "\nGet data from DB are "; //print_r($data);
           $rank=$data->rank;$update_rank = isset($_POST['rank']) ? $_POST['rank'] : "";
           if($rank!=$update_rank){update_field($_id,'rank',$update_rank);}

           $name=$data->name;$update_name = isset($_POST['name']) ? $_POST['name'] : "";
           if($name!=$update_name){update_field($_id,'name',$update_name);}

           if (!empty($_FILES['record_image'])) { //record_image
             $files = $_FILES["record_image"]['tmp_name'];
             $option= array("public_id" => "$Tel1");
             $cloudUpload = \Cloudinary\Uploader::upload($files,$option);
             $img_url = $cloudUpload['secure_url'];
              if(!empty($img_url)){
               $_SESSION['message']=$_SESSION['message']." got img_url is ".$img_url;
               update_field($_id,'img_url',$img_url);
             }
           }
           // retrieve database
           $json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020?apiKey='.MLAB_API_KEY);
           $data = json_decode($json);
           $isData=sizeof($data);
           if($isData >0){
               showdata($data);
             }
         }

      }else{//echo "\nShow form for edit user";
        show_form($_id);
        }
      //  $json = file_get_contents('https://api.mlab.com/api/1/databases/crma51/collections/friend?apiKey='.MLAB_API_KEY);

      break;
   default :

  }//end switch
  $data = json_decode($json);
  $isData=sizeof($data);
  if($isData >0){
      showdata($data);
  }
}//end if isset form_no
else{
  $json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020?apiKey='.MLAB_API_KEY);
  $data = json_decode($json);
  $isData=sizeof($data);
  if($isData >0){
      $vrunner=$data;
    }
}

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

    <title>MI Bn virtualrun</title>
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
        <h1>Mi Bn Virtual Run</h1>
        <div class="page-header">
          <div class="panel panel-primary">
            <div class="panel-heading">
                <h2>Vrun2020 : กิจกรรม MI Bn Virtual Run 2020</h2>
            </div>
            <div class="row">
            <div class="panel-body">
            <h3><span class="label label-warning"><?php echo $_SESSION['message']; $_SESSION['message']='';?></span></h3>
            ประชาสัมพันธ์กิกิจกรรม
          </div> <!-- panel-body -->
      </div> <!-- row -->
           </div> <!-- panel panel-primary -->

           <div class="panel panel-success">
             <div class="panel-heading">
                 <h2>ผู้เข้าร่วมกิจกรรม MI Bn Virtual Run 2020</h2>
             </div>
             <div class="row">
             <div class="panel-body">
               <div class='table-responsive'>
               <table class='table table-hover table-responsive table-bordered'>
               <tr><th>ลำดับ</th><th>ชื่อ สกุล</th><th>username</th><th>ประเภทสมาชิก</th><th>Range</th><th>Action</th>
               </tr>
            <?php $i=0;
            foreach ($vrunner as $runner){
              $i++;
              $runner_id=$runner->_id;
              foreach($runner_id as $id){
              ?>
              <tr><td width='10%'><?php echo "{$i}";?></td>
                <td width='30%' class='text-nowrap'><a href="runner_preview.php?event=vrun2020&id=<?php echo $id; ?>"><?php echo "{$runner->fullname}";?></a></td>
                <td width='20%'><?php echo "{$runner->username}";?></td>
                <td width='20%'><?php echo "{$runner->type}";?></td>
                <td width='10%'><?php echo "{$runner->range}";?></td>
                <td width='20%'><?php print_r($id);?></td></tr>

          <?php  } } // end foreach runner?>
          </table>
        </div> <!-- table-responsive-->
           </div> <!-- panel-body -->
         </div> <!-- row -->
       </div> <!-- panel panel-success -->
        </div> <!-- page-header -->
      </div> <!-- jumbotron -->
    </div> <!-- main -->


     <?php
     function showdata($data)
     { ?>
       <div class='table-responsive'>
       <table class='table table-hover table-responsive table-bordered'>
       <tr><th>ลำดับ</th><th>ชื่อ สกุล</th><th>ตำแหน่ง</th>
         <th>โทรศัพท์</th><th>ประเภทสมาชิก</th><th>Approved</th><th>Action</th>
       </tr>
       <?php
       $id=0;
       foreach($data as $rec){
       $id++;
       $_id=$rec->_id;
       foreach($_id as $rec_id){
       $_id=$rec_id;
       }
       $rank=$rec->rank;
       $name=$rec->name;
       $lastname=$rec->lastname;
       $position=$rec->position;
       $Tel1=$rec->Tel1;
       $type=$rec->type;
       $approved=$rec->approved;
?>
       <tr><td width='10%'><?php echo "{$id}";?></td>
         <td width='30%' class='text-nowrap'><a href="friend_preview.php?_id=<?php echo $_id; ?>"><?php echo "{$rank} {$name} {$lastname}";?></a></td>
         <td width='20%'><?php echo "{$position}";?></td>
         <td width='20%'><?php echo "{$Tel1}";?></td>
         <td width='10%'><?php echo "{$type}";?></td>
         <td width='20%'>
          <?php if($approved){ ?>
                  <button type="button" class="btn btn-xs btn-success">อนุมัติแล้ว</button>
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
                    <input type="hidden" name="user_id" value="<?php echo $_id;?>">
                    <input type='hidden' name='form_no' value='user_approved'>
                    <input type='hidden' name='approved' value='1'>
                    <button type="submit" class="btn btn-xs btn-warning">ยกเลิกการอนุมัติ</button>
                    </form>
                <?php }else{ // not approved ?>
                  <button type="button" class="btn btn-xs btn-danger">ยังไม่อนุมัติ</button>
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $_id;?>">
                    <input type='hidden' name='form_no' value='user_approved'>
                    <input type='hidden' name='approved' value='0'>
                    <button type="submit" class="btn btn-xs btn-warning">อนุมัติ</button>
                    </form>
                <?php } //end id approved ?>
         </td><td width='10%'>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
           <input type="hidden" name="user_id" value="<?php echo $_id; ?>">
           <input type='hidden' name='form_no' value='user_edit'>
           <input type='hidden' name='edited' value='0'>
           <button type="submit" class="btn btn-xs btn-warning">แก้ไข</button>
           </form>
         </td>
       </tr>
     <?php }// end table ?>
     </tbody>
      </table>
</div> <!-- class='table-responsive'-->
  <?php  }// end function   ?>

<?php
function run_approved($_id,$approved){
  if($approved){
  $newData = '{ "$set" : { "approved" : 0 } }';
  $_SESSION['message']='ยกเลิกการอนุมัติ';
}else{
  $newData = '{ "$set" : { "approved" : 1 } }';
  $_SESSION['message']='อนุมัติ แล้ว';
}
  $opts = array('http' => array( 'method' => "PUT",
                                 'header' => "Content-type: application/json",
                                 'content' => $newData
                                             )
                                          );
  $url = 'https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020/'.$_id.'?apiKey='.MLAB_API_KEY.'';
          $context = stream_context_create($opts);
          $returnValue = file_get_contents($url,false,$context);
          if($returnValue){
            $_SESSION['message']=$_SESSION['message'].'=> สำเร็จ.';
         		 header('Location: usermanager.php?message=Approved');
  	        }else{
            $_SESSION['message']=$_SESSION['message'].'=> ไม่สำเร็จ.';
  		       header('Location: usermanager.php?message=CannotApproved');
                   }
}
 ?>


<?php
function update_field($_id,$field_name,$new_info){

        $newData = '{ "$set" : { "'.$field_name.'" : "'.$new_info.'"} }';
        $opts = array('http' => array( 'method' => "PUT",
                                       'header' => "Content-type: application/json",
                                       'content' => $newData
                                                   )
                                                );
        $url = 'https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020/'.$_id.'?apiKey='.MLAB_API_KEY;
                $context = stream_context_create($opts);
                $returnValue = file_get_contents($url,false,$context);

}
 ?>
         <div><!-- class="jumbotron"-->
      </div> <!-- container theme-showcase -->


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
