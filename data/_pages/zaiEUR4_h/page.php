<?php
defined('is_running') or die('Not an entry point...');
$fileVersion = '5.1';
$fileModTime = '1610240909';
$file_stats = array (
  'created' => 1610236056,
  'gpversion' => '5.1',
  'modified' => 1610240909,
  'username' => 'popwandee',
);

$file_sections = array (
  0 => 
  array (
    'type' => 'text',
    'content' => '<h1>ร้านค้า</h1>

<p>ร้านค้าคือร้านที่สมัครใจที่จะรับคูปองอิเล็กทรอนิกส์ เป็นค่าอาหาร และเครื่องดื่ม แล้วนำมาขอแลกเป็นค่าอาหารในรูปของเงินสด หรือรับโอนเข้าบัญชีธนาคาร</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<h2>รายชื่อร้านค้าที่ร่วมโครงการ</h2>

<p>Learn how to <a href="http://www.typesettercms.com/Docs/Main/Admin" rel="nofollow" title="Typesetter File Management">manage your files</a>, <a href="http://www.typesettercms.com/Docs/Main/Creating%20Galleries" rel="nofollow" title="Creating Galleries in Typesetter">create galleries</a> and more in the <a href="http://www.typesettercms.com/Docs/index.php/" rel="nofollow" title="Typesetter Documentation">Typesetter Documentation</a>.</p>
',
    'resized_imgs' => 
    array (
    ),
    'modified' => 1610240289,
    'modified_by' => 'popwandee',
    'attributes' => 
    array (
    ),
  ),
  1 => 
  array (
    'type' => 'wrapper_section',
    'content' => '',
    'contains_sections' => '2',
    'attributes' => 
    array (
      'class' => 'gpRow',
    ),
    'gp_label' => 'Section Wrapper',
    'gp_color' => '#555',
  ),
  2 => 
  array (
    'type' => 'text',
    'content' => '<div>
<h2>Login ร้านค้า</h2>

<p>&nbsp;</p>

<form action="ecoupon/merchant.php" method="post">
<div class="form-group"><label>Email ร้านค้า</label> <input class="form-control" name="merchant_email" type="text" /></div>

<div class="form-group"><label>รหัสผ่าน</label> <input class="form-control" name="merchant_password" type="password" /></div>

<div class="form-group"><input class="btn btn-primary" type="submit" value="Login" /></div>

<div align="left" class="wrapper">
<p>ยังไม่มีบัญชีร้านค้า <a href="merchant_signup.php">สมัครเข้าร่วมโครงการ</a></p>
</div>
</form>

<p>&nbsp;</p>
</div>
',
    'attributes' => 
    array (
      'class' => 'gpCol-6',
    ),
    'resized_imgs' => 
    array (
    ),
    'modified' => 1610240193,
    'modified_by' => 'popwandee',
  ),
  3 => 
  array (
    'type' => 'image',
    'content' => '',
    'nodeName' => 'img',
    'attributes' => 
    array (
      'src' => '/data/_resized/img_type/sample.jpg.1610240892.png',
      'width' => '200',
      'height' => '200',
      'class' => 'gpCol-6',
    ),
    'orig_src' => '/NuBee/data/_uploaded/image/sample.jpg',
    'posx' => '0',
    'posy' => '0',
    'modified' => 1610240905,
    'modified_by' => 'popwandee',
  ),
  4 => 
  array (
    'type' => 'wrapper_section',
    'content' => '',
    'contains_sections' => '2',
    'attributes' => 
    array (
      'class' => 'gpRow',
    ),
    'gp_label' => 'Section Wrapper',
    'gp_color' => '#555',
  ),
  5 => 
  array (
    'type' => 'image',
    'content' => '',
    'nodeName' => 'img',
    'attributes' => 
    array (
      'src' => '/include/imgs/default_image.jpg',
      'width' => '400',
      'height' => '300',
      'class' => 'gpCol-6',
    ),
    'orig_src' => '/NuBee/include/imgs/default_image.jpg',
    'posx' => 0,
    'posy' => 0,
    'modified' => 1610240299,
    'modified_by' => 'popwandee',
  ),
  6 => 
  array (
    'type' => 'text',
    'content' => '<div>
<h2>Sign up สมัครเข้าร่วมโครงการ</h2>

<p>&nbsp;</p>

<form action="ecoupon/merchant_signup.php" method="post">
<div class="form-group"><label>ชื่อร้านค้า</label> <input class="form-control" name="merchant_name" type="text" /></div>

<div class="form-group"><label>เบอร์โทร ร้านค้า</label> <input class="form-control" name="merchant_telephone" type="text" /></div>

<div class="form-group"><label>Email ร้านค้า</label> <input class="form-control" name="merchant_email" type="text" /></div>

<div class="form-group"><label>Password</label> <input class="form-control" name="merchant_password" type="password" /></div>

<div class="form-group"><label>Confirm Password</label> <input class="form-control" name="confirm_merchant_password" type="password" /></div>

<div class="form-group"><input class="btn btn-primary" name="merchant_status" type="hidden" value="Inactive" /> <input class="btn btn-primary" type="submit" value="Submit" /> <input class="btn btn-default" type="reset" value="Reset" /></div>

<p>มี account อยู่แล้วใช่ไหม? <a href="merchant.php">Login ที่นี่</a>.</p>
</form>

<p>&nbsp;</p>
</div>
',
    'attributes' => 
    array (
      'class' => 'gpCol-6',
    ),
    'resized_imgs' => 
    array (
    ),
    'modified' => 1610240249,
    'modified_by' => 'popwandee',
  ),
);

$meta_data = array (
  'file_number' => 5,
  'file_type' => 'text',
  'gallery_dir' => '/image',
);