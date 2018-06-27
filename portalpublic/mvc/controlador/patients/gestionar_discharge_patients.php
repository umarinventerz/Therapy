<?php
session_start();
require_once '../../../conex.php';

$conexion = conectar();

if($_POST['name'] == ''){ 
    $json_resultado['mensaje'] = "You must select a patient";
    $json_resultado['type'] = "error";
}else{ 
    
list($Pat_id,$company) = explode('-',$_POST['name']) ;
$t = 0;
if(isset($_POST['OT'])){
    $array_discipline[$t] = 'OT';
    $t++;
}

if(isset($_POST['PT'])){
    $array_discipline[$t] = 'PT';
    $t++;
}

if(isset($_POST['ST'])){
    $array_discipline[$t] = 'ST';
    $t++;
}
$user_id = $_SESSION['user_id'];
$date = date('Y-m-d H:m:s');
$patient_id = trim($Pat_id);

$y = 0;
$g = 0;
while(isset($array_discipline[$y])){
    
    /*$sql_discharge_verificar = "SELECT * FROM tbl_audit_discharge_patient WHERE patient_id = '".$patient_id."' AND discipline ='".$array_discipline[$y]."'";
    $resultado_discharge_verificar = ejecutar($sql_discharge_verificar,$conexion);                     
    
    $id_discharge_verificar = null;
    while ($row=mysqli_fetch_array($resultado_discharge_verificar)) {	
        $id_discharge_verificar = $row['id'];
    } 
    
    if($id_discharge_verificar == null){*/
        $sql_careplans = "UPDATE careplans SET status = 0 WHERE Patient_ID = '".trim($Pat_id)."' AND Discipline = '".$array_discipline[$y]."'";
        $resultado = ejecutar($sql_careplans,$conexion);
        $sql_authorizations = "UPDATE authorizations SET status = 0 WHERE Pat_id = '".trim($Pat_id)."' AND Discipline = '".$array_discipline[$y]."'";
        $resultado = ejecutar($sql_authorizations,$conexion);
        $sql_insurance = "UPDATE insurance SET status = 0  WHERE Pat_id = '".trim($Pat_id)."' AND Discipline = '".$array_discipline[$y]."'";
        $resultado = ejecutar($sql_insurance,$conexion);
        $sql_signed_doctor = "UPDATE signed_doctor SET status = 0  WHERE Patient_ID = '".trim($Pat_id)."' AND Discipline = '".$array_discipline[$y]."'";
        $resultado = ejecutar($sql_signed_doctor,$conexion);
        $sql_prescription = "UPDATE prescription SET status = 0  WHERE Patient_ID = '".trim($Pat_id)."' AND Discipline = '".$array_discipline[$y]."'";
        $resultado = ejecutar($sql_prescription,$conexion);

        $sql_patients_copy = "UPDATE patients_copy 
        SET discharge_".$array_discipline[$y]." = '1' ,
            prescription_".$array_discipline[$y]."= '0', 
            waiting_prescription_".$array_discipline[$y]."= '0',
            eval_auth_".$array_discipline[$y]."= '0',
            waiting_auth_eval_".$array_discipline[$y]."= '0',
            eval_patient_".$array_discipline[$y]."= '0',
            doctor_signature_".$array_discipline[$y]."= '0',
            waiting_signature_".$array_discipline[$y]."= '0',
            tx_auth_".$array_discipline[$y]."= '0',
            waiting_tx_auth_".$array_discipline[$y]."= '0',
            ready_treatment_".$array_discipline[$y]."= '0',
            scheduled_".$array_discipline[$y]."= '0',
            hold_".$array_discipline[$y]."= '0'
            
            WHERE Pat_id = '".trim($Pat_id)."'   ";
        $resultado = ejecutar($sql_patients_copy,$conexion);
 

        $insert =" INSERT into tbl_audit_discharge_patient (user_id, date, patient_id, discipline) values ('".$user_id."','".$date."','".$patient_id."','".$array_discipline[$y]."');";
        $resultado = ejecutar($insert,$conexion); 
        
        $resultado_discharge_by_discipline[$g] = 'Id: '.$patient_id.', Discipline: '.$array_discipline[$y].' Discharge realizado con exito';
        $g++;
  /*  }else{
        $resultado_discharge_by_discipline[$g] = 'Id: '.$patient_id.', Discipline: '.$array_discipline[$y].' <font color="red">Ya se realizo un discharge </font>';
        $g++;
    }*/
    $y++;
}
//echo '<pre>';
//print_r($resultado_discharge_by_discipline);
 $json_resultado['mensaje'] = "Last modification";
 $json_resultado['type'] = "success";
 $json_resultado['result_discharge'] = $resultado_discharge_by_discipline;
} 


echo json_encode($json_resultado); 
?>
