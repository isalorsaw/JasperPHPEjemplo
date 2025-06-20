<?php
session_start();
include_once('phpjasperxml_0.9d/class/tcpdf/tcpdf.php');
include_once("phpjasperxml_0.9d/class/PHPJasperXML.inc.php");
ini_set('display_errors', 0);

// Datos de conexión
$server = $_SESSION["servidor"];
$user = $_SESSION["usuario"];
$bd = $_SESSION["bd"];
$clave = $_SESSION["clave"];

// Cargar el archivo JRXML
$xml = simplexml_load_file($_SESSION["filepath"]);
$report = new PHPJasperXML();
$report->arrayParameter = $_SESSION["parametros"];
$report->debugsql = false;
$report->xml_dismantle($xml);
$report->transferDBtoArray($server, $user, $clave, $bd);

// Mostrar el PDF directamente en el navegador
$report->outpage("I"); // I = Muestra en navegador
?>