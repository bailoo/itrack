<?php
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$root=$_SESSION['root'];
$ms_size=0;
$msname=array();
$mstype=array();
$points=array();


HomeGetGroup($root);

function HomeGetGroup($AccountNode)
{
	global $group;
	global $group_cnt;
	global $DbConnection;
	global $ms_size;
	global $msname;
	global $mstype;
	global $points;
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
				while($row2 = mysql_fetch_object($result))	
				{
					$msname[$ms_size]=$row2->milestone_name;		
					$mstype[$ms_size]=$row2->milestone_type;
					$coordinates4[$ms_size]=$row2->coordinates;
					$points[$ms_size] = base64_decode($coordinates4[$ms_size]);					
					//echo "ma_name=".$msname[$ms_size]." mstyope=".$mstype[$ms_size]." points=".$points1[$ms_size];
					
					
				/*	$points1 = base64_decode($coordinates4[$i]);
					$coordinates5[$i] = str_replace(',',':',$points1);			
					$coordinates6[$i] = str_replace(' ',',',$coordinates5[$i]);
					//echo "coordinates4=".$coordinates4[$i]."<br>coordinates5=".$coordinates5[$i]."<br>coordinates6=".$coordinates6[$i];				
					echo"<input type='hidden' name='ms_coord[]' value='".$coordinates6[$i]."'>";
					echo"<input type='hidden' name='ms_name[]' value='".$msname[$i]."'>";
					echo"<input type='hidden' name='ms_type[]' value='".$mstype[$i]."'>";	*/	
					$ms_size++;
				}		
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
		HomeGetGroup($AccountNode->child[$i]);
	}   
}
//global $ms_size;
	//$_SESSION['final_group_array'] = $final_group_array;
	//echo "size=".$ms_size;
for($i=0;$i<$ms_size;$i++)
{
	//echo " msname=".$msname[$i]." mstype=".$mstype[$i]." points=".$points[$i]."<br><br>";
}	
function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&apos;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 

echo '<t1>';

for($i=0;$i<$ms_size;$i++)
{
	//ADD TO XML DOCUMENT NODE
	echo '<marker ';
	echo 'msname="' . parseToXML($msname[$i]) . '" ';	
	echo 'mstype="' . parseToXML($mstype[$i]) . '" ';	
	echo 'points="' . parseToXML($points[$i]) . '" ';	
	echo '/>';		
	
} //loop $j closed

echo '</t1>';
?>