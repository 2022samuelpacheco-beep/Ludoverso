<!--Incluir Cabecera Administrador
--------------------------------------------------------------------------------->
<?php include("template/cabeceraAdmin.php"); ?>

<!--Contenedor de elementos
--------------------------------------------------------------------------------->
                <div class="col-md-12">                    
                    <div class="jumbotron">
                        <h1 class="display-3">Bienvenido <?php echo $nombreUsuario;?> </h1>
                        <p class="lead">Ahora puedes administrar la librería</p>
                        <hr class="my-2">
                        <p>More info</p>
                        <p class="lead">
                            <a class="btn btn-primary btn-lg" href="seccion/productosAdmin.php" role="button">Administrar los Libros</a>
                        </p>
                    </div>
                </div>
    

<!--Incluir Pie Administrador
--------------------------------------------------------------------------------->
<?php include("template/pieAdmin.php"); ?>