<?php 
$to_email = "bhatiaishika00@gmail.com";
$username = "ishika";
$email_c = "bhatiaishika00@gmail.com";
$messsage = "Hello done";
$subject = "Fedback from customer";
$body = " Username :".$username." Email :".$email_c."\n".$messsage;
$headers = "From: bhatiaishika00@gmail.com";

if(mail($to_email, $subject, $body, $headers))
{
	echo "Feedback sent";
}
else{
	
    echo "Try again";
    
}
?>