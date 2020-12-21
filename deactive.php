<?php
// include database connection
include 'config.php';
require_once "vendor/restdbclass.php";

try {

    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
    $statusCoupon=isset($_GET['statusCoupon']) ? $_GET['statusCoupon'] : "ส่งคืนแล้ว";
    $target=isset($_GET['target']) ? $_GET['target'] : "listcoupon";

    // delete query
    $collectionName = "coupon";
    $objectId = $id;
    if($statusCoupon=="ส่งคืนแล้ว"){
        $obj =  array("statusCoupon" => "ส่งคืนแล้ว");
    }else{
        $obj =  array("statusCoupon" => "ยังไม่ส่งคืน");
    }


    $coupon = new RestDB();
    $res = $coupon->updateDocument($collectionName, $objectId, $obj);

        if(!empty($res)){
		  // redirect to read records page and
        	// tell the user record was deleted
       		 header('Location: '.$target.'.php?message=updated');
	        }else{
		     header('Location: '.$target.'.php?message=CannotUpdated');
                 }
}

// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>
