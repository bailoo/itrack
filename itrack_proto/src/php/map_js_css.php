<link rel="StyleSheet" href="src/css/help.css">
<!--<script language="javascript" src="src/js/pdmarker.js"></script>-->

<?php 
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');
	include_once("show_data_on_map1.php");
	include_once('get_group.php'); 
	$root=$_SESSION['root'];
?>

<!--<script type="text/javascript" src="src/dragzoom/gzoom.js"></script>
<script type="text/javascript">
	document.write('<script type="text/javascript" src="src/js/extlargemapcontrol'+(document.location.search.indexOf('packed')>-1?'_packed':'')+'.js"><'+'/script>');
</script>
<script language="javascript" src="src/js/labeledmarker.js"></script>-->
<script type="text/javascript" src="src/js/home.js?<?php echo time();?>"></script> 