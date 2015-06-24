<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	require 'PHPMailer_5.2.4/class.phpmailer.php';
	
	$root = $_SESSION['root'];
	$DEBUG=0; 
	$account_id_local=explode(",",$account_id_local);
    $vehicle_imei_name=explode("@",$vehicle_imei_name);
        
	if($action_type =="add")                     ///////// ADD
	{
			$max_no1=getMaxDocketNoConsignmentInfo($account_id_local[0],$DbConnection);			
			$max_no1 = preg_replace("/[^0-9]/", '', $max_no1);          
			echo "mas_no1=".$max_no1."<br>";

			if($max_no1=="")
			{
				$dock_no=$account_id_local[1]."/0001";
			}
			else
			{ 
				$max_no1=$max_no1+1; 
				//echo "mas_no1=".$max_no1."<br>";                    
				if($max_no1<=9)
				{$dock_str="000";}
				else if($max_no1>=10 && $max_no1<=99)
				{$dock_str="00";}
				else if($max_no1>=100 && $max_no1<=999)
				{$dock_str="0";}
				else
				{$dock_str="";}       
				$dock_no =$account_id_local[1]."/".$dock_str.$max_no1;            
			}
		
			$Result=insertConsignmentInfo($account_id_local[0],$vehicle_imei_name[0],$vehicle_imei_name[1],$from_place,$to_place,$consignee_name,$start_date,$end_date,$dock_no,$account_id,$date,$email,1,$remark,$DbConnection);
		    //echo "result=".$Result."<br>";
			if($Result)
			{
			//echo "in if<br>";
					$message_web = "<center>
								<br>
									<FONT color=\"green\">
										 <strong>Your Docket Number is : </strong>
								   </font>
									<font color='red'>
									   <strong> ".$dock_no."</strong>
									 </font></center>";
									 
					//SEND EMAIL
					
					$user_id =getUserID($account_id_local[0],1,$DbConnection);
					//$to = $email;
					$message = "";
					$subject = 'Confirmation-VTS:Consignment Added :DOCKET NO'.$dock_no;
					$message = '<br><font color=green><strong>Consignment Added Successfully</strong></font>';
					$message.= '<br><strong>UserID :"</strong><font color=blue><strong>'.$user_id.'</strong></font>';
					$message .= '<br><font color=blue><strong>DOCKET NO : </strong></font><font color=green><strong>'.$dock_no.'</strong></font>';
					$message.= '<br><br><strong>CONSIGNMENT DETAIL:-</strong>';
					$message.= '<br><strong>Consignee Name :</strong><font color=blue><strong>'.$consignee_name.'</strong></font>';
					$message.= '<br><strong>Vehicle Name :</strong><font color=blue><strong>'.$vehicle_imei_name[1].'</strong></font>';
					$message.= '<br><strong>Start Date :</strong><font color=blue><strong>'.$start_date.'</strong></font>';
					$message.= '<br><strong>End Date :</strong><font color=blue><strong>'.$end_date.'</strong></font>';
					$message.= '<br><strong>From Place :</strong><font color=blue><strong>'.$from_place.'</strong></font>';
					$message.= '<br><strong>To Place :</strong><font color=blue><strong>'.$to_place.'</strong></font>';
					
					$message .= '<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>'; 
					
					try
					{
						$mail = new PHPMailer(true); //New instance, with exceptions enabled

						//$body             = file_get_contents('contents.html');
						$body             = "<html><head></head><body>".$message."</body></html>";
						$body             = preg_replace('/\\\\/','', $body); //Strip backslashes

						$mail->IsSMTP();                           // tell the class to use SMTP
						$mail->SMTPAuth   = true;                  // enable SMTP authentication
						$mail->Port       = 25;                    // set the SMTP server port
						$mail->Host       = "localhost"; // SMTP server
						$mail->Username   = "support@iembsys.co.in";     // SMTP server username
						$mail->Password   = "support123iespl";            // SMTP server password

						$mail->IsSendmail();  // tell the class to use Sendmail

						//$mail->AddReplyTo("rizwan@iembsys.com","First Last");

						$mail->From       = "support@iembsys.co.in";
						$mail->FromName   = "Confirmation:VTS-Consignment";

						//$to = "rizwan@iembsys.com";
						$to = $email;

						$mail->AddAddress($to);

						$mail->Subject  = $subject;

						$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
						$mail->WordWrap   = 80; // set word wrap

						$mail->MsgHTML($body);

						$mail->IsHTML(true); // send as HTML

						$mail->Send();
						//echo 'Message has been sent.';
					} 
					catch (phpmailerException $e) 
					{
						//echo $e->errorMessage();
					}					
					/*$random_hash = md5(date('r', time()));  
					$headers = "From: support@iembsys.co.in\r\n";
					//$headers .= "Cc: rizwan@iembsys.com";  
					$headers .= "Cc: rizwan@iembsys.com";	
					$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 

					//echo "\nFILE PATH=".$file_path;  
					include_once("send_mail_api_simple.php");*/
					//################################//
			}   
			else
			{
				$message = "<center><br>
								<FONT color=\"red\">
									<strong>Sorry! Unable to process request.</strong></font>
							 </center>";
			   
			} 	 
		//}
	}
	
	echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
			<tr>
				<td colspan="3" align="center"><b>'.$message_web.'</b></td>    
			</tr>
		</table><br>'; 
	echo'<center><a href="javascript:show_option(\'manage\',\'consignment_info\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
  //include_once("manage_action_message.php");
?>
        