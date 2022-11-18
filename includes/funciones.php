<?php

function debuguear($variable): string
{
  echo "<pre>";
  var_dump($variable);
  echo "</pre>";
  exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
  $s = htmlspecialchars($html);
  return $s;
}

//Funcion que revisa que el usuario esta autenticada
function isAuth(): void
{
  if (!isset($_SESSION["login"])) {
    header("Location: /");
  }
}


//Funcion que revisa si es el ultima cita

function esUltimo(string $actual, string $proximo): bool
{
  if ($actual !== $proximo) {
    return true;
  }

  return false;
}


function isAdmin():void{
  if (!isset($_SESSION["admin"])) {
    header("Location: /");
  }
}