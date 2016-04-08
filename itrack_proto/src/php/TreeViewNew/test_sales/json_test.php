<?php
$posts=$_POST['obj'];
echo "post=".$posts."<br>";
$fp = fopen('test.json', 'a+');
fwrite($fp, $posts."]"));
fclose($fp);
?>