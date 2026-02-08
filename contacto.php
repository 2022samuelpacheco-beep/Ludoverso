<?php 
include("template/cabecera.php");
?>

<body class="container mt-5">
    <h2 class="mb-4">Confirmar Compra</h2>
    <form action="correo_enviar.php" method="POST">
        <div class="mb-3">
            <label for="">Correo Electronico principal</label>
            <input type="email" name="destinatario" class="form-control"  placeholder="correo1Qejemplo.com" required>
        </div>
        <div class="mb-3">
            <label for="">correo secundario(copia)</label>
            <input type="text" name="copias" class="form-control" placeholder="correo1Qejemplo.com">

        </div>

        <div>
            <label for="">asunto</label>
            <input type="text" name="asunto" class="form-control" required>

        </div>

        <div>
            <label for="">mensaje</label>
            <textarea name="mensaje" rows="5" class="form-control" required></textarea>

        </div>
         

        <br> <br>
        <button type="submit" class="btn btn-primary">enviar correo</button>
        <br> <br>
        <div>
                <h6>confirme y verifique la factura en su correo</h5>

         </div>   
    </form>

</body>


<?php include("template/pie.php"); ?>