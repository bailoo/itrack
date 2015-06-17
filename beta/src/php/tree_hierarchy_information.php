<?php 
	function get_parent_account_id($AccountNode,$function_account_id)
	{
		$parent_account_id=null;		
		//echo "account_id_1=".$AccountNode->data->AccountID."account_id2=".$function_account_id."<br>";
		if($AccountNode->data->AccountID==$function_account_id)
		{
			$parent_account_id=$AccountNode->parents->data->AccountID;		
			return $parent_account_id;					
		}
		
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{     
			$parent_account_id=get_parent_account_id($AccountNode->child[$i],$function_account_id);		
			if($parent_account_id!=null)
			{
				return $parent_account_id;
			}
		}
	}
	
	function find_child($AccountNode,$function_account_id)
	{
		$tmp_child = null;
		if($AccountNode->data->AccountID==$function_account_id)
		{
			if($AccountNode->ChildCnt==0 && $AccountNode->VehicleCnt==0)
			{
				$tmp_child="No Child Found";
			}
			else
			{
				$tmp_child="Child Found";
			}			
			return $tmp_child;	
		}
		
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{     
			$tmp_child = find_child($AccountNode->child[$i],$function_account_id);
			if($tmp_child!=null)
			{
				return $tmp_child;
			}
		}	
	}
	
	function printgroups($root,$final_group_array,$account_id1,$group_id1)
	{
		global $DbConnection;
		if($group_id1!=null)
		{
			$row=getGroupNameRemarkGroup($group_id1,$DbConnection);				
			echo"<option value=".$group_id1.">".$row[0]."</option>	";	
			/*for($i=0;$i<sizeof($final_group_array);$i++)
			{
				if($final_group_array[$i][0]==$group_id1)
				{
					echo"<option value=".$final_group_array[$i][0].">".$final_group_array[$i][1]."</option>	";	
				}
			}*/
		}
		else
		{
			echo"<option value=''>No Group</option>";
		}
		
		for($i=0;$i<sizeof($final_group_array);$i++)
		{
			if($final_group_array[$i][2]==$account_id1)
			{
				echo"<option value=".$final_group_array[$i][0].">".$final_group_array[$i][1]."</option>	";
			}
		}
	} 

	function account_column_count($AccountNode)
	{ 
		global $ColumnNo;
		global $RowNo;
		global $MaxColumnNo;
		global $count;  
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;

			$ColumnNo++;
			$RowNo++;

			if($MaxColumnNo<$ColumnNo)
			{
				$MaxColumnNo = $ColumnNo;
			}
			for($i=0;$i<$ChildCount;$i++)
			{
				account_column_count($AccountNode->child[$i]);
			}
			$ColumnNo--;
		}
	}

	function checkbox_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		global $local_account_ids;
		global $account_size;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;
			if($account_size!="")
			{
			  for($i=0;$i<$account_size;$i++)
			  {	
			   if($account_id_local==$local_account_ids[$i])
			   {
				  $acc_check=1;
			   }	
			  }
			}
			echo"<tr>";
			for($k=0;$k<$ColumnNo;$k++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"<td>&nbsp;";
			if($acc_check==1) 
			{               
					echo"<INPUT TYPE='checkbox' name='manage_id[]' VALUE='$account_id_local' checked><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->";
				  $acc_check=0;
			}
				else
				{
			  echo"<INPUT TYPE='checkbox' name='manage_id[]' VALUE='$account_id_local'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->";
			}
			echo"</td>";
			for($l=($k+1);$l<$MaxColumnNo;$l++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"</tr>";

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				checkbox_account_hierarchy($AccountNode->child[$i]);
			}  
			$ColumnNo--;
		}
	}
	
	function radio_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
	 
			echo"<tr>";
			for($k=0;$k<$ColumnNo;$k++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"<td>&nbsp;                
				<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local' onclick='javascript:temp()'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->
			</td>";
			for($l=($k+1);$l<$MaxColumnNo;$l++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"</tr>";

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				radio_account_hierarchy($AccountNode->child[$i]);
			}  
			$ColumnNo--;
		}
	}
	
    function selectbox_account_hierarchy($AccountNode)
	{
		global $DbConnection;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
			if($account_id_local!='1')
			{
				$queryuid="SELECT user_id,group_id from account WHERE account_id='$account_id_local' and status=1";
				//echo "<br>".$queryuid;
				$resultuid=mysql_query($queryuid,$DbConnection);
				$rowuid=mysql_fetch_row($resultuid);
				$user_id=$rowuid[0];
				$group_id=$rowuid[1];
			echo"  
				
				<option VALUE='$account_id_local'>".$group_id." " .$account_name."($user_id) </option>
			";
			}
			else
			{
				echo"
				<option VALUE='select'>Select </option>
				";
			}
			for($i=0;$i<$ChildCount;$i++)
			{     
				selectbox_account_hierarchy($AccountNode->child[$i]);
			} 
		}
		
	} 
	function vehicle_radio_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		$path = $_SERVER['SCRIPT_NAME'];	
		$url_substr = explode('/',$path);		
		$size = sizeof($url_substr);
		$interface = $url_substr[$size-1];	
		//echo"".$interface;
		if($interface=="report_common_prev.php" )		
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
	 
			echo"<tr>";
			for($k=0;$k<$ColumnNo;$k++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"<td>&nbsp;                
				<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local' onclick='javascript:show_account_detail()'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->
			</td>";
			for($l=($k+1);$l<$MaxColumnNo;$l++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"</tr>";

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				vehicle_radio_account_hierarchy($AccountNode->child[$i]);
			}  
			$ColumnNo--;
		}
		else
		{
			if($AccountNode->data->AccountTypeThirdParty!='1')
			{
				$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
	 
			echo"<tr>";
			for($k=0;$k<$ColumnNo;$k++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"<td>&nbsp;                
				<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local' onclick='javascript:show_account_detail()'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->
			</td>";
			for($l=($k+1);$l<$MaxColumnNo;$l++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"</tr>";

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				vehicle_radio_account_hierarchy($AccountNode->child[$i]);
			}  
			$ColumnNo--;
			}
		}
	}
	
	/*
	function vehicle_radio_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
	 
			echo"<tr>";
			for($k=0;$k<$ColumnNo;$k++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"<td>&nbsp;                
				<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local' onclick='javascript:show_account_detail()'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->
			</td>";
			for($l=($k+1);$l<$MaxColumnNo;$l++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"</tr>";

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				vehicle_radio_account_hierarchy($AccountNode->child[$i]);
			}  
			$ColumnNo--;
		}
	}
	*/
	function schedule_radio_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
	 
			echo"<tr>";
			for($k=0;$k<$ColumnNo;$k++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"<td>&nbsp;                
				<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local'>&nbsp;".$account_name."
			</td>";
			for($l=($k+1);$l<$MaxColumnNo;$l++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"</tr>";

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				schedule_radio_account_hierarchy($AccountNode->child[$i]);
			}  
			$ColumnNo--;
		}
	}
	
	function radio_group_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
	 
			echo"<tr>";
			for($k=0;$k<$ColumnNo;$k++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"<td>&nbsp;                
				<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local,$group_id_local'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->
			</td>";
			for($l=($k+1);$l<$MaxColumnNo;$l++)
			{
				echo"<td>&nbsp;".''."</td>";
			}
			echo"</tr>";

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				radio_group_account_hierarchy($AccountNode->child[$i]);
			}  
			$ColumnNo--;
		}
	}
	
	function radio_group_account_hierarchy_transporter($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		global $DbConnection;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
			$queryType="SELECT user_type from account WHERE account_id='$account_id_local'";
			//echo "<br>".$queryType;
			$resultType=mysql_query($queryType,$DbConnection);
			$rowType=mysql_fetch_row($resultType);
			$function_account_type=$rowType[0];
			//echo "userType=".$function_account_type."<br>";
			
			if($function_account_type=='raw_milk' || $function_account_type=='hindalco_invoice'){
				echo"<tr>";
				for($k=0;$k<$ColumnNo;$k++)
				{
					echo"<td>&nbsp;".''."</td>";
				}
				
				echo"<td>&nbsp;                
					<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local,$group_id_local' Onclick='javascript:return show_transporter_vehicles(manage1,\"deassign\")'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->
				</td>";
				for($l=($k+1);$l<$MaxColumnNo;$l++)
				{
					echo"<td>&nbsp;".''."</td>";
				}
				echo"</tr>";
			}
			

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				radio_group_account_hierarchy_transporter($AccountNode->child[$i]);
			}  
			$ColumnNo--;
		}
	}
	function radio_group_account_hierarchy_transporter_default_chilling($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		global $DbConnection;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
			$queryType="SELECT user_type from account WHERE account_id='$account_id_local'";
			//echo "<br>".$queryType;
			$resultType=mysql_query($queryType,$DbConnection);
			$rowType=mysql_fetch_row($resultType);
			$function_account_type=$rowType[0];
			//echo "userType=".$function_account_type."<br>";
			
			if($function_account_type=='raw_milk' || $function_account_type=='hindalco_invoice'){
				echo"<tr>";
				for($k=0;$k<$ColumnNo;$k++)
				{
					echo"<td>&nbsp;".''."</td>";
				}
				$DEFAULTPLANTQUERY="SELECT	transporter_chilling_plant_assignment.customer_no,station.station_name FROM transporter_chilling_plant_assignment,station
		   WHERE transporter_chilling_plant_assignment.account_id=$account_id_local  AND transporter_chilling_plant_assignment.status=1 AND 
			station.customer_no=transporter_chilling_plant_assignment.customer_no AND station.type=2 AND station.status=1"	;
			$resultDefault=mysql_query($DEFAULTPLANTQUERY,$DbConnection);
			$rowDefault=mysql_fetch_row($resultDefault);
			if($rowDefault[0]!="")
			{
				$default_plant=" (".$rowDefault[1]."-".$rowDefault[0] .")";
			}
			else
			{
				$default_plant=" (-)";
			}
				echo"<td>&nbsp;                
					<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local,$group_id_local' Onclick='javascript:return show_transporter_vehicles(manage1,\"deassign\")'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name.$default_plant."<!--</a>-->
				</td>";
				for($l=($k+1);$l<$MaxColumnNo;$l++)
				{
					echo"<td>&nbsp;".''."</td>";
				}
				echo"</tr>";
			}
			

			$ColumnNo++;
			$RowNo++;

			for($i=0;$i<$ChildCount;$i++)
			{     
				radio_group_account_hierarchy_transporter_default_chilling($AccountNode->child[$i]);
			}  
			$ColumnNo--;
		}
	}
	
	function radio_group_account_hierarchy_transporter_child($AccountNode)
	{
		global $Child_account;
		global $DbConnection;
		if($AccountNode->data->AccountTypeThirdParty!='1')
		{
			$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
			$account_id_local=$AccountNode->data->AccountID;
			$group_id_local=$AccountNode->data->AccountGroupID;
			//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
			$account_name=$AccountNode->data->AccountName;
			$ChildCount=$AccountNode->ChildCnt;		
			$queryType="SELECT user_type from account WHERE account_id='$account_id_local'";
			//echo "<br>".$queryType;
			$resultType=mysql_query($queryType,$DbConnection);
			$rowType=mysql_fetch_row($resultType);
			$function_account_type=$rowType[0];
			//echo "userType=".$function_account_type."<br>";
			
			if($function_account_type=='raw_milk' || $function_account_type=='hindalco_invoice' ){
				$Child_account=$Child_account.$account_id_local.",";
			}
			

			for($i=0;$i<$ChildCount;$i++)
			{     
				radio_group_account_hierarchy_transporter_child($AccountNode->child[$i]);
			} 
		}
		
	}
	
	function printaccounthierarchy($tree,$group_id,$group_name1,$Accounid)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		global $account_node_count;
		$AccountTree = GetAccountTree($tree,$Accounid);
		if($AccountTree->data->AccountTypeThirdParty!='1')
		{
			echo "<tr>";	
			printAccountNodeHoerarch($AccountTree);
			$account_id_local=$AccountTree->data->AccountID;
			if($count!=1) /////////if there is no parent of it
			{ 
				for($l=0;$l<$MaxColumnNo;$l++)
				{
					if($l!=$MaxColumnNo-1)
					{						
						echo"<td>&nbsp;".''."</td>";
					}
					else
					{
						echo"<td><input type='radio' name='manage_id' value='$account_id_local,$group_id'>".$group_name1."</td>";
					}
				}
			echo"</tr>";
			}	
			else
			{
				$MaxColumnNo1=$MaxColumnNo-$account_node_count;
				for($l=0;$l<$MaxColumnNo1;$l++)
				{
					if($l!=($MaxColumnNo1-1))
					{
						echo"<td>&nbsp;".''."</td>";
					}
					else
					{
						echo "<td><input type='radio' name='manage_id' value='$account_id_local,$group_id'>".$group_name1."</td>";
					}
				}
			echo"</tr>";

			}			
		}
	}

	function GetAccountTree($tree,$Accountid)
	{
		$ColumnNo++;
		$RowNo++;
		if($tree->data->AccountID==$Accountid)
		{
			return $tree;
		}
		else if($tree->ChildCnt==0)
		{
			return null;
		}
		else
		{
			$childcnt = $tree->ChildCnt;
			for($i=0;$i<$childcnt;$i++)
			{
				$tmp = GetAccountTree($tree->child[$i],$Accountid);
				if($tmp!=null)
				{
					return $tmp;
				}
			}
		}
		$ColumnNo--;
	}
	function printAccountNodeHoerarch($AccountTree)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		global $account_node_count;		
	
		for($k=0;$k<$ColumnNo;$k++)
		{
			echo"<td>&nbsp;".''."</td>";
		}
		if($AccountTree->parents==null)
		{		  
			echo "<td>".$AccountTree->data->AccountName."</td>";	
			return;
		}
		else
		{
		    $count=1;			
			printAccountNodeHoerarch($AccountTree->parents);			
			echo "<td>".$AccountTree->data->AccountName."</td>";
			$account_node_count++;	
		}
	}
?>
