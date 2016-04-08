<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	$final_group_array=$_SESSION['final_group_array'];
	$DEBUG=0;
	$value = $_POST['add_account'];
	$tmp = explode(',',$value);
	//echo $tmp[0].','.$tmp[1].'<BR>';
	$account_id1 = $tmp[0];
	$group_id1 = $tmp[1];
	$query="select user_type from account where account_id='$account_id1'";
	$result_query=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result_query);  
	$user_type1=$row->user_type;
  
	echo"<input type='hidden' name='add_account_id' id='add_account_id' value='$account_id1'>"; 
	$query_group="SELECT group_id,group_name FROM `group` WHERE parent_account_id='$account_id' and status='1'"; 
	if($DEBUG) print_query($query_group);
	$result_group=mysql_query($query_group,$DbConnection);
	$group_size=0;
  
	while($row=mysql_fetch_object($result_group))
	{
		$group_id_local[$group_size]=$row->group_id;
		$group_name_local[$group_size]=$row->group_name;
		$group_size++;
	}  
 
	//echo $account_id1;
	$query1="SELECT ".$list_fname." FROM account_feature WHERE account_id='$account_id1'";
	if($DEBUG) print_query($query1);
	$result1=@mysql_query($query1,$DbConnection);
	$row1=@mysql_fetch_object($result1);
	for ($fi=1; $fi<=$feature_count; $fi++) 
	{
		$perm_features[$fi]=$row1->$fname[$fi];
		if($DEBUG) print_message($fname[$fi],$perm_features[$fi]); 
	}	
?>     
  <form name="manage1" method="post"><center><br>
  <fieldset class="manage_fieldset">
		<legend><strong>Add Account</strong></legend>		
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    <tr> 
      <td>Login ID</td> <td>&nbsp;:&nbsp;</td>
      <td> <input type="text" value="" name="login" id="login" class="tb1"> </td> 
    </tr>
    <tr>
      <td>Password</td> <td>&nbsp;:&nbsp;</td>
      <td> <input type="password" name="password" id="password" value="" class="tb1"> </td>
    </tr>
    <tr>
      <td>Re-Enter Password</td> <td>&nbsp;:&nbsp;</td>
      <td> <input type="password" name="re_enter_password" id="re_enter_password" class="tb1"> </td>
    </tr>
	<tr>
      <td>Name</td> <td>&nbsp;:&nbsp;</td>
      <td> <input type="text" name="user_name" id="user_name" value="" class="tb1"> </td>
    </tr>
    <tr>
	<tr>
      <td>Choose Group</td> <td>&nbsp;:&nbsp;</td>
      <td> 
	  <?php			
			echo"<select name='group_id' id='group_id'>";
			include_once('tree_hierarchy_information.php');
			printgroups($root,$final_group_array,$account_id1,$group_id1);
			echo"</select>";
			?>
  </td>
    </tr>
	
    <?php
/*    if ($user_type1=="admin")
    {
      echo '<input type="hidden" value="1" name="ac_type" id="ac_type">';
      echo '			
      <tr valign="top">
        <td> Company </td> <td>&nbsp;:&nbsp;</td>
        <td class="text">
          <input type="radio" value="same" name="company_type" id="company_type_same" checked> Same
          <input type="radio" value="new" name="company_type" id="company_type_new"> New
        </td>
      </tr>';
    }
    else*/
    {
      echo '			
      <tr valign="top">
        <td> Permission </td> <td>&nbsp;:&nbsp;</td>
        <td class="text">
          <input type="radio" value="0" name="ac_type" id="ac_type" checked> View
          <input type="radio" value="1" name="ac_type" id="ac_type"> Edit
        </td>
      </tr>
	  <tr valign="top">
        <td>Admin Permission</td> <td>&nbsp;:&nbsp;</td>
        <td class="text">
          <input type="radio" value="Current" name="perm_type" id="perm_type" /> Current
          <input type="radio" value="Child" name="perm_type" id="perm_type" checked /> Child
        </td>
      </tr>
	  ';
      echo '<input type="hidden" value="same" name="company_type" id="company_type">';
    }
    ?>
    
    <tr valign="top"> 
      <td colspan="3" align="center">
        <fieldset class="add_account" style="width:320px;">
          <legend>Account Feature</legend>
          <table class="add_account" border="0" align="center">
            <tr valign="top">
              <td colspan="6" align="center">
                <!--<input type="checkbox" name="all" onclick="javascript:check_uncheck_features(this.form)" checked/> Select All-->
                <input type="button" name="all" Onclick="javascript:return check_uncheck_features_button(this.form)" value="Select None" />
              </td>
            </tr>
            <?php
            $td_count=0;
            echo '<tr valign="top">';
            echo '<input type="hidden" value="'.$feature_count.'" name="fcount" id="fcount">';
			//echo "feature_count=".$feature_count."<br>";
			//echo "perm_features=".$perm_features."<br>";
			
            for ($fi=1; $fi<=$feature_count; $fi++)
            {
			//echo "perm_features=".$perm_features[$fi]."<br>";
              if($perm_features[$fi]==1)
              {
				//echo "in if<br>";
                $td_count++;				
				if($fname[$fi]=="device_permission")
				{
					echo '<td><input type="checkbox" value="'.$fname[$fi].'" name="account_feature[]"></td>';
				}
				else
				{
					echo '<td><input type="checkbox" value="'.$fname[$fi].'" name="account_feature[]" checked></td>';
				}
                echo '<td>'.ucfirst(strtolower($fname[$fi])).'</td>';
              }
              else
              {
			  // echo "in else<br>";
                echo '<input type="hidden" value="'.$fname[$fi].'" name="account_feature[]">';
              }
              if ($td_count%3==0) echo '</tr><tr>';
            }
            echo '</tr>';
            ?>
          </table>
        </fieldset>
      </td>                     
    </tr>
    <tr>                    									
      <td align="center"  colspan="3">
        <div style="height:10px"></div>
        <!--<input type="button" value="Test" Onclick="javascript:return test_abc(thisform)" id="test_button">&nbsp;-->
        <input type="button" value="Add" Onclick="javascript:return action_manage_account('add')" id="enter_button">&nbsp;
        <input type="reset" value="Clear">
      </td>
    </tr>
  </table>
  </fieldset>
</center>
</form>

  
<!-- ADD ACCOUNT CLOSED -->  


