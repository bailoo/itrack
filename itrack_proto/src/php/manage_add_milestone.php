<?php 
  include_once('Hierarchy.php'); 
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('get_group.php');
  $group_cnt=0; 
  $root=$_SESSION['root'];  
  echo "add##";
  $DEBUG=0;    
  echo'<table align="center">
        <tr>
          <td>
              <fieldset class=\'assignment_manage_fieldset\'>
                <legend>
                    <strong>Group</strong>
                </legend>
                <table border="0" class="manage_interface">
					';GetGroup($root);echo'</table>
              </fieldset>	
          </td>
        </tr>
      </table>';
	    
	  echo'
      <br>
        <fieldset class="report_fieldset">
            <legend><strong>Add milestone</strong></legend>	           
                  <div id="itrvalue"></div>        
                    <input type="hidden" name="lat_size" value="'.$lat_lng_value.'"> 
                    <input type="hidden" name="m_name" value="'.$ms_name1.'">
      
                    <div style="height:10px;"></div>
                      <table border="0" cellspacing="2" cellpadding="2">      
                        <tr>
                          <td  width="8%"></td>
                          <td class="text" align="right">Mile Stone Name:</td>
                          <td width=8%><input type ="text" id="ms_name" name="ms_name" size="18"></td>
                          <td  width="7%" class="text" align="right">Type :</td>
                          <td><select name="ms_type" id="ms_type">
                                <option value="BS">Base Station</option>
                                <option value="OS">Operation Station</option>
                              </select>
                          </td>
                        </tr>
                        <tr>	
                          <td  width="8%"></td>
                          <td width=28% class="text" align="right">Add :</td>
                          <td colspan=3><input type="hidden" id="itr">
                              <select name="lat_lng" id="lat_lng" onchange="javascript:get_latlng_fields(this.value)">
                                <option value="Lat_Lng">Lat/Lng</option>									
                                <option value="4">4</option>  <option value="5">5</option>  <option value="6">6</option>
                                <option value="7">7</option>  <option value="8">8</option>  <option value="9">9</option>
                                <option value="10">10</option>  <option value="11">11</option>  <option value="12">12</option>
                                <option value="13">13</option>  <option value="14">14</option>  <option value="15">15</option>
                                <option value="16">16</option>  <option value="17">17</option>  <option value="18">18</option>
                                <option value="19">19</option>  <option value="20">20</option>		
                              </select>																		
                          </td>								
                        </tr>
                        <tr>                                                     
                          <td colspan=5>                            
                              <div id="number_of_fields" style="display:none;"></div>   		                  
  		                    </td>
	                       </tr>
                        </table>								
                        </form>
                      </fieldset>'; 			
                    ////////////for reset the value of ma_name and lal/long after refreshment//////
                    echo'<script type="text/javascript">
                    		var lt_lng_size=document.thisform.lat_size.value;						
                    		document.getElementById("lat_long").value=lt_lng_size;						
                    		var msname=document.thisform.m_name.value;								
                    		document.getElementById("ms_name2").value=msname;											
                    	</script>'; 
						
?>