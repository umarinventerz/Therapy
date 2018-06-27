<?php

require_once '../../../conex.php';

$conexion = conectar();
$Pat_id = $_GET['patient_id'];
$discipline = $_GET['discipline'];
$authorization = $_GET['authorization'];

$delete = "DELETE FROM authorizations WHERE Pat_id = '".$Pat_id."' AND Discipline = '".$discipline."' AND `Auth_#` = '".$authorization."'";
ejecutar($delete,$conexion);

echo 'AUTHORIZATIONS DELETED';
?>
