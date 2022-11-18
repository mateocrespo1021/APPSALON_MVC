<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
//Clase porque no se relaciona con la base de datos
class Email
{

    public $email;
    public $nombre;
    public $token;
    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        //Crear el objeto email
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'c88a6a13ae18ac';
        $phpmailer->Password = 'a9ba5fc067f228';

        $phpmailer->setFrom("cuentas@appsalon.com");
        $phpmailer->addAddress("cuentas@appsalon.com", "AppSalon.com");
        $phpmailer->Subject = "Confirma tu cuenta";
        //Set HTML
        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet="UTF-8";
        
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salon , solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token  . "'>Confirmar</a></p>";
        $contenido.="<p>Si tu no solicitaste esta cuenta, puedes ingorar el mensaje</p>";
        $contenido .= "</html>";

        $phpmailer->Body=$contenido;

        //Enviar el email
        $phpmailer->send();
    }

    public function enviarInstrucciones(){
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'c88a6a13ae18ac';
        $phpmailer->Password = 'a9ba5fc067f228';

        $phpmailer->setFrom("cuentas@appsalon.com");
        $phpmailer->addAddress("cuentas@appsalon.com", "AppSalon.com");
        $phpmailer->Subject = "Reestablece tu password";
        //Set HTML
        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet="UTF-8";
        
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado reestablecer tu password , sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/recuperar?token=" . $this->token  . "'>Reestablecer Password</a></p>";
        $contenido.="<p>Si tu no solicitaste este cambio, puedes ingorar el mensaje</p>";
        $contenido .= "</html>";

        $phpmailer->Body=$contenido;

        //Enviar el email
        $phpmailer->send();
    }
}
