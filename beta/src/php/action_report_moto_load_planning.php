<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	//echo "account_id_local=".$account_id_local."startdate=".$start_date;
	//echo "manage_id=".$manage_id."<br>";
?>
<html>
	<title>
		<?php echo $title; ?>
	</title>
	<head>
	<style>
	.headings
	{
		font-size: 9pt;	
		font-weight: bold;
		color:blue;
		text-align:center;
	}
	.main_tr
	{	
		text-align:left;
		background-color:#E3E3E3;
	}
	</style>
	<script>
		function show_hide_option(func_hide_show_id,id_for_color)
		{
			//alert("hide_show_id="+document.getElementById("hide_show_id").value+"fun_id="+func_hide_show_id+"current_status_id="+document.getElementById("current_status_id").value);
			if((func_hide_show_id==document.getElementById("hide_show_id").value) && (document.getElementById("current_status_id").value=="off"))
			{
				document.getElementById(id_for_color).style.color="blue"; // for color change
				document.getElementById(func_hide_show_id).style.display="none";
				document.getElementById("current_status_id").value="on";				
			}
			else if((func_hide_show_id==document.getElementById("hide_show_id").value) && (document.getElementById("current_status_id").value=="on"))
			{	
				document.getElementById(id_for_color).style.color="red"; // for color change
				document.getElementById(func_hide_show_id).style.display="";					
				document.getElementById("current_status_id").value="off";				
			}
			else
			{
				var all_hide_show_ids=document.getElementById("all_hide_show_ids").value;
				all_hide_show_ids=all_hide_show_ids.split(",");
				var all_headings_id=document.getElementById("all_headings_id").value;
				all_headings_id=all_headings_id.split(",");
				
				for(var i=0;i<all_headings_id.length;i++)
				{
					if(func_hide_show_id==all_hide_show_ids[i])
					{
						document.getElementById(func_hide_show_id).style.display="";
						document.getElementById(id_for_color).style.color="red";
						document.getElementById("current_status_id").value="off";
						document.getElementById("hide_show_id").value=func_hide_show_id;
					}
					else
					{
						//alert("id1s="+all_headings_id[i]);
						document.getElementById(all_hide_show_ids[i].trim()).style.display="none";
						document.getElementById(all_headings_id[i].trim()).style.color="blue";					
					}				
				}			
			}				
					
		}
	</script>
	</head>
<body>
	<input type="hidden" id="hide_show_id">	
	<input type="hidden" id="current_status_id">
	<input type="hidden" id="all_headings_id" value="main_tr_1,main_tr_2,main_tr_3">
	<input type="hidden" id="all_hide_show_ids" value="at_consignee,at_landmark,at_consigner">
	<input type="hidden" id="prev_status_id" value="off">
<table width="100%" border=0>
	<tr>
		<td>
			<table align="center" width="95%" class="menu" border="1" rules="all" bordercolor="black" border=1>
				<tr onclick="javascript:show_hide_option('at_consigner','main_tr_1')" class="main_tr">
					<td>
						<table class="headings">
							<tr id="main_tr_1">
								<td align="center">
									At Consigner
								</td>
								<td align="center">
									-
								</td>
								<td align="center">
									1046
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="at_consigner" style="display:none">
					<td>
						<table cellspacing=0 cellpadding=0 width="100%">
							<tr bgcolor="#CFE0F7">
								<td align="left">
									<table cellspacing=2 cellpadding=2>
										<tr>
											<td align="center">
											<img src='../../images/icon/pdf_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/excel_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/email.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border=1 width="100%">
										<tr>
											<td align="center">
												Transporter
											</td>
											<td align="center">
												T
											</td>
											<td align="center">
												V
											</td>
											<td align="center">
												L
											</td>
											<td align="center">
												D
											</td>
											<td align="center">
												C
											</td>
											<td align="center">
												C
											</td>
										</tr>
										<tr>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr onclick="javascript:show_hide_option('at_landmark','main_tr_2')"  class="main_tr">
					<td>
						<table class="headings" >
							<tr id="main_tr_2">
								<td align="center">
									At Landmark
								</td>
								<td align="center">
									-
								</td>
								<td align="center">
									1046
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="at_landmark" style="display:none">
					<td align="center">
						<table cellspacing=0 cellpadding=0 width="100%">
							<tr bgcolor="#CFE0F7">
								<td align="left">
									<table cellspacing=2 cellpadding=2>
										<tr>
											<td align="center">
											<img src='../../images/icon/pdf_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/excel_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/email.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border=1 width="100%">
										<tr>
											<td align="center">
												Transporter
											</td>
											<td align="center">
												T
											</td>
											<td align="center">
												V
											</td>
											<td align="center">
												L
											</td>
											<td align="center">
												D
											</td>
											<td align="center">
												C
											</td>
											<td align="center">
												C
											</td>
										</tr>
										<tr>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="main_tr" onclick="javascript:show_hide_option('at_consignee','main_tr_3')">
					<td>
						<table class="headings" >
							<tr id="main_tr_3">
								<td align="center">
									At Consignee
								</td>
								<td align="center">
									-
								</td>
								<td align="center">
									1046
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="at_consignee" style="display:none">
					<td align="center">
						<table cellspacing=0 cellpadding=0 width="100%">
							<tr bgcolor="#CFE0F7">
								<td align="left">
									<table cellspacing=2 cellpadding=2>
										<tr>
											<td align="center">
											<img src='../../images/icon/pdf_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/excel_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/email.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border=1 width="100%">
										<tr>
											<td align="center">
												Transporter
											</td>
											<td align="center">
												T
											</td>
											<td align="center">
												V
											</td>
											<td align="center">
												L
											</td>
											<td align="center">
												D
											</td>
											<td align="center">
												C
											</td>
											<td align="center">
												C
											</td>
										</tr>
										<tr>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
											<td align="center">
												-
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>

