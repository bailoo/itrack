<?php 
	//include_once('Hierarchy.php');
        include_once('coreDb.php');
        include_once("report_hierarchy_header_apk.php");
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
               
	//$root=$_SESSION['root'];  
	$DEBUG=0;
	$parameter_type="apk";
	echo "add##";
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//include_once('tree_hierarchy_information.php');
	$account_id_local1 = $_POST['account_id_local'];
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value']; 

	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
  
        //echo "AcIdLocal=".$common_id1." <br> ,vdisp=".$vehicle_display_option1." ,optionval=".$options_value1;
	//$function_string='get_'.$vehicle_display_option1.'_vehicle';
        $function_string='get_all_vehicle';      
	//include_once('manage_radio_account.php');
  
  //date_default_timezone_set('Asia/Calcutta');
	$start_date=date("Y/m/d 00:00:00");
         
  echo '
    <br>
    <form name="v_form">
    <fieldset class="report_fieldset">
    <legend>Select Person</legend>	
    
    <table border=0  cellspacing=0 cellpadding=0  width="100%">
            <tr>
                <td align="center">							
                    <div style="overflow: auto;height: 150px; width: 650px;" align="center">
                            <table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';	
                              echo'<tr><td height="5px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_device(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
                                   $function_string($common_id1,"all");
                              echo'</table>
                    </div>
                </td>
            </tr>
    </table>
    </fieldset> <br>
    
  <table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
  <td>&nbsp;APK Version &nbsp; &nbsp;</td>
        <td> :</td>
        <td>';
        $data = getApk_versionDetail($DbConnection);
    
        echo'<select name="apk_version" id="apk_version" >
          <option value="select">Select</option>';

    	  //$query="select vehicle_id,vehicle_name from vehicle where account_id='$common_id1' and status='1'";
            for($i=0; $i<sizeof($data); $i++)			    			
            {
              $version = $data[$i]['version'];
              $apk_url = $data[$i]['apk_url'];
              $apk_str = $version."$".$apk_url;
              echo '<option value='.$apk_str.'>'.$version.'</option>';
            }
         ?>

        </select>
      </td>
</tr>
      
<tr>
	<td colspan="3" align="center">
		<br><input type="button" id="enter_button" value="Assign APK" onclick="javascript:return action_manage_apk('add')"/>&nbsp;
                <input type="reset" id="enter_button" value="Reset"/>
		
	</td>
</tr>
</table>
  
</form>