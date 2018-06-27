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

$update = "UPDATE tbl_status_appoiments SET name='".$name."' WHERE id=".$id;
$resultado = ejecutar($update,$conexion);            
            
if($resultado){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}    
echo json_encode($array);
?>

