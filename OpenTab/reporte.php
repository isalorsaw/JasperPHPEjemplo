<?php
session_start();
include_once('phpjasperxml_0.9d/class/tcpdf/tcpdf.php');
include_once("phpjasperxml_0.9d/class/PHPJasperXML.inc.php");
ini_set('display_errors', 0);

	$server=$_SESSION["servidor"];
	$user=$_SESSION["usuario"];
	$bd=$_SESSION["bd"];
	$clave=$_SESSION["clave"];
	
	//print_r($_SESSION);
	
	$xml = simplexml_load_file($_SESSION["filepath"]);
    $report = new PHPJasperXML();
    $report->arrayParameter = $_SESSION["parametros"];//array("idcurso" => $lendingid, "seccion"=> "'".$seccion."'");
    $report->debugsql = false;
    $report->xml_dismantle($xml);
    $report->transferDBtoArray($server, $user, $clave, $bd);
    $report->outpage("I");
?>