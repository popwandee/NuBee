<?php
if($_POST){
        $name=htmlspecialchars(strip_tags($_POST['name']));
        $government_id=htmlspecialchars(strip_tags($_POST['government_id']));
        $coupon_id=htmlspecialchars(strip_tags($_POST['coupon_id']));
        }
echo "NAME :".$name;
echo "Government ID :".$government_id;
echo "COUPON ID :".$coupon_id;
?>
