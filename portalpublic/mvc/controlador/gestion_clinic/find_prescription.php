<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}
$conexion = conectar();
$patients_id = $_POST['patients_id'];
$discipline_id = $_POST['discipline_id'];

$sql  = "SELECT * FROM prescription "
        . "WHERE Patient_ID = ".$patients_id." AND Discipline = '".$discipline_id."' AND status IN (1,2) 
        AND (prescription.deleted = 0 OR prescription.deleted IS NULL);"; 
$resultado = ejecutar($sql,$conexion);   
$prescription = [];
while($datos = mysqli_fetch_assoc($resultado)) {
    $prescription = $datos;
}
if(empty($prescription)){
    $exist = 0;
}else{
    $exist = 1;
}
$json_resultado['patients_id'] = $_POST['patients_id'];
$json_resultado['exist'] = $exist;
echo json_encode($json_resultado);