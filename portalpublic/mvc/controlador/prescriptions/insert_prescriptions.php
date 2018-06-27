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

 $datos_formulario = explode('&',$_POST['campos_formulario']);
list($ig,$patients_id) = explode('=',$datos_formulario[0]);
list($ig,$discipline_id) = explode('=',$datos_formulario[1]);
list($ig,$companies_id) = explode('=',$datos_formulario[2]);
list($ig,$diagnostic_id) = explode('=',$datos_formulario[3]);
list($ig,$Issue_date) = explode('=',$datos_formulario[4]);
list($ig,$End_date) = explode('=',$datos_formulario[5]);
list($ig,$physician_id) = explode('=',$datos_formulario[6]);


//discipline_modal

$conexion = conectar();


$discipline_sql = "SELECT *,DisciplineId as id_disciplina FROM discipline WHERE Name = '".$discipline_id."';";
$resultadoDiscipline = ejecutar($discipline_sql,$conexion); 
while($row = mysqli_fetch_array($resultadoDiscipline)){
    $id_disciplina = $row['id_disciplina'];
}

if(!isset($_FILES['file-1']['name'][0])){
        echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
}else
if($_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
}else        
if(!isset($_FILES['file-1']['name'][0])){
        echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
}else
if($_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
}else{  
        $sql  = "SELECT * FROM patients "
            . "WHERE true AND id = ".$patients_id.";"; 
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

        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 1";
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

$selectVerificar = "SELECT *, discipline.DisciplineID as id_disciplina FROM prescription "
        . " LEFT JOIN discipline ON discipline.DisciplineID = prescription.Discipline"
        . " WHERE Patient_id = '".$patients_id."'  AND discipline.Name= '".$discipline_id."' ;";
$resultadoVerificar = ejecutar($selectVerificar,$conexion);
$row_cnt = mysqli_num_rows($resultadoVerificar);

if($row_cnt > 0){
	$update="UPDATE prescription SET status = 3 WHERE ".
	"Patient_id='".$patients_id."'  AND Discipline= '".$id_disciplina."' ;"; 
	ejecutar($update,$conexion);
}


$physician = "SELECT * FROM physician WHERE NPI = '".$physician_id."';";
$resultadoPhysician = ejecutar($physician,$conexion);					
while($row = mysqli_fetch_array($resultadoPhysician)){
    $Name = $row['Name'];
}
$discipline_sql = "SELECT *,DisciplineId as id_disciplina FROM discipline WHERE Name = '".$discipline_id."';";
$resultadoDiscipline = ejecutar($discipline_sql,$conexion);	
while($row = mysqli_fetch_array($resultadoDiscipline)){
    $id_disciplina = $row['id_disciplina'];
}
        
 $info_pat="SELECT * FROM patients
                    WHERE  id =".$patients_id;
 $info_pat_id = ejecutar($info_pat,$conexion);
 while ($row=mysqli_fetch_array($info_pat_id)) {
     $salida['Pat_id'] = $row['Pat_id'];
 }
        
$insert="INSERT into prescription (Patient_id, patient_name, Discipline, Diagnostic , Issue_date , End_date, Physician_name , Physician_NPI , Table_name, status) 
values ('".$salida['Pat_id']."','".$Patient_name."','".$id_disciplina."','".$diagnostic_id."','".date('Y-m-d', strtotime($Issue_date))."','".date('Y-m-d', strtotime($End_date))."','".$Name."','".trim($physician_id)."', '".$companies_id."' , '1');";
ejecutar($insert,$conexion);

//consulto el ultimo id registrado                         
$sql_prescription  = "SELECT max(id_prescription) as identificador FROM prescription;";
$resultado_prescription = ejecutar($sql_prescription,$conexion); 
$j = 0;      
$id_prescription = '';
while($datos_prescription = mysqli_fetch_assoc($resultado_prescription)) {            
    $id_prescription = $datos_prescription['identificador'];
    $j++;
}

//guardo auditoria en tbl_audit_generales
$fecha=date('Y-m-d H:i:s');
$audit_general="INSERT INTO tbl_audit_generales(user_id,Pat_id,type,created_at) VALUES('".$_SESSION['user_id']."','".$id_prescription."','5','".$fecha."')";
//ejecutar($audit_general,$conexion);


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// UPDATE DE REPORTE NUEVO CUANDO LLEGA LA PRESCRIPTION PORNER STATUS THE WAITING =0 Y STATUS DE EVAL_AUTH = 1 SI NECESITA OR  EVAL_PATIENT = 1 SI NO NECESITA 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 $consulta_if_need_auth_eval = " SELECT * 
                                FROM  patients
                                    JOIN tbl_patient_seguros s on s.patient_id=patients.id
                                        JOIN tbl_seguros_table st on s.insure_id=st.id_seguros
                                            AND st.id_seguros_type_person=patients.id_seguros_type_person
                                                AND st.id_seguros_type_task='2'
                                                    AND st.discipline='".$id_disciplina."'
                                WHERE patients.id='".$patients_id."' ";

//QUERY DE ARRIBA CORREGIDO 
$result_consulta_if_need_auth_eval = ejecutar($consulta_if_need_auth_eval,$conexion);
$row_cnt_need_eval = mysqli_num_rows($result_consulta_if_need_auth_eval);
if($row_cnt_need_eval > 0){
//
//                    ////////////////// consulta seguros que no necesites la primera vez que vienen
//
//                    // ESTA CONSULTA ES PARA LOS SEGUROS QUE SON LAS PRIMERAS VISITAS NO NECESITAN AUTHS
                $consulta_si_es_nuevo = "
                    SELECT * 
                        FROM patients p
                            JOIN tbl_patient_seguros s on s.patient_id=p.id
                                JOIN tbl_seguro_special_relation sr on s.insure_id=sr.id_seguro
                                AND sr.id_seguro_special='1'
                                    JOIN tbl_seguro_special_relation_discipline sdr ON sdr.id_seguro_special_relation= sr.id_seguro_special_relation
                                    AND sdr.discipline='".$id_disciplina."'                
                                        LEFT JOIN careplans on p.id=careplans.Patient_ID
                                        AND careplans.Discipline='".$id_disciplina."'         
                        WHERE
                            p.active=1
                                AND careplans.Patient_ID is null 
                                    AND p.id='1'  ";

///  QUERY DE ARRIBA CORREGIDO 

                                        $resultado_nuevo = ejecutar($consulta_si_es_nuevo,$conexion);
                                        $datos_verificar_nuevos = null;
                while ($row=mysqli_fetch_array($resultado_nuevo)) { 
                      $datos_verificar_nuevos = $row['id'];
                      } 

         if($datos_verificar_nuevos != null) {

    $update_new_report="UPDATE patients_copy set waiting_prescription_".$discipline_id."='0' , eval_patient_".$discipline_id."='1' 
                        , waiting_prescription_date_".$discipline_id." = '0000-00-00'
                        WHERE id='".$patients_id."'   "; 
    ejecutar($update_new_report,$conexion);

    }else{
        $update_new_report="UPDATE patients_copy set waiting_prescription_".$discipline_id."='0' , eval_auth_".$discipline_id."='1' 
                            , waiting_prescription_date_".$discipline_id." = '0000-00-00'
                            WHERE id='".$patients_id."'   "; 
    ejecutar($update_new_report,$conexion);

    }

    
}else{
    $update_new_report="UPDATE patients_copy set waiting_prescription_".$discipline_id."='0' , eval_patient_".$discipline_id."='1' 
                        , waiting_prescription_date_".$discipline_id." = '0000-00-00'
                        WHERE id='".$patients_id."'   "; 
    ejecutar($update_new_report,$conexion);
}


///  UPDATE PARA LAS NOTAS POR TITULOS A STATUS = 0
 $update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$patients_id."'  AND discipline= '".$discipline_id."' 
                                                        AND (type_report = 'POC EXPIRED NO PRESCRIPTION' OR type_report = 'WAITING FOR PRESCRIPTION')";  
ejecutar($update_notes,$conexion);

$max_id_prescription = "SELECT MAX(id_prescription) AS id_prescription FROM prescription";
$result_prescription = mysqli_query($conexion, $max_id_prescription);
$id_tbl_prescription = 0;
while($row = mysqli_fetch_array($result_prescription,MYSQLI_ASSOC)){
        $id_tbl_prescription = $row['id_prescription'];
}

$sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
        . " VALUES ('1','1',".$id_tbl_prescription.",'".$patients_id."','".$ruta1."')";
ejecutar($sql_insert_document,$conexion);

echo 'OK';


