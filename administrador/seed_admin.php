<?php
include "config/bd.php";

// Usuario 1: ludoverso
$usuario1 = "ludoverso";
$nombre1  = "Ludoverso";
$clave1   = password_hash("123456", PASSWORD_BCRYPT);

// Usuario 2: GinoAdmin
$usuario2 = "GinoAdmin";
$nombre2  = "GinoAdmin";
$clave2   = password_hash("123456", PASSWORD_BCRYPT);

$sql = "INSERT INTO usuarios_admin (usuario, nombre, clave) VALUES (:usuario, :nombre, :clave)";

$stmt = $conexion->prepare($sql);

// ludoverso
$stmt->execute([
    ":usuario" => $usuario1,
    ":nombre"  => $nombre1,
    ":clave"   => $clave1
]);

// GinoAdmin
$stmt->execute([
    ":usuario" => $usuario2,
    ":nombre"  => $nombre2,
    ":clave"   => $clave2
]);

echo "Usuarios creados correctamente";
