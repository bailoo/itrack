<html>
	<title>test</title>
	<head>
	<script>
	 function report_pdf_only(target_file)
    {
      //alert("reportcsv");
     // document.forms[0].action = target_file;    
      document.forms[0].submit();
    }
	</script>
	</head>
<body>
	<form name="temp_page" action="text_ex1.php" method="POST" target="_blank">
    <?php
	$t='';
	$t=$t.'<table class="morpion" width="100%"><tr bgcolor="gray"><td >O</td><td >O</td></tr>
	<tr bgcolor="green"><td >O</td><td >O</td></tr></table>'; 
echo'<textarea id="tw" name="tw" cols="40" rows="20" style="visibility:hidden;">'.$t.'</textarea>';	
	//echo'<input TYPE="hidden" value='.$t.' name="t">';
	echo'<input type="button" onclick="javascript:report_pdf_only();" value="Get PDF" class="noprint">';
	echo'</form>';
	?>

</body>
</html>