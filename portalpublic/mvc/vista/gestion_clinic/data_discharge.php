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

$sql  = "SELECT *,di.created_discharge,di.id_template as id_tem,di.ckeditor as ckeditor_discharge FROM tbl_discharge di"
        . " LEFT JOIN tbl_evaluations e ON e.id = di.id_evaluation "
        . " LEFT JOIN prescription p ON p.id_prescription = e.id_prescription "
        . " LEFT JOIN tbl_documents d ON d.id_table_relation = di.id_discharge "
        . " LEFT JOIN patients pa ON pa.id = e.patient_id "
        . " WHERE d.id_type_document = 143 AND di.id_discharge = ".$_GET['id_discharge'].";"; 
$resultado = ejecutar($sql,$conexion);   
$reporte = [];
$i = 1;      
while($datos = mysqli_fetch_assoc($resultado)) {
    $reporte = $datos;
}
$reporte['start_date'] = date('Y-m-d', strtotime($reporte['start_discharge']));
$reporte['end_date'] = date('Y-m-d', strtotime($reporte['end_discharge']));
$reporte['created_summary'] = date('Y-m-d', strtotime($reporte['created_discharge']));
$reporte['signed_date'] = date('Y-m-d', strtotime($reporte['date_signature']));

$json_resultado['discharge'] = $reporte;

echo json_encode($json_resultado); 

?>