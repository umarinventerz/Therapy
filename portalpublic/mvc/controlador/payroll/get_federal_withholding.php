<?php
session_start();
require_once("../../../conex.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$conexion = conectar();
$sql_federal  = "SELECT dependence_". sanitizeString($conexion, $_REQUEST['dependencies']) . " as dependence FROM federal_withholdings fw WHERE '". sanitizeString($conexion, $_REQUEST['gross']) . "'>=at_least and '" . sanitizeString($conexion, $_REQUEST['gross']) . "'<but_less_then AND civil_status = '" . sanitizeString($conexion, $_REQUEST['civil_status']) . "';";
$data_federal_withholdings = ejecutar($sql_federal,$conexion);                

$reporte_federal_withholdings = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($data_federal_withholdings)) {            
    $reporte_federal_withholdings[$i] = $datos;
    $i++;
}

if($reporte_federal_withholdings[0]['dependence'] == null){
    $variable_federal = 0;
} else {
    $variable_federal =  $reporte_federal_withholdings[0]['dependence'];
}

$json_resultado['variable_federal'] =  $variable_federal;

echo json_encode($json_resultado);

?>