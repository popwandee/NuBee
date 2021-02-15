<?php $root_url= "http://" . $_SERVER['HTTP_HOST']."/NuBee";?>
<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header"><a class="navbar-brand" href="index.php">Smart MIBn</a></div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="index.php">Home</a></li>
     <li class="dropdown">
       <a href="<?php echo $root_url; ?>/ecoupon/merchant.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
         สำหรับร้านค้า <span class="caret"></span></a>
       <ul class="dropdown-menu">
     <li><a href="<?php echo $root_url; ?>/ecoupon/merchant_index.php">ระบบร้านค้า</a></li>
     <li><a href="<?php echo $root_url; ?>/ecoupon/merchant.php">ข้อมูลร้านค้า</a></li>
     <li><a href="<?php echo $root_url; ?>/ecoupon/merchant_signup.php">สมัครร้านค้า</a></li>
     </ul>
     </li><li class="dropdown">
       <a href="<?php echo $root_url; ?>/ecoupon/merchantlist.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
         ผู้ดูแลระบบ <span class="caret"></span></a>
       <ul class="dropdown-menu">
     <li><a href="<?php echo $root_url; ?>/ecoupon/manage_coupon.php">จัดการคูปอง (แอดมิน)</a></li>
     <li><a href="<?php echo $root_url; ?>/ecoupon/merchantlist.php">บริหารจัดการร้านค้า</a></li>
     </ul>
     </li>

        <li><a href="<?php echo $root_url; ?>/logout.php">ออกจากระบบ</a></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>
