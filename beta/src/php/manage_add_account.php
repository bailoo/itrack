<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$root=$_SESSION['root'];
	$final_group_array=$_SESSION['final_group_array'];
	//echo "size=".sizeof($final_group_array);
	$DEBUG=0;
	$value = $_POST['add'];
	$tmp = explode(',',$value);
	$account_id1 = $tmp[0];
	//echo"account_id=".$account_id1."<br>";
	$group_id1 = $tmp[1];
	$size_utype = sizeof($user_typeid_session);

	if($DEBUG)
	{
		for($i=0;$i<$size_utype_session;$i++)
		{	    
			echo"<br>user_type_id_local=".$user_type_id_session[$i]." ,user_type_name_local=".$user_type_name_session[$i];
		}

		//echo "<br>size_feature_session=".$size_feature_session;
		for($i=0;$i<$size_feature_session;$i++)
		{
			echo"<br>feature_id_session=".$feature_id_session[$i]." ,feature_name_session=".$feature_name_session[$i];
		}	
	} 
	echo"<input type='hidden' name='add_account_id' id='add_account_id' value='$account_id1'>";
	echo"<input type='hidden' name='feature_count' id='feature_count' value='$feature_count'>";
	echo"<input type='hidden' name='add_group_id' id='add_group_id' value='$group_id1'>";	
?>     
	<form name="manage1" method="post"><center><br>
		<center><b>Add Account</b></center>
		<br>
		<table border="0" align=center class="manage_account_interface" cellspacing="2" cellpadding="2">
			<tr> 
				<td>
					Login ID
				</td> 
				<td>
					&nbsp;:&nbsp;
				</td>
				<td> 
					<input type="text" value="" name="login" id="login" class="tb1" onkeyup="unchecked_substation()"> 
				</td> 
			</tr>
			<tr>
				<td>
					Password
				</td> 
				<td>
					&nbsp;:&nbsp;
				</td>
				<td> 
					<input type="password" name="password" id="password" value="" class="tb1"> 
				</td>
			</tr>
			<tr>
				<td>
					Re-Enter Password
				</td> 
				<td>
					&nbsp;:&nbsp;
				</td>
				<td> 
					<input type="password" name="re_enter_password" id="re_enter_password" class="tb1"> 
				</td>
			</tr>
			<tr>
				<td>
					Name
				</td>
				<td>
					&nbsp;:&nbsp;
				</td>
				<td> 
					<input type="text" name="user_name" id="user_name" value="" class="tb1"> 
				</td>
			</tr>
			<tr>
				<td>
					Distance Variable
				</td> 
				<td>
					&nbsp;:&nbsp;
				</td>
				<td> 
					<input type="text" name="distance_variable" id="distance_variable" value="" class="tb1" onkeypress="javascript:IntegerAndDecimal(event,this,true);" onMouseDown="DisableRightClick(event)"> 
				</td>
			</tr>
			<tr>
				<td>
					Choose Group
				</td> 
				<td>
					&nbsp;:&nbsp;
				</td>
				<td> 
					<?php			
					echo"<select name='group_id' id='group_id'>";
							include_once('tree_hierarchy_information.php');
							printgroups($root,$final_group_array,$account_id1,$group_id1);
					echo"</select>";

					if($DEBUG==1)
					{
						echo "account_id=".$tmp[0].','.$tmp[1].'<BR>'.$account_id1."group_id=".$group_id1."<br>";
						echo "query=".$query."<br>";
						echo "query_group=".$query_group;
					}
					?>
				</td>
			</tr>
	
		<?php
			$flag_substation=0;
			$flag_raw_milk=0;
			$flag_plant_raw_milk=0;
			$show_combo = 0;
			$flagcombo=array();
			$flagcombo[]=array("KEY"=>"0","DISPLAY"=>"None");	
			for($k=0;$k<$size_feature_session;$k++)
			{
				if($feature_name_session[$k] == "substation")
				{
					$flag_substation = 1;
					$show_combo = 1;
					$flagcombo[]=array("KEY"=>"substation","DISPLAY"=>"PolyPack");
				}
				if($feature_name_session[$k] == "raw_milk")
				{
					$flag_raw_milk = 1;
					$show_combo = 1;
					$flagcombo[]=array("KEY"=>"raw_milk","DISPLAY"=>"Raw Milk");
				}
                                if($feature_name_session[$k] == "proc_admin")
				{
					$flag_raw_milk = 1;
					$show_combo = 1;
					$flagcombo[]=array("KEY"=>"proc_admin","DISPLAY"=>"Proc. Admin");
				}
				if($feature_name_session[$k] == "plant_raw_milk")
				{
					$flag_plant_raw_milk = 1;
					$show_combo = 1;
					$flagcombo[]=array("KEY"=>"plant_raw_milk","DISPLAY"=>"Plant Raw Milk");
				}
				  if($feature_name_session[$k] == "plant_admin")
				{
					$flag_plant_raw_milk = 1;
					$show_combo = 1;
					$flagcombo[]=array("KEY"=>"plant_admin","DISPLAY"=>"Plant Admin");
				}	
				if($feature_name_session[$k] == "hindalco_invoice")
				{
					$flag_plant_raw_milk = 1;
					$show_combo = 1;
					$flagcombo[]=array("KEY"=>"hindalco_invoice","DISPLAY"=>"Hindalco Invoice");
				}
			}
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
		echo'<tr valign="top">
				<td> Permission </td> 
				<td>&nbsp;:&nbsp;</td>
				<td class="text">
					<input type="radio" value="0" name="ac_type" id="ac_type" checked> View
					<input type="radio" value="1" name="ac_type" id="ac_type"> Edit
				</td>
			</tr>
		<!--<tr valign="top">
				<td>Admin Permission</td> 
				<td>&nbsp;:&nbsp;</td>
				<td class="text">
				<input type="radio" value="Current" name="perm_type" id="perm_type" /> Current
				<input type="radio" value="Child" name="perm_type" id="perm_type" checked /> Child
				</td>
			</tr>-->';
	echo'<tr valign="top">
			<td>Select UserType : </td> 
			<td>&nbsp;:&nbsp;</td>
			<td class="text">';
			if($show_combo = 1)
			{
				echo'<select name="sub_utype" id="sub_utype" onchange=validate_substation_type_user(this.value);>';
				foreach($flagcombo as $fc)
				{
					echo '<option value="'.$fc['KEY'].'">'.$fc['DISPLAY'].'</option>';
				}
				echo'
				</select>';
			}
		echo'</td>
		</tr>';
	  
	  /*echo '
	  <tr valign="top">
        <td>Route Assignment from SubStation </td> <td>&nbsp;:&nbsp;</td>
        <td class="text">
          <input type="checkbox" value="substation" name="route_substation" id="route_substation" onclick=validate_substation_type_user();>
         
        </td>
      </tr>
	  ';
	  }
	  else{
	  echo'<input type="hidden" value="" name="route_substation" id="route_substation" >';
	  }*/
	  echo'
    </table>
     <br>
    <table border=0>
      <tr>
        <td>
            <fieldset class="manage_account_fieldset">
		        <legend><strong>User Type</strong></legend>
            <table border=0>'; 
    for($i=0;$i<$size_utype_session;$i++)
    {
      echo'<tr valign="top">
              <td><input type="checkbox" value="'.$user_type_id_session[$i].'" name="user_type[]" id="user_type[]" onclick="javascript:check_usertypes(this.value);"/>
               '.$user_type_name_session[$i].'</td>
           </tr>';     
      echo'<tr>
            <td>
              <table>
                  <tr>
                      <td></td>
                      <td>';      
                        $query = "SELECT feature_id from usertype_mapping WHERE user_type_id='$user_type_id_session[$i]'";
                        $result = mysql_query($query);
                        $row = mysql_fetch_object($result);
                        $feature_id_str_db = $row->feature_id;
                        $feature_id_db = explode(",",$feature_id_str_db);    
                        for($j=0;$j<sizeof($feature_id_db);$j++)
                        {
                          for($k=0;$k<$size_feature_session;$k++)
                          {
                            //echo "feature_id="."feature".$i;
                            if($feature_id_db[$j]==$feature_id_session[$k])
                            {                             
                              echo'<td><input type="checkbox" value="'.$feature_id_session[$k].'" name="feature'.$i.'[]" id="feature'.$i.'[]" onclick="javascript:check_features(this,this.value);"/></td>';                      
                              echo '<td>'.$feature_name_session[$k].'<input type="hidden" value="'.$feature_name_session[$k].'" id="post_feature'.$i.'[]"/></td>';                            
                            //echo "<br>feature_id_session=".$feature_id_session[$k]." ,feature_name_session=".$feature_name_session[$k];
                            }
                          }	   
                        }           
                echo'</tr>
            </table>            
          </td>
        </tr>';
    /*echo'<input type="radio" value="mining" name="user_type" id="user_type" onclick="javascript:get_acc_features(this.value,\''.$value.'\');"/> Mining
    <input type="radio" value="school" name="user_type" id="user_type" onclick="javascript:get_acc_features(this.value,\''.$value.'\');"/> School
    <input type="radio" value="courier" name="user_type" id="user_type" onclick="javascript:get_acc_features(this.value,\''.$value.'\');"/> Courier
    <input type="radio" value="pos" name="user_type" id="user_type" onclick="javascript:get_acc_features(this.value,\''.$value.'\');"/> POS';*/
    }
    echo'</table>
     </fieldset>
     </td>
     </tr>
     </table>';
    echo '<input type="hidden" value="same" name="company_type" id="company_type">';
}
    ?>
    <table>
    <!--<tr valign="top"> 
      <td colspan="3" align="center">
        <fieldset id="frameset1" style="display:none;" class="add_account" style="width:320px;">
          <legend>Account Feature</legend>                
          <div id="account_feature_usertype"></div>
        </fieldset>
      </td>                     
    </tr>-->
    <tr>                    									
      <td align="center"  colspan="3">
        <div style="height:10px"></div>
        <!--<input type="button" value="Test" Onclick="javascript:return test_abc(thisform)" id="test_button">&nbsp;-->
        <input type="button" value="Add" Onclick="javascript:return action_manage_account('add')" id="enter_button">&nbsp;
        <input type="reset" value="Clear">
      </td>
    </tr>
  </table>
  <a href="javascript:show_option('manage','account');" class="back_css">&nbsp;<b><< Back</b></a>
	<?php 
		include_once('manage_loading_message.php');
	?>
</center>
</form>   
<!-- ADD ACCOUNT CLOSED -->  


