<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$type1 = $_POST['type']; 
	$sector_id_1 = $_POST['sector_id'];
	$route_id_1=$_POST['route_id'];  
	$account_id_local = $_POST['account_id_local'];

	$sector_found = 0;

	if($type1 == "sector_coord")
	{
		$query="SELECT sector_name,sector_coord FROM sector WHERE sector_id='$sector_id_1'";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$sector_name1=$row->sector_name;
		$coord_1=$row->sector_coord;
		$coord_1 = base64_decode($coord_1);

		echo "manage_sector_coord##".$sector_name1."##".$coord_1;    
	}
  
	else if(($type1 == "sector_ids") && ($route_id_1!=""))
	{
		$query_route = "SELECT route_name,route_sector_ids FROM route WHERE route_id='$route_id_1'";
    // echo "<br>q1=".$query;
     
		$result1=mysql_query($query_route,$DbConnection);
		$row1=mysql_fetch_object($result1);
		$route_name1=$row1->route_name;
		$sector_ids1=$row1->route_sector_ids;
		//echo $route_sector_ids;

		echo "manage_sector_ids##";

		$seq_global = 0;
     
    if($sector_ids1!="")
		{    		    			    			
			echo'
			 <table border="0" class="manage_interface">			        					
			  <tr>
				  <td colspan="3" align="left">Route Name&nbsp:&nbsp;<input type="text" id="route_name" value="'.$route_name1.'"></td>
				  <td>
				</tr>
        <tr>
					<td colspan="3" align="left">Select Sectors&nbsp:&nbsp;</td>
			  </tr>						
			  <tr>
				  <td colspan="2"></td>
				  <td>
					<table border="1" class="manage_interface">	
					
					<tr bgcolor="lightgrey">
					  <td align="left"><input type="checkbox" id="all" name="all" onclick="javascript:select_all_sectors(this.form);">Select</td>
								<td align="left">Sectors Name</td>            						
					  <td align="left">Sequence</td>
							</tr>							
			';
								
			/*$total_account_str ="";
			for($i=0;$i<sizeof($total_account_ids);$i++)
			{
				if($i==0)
				{
				  $total_account_str = $total_account_ids[$i];
			  }
			  else
			  {
				$total_account_str = $total_account_str.",".$total_account_ids[$i];
			  }
			 // echo "<br>".$total_account_ids[$i];
			} 
			
			$total_account_str = $total_account_str.",".$account_id;  */
			
			/*while($row = mysql_fetch_object($result))
			{
			  $sector_id[] = $row->sector_id;
			  $sector_name[] = $row->sector_name;
			} */
				
			// SHOW EXISTING SECTORS
			$sector_ids2 = explode(':',$sector_ids1);
			
			for($k=0;$k<sizeof($sector_ids2);$k++)
			{
				//echo "<br>sector:".$sector_ids2[$k];

				$sector_id3 = explode(',',$sector_ids2[$k]);

				/*for($p=0;$p<sizeof($sector_id3);$p++)
				{
				echo "<br> one=".$sector_id3[$p];
				}*/
				//echo "<br>size:sector_id3=".sizeof($sector_id3)." one=".$sector_id3[0]." ,two=".$sector_id3[1];
				$sector_id_main = $sector_id3[0];
				$sector_id_main_array[] = $sector_id_main;
				$sequence_main = $sector_id3[1];

				$query2 = "SELECT sector_id,sector_name FROM sector WHERE sector_id='$sector_id_main' and status=1";
				//echo $query;
				$result2 = mysql_query($query2,$DbConnection);
				if($row2 = mysql_fetch_object($result2))
				{          
          $sector_name = $row2->sector_name;
					echo'<tr>
							<td><input type="checkbox" id="sector[]" name="sector[]" value="'.$sector_id_main.'" checked></td>
					<td>'.$sector_name.'</td>                                    
					<td>';
					$seq = "seq".$seq_global; 
					//echo "<br>SEQ=".$seq;
          echo'<select name="sequence" id="'.$seq.'" onchange="javascript:validate_sector_sequence(this.id)">';
		  
					echo '<option value="0">Select</option>';
					
					for($j=1;$j<25;$j++)
					{                      
					  if($j == $sequence_main)
						echo '<option value="'.$j.'" selected>'.$j.'</option>';
					  else
						echo '<option value="'.$j.'">'.$j.'</option>';
					}  
					echo'</select>
						</td>   						  						
					   </tr>';
					   $seq_global ++;
					   
					   $sector_found = 1;
				 } // inner if closed	     				  
			} // for closed                                     
		}	//if($sector_ids1!="") closed							
	
	
		/// SHOW UNASSIGNED SECTORS    
     
     
		if($sector_ids1 =="")
		{    		    			    			
    		echo'
    		 <table border="0" class="manage_interface">			        
  			  <tr>
  				  <td colspan="3" align="left">Route Name&nbsp:&nbsp;<input type="text" id="route_name" value="'.$route_name1.'"></td>
  				  <td>
  				</tr>		    		  
          <tr>
    				<td colspan="3" align="left">Select Sectors&nbsp:&nbsp;</td>
    		  </tr>						
    		  <tr>
    			  <td colspan="2"></td>
    			  <td>
    				<table border="1" class="manage_interface">	
    				
    				<tr bgcolor="lightgrey">
    				  <td align="left"><input type="checkbox" id="all" name="all" onclick="javascript:select_all_sectors(this.form);">Select</td>
    							<td align="left">Sectors Name</td>            						
    				  <td align="left">Sequence</td>
    						</tr>							
    		';
		}     
          
		$sector_id_str = "";
		for($i=0;$i<sizeof($sector_id_main_array);$i++)
		{
			if($i==0)
			{
				$sector_id_str = $sector_id_main_array[$i];
			}
			else
			{
				$sector_id_str = $sector_id_str.",".$sector_id_main_array[$i];
			}
		}
     
		$query = "SELECT sector_id, sector_name FROM sector WHERE sector_id NOT IN($sector_id_str) AND (user_account_id='$account_id_local' OR create_id='$account_id') AND status=1";
		//echo "<br>q2=".$query;
    $result = mysql_query($query,$DbConnection);
           
		$i=0;
		while($row = mysql_fetch_object($result))
		{      
			$sector_id = $row->sector_id;
			$sector_name = $row->sector_name;  

			echo'<tr>
			<td><input type="checkbox" id="sector[]" name="sector[]" value="'.$sector_id.'"></td>
			<td>'.$sector_name.'</td>                                    
			<td>';
			$seq = "seq".$seq_global; 
			//echo "<br>SEQ=".$seq;
      echo'<select name="sequence" id="'.$seq.'" onchange="javascript:validate_sector_sequence(this.id)">';

			echo '<option value="0">Select</option>';

			for($j=1;$j<25;$j++)
			{                      
				echo '<option value="'.$j.'">'.$j.'</option>';
			}  
			echo'</select>
			</td>   						  						
			</tr>';  
			$i++; 
			$seq_global ++;
			$sector_found =1;     
  		} // while closed     					   
		  
      
		echo'</table>
			  </td>
			</tr>
		 </table>';        
  	
		if(!$sector_found)
    {
			echo "<font color=red>Sorry Currently No sector Added in this route!</font>";
    }
  
	}   // if route_id!="" closed 
    
?>

        