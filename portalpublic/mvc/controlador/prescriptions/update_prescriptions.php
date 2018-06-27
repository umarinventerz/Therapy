<?php
session_start();
require_once '../../../conex.php';
//echo '<pre>';
//print_r($_GET);
$conexion = conectar();
$pat_id = $_GET['pat_id'];
$Company = $_GET['Company'];
$datos = explode(" ",$_GET['Discipline']);
$Discipline = $datos[0];
if($_GET['type'] == 'READY FOR TREATMENT'){
	//$update_p = "UPDATE prescription SET status = 0 WHERE Patient_ID = '".$Pat_id."' #AND Table_name = '".$Company."' 
	//AND Discipline='".$Discipline."'";
	//ejecutar($update_p,$conexion);
	
	$update_s = "UPDATE signed_doctor SET status = 0 WHERE Patient_ID = '".$pat_id."' AND Discipline='".$Discipline."' ";
	ejecutar($update_s,$conexion);
	
	$update_a = "UPDATE authorizations SET status = 0 WHERE pat_id = '".$pat_id."' AND Discipline='".$Discipline."' ";
	ejecutar($update_a,$conexion);

//////////////////////// UPDATE PARA EL NUEVO REPORTE DE PATIENTS COPY /////////////////////////////
	ECHO $update_to_scheduled = " UPDATE patients_copy SET  ready_treatment_".$Discipline." = 0 , scheduled_".$Discipline." = 1 WHERE  Pat_id = '".$pat_id."'      ";
	ejecutar($update_to_scheduled,$conexion);
/////////////////////// NOTAS
	echo $update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$pat_id."' AND discipline = '".$Discipline."'  AND type_report = 'READY FOR TREATMENT'  ";   
	ejecutar($update_notes,$conexion);

}else{
	$evalschedule = $_GET['evalschedule'];
	$evalDone = $_GET['evalDone'];
	if($evalDone == 0){
echo $update = "UPDATE prescription SET Eval_schedule = '".$evalschedule."' WHERE Patient_ID = '".$pat_id."'#AND Table_name = '".$Company."'
 AND Discipline='".$Discipline."' and status=1";	 	
	ejecutar($update,$conexion);
	
	}else{	
	$update = "UPDATE prescription SET Eval_schedule = '".$evalschedule."',Eval_done = '".$evalDone."' WHERE Patient_ID = '".$pat_id."' AND Discipline='".$Discipline."'";	 	
	ejecutar($update,$conexion);

	$update_eval_done = "UPDATE patients_copy SET  eval_patient_".$Discipline." = '0' , doctor_signature_".$Discipline." = '1' 
	WHERE  pat_id = '".$pat_id."'    AND eval_patient_".$Discipline." = '1'  ";
	ejecutar($update_eval_done,$conexion);
	}
	
}
	echo 'Actualizado';
	

//$_SESSION['find'] = ' Find ';
//$_SESSION['name'] = trim($_GET['patient_name']).'-'.$Company;
?>
