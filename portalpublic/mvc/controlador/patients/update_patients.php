<?php
session_start();
require_once '../../../conex.php';

$conexion = conectar();
$Pat_id = $_GET['Patient_id'];
$Company = $_GET['Company'];
$newInsurance = $_GET['newInsurance'];
$update = "UPDATE patients SET Thi_Ins = '".$newInsurance."' WHERE Pat_id = '".$Pat_id."' 
#AND Table_name = '".$Company."'
";
$update2 = "UPDATE careplans set mail_sent_tx='0', tx_sent_time='' where Patient_ID='".$Pat_id."' and  status='1' ";

ejecutar($update,$conexion);	
ejecutar($update2,$conexion);
echo 'Actualizado';
//$_SESSION['find'] = ' Find ';
//$_SESSION['name'] = trim($_GET['patient_name']).'-'.$Company;
?>
