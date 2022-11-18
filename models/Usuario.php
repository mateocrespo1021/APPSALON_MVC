<?php

namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla="usuarios";
    protected static $columnasDB=["id","nombre","apellido","email","password","telefono","admin","confirmado","token"];

    //Los atributos deben ser igualos a los de la
    //tabla de base de datos

    protected $id;
    protected $nombre;
    protected $apellido;
    protected $email;
    protected $password;
    protected $telefono;
    protected $admin;
    protected $confirmado;
    protected $token;

    public function __construct($args=[])
    {
     $this->id=$args["id"]??null;
     $this->nombre=$args["nombre"]??"";
     $this->email=$args["email"]??"";
     $this->password=$args["password"]??"";
     $this->telefono=$args["telefono"]??"";
     $this->admin=$args["admin"]??"0";
     $this->confirmado=$args["confirmado"]??"0";
     $this->token=$args["token"]??"";   
     }
    
      /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of telefono
     */ 
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Get the value of admin
     */ 
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Get the value of confirmado
     */ 
    public function getConfirmado()
    {
        return $this->confirmado;
    }

    /**
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the value of apellido
     */ 
    public function getApellido()
    {
        return $this->apellido;
    }



     /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Set the value of confirmado
     *
     * @return  self
     */ 
    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;

        return $this;
    }

      /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    //Mensaje de validacion para la creacion
    //de una cuenta
    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas["error"][]="El nombre es obligatorio";
        }

        if (!$this->apellido) {
            self::$alertas["error"][]="El apellido es obligatorio";
        }

        //Valido el correo con una expresion regular , preg_match me devuelve 0 si no cumple , y 1 si cumple
        $matches=null;
        if (0 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $this->email , $matches) ) {
            self::$alertas["error"][]="El email no es valido";
        }

        if (!$this->password) {
            self::$alertas["error"][]="El password es obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas["error"][]="El password debe contener al menos 6 caracteres";
        }

        
        return self::$alertas;
    }
    
    //Revisa si el usuario ya existe
    public function existeUsuario(){
      $query="SELECT * FROM " . self::$tabla . " WHERE email= '" . $this->email ."' LIMIT 1" ;
      $resultado=self::$db->query($query);
      if ($resultado->num_rows) {
        self::$alertas["error"][]="El usuario ya está registrado";
      }
      return $resultado;
    }
    
    //Hashea el password , es como cifrar el password por seguridad
    public function hashPassword(){
        $this->password=password_hash($this->password,PASSWORD_BCRYPT);
    }

    public function crearToken(){
        //Genera un id unico para validar el usuario
        $this->token=uniqid();
    }

    public function validarLogin()
    {
      if (!$this->email) {
        self::$alertas["error"][]="El Email Es Obligatorio";
      }

      if (!$this->password) {
        self::$alertas["error"][]="El Password Es Obligatorio";
      }
      return self::$alertas;
    }

    public function comprobarUsuario($password){
        $resultado=password_verify($password,$this->password);
        if (!$resultado || !$this->confirmado) {
            self::$alertas["error"][]="Password Incorrecto o tu cuenta no ha sido confirmada";
        }else{
            return true;
        }
        return false;
    }

    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas["error"][]="El Email Es Obligatorio";
          }
        return self::$alertas;    
    }


    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas["error"][]="El password es obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas["error"][]="El password debe contener al menos 6 caracteres";
        }

        return self::$alertas;    
    }

  

   
}