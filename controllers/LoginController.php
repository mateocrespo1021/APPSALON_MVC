<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;
//Los controllers llaman al modelo y se encargan de la logica
//de la vista al cargar

class LoginController
{
    //Inicia sesión
    public static function login(Router $router)
    {
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)) {
                //Comprobar que exista el usuario
                $usuario = Usuario::where("email", $auth->getEmail());
                if ($usuario) {
                    //Verifcar el password
                    if ($usuario->comprobarUsuario($auth->getPassword())) {
                        //Autenticar el usuario
                        session_start();
                        //Agrego datos a la superglobal sesión
                        $_SESSION["id"] = $usuario->getId();
                        $_SESSION["nombre"] = $usuario->getNombre() . " " . $usuario->getApellido();
                        $_SESSION["email"] = $usuario->getEmail();
                        $_SESSION["login"] = true;

                        //Redireccionamiento       
                        if ($usuario->getAdmin() === "1") {
                            $_SESSION["admin"] = $usuario->getAdmin() ?? null;
                            header("Location: /admin");
                        } else {
                            header("Location: /cita");
                        }
                    }
                } else {
                    Usuario::setAlerta("error", "Usuario no encontrado");
                }
            }
        }
        //Obtengo otra vez las alertas para mostrar
        $alertas = Usuario::getAlertas();
        $router->render("auth/login", [
            "alertas" => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header("Location: /");
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where("email", $auth->getEmail());
                //Si el usuario existe y está confirmado
                if ($usuario && $usuario->getConfirmado() === "1") {
                    //Generar token de un solo uso
                    $usuario->crearToken();
                    //Actualizamos en la bd
                    $usuario->guardar();
                    //TODO : Enviar el email
                    $email = new Email($usuario->getEmail(), $usuario->getNombre(), $usuario->getToken());
                    $email->enviarInstrucciones();
                    //Alerta de exito
                    Usuario::setAlerta("exito", "Revisa tu email");
                } else {
                    Usuario::setAlerta("error", "El Usuario no existe o no está confirmado");
                }
            }
        }
        //Obtengo las alertas actualizadas
        $alertas = Usuario::getAlertas();
        $router->render("auth/olvide-password", ["alertas" => $alertas]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;
        $token = s($_GET["token"]);

        //Buscar el usuario por su token,el token nos ayuda
        //validar la identidad del usuario
        $usuario = Usuario::where("token", $token);
        if (empty($usuario)) {
            Usuario::setAlerta("error", "Token No Válido");
            $error = true;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            //Validación es correcta
            if (empty($alertas)) {
                $usuario->setPassword(null);
                $usuario->setPassword($password->getPassword());
                $usuario->hashPassword();
                //Actualizamos el usuario
                $resultado = $usuario->guardar();
                if ($resultado) {
                    header("Location: /");
                }
            }
        }

        //Obtengo las alertas actualizadas
        $alertas = Usuario::getAlertas();
        $router->render("auth/recuperar-password", ["alertas" => $alertas, "error" => $error]);
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario();
        $alertas = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            //Validar campos
            if (empty($alertas)) {
                //Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    //Si esta , entonces llamo a las alertas
                    $alertas = Usuario::getAlertas();
                } else {
                    //Crear un nuevo usuario
                    //Hasheo el password
                    $usuario->hashPassword();
                    //Generar um token único
                    $usuario->crearToken();
                    //Enviar el Email para confirmar identidad
                    $email = new Email($usuario->getNombre(), $usuario->getEmail(), $usuario->getToken());
                    $email->enviarConfirmacion();
                    //Crear el usuario

                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header("Location: /mensaje");
                    }
                }
            }
        }
        $router->render("auth/crear-cuenta", ["usuario" => $usuario, "alertas" => $alertas]);
    }

    public static function mensaje(Router $router)
    {
        $router->render("auth/mensaje");
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET["token"]);
        $usuario = Usuario::where("token", $token);
        if (empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta("error", "Token No Válido");
        } else {
            //Modificar a usuario confirmado
            $usuario->setConfirmado("1");
            $usuario->setToken(null);
            $usuario->guardar();
            Usuario::setAlerta("exita", "Cuenta Comprobada Correctamente");
        }
        //Obtengo otra vez las alertas para mostrar
        $alertas = Usuario::getAlertas();
        //Renderiza la vista
        $router->render("auth/confirmar-cuenta", ["alertas" => $alertas]);
    }
}
