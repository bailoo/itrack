<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG = 0;
	
  // Get all POST data
  $post_id=$_POST['id'];
  $post_type=$_POST['t'];
  if($DEBUG)
  {
    echo "Request ID = ".$post_id." (Length: ".strlen($post_id).") <br>";
    echo "Type = ".$post_type." (Length: ".strlen($post_type).") <br>";
    // echo "Body = ".$post_body." (Length: ".strlen($post_body).") <br>";
    // echo "Body = ".str_replace(chr(10), "<br>", $post_body)." (Length: ".strlen($post_body).") <br>";
  }
  
  if (strlen($post_id)==0 || strlen($post_type)==0)
  {
    echo "<center><font color=red size=4><strong>Wrong Input</strong></font></center><hr>";
  }
  else
  {
    $req_id=$post_id;
    
    $query="SELECT name,subject,body,status,create_id,create_date FROM request WHERE req_id=".$req_id." ORDER BY create_date";
    if($DEBUG) print_query($query);
    $result=mysql_query($query,$DbConnection);
    $count = mysql_num_rows($result);
    	
    if($count <= 0)
    {
      echo "<center><font color=red size=4><strong>Wrong Request ID</strong></font></center><hr>";
    }
    else
    {
      echo "<form method = \"post\"  name=\"thisform\">";
      echo "<table border=0 width=\"100%\" cellpadding=\"2\" class=\"manage_interface\" >";
      echo '
        <tr>
          <td colspan="2" align="center">
            <b>Request ID: '.$req_id.'</b>
            <div style="height:5px;"></div>
          </td>    
        </tr>
        ';
      $own_post=0;
      while ($row=mysql_fetch_object($result))
      {
        $create_id[$req_id] = $row->create_id;
        
        if($create_id[$req_id] == $account_id)
        {
          $own_post=1;
        }
      
        $query1="SELECT superuser,user,grp FROM account WHERE account_id=".$create_id[$req_id]." LIMIT 1";
        if($DEBUG) print_query($query1);
        $result1=mysql_query($query1,$DbConnection);
        $row1=mysql_fetch_object($result1);
    
        $id[$req_id] = $req_id;    
        $name[$req_id] = $row->name;    
        $subject[$req_id] = $row->subject;    
        $body[$req_id] = $row->body;    
        $status_raw[$req_id] = $row->status;    
        $status[$req_id] = $row->status;    
        $create_date[$req_id] = $row->create_date;
        $login_superuser[$req_id] = $row1->superuser;
        $login_user[$req_id] = $row1->user;
        $login_group[$req_id] = $row1->grp;
        $login[$req_id] = $login_superuser[$req_id].",".$login_user[$req_id].",".$login_group[$req_id];
        $body[$req_id] = str_replace(chr(10), "<br>", $body[$req_id]);
        
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

        echo '
          <tr >
            <td colspan="2"><div style="height:10px"></div></td>
          </tr>
          <tr>
            <td class="srl">Login : </td>
            <td class="srr">'.$login[$req_id].'</td>
          </tr>
          <tr>
            <td class="srl">Name : </td>
            <td class="srr">'.$name[$req_id].'</td>
          </tr>
          <tr>
            <td class="srl">Subject : </td>
            <td class="srr">'.$subject[$req_id].'</td>
          </tr>
          <tr>
            <td class="srl">Status : </td>
            <td class="srr">'.$status[$req_id].'</td>
          </tr>
          <tr>
            <td class="srl">Date : </td>
            <td class="srr">'.$create_date[$req_id].'</td>
          </tr>
          <tr>
            <td class="srl">Body : </td>
            <td class="srr">'.$body[$req_id].'</td>
          </tr>
        ';        
      }
      if ($post_type==1 || $status_raw[$req_id]==1 || $own_post==1)
      {
        echo '
            <tr>
              <td></td>
              <td>
                <b>Request Update</b>
                <input style="width:300px" type="hidden" name="req_id" id="req_id" value='.$req_id.'>
              </td>    
            </tr>              
            <tr>
              <td class="vt">Name : </td>
              <td><input style="width:300px" type="text" name="name" id="name"></td>
            </tr>
            <tr>
              <td class="vt">Subject : </td>
              <td><input style="width:300px" type ="text" name="subject" id="subject"></td>
            </tr>
            <tr>
              <td class="vt">Status : </td>
              <td>
                <input type="radio" name="status" value="1" checked> Open 
                <input type="radio" name="status" value="0"> Close 
              </td>
            </tr> 
            <tr>
              <td class="vt">Update : </td>
              <td><textarea style="width:300px" rows="10" name="body" id="body"></textarea></td>
            </tr> 									
            <tr>
              <td colspan="2"></td>
            </tr>
            <tr>                    									
              <td></td>                    									
              <td>
                <input type="button" onclick="javascript:action_feedback_update(thisform)" value="Submit" id="submit">
              </td>
            </tr>
        ';
      }
      echo "</table>";
      echo "</form>";
    }
  }
?>



