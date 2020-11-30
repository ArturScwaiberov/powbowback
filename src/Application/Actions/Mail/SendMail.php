<?php


//Require autoload.php of PHPMailer
require_once '../vendor/autoload.php';

$app = new \Slim\App;

$app->post('/forgotpassword',function(Request $request, Response $response)
{
        $requestParamter = $request->getParsedBody();
        $email =  $requestParamter['email'];
        $id = $requestParamter['id'];
        sendVerificationEmail($email,$id);

});


//Function to send mail, 
function sendVerificationEmail($email,$id)
{      
    $mail = new PHPMailer;

    $mail->SMTPDebug=3;
    $mail->isSMTP();

    $mail->Host="smtp.gmail.com";
    $mail->Port=587;
    $mail->SMTPSecure="tls";
    $mail->SMTPAuth=true;
    $mail->Username="socialcodia@gmail.com";
    $mail->Password="12345";

    $mail->addAddress($email,"User Name");
    $mail->Subject="Verify Your Email Address For StackOverFlow";
    $mail->isHTML();
    $mail->Body=" Welcome to StackOverFlow.<b><b> Please verify your email adress to continue..";
    $mail->From="SocialCodia@gmail.com";
    $mail->FromName="Social Codia";

    if($mail->send())
    {
        echo "Email Has Been Sent Your Email Address";
    }
    else
    {
        echo "Failed To Sent An Email To Your Email Address";
    }


}


$app->run();