<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  $DEBUG=0;
  $flag=0;
	$result_response=1;
	
  // Get all POST data
  $post_req_id=$_POST['req_id'];
  $post_name=$_POST['name'];
  $post_subject=$_POST['subject'];
  $post_status=$_POST['status'];
  $post_body=htmlspecialchars($_POST['body']);
  if($DEBUG)
  {
    echo "Request ID = ".$post_req_id." (Length: ".strlen($post_req_id).") <br>";
    echo "Name = ".$post_name." (Length: ".strlen($post_name).") <br>";
    echo "Subject = ".$post_subject." (Length: ".strlen($post_subject).") <br>";
    echo "Status = ".$post_status." (Length: ".strlen($post_status).") <br>";
    echo "Body = ".$post_body." (Length: ".strlen($post_body).") <br>";
  }
  
  if (strlen($post_req_id)==0 || strlen($post_name)==0 || strlen($post_subject)==0 || strlen($post_status)==0 || strlen($post_body)==0)
  {
    $flag = -1;
  }
  else
  {
    // Date of account creation
    date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
  	$date=date("Y-m-d H:i:s");
  	
    // Add feedback into request table
    $query="INSERT INTO request (req_id,name,subject,body,status,create_id,create_date) VALUES ('$req_id','$post_name','$post_subject','$post_body','$post_status','$account_id','$date')";
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
    $msg = "Feedback Updated Successfully!<br>Your feedback ID is: ".$req_id;
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
        