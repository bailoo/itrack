<?php
//include_once("SessionVariable.php");

/*if (!isset($_SESSION['loguid']))
{
	echo"Please goto through  Login Page";
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
}
else
{*/
	
	
	
echo'
<html>
<head>
<title>Welcome to AMF Central Monitoring System-iembsys product Ver 1.01</title>
<LINK rel=StyleSheet 
href="../css/csslogin.css"><LINK 
rel=StyleSheet href="../css/rfnet.css">
<style type="text/css">
<!--
body {
margin: 0px;
padding: 0px;
}
#header {
width: 100%;
height: 12.5%;
top: 0%;
left: 0%;

/*border:solid;
border-color:#000000;*/
}

#content {
float: left;
width:100%;
height: 83%;
top: 12.5%;
left: 0%;
/*border:solid;
border-color:#000000;*/
}
#footer {
float: left;
width: 100%;
height: 5%;
top: 83%;
left: 0%;
/*border:solid;
border-color:#000000;*/
}
-->
</style>
<META content=en-us http-equiv=Content-Language>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<STYLE type=text/css>BODY {
	BACKGROUND-COLOR: #ffffff; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #000000; FONT-SIZE: 12pt
}
.error_message {
	FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #ff0000; FONT-SIZE: 11pt
}
.thanks_message {
	FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #000000; FONT-SIZE: 11pt
}
A:link {
	COLOR: #000000; TEXT-DECORATION: none
}
A:visited {
	COLOR: #000000; TEXT-DECORATION: none
}
A:hover {
	COLOR: #000000; TEXT-DECORATION: none
}
.table {
	BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; WIDTH: 500px; BORDER-COLLAPSE: collapse; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid
}
.table_header {
	BORDER-BOTTOM: #070707 1px solid; TEXT-ALIGN: center; BORDER-LEFT: #070707 1px solid; PADDING-BOTTOM: 2px; BACKGROUND-COLOR:#EAE1C4 ; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: BLACK; FONT-SIZE: 11pt; BORDER-TOP: #070707 1px solid; FONT-WEIGHT: bold; BORDER-RIGHT: #070707 1px solid; PADDING-TOP: 2px
}
.attach_info {
	BORDER-BOTTOM: #070707 1px solid; BORDER-LEFT: #070707 1px solid; PADDING-BOTTOM: 4px; BACKGROUND-COLOR: #ebebeb; PADDING-LEFT: 4px; PADDING-RIGHT: 4px; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #000000; FONT-SIZE: 8pt; BORDER-TOP: #070707 1px solid; BORDER-RIGHT: #070707 1px solid; PADDING-TOP: 4px
}
.table_body {
	BORDER-BOTTOM: #070707 1px solid; BORDER-LEFT: #070707 1px solid; PADDING-BOTTOM: 2px; BACKGROUND-COLOR: #ebebeb; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #000000; FONT-SIZE: 10pt; BORDER-TOP: #070707 1px solid; BORDER-RIGHT: #070707 1px solid; PADDING-TOP: 2px
}
.table_footer {
	BORDER-BOTTOM: #070707 1px solid; TEXT-ALIGN: center; BORDER-LEFT: #070707 1px solid; PADDING-BOTTOM: 2px; BACKGROUND-COLOR: #EAE1C4; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; BORDER-TOP: #070707 1px solid; BORDER-RIGHT: #070707 1px solid; PADDING-TOP: 2px
}
INPUT {
	BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BACKGROUND-COLOR: #afaeae; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #000000; FONT-SIZE: 10pt; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid
}
SELECT {
	BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BACKGROUND-COLOR: #afaeae; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #000000; FONT-SIZE: 10pt; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid
}
TEXTAREA {
	BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BACKGROUND-COLOR: #afaeae; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #000000; FONT-SIZE: 10pt; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid
}
.copyright {
	TEXT-ALIGN: right; BORDER-RIGHT-WIDTH: 0px; FONT-FAMILY: Verdana, Arial, sans-serif; BORDER-TOP-WIDTH: 0px; BORDER-BOTTOM-WIDTH: 0px; COLOR: #000000; FONT-SIZE: 9pt; BORDER-LEFT-WIDTH: 0px
}
FORM {
	PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; PADDING-TOP: 0px
}
</STYLE>

</head>
 <SCRIPT type=text/javascript>
var error="";
e_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;

function Checkit(theform) 
{
	if(theform.yourname.value=="") {
		error+="You did not enter your name\n";
	}
	
	if(theform.youremail.value=="") {
		error+="You did not enter your email\n";
	} else if(!e_regex.test(theform.youremail.value)) {
		error+="Invalid email address\n";
	}
		
	if(theform.yourmessage.value=="") {
		error+="You did not enter your message\n";
	}
	
	if(error) {
		alert("**The form returned the following errors:**\n\n" + error);
		error="";
		return false;
	} else {
		return true;
	}
}
</SCRIPT>


<body background="../image/background.png">
<FORM encType=multipart/form-data onsubmit="return Checkit(this);" method=post 
name=phmailer action=smtp_email.php><center>
<table cellpadding="0" cellspacing="0" height="100%" width="985"  border=0  style="background-image:url(../image/innerbackground.png);"><!--main table started from here-->
<tr valign="top"><td>
	<!--Header Section-->
	<div id="header">
		<table cellpadding="0" cellspacing="0" height="100%" width=985 border=0><!--table1-->
			<tr  ><!--table1 tr1-->
				<td class=tb3 valign="top" background="../image/topheader.png" style="background-repeat:no-repeat;height:73;width:367; " align="left">	                <!--table1 tr1 td1-->
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../image/vnt.png" width="53" height="56"  />&nbsp;<em>&nbsp;VNT</em><strong>-Auto Mains Failure</strong> <em><font size=2>ver1.01</font></em>
				</td><!--/table1 tr1 td1-->
				
				<td background="../image/roundedheader.png"  style="height:56" ><!--table1 tr1 td2-->
					<table width=600 height=98% border=0 align="center" >
					<tr align="left"  >
						<td align="center"><a style="text-decoration:none;border:none" href="../HomePage.php" target="_self" ><img src="../icons/house.png" alt="Home Page" style="text-decoration: none;border: none"></a></td>
						<td align="center"><a style="text-decoration:none;border:none" href="../mainscreen.php" target="_self" ><img src="../icons/compass.png" alt="RealTime Display" style="text-decoration: none;border: none"></a></td>
						<td align="center"><a style="text-decoration:none;border:none" href="../login.php" target="_self" ><img src="../icons/world.png" alt="ViewSite" style="text-decoration: none;border: none"></a></td>
                        <td align="center"><a style="text-decoration:none;border:none" href="../SelectSite03.php" target="_self" ><img src="../icons/DailyStatusReport.png" alt="Daily Status Report" style="text-decoration: none;border: none"></a></td>
						<td align="center"><a style="text-decoration:none;border:none" href="../SelectSite05.php" target="_self" ><img src="../icons/MonthlyStatusReport.png" alt="Monthly Status Report" style="text-decoration: none;border: none"></a></td>
						<td align="center"><a style="text-decoration:none;border:none" href="../SelectSite02.php" target="_self" ><img src="../icons/report.png" alt="Fault & Alarm Report" style="text-decoration: none;border: none"></a></td>
						<td align="center"><img src="../icons/chart_curve.png" alt="Graphical View"></td>
						<td align="center"><img src="../icons/doc_pdf.png" alt="View PDF Report"></td>
						<td align="center"><img src="../icons/page_excel.png" alt=" View CSV Report"></td>
						<td align="center"><img src="../icons/printer.png" alt="Print"></td>
						<td align="center"><a style="text-decoration:none;border:none" href="phpemail1.php" target="_self" ><img src="../icons/email.png" alt="Email" style="text-decoration: none;border: none"></a></td>
						<td align="center"><a style="text-decoration:none;border:none" href="../HomePage.php" target="_self" ><img src="../icons/arrow_refresh.png" alt="Page Refresh" style="text-decoration: none;border: none"></td>
						
						
					</tr>
					<!--<tr>
						<td class=tb3  colspan=100% align="center">
						<div id=fdd  >
							From: DD<SELECT name=day class=tb3><option value="01">01</option></SELECT>
							MM<SELECT name=day class=tb3><option value="01">Jan</option></SELECT>
							YY<SELECT name=day class=tb3><option value="2010">2010</option></SELECT>
							&nbsp;&nbsp;To: DD<SELECT name=day class=tb3><option value="01">01</option></SELECT>
							MM<SELECT name=day class=tb3><option value="01">Jan</option></SELECT>
							YY<SELECT name=day class=tb3><option value="2010">2010</option></SELECT>
							<img src="icons/accept.png" alt="OK">
						</div>
						</td>
					</tr>-->
					</table>
					
				</td><!--/table1 tr1 td2-->
			</tr><!--/table1 tr1-->
		</table><!--/table1-->
	</div>
	<!--/header Section-->
	

	
	
	<!--Content Section-->
	<div id="content">
		<table cellpadding="0" cellspacing="0" height="100%" width="985"  align="center" border="0"><!--table1-->
			<tr valign="top"><!--table1 tr1-->
				
				<td background="../image/rectangle3.png" style="height:100%" width="10%" valign="top"; ><!--table1 tr1 td1-->
					<table width="200" height="100%" border="0">
						
						<tr>
						<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../HomePage.php" target="_self" ><img src="../image/Home.png" style="text-decoration: none;border: none" width="150" height="38" /></td>
						</tr>
						<tr>
						<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../mainscreen.php" target="_self" ><img src="../image/RealTimeDisplay.png" style="text-decoration: none;border: none" width="150" height="38"  /></A></td>
						</tr>
						<tr>
						<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../SelectSite03.php" target="_self" ><img src="../image/DailyStatus.png" style="text-decoration: none;border: none" width="150" height="38" /></a></td>
						</tr>
						<tr>
						<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../SelectSite02.php" target="_self" ><img src="../image/Faultand Alarm.png" style="text-decoration: none;border: none" width="150" height="38" /></a></td>
						</tr>
						<tr>
						<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../dgenergymeter.php" target="_self" ><img src="../image/dgenergymeter.png" style="text-decoration: none;border: none" width="150" height="38" /></a></td>
						</tr>
						<tr>
						<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../SelectSite05.php" target="_self" ><img src="../image/SitewiseMonthly.png" style="text-decoration: none;border: none" width="150" height="38" /></a></td>
						</tr>
						<tr>
						<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../SelectSite06.php" target="_self" ><img src="../image/dgreport.png" style="text-decoration: none;border: none" width="150" height="38" /></a></td>
						</tr>
						<tr>
						<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../SelectSite07.php" target="_self" ><img src="../image/UpdowntimeReport.png" style="text-decoration: none;border: none" width="150" height="38" /></a></td>
						</tr>
						<tr>
						<!--<td scope="row" align="center">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;border:none" href="../SelectSite08.php" target="_self" ><img src="image/exchangewiseReport.png" style="text-decoration: none;border: none" width="150" height="38" /></a></td>-->
						</tr>
					</table>
				</td><!--/table1 tr1 td1-->
				
				<td align="center"  width=90% style="background-color:d5d1cf" valign="middle" >
					<!--table1 tr1 td2-->
					<TABLE class=table align=center>
						  <TBODY>
						  <TR>
							<TD class=table_header width="100%" colSpan=2>VNT-Auto Mains Failure Ver 1.01
							 </TD></TR>
						  <TR>
							<TD ></TD></TR>
						  <TR>
							<TD class=table_body width="30%">Your Name:</TD>
							<TD class=table_body width="70%"><INPUT size=30 name=yourname><SPAN 
							  class=error_message>*</SPAN></TD></TR>
						  <TR>
							<TD class=table_body width="30%">Your Email:</TD>
							<TD class=table_body width="70%"><INPUT size=30 name=youremail><SPAN 
							  class=error_message>*</SPAN></TD></TR>
						  <TR>
							<TD class=table_body width="30%">Subject:</TD>
							<TD class=table_body width="70%"><INPUT size=30 name=emailsubject> </TD></TR>
						  <TR>
							<TD class=table_body width="30%">Attach File:</TD>
							<TD class=table_body width="70%"><INPUT type=text readonly value="report1.pdf" size=30 >
					'; $file=realpath("../pdfvnt/reports.pdf"); 
							  echo" <input type=hidden name=attachment  value=\"$file\" size=30 />"; 
							  echo' </TD></TR>
						  <TR>
							<TD class=table_body width="100%" colSpan=2>Your Message:<SPAN 
							  class=error_message>*</SPAN><BR>
							  <DIV align=center><TEXTAREA rows=8 cols=60 name=yourmessage></TEXTAREA> 
							  </DIV></TD></TR>
						  <TR>
							<TD class=table_footer width="100%" colSpan=2><INPUT value=true 
							  type=hidden name=submit> <INPUT value=" Send Email " type=submit> &nbsp; <INPUT value=" Reset Form " type=reset> </TD></TR></TBODY></TABLE>
						
  
				</td><!--/table1 tr1 td1-->
				
			</tr><!--/table1 tr1-->
			
		</table><!--/table1-->
		
		
	
	</div>
	<!--/Content Section-->
	
	
	
	<!--Footer Section-->
	<div id="footer" class="tb3" align="right">
	
	&nbsp;&nbsp;&nbsp;
					 <strong><img src="../icons/drink.png" width="16" height="16" />Welcome<font color=blue>'.$user.'</font></strong>
					 <a style="text-decoration:none;border:none" href="../logout.php" target="_self" ><img src="../icons/lock.png" style="border: none"/><strong>SignOut</strong></a>
			
				
	</div>
	<!--/Footer Section-->
</td></tr></table><!--/end of main table-->
</center>
</form>
</body>
</html>
';
//}
?>