<?php 

session_start();
require 'administrador/config/bd.php';

$asunto  = htmlspecialchars($_POST['asunto']);
$mensaje = nl2br(htmlspecialchars($_POST['mensaje']));

// RECONSTRUCCIÓN DEL CARRITO
$librosCarrito = [];
$totalGeneral = 0;

if (!empty($_SESSION['carrito'])) {

    // Extraemos los ids de los libros en el carrito
    $ids = array_keys($_SESSION['carrito']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // Consultamos los libros en la base de datos
    $sql = $conexion->prepare(
        "SELECT * FROM libros WHERE id IN ($placeholders)"
    );

    // Asociamos los valores a los placeholders
    foreach ($ids as $i => $id) {
        $sql->bindValue($i + 1, $id, PDO::PARAM_INT);
    }

    // Ejecutamos la consulta
    $sql->execute();
    $libros = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Calculamos la cantidad y el subtotal de cada libro
    foreach ($libros as $libro) {
        $cantidad = $_SESSION['carrito'][$libro['id']];
        $subtotal = $cantidad * $libro['precio'];

        $libro['cantidad'] = $cantidad;
        $libro['subtotal'] = $subtotal;

        // Añadimos los libros al carrito reconstruido
        $librosCarrito[] = $libro;
        $totalGeneral += $subtotal;
    }
}



use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

$correo= new PHPMailer(true);
try {
    //configuracion del servidor SMTP
    $correo -> isSMTP();
    $correo -> Host = 'smtp.gmail.com';
    $correo -> SMTPAuth = true;
    $correo -> Username = '2022samuelpacheco@gmail.com';
    $correo -> Password = 'vfvh atgh xhbo wegk';
    $correo -> SMTPSecure = 'tls';
    $correo -> Port = 587;
    //remitente
    $correo -> setFrom('2022samuelpacheco@gmail.com', 'mi aplicacion');
    //destinatario principal
    $correo -> addAddress($_POST['destinatario']);
    //copias
    if(!empty($_POST['copias'])){
        $copias = explode(',', $_POST['copias']);
        foreach($copias as $copia){
            $correo -> addAddress(trim($copia));
        }
    }

  
   

    //contenido de la venta

      $correo->Subject = 'Confirmación de compra en tu librería';
 $mailContent = '<h1>Gracias por tu compra</h1>';
   //contenido de el mensaje
    $correo -> isHTML(true);
    $correo -> Subject = $_POST['asunto'];
    $correo -> Body = nl2br($_POST['mensaje']); // soporta saltos de linea
    $mailContent  = '<h1>Factura de Compra</h1>';
    $mailContent .= '<p><strong>Asunto del cliente:</strong><br>'.$asunto.'</p>';
    $mailContent .= '<p><strong>Mensaje del cliente:</strong><br>'.$mensaje.'</p>';
    $mailContent .= '<hr>';
    $mailContent .= '<h3>Detalle de la compra</h3>';
    $mailContent .= '<p>El siguiente es el resumen de tu compra:</p>';
    $mailContent .= '<table border="1" cellpadding="5" cellspacing="0">
                        <tr>
                            <th>Libro</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>';

                         // Añadir los libros al correo
    foreach ($librosCarrito as $libro) {
        $mailContent .= '<tr>
                            <td>' . htmlspecialchars($libro['nombre']) . '</td>
                            <td>' . $libro['cantidad'] . '</td>
                            <td>S/ ' . number_format($libro['precio'], 2) . '</td>
                            <td>S/ ' . number_format($libro['subtotal'], 2) . '</td>
                        </tr>';
    }

    $mailContent .= '</table>';
    $mailContent.= '<h3>Total: S/ ' . number_format($totalGeneral, 2) . '</h3>';

    $correo->Body = $mailContent;

     $correo -> send();
     
    // Vaciar carrito después de la compra
unset($_SESSION['carrito']);

// Redirigir a página de éxito
header("Location: compra_exitosa.php");
exit;



} catch (\Throwable $th) {
    echo "error al enviar correo: {$correo -> ErrorInfo}";
}





?>