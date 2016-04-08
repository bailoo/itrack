<?php
  include_once('util_session_variable.php');
  include_once("util_php_mysql_connectivity.php");
  $number = $_POST['number'];
  echo "show_latlng_fields##";
  $ms_id = $_POST['id'];
  $DEBUG=0;
  
  $query = "SELECT * FROM milestone_assignment WHERE milestone_id ='$ms_id'";   
  if($DEBUG==1)
  {print_query($query);}
  
  $result = mysql_query($query, $DbConnection);   
  $row = mysql_fetch_object($result);
  
  $coordinates = $row->coordinates;  
  $coordinates = base64_decode($coordinates);
  $points = explode(',',$coordinates);
  
  if($number == 0)
  {
    $number = sizeof($points);
  }
  
  for($i=0;$i<sizeof($points);$i++)
  {
    $pointtmp = explode(' ',$points[$i]);
    $lat[$i] = $pointtmp[0];
    $lng[$i] = $pointtmp[1];  
  } 
  
  if($DEBUG==1)
  {
    echo"points=".$points."<br>coordinates=".$coordinates."<br>number=".$number."<br>cord_size=".sizeof($points);
  } 
  
  echo'<br>
  <fieldset class="report_fieldset">
    <legend><strong>Milestone Details</strong></legend>	
      <table border=0 width=100%>
        <tr>
          <td class="text" align="right">Milestone Name :</td>
          <td><input type="text" name="ms_name" id="ms_name" value='.$row->milestone_name.'></td>
        </tr>
        <tr>
          <td class="text" align="right">Type :</td>
          <td><select name="ms_type" id="ms_type">';
                if($row->milestone_type=="BS")
                {
                  echo'<option value="BS" selected>Base Station</option>
                      <option value="OS">Operation Station</option>';
                }
                else if($row->milestone_type=="OS")
                {
                  echo'<option value="BS">Base Station</option>
                       <option value="OS" selected>Operation Station</option>';                  
                }
             echo'</select>
          </td>
        </tr>
          <td class="text" align="right">Add :</td>     
  	      <td><input type="hidden" id="itr">
  		        <select name="lat_lng" id="lat_lng" onchange="javascript:manage_get_edit_latlng_fields(this.value, '.$ms_id.')">';
                for($i=4;$i<=20;$i++)
                {
                  if($number==$i)
                  {
                     echo'<option value='.$number.' selected>'.$number.'</option>';
                  } 
                  else
                  {
                    echo'<option value='.$i.'>'.$i.'</option>';
                  }        
                } 																		
    	echo'</select>																		
		      </td>								
	       </tr>
          <tr>
            <td colspan=2>
              <table border =0 align="center">'; 
              for($i=0;$i<$number;$i++)																	
              {
              echo"<tr>									
                      <td class='text' align='right'>Latitude :</td>
                      <td><input type='text' id='lat".$i."' name='lat".$i."' value='".$lat[$i]."' onkeyup='CheckFloating(this)'></td>
                      <td class='text' align='right'>Longitude :</td>
                      <td><input type='text' id='lng".$i."' name='lng".$i."' value='".$lng[$i]."' onkeyup='CheckFloating(this)'></td>											
                    </tr>";
                       											
              }
        echo'</table>
            </td>
          </tr>     
    </table>
  </fieldset>';
  
?>