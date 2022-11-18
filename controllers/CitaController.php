<?php

namespace Controllers;

use MVC\Router;

class CitaController
{
    public static function index(Router $router)
    {
        //Protege la ruta si no está autenticado
        isAuth();
        $router->render("cita/index", ["nombre" => $_SESSION["nombre"], "id" => $_SESSION["id"]]);
    }
}
