<?php
  $query1="SELECT refresh_rate from account_preference WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $refresh_rate1=$row1->refresh_rate;

  if($refresh_rate1==1)
  {
  echo '<tr>
          <td>
            <table border="0" class="module_left_menu">
              <tr>
                <td>
                 Refresh Rate&nbsp;:&nbsp;
                 <select name="refresh_rate">
                  <option value="disable">Disable</option>
                </select>
                </td>
              </tr>
            </table>
          </td>
        </tr>';
  }
?>
