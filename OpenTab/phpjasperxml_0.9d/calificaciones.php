<?php
	session_start();
	if($_SESSION["user"]=="")
	{
		echo "<script language=\"javascript\" >window.location = \"index.php\"; </script>";
	}
	include("../include/config.php");
	mysqli_set_charset($conexion,"utf8");

	$cod_alumno=(isset($_GET['codigo'])?base64_decode($_GET['codigo']):'');
	$sem=($_GET['sem']==1?"SEM1":"");
	
	//RNE & nombre alumno
	$sqlrne = "SELECT *,curso.idcurso,curso.nombre2 AS nomcurso FROM alumno
	INNER JOIN curso ON alumno.idcurso = curso.idcurso WHERE alumno.rne_alumno = ".$cod_alumno;

	$queryrne = mysqli_query($conexion,$sqlrne) or die($queryrne."<br/><br/>".mysql_error());
	$filarne = mysqli_fetch_array($queryrne);
	$nomalumno = $filarne['rne_alumno']."-".$filarne['pnom_alumno']." ".$filarne['snom_alumno']." ".$filarne['psname_alumno']." ".$filarne['ssname_alumno'];

	$query=mysqli_query($conexion,"select max(anio)as anio from tbl_aniolectivo");
	$fila=mysqli_fetch_array($query);
	$anio=$fila["anio"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8"/>
	<title>Vista de Boleta de Calificaciones</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/jquery.mobile-1.4.2.min.css">
    <script src="../js/jquery-1.10.2.min.js"></script>
    <script src="../js/jquery.mobile-1.4.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables2.css"> 
    <script type="text/javascript" charset="utf8" src="DataTables/datatables.js"></script>
    <link href="../css/estilo2.css" rel="stylesheet">
	<style type="text/css">
		@media screen and (max-width: 600px) {
		table {width: 95%; display: block;}
		thead {display: none;}
		tr:nth-of-type(2n) {background-color: inherit;}
		tr td:first-child {background: #A5E0E8; font-weight:bold;font-size:1.3em;}
		tbody td {display: block; text-align:center;}
		tbody td:before { 
		    content: attr(data-th); 
		    display: block;
		    text-align:center; 
		  }
		} 
	</style>
	<script type="text/javascript">
		function cerrarsesion()
        {
             window.open('close.php','_self',false);
        }
        function validar()
        {
        	//alert("Aqui");
        	if(document.getElementById("parcial").value=="")return false;
            else
            	{
            		document.getElementById("formulario").submit();
            		return true;
            	}
        }
         function llamar(link)
        {
          //alert(link);
          window.location=link;
        }
	</script>
</head>
<body oncontextmenu="return false">
	<div data-role="page" class="jqm-demos ui-responsive-panel" id="inicio">
<form name="formulario" id="formulario" action="calificaciones.php" method="post">
		<div style="min-height: 45px; max-height: 55px; background: #A5E0E8; border-bottom-color: #2A4151; border-bottom-width: 2px;" vertical-align="center" align="center" data-role="header" data-position="fixed" data-fullscreen="false" data-theme="a" width="60%">
			<img id="inmarelogoheader" src="../images/mar.png" style="width: 5vh;"/>
        	<a style="margin-left: 2%; font-color: #FFFFFF; background-color: #2A4151;" href="#left-panel" data-theme="b" data-icon="bullets" data-position-to="window" data-role="button" class="ui-btn-left-b  ui-btn-inline ui-shadow ui-corner-all ui-icon-grid">OPCIONES</a>
        	<!-- botón cerrar sesión -->
        	<a style="margin-right: 2%; font-color: #FFFFFF; background-color: #2A4151;" href="javascript:cerrarsesion();" data-theme="b" data-icon="home" data-position-to="window" data-role="button" class="ui-btn-right ui-btn-inline ui-shadow ui-corner-all ui-icon-home ui-btn-icon-left">SALIR</a>
        </div> <!-- close header -->

        <?php
	    	include("leftpanel.php");
	    	//*****************************************************************************************************
	    	include("../include/config.php");
			mysqli_set_charset($conexion,"utf8");
	    	$sql="select now() as hoy";
			$result=mysqli_query($conexion,$sql);
			while($row=mysqli_fetch_assoc($result))
			{
				$fechahoy=$row['hoy'];
			}
			
	    	$sql="INSERT INTO tbl_bitacora(usuario_codigo,bit_nombre_equipo,bit_ip_equipo,bit_descripcion,bit_fecha) VALUES('".$_SESSION["user"]."','".gethostname()."','".$_SESSION["ip_publica"]."','Entro a vista de calificaciones de ".$nomalumno." del ".$_GET['title']."','".$fechahoy."')";
			mysqli_query($conexion,$sql) or die($queryrne."<br/><br/>".mysql_error());
			//*****************************************************************************************************
	    ?>

	    <div id="contenido" data-role="content">
	    	<?php 
$_SESSION["modulo"]="Vista Reporte de Calificaciones<br>Alumno: ".$nomalumno."<br>Grado: ".$filarne["nomcurso"]."-".$filarne["seccion_alumno"]." Año: ".$filarne["anio_alumno"];
include("menu.php");
	    	?>
	    		<div align="left">
  					<p><a onclick='javascript:llamar("<?php echo $_SESSION["bie"];?>");'>Vista Principal</a></p>
				</div>
			<?php
				$sqlc = "select foto_blob from tbl_foto f WHERE f.identidad = '".$cod_alumno."'";
				$queryc = mysqli_query($conexion,$sqlc) or die($queryc."<br/><br/>".mysql_error());
				$filac = mysqli_fetch_array($queryc);
				echo "<div align=center>
					<img src='data:image/jpeg;base64,".base64_encode($filac['foto_blob'])."' height='200' width='300'/></div>";
			?>
				<!-- tabla clases -->
				<ul style="background: #FFFFFF; border: solid; border-width: 2px; text-align: left; margin-left: 20%; margin-right: 20%; border-color: #2A4151;" data-role="listview" data-theme="b" data-inset="true" data-shadow="false">

					<li style="background-color: #2A4151; color: #FFFFFF !important; border: none; text-shadow: none;" data-role="list-divider" data-theme="b" data-inset="true" align="center">Notas e Inasistencias del <?php echo $_GET['title'];?></li>

					<table align="center" style="text-align: left; margin-left: 2%; margin-right: 2%; margin-top: 2%; margin-bottom: 2%; background-size: 20vw 30vh;" align="center" id="example" class="ui-responsive table-stroke" cellspacing="2" width="90%" data-theme="b" data-inset="true" data-mode="reflow">
						<thead>
							<tr>
								<th style="background-color: #2A4151; color: #FFFFFF !important; border: none; text-shadow: none;" width=60%>Asignatura</th>
								<th style="background-color: #2A4151; color: #FFFFFF !important; border: none; text-shadow: none;" width=20%>Inasistencia</th>
								<th style="background-color: #2A4151; color: #FFFFFF !important; border: none; text-shadow: none;" width=20%>Nota</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$sql="call sp_notas_alumno(".$anio.",'".$cod_alumno."','".$sem."')";
							//$conexion1=mysqli_connect($_SESSION["vservidor"],$_SESSION["vuser"],$_SESSION["vclave"],$_SESSION["vbd"]);
							echo $sql;
							mysqli_set_charset($conexion,"utf8");
							$query = mysqli_query($conexion,$sql) or die($query."<br/><br/>".mysql_error());
							
							$suma=0;
							$cont=0;
							while($fila = mysqli_fetch_array($query) )
							{
								if($fila[$_GET["n1"]]!=0)
								{
									$nota=($fila[$_GET["n1"]]==0?0:$fila[$_GET["n1"]]);
									$suma+=$nota;
									$cont=$cont+1;
									$conti=$fila[$_GET["i"]];
						?>
					        <tr>
					          <td style="text-shadow: none; color: #2A4151;"> <?php echo $fila['nomasig'] ?> </td>
					          <td style="text-align: center; text-shadow: none; color: #2A4151;"> <?php echo $fila[$_GET["i"]] ?> </td>
					          <td style="text-align: center; text-shadow: none; color: #2A4151;"> <?php echo $fila[$_GET["n1"]] ?> </td>
					        </tr>

					        <?php
					        } 
							} 
							?>
					        	<tr>
					        		<td style="text-align: left; background: #A5E0E8; font-weight: bold; color: #2A4151;">TOTAL Y PROMEDIO:
					        		</td>
					        		<td style="text-align: center; background: #A5E0E8; font-weight: bold; color: #2A4151;">
					        			<?php echo $conti;?>
					        		</td>
					        		<td style="text-align: center; background: #A5E0E8; font-weight: bold; color: #2A4151;">
					        			<?php echo ($suma==0?"":round($suma/$cont,0)."%")?>
					        		</td>
					        	</tr>
							<!--</th>-->

					        <?php
					        ?>
				  		</tbody>

					</table>
				</ul>
				<?php
if(isset($_GET['per']))
{
	$sqlcol="SELECT descrip,p1,
personalidad.pos_neg
FROM perso
INNER JOIN personalidad
ON personalidad.idpersonalidad = perso.idpersonalidad
WHERE 
anio IN(SELECT MAX(anio) FROM tbl_aniolectivo)
and ".$_GET["per"]."=1
AND perso.rne_alumno = '".$cod_alumno."' ORDER BY personalidad.pos_neg desc";
//echo "SQL ".$sqlcol."<br>";
//include("../include/config.php");
//$conexion1=mysqli_connect($_SESSION["vservidor"],$_SESSION["vuser"],$_SESSION["vclave"],$_SESSION["vbd"]);
//mysqli_set_charset($conexion1,"utf8");
include("../include/config.php");
mysqli_set_charset($conexion,"utf8");
$resultcol = mysqli_query($conexion,$sqlcol) or die($resultcol."<br/><br/>".mysqli_error());
$countcol=mysqli_num_rows($resultcol);

$sqlesc="SELECT 
descrip,
".$_GET["per"].",
case ".$_GET["per"]." when 'O' then 'Outstanding/Excelente'
when 'VG' then 'Very Good/Muy Bueno'
when 'G' then 'Good/Bueno'
when 'N' then 'Needs to Improvement/Necesita Mejorar'
end as estado
 FROM perso2 
INNER JOIN personalidad2 ON personalidad2.idpersonalidad2 = perso2.idpersonalidad2  
inner join tbl_aniolectivo lec on lec.fecha_estado='ACTIVO'
WHERE personalidad2.idpersonalidad2<='5' 
AND perso2.rne_alumno = '".$cod_alumno."' and perso2.anio=lec.anio 
ORDER BY personalidad2.descrip";
//echo "SQL ".$sqlesc."<br>";
//$conexion=mysqli_connect($_SESSION["servidor"],$_SESSION["usuario"],$_SESSION["clave"],$_SESSION["bd"]);
//include("../include/config.php");
//$conexion1=mysqli_connect($_SESSION["vservidor"],$_SESSION["vuser"],$_SESSION["vclave"],$_SESSION["vbd"]);
//mysqli_set_charset($conexion1,"utf8");
$resultesc = mysqli_query($conexion,$sqlesc) or die($resultesc."<br/><br/>".mysqli_error());
$countesc=mysqli_num_rows($resultesc);
			?>
<!--############################################################## tabla evaluación de personalidad #########################-->
				<ul style="background: #FFFFFF; border: solid; border-width: 2px; text-align: left; margin-left: 20%; margin-right: 20%; border-color: #2A4151;" data-role="listview" data-filter-theme="a" data-inset="true" data-shadow="false">

					<li style="background: #2A4151; color: #FFFFFF; border: none; text-shadow: none;" data-role="list-divider" data-theme="b" data-inset="true">Evaluación de personalidad</li>

						<?php
							if($countesc>0)
							{
						?>
								<table background="../images/marcaagua.png" align="center" style="text-align: left; margin-left: 2%; margin-right: 2%; margin-top: 2%; margin-bottom: 2%; border-color: #2A4151; background-size: 20vw 30vh;" id="example" class="ui-responsive table-stroke" cellspacing="2" width="70%" data-theme="b" data-inset="true" data-mode="reflow">
								<thead>
									<tr>
										<th style="background: #2A4151; color: #FFFFFF; text-shadow: none;" data-priority="1">Descripción</th>
										<th style="text-align: center; background: #2A4151; color: #FFFFFF; text-shadow: none;" data-priority="2"><?php echo $_GET['title'];?></th>
										<th style="text-align: center; background: #2A4151; color: #FFFFFF; text-shadow: none;" data-priority="2"></th>
									</tr>
								</thead>
								<tbody>
						<?php
								while($fila = mysqli_fetch_array($resultesc) )
								{
									echo "
										<tr>
											<td style='text-shadow: none; color: #2A4151;'> ".$fila['descrip']." </td>
											<td style='text-align: center; text-shadow: none; color: #2A4151;'>".$fila[$_GET["per"]]."</td>
											<td style='text-align: center; text-shadow: none; color: #2A4151;'>".$fila['estado']."</td>
										</tr>";
								}
?>
								</tbody>
								</table>

								<!-- Método de evaluación -->
								<a><img style='width: 40%; margin-top: 1%; margin-right: 30%; margin-left: 30%;'' src='../images/ev.png'></a>
				                	<div class='imgcaption'></div>
				                		<style type='text/css'>
				                    	.imgcaption
				                    	{
				                        	width: 30%;
				                        	text-align: center;
				                        	margin-top: 1%;
				                    	}
				                	</style>
<?php						}?>

								<table background="../images/marcaagua.png" align="center" style="text-align: left; margin-left: 2%; margin-right: 2%; margin-top: 2%; margin-bottom: 2%; border-color: #2A4151; background-size: 20vw 30vh;" id="example" class="ui-responsive table-stroke" cellspacing="2" width="70%" data-theme="b" data-inset="true" data-mode="reflow">
								<thead>
									<tr>
										<th style="background: #2A4151; color: #FFFFFF; text-shadow: none;" data-priority="1">Descripción</th>
										<th style="text-align: center; background: #2A4151; color: #FFFFFF; text-shadow: none;" data-priority="2"><?php echo $_GET['title'];?></th>
									</tr>
								</thead>
								<tbody>
						<?php
							$antes=-1;
								while($fila = mysqli_fetch_array($resultcol) )
								{

									if($antes!=$fila["pos_neg"])
									{
										if($fila["pos_neg"]==1)
										{
											echo 
											"
											<tr>
										        <th colspan=2 style='background: #FEF668; text-align: center; text-shadow: none; color: #2A4151;'> 
										        	Queremos felicitar a su hijo por EXCELENTE cumplimiento de los siguientes aspectos:
										        </th>
											</tr>";
										}
										else if($fila["pos_neg"]==0)
										{

											echo"<tr></tr>
								        	<th colspan=2 style='background: #FEF668; text-align: center; text-shadow: none; color: #2A4151;'> 
								        	Su hijo ha estado manifestando las faltas implicadas acontinuación:
								        </th>";
										}
										$antes=$fila["pos_neg"];
									}
									echo "
										<tr>
											<td style='text-shadow: none; color: #2A4151;'> ".$fila['descrip']." </td>
											<td style='text-align: center; text-shadow: none; color: #2A4151;'>".($fila["pos_neg"]==0?"☓":"✓")."</td>
										</tr>";
								}

								if($antes==0)
								{
									echo 
									"
										<tr>
								        <th colspan=2 style='background: #A5E0E8; text-align: center; text-shadow: none; color: #2A4151;'> 
								        	Confiamos que usted le ayudará a mejorar.
								        </th>
								        </tr>
									";
								}
?>
								</tbody>
								</table>
				  		</tbody>

					</table>
				</ul>				        

				</ul>

			<?php
}
			?>
			<style type="text/css">

			  	#contenido
			  	{

				    background: url(../images/sil.png);
				    background-repeat: repeat-x;
				    background-position:bottom;
				    background-attachment:scroll;
				    min-height: 30vh;

				    background-color: #FED035;
				    min-height: 100vh;
			  	}

			  	#no
			  	{

				    background: url(../images/marcaagua.png);
				    /*background-repeat: repeat-x;*/
				    background-position: center;
				    background-attachment:scroll;
				    min-height: 50vh;

				    
			  	}

			  	#titulo1
			  	{
			  		color: #2A4151 !important;
			  		text-align: center;
			  		text-shadow: none;
			  	}

			  	#tit2
		        {
		            background: #FED035 !important;
		            color: #2A4151 !important;
		            width: 50% !important;
		            margin: auto !important;
		            text-align: center !important;
		            text-shadow: none;

		        }

			</style>

	    </div> <!-- close #contenido -->
	    
	</div> <!-- close #inicio -->    	
		</form>
</body>
</html>