<?php    
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $edit_account = $_POST['edit_account'];
  
  echo 'edit##';  
      
  $DEBUG=0;
  
  $query="SELECT superuser,user,grp FROM account WHERE account_id='$edit_account' ORDER BY account_id";
  
  if($DEBUG == 1)
    print_query($query);
    
  $result = mysql_query($query,$DbConnection);
  $row = mysql_fetch_object($result);
  $superuser_2 = $row->superuser;
  $user_2 = $row->user;
  $grp_2 = $row->grp;
  //$password = $row->password;
  
  if($user_2 == "admin")
    $login = $superuser_2;
  else if($grp_2 == "admin")
    $login = $user_2; 
  else
    $login = $grp_2;
        
  echo'
  <br><fieldset class="manage_fieldset">
		<legend><strong>Edit Account</strong></legend>
		
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">   
    <tr> 
      <td>Login</td> <td>&nbsp;:&nbsp;</td>
      <td> <input type="text" value="'.$login.'" name="login" id="login" class="tb1"> </td> 
    </tr>
    <tr>
      <td>Password</td> <td>&nbsp;:&nbsp;</td>
      <td> <input type="password" name="password" id="password" value="" class="tb1"> </td>
    </tr>
    <tr>
      <td>Re-Enter Password</td> <td>&nbsp;:&nbsp;</td>
      <td> <input type="password" name="re_enter_password" id="re_enter_password" class="tb1"> </td>
    </tr> ';
    
    if ($account_type=="Root")
    {
      echo '<input type="hidden" value="1" name="ac_type" id="ac_type">';
      echo '			
      <tr>
        <td> Company </td> <td>&nbsp;:&nbsp;</td>
        <td class="text">
          <input type="radio" value="same" name="company_type" id="company_type_same" checked> Same
          <input type="radio" value="new" name="company_type" id="company_type_new"> New
        </td>
      </tr>';
    }
    else if (($permission_type=="Distributor") || ($account_type=="Superuser"))
    {
      echo '			
      <tr>
        <td> Permission </td> <td>&nbsp;:&nbsp;</td>
        <td class="text">
          <input type="radio" value="0" name="ac_type" id="ac_type" onclick="javascript:set_company_type(1,this.form)" checked> User
          <input type="radio" value="1" name="ac_type" id="ac_type" onclick="javascript:set_company_type(0,this.form)"> Distributor
        </td>
      </tr>';
      echo '
      <tr>
        <td> Company </td> <td>&nbsp;:&nbsp;</td>
        <td class="text">
          <input type="radio" value="same" name="company_type" id="company_type_same" checked> Same
          <input type="radio" value="new" name="company_type" id="company_type_new"> New
        </td>
      </tr>';
    }
    else
    {
      echo '			
      <tr>
        <td> Permission </td> <td>&nbsp;:&nbsp;</td>
        <td class="text">
          <input type="radio" value="0" name="ac_type" id="ac_type" checked> View
          <input type="radio" value="1" name="ac_type" id="ac_type"> Edit
        </td>
      </tr>';
      echo '<input type="hidden" value="same" name="company_type" id="company_type">';
    }

    if (($permission_type=="Distributor") || ($account_type=="Root") || ($account_type=="Superuser"))
    {
      echo '<input type="hidden" value="new" name="perm_type" id="perm_type">';
      echo '<input type="hidden" value="-1" name="admin_perm" id="admin_perm">';
    }
    else
    {
      echo '
    <tr> 
      <td colspan="3" align="center">
        <fieldset class="add_account" style="width:320px;">
          <legend>Admin Permission</legend>
          <table class="add_account" border="0" align="center">
            <tr>
              <td colspan="6" align="left" class="text"> 
                <input type="radio" value="new" name="perm_type" id="perm_type" onclick="javascript:show_admin_perm(1,this.form)" checked/> New
                <input type="radio" value="select" name="perm_type" id="perm_type" onclick="javascript:show_admin_perm(2,this.form)"/> Select
                <select name="admin_perm" id="admin_perm" style="display:none" onChange="javascript:set_features_from_select(this.form)">';
                for ($pi=1; $pi<=$perm_count; $pi++)
                {
                  echo '<option value="'.$perm_value[$pi].'"> '.$perm_name[$pi].' </option>'; 
                }
      echo '
                </select>';
                for ($pi=1; $pi<=$perm_count; $pi++)
                {
                  echo '<input type="hidden" value="'.$perm_features[$pi].'" name="parm_name_'.$perm_value[$pi].'" id="parm_name_'.$perm_value[$pi].'">'; 
                }
      echo '
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>';
    }
    
    echo'
    <tr> 
      <td colspan="3" align="center">
        <fieldset class="add_account" style="width:320px;">
          <legend>Account Feature</legend>
          <table class="add_account" border="0" align="center">
            <tr>
              <td colspan="6" align="center">
                <!--<input type="checkbox" name="all" onclick="javascript:check_uncheck_features(this.form)" checked/> Select All-->
                <input type="button" name="all" Onclick="javascript:return check_uncheck_features_button(thisform)" value="Select None" />
              </td>
            </tr>';
            
            $td_count=0;
            echo '<tr>';
            echo '<input type="hidden" value="'.$feature_count.'" name="fcount" id="fcount">';
            for ($fi=1; $fi<=$feature_count; $fi++)
            {
              echo '<input type="hidden" value="'.$fname[$fi].'" name="fname'.$fi.'" id="fname'.$fi.'">';
              if ($fvalue[$fi]=="1")
              {
                $td_count++;
                echo '<td><input type="checkbox" value="1" name="'.$fname[$fi].'" id="'.$fname[$fi].'" checked></td>';
                echo '<td>'.ucfirst(strtolower($fname[$fi])).'</td>';
              }
              else
              {
                echo '<input type="hidden" value="0" name="'.$fname[$fi].'" id="'.$fname[$fi].'">';
              }
              if ($td_count%3==0) echo '</tr><tr>';
            }
            echo '
            </tr>            
          </table>
        </fieldset>
      </td>                     
    </tr>
    <tr>                    									
      <td align="center"  colspan="3">
        <div style="height:10px"></div>        
        <input type="button" value="Update" disabled="true" id="u_d_enter_button" Onclick="javascript:return action(thisform,\'edit\')" id="enter_button">&nbsp;        
        <input type="reset" value="Clear">&nbsp;
        <input type="button" value="Delete Account" disabled="true" id="u_d_enter_button" Onclick="javascript:return action(thisform,\'delete\')" id="enter_button">&nbsp;
      </td>
    </tr>
  </table>
  </fieldset>
  ';