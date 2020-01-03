<!DOCTYPE HTML>
<html>
<head>
    <title>ระบบการจ่ายคูปองค่าอาหารกลางวัน พัน.ขกท.</title>
      
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
          
</head>
<body>
  
    <!-- container -->
    <div class="container">
   
        <div class="page-header">
            <h1>ลงทะเบียนรับคูปอง</h1>
        </div>
      
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>ยศ ชื่อ สกุล</td>
            <td><input type='text' name='name' class='form-control' /></td>
        </tr>
        <tr>
            <td>รหัสประจำตัวข้าราชการทหาร</td>
            <td><input type='text' name='government_id' class='form-control' /></td>
        </tr>
        <tr>
            <td>รหัสคูปอง</td>
            <td><input type='text' name='coupon_id' class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />
            </td>
        </tr>
    </table>
</form>
          
    </div> <!-- end .container -->
    if($_POST){
        $name=htmlspecialchars(strip_tags($_POST['name']));
        $government_id=htmlspecialchars(strip_tags($_POST['government_id']));
        $coupon_id=htmlspecialchars(strip_tags($_POST['coupon_id']));
        }
echo "NAME :".$name;
echo "Government ID :".$government_id;
echo "COUPON ID :".$coupon_id;
      
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
   
<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</body>
</html>
