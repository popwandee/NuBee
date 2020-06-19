<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}



// Cloudinary
require '../vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require '../vendor/cloudinary/cloudinary_php/src/Uploader.php';
require '../vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "../config.php";// mlab
require_once "../vendor/autoload.php";
require_once "../vendor/function.php";
// Define variables and initialize with empty values
$username = $username_err =  "";

 // ลงทะเบียนวิ่งแล้ว
$event_register=false;

$fullname = isset($_SESSION["fullname"]) ? $_SESSION["fullname"] : "";
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";


// Processing form data when form is submitted
if(isset($_POST['event']) && $_POST['event']=='vrun2020' ) {

        // Set parameters
        $param_username = $username;
        // Prepare a select statement
        $json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020?apiKey='.MLAB_API_KEY.'&q={"username":{"$regex":"'.$param_username.'"}}');
        $vrunner = json_decode($json);
        $isData=sizeof($vrunner);
        //ตรวจสอบว่ามีชื่อผู้ใช้นี้อยู่แล้วหรือไม่
        if($isData >0){
            // มีชื่อผู้ใช้นี้อยู่แล้ว -
            $_SESSION['message']= "Username นี้สมัครเข้าร่วมกิจกรรมแล้ว";
            $username_err = $_SESSION['message'];
            $event_register=true;
         }


    // Check input errors before inserting in database
    if(empty($username_err)){

            // Set parameters
            $param_fullname = $fullname;
            $param_username = $username;

            // Attempt to execute the prepared statement
           $newData = json_encode(array('fullname' => $param_fullname,
           'username' => $param_username,
   			        'type' => "runner"
			        ) );
           $opts = array('http' => array( 'method' => "POST",
                               'header' => "Content-type: application/json",
                               'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020?apiKey='.MLAB_API_KEY.'';
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);
        if($returnValue){
		   echo "<div align='center' class='alert alert-success'>ลงทะเบียนเรียบร้อย</div>";
	        // Redirect to login page
                header("location: listvrun2020.php");
        }else{
		           echo "<div align='center' class='alert alert-danger'>ไม่สามารถลงทะเบียนได้</div>";
                 }


        }// end if not $username_err


}// end is set username

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
      <script src="../vendor/bootstrap/ie-emulation-modes-warning.js"></script>
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
              <h2>Vrun2020 Register : ลงทะเบียนเข้าร่วมกิจกรรม MI Bn Virtual Run 2020</h2>
          </div>
          <div class="row">
          <div class="panel-body">
          <h3><span class="label label-warning"><?php echo $_SESSION['message']; $_SESSION['message']='';?></span></h3>
          ประชาสัมพันธ์กิกิจกรรม
        </div> <!-- panel-body -->
    </div> <!-- row -->
         </div> <!-- panel panel-primary -->
           <?php if (!$event_register){ ?>
         <div class="panel panel-info">
           <div class="panel-heading">
               <h2>Vrun2020 Register : ลงทะเบียนเข้าร่วมกิจกรรม MI Bn Virtual Run 2020</h2>
           </div>
           <div class="row">
             <div class="col-sm-4" >
           <div class="panel-body">
           <p>  <?php echo $_SESSION['message']; $_SESSION['message']='';?></p>
             <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                 <div class="form-group">
                     <input type="hidden" name="event" value="vrun2020">
                     <input type="submit" class="btn btn-primary" value="เข้าร่วมกิจกรรม">
                 </div>
             </form>
         </div> <!-- panel-body -->
     </div> <!-- col-sm-4 -->
     </div> <!-- row -->
   </div> <!-- panel panel-info -->
        <?php }?>
         <div class="panel panel-success">
           <div class="panel-heading">
               <h2>ผู้เข้าร่วมกิจกรรม MI Bn Virtual Run 2020</h2>
           </div>
           <div class="row">
             <div class="col-sm-4" >
           <div class="panel-body">
            รายชื่อ
         </div> <!-- panel-body -->
       </div> <!-- col-sm-4 -->
       </div> <!-- row -->
     </div> <!-- panel panel-success -->
      </div> <!-- page-header -->
    </div> <!-- jumbotron -->
  </div> <!-- main -->

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

  <!-- Latest compiled and minified Bootstrap JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
