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




class SendMail extends Action
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

        $products = $data['products'];
        $totalAmount = $data['totalAmount'];
        $phone = $data['phone'];
        $adress = $data['adress'];
        $payMethod = $data['payMethod'];
        $token = $data['token'];
        if ($token === 'Рowpowtoken') {
            $this->sendVerificationEmail($products, $totalAmount, $phone, $adress, $payMethod);
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
    protected function sendVerificationEmail($products, $totalAmount, $phone, $adress, $payMethod)
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
        $mail->Subject="Поступил новый заказ";
        $mail->isHTML();

        $body = "<p>Внимание! Поступил новый заказ, пожалуйста свяжитесь с клиентом.</p>";
        $body .= "<h3>Список товаров:</h3>";
        foreach ($products as $product) {
            $body .= $product ."<br>";
        }
        $body .= "<br>";
        $body .= "<p><strong>Сумма заказа: </strong>$totalAmount</p>";
        $body .= "<p><strong>Номер клиента: </strong>$phone</p>";
        $body .= "<p><strong>Адрес доставки: </strong>$adress</p>";
        $body .= "<p><strong>Способ оплаты: </strong>$payMethod</p>";
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
