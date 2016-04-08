<?php  
  $number = $_POST['number']; 
  echo "show_latlng_fields##";  
  echo'<table border=0 align="center" cellpadding=3 cellspacing=3>';
	       for($i=0;$i<$number;$i++)																	
	       {
		            echo"<tr>
		                  <td  width='13%'></td>
                      <td class='text' align='right'>Latitude :</td>
  		                <td><input type='text' id='lat".$i."' name='lat".$i."' onkeyup='CheckFloating(this)'></td>
  		                <td class='text' align='right'>Longitude :</td>
  		                <td><input type='text' id='lng".$i."' name='lng".$i."' onkeyup='CheckFloating(this)'></td>
                    </tr>";	
		              if($i==($number-1))
	                {
			             echo'<tr>										
			                     <td><div style="height:20px;"></div></td>																
			                     <td colspan=4 align="center"><input type="button" value="Enter" onclick="javascript:action_manage_milestone(\'add\')">
				                        <input type="reset" value="Clear">
			                      </td>															
		                    </tr>';
	                 }											
          }
  echo'</table>';
?>