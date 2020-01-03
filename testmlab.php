<?php
$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer";
$_msg="hello";
$api_key="6QxfLc4uRn3vWrlgzsWtzTXBW7CYVsQv";
$url = 'https://api.mlab.com/api/1/databases/nubee/collections/personel?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/nubee/collections/personel?apiKey='.$api_key.'&q={"question":"'.$_msg.'"}');
$data = json_decode($json);
$isData=sizeof($data);
$_question="Question";
$_answer="Answer";

    $newData = json_encode(
      array(
        'question' => $_question,
        'answer'=> $_answer
      )
    );
    $opts = array(
      'http' => array(
          'method' => "POST",
          'header' => "Content-type: application/json",
          'content' => $newData
       )
    );
    $context = stream_context_create($opts);
    $returnValue = file_get_contents($url,false,$context);
    
    

  if($isData >0){
   foreach($data as $rec){
    $result = $rec->answer;
       echo $result;
   }
  

?>
