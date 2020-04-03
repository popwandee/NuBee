<?php
// include database connection
include 'config.php';
 
try {
     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
 
    // delete query
    echo "$id is ".$id;
$opts = array('http' => array( 'method' => "DELETE",
                               'header' => "Content-type: application/json",
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/nubee/collections/coupon/'.$id.'?apiKey='.MLAB_API_KEY.',type: "DELETE",
   timeout: 300000';
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);
        if($returnValue){
		  // redirect to read records page and 
        // tell the user record was deleted
        header('Location: listcoupon.php?message=deleted');
	        }else{
		    die('CannotDeleted');
                 }
	
     
   
}
 
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>
