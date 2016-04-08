<?php
	include_once('SessionVariable.php');
	include_once("PhpMysqlConnectivity.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>ItrackSolution TripInfo</title>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<LINK REL="StyleSheet" HREF="menu.css">

	<script type=text/javascript src="menu.js"></script>

	<script type="text/javascript" language="javascript" src="datetimepicker_sd.js"></script>
	<script type="text/javascript" language="javascript" src="datetimepicker.js"></script>

	<style style type="text/css">
	<!--
	div.scroll {
	height: 200px;
	width: 650px;
	overflow: auto;
	border: 1px solid #666;
	padding: 8px;
	-->
	</style>
	
	<script language="javascript">

	function validate_form(obj) 
	{
		var option_choices=0;
		var numtype = 0;
		var i = 0;
		var s = obj.elements['vehicleserial[]'];
		//alert(s.length);

		for(i=0;i<s.length;i++)
		{
			if(s[i].checked)
				numtype = 1;
		}
		if(numtype==0)
		{
			alert("Please Select At Least One Vehicle");
			return false;
		}
		if(obj.date.value=="")
		{
			alert("Please Enter Start Date");
			obj.date.focus();
			return false;
		}
	}

	function updateFields(obj)
	{
		if(obj.selectall.checked)
		{
			obj.option1.checked="true";
			obj.option2.checked="true";
			obj.option3.checked="true";
			obj.option4.checked="true";
			obj.option5.checked="true";
			obj.option6.checked="true";			
		}
		else if(obj.selectall.checked==false)
		{
			obj.option1.checked=false;
			obj.option2.checked=false;
			obj.option3.checked=false;
			obj.option4.checked=false;
			obj.option5.checked=false;
			obj.option6.checked=false;			
		}
	}

	function All(obj)
	{
		if(obj.all.checked)
		{
			var i;
			var s = obj.elements['vehicleserial[]'];
			for(i=0;i<s.length;i++)
				s[i].checked="true";		
		}
		else if(obj.all.checked==false)
		{
			var i;
			var s = obj.elements['vehicleserial[]'];
			for(i=0;i<s.length;i++)
				s[i].checked=false;
		}
	}
	
	
	function submit_form()
	{
    var time_interval=5;	
    var date = new Date();      
    var dest = "xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml"	
  
  	var obj = document.forms[0];	 
  	var dmode=2;
  	var s = obj.elements['vehicleserial[]'];
  	var len = s.length;	
  	var n=new Array();
  	var vid;
  	vid = "";
  	var i = 0;
  	var j=0;
  	var num=0;
  	var cnt=0;
  	
  	if (len != undefined)
  	{
  		for(i=0;i<len;i++)
  		{
  			if(s[i].checked)
  			{
  				if(cnt==0)
  				{
  				vid =  vid + s[i].value;
  				cnt=1;
  				}
  				else
  				{
  					vid = vid+ "," + s[i].value;
  				}
  			}
  			num = 1;
  			j++;				
  		}
  	}
  	else 
  	{	
  		if (s.checked)
  		{
  			//alert('radio_button3='+rad_but1);
  			vid = vid + s.value;				
  				//alert(vid);
			var dt = obj.date.value;
			
			//alert(dt);
			
			var tmp = CalculateTripDate(dt);
			var date_final=tmp.split("/");
  			var st = date_final[0];			
  			var et = date_final[1];	
  		
  			//document.myform.action="Full_data_prev.php?n="+vid+"&StartDate="+st+" &EndDate="+et+"&radio_but="+rad_but1+ "&dispmode="+dmode;
  			document.forms[0].action="get_vehicles_summary_data.php?vserial="+vid+"&startdate="+st+" &enddate="+et+"&mode="+dmode+" &time_interval="+time_interval+"&xml_file="+dest;
  			//document.forms[0].target="_blank";
  			document.forms[0].submit();		
  		}
  	}
  
  	if(dmode==2 && len != undefined)
  	{			
  		var dt = obj.date.value;
		var tmp = CalculateTripDate(dt);
		var date_final=tmp.split("/");
		var st = date_final[0];			
		var et = date_final[1];	

   		document.forms[0].action="get_vehicles_summary_data.php?vserial="+vid+"&startdate="+st+" &enddate="+et+"&mode="+dmode+" &time_interval="+time_interval+"&xml_file="+dest;
  		//document.forms[0].target="_blank";
  		document.forms[0].submit();	
  	}
  } // function closed	
  
  function CalculateTripDate(date)
	{
		//alert("In Calculate function: "+date);

		datetime = date;
		var date_ist_gmt1=datetime.split("/");
		
		var d = new Date();
		var year1= d.getYear();
		//alert(year1);

		//alert("D1="+date_ist_gmt3[2]+" ,"+date_ist_gmt3[1]+" ,"+date_ist_gmt3[0]);
		//alert("D2="+date_ist_gmt2[2]+" ,"+date_ist_gmt2[1]+" ,"+date_ist_gmt1[0]);

		d.setDate(date_ist_gmt1[2]);
		d.setMonth(date_ist_gmt1[1]);
		d.setYear(date_ist_gmt1[0]);
		d.setHours("06");
		d.setMinutes("30");
		d.setSeconds("00");

		var datetime1=d.getTime();      
		//alert('datetime1'+datetime1);
		var datetime2=d.getTime()+86400000;      
		//alert('datetime2'+datetime2);
	  
		var getfulldate=new Date();

		getfulldate.setTime(datetime1);
		var year=getfulldate.getYear();
	  
		var Final_year=0;
	  
		if(year>2000) 
		{		
			Final_year=year;
		}
		else
		{
			Final_year=year+1900;  
		}

		var month=getfulldate.getMonth();
		if(month<=9)
		{
			month='0'+month;
		}
		var day=getfulldate.getDate();
		if(day<=9)
		{
			day='0'+day;
		}
		var hour=getfulldate.getHours();
		if(hour<=9)
		{
			hour='0'+hour;
		}
		//alert('hour='+hour);
		var minute=getfulldate.getMinutes();
		if(minute<=9)
		{
			minute='0'+minute;
		}
		//alert('minute='+minute);
		var second=getfulldate.getSeconds();
		if(second<9)
		{
			econd='0'+second;
		}
		var datetimefinal1=Final_year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
		
		getfulldate.setTime(datetime2);
		year=getfulldate.getYear();
	  
		Final_year=0;
	  
		if(year>2000) 
		{		
			Final_year=year;
		}
		else
		{
			Final_year=year+1900;  
		}

		month=getfulldate.getMonth();
		if(month<=9)
		{
			month='0'+month;
		}
		day=getfulldate.getDate();
		if(day<=9)
		{
			day='0'+day;
		}
		hour=getfulldate.getHours();
		if(hour<=9)
		{
			hour='0'+hour;
		}
		//alert('hour='+hour);
		minute=getfulldate.getMinutes();
		if(minute<=9)
		{
			minute='0'+minute;
		}
		//alert('minute='+minute);
		second=getfulldate.getSeconds();
		if(second<9)
		{
			econd='0'+second;
		}

		var datetimefinal2=Final_year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
		//alert("datetime="+datetimefinal1+'/'+datetimefinal2);	
		return  datetimefinal1+'/'+datetimefinal2;    
	}// JavaScript Document
	</script>

</head>
  
<?php
$case = $_GET['case'];
?>
  
<body bgcolor="white">

  <?php
	//echo "access=".$access;
		//if($access=="0")
		  //include('menu.php');

		//else 
		  include('usermenu.php');
	?>

		<td class="bg" valign="top">
		
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								<?php
								
									echo'<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Trip Summary Report</td>';
										
								?>
							</tr>
						</table>
						
<?php

  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
  //date_default_timezone_set("Asia/Calcutta");
	$sd = date("Y/m/d")." 00:00:00";
  $ed = date("Y/m/d H:i:s");	
	
  echo'																											
				<form  method="post" name="thisform" onSubmit="javascript:return validate_form(thisform)" action="javascript:submit_form();">
								
				<br>								

				<table border=1 rules=all bordercolor="#e5ecf5" cellspacing=0 cellpadding=0 align="center">		
					<tr>
						<td class="text"><b>&nbsp;Select&nbsp;Vehicle</b></td>					
					</tr>

					<tr>
						<td class="text" align="center">
							<input type="checkbox" name="all" value="1" onClick="javascript:All(this.form)">&nbsp;Select All
						</td>
					
					</tr>
				</table>

				<br>
				<table border=0  align="center" cellspacing=0 cellpadding=0  width="100%">
					<tr>
						<td align="center">							
							<div style="overflow: auto;height: 150px; width: 650px;" align="center">
								<table border=1 rules=rows bordercolor="lightblue" cellspacing=0 cellpadding=0 align="center" width="100%">	';						
									include('show_vehicles_chk.php');						                  										
								echo'</table>
							</div>
						</td>
					</tr>
				</table>				
				<br>		
    <br>';
													
//date_default_timezone_set('Asia/Calcutta');
$date=date("Y/m/d");	
//$EndDate=date("Y/m/d H:i:s");	

echo'
<table border=0 cellspacing=0 cellpadding=3 align="center">	
	<tr>
		<td  class="text"><b>Select Date : </b></td>
		<td>
			<table>
				<tr>
					<td  class="text">	</td>
					<td class="text">
				      <input type="text" id="date1" name="date" value="'.$date.'" size="10" maxlength="19">
		
							<!--<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
								<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>-->											
					</TD>
																
					</td>
				</tr>
			</table>
		<td>
	</tr>									
</table>	
				<br><table border=0 align="center">						
					<tr>
						<td class="text" align="center"><input type="submit" value="Show Trip Report"></td>
					</tr>
				</table>				
		</form>';	
		
		//echo '<br><center><font size=6><strong>PAGE IS UNDER CONSTRUCTION</strong></font>';

?>				
			 </TD>
		 </TR>
	</TABLE> 
</td>

</tr>
</TABLE>	

	</body>
</html>
