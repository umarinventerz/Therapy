<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();


$delete="DELETE FROM tbl_note_cpt_relation WHERE id_note_cpt_relation=".$_POST['id'];

$resultado = ejecutar($delete,$conexion);

if($resultado){
        $id_note=$_POST['id_note'];
        $mensaje="<b>Information was deleted correctly</b>";
        $success=true;
}else{
    $mensaje="<b>An error has occurred, please try again</b>";
    $success=false;
    $id_note='';
}
$arreglo=array('mensaje'=>$mensaje,'success'=>$success, 'id_note'=>$id_note);
echo json_encode($arreglo);