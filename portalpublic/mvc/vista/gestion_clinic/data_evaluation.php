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

$sql  = "SELECT *,e.id as id_evaluation,e.date_signed as date_signed_e,e.status_id as status_eval FROM tbl_evaluations e"
        . " LEFT JOIN prescription p ON p.id_prescription = e.id_prescription "
        . " LEFT JOIN tbl_documents d ON d.id_table_relation = e.id "
        . " LEFT JOIN patients pa ON pa.id = e.patient_id "
        . " WHERE e.id = ".$_GET['id_document'].";"; 
$resultado = ejecutar($sql,$conexion);   
$reporte = [];
$i = 1;      
while($datos = mysqli_fetch_assoc($resultado)) {
    $reporte = $datos;
}
$reporte['from'] = date('Y-m-d', strtotime($reporte['from']));
$reporte['to'] = date('Y-m-d', strtotime($reporte['to']));
$reporte['created'] = date('Y-m-d', strtotime($reporte['created']));
$reporte['date_signed_e'] = date('Y-m-d', strtotime($reporte['date_signed_e']));

$json_resultado['evaluation'] = $reporte;

echo json_encode($json_resultado); 

?>