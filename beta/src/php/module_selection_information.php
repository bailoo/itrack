<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$root=$_SESSION['root'];		
	$final_group_array=$_SESSION['final_group_array'];
	$DEBUG = 0;    
	$display_type = $_POST['display_type1'];		
	$category1 = $_POST['category'];
	
	echo"map_selection_information##";
	
	//echo "display_type=".$display_type."<br>";

	$group=array(array());
	$group_cnt;
	$usertype_cnt;
	$usertype=array();
	$vehicletag=array();
	$vehicletag_cnt;
	$vehicletype=array();
	$vehicletype_cnt;
	$vehicle=array();
	$vehicle_cnt;
	$ColumnNo;
	$RowNo;
	$count;
	$MaxColumnNo;
	$npsp_space="&nbsp;";
	//$select_all_portal_option="select_all_portal_option";
	$select_all_td="<td class='text'>&nbsp;<input type='checkbox' name='all_1' value='1' onClick='javascript:SelectAll(\"display_option\");'>&nbsp;&nbsp;Select All".$npsp_space."</td>";			
	echo'<div id="selection_information_test" style="height:60%;width:100%;overflow:auto;">
<br>';
echo"<table border=1 cellspacing=0 rules='all' bordercolor='#A3AAFF' cellpadding=0 class='module_left_menu' align='center'>";
  if($display_type=="group")     
  {
    $group_cnt=0;
	echo "<input type='hidden' name='common_div_option' id='common_div_option' value='$display_type'>
		
			<tr>
				<td class='text'>".$npsp_space."SNo".$npsp_space." </td>
				<td class='text'>".$npsp_space."GroupID".$npsp_space."</td>
				<td class='text'>".$npsp_space."GroupName".$npsp_space."</td>
				".$select_all_td."
			</tr>";			
			GetGroup($root,$category1); 
	   
  }
  
  else if($display_type=="user_type")
  {	
    $usertype_cnt=0;
	echo "<input type='hidden' name='common_div_option' id='common_div_option' value='$display_type'>
			<tr>
				<td class='text'>".$npsp_space."SNo".$npsp_space." </td>
				<td class='text'>".$npsp_space."UserType".$npsp_space."</td>
				".$select_all_td."
			</tr>";
			GetUser($root,$category1); 

  }
  else if($display_type=="vehicle_tag")
  {	
	$vehicletag_cnt=0;
	echo "<input type='hidden' name='common_div_option' id='common_div_option' value='$display_type'>
			<tr>
				<td class='text'>".$npsp_space."SNo".$npsp_space." </td>
				<td class='text'>".$npsp_space."Tag Name".$npsp_space."</td>
				".$select_all_td."
			</tr>";
			GetTag($root); 

    /* echo "<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
          <tr>
            <td class='text' colspan='2'>
              &nbsp;<input type='checkbox' name='all' value='1' onClick='javascript:SelectAll(this.form);'>&nbsp;&nbsp;&nbsp;Select All	3			         
            </td>			
          </tr>
        </table>";*/
  }
  else if($display_type=="vehicle_type")
  {
	 $vehicletype_cnt=0;
     echo "<input type='hidden' name='common_div_option' id='common_div_option' value='$display_type'>

			<tr>
				<td class='text'>".$npsp_space."SNo".$npsp_space."</td>
				<td class='text'>".$npsp_space."Vehicle Type".$npsp_space."</td>
				".$select_all_td."
			</tr>";
		  GetVehicleType($root); 

  }
  else if($display_type=="user")
  {		
		$MaxColumnNo=0; 
		$ColumnNo=0;
		$RowNo=0;
		$count=0;
		echo "<input type='hidden' name='common_div_option' id='common_div_option' value='$display_type'>";
	
		FindColumnCnt($root);
		echo"<tr>";
		for($i=0;$i<$MaxColumnNo;$i++)
		{
			echo"<td>&nbsp;".'Level'.$i."</td>";
		}
		echo"</tr>";
		$ColumnNo=0;
		$RowNo=0;
		//var_dump($root);
		DisplayHierarchy($root,$category1);
  }
  else if($display_type=="vehicle")
  {	
    echo "<input type='hidden' name='common_div_option' id='common_div_option' value='$display_type'>

          <tr>
			<td>".$npsp_space."Serial No".$npsp_space."</td>
			<td>".$npsp_space."Vehicle Name".$npsp_space."</td>            
            ".$select_all_td."          			
          </tr>";
		  GetVehicle($root);

  }
  else if($display_type=="all")
  {
     echo "<input type='hidden' name='common_div_option' id='common_div_option' value='$display_type'>
	
          <tr>
            <td class='text' colspan='2'>
              &nbsp;<input type='checkbox' name='all' value='1' onClick='javascript:SelectAll(this.form);'>&nbsp;&nbsp;Select All	
			</td>			
          </tr>";

  }
  echo"</table>"; 
  echo'</div>';
  if($display_type!="all")
  {  
	 echo'<br><center> <input type="button" name="submit" value="Enter" onclick="javascript:display_vehicle_according_divoption(this.form)"></center>';
  }
  
	function GetGroup($AccountNode,$category1)
	{
		global $group;
		global $group_cnt;
		$group_id = $AccountNode->data->AccountGroupID;
		$group_name = $AccountNode->data->AccountGroupName;
		$account_type = $AccountNode->data->AccountType;
	
		if($account_type==$category1) 
		{
			if($group_id!=null)
			{
				for($i=0;$i<$group_cnt;$i++)
				{
					//echo "3:".$group_id.' '.$group[$i][0].' '.$i.'<BR>';
					if($group[$i][0]==$group_id)
					{
						break;
					}
				}
				if($i>=$group_cnt)
				{
					//echo "2:".$group_id.' '.$group_name.' '.$group_cnt.'<BR>';
					$group[$group_cnt][0]=$group_id;
					$group[$group_cnt][1]=$group_name;
					$group_cnt++;
					echo'<tr> 
							<td>&nbsp;'.$group_cnt.'</td>
							<td>&nbsp;'.$group_id.'</td>
							<td>&nbsp;'.$group_name.'</td>
							<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="home_array[]" VALUE="'.$group_id.'"></td>
						</tr>';
				}
			}		
		}
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
			GetGroup($AccountNode->child[$i],$category1);
		}   
	}
	
	function GetUser($AccountNode,$category1)
	{
		global $usertype;
		global $usertype_cnt;		
		//echo "1:".$group_id.' '.$user_type.'<BR>';
		if($user_type!=null)
		{
			for($i=0;$i<$usertype_cnt;$i++)
			{
				//echo "3:".$group_id.' '.$group[$i][0].' '.$i.'<BR>';
				if($usertype[$i]==$user_type)
				{
					break;
				}
			}
			if($i>=$usertype_cnt)
			{
				$usertype[$usertype_cnt]=$user_type;
				$usertype_cnt++;
				
				echo'
				  <tr> 
							<td>&nbsp;'.$usertype_cnt.'</td>
							<td>&nbsp;'.$user_type.'</td>
							<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="home_array[]" VALUE="'.$user_type.'"></td>
				   </tr>';
			}
		}
		
		$ChildCount=$AccountNode->ChildCnt;
     
		for($i=0;$i<$ChildCount;$i++)
		{ 
			GetUser($AccountNode->child[$i],$category1);
		}    
	}
	
	function GetTag($AccountNode)
	{
		global $vehicletag;
		global $vehicletag_cnt;
		
		
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{
			$vehicle_tag = $AccountNode->data->VehicleTag[$j];
			///echo "1:".$vehicle_tag.'<BR>';
			if($vehicle_tag!=null)
			{
				for($i=0;$i<$vehicletag_cnt;$i++)
				{
					if($vehicletag[$i]==$vehicle_tag)
					{
						break;
					}
				}			
				if($i>=$vehicletag_cnt)
				{
					$vehicletag[$vehicletag_cnt]=$vehicle_tag;
					$vehicletag_cnt++;
					
					echo'<tr> 
						<td>&nbsp;'.$vehicletag_cnt.'</td>
						<td>&nbsp;'.$vehicle_tag.'</td>
						<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="home_array[]" VALUE="'.$vehicle_tag.'"></td>
					</tr>';
				}
			}
		}		
		$ChildCount=$AccountNode->ChildCnt;
     
		for($i=0;$i<$ChildCount;$i++)
		{ 
			GetTag($AccountNode->child[$i]);
		}    
	}
	
	function GetVehicleType($AccountNode)
	{
		global $vehicletype;
		global $vehicletype_cnt;
		
		
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{
			$vehicle_type = $AccountNode->data->VehicleType[$j];
			if($vehicle_type!=null)
			{
				for($i=0;$i<$vehicletype_cnt;$i++)
				{
					if($vehicletype[$i]==$vehicle_type)
					{
						break;
					}
				}			
				if($i>=$vehicletype_cnt)
				{
					$vehicletype[$vehicletype_cnt]=$vehicle_type;
					$vehicletype_cnt++;
					
					echo'<tr> 
						<td>&nbsp;'.$vehicletype_cnt.'</td>
						<td>&nbsp;'.$vehicle_type.'</td>
						<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="home_array[]" VALUE="'.$vehicle_type.'"></td>
					</tr>';
				}
			}
		}
		
		$ChildCount=$AccountNode->ChildCnt;
     
		for($i=0;$i<$ChildCount;$i++)
		{ 
			GetVehicleType($AccountNode->child[$i]);
		}    
	}
	
	function GetVehicle($AccountNode)
	{
		global $vehicle;
		global $vehicle_cnt;		
		
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{
			$vehicle_id = $AccountNode->data->VehicleID[$j];
			$vehicle_name = $AccountNode->data->VehicleName[$j];					
			for($i=0;$i<$vehicle_cnt;$i++)
			{
				if($vehicle[$i]==$vehicle_id)
				{
					break;
				}
			}			
			if($i>=$vehicle_cnt)
			{
				$vehicle[$vehicle_cnt]=$vehicle_id;
				$vehicle_cnt++;
				
				echo'<tr> 
					<td>&nbsp;'.$vehicle_cnt.'</td>
					<td>&nbsp;<A HREF="#" style="text-decoration:none;" onclick="portal_vehicle_information('.$vehicle_id.')">'.$vehicle_name.'</A></td>
					<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="home_array[]" VALUE="'.$vehicle_id.'"></td>
				</tr>';
			}
		}
		
		$ChildCount=$AccountNode->ChildCnt;
     
		for($i=0;$i<$ChildCount;$i++)
		{ 
			GetVehicle($AccountNode->child[$i]);
		}    
	}
	
	function FindColumnCnt($AccountNode)
	{ 
		global $ColumnNo;
		global $RowNo;
		global $MaxColumnNo;
		global $count; 
		
		$ChildCount=$AccountNode->ChildCnt;
		$ColumnNo++;
		
		$RowNo++;
		if($MaxColumnNo<$ColumnNo)
		{
			$MaxColumnNo = $ColumnNo;
		}
		for($i=0;$i<$ChildCount;$i++)
		{
			FindColumnCnt($AccountNode->child[$i]);
		}
		$ColumnNo--;
		//echo "columnnobelow=".$ColumnNo."<br>";
	}

	function DisplayHierarchy($AccountNode,$category1)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;

		$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
		$account_id_local=$AccountNode->data->AccountID;
		$account_name=$AccountNode->data->AccountName;
		$ChildCount=$AccountNode->ChildCnt;
		$flag=0;
		$user_type=$AccountNode->data->AccountType;
		//echo "user_type=".$user_type;
		$user_type1=explode(",",$user_type);
		for($i=0;$i<sizeof($user_type1);$i++)
		{
			//echo "cat=".$category1."user=".$user_type1[$i]."<br>";
			if($user_type1[$i]==$category1)
			{
				$flag=1;
			}
		}
	
			if($flag==1)
			{
				echo"<tr>";
				for($k=0;$k<$ColumnNo;$k++)
				{
					echo"<td>&nbsp;".''."</td>";
				}
				echo"<td>&nbsp;              
					<INPUT TYPE='checkbox' name='home_array[]' VALUE='$account_id_local'><a href='tree_account_detail.php?account_id_local=$account_id_local' class='hs2'>".$account_name."</a>
				&nbsp;</td>";
				for($l=($k+1);$l<$MaxColumnNo;$l++)
				{
					echo"<td>&nbsp;".''."</td>";
				}
				echo"</tr>";

				$ColumnNo++;
				$RowNo++;
			}
		

		for($i=0;$i<$ChildCount;$i++)
		{     
			DisplayHierarchy($AccountNode->child[$i],$category1);
		} 
if($flag==1)
			{		
		$ColumnNo--;
		}
		//echo " columnnobelow=".$ColumnNo."<br>";
	}
	
  
?>
