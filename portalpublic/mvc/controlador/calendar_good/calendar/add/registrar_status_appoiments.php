<?php
session_start();
require_once("../../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

$name=$_POST['status'];

$insert = "INSERT into tbl_status_appoiments (name)
            values ('".$name."');";
            $resultado = ejecutar($insert,$conexion);
            
if($insert){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}    
echo json_encode($array);
?>

