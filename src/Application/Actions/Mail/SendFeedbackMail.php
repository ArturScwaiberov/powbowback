<?php
declare(strict_types=1);

namespace App\Application\Actions\Mail;



use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

//From PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;




class SendFeedbackMail extends Action
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger);
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        $phone = $data['phone'];
        $message = $data['message'];
        $token = $data['token'];
        if ($token === 'Рowpowtoken') {
            $this->sendVerificationEmail( $phone, $message,);
        }
        $payload = json_encode($data);
        $this->response->getBody()->write($payload);
        return $this->response->withHeader('Content-Type', 'application/json');
        // if ($requestParamter) {
        //     $email = $requestParamter['email'];
        //     $password = $requestParamter['password'];
        //     $this->sendVerificationEmail();
        // }
        // $str = $email ? 'email sended to: ' . $email : 'empty';
        // $this->response->getBody()->write($str);
        // return $this->response;
    }

    //Function to send mail, 
    protected function sendVerificationEmail( $phone, $message,)
    {      
        $mail = new PHPMailer;

        $mail->SMTPDebug=3;
        $mail->isSMTP();

        $mail->Host="evamall.altkg.com";
        $mail->Port=587;
        $mail->SMTPSecure="tls";
        $mail->SMTPAuth=true;
        $mail->Username="eva-purchases@evamall.altkg.com";
        $mail->Password='I9ezpDh#4!d_kadv&$288@';
        $mail->CharSet = 'utf-8';

        $mail->addAddress("3.13.13@mail.ru");
        $mail->addAddress("artlabteam.com@gmail.com");
        $mail->Subject="Новое сообщение от пользователя";
        $mail->isHTML();

        $body = "<p>Внимание! Поступило сообщение из формы обратной связи, пожалуйста свяжитесь с ним.</p>";
        $body .= "<h3>Сообщение:</h3>";
        $body .= "<p>$message</p>";
        $body .= "<p><strong>Номер пользователя: </strong>$phone</p>";
        $body .= "<br><hr>";
        $body .= "<h2>EvaMall</h2>";
        $mail->Body=$body;
        $mail->From="eva-purchases@evamall.altkg.com";
        $mail->FromName="EvaMall";

        if($mail->send())
        {
            echo "Email Has Been Sent Your Email Address";
        }
        else
        {
            echo "Failed To Sent An Email To Your Email Address";
        }
    }
}
