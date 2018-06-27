<?php

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

$user_id = $_SESSION['user_id'];

$conexion = conectar();
           
$date = date('Y-m-d');

$id_treatments_charges = $_POST['id_treatments_charge'];
$reference_number = $_POST['reference_number'];

$insert =" INSERT INTO tbl_treatments_charges_disassociate (id_treatments_charge,user_id,date,reference_number) VALUES ('".$id_treatments_charges."','".$user_id."','".$date."',".$reference_number.");";
$resultado = ejecutar($insert,$conexion); 

$delete =" DELETE FROM tbl_treatments_associated WHERE id_treatments_charges = ".$id_treatments_charges." AND numero_referencia = ".$reference_number;
$resultado = ejecutar($delete,$conexion); 

$json_resultado['resultado'] = 'Desasociado';
                                  
echo json_encode($json_resultado);                                  

?>