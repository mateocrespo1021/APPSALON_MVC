<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar()
    {
       
        //Almacena la cita y devuelde el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        //Id de la cita
        $id = $resultado["id"];

        //Almacena la cita y el servicio
        $idServicios = explode(",", $_POST["servicios"]);
       

        foreach ($idServicios as $idServicio) {
            $args = [
                "citaId" => $id,
                "servicioId" => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        
        //Almacena la cita y el servicio
        $resultado = [
            "resultado" => $resultado
        ];

        echo json_encode($resultado);
    }


    public static function eliminar(){
        if ($_SERVER["REQUEST_METHOD"]==="POST") {
            $id=$_POST["id"];
            $cita=Cita::find($id);
            $cita->eliminar();
            header("Location:" . $_SERVER["HTTP_REFERER"]);
        }
    }
}
