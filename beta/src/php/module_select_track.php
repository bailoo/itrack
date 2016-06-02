<?php
	$query1="SELECT time_zone from account_preference WHERE account_id='$account_id'";
	$result1=mysql_query($query1,$DbConnection);
	$row1=mysql_fetch_object($result1);
	$time_zone1=$row1->time_zone;

	//date_default_timezone_set('Asia/Calcutta');
	$StartDate=date('Y/m/d');
	$StartDate=$StartDate." 00:00:00";
	$EndDate=date('Y/m/d H:i:s');
?>
<tr>
  <td>
 <input type="hidden" id="date_switch">
    <table border='0' cellpadding='0' cellspacing='0' class="panel panel-warning" width="100%"  style="font-size:13px;margin-bottom:0px">
       <tr>
          <td colspan="2" align="center">
              

                 <ul class="nav nav-tabs" style="border-bottom: 1px solid #baa5a5;margin-bottom: 3px;">
                    <li class="active"><a href="#mapview" data-toggle="tab"><i class="fa fa fa-map" aria-hidden="true"></i>

&nbsp;Map</a></li>
                    
                    <li >
                        <a href="#reportview" data-toggle="tab"><i class="fa fa-file-text" aria-hidden="true"></i>
&nbsp;Report</a>
                        
                    </li>
                    <li>
                     <?php
                       //if($account_id==2)
                       {
                       echo'<a href="#" data-toggle="tooltip" data-placement="right" title="Text Report Current Location!" onclick="show_current_last_data();" class="hs2"><i class="fa fa-road" aria-hidden="true"></i>&nbsp;
                                       <img src="../../images/live/live_vehicle.gif" width="8px" hieght="8px" style="border:none;">
                               </a>  ';
                        
                       }
                       ?>
                       

                    </li>
                </ul>
                

              
          </td>
      </tr>
      <tr>        
        <td colspan="2" align="left">
         
          <table  width="100%" align="left" cellspacing="2" cellpadding="2">
            <tr>
                <td  align="left"><b>Start Date</b></td>
                <td  align="left"><b>:</b></td>         
              <td align="left">
                
                      <?php             
                        echo'<input type="text" id="date1" name="start_date" value="'.$StartDate.'" size="15" maxlength="19">';
                      ?>
                <a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)><i class="fa fa-calendar" aria-hidden="true"></i></a>    
              </td>
            </tr>
            <tr>
                <td align="left"><b>End Date</b></td>
                 <td  align="left"><b>:</b></td>             
              <td align="left">  
                
                <?php             
                   echo'<input type="text" id="date2" name="end_date" value="'.$EndDate.'" size="15" maxlength="19">';
                  ?> 
                 <a href=javascript:NewCal("date2","yyyymmdd",true,24)><i class="fa fa-calendar" aria-hidden="true"></i></a>	
          		   
              </td>
            </tr>
            <tr>
                  <td align="left"><b>Interval</b></td>
                   <td  align="left"><b>:</b></td>               
                <td align="left">
                   <select name='interval'  >
                    <option value='auto'>Auto</option> 
                    <option value="5">5 sec</option>
                    <option value="30" selected>30 sec</option>
  									<option value="60">1 min</option>
  									<option value="120">2 min</option>
  									<option value="240">4 min</option>
  									<option value="300">5 min</option>
  									<option value="600">10 min</option>
  									<option value="900">15 min</option>
  									<option value="1200">20 min</option>
  									<option value="1800">30 min</option>
								    <option value="3600">60 min</option>								
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="3">
                    <div class = "btn-group" data-toggle = "radio" style="width:100%">
                       <label class = "">
                          <input type = "radio" name ="mode" value="1"  checked Onclick="javascript:switch_vehicle_selection(this.value);"> Last Position
                       </label>

                       <label >
                          <input type = "radio" name = "mode" value="2" Onclick="javascript:switch_vehicle_selection(this.value);"> Track Position
                       </label>   

                    </div>
                   
                </td>

             </tr>
          </table>
         </td> 
      </tr>
       
      
      <tr>
          <td colspan="2" align="center">
              <div class="tab-content">
                    <div id="mapview" class="tab-pane fade in active">
                      Latitude/Longitude&nbsp;<input type="checkbox" name="latlng" onclick="javascript:show_latlng()">     
                      <p><a href="#" style="text-decoration:none;padding: 3px 12px;" class="btn btn-success" Onclick="javascript:return show_data_on_map('map_report');" >Get Map View</a></p>
                    </div>
                    <div id="reportview" class="tab-pane fade">
                        <p><b>Data with Location </b><input type="checkbox" name="location" checked data-toggle="toggle" data-onstyle="success" data-size="mini" data-on="<i class='fa fa-check '></i> <i class='fa fa-map-marker '></i>" data-off="<i class='fa fa-times'></i> <i class='fa fa-map-marker '></i></p>">
                      <p><a href="#" Onclick="javascript:return show_data_on_map('text_report');" style="text-decoration:none;padding: 3px 12px;" class="btn btn-success">Get Text Report</a></p>
                    </div>
                  <BR>
               </div>
          </td>
      </tr>
        
      
      
    </table>
  </td>
</tr>
