<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class RegisterModel
{
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function sendemail_verify($nombreCompleto, $email, $verify_token)
    {
        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'matiaslucianotonello11@gmail.com';       //SMTP username
        $mail->Password   = 'wkmj ympg nggf aube';                     //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('matiaslucianotonello11@gmail.com', "PreguntAR");
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Email verification from PreguntAR';

        // CSS styles for the email template
        $email_template = "
        <html>
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
                <a href='http://localhost/login?token=$verify_token'>Verificar correo electr&oacute;nico</a>
            </div>
        </body>
        </html>
    ";

        $mail->Body = $email_template;

        $mail->send();
    }

    public function validarContrasenas($password, $confirmPassword)
    {
        if ($password !== $confirmPassword) {
            $data["error"] = "Las contraseñas no coinciden";
        }

        return $data ?? "";
    }

    public function userRegistration($username, $nombreCompleto, $fechaDeNacimiento, $sexo, $password, $confirmPassword, $ubicacion, $email, $rol, $foto, $fechaDeRegistro, $verify_token) {
        $sql = "INSERT INTO usuarios (usuario, nombre_completo, fecha_nacimiento, sexo, contraseña, ubicacion, email, foto, fecha_registro, verify_token)
            VALUES ('$username', '$nombreCompleto', '$fechaDeNacimiento', '$sexo', '$password', '$ubicacion', '$email', '$foto', '$fechaDeRegistro', '$verify_token')";

        //$this->sendemail_verify($nombreCompleto,$email, $verify_token);

        return $this->database->execute($sql);
    }

    public function consultarTodosLosMailDeUsuarios()
    {
        return $this->database->query("SELECT email FROM usuarios");
    }

    public function consultarTodosLosNombresDeUsuarios()
    {
        return $this->database->query("SELECT usuario FROM usuarios");
    }

    public function estaDuplicado($mail, $username)
    {
        $duplicado = "";

        $todosLosMails = $this->consultarTodosLosMailDeUsuarios();
        $todosLosUsername = $this->consultarTodosLosNombresDeUsuarios();

        foreach ($todosLosMails as $mails) {
            if (isset($mails["email"]) && $mails["email"] == $mail) {
                $duplicado = "email en uso ";
                break;
            }
        }

        foreach ($todosLosUsername as $user) {
            if (isset($user["usuario"]) && $user["usuario"] == $username) {
                $duplicado = $duplicado."usuario en uso!";
                break;
            }
        }

        return $duplicado;
    }

    public function getFechaDeRegistro() {
        $sql = "SELECT CURDATE() AS fechaDeRegistro";
        return $this->database->query($sql);
    }

}