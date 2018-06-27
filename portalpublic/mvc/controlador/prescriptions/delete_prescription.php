<?php

require_once '../../../conex.php';


$conexion = conectar();
$Pat_id = $_GET['patient_id'];
$Company = $_GET['company'];
$discipline = $_GET['discipline'];
$diagnostic = $_GET['diagnostic'];



$delete = "DELETE FROM prescription WHERE Patient_ID = '".$Pat_id."' AND Table_name = '".$Company."' AND Discipline = '".$discipline."' AND Diagnostic = '".$diagnostic."'";
ejecutar($delete,$conexion);

	
echo 'PRESCRIPTION DELETED';
?>
