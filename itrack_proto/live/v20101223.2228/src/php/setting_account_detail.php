<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  /*$query1="SELECT superuser,user,grp FROM account WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $superuser=$row1->superuser;
  $user=$row1->user;
  $grp=$row1->grp;*/
  
  $query="SELECT email FROM account WHERE account_id='$account_id'";
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  $email=$row->email;  
      
  $query="SELECT name,address1,address2,city,state,country,zip,phone FROM account_detail WHERE account_id='$account_id'";
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  $name=$row->name;
  $address1=$row->address1;  
  $address2=$row->address2;  
  $city=$row->city;  
  $state=$row->state;  
  $country=$row->country;      
  $zip=$row->zip;    
  $phone=$row->phone; 
  

  echo'
  <form method = "post"  name="thisform">
     <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
         <tr>
          <td colspan="3" align="center"><b>Account Detail</b><div style="height:5px;"></div></td>    
        </tr>     
          
			<tr>
						<td>Name</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="text" name="user_name" id="user_name" value="'.$name.'">
						</td>
				</tr>
				<tr>
						<td>Address1</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="text" name="address1" id="address1" value="'.$address1.'">
						</td>
				</tr> 
				
				<tr>
						<td>Address2</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="text" name="address2" id="address2" value="'.$address2.'">
						</td>
				</tr> 		
        
        <tr>
						<td>City</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="text" name="city" id="city" value="'.$city.'">
						</td>
				</tr> 		
				
        <tr>
						<td>State</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="text" name="state" id="state" value="'.$state.'">
						</td>
				</tr> 		        	
				
        <tr>
						<td>Country</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="text" name="country" id="country" value="'.$country.'">
						</td>
				</tr> 		
        
        <tr>
						<td>Zip</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="text" name="zip" id="zip" value="'.$zip.'">
						</td>
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
					<td align="center" colspan="3"><input type="button" onclick="javascript:action_setting_account_detail(thisform)" value="Enter" id="enter_button">&nbsp;<input type="reset" value="Clear"></td>
				</tr>
    </table>
  </form>';
?>
