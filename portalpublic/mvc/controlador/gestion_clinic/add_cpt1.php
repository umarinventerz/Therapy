<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

$insert_note_cpt_relation="INSERT INTO tbl_note_cpt_relation(id_note,id_cpt,id_diagnosis,location,units,duration,start,end) "
                    . "VALUES('".$_POST['id_note']."','".$_POST['id_cpt']."','".$_POST['id_diagnosis']."','".$_POST['location']."','".$_POST['units']."','".$_POST['duration']."','".$_POST['start']."','".$_POST['end']."')";

$resultado = ejecutar($insert_note_cpt_relation,$conexion);

if($resultado_nota){
        $id_note=$_POST['id_note'];
        $mensaje="<b>Information was loaded correctly</b>";
        $success=true;
}else{
    $mensaje="<b>An error has occurred, please try again</b>";
    $success=false;
    $id_note='';
}
$arreglo=array('mensaje'=>$mensaje,'success'=>$success, 'id_note'=>$id_note);
echo json_encode($arreglo);