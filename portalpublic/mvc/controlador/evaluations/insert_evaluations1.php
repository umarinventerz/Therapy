 <?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
//	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
//		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
//		echo '<script>window.location="../../home/home.php";</script>';
//	}
}
$conexion = conectar();
$datos_formulario = explode('&',$_POST['campos_formulario']);
foreach($datos_formulario as $df){    
    list($key,$valor)= explode('=',$df);
    $_POST[$key] = $valor;
}

if(!isset($_FILES['file-1']['name'][0])){
        echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index_1.php";</script>';
        $flag = 1;
}else
if($_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index_1.php";</script>';
        $flag = 1;
}else{  
        $sql  = "SELECT * FROM patients "
            . "WHERE true AND id = ".$_POST['patients_id_eval_hidden'].";"; 
        $resultado_patiens = ejecutar($sql,$conexion); 
         while($row = mysqli_fetch_assoc($resultado_patiens)) {
            $Patient_name = trim($row["Last_name"]).', '.trim($row["First_name"]);
        }
        
        $patient_name_directorio = str_replace(',','',str_replace(' ','_',$Patient_name));
                        
        if (!file_exists('../../../patients')) {        
            mkdir('../../../patients', 0777, false);  
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
            mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
        }    

        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 3";
        $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                 $reporte_tipo_documento = $row["type_documents"];
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento)) {        
            mkdir('../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento, 0777, false);  
        }

        $temporal = $_FILES['file-1']['tmp_name'][0];
        $vowels = array(" ", "-","_");
        $nombre_archivo = str_replace($vowels,"",$_FILES['file-1']['name'][0]);
        $id=fopen($temporal,'r'); 
        if (is_uploaded_file($temporal)) {
                $ruta = '../../../patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
                $ruta1 = 'patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
                move_uploaded_file($temporal,$ruta);
                chmod($ruta, 0777);                
        }  
    
}

$field = '';
if($_POST['templateCkEditor'] == 'template'){
    $field = ', id_template';
    $value = ','.$_POST['template_id'];
}
else{
    $field = ', ckeditor';
    $value = ',\''.$_POST['editor'].'\'';
}
//signature
if($_POST['firma_eval']!=''){
        $therapista_signed=1;
        $fecha_firma=date('Y-m-d H:i:s');
        $latitude=$_POST['latitud_firma_eval'];
        $longitud=$_POST['longitud_firma_eval'];
        $therapista_signature=$_POST['ruta_firma_eval'];
}else{
   $therapista_signed=0;
    $fecha_firma="";
    $latitude="";
    $longitud="";  
    $therapista_signature=""; 
}
$conexion = conectar();
if($_POST['signatureEvaluationStatus'] == 1){
    $date_signature = 'now()';
}else{
    $date_signature = 'null';
}
$insert="INSERT into tbl_evaluations (`name`, discipline_id, `from` , `to` , id_user, created , diagnostic, status_id, md_signed, 
date_signed, id_prescription, patient_id,company, units, minutes".$field.",therapist_signed,therapist_signed_date,therapist_signed_location_latitude,therapist_signed_location_longitude,therapist_signature) 
values ('".$Patient_name."','".$_POST['discipline_id_eval_hidden']."','".$_POST['from_eval']."',"
    . "'".$_POST['to_eval']."','".$_SESSION['user_id']."','".$_POST['date_of_eval']."','".$_POST['diagnostic_eval_hidden']."',1, '".$_POST['md_signed']."' ,"
    . "'".$_POST['date_of_signed']."',".$_POST['prescription_id_eval_hidden'].",".$_POST['patients_id_eval_hidden'].","
    . "'".$_POST['company_id_eval_hidden']."',".$_POST['unid_of_eval'].",".$_POST['min_of_eval'].$value.",'".$therapista_signed."','".$fecha_firma."','".$latitude."','".$longitud."','".$therapista_signature."');";
ejecutar($insert,$conexion);

$max_id_evaluation = "SELECT MAX(id) AS id_evaluation FROM tbl_evaluations";
$result_evaluation = mysqli_query($conexion, $max_id_evaluation);
$id_tbl_evaluation = 0;
while($row = mysqli_fetch_array($result_evaluation,MYSQLI_ASSOC)){
        $id_tbl_evaluation = $row['id_evaluation'];
}

$sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
        . " VALUES ('3','1',".$id_tbl_evaluation.",'".$_POST['patients_id_eval_hidden']."','".$ruta1."')";
ejecutar($sql_insert_document,$conexion);

//$selectVerificar = "SELECT * FROM prescription WHERE ".
//"Patient_id = '".$patients_id."'  AND Discipline= '".$discipline_id."' ;";
//$resultadoVerificar = ejecutar($selectVerificar,$conexion);					
//$row_cnt = mysqli_num_rows($resultadoVerificar);
//if($row_cnt > 0){
//	$update="UPDATE prescription SET status = 0 WHERE ".
//	"Patient_id='".$patients_id."'  AND Discipline= '".$discipline_id."' ;";; 
//	ejecutar($update,$conexion);
//}
//
//
//$physician = "SELECT * FROM physician WHERE NPI = '".$physician_id."';";
//$resultadoPhysician = ejecutar($physician,$conexion);					
//while($row = mysqli_fetch_array($resultadoPhysician)){
//    $Name = $row['Name'];
//}
//        
//$insert="INSERT into prescription (Patient_id, patient_name, Discipline, Diagnostic , Issue_date , End_date, Physician_name , Physician_NPI , Table_name, status) 
//values ('".$patients_id."','".$Patient_name."','".$discipline_id."','".$diagnostic_id."','".date('Y-m-d', strtotime($Issue_date))."','".date('Y-m-d', strtotime($End_date))."','".$Name."','".trim($physician_id)."', '".$companies_id."' , '1');";
//ejecutar($insert,$conexion);
//
////consulto el ultimo id registrado                         
//$sql_prescription  = "SELECT max(id_prescription) as identificador FROM prescription;";
//$resultado_prescription = ejecutar($sql_prescription,$conexion); 
//$j = 0;      
//$id_prescription = '';
//while($datos_prescription = mysqli_fetch_assoc($resultado_prescription)) {            
//    $id_prescription = $datos_prescription['identificador'];
//    $j++;
//}
//
////guardo auditoria en tbl_audit_generales
//$fecha=date('Y-m-d H:i:s');
//$audit_general="INSERT INTO tbl_audit_generales(user_id,Pat_id,type,created_at) VALUES('".$_SESSION['user_id']."','".$id_prescription."','5','".$fecha."')";
////ejecutar($audit_general,$conexion);
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//// UPDATE DE REPORTE NUEVO CUANDO LLEGA LA PRESCRIPTION PORNER STATUS THE WAITING =0 Y STATUS DE EVAL_AUTH = 1 SI NECESITA OR  EVAL_PATIENT = 1 SI NO NECESITA 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//$consulta_if_need_auth_eval = "SELECT * from  patients
//join seguros on seguros.insurance=patients.Pri_Ins
//join tbl_seguros_table st on seguros.ID=st.id_seguros
//                                 and st.id_seguros_type_person=patients.id_seguros_type_person
//                                 and st.id_seguros_type_task='2'
//                                 and st.discipline='".$discipline_id."'
//where patients.Pat_id='".$patients_id."' ";
//$result_consulta_if_need_auth_eval = ejecutar($consulta_if_need_auth_eval,$conexion);
//$row_cnt_need_eval = mysqli_num_rows($result_consulta_if_need_auth_eval);
//if($row_cnt_need_eval > 0){
//
//                    ////////////////// consulta seguros que no necesites la primera vez que vienen
//
//                    // ESTA CONSULTA ES PARA LOS SEGUROS QUE SON LAS PRIMERAS VISITAS NO NECESITAN AUTHS
//                $consulta_si_es_nuevo = "SELECT * 
//                                            FROM patients p
//                                                    JOIN seguros s on p.Pri_Ins=s.insurance
//                                                    JOIN tbl_seguro_special_relation sr on s.ID=sr.id_seguro
//                                                        AND sr.id_seguro_special='1'
//                                                    JOIN tbl_seguro_special_relation_discipline sdr ON sdr.id_seguro_special_relation= sr.id_seguro_special_relation
//                                                        AND sdr.discipline='".$discipline_id."'                
//                                                #   LEFT JOIN authorizations on authorizations.Pat_id=p.Pat_id
//                                                #       AND authorizations.status='1'
//                                                #       AND authorizations.Discipline='".$discipline_id."'
//                                                    LEFT join careplans on p.Pat_id=careplans.Patient_ID
//                                                        AND careplans.Discipline='".$discipline_id."'
//                        
//                                            WHERE
//                                                p.active=1
//                                                    AND careplans.Patient_ID is null 
//                                                        #AND authorizations.id_authorizations is not null
//                                                            AND p.Pat_id='".$patients_id."'  ";
//                                        $resultado_nuevo = ejecutar($consulta_si_es_nuevo,$conexion);
//                                        $datos_verificar_nuevos = null;
//                while ($row=mysqli_fetch_array($resultado_nuevo)) { 
//                      $datos_verificar_nuevos = $row['id'];
//                      } 
//
//         if($datos_verificar_nuevos != null) {
//
//    $update_new_report="UPDATE patients_copy set waiting_prescription_".$discipline_id."='0' , eval_patient_".$discipline_id."='1' WHERE Pat_id='".$patients_id."'   "; 
//    ejecutar($update_new_report,$conexion);
//
//    }else{
//        $update_new_report="UPDATE patients_copy set waiting_prescription_".$discipline_id."='0' , eval_auth_".$discipline_id."='1' WHERE Pat_id='".$patients_id."'   "; 
//    ejecutar($update_new_report,$conexion);
//
//    }
//
//
//
//}else{
//    $update_new_report="UPDATE patients_copy set waiting_prescription_".$discipline_id."='0' , eval_patient_".$discipline_id."='1' WHERE Pat_id='".$patients_id."'   "; 
//    ejecutar($update_new_report,$conexion);
//}
//
//
/////  UPDATE PARA LAS NOTAS POR TITULOS A STATUS = 0
// $update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$patients_id."'  AND discipline= '".$discipline_id."' 
//                                                        AND (type_report = 'POC EXPIRED NO PRESCRIPTION' OR type_report = 'WAITING FOR PRESCRIPTION')";  
//ejecutar($update_notes,$conexion);
//
//$max_id_prescription = "SELECT MAX(id_prescription) AS id_prescription FROM prescription";
//$result_prescription = mysqli_query($conexion, $max_id_prescription);
//$id_tbl_prescription = 0;
//while($row = mysqli_fetch_array($result_prescription,MYSQLI_ASSOC)){
//        $id_tbl_prescription = $row['id_prescription'];
//}
//
//$sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
//        . " VALUES ('1','1',".$id_tbl_prescription.",'".$patients_id."','".$ruta1."')";
//ejecutar($sql_insert_document,$conexion);

$json_resultado['mensaje'] = 'ok';  
                       
echo json_encode($json_resultado);


