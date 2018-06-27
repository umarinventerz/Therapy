<?php

require_once '../../../conex.php';


$conexion = conectar();
$Pat_id = $_GET['patient_id'];
$Company = $_GET['company'];
$discipline = $_GET['discipline'];
$cpt = $_GET['cpt'];
$authorization = $_GET['authorization'];


$delete = "DELETE FROM signed_doctor WHERE Patient_ID = '".$Pat_id."' AND Table_name = '".$Company."' AND Discipline = '".$discipline."' AND CPT = '".$cpt."' AND Authorization = '".$authorization."'";
ejecutar($delete,$conexion);

echo 'SIGNED DOCTOR DELETED';
?>
