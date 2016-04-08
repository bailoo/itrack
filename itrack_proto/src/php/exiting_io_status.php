<?php	
	for($i=0;$i<$NoofIO;$i++)
	{
            $io_name[$i]=getFeatureNameFeatureGrouping($io_id1[$i],$DbConnection);	
	}
	
	if($num_rows1>0)
	{
		$row=getIoAssignmentDetail($list_fname,$vehicle_id,$DbConnection);
		//echo "featurcnt=".$feature_count."<br>";
		for($fi=1;$fi<=$feature_count;$fi++) 
		{
                    //echo "featureName=".$fname[$fi]."<br>";
                    $IOPresent = false;
                    for($i=0;$i<$NoofIO;$i++)
                    {
                        //echo "allIoName=".$fname[$fi]." deviceAssignIoName=".$io_name[$i]."<br>";
                        //echo "ioName=".$fname[$fi]."<br>";
                        if($fname[$fi]==$io_name[$i])
                        {
                            //echo "in io present<br>";
                            $IOPresent = true;
                            break;
                        }
                    }
			if($fname[$fi]!="engine_type" && $fname[$fi]!="ac_type" && $fname[$fi]!="sos_type" && $fname[$fi]!="")
			{
				
				if($IOPresent==true)
				{							
					$perm_features[$fi]=$row->$fname[$fi];
					if($perm_features[$fi]!=null)
					{
						echo'<td align="left">';				
							echo'&nbsp;<INPUT TYPE="hidden" name="io_type'.$vehicle_id.'[]" VALUE="'.$fname[$fi].'">
								<select name="'.$vehicle_id.$fname[$fi].'" id="'.$vehicle_id.$fname[$fi].'">';
								//echo"<option value='select'>Select</option>";
								for($combo_cnt=1;$combo_cnt<=8;$combo_cnt++)
								{
									if($perm_features[$fi]==$combo_cnt)
									{
										echo"<option value=".$perm_features[$fi]." selected>IO_".$combo_cnt."</option>";
									}
									else
									{
										echo"<option value=".$combo_cnt.">IO_".$combo_cnt."</option>";
									}
								}				
							echo'</select>';
							if($fname[$fi]=="engine")
							{
								if($row->engine_type==1)
								{
									echo'&nbsp;<INPUT TYPE="checkbox" id="engineType'.$vehicle_id.'" checked>';
								}
								else
								{
									echo'&nbsp;<INPUT TYPE="checkbox" id="engineType'.$vehicle_id.'">';
								}
							}
							else if($fname[$fi]=="ac")
							{
								if($row->ac_type==1)
								{
									echo'&nbsp;<INPUT TYPE="checkbox" id="acType'.$vehicle_id.'" checked>';
								}
								else
								{
									echo'&nbsp;<INPUT TYPE="checkbox" id="acType'.$vehicle_id.'">';
								}
							}
							else if($fname[$fi]=="sos")
							{
								if($row->sos_type==1)
								{
									echo'&nbsp;<INPUT TYPE="checkbox" id="sosType'.$vehicle_id.'" checked>';
								}
								else
								{
									echo'&nbsp;<INPUT TYPE="checkbox" id="sosType'.$vehicle_id.'">';
								}
							}							
						echo'</td>';								
					}	
					else
					{
						echo'<td>';
						echo'&nbsp;<INPUT TYPE="hidden" name="io_type'.$vehicle_id.'[]" VALUE="'.$fname[$fi].'">';
						echo'&nbsp;<select name="'.$vehicle_id.$fname[$fi].'" id="'.$vehicle_id.$fname[$fi].'">';
							echo"<option value='select'>Select</option>";
							for($combo_cnt=1;$combo_cnt<=8;$combo_cnt++)
							{
								//echo"cmb_cnt__2=".$combo_cnt."<br>";
								echo"<option value=".$combo_cnt.">IO_".$combo_cnt."</option>";								
							}
							echo'</select>';
							if($fname[$fi]=="engine")
							{
								echo'&nbsp;<INPUT TYPE="checkbox" id="engineType'.$vehicle_id.'">';							
							}
							else if($fname[$fi]=="ac")
							{
								echo'&nbsp;<INPUT TYPE="checkbox" id="acType'.$vehicle_id.'">';						
							}
							else if($fname[$fi]=="sos")
							{
								echo'&nbsp;<INPUT TYPE="checkbox" id="sosType'.$vehicle_id.'">';							
							}
						echo'</td>';
					}
				}							
				else
				{
					echo'<td>';
					echo'-';
					echo'</td>';						
				}
			}
		}
	}
	/*else
	{
		for ($fi=1; $fi<=$feature_count; $fi++) 
		{
		echo'<td align="left">';												
					echo'&nbsp;<INPUT TYPE="hidden" name="io_type'.$vehicle_id.'[]" VALUE="'.$fname[$fi].'">
						<select name="'.$vehicle_id.$fname[$fi].'" id="'.$vehicle_id.$fname[$fi].'" disabled>';
							echo"<option value='select'>Select</option>";
							for($combo_cnt=1;$combo_cnt<=8;$combo_cnt++)
							{
								echo"<option value=".$combo_cnt.">IO_".$combo_cnt."</option>";								
							}
				echo"</select>";
		echo'</td>';
		}
	}*/


?>
