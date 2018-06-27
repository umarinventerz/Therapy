<?php
session_start();
require_once '../../../conex.php';
//echo '<pre>';
//print_r($_GET);
$conexion = conectar();
$Pat_id = $_GET['Patient_id'];
$Company = $_GET['Company'];
$datos = explode(" ",$_GET['Discipline']);
$Discipline = $datos[0];
if($_GET['type'] == 'READY FOR TREATMENT'){
	$update_p = "UPDATE prescription SET status = 0 WHERE Patient_ID = '".$Pat_id."' #AND Table_name = '".$Company."' 
	AND Discipline='".$Discipline."'";
	ejecutar($update_p,$conexion);
	$update_s = "UPDATE signed_doctor SET status = 0 WHERE Patient_ID = '".$Pat_id."' #AND Table_name = '".$Company."' 
	AND Discipline='".$Discipline."'";
	ejecutar($update_s,$conexion);
	$update_a = "UPDATE authorizations SET status = 0 WHERE pat_id = '".$Pat_id."' #AND Company = '".$Company."' 
	AND Discipline='".$Discipline."'";
	ejecutar($update_a,$conexion);

   }else{
	$evalschedule = $_GET['evalschedule'];
	$evalDone = $_GET['evalDone'];
	 $update = "UPDATE prescription SET Eval_schedule = '".$evalschedule."',Eval_done = '".$evalDone."' WHERE Patient_ID = '".$Pat_id."' 
	# AND Table_name = '".$Company."' 
	 AND Discipline='".$Discipline."' and status='1' ";

	$update_new_report = " UPDATE patients_copy SET eval_patient_".$Discipline."='0' ,doctor_signature_".$Discipline."='1' WHERE Pat_id = '".$Pat_id."' and eval_patient_".$Discipline."='1' " ;

	ejecutar($update,$conexion);	
	ejecutar($update_new_report,$conexion);	
	
}
	echo 'Actualizado';
	

//$_SESSION['find'] = ' Find ';
//$_SESSION['name'] = trim($_GET['patient_name']).'-'.$Company;
?>
