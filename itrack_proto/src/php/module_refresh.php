<?php
  $query1="SELECT refresh_rate from account_preference WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $refresh_rate1=$row1->refresh_rate;

  if($refresh_rate1==1)
  {
  echo '<tr valign="top">
          <td valign="top">
            <table border="0" class="module_left_menu">
              <tr valign="top">
                <td valign="top">
					&nbsp;&nbsp;Refresh Rate&nbsp;:&nbsp;
	               <select name="autoref_combo" onchange="auto_refresh()">
						 <option name="box0" value="0">disable</option>
						 <option name="box1" value="0"></option>
						 <!--<option name="box5s" value="5">5 sec</option>-->
						 <option name="box10s" value="10" selected>10 sec</option>
						 <option name="box20s" value="20">20 sec</option>
						 <option name="box30s" value="30">30 sec</option>
						 <option name="box1m" value="60">1 min</option>
						 <option name="box2m" value="120">2 min</option>
						 <option name="box3m" value="180">3 min</option>
						 <option name="box4m" value="240">4 min</option>
						 <option name="box5m" value="300">5 min</option>
						 <option name="box10m" value="360">10 min</option>
					</select>
                </td>
              </tr>
            </table>
          </td>
        </tr>';
        
        echo '<tr valign="top"><td><span style="font-size:x-small;">&nbsp;&nbsp;&nbsp;(Note: Below 2 min interval is valid for single vehicle only)</span></td></tr>';        
  }
?>
