<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
        include_once("util_account_detail.php");
	$account_id_local=$_POST['setting_account_id'];
	$tmp = explode(',',$account_id_local);
	//echo $tmp[0].','.$tmp[1].'<BR>';
	$account_id1 = $tmp[0];
	$group_id1 = $tmp[1];
	  

	$query="SELECT name,email,address1,address2,city,state,country,zip,phone FROM account_detail WHERE account_id='$account_id1'";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$name=$row->name;
	$email=$row->email;
	$address1=$row->address1;  
	$address2=$row->address2;  
	$city=$row->city;  
	$state=$row->state;  
	$country=$row->country;      
	$zip=$row->zip;    
	$phone=$row->phone; 
	echo'<form method = "post"  name="setting">
			<input type="hidden" name="local_account_id" id="local_account_id" value="'.$account_id1.'">';			
		echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="3" align="center"><b>Account Detail</b><div style="height:5px;"></div></td>    
				</tr>     

				<tr>
					<td>Name</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="user_name" id="user_name" value="'.$name.'"></td>
				</tr>
				
				<tr>
					<td>Address1</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="address1" id="address1" value="'.$address1.'"></td>
				</tr> 

				<tr>
					<td>Address2</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="address2" id="address2" value="'.$address2.'"></td>
				</tr> 		

				<tr>
					<td>City</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="city" id="city" value="'.$city.'"></td>
				</tr> 		

				<tr>
					<td>State</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="state" id="state" value="'.$state.'"></td>
				</tr> 		        	

				<tr>
					<td>Country</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="country" id="country" value="'.$country.'"></td>
				</tr> 		

				<tr>
					<td>Zip</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="zip" id="zip" value="'.$zip.'"></td>
				</tr> 	        					

				<tr>
					<td>Phone No</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="phoneno" id="phoneno" value="'.$phone.'"></td>
				</tr> 									
				<tr>
					<td>E-MailID</td>
					<td>&nbsp;:&nbsp;</td>
					<td><input type ="text" name="email" id="email" value="'.$email.'"></td>
				</tr> 							          

				<tr>
					<td></td>
				</tr>

				<tr>                    									
					<td align="center" colspan="3"><input type="button" onclick="javascript:action_setting_account_detail(setting)" value="Enter" id="enter_button">&nbsp;<input type="reset" value="Clear"></td>
				</tr>
			</table>			
		</form>';
                 if($user_type=="raw_milk" || $user_type=='substation' || $user_type=="plant_raw_milk" || $user_type=="hindalco_invoice" )
            {
                    
                }
                else
                {
			echo'<center><a href="#" onclick="javascript:setting_account_detail(\'src/php/setting_update_choose_account.php\',\'first_stage\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
                }
?>
