<?php
// include database connection
include 'config.php';
require_once "vendor/restdbclass.php";

try {

    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    // delete query
    $collectionName = "coupon";
    $objectId = "'.$id.'";

    $coupon = new RestDB();
    $res = $coupon->deleteDocument($collectionName,$objectId);

        if(!empty($res)){
		  // redirect to read records page and
        	// tell the user record was deleted
       		 header('Location: listcoupon.php?message=deleted');
	        }else{
		    header('Location: listcoupon.php?message=CannotDeleted');
                 }
}

// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>
