<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

$insert_goal="INSERT INTO tbl_goals_library(goal_text,goal_discipline_id,goal_area,goal_term_id,deleted,active) "
                    . "VALUES('".$_POST['goal_text']."','".$_POST['goal_discipline_id']."','".$_POST['goal_area']."','".$_POST['goal_term_id']."',0,1)";

$resultado = ejecutar($insert_goal,$conexion);

if($resultado){
        $success=true;
}else{
    $success=false;
}

$arreglo=array('success'=>$success);
echo json_encode($arreglo);