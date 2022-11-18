<h1 class="nombre-pagina">Olvide Password</h1>
<p>Reestablece tu password escribiendo tu email a continuación</p>
<?php
include_once __DIR__ . "/../templates/alertas.php"
?>  
<form action="/olvide" class="formulario" method="POST">
<div class="campo">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Tú Email">
 </div>
 <input type="submit" class="boton" value="Enviar Instrucciones">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear Una</a>
</div>