<?php
session_start();
include("config.php");

$accion = isset($_POST["accion"]) ? $_POST["accion"] : "";
$pdf_generado = false;

if ($accion == "1") {
    $titulo = "Reporte de Grados";
    $buscar = $_POST["txtnombre"];

    // Guardar parámetros en sesión para usarlos en reporte.php
    $_SESSION["filepath"] = "Rep_Menu.jrxml";
    $_SESSION["parametros"] = array("title" => $titulo, "nombre" => "'".$buscar."'");

    // Indicamos que el PDF está listo para mostrarse
    $pdf_generado = true;
    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Listados de Grados</title>
    <script>
        function verificar() {
            var g = document.getElementById("txtnombre").value;
            if(g.trim() === "") {
                alert("Por favor ingrese un dato.");
                document.getElementById("txtnombre").focus();
                return false;
            }
            document.getElementById("accion").value = "1";
            return true;
        }
    </script>
</head>
<body>

<div class="content-area">
    <div class="card">
        <div class="card-header">
            <h2>Reporte Listado de Grados</h2>
        </div>
        <div class="card-body">
            <form action="index.php" method="POST" onsubmit="return verificar()">
                <input type="hidden" name="accion" id="accion">
                <label for="txtnombre">Nombre:</label>
                <input type="text" id="txtnombre" name="txtnombre" value="%">
                <input type="submit" value="Generar Reporte">
            </form>

            <!-- Aquí se mostrará el PDF -->
            <?php if ($pdf_generado): ?>
                <hr>
                <h3>Vista Previa del Reporte</h3>
                <iframe 
                    src="reporte.php" 
                    style="width:100%; height:600px;" 
                    frameborder="0">
                    Tu navegador no soporta iframes.
                </iframe>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>