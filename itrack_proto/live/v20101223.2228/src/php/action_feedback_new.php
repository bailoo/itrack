<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  $DEBUG=0;
  $flag=0;
	$result_response=1;
	
  // Get all POST data
  $post_name=$_POST['name'];
  $post_subject=$_POST['subject'];
  $post_body=htmlspecialchars($_POST['body']);
  if($DEBUG)
  {
    echo "Name = ".$post_name." (Length: ".strlen($post_name).") <br>";
    echo "Subject = ".$post_subject." (Length: ".strlen($post_subject).") <br>";
    echo "Body = ".$post_body." (Length: ".strlen($post_body).") <br>";
    // echo "Body = ".str_replace(chr(10), "<br>", $post_body)." (Length: ".strlen($post_body).") <br>";
  }
  
  if (strlen($post_name)==0 || strlen($post_subject)==0 || strlen($post_body)==0)
  {
    $flag = -1;
  }
  else
  {
    // Date of account creation
    date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
  	$date=date("Y-m-d H:i:s");
    
    // Get request number for new feedback 
    $query="SELECT MAX(req_id)+1 as req_id FROM request";
    if($DEBUG) print_query($query);
  	$result=mysql_query($query,$DbConnection);
  	$row=mysql_fetch_object($result);
  	$req_id=$row->req_id;
  	
    // Add feedback into request table
    $query="INSERT INTO request (req_id,name,subject,body,status,create_id,create_date) VALUES ('$req_id','$post_name','$post_subject','$post_body','1','$account_id','$date')";
    $result1=mysql_query($query,$DbConnection);
    $result_response = $result_response && $result1;
    if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);

    if($result_response)
  	{
  	 $flag=1;
    }
  }

  if ($flag==-1)
	{
    $msg = "Empty input. Please try again.";
    $msg_color = "red";
	}					
	else if($flag==1)
	{
    $msg = "Feedback Added Successfully!<br>Your feedback ID is: ".$req_id;
    $msg_color = "green";
	}					
	else if($flag==0)
	{
    $msg = "Unable to add feedback due to some server problem!";
    $msg_color = "red";
	}
	else
	{
    $msg = "Sorry! Unable to process request!";
    $msg_color = "blue";
  }
  
  echo "<br><font color=\"".$msg_color."\" size=4><strong>".$msg."</strong></font>";  
?>
        