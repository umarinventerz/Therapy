<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="index.php";</script>';
}else{
//	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
//		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
//		echo '<script>window.location="../../home/home.php";</script>';
//	}
}

$conexion = conectar();

$sql  = "SELECT * FROM diagnosiscodes"
        . " WHERE DiagCodeValue = '".$_POST['diagnostic_code']."'";
$resultado = ejecutar($sql,$conexion);
$prescription = [];
while($datos = mysqli_fetch_assoc($resultado)) {
    $prescription = $datos;
}
$couny=count($prescription);


if($couny==0) {
    $sql_insert = "INSERT INTO diagnosiscodes(DiagCodeValue,DiagCodeDescrip,TreatDiscipId)
     VALUES ('" . $_POST['diagnostic_code'] . "','" . $_POST['description_code'] . "','" . $_POST['discipline'] . "')";
    ejecutar($sql_insert, $conexion);

    $array=array('success'=>true);
}
if($couny!=0)
{ $array=array('success'=>false);
 }

echo json_encode($array);
