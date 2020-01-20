
  <?php
         define("MLAB_API_KEY", '6QxfLc4uRn3vWdeleterlgzsWtzTXBW7meCYVsQv');
$newData = '
[
  {
    "id": 1,
    
  }
]
';
$opts = array('http' => array( 'method' => "POST",
                               'header' => "Content-type: application/json",
                               'content' => $newData
                                           )
                                        );
$url = 'https://api.mlab.com/api/1/databases/nubee/collections/personel?apiKey='.MLAB_API_KEY.'';
        $context = stream_context_create($opts);
        $returnValue = file_get_contents($url,false,$context);
