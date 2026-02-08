<!--Incluir Cabecera Administrador
--------------------------------------------------------------------------------->
<?php include("../template/cabeceraAdmin.php"); ?>

<?php
    session_start();
    session_destroy();
    header('Location:../indexAdmin.php');
?>

<!--Incluir Pie Administrador
--------------------------------------------------------------------------------->
<?php include("../template/pieAdmin.php"); ?>