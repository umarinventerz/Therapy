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

$reporte = '';
if($_POST['amendment'] == 1){
    $sql  = "SELECT * FROM tbl_amendment ea"        
        . " WHERE ea.id_evaluations = ".$_POST['id_eval'].";"; 
    $resultado = ejecutar($sql,$conexion);       
    $i = 1;      
    while($datos = mysqli_fetch_assoc($resultado)) {
        $reporte .= $datos['ckeditor'];
    }
}else{
    $sql  = "SELECT * FROM tbl_evaluations e"        
        . " WHERE e.id = ".$_POST['id_eval'].";"; 
    $resultado = ejecutar($sql,$conexion);       
    $i = 1;      
    while($datos = mysqli_fetch_assoc($resultado)) {
        $reporte .= $datos['ckeditor'];
    }
}


$json_resultado['ckeditor'] = $reporte;

echo json_encode($json_resultado); 

?>