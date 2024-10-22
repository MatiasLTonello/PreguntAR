<?php

use PHPMailer\PHPMailer\PHPMailer;

class EmailHelper
{

    public function enviarEmailDeValidacion($nombreCompleto, $email, $verify_token)
    {
        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'preguntar029@gmail.com';       //SMTP username
        $mail->Password   = 'hhcq pgdd pslu ohbc';                     //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('preguntar029@gmail.com', "PreguntAR");
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Email verification from PreguntAR';

        // CSS styles for the email template
        $email_template = "
        <html lang='es'>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }

                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #f7f7f7;
                    border-radius: 5px;
                    text-align: center;
                }

                h2 {
                    color: #333333;
                }

                h5 {
                    color: #666666;
                }
                
                p {
                color: black !important;
                font-size: 18px;
                }

                a {
                    display: inline-block;
                    margin-top: 10px;
                    padding: 10px 20px;
                    background-color: #ffd0f6;
                    color: black !important;
                    text-decoration: none;
                    border-radius: 5px;
                }
                
                a:hover {
                color: black !important;
                background-color: #ffbaf7;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Hola $nombreCompleto! Te has registrado en PreguntAR</h2>
                <p>Verifica tu correo electr&oacute;nico con el siguiente enlace:</p>
                <a href='http://localhost/login/login?token=$verify_token'>Verificar correo electr&oacute;nico</a>
            </div>
        </body>
        </html>
    ";

        $mail->Body = $email_template;

        $mail->send();
    }

}