<?php
error_reporting(0);
session_start();
require_once("../../../conex.php");
require_once("../../../dompdf/dompdf_config.inc.php");
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

if(isset($_POST['medicare'])){ $medicare = true; $medicare_check = 'checked'; } else { $medicare = false; $medicare_check = null; }
if(isset($_POST['medicaid'])){ $medicaid = true; $medicaid_check = 'checked';} else { $medicaid_check = false; $medicaid_check = null;}
if(isset($_POST['medicaid_healthy_kids'])){ $medicaid_healthy_kids = true;  $medicaid_healthy_kids_check = 'checked';} else { $medicaid_healthy_kids_check = null; $medicaid_healthy_kids_check = false;}

if(isset($_POST['office_11'])){ $office_11 = true;  $office_11_check = 'checked';} else { $office_11_check = false;  $office_11_check = null;}
if(isset($_POST['independent_clinic_49'])){ $independent_clinic_49 = true; $independent_clinic_49_check = 'checked';} else { $independent_clinic_49_check = null; $independent_clinic_49 = false;}
if(isset($_POST['other'])){$other = true; $other_check = 'checked'; } else { $other = false; $other_check = null; }

if(isset($_POST['physical_therapy'])){ $physical_therapy = true;  $physical_therapy_check = 'checked';} else { $physical_therapy = false; $physical_therapy_check = null;}
if(isset($_POST['occupational_therapy'])){ $occupational_therapy = true; $occupational_therapy_check = 'checked'; } else { $occupational_therapy = false; $occupational_therapy_check = null;}
if(isset($_POST['speech_therapy'])){ $speech_therapy = true; $speech_therapy_check = 'checked'; } else { $speech_therapy = false; $speech_therapy_check = null;}




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
patient_country, 
patient_date_of_birth,
primary_diagnosis_description, 
icd_code_1, 
icd_code_2, 
icd_code_3, 
icd_code_4, 
status_post_surgery, 
date_of_surgery,
for_cerebral_vascular_accident, 
physical_therapy,
occupational_therapy,
speech_therapy,
since_evaluation_date, 
number_of_visits_attended,
date_of_last_visit,
additional_information)  
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
'".$_POST['patient_country']."',
'".$_POST['patient_date_of_birth']."',
'".$_POST['primary_diagnosis_description']."',
'".$_POST['icd_code_1']."',
'".$_POST['icd_code_2']."',
'".$_POST['icd_code_3']."',
'".$_POST['icd_code_4']."',
'".$_POST['status_post_surgery']."',
'".$_POST['date_of_surgery']."',
'".$_POST['for_cerebral_vascular_accident']."',
'".$_POST['physical_therapy']."',
'".$_POST['occupational_therapy']."',
'".$_POST['speech_therapy']."',
'".$_POST['since_evaluation_date']."',
'".$_POST['number_of_visits_attended']."',
'".$_POST['date_of_last_visit']."',    
'".$_POST['additional_information']."');";  

ejecutar($insert,$conexion);

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
    
    
    

$html = '
<table cellpadding="0" cellspacing="0" border="1" width="100%" align="center">
        <tr>
            <td>
    <table cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="50%" align="center">
                    <tr>
                        <td>
                            <img src="../../../images/ata.jpg">
                        </td>
                        <td align="center">
                            <b>American Therapy<br>Adminsitrators<br>of Florida</b>
                        </td>                                             
                    </tr>
                </table>
            </td>  
            <td align="center"><img src="../../../images/codigo_qr.jpg" width="50px" height="50px"></td>
            <td align="center"><h2><font color="#BDBDBD">Patient Intake Form</font></h2></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
        <tr>
            <td>
                <input type="checkbox"> Routine
            </td>
            <td>
                <input type="checkbox"> Urgent (Please Indicate Medical reason in the Additional Information Section Below)
            </td>  
            <td>
                <table cellpadding="0" cellspacing="0" border="1" width="90%" height="90px" align="center">
                    <tr>
                        <td align="center">
                            For Inquires or status of<br>pending request, call:<br>1 (888) 550-8800 x1
                        </td>                                                              
                    </tr>
                </table>
            </td>    
            <td>
                <table cellpadding="0" cellspacing="0" border="1" width="90%" height="90px" align="center">
                    <tr>
                        <td align="center">
                            Fax this request to:<br>1 (855) 410-0121
                        </td>                                                              
                    </tr>
                </table>
            </td>              
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">
        <tr>
            <td width="50%">
                <font color="#A4A4A4" size="2"><b>Facility / Group Name</b></font><br><b>'.$_POST['facility_group_name'].'</b>
            </td>
            <td width="50%">
                <font color="#A4A4A4" size="2"><b>TIN number</b></font><br><b>'.$_POST['tin_number'].'</b>
            </td>            
        </tr>
        <tr>
            <td width="50%">
                <font color="#A4A4A4" size="2"><b>Facility / Group Address (where services will be rendered)</b></font><br><b>'.$_POST['facility_group_address'].'</b>
            </td>
            <td width="50%">
                <font color="#A4A4A4" size="2"><b>Facility / Group NPI</b></font><br><b>'.$_POST['facility_group_npi'].'</b>
            </td>            
        </tr>  
    </table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>City</b></font><br><b>'.$_POST['city'].'</b>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>State</b></font><br><b>'.$_POST['state'].'</b>
            </td>    
            <td>
                <font color="#A4A4A4" size="2"><b>Zip</b></font><br><b>'.$_POST['zip'].'</b>
            </td>             
        </tr>  
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Contact Person</b></font><br><b>'.$_POST['contact_person'].'</b>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Phone</b></font><br><b>'.$_POST['phone'].'</b>
            </td>    
            <td>
                <font color="#A4A4A4" size="2"><b>Fax</b></font><br><b>'.$_POST['fax'].'</b>
            </td>             
        </tr>   
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Treating Therapist Name</b></font><br><b>'.$_POST['treating_therapist_name'].'</b>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Treating Therapist Npi</b></font><br><b>'.$_POST['treating_therapist_npi'].'</b>
            </td>                             
        </tr>   
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Refering Provider Name</b></font><br><b>'.$_POST['refering_provider_name'].'</b>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Refering Provider Npi</b></font><br><b>'.$_POST['refering_provider_npi'].'</b>
            </td>                             
        </tr>         
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Patient Name</b></font><br><b>'.$_POST['patient_name'].'</b>
            </td>   
            <td>
                <font color="#A4A4A4" size="2"><b>Patient Id</b></font><br><b>'.$_POST['patient_id'].'</b>
            </td>               
        </tr>          
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Patient Country</b></font><br><b>'.$_POST['patient_country'].'</b>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Patient Date Of Birth</b></font><br><b>'.$_POST['patient_date_of_birth'].'</b>
            </td>                               
        </tr>  
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Line of Business</b></font>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Medicare</b></font>&nbsp;&nbsp;<input type="checkbox" '.$medicare_check.'>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Medicaid</b></font>&nbsp;&nbsp;<input type="checkbox" '.$medicaid_check.'>
            </td>    
            <td>
                <font color="#A4A4A4" size="2"><b>Medicaid Healthy Kids</b></font>&nbsp;&nbsp;<input type="checkbox" '.$medicaid_healthy_kids.'>
            </td>              
        </tr> 
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Place of Service</b></font>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Office 11</b></font>&nbsp;&nbsp;<input type="checkbox" '.$office_11.'>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Independent Clinic 49</b></font>&nbsp;&nbsp;<input type="checkbox" '.$independent_clinic_49_check.'>
            </td>    
            <td>
                <font color="#A4A4A4" size="2"><b>Other</b></font>&nbsp;&nbsp;<input type="checkbox"  '.$other_check.'>
            </td>              
        </tr> 
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Primary Diagnosis Description</b></font><br><b>'.$_POST['primary_diagnosis_description'].'</b>
            </td>                  
        </tr>  
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">           
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>ICD 10</b></font>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Icd Code 1</b></font><br><b>'.$_POST['icd_code_1'].'</b>
            </td>
            <td>
                <font color="#A4A4A4" size="2"><b>Icd Code 2</b></font><br><b>'.$_POST['icd_code_2'].'</b>
            </td>    
            <td>
                <font color="#A4A4A4" size="2"><b>Icd Code 3</b></font><br><b>'.$_POST['icd_code_3'].'</b>
            </td>  
            <td>
                <font color="#A4A4A4" size="2"><b>Icd Code 4</b></font><br><b>'.$_POST['icd_code_4'].'</b>
            </td>              
        </tr>
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">           
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Status Post Surgery</b></font><br><b>'.$_POST['status_post_surgery'].'</b>
            </td>                  
        </tr>  
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">           
        <tr>
            <td width="50%">
                <font color="#A4A4A4" size="2"><b>Date Of Surgery</b></font><br><b>'.$_POST['date_of_surgery'].'</b>
            </td>      
            <td width="50%">
                <font color="#A4A4A4" size="2"><b>For Cerebral Vascular Accident</b></font><br><b>'.$_POST['for_cerebral_vascular_accident'].'</b>
            </td>              
        </tr>   
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td>
                <input type="checkbox"><font color="#A4A4A4" size="2"><b>Please Checkbox to Confirm</b></font><br><b>Texto que no se entiende bien</b>
            </td>      
            <td>
                <input type="checkbox"><font color="#A4A4A4" size="2"><b>Please Checkbox to Confirm</b></font><br><b>Texto que no se entiende bien</b>
            </td>        
            <td>
                <input type="checkbox"><font color="#A4A4A4" size="2"><b>Please Checkbox to Confirm</b></font><br><b>Texto que no se entiende bien</b>
            </td>             
        </tr> 
</table>
</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="1" width="100%" align="center">
        <tr>
            <td>

<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">        
        <tr bgcolor="#585858">
            <td>
                <font color="white"><b>STEP 1: FILL OUT SEPARATE PATIENT FORM FOR EACH DISCIPLINE</b></font>
            </td>                             
        </tr>
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td>
                <input type="checkbox" '.$physical_therapy_check.'><font color="#A4A4A4" size="2"><b>Physical Therapy</b></font>
            </td>      
            <td>
                <input type="checkbox" '.$occupational_therapy_check.'><font color="#A4A4A4" size="2"><b>Occupational Therapy</b></font>
            </td>        
            <td>
                <input type="checkbox" '.$speech_therapy_check.'><font color="#A4A4A4" size="2"><b>Speech Therapy</b></font>
            </td>             
            <td>
                <font color="#A4A4A4" size="2"><b>Evaluation Date (mm/dd/yyyy)</b></font>
            </td>              
        </tr> 
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td bgcolor="#585858">
                <font color="white"><b>TEST SCORE</b></font>
            </td>      
            <td>
                <font color="#A4A4A4" size="2"><b>Test Used</b></font>
            </td>        
            <td>
                <font color="#A4A4A4" size="2"><b>Test Results<br>(Standar Deviation)</b></font>
            </td>             
            <td>
                <font color="#A4A4A4" size="2"><b>Test Result<br>(Age Equivalency)</b></font>
            </td>  
            <td>
                <input type="checkbox"><font color="#A4A4A4" size="2"><b>Month</b></font><br><input type="checkbox"><font color="#A4A4A4" size="2"><b>Year<br></b></font>
            </td>             
        </tr>
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Note/Comments</b></font><br><b>Comments</b>
            </td>                           
        </tr>  
        <tr bgcolor="#585858">
            <td>
                <font color="white"><b>STEP 2: For Extended Episode Fee (EEF) Request (Alter Completion of Step 1 above, If patients needs continued therapy, complete below and fax to ATA-FL)</b></font>
            </td>                             
        </tr> 
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Since evaluation date: # visits scheduled</b></font>
            </td>      
            <td>
                <font color="#A4A4A4" size="2"><b>Number of visits Attended</b></font>
            </td>        
            <td>
                <font color="#A4A4A4" size="2"><b>Date of Last Visit (mm/dd/yyyy)</b></font>
            </td>                                    
        </tr>   
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr bgcolor="#585858">
            <td>
                <font color="white"><b>STEP 3: Applicable if patienty is in continuous therapy for 4 months (You may request an additional EEF Level by submiting the following information 4 months after eval date)</b></font>
            </td>                             
        </tr>
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Since evaluation date: # visits scheduled</b></font>
            </td>      
            <td>
                <font color="#A4A4A4" size="2"><b>Number of visits Attended</b></font>
            </td>        
            <td>
                <font color="#A4A4A4" size="2"><b>Date of Last Visit (mm/dd/yyyy)</b></font>
            </td>                                    
        </tr> 
</table>
<table cellpadding="0" cellspacing="0" align="center" width="100%" border="1">         
        <tr>
            <td>
                <font color="#A4A4A4" size="2"><b>Additional Information</b></font><br><b>'.$_POST['additional_information'].'</b>
            </td>                           
        </tr>          
        
    </table>
            </td>
        </tr>
    </table>    
        ';


$ruta_insercion_pdf = '../../../pdf_form_pif/'.$identificador.'.pdf';

$update_pdf = "UPDATE tbl_form_pif SET route_pdf = '".$ruta_insercion_pdf."' WHERE id_form_pif = ".$identificador;
ejecutar($update_pdf,$conexion);    

$dompdf = new DOMPDF();                
$dompdf->load_html($html);
$dompdf->render();
$pdf = $dompdf->output(); 

file_put_contents($ruta_insercion_pdf, $pdf);
chmod ($ruta_insercion_pdf,0777);                    
   
$json_resultado['resultado'] = 'insertado';


echo json_encode($json_resultado);
	

