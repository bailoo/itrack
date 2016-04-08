<?php
  $query1="SELECT latlng from account_preference WHERE account_id='$account_id'";
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
    }
 ?>