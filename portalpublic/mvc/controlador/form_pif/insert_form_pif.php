<?php
error_reporting(0);
session_start();
require_once("../../../conex.php");
require_once('../../../fpdf181/fpdf.php');
require_once('../../../FPDI-1.6.1/fpdi.php');
require_once("../../../mail/phpmailer.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}

if(isset($_POST['medicare'])){ $medicare = 1; $medicare_check = 'checked'; } else { $medicare = 0; $medicare_check = null; }
if(isset($_POST['medicaid'])){ $medicaid = 1; $medicaid_check = 'checked';} else { $medicaid = 0; $medicaid_check = null;}
if(isset($_POST['medicaid_healthy_kids'])){ $medicaid_healthy_kids = 1;  $medicaid_healthy_kids_check = 'checked';} else { $medicaid_healthy_kids_check = null; $medicaid_healthy_kids = 0;}

if(isset($_POST['office_11'])){ $office_11 = 1;  $office_11_check = 'checked';} else { $office_11 = 0;  $office_11_check = null;}
if(isset($_POST['independent_clinic_49'])){ $independent_clinic_49 = 1; $independent_clinic_49_check = 'checked';} else { $independent_clinic_49_check = null; $independent_clinic_49 = 0;}
if(isset($_POST['other'])){$other = 1; $other_check = 'checked'; } else { $other = 0; $other_check = null; }

if(isset($_POST['physical_therapy'])){ $physical_therapy = 1;  $physical_therapy_check = 'checked';} else { $physical_therapy = 0; $physical_therapy_check = null;}
if(isset($_POST['occupational_therapy'])){ $occupational_therapy = 1; $occupational_therapy_check = 'checked'; } else { $occupational_therapy = 0; $occupational_therapy_check = null;}
if(isset($_POST['speech_therapy'])){ $speech_therapy = 1; $speech_therapy_check = 'checked'; } else { $speech_therapy = 0; $speech_therapy_check = null;}
    

$conexion = conectar();


$insert = "
INSERT into tbl_form_pif(
  facility_group_name,
  tin_number,
  facility_group_adress,
  facility_group_npi,
  city,
  state,
  zip,
  contact_person,
  phone,
  fax,
  treating_terapist_name,
  treating_terapist_npi,
  refering_provider_name,
  refering_provider_npi,
  patient_name,
  Pat_id,
  patient_county,
  patient_date_of_birth,
  primary_diagnosis_description,
  icd_code_1,
  icd_code_2,
  icd_code_3,
  icd_code_4,
  status_post_surgery,
  date_of_surgery,
  for_cerebral_vascular_accident,
  since_evaluation_date,
  number_of_visits_attended,
  date_of_last_visit,
  additional_information,
  discipline,
  idc_10,
  since_evaluation_date_1,
  number_of_visits_attended_1,
  date_of_last_visit_1,
  note_comments,
  test_used,
  test_result_standard,
  test_result_age,
  times_peer_weeks,
  number_weeks,
  confirm_1,
  confirm_2,
  confirm_3,
  month_test,
  year_test,
  routine,
  urgent)  
VALUES
('".$_POST['facility_group_name']."',
'".$_POST['tin_number']."',
'".$_POST['facility_group_address']."',
'".$_POST['facility_group_npi']."',
'".$_POST['city']."',
'".$_POST['state']."',
'".$_POST['zip']."',
'".$_POST['contact_person']."',
'".$_POST['phone']."',
'".$_POST['fax']."',
'".$_POST['treating_therapist_name']."',
'".$_POST['treating_therapist_npi']."',
'".$_POST['refering_provider_name']."',
'".$_POST['refering_provider_npi']."',
'".$_POST['patient_name']."',
'".$_POST['patient_id']."',
'".$_POST['patient_county']."',
'".$_POST['patient_date_of_birth']."',
'".$_POST['primary_diagnosis_description']."',
'".$_POST['icd_code_1']."',
'".$_POST['icd_code_2']."',
'".$_POST['icd_code_3']."',
'".$_POST['icd_code_4']."',
'".$_POST['status_post_surgery']."',
'".$_POST['date_of_surgery']."',
'".$_POST['for_cerebral_vascular_accident']."',
'".$_POST['since_evaluation_date']."',
'".$_POST['number_of_visits_attended']."',
'".$_POST['date_of_last_visit']."',
'".$_POST['additional_information']."',    
'".$_POST['discipline']."',
'".$_POST['since_evaluation_date_2']."',
'".$_POST['number_of_visits_attended_2']."',
'".$_POST['date_of_last_visit_2']."',
'".$_POST['note_comments']."',
'".$_POST['test_used']."',
'".$_POST['result_standar']."',
'".$_POST['result_age']."',
'".$_POST['times_per_week']."',
'".$_POST['number_weeks']."',
'".$_POST['confirm_1']."',
'".$_POST['confirm_2']."',
'".$_POST['confirm_3']."',
'".$_POST['month']."',
'".$_POST['year']."',    
'".$_POST['date_of_last_visit']."',    
'".$_POST['routine']."',        
'".$_POST['urgent']."');";  
//ALTER TABLE `kidswork_therapy`.`tbl_form_pif` ADD COLUMN `routine` BOOLEAN  NOT NULL AFTER `year_test`,
//ADD COLUMN `urgent` BOOLEAN  NOT NULL AFTER `routine`;

ejecutar($insert,$conexion);

    list($id_company,$data_company) = explode('|',$_POST['facility_group_name']);
    
    
    $sql_company = "SELECT * FROM companies WHERE company_id = ".$id_company;
    $resultado_company = ejecutar($sql_company,$conexion); 
    $reporte_company = array();
    $j = 0;      
    while($datos = mysqli_fetch_assoc($resultado_company)) {            
        $reporte_company[$j] = $datos;
        $j++;
    }
    
    $sql  = "SELECT max(id_form_pif) as identificador FROM tbl_form_pif;";
    $resultado = ejecutar($sql,$conexion); 
    $j = 0;      
    while($datos = mysqli_fetch_assoc($resultado)) {            
        $reporte[$j] = $datos;
        $j++;
    }
    
    $identificador = $reporte[0]['identificador'];
   

    $insert = "
    INSERT into tbl_line_of_business(
    medicare, 
    medicaid, 
    medicaid_healthy_kids, 
    id_form_pif)  
    VALUES
    ('".$medicare."',
    '".$medicaid."',
    '".$medicaid_healthy_kids."',
    '".$identificador."');";  

    ejecutar($insert,$conexion);   
    
    
    $insert = "
    INSERT into tbl_place_of_service(
    office_11, 
    independent_clinic_49, 
    other, 
    id_form_pif)  
    VALUES
    ('".$office_11."',
    '".$independent_clinic_49."',
    '".$other."',
    '".$identificador."');";  

    ejecutar($insert,$conexion);     
 
 //echo $_POST['occupational_therapy'];
 //die;   

list($last_name,$name) = explode(',',$_POST['patient_name']);
list($treating_therapist_name,$treating_therapist_npi) = explode('|',$_POST['treating_therapist_name']);
    // initiate FPDI
$pdf = new FPDI();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("prueba.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 10, 10, 190);

// now write some text above the imported page
$pdf->SetFont('Helvetica');
$pdf->SetTextColor(0, 0, 0);

// ############ routine and urgent ###################
if($_POST['routine'] == 'on'){
    $pos_ = 20;
}
if($_POST['urgent'] == 'on'){
    $pos_ = 34;
}
$pdf->SetXY($pos_, 10);
$pdf->Write(66, 'x');
// ##################################################


$pdf->SetXY(20, 10);
$pdf->Write(84, $reporte_company[0]['company_name']);

$pdf->SetXY(133, 10);
$pdf->Write(84, $_POST['tin_number']);

$pdf->SetXY(133, 10);
$pdf->Write(100, $_POST['facility_group_npi']);

$sql_user_system = "SELECT * FROM user_system WHERE user_id = ".$_POST['contact_person'];
$resultado_user = ejecutar($sql_user_system,$conexion); 
$j = 0;      
while($datos = mysqli_fetch_assoc($resultado_user)) {            
    $reporte_user[$j] = $datos;
    $j++;
}    
$pdf->SetXY(19, 10);
$pdf->Write(132, $reporte_user[0]['First_name'].' '.$reporte_user[0]['Last_name']);


$pdf->SetXY(19, 10);
$pdf->Write(149, $treating_therapist_name);
$pdf->SetXY(133, 10);
$pdf->Write(149, $_POST['treating_therapist_npi']);


$pdf->SetXY(19, 10);
$pdf->Write(166, $_POST['refering_provider_name']);
$pdf->SetXY(133, 10);
$pdf->Write(166, $_POST['refering_provider_npi']);

$pdf->SetXY(19, 10);
$pdf->Write(183, trim($last_name));
$pdf->SetXY(78, 10);
$pdf->Write(183, trim($name));
$pdf->SetXY(133, 10);
$pdf->Write(183, $_POST['patient_id']);

$pdf->SetXY(19, 10);
$pdf->Write(200, $_POST['patient_county']);
$pdf->SetXY(133, 10);
$pdf->Write(200, $_POST['patient_date_of_birth']);

// ############ Line of Business ###################
if($_POST['medicare'] == 'on'){
    $pdf->SetXY(47, 10);
    $pdf->Write(209, 'x');
}
if($_POST['medicaid'] == 'on'){
    $pdf->SetXY(74, 10);
    $pdf->Write(209, 'x');
}
if($_POST['medicaid_healthy_kids'] == 'on'){
    $pdf->SetXY(99, 10);
    $pdf->Write(209, 'x');
}
// #########################################

// ########### Place of service ############
if($_POST['office_11'] == 'on'){
    $pdf->SetXY(47, 10);
    $pdf->Write(221, 'x');
}
if($_POST['independent_clinic_49'] == 'on'){
    $pdf->SetXY(74, 10);
    $pdf->Write(221, 'x');
}
if($_POST['other'] == 'on'){
    $pdf->SetXY(112, 10);
    $pdf->Write(221, 'x');
}
// ###########################################

$pdf->SetXY(19, 10);
$pdf->Write(240, $_POST['primary_diagnosis_description']);

// ########## ICD #####################
if($_POST['icd_code_10'] == 'on'){
    $pdf->SetXY(20, 10);
    $pdf->Write(251, 'x');
}
$pdf->SetXY(35, 10);
$pdf->Write(255, $_POST['icd_code_1']);
$pdf->SetXY(74, 10);
$pdf->Write(255, $_POST['icd_code_2']);
$pdf->SetXY(113, 10);
$pdf->Write(255, $_POST['icd_code_3']);
$pdf->SetXY(151, 10);
$pdf->Write(255, $_POST['icd_code_4']);
// ##################################

$pdf->SetXY(19, 146);
$pdf->Write(0, $_POST['status_post_surgery']);

$pdf->SetXY(19, 154);
$pdf->Write(0, $_POST['date_of_surgery']);
$pdf->SetXY(105, 154);
$pdf->Write(0, $_POST['for_cerebral_vascular_accident']);

// ########## check box to confirm ###########
//if($_POST['confirm_1'] == 'on'){
    $pdf->SetXY(20, 158);
    $pdf->Write(0, 'x');
//}
if($_POST['confirm_2'] == 'on'){
    $pdf->SetXY(77, 158);
    $pdf->Write(0, 'x');
}
if($_POST['confirm_3'] == 'on'){
    $pdf->SetXY(134, 158);
    $pdf->Write(0, 'x');
}
// ###########################################

$pdf->SetXY(23, 171);
$pdf->Write(0, $_POST['times_per_week']);
$pdf->SetXY(44, 171);
$pdf->Write(0, $_POST['number_weeks']);


if($_POST['physical_therapy'] == 'on'){
    $setXY = 20;
    $_POST['discipline'] = 'PT';
}
if($_POST['occupational_therapy'] == 'on'){
    $setXY = 63;
    $_POST['discipline'] = 'OT';
}
if($_POST['speech_therapy'] == 'on'){
    $setXY = 105;
    $_POST['discipline'] = 'ST';
}
$pdf->SetXY($setXY, 180);
$pdf->Write(0, 'x');
$pdf->SetXY(163, 180);
$pdf->Write(0, $_POST['evaluation_date']);


$pdf->SetXY(37, 187);
$pdf->Write(0, $_POST['test_used']);
$pdf->SetXY(117, 187);
$pdf->Write(0, $_POST['result_standar']);
$pdf->SetXY(158, 187);
$pdf->Write(0, $_POST['result_age']);

if($_POST['month'] == 'on'){
    $pdf->SetXY(175, 184);
    $pdf->Write(0, 'x');
}
if($_POST['year'] == 'on'){
    $pdf->SetXY(175, 187);
    $pdf->Write(0, 'x');
}

$arrayNotesX = array(37,20);
$arrayNotesY = array(193,199);
$notes = cortarCadena($_POST['note_comments'],$arrayNotesX,$arrayNotesY,50,60); 
$b = 0;
while(isset($notes[$b])){
    $pdf->SetXY($notes[$b]['x'], $notes[$b]['y']);
    $pdf->Write(0, $notes[$b]['oracion']);
    $b++;
}

$pdf->SetXY(20, 211);
$pdf->Write(0, $_POST['since_evaluation_date']);
$pdf->SetXY(77, 211);
$pdf->Write(0, $_POST['number_of_visits_attended']);
$pdf->SetXY(134, 211);
$pdf->Write(0, $_POST['date_of_last_visit']);


$pdf->SetXY(20, 223);
$pdf->Write(0, $_POST['since_evaluation_date_2']);
$pdf->SetXY(77, 223);
$pdf->Write(0, $_POST['number_of_visits_attended_2']);
$pdf->SetXY(134, 223);
$pdf->Write(0, $_POST['date_of_last_visit_2']);

$arrayX = array(44,20,20);
$arrayY = array(229,235,241);
$additional_information = cortarCadena($_POST['additional_information'],$arrayX,$arrayY,50,60); 

$b = 0;
while(isset($additional_information[$b])){
    $pdf->SetXY($additional_information[$b]['x'], $additional_information[$b]['y']);
    $pdf->Write(0, $additional_information[$b]['oracion']);
    $b++;
}
    
$pdf->Output('F','../../../pdf_form_pif/73.pdf');
//$pdf->Output('F','../../../pdf_form_pif/'.$identificador.'.pdf');

chmod ('../../../pdf_form_pif/73.pdf',0777);                    
//chmod ('../../../pdf_form_pif/'.$identificador.'.pdf',0777);     
   
$json_resultado['resultado'] = 'insertado';
$json_resultado['ruta'] = "../../../pdf_form_pif/73.pdf";


function cortarCadena($cadena,$arrayX,$arrayY,$cant1,$cant2){
    $cadena=ereg_replace('[[:space:]]+',' ',$cadena);      
    $arrayPalabras = explode(" ",$cadena);
    $r = 0;
    $arrayOracion = array();
    $o = 0;
    $cantidadCaracteres = 0;
    while(isset($arrayPalabras[$r])){
        $cantidadCaracteres += strlen($arrayPalabras[$r]);        
        if(($cantidadCaracteres >= $cant1) || ($o > 0 && $cantidadCaracteres >= $cant2)){            
            $cantidadCaracteres = 0;  
            $arrayOracion[$o]['x'] = $arrayX[$o]; 
            $arrayOracion[$o]['y'] = $arrayY[$o];       
            $o++;
            $arrayOracion[$o]['oracion'] = $arrayPalabras[$r].' ';
        }else{
            $arrayOracion[$o]['oracion'] .= $arrayPalabras[$r].' ';            
        }
        
        $r++;
    }
    if(!isset($arrayOracion[$o]['x'])){
        $arrayOracion[$o]['x'] = $arrayX[$o]; 
        $arrayOracion[$o]['y'] = $arrayY[$o];
    }
        
    return $arrayOracion;
}

echo json_encode($json_resultado);

