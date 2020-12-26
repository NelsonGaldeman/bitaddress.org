<?php

require_once 'vendor/autoload.php';
include 'config.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $recaptchaKey,
        'response' => $_POST["g-recaptcha-response"]
    );
    $options = array(
        'http' => array (
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success = json_decode($verify);

    if ($captcha_success->success==false) {
        exit();
    }

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = $SMTP_SERVER;
    $mail->SMTPAuth = true;
    $mail->Username = $SMTP_USER;
    $mail->Password = $SMTP_PASSWORD;
    $mail->Port = $SMTP_PORT;
    $mail->SMTPSecure = 'tls';

    $mail->setFrom($SMTP_USER, 'Regalale Bitcoins');
    $mail->addAddress('nelsongaldeman@gmail.com', 'Nelson Galdeman');

    $mail->Subject = 'Contacto de Regalale Bitcoins - #'.rand(1000000,9999999);

    $mail->Body = $_POST['name']."\n".$_POST['email']."\n\n".$_POST['message'];

    if ($mail->send()){
        $sent = true;
    } else {
        $sent = false;
    }
}


if (isset($sent) && $sent) {
    $content .= "Mensaje enviado!";
} else if (isset($sent) && !$sent) {
    $content .= "Error al enviar el mensaje!";
}

$content .= '
<form action="contacto.php" method="POST" enctype="multipart/form-data">
    <span>Nombre</span><br>
    <input type="text" name="name" style="width: 300px"/><br><br>
    <span>Email</span><br>
    <input type="text" name="email" style="width: 300px"/><br><br>
    <span>Mensaje</span><br>
    <textarea name="message" style="width: 300px; height: 250px;"></textarea><br><br>
    <div class="g-recaptcha" data-sitekey="6Lf6xhUaAAAAAK7RIq_F8O0tMBLWS6FdMQ0hJgWe"></div>
    <br><br>
    <input type="submit" value="Enviar"/>
</form>
';

define('CONTACT', true);

include('contenido.php');