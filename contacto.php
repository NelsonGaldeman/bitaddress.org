<?php

require_once 'vendor/autoload.php';
include 'config.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {

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

    $mail->Subject = 'Contacto de Regalale Bitcoins';

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
    <input type="submit" value="Enviar"/>
</form>
';

include('contenido.php');