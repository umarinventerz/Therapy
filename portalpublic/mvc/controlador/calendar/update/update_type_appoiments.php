<?php
session_start();
require_once("../../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

$id=$_POST['id'];
$name=$_POST['name'];
$color=$_POST['color'];
$location=$_POST['type_location'];

$update = "UPDATE tbl_type_appoiments SET name='".$name."', color='".$color."', location_id='".$location."' WHERE id=".$id;
$resultado = ejecutar($update,$conexion);            
            
if($resultado){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}    
echo json_encode($array);
?>

