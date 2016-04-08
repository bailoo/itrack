<?php

   // Include the Mail package
   require "Mail.php";

   // Identify the sender, recipient, mail subject, and body
   $sender    = "iespltest@gmail.com";
   $recipient = "rizwan@iembsys.com";
   $subject   = "Thank you for your email!";
   $body      = "I'll get back to you as soon as I can!";

   // Identify the mail server, username, password, and port
   $server   = "ssl://smtp.gmail.com";
   $username = "iespltest@gmail.com";
   $password = "iespl_iembsys";
   $port     = "465";

   // Set up the mail headers
   $headers = array(
      "From"    => $sender,
      "To"      => $recipient,
      "Subject" => $subject
   );

   // Configure the mailer mechanism
   $smtp = Mail::factory("smtp",
      array(
        "host"     => $server,
        "username" => $username,
        "password" => $password,
        "auth"     => true,
        "port"     => 465
      )
   );

   // Send the message
   $mail = $smtp->send($recipient, $headers, $body);

   if (PEAR::isError($mail)) {
      echo ($mail->getMessage());
   }

?>