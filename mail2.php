<?php
    require(“includes/classes/PHPMailer.php”);
    $mail=new PHPMailer(); //creat PHPMailer object
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPAuth = true; //needs login information
    $mail->SMTPSecure = “tls”; //specifies tls security
    $mail->Host = “smtp.gmail.com”; // sets GMAIL as the SMTP server
    $mail->Port = 465; //gmail smtp port
    $mail->Username = ‘bhatiaishika00@gmail.com’; //SMTP account username
    $mail->Password = ‘bhatia@ishika@123’; // SMTP account password
    $mail->From = ‘bhatiaishika00@gmail.com’; //sender’s e-mail address
    $mail->Body =‘The lecture videos have been uploaded successfully in the courses window and you can access it from there. Thank you’;//e-mail message
//add file attachment


    $con = mysqli_connect("localhost", "root", "", "e-learning");

    if(! $con ) {
    die('Could not connect: ' . mysql_error());
    }

    $sql = 'SELECT email FROM users';
    $retval = mysql_query( $sql, $con );

    if(! $retval ) {
    die('Could not get data: ' . mysql_error());
    }

    while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
        $mail->AddAddress($row[‘email’]);//receiver’s e-mail address
    }



    mysql_close($con);


$mail->WordWrap = 50;
if(!$mail->Send())
{
$status=’Message was not sent.’;
}
else
{
$status=’Message has been sent.’;
}

}
?>