<?php
session_start();
require_once("../../../../../conex.php");
require_once("../../date.php");
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'Must LOG IN First\')</script>';
    echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();


$id_date=$_POST['id_date'];
$star_time=$_POST['star_time'];
$end_time=$_POST['end_time'];

$update_date = "UPDATE calendar_appoiment_date SET start='".$star_time."',end='".$end_time."' WHERE id=".$id_date;
$resultado_date = ejecutar($update_date,$conexion); 
if($resultado_date){
$array=array('success'=>true);
}else{
    $array=array('success'=>false);
}   
echo json_encode($array);