<?php
	include_once('util_php_mysql_connectivity.php');
	$query="SELECT device_imei_no from device_assignment where account_id IN('930','960','103','143','166','178','181','185','187','189','209','210','219','223','236','299','414','432','433','434','435','436','445','575','431','781','782','783','444','446','447','536','587','700','448','605','461','486','526','527','882','590','599','591','600','592','593','601','594','602','595','603','596','604','597','598','606','702','815','827','907','915','916','919','922','924','925','970','926','927','928','929','931','932','933','934','935','936','961','962','963','964','965','968','937','974','975','976','938','939','940','967','973','978','941','972','942','943','944','966','977','945','980','946','947','948','981','949','969','951','971','952','953','954','955','956','957','958','959','979','1045','1059','1064') and status=1";
	$result=mysql_query($query,$DbConnection);
	$i=0;
	while($row=mysql_fetch_object($result))
	{
		echo "\n=".$i;
		$insert_query="Insert INTO device_assignment (account_id,device_imei_no,status,create_id,create_date) VALUES('1083','$row->device_imei_no',1,1,'2014-02-18 11:17:17')";
		$insert_query_result=mysql_query($insert_query,$DbConnection);
		$insert_query1="Insert INTO device_assignment (account_id,device_imei_no,status,create_id,create_date) VALUES('1084','$row->device_imei_no',1,1,'2014-02-18 11:17:17')";
		$insert_query_result1=mysql_query($insert_query1,$DbConnection);
		$i++;
	}	
?>
