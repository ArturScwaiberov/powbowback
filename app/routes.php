<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

//From PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Function to send mail, 
function sendVerificationEmail()
{      
    $mail = new PHPMailer;

    $mail->SMTPDebug=3;
    $mail->isSMTP();

    $mail->Host="evamall.altkg.com";
    $mail->Port=587;
    $mail->SMTPSecure="tls";
    $mail->SMTPAuth=true;
    $mail->Username="purchases@evamall.altkg.com";
    $mail->Password='$Q7pi64n';

    $mail->addAddress("3.13.13@mail.ru","User Name");
    $mail->Subject="Verify Your Email Address For StackOverFlow";
    $mail->isHTML();
    $mail->Body=" Welcome to StackOverFlow.<b><b> Please verify your email adress to continue..";
    $mail->From="purchases@evamall.altkg.com";
    $mail->FromName="Social Evamall";

    if($mail->send())
    {
        echo "Email Has Been Sent Your Email Address";
    }
    else
    {
        echo "Failed To Sent An Email To Your Email Address";
    }


}


return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/mail',function(Request $request, Response $response)
    {
        $requestParamter = $request->getParsedBody();
        sendVerificationEmail();

    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};


