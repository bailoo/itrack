<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $query1="SELECT superuser,user,grp FROM account WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $superuser=$row1->superuser;
  $user=$row1->user;
  $grp=$row1->grp;
  
  $query1="SELECT admin_id FROM account_detail WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $admin_id=$row1->admin_id;
    
  $type_superuser = "hidden";
  $type_user = "hidden";
  $type_grp = "hidden";          
  
  if($superuser=="admin")
  { 
    $type_superuser = "text";
  }
  else if($user=="admin")
  {
    $type_user = "text";
  }
  else
  {
    $type_grp = "text";
  }

  $query = "SELECT latlng,refresh_rate from account_preference where account_id=".$account_id."";
  //echo $query;
  $res = mysql_query($query);
  $row = mysql_fetch_object($res);
  
  $ltlng = $row->ltlng;
  $refresh_rate = $row->refresh_rate;


  echo'
  <form method = "post"  name="thisform">
     <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
         <tr>
          <td colspan="3" align="center"><b>Feature Preference</b><div style="height:5px;"></div></td>    
        </tr>               
			 <tr>
						<td>LatLng</td>
						<td>&nbsp;:&nbsp;</td>
						<td>';
							 
               if($ltlng == 1)                 
               {   
                  echo'<input type="radio" name="latlng" id="latlng" value="1" checked>YES&nbsp;';
                  echo'<input type="radio" name="latlng" id="latlng" value="0">NO&nbsp;';
               }
							 else if($ltlng == 0)                 
               {   
                  echo'<input type="radio" name="latlng" id="latlng" value="1">YES&nbsp;';
                  echo'<input type="radio" name="latlng" id="latlng" value="0" checked>NO&nbsp;';                  
               }							
              
              echo'
						</td>
				</tr>
				<tr>
						<td>Refresh Rate</td>
						<td>&nbsp;:&nbsp;</td>
						<td>';
							 
               if($refresh_rate == 1)                 
               {   
                  echo'<input type="radio" name="refresh_rate" id="refresh_rate" value="1" checked>YES&nbsp;';
                  echo'<input type="radio" name="refresh_rate" id="refresh_rate" value="0">NO&nbsp;';
               }
							 else if($refresh_rate == 0)                 
               {   
                  echo'<input type="radio" name="refresh_rate" id="refresh_rate" value="1">YES&nbsp;';
                  echo'<input type="radio" name="refresh_rate" id="refresh_rate" value="0" checked>NO&nbsp;';                  
               }	
                          
               echo'
						</td>
				</tr> 
        
        <tr>
          <td></td>
        </tr>
        											
			  <tr>                    									
					<td align="center" colspan="3"><input type="button" onclick="javascript:action_setting_feature_pref(thisform)" value="Enter" id="enter_button">&nbsp;<input type="reset" value="Clear"></td>
				</tr>
    </table>
  </form>';
?>
