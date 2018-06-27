<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();
$site=0;
$site=$_POST['value'];
//consulto si el appoiments enviado tiene alguna evaluacion asociada

$employye_informations="SELECT * FROM employee
WHERE id=".$_POST['value'];

$employye_informations_info = ejecutar($employye_informations,$conexion);
while ($row_appoiment=mysqli_fetch_array($active_appoiment)) {

    $valor_appoiment['id'] = $row_appoiment['id'];
}

    echo json_encode($arreglo);
