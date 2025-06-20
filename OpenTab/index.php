<?php
session_start();
	include("config.php");
    $accion=(isset($_POST["accion"])?$_POST["accion"]:"");
    //echo "Accion ".$accion;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../images/mar.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title>Reporte Listados de Grados</title>
    <link rel="stylesheet" href="css/template_css.css">
    <!--<script src="../js/jquery-1.10.2.min.js"></script>-->
    <script>
    		function verificar()
    		{
    			var g=document.getElementById("txtnombre").value;
    			if(g=="")
    			{
    				alert("Ingresar Dato")
					document.getElementById("txtnombre").focus();
    			}
    			else 
    			{
    				document.getElementById("accion").value="1";
    				return true;
    			}
    			return false;
    		}
    </script>
</head>
<body>
   <?php 
    if($accion=="1")
	{
		$titulo="Reporte de Grados";
		$buscar=$_POST["txtnombre"];
		$_SESSION["filepath"]="Rep_Menu.jrxml";
		$_SESSION["parametros"]=array("title" => "'".$titulo."'", "nombre"=> "'".$buscar."'");
		echo "<script language=\"javascript\" >window.open(\"reporte.php\",'_blank'); </script>";
	}//Si voy a generar
    
    ?>
        <!-- Área de contenido -->
        <div class="content-area">
            <div class="card">
                <div class="card-header">
                    <h2>Reporte Listado de Grados</h2>
                </div>
                <div class="card-body">
                <form action="index.php" method="POST">
					<input type="hidden" name="accion" id="accion">
					<label for="txtnombre">Nombre:</label>
					<input type="text" id="txtnombre" name="txtnombre" value="%">
					<input type="submit" value="Enviar" onClick="return verificar()">
				</form>
                    
			</div>

                </div>
        </div>
</body>
</html>