<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  $DEBUG=0;
  
  // GET ACCOUNT ADMIN IDs FOR EDIT FEATURE
  // SELF
  /*$query = "SELECT superuser,user,grp FROM account WHERE account_id='$account_id' AND status='1'";
  $result = mysql_query($query, $DbConnection);  
  $row = mysql_fetch_object($result);
  $superuser_self = $row->superuser;
  $user_self = $row->user;
  $grp_self = $row->grp;  */
  
  // OTHERS
  $query = "SELECT account_id, superuser,user,grp FROM account WHERE ".
  "account_id IN (SELECT account_id FROM account_detail WHERE admin_id IN (SELECT admin_id from account_detail WHERE account_id='$account_id')) ".
  "AND status='1'";
  
  if($DEBUG == 1)
    print_query($query);
    
  $result = mysql_query($query, $DbConnection);
  
  $j=0;
  while($row = mysql_fetch_object($result))
  {
    $account_sub = $row->account_id;
    $superuser_sub = $row->superuser;
    $user_sub = $row->user;
    $grp_sub = $row->grp;
    
    if($user_sub=="admin")
    {
      $account_2[$j] = $account_sub;
      $account_name[$j] = $superuser_sub." (superuser)";
    }      
    else if($grp_sub=="admin")
    {
      $account_2[$j] = $account_sub;
      $account_name[$j] = $user_sub." (user)";    
    }        
    else
    {
      $account_2[$j] = $account_sub;
      $account_name[$j] = $grp_sub." (grp)";    
    }  
    
    if($DEBUG ==1)
      echo  $account_2[$j]." ,".$account_name[$j]."<br>";  
            
    $j++;
  }
?>             

<center>     
<form method = "post"  name="thisform">

  <fieldset class="manage_fieldset">
		<legend><strong>Account</strong></legend>  
		
		<input type="radio" name="option" value="new" onclick="javascript:show_add('manage','add_account');document.getElementById('exist').style.display='none';document.getElementById('edit_div').style.display='none';">New
		<input type="radio" name="option" value="exist" onclick="javascript:document.getElementById('edit_div').style.display='none';document.getElementById('exist').style.display='';">Existing
  
<!-- ADD ACCOUNT OPENS -->
  
<!-- ADD ACCOUNT CLOSED -->  


<!-- EDIT ACCOUNT OPTION OPENS -->

  <fieldset class="manage_fieldset" style="display:none;" id="exist">
		<legend><strong>Existing Account</strong></legend>		 
     <table class="manage_interface" border="0" align="center">
     <tr valign="true">
     <td>
       Account Name <select name="edit_account" id="edit_account" Onchange="javascript:return show_edit('manage','edit_account');">
              
        <?php
  		    echo '<option value="select">Select</option>';
          echo '<option value="'.$account_id.'">Self</option>';
          for($i=0;$i<$j;$i++)
          {
            echo '<option value="'.$account_2[$i].'">'.$account_name[$i].'</option>';
          }		  
        ?>      
       
       </select>
     </td>
     </tr>
     <tr><td></td></tr>
     <tr><td>     
     </td></tr>
     </table>
		
  </fieldset>

<!-- EDIT ACCOUNT OPTION CLOSED -->


<!-- EDIT ACCOUNT OPENS -->

<div align="center" id="edit_div" style="display:none;"></div>

<!-- EDIT ACCOUNT CLOSED-->

</fieldset>

</form>

</center>