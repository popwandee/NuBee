<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$user_info = isset($_SESSION["user_info"]) ? $_SESSION["user_info"] : "";
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";
$position = isset($_SESSION["position"]) ? $_SESSION["position"] : "";
$province = isset($_SESSION["province"]) ? $_SESSION["province"] : "";


// Cloudinary
require '../vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require '../vendor/cloudinary/cloudinary_php/src/Uploader.php';
require '../vendor/cloudinary/cloudinary_php/src/Api.php';

// Include config file
require_once "../config.php";// mlab
require_once "../vendor/autoload.php";
require_once "../vendor/function.php";
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

    <title>MI Bn Virtualrun</title>
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
      <h1>MI Bn Virtualrun</h1>
      <p>เตรียมทหาร รุ่นที่ 40 จปร.รุ่นที่ 51</p>
    </div>

  <hr>
  <button type="button" class="btn btn-xs btn-danger">
<?php
// get summary financemanager
$json = file_get_contents('https://api.mlab.com/api/1/databases/virtualrun/collections/vrun2020?apiKey='.MLAB_API_KEY);
$data = json_decode($json);
$isData=sizeof($data);
if($isData >0){
  //echo "\nGet data from DB are "; //print_r($data);
     $sum=$data->sum;
   }else{ $sum="ไม่มีข้อมูล";}

$message = isset($_GET['message']) ? $_GET['message'] : "";   echo $message; ?>
</button>
            <div class="page-header">
              <h1>ยินดีต้อนรับเพื่อนๆ สมาชิกชมรมวิ่งเพื่อสุขภาพทุกท่านครับ</h1>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="list-group">
                  <span class="list-group-item active"><?php echo $user_info;?></span>
                  <span class="list-group-item">โทรศัพท์ <?php echo $telephone;?></span>
                  <a href="reset_password.php" class="list-group-item">เปลี่ยนรหัสผ่าน</a>
                </div>
              </div><!-- /.col-sm-4 -->
              <div class="col-sm-4">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title">สถานะระยะทางวิ่ง</h3>
                  </div>
                  <div class="panel-body">

                    <span class="list-group-item">รวมที่อนุมัติแล้ว <?php $sum=number_format($sum, 2);echo $sum;?> บาท</span>
                  </div>
                </div>
              </div><!-- /.col-sm-4 -->
              <div class="col-sm-4">
                <div class="panel panel-info">
                  <div class="panel-heading">
                    <h3 class="panel-title">ประกาศจากชมรม</h3>
                  </div>
                  <div class="panel-body">
                    <?php show_10_post();?>
                  </div>
                </div>
              </div><!-- /.col-sm-4 -->
            </div>

              </div> <!-- /container -->

              <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
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
            <!-- Footer -->
            <footer>
              <div class="container">
                <div class="row">
                  <div class="col-lg-8 col-md-10 mx-auto">
                    <ul class="list-inline text-center">
                      <li class="list-inline-item">
                        <a href="#">
                          <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                           <!-- lisa_echo_bot_box -->
                           <ins class="adsbygoogle"
                                style="display:block"
                                data-ad-client="ca-pub-0730772505870150"
                                data-ad-slot="3696345155"
                                data-ad-format="auto"
                                data-full-width-responsive="true"></ins>
                           <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                           </script>
                          </span>
                        </a>
                      </li>
                      <li class="list-inline-item">
                        <a href="#">
                          <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                           <!-- lisa_echo_bot_box -->
                           <ins class="adsbygoogle"
                                style="display:block"
                                data-ad-client="ca-pub-0730772505870150"
                                data-ad-slot="3696345155"
                                data-ad-format="auto"
                                data-full-width-responsive="true"></ins>
                           <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                           </script>
                          </span>
                        </a>
                      </li>
                      <li class="list-inline-item">
                        <a href="#">
                          <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                           <!-- lisa_echo_bot_box -->
                           <ins class="adsbygoogle"
                                style="display:block"
                                data-ad-client="ca-pub-0730772505870150"
                                data-ad-slot="3696345155"
                                data-ad-format="auto"
                                data-full-width-responsive="true"></ins>
                           <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                           </script>
                          </span>
                        </a>
                      </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; MI Bn Virtualrun-2020</p>
                  </div>
                </div>
              </div>
            </footer>

<?php
function show_10_post(){
  $q = '?s={"date":-1}&sk=0&l=10';
    $url= "https://api.mlab.com/api/1/databases/virtualrun/collections/post".$q."&apiKey=".MLAB_API_KEY;
    $json = file_get_contents($url);
    $data = json_decode($json);
                  $isData=sizeof($data);
                  if($isData >0){
                    foreach($data as $rec){
                      $i++;
                      $_id=$rec->_id;
                      foreach($_id as $rec_id){
                      $_id=$rec_id;
                      }
                       $name=$rec->name;
                       $title=$rec->title;
                       ?>
      <a href="request.php?action=review&_id=<?php echo $_id;?> "><?php echo $title;?></a> : <?php echo $name;?><br>
      <?php    } //end foreach
               }else{ echo "ยังไม่มีข้อมูลค่ะ"; }
  }// end function show_friend
?>

            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

            <!-- Latest compiled and minified Bootstrap JavaScript -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>

</html>
