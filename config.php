<?php

$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime();
$datetime->setTimezone($tz_object);
$dateTimeToday = $datetime->format('Y-m-d');

$timeStamp = $datetime->format('Y-m-d H:m:s');

?>
