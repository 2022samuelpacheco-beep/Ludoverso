<?php
$host="localhost";
$bd="ludoverso";
$usuario="root";
$contrasenia="";


try {
    $conexion = new PDO("mysql:host=$host;dbname=$bd", $usuario, $contrasenia);    
    // if ($conexion) {echo "Conectado al sistema";}

} catch (Exception $ex) {
    echo "Error de conexión: " . $ex->getMessage();
}
?>