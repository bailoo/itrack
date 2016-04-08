<?php
$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));
echo "\nprev_date=".$previous_date;
?>