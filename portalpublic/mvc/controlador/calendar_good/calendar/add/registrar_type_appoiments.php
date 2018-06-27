<?php
session_start();
require_once("../../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

$type=$_POST['type'];
$color=$_POST['color'];
$location=$_POST['location_type'];

$insert = "INSERT into tbl_type_appoiments (name,color,status,location_id)
            values ('".$type."','".$color."','1','".$location."');";
            $resultado = ejecutar($insert,$conexion);
            
if($insert){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}    
echo json_encode($array);
?>

