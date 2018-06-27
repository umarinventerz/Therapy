<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();


$update_note_cpt_relation="UPDATE tbl_note_cpt_relation SET id_cpt=".$_POST['id_cpt'].", id_diagnosis=".$_POST['id_diagnosis'].", location='".$_POST['location']."',units=".$_POST['units'].",duration=".$_POST['duration'].", start='".$_POST['start']."',end='".$_POST['end']."' WHERE id_note_cpt_relation=".$_POST['id'];

$resultado = ejecutar($update_note_cpt_relation,$conexion);

if($resultado){
        $id_note=$_POST['id_note'];
        $mensaje="<b>Information was updated correctly</b>";
        $success=true;
}else{
    $mensaje="<b>An error has occurred, please try again</b>";
    $success=false;
    $id_note='';
}
$arreglo=array('mensaje'=>$mensaje,'success'=>$success, 'id_note'=>$id_note);
echo json_encode($arreglo);