<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}

$conexion = conectar();
    
$modify = "update patients_copy set prescription_ot=".$_POST['fila1_ot'].", prescription_pt=".$_POST['fila1_pt'].", prescription_st=".$_POST['fila1_st']
         .", waiting_prescription_ot=".$_POST['fila2_ot'].", waiting_prescription_pt=".$_POST['fila2_pt'].", waiting_prescription_st=".$_POST['fila2_st']
         .", eval_auth_ot=".$_POST['fila3_ot'].", eval_auth_pt=".$_POST['fila3_pt'].", eval_auth_st=".$_POST['fila3_st']
         .", waiting_auth_eval_ot=".$_POST['fila4_ot'].", waiting_auth_eval_pt=".$_POST['fila4_pt'].", waiting_auth_eval_st=".$_POST['fila4_st']
         .", eval_patient_ot=".$_POST['fila5_ot'].", eval_patient_pt=".$_POST['fila5_pt'].", eval_patient_st=".$_POST['fila5_st']
         .", doctor_signature_ot=".$_POST['fila6_ot'].", doctor_signature_pt=".$_POST['fila6_pt'].", doctor_signature_st=".$_POST['fila6_st']
         .", waiting_signature_ot=".$_POST['fila7_ot'].", waiting_signature_pt=".$_POST['fila7_pt'].", waiting_signature_st=".$_POST['fila7_st']
        .", tx_auth_ot=".$_POST['fila8_ot'].", tx_auth_pt=".$_POST['fila8_pt'].", tx_auth_st=".$_POST['fila8_st']
         .", waiting_tx_auth_ot=".$_POST['fila9_ot'].", waiting_tx_auth_pt=".$_POST['fila9_pt'].", waiting_tx_auth_st=".$_POST['fila9_st']
         .", ready_treatment_ot=".$_POST['fila10_ot'].", ready_treatment_pt=".$_POST['fila10_pt'].", ready_treatment_st=".$_POST['fila10_st']
         .", scheduled_ot=".$_POST['fila11_ot'].", scheduled_pt=".$_POST['fila11_pt'].", scheduled_st=".$_POST['fila11_st']
         .", hold_ot=".$_POST['fila12_ot'].", hold_pt=".$_POST['fila12_pt'].", hold_st=".$_POST['fila12_st']
         .", progress_adults_ot=".$_POST['fila13_ot'].", progress_adults_pt=".$_POST['fila13_pt'].", progress_adults_st=".$_POST['fila13_st']
         .", progress_pedriatics_ot=".$_POST['fila14_ot'].", progress_pedriatics_pt=".$_POST['fila14_pt'].", progress_pedriatics_st=".$_POST['fila14_st']
         .", been_seen_ot=".$_POST['fila15_ot'].", been_seen_pt=".$_POST['fila15_pt'].", been_seen_st=".$_POST['fila15_st']   
         .", discharge_ot=".$_POST['fila16_ot'].", discharge_pt=".$_POST['fila16_pt'].", discharge_st=".$_POST['fila16_st']   
         ." WHERE Pat_id='".$_POST['pat_id']."'  ";
        
              
$modificar = ejecutar($modify,$conexion); 
    
    if($modificar){
        $array=array('succes'=>'Records updated successfully','id'=>$id_template);
    }else{
        $array=array('succes'=>'There was an error updating the Records, please try again');
    }
    $response=  json_encode($array);
    echo $response;



