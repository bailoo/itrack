<?php
function GetGroup_3($AccountNode,$DbConnection)
{
	global $final_group_array1;
	global $group;
	global $group_cnt;
	global $grp_cnt;

	//$group_id = $AccountNode->data->AccountGroupID;
	//$group_name = $AccountNode->data->AccountGroupName;	
	$account_id_local = $AccountNode->data->AccountID;
	$query="select * from `group` where parent_account_id='$account_id_local' and status='1'";
	$result=mysql_query($query,$DbConnection);	
	while($row=mysql_fetch_object($result))
	{
		$group_id_local= $row->group_id;
		$group_name_local= $row->group_name;
		$parent_account_id = $row->parent_account_id;
		$create_id = $row->create_id;
		
		/*for($i=0;$i<$group_cnt;$i++)
		{
			//echo "3:".$group_id.' '.$group[$i][0].' '.$i.'<BR>';
			if($group[$i][0]==$group_id)
			{
			break;
			}
		}*/
		//if($i>=$group_cnt)
		//{			
			$final_group_array1[$group_cnt][0]=$group_id_local;
			$final_group_array1[$group_cnt][1]=$group_name_local;
			$final_group_array1[$group_cnt][2]=$parent_account_id;
			$final_group_array1[$group_cnt][3]=$create_id;			
			$group_cnt++;							
		//}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
		GetGroup_3($AccountNode->child[$i],$DbConnection);
	}

	return $final_group_array1;
} 

function GetGroup($AccountNode)
{
	global $group;
	global $group_cnt;
	$group_id = $AccountNode->data->AccountGroupID;
	$group_name = $AccountNode->data->AccountGroupName;
	//echo "1:".$group_id.' '.$group_name.' '.$group_cnt.'<BR>';

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
					<td align="left" valign="top"><INPUT TYPE="radio" name="manage_id" VALUE="'.$group_id.'">'.$group_name.'</td>
				</tr>
				';
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
		GetGroup($AccountNode->child[$i]);
	}   
}
function HomeGetGroup($AccountNode)
{
	global $group;
	global $group_cnt;
	global $DbConnection;
	$group_id = $AccountNode->data->AccountGroupID;
	$group_name = $AccountNode->data->AccountGroupName;
	//echo "1:".$group_id.' '.$group_name.' '.$group_cnt.'<BR>';

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
				$query = "select * from milestone_assignment where group_id='$group_id' and Status=1";
				//echo $query;
				$result=mysql_query($query,$DbConnection);						
				$i=0;
				while($row2 = mysql_fetch_object($result))	
				{
					$msname[$i]=$row2->milestone_name;		
					$mstype[$i]=$row2->milestone_type;
					$coordinates4[$i]=$row2->coordinates;
					$points1 = base64_decode($coordinates4[$i]);
					$coordinates5[$i] = str_replace(',',':',$points1);			
					$coordinates6[$i] = str_replace(' ',',',$coordinates5[$i]);
					//echo "coordinates4=".$coordinates4[$i]."<br>coordinates5=".$coordinates5[$i]."<br>coordinates6=".$coordinates6[$i];				
					echo"<input type='hidden' name='ms_coord[]' value='".$coordinates6[$i]."'>";
					echo"<input type='hidden' name='ms_name[]' value='".$msname[$i]."'>";
					echo"<input type='hidden' name='ms_type[]' value='".$mstype[$i]."'>";		
					$i++;
				}		
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
		HomeGetGroup($AccountNode->child[$i]);
	}   
}
	//$_SESSION['final_group_array'] = $final_group_array;
?>