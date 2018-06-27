<?php
session_start();
require_once("../../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

$id=$_GET['id'];


$delete = "DELETE FROM tbl_location_appoiments WHERE id=".$id;
$resultado = ejecutar($delete,$conexion);            
            
if($resultado){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}    
echo json_encode($array);
?>


