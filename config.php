<?php
define("MLAB_API_KEY", '6QxfLc4uRn3vWrlgzsWtzTXBW7CYVsQv');
$tz_object = new DateTimeZone('Asia/Bangkok');
$datetime = new DateTime(); $datetime->setTimezone($tz_object); $dateTimeToday = $datetime->format('Y-m-d');
?>
