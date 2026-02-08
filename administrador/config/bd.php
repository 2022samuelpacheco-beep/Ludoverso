<?php
// Datos de conexión para LOCALHOST (XAMPP)
$host        = "localhost";
$bd          = "ludoverso";   // nombre de tu base de datos local
$usuario     = "root";        // usuario por defecto en XAMPP
$contrasenia = "";            // normalmente vacío en XAMPP

try {
    // Agrego charset utf8mb4 para tildes y caracteres especiales
    $conexion = new PDO(
        "mysql:host=$host;dbname=$bd;charset=utf8mb4",
        $usuario,
        $contrasenia
    );

    // Para que PDO lance excepciones si hay errores
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Puedes descomentar esto si quieres verificar la conexión:
    // echo "Conectado al sistema";

} catch (PDOException $ex) {
    echo "Error de conexión: " . $ex->getMessage();
    exit;
}
?>
