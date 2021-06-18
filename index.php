<?php 
//include library
require_once __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["message"]) && isset($_POST["Email"]))
{
    $fname=$_POST["fname"];
    $lname=$_POST["lname"];
    $email=$_POST["Email"];
    $message = $_POST["message"];

    //a new instance of library
    $mpdf = new \Mpdf\Mpdf();
    $data="";
    $data .="<h1>Your Details</h1>";
    $data .="<strong>First name</strong>".$fname."<br>";
    $data .="<strong>Last name</strong>".$lname."<br>";
    $data .="<strong>Email</strong>".$email."<br>";
    $data .="<strong>Message</strong>".$message."<br>";
    $mpdf -> WriteHtml($data);
   // $mpdf ->output("myfile.pdf","D");
   $enquirydata=[
       'fname'=>$fname,
       'lname'=>$lname,
       'email'=>$email,
       'message'=>$message
   ];


   $pdf= $mpdf->output("","S");
   sendEmail($pdf, $enquirydata);
}
   function sendEmail($pdf, $enquirydata)
   {
        $emailbody='';
        $emailbody .='<h1>Email from'.$enquirydata['fname'].'</h1>';
        foreach($enquirydata as $title => $data)
        {
            $emailbody .= "<strong>" .$title."</strong> :" .$data. "<br/>";
        }
   
       //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = '4e5d5a0fb35f99';                     //SMTP username
            $mail->Password   = 'a75ee2b3876e16';                               //SMTP password
            $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 2525;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('tezt@email.com', 'Mailer');
            $mail->addAddress('thuvamit2017@gmail.com', 'Joe User');     //Add a recipient
            

            //Attachments
            $mail->addStringAttachment($pdf,"attchment.pdf");

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Email from'.$enquirydata["fname"];
            $mail->Body    = $emailbody;
            $mail->AltBody = strip_tags($emailbody);

            $mail->send();
            header("Location:thanks.php?fname=".$enquirydata['fname']);
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

?>

<!DOCTYPE HTML>
<html>
<head>
<title>creating pdf file</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
    <h1>create PDF from Html</h1>
    <p>Fill out the Details to generate the pdf</p>
    <form action="index.php" method="post">
        <input type="text" placeholder="First name" name="fname" class="form-control" required>
        <input type="text" placeholder="Last name" name="lname" class="form-control" required>
        <input type="email" placeholder="Email" name="Email" class="form-control" required>
        <textarea name="message" placeholder="Message"  class="form-control" required>
        </textarea>
        <button class="btn btn-success" type="submit">Generate</button>
    </form>
    </div>
</body>
</html>