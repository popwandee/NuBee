<?php $root_url= "http://" . $_SERVER['HTTP_HOST']."/NuBee";?>
<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Smart MIBn</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?php echo $root_url; ?>/index.php">Home</a></li>
        <li><a href="<?php echo $root_url; ?>/listman.php">รายชื่อกำลังพล</a></li>
        <li class="dropdown">
          <a href="<?php echo $root_url; ?>/listcoupon.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            ระบบคูปองอาหารกลางวัน <span class="caret"></span></a>
          <ul class="dropdown-menu">
        <li><a href="<?php echo $root_url; ?>/newcoupon.php">ลงทะเบียนรับคูปอง</a></li>
        <li><a href="<?php echo $root_url; ?>/listman.php">รายชื่อกำลังพลทั้งหมด</a></li>
        <li><a href="<?php echo $root_url; ?>/search.php">ค้นหากำลังพล</a></li>
      <li><a href="<?php echo $root_url; ?>/listcoupon.php">คูปองที่รับไปแล้ว</a></li>
      <li><a href="<?php echo $root_url; ?>/notreturncoupon.php">คูปองที่ยังไม่ส่งคืน</a></li>
      </ul>
     </li>
     <li class="dropdown">
       <a href="<?php echo $root_url; ?>/listcoupon.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
         ระบบคูปองอิเล็กทรอนิกส์ <span class="caret"></span></a>
       <ul class="dropdown-menu">
     <li><a href="<?php echo $root_url; ?>/ecoupon/index.php">ระบบคูปอง</a></li>
     <li><a href="<?php echo $root_url; ?>/ecoupon/manage_coupon.php">จัดการคูปอง (แอดมิน)</a></li>
     <li><a href="<?php echo $root_url; ?>/ecoupon/checkcoupon.php">ตรวจสอบคูปอง</a></li>
     <li><a href="<?php echo $root_url; ?>/ecoupon/merchant.php">ร้านค้า</a></li>
     </ul>
     </li>
     <li class="dropdown">
       <a href="<?php echo $root_url; ?>/brkfund/index.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
         กองทุนบำรุงขวัญ พัน.ขกท. <span class="caret"></span></a>
       <ul class="dropdown-menu">
         <li><a href="<?php echo $root_url; ?>/brkfund/index.php">สถานภาพกองทุน</a></li>
        <!--  <li><a href="<?php echo $root_url; ?>/brkfund/members.php">รายชื่อสมาชิก</a></li> -->
         <li><a href="<?php echo $root_url; ?>/brkfund/record.php">รายการนำส่ง/จ่ายเงิน</a></li>
      </ul>
     </li>

        <li><a href="<?php echo $root_url; ?>/logout.php">ออกจากระบบ</a></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>
