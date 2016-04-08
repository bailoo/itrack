<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
?>

<?php
  $DEBUG=0;
  
  $query="SELECT DISTINCT req_id FROM request ORDER BY create_date DESC";
  if($DEBUG) print_query($query);
  $result=mysql_query($query,$DbConnection);
  $count = mysql_num_rows($result);
  
  while ($row=mysql_fetch_object($result))
  {
    $req_id=$row->req_id;
    
    $query1="SELECT name,subject,body,create_id,create_date FROM request WHERE req_id=".$req_id." ORDER BY create_date LIMIT 1";
    if($DEBUG) print_query($query1);
    $result1=mysql_query($query1,$DbConnection);
    $row1=mysql_fetch_object($result1);
    $create_id[$req_id] = $row1->create_id;
        
    $query2="SELECT status,create_date FROM request WHERE req_id=".$req_id." ORDER BY create_date DESC";
    if($DEBUG) print_query($query2);
    $result2=mysql_query($query2,$DbConnection);
    $post_count[$req_id] = mysql_num_rows($result2);
    $row2=mysql_fetch_object($result2);
    
    $query3="SELECT superuser,user,grp FROM account WHERE account_id=".$create_id[$req_id]." LIMIT 1";
    if($DEBUG) print_query($query3);
    $result3=mysql_query($query3,$DbConnection);
    $row3=mysql_fetch_object($result3);
    
    $id[$req_id] = $req_id;    
    $name[$req_id] = $row1->name;    
    $subject[$req_id] = $row1->subject;    
    $body[$req_id] = $row1->body;    
    $status[$req_id] = $row2->status;    
    $create_date[$req_id] = $row1->create_date;
    $latest_date[$req_id] = $row2->create_date;
    $login_superuser[$req_id] = $row3->superuser;
    $login_user[$req_id] = $row3->user;
    $login_group[$req_id] = $row3->grp;
        
    $name[$req_id] = substr($name[$req_id], 0, 10)."...";    
    $subject[$req_id] = substr($subject[$req_id], 0, 20)."...";    
    $body[$req_id] = substr($body[$req_id], 0, 50)."...";
    $login[$req_id] = $login_superuser[$req_id].",".$login_user[$req_id].",".$login_group[$req_id];
    
    if ($status[$req_id]=="0")
    {
      $status[$req_id] = "<font color=green>Close</font>";
    }
    else if ($status[$req_id]=="1")
    {
      $status[$req_id] = "<font color=red>Open</font>";
    }
    else
    {
      $status[$req_id] = "<font color=blue>Unknown</font>";
    }
  }
?>

<?php

  echo "<table border='1' align=center class='manage_interface' cellspacing='2' cellpadding='2' width=100%>";
  
  echo '
    <tr>
      <td colspan="9" align="center">
        <b>All Feedback Requests</b>
        <div style="height:5px;"></div>
      </td>    
    </tr>
    ';
  
  echo "<tr><th>ID</th><th>Login</th><th>Name</th><th>Subject</th><th>Content</th><th>Posts</th><th>Create Time</th><th>Latest Time</th><th>Status</th></tr>";
  if($count>0)
  {
    foreach ($id as $i=>$req_id)
    {
      echo "<tr>";
      echo "<td>".$req_id."</td>";
      echo "<td>".$login[$req_id]."</td>";
      echo "<td>".$name[$req_id]."</td>";
      echo "<td>".$subject[$req_id]."</td>";
      echo "<td><a href=\"javascript:show_request($req_id,0)\";>".$body[$req_id]."</a></td>";
      echo "<td>".$post_count[$req_id]."</td>";
      echo "<td>".$create_date[$req_id]."</td>";
      echo "<td>".$latest_date[$req_id]."</td>";
      echo "<td>".$status[$req_id]."</td>";
      echo "</tr>";
    }
  }
  echo "</table>";


?>