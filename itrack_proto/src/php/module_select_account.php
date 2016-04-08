<?php 
  /*$query1="SELECT superuser,user,grp FROM account WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $superuser=$row1->superuser;
  $user=$row1->user;
  $grp=$row1->grp;  */
  
  $query2="SELECT admin_id FROM account_detail WHERE account_id='$account_id'";
  $result2=mysql_query($query2,$DbConnection);
  $row2=mysql_fetch_object($result2);
  $admin_id=$row2->admin_id;
   // echo "admin_id=".$admin_id." account_id=".$account_id."<br>";
 
  $query3="SELECT account_id,superuser,user,grp FROM account WHERE ".
  "account_id IN (SELECT account_id FROM account_detail WHERE ".
  "admin_id='$admin_id' OR account_admin_id='$admin_id' ORDER BY account_id)"; 
  //echo "query=".$query3;
  $result3=mysql_query($query3,$DbConnection);
  $i=0;
  while($row3=mysql_fetch_object($result3))
  {
    $account_id_local[$i]=$row3->account_id;
    $superuser_local[$i]=$row3->superuser;
    $user_local[$i]=$row3->user;
    $grp_local[$i]=$row3->grp;
    $i++;
  }
?>
<tr>
  <td align="center" colspan="8">
    <table border="0" class='module_left_menu' width="100%" align="center">
  <?php 
    if($superuser=="admin")      //////////// ROOT
    {
      echo '<tr>
        <td width="45%" align="right">Super User</td>
        <td width="2%" align="center">:</td>
        <td>
          <select name="superuser_accountid" onchange="javascript:show_user(this.value)">';
          for($j=0;$j<$i;$j++)
          {
            $account_id_local_1=$account_id_local[$j];
            $superuser_local_1=$superuser_local[$j];
            echo '<option value="'.$account_id_local_1.'"> '.$superuser_local_1.' </option>';       
          }
      echo '</td>
      </tr>
      <tr>
        <td width="45%" align="right">User</td>
        <td width="2%" align="center">:</td>
        <td>
          <select name="user_accountid" id="user_accountid" onchange="javascript:show_grp(this.value);">
            <option value="select">Select</opiton>
          </select>
        </td>
      </tr>
      
      <tr>
        <td width="45%" align="right">Group</td>
        <td width="2%" align="center">:</td>
        <td>
          <select name="grp_accountid" id="grp_accountid">
            <option value="select">Select</opiton>
          </select>
        </td>
      </tr>';            
    }
    
    else if($superuser!="admin" && $user=="admin")      ////////// SUPER USER
    {
        echo'
          <tr>
          <td width="45%" align="right">User</td>
          <td width="2%" align="center">:</td>
          <td>
            <select name="user_accountid" id="user_accountid" onchange="javascript:show_grp(this.value);">';
              for($j=0;$j<$i;$j++)
              {
                $account_id_local_1=$account_id_local[$j];
                $user_local_1=$user_local[$j];
                echo '<option value="'.$account_id_local_1.'"> '.$user_local_1.' </option>';       
              }
          echo'            
            </select>
          </td>
        </tr>
        
        <tr>
          <td width="45%" align="right">Group</td>
          <td width="2%" align="center">:</td>
          <td>
            <select name="grp_accountid" id="grp_accountid" onchange="report_show_vehicle(this.value);">
              <option value="select">Select</opiton>
            </select>
          </td>
        </tr>
        ';
    }
    
    else            //////////// GROUP 
    {
        echo '
        <tr>
          <td width="45%" align="right">Group</td>
          <td width="2%" align="center">:</td>
          <td>';
                   
            echo'<select name="user_accountid" onchange="report_show_vehicle(this.value);"><option value="select">Select</option>';
            for($j=0;$j<$i;$j++)
            {
              $account_id_local_1=$account_id_local[$j];
              $grp_local_1=$grp_local[$j]; 
              
              if($user=="admin") 
              {
                $perm_string = $user_local_1;
              }
              else
              {
                $perm_string = $grp_local_1; 
              }
              //echo 'acc_id='.$account_id_local_1.' ,perm_str='.$perm_string.'<br>';
              echo '<option value="'.$account_id_local_1.'"> '.$perm_string.' </option>';       
            }
            echo '</select>';
          echo '</td>
        </tr>';
    }  
            
  ?>
    </table>
  </td>
</tr>

<tr><td colspan="8" height="5px"></td></tr>

