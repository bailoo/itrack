<html>

<body>

<?php 
$vehicle_id = $_GET['vid'];

$url = "http://s.wordpress.com/mshots/v1/http%3A%2F%2Fwww.itracksolution.com%2Fsrc%2Fphp%2Fsector_screenshot.php%3Fvid%3D".$vehicle_id."?w=1024";
echo '<img src="'.$url.'"/>';
//$url = "http://www.itracksolution.com/src/php/sector_screenshot?".$vehicle_id;
//echo $url."<br>";
//echo '<a href="'.$url.'">Show Screenshot</a>';

?>
</body>

</html>