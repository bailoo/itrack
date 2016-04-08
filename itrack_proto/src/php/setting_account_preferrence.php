<?php
  include_once('util_session_variable.php');	include_once('util_php_mysql_connectivity.php');
  $account_id_local=$_POST['setting_account_id'];

  $query1="SELECT user_id FROM account WHERE account_id='$account_id_local'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $user_id=$row1->user_id;
  
  $query1="SELECT admin_id FROM account_detail WHERE account_id='$account_id_local'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $admin_id=$row1->admin_id;

  $query = "SELECT latlng,refresh_rate from account_preference where account_id=".$account_id_local."";
  //echo $query;
  $res = mysql_query($query);
  $row = mysql_fetch_object($res);
  
  $latlng = $row->latlng;
  $refresh_rate = $row->refresh_rate;
  //echo "latlng=".$ltlng."<br>"." refresh_rate=".$refresh_rate;

  echo'<form method = "post"  name="thisform">
		<input type="hidden" id="local_account_id" value="'.$account_id_local.'">
     <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
         <tr>
          <td colspan="3" align="center"><b>Feature Preference</b><div style="height:5px;"></div></td>    
        </tr>               
			 <tr>
						<td>LatLng</td>
						<td>&nbsp;:&nbsp;</td>
						<td>';
			  			 
               if($latlng == 1)                 
               {   
                  echo'<input type="radio" name="latlng" id="latlng" value="1" checked>YES&nbsp;';
                  echo'<input type="radio" name="latlng" id="latlng" value="0">NO&nbsp;';
               }
				else if($latlng == 0)                 
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
    </table><br>
	<center><a href="javascript:show_option(\'setting\',\'account_preferrence_prev\');" class="back_css">&nbsp;<b>Back</b></a></center> 
  </form>';
?>
