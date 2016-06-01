<?php
  /*$query1="SELECT latlng from account_preference WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $lat_lng=$row1->latlng;

  if($lat_lng==1)
  {
  echo'<tr>
          <td>
            <table border="0" class="module_left_menu">
              <tr>
                <td>
                 Latitude/Longitude &nbsp;&nbsp;<input type="checkbox" name="latlng" onclick="javascript:show_latlng()">     
                </td>
              </tr>
            </table>
          </td>
        </tr>';
    }*/
 ?>

<?php
  $query1="SELECT latlng from account_preference WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $lat_lng=$row1->latlng;

  if($lat_lng==1)
  {
  ?>  
    <td>
     <input type="checkbox"  onchange="show_data_on_map('map_report');" name="geofence_feature" data-toggle="toggle" data-onstyle="info" data-size="mini"  data-on="<i class='fa fa-check '></i>Geofence" data-offstyle="warning" data-off="<i class='fa fa-times'></i>Geofence"  >  
    </td>
    <td>
    <!--<input type="checkbox" name="latlng" id="toggle-one_latlng"  data-toggle="toggle" data-onstyle="info" data-size="mini"  data-on="<i class='fa fa-map-marker '></i> Lat/Lng" data-off="<i class='fa fa-map-marker '></i> Lat/Lng" > -->   
    <!-- Latitude/Longitude&nbsp;<input type="checkbox" name="latlng" onclick="javascript:show_latlng()"> -->    
   
    </td>      
  <?php
  }
  ?>