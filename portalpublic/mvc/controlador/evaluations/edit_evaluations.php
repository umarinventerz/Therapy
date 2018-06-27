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
    
    if(isset($_FILES['file-1']['name'][0]) && $_FILES['file-1']['type'][0] != 'application/pdf'){
            echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
            echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
    }else{  
        if(isset($_FILES['file-1']['name'][0])){
            $sql  = "SELECT * FROM prescription "
            . "WHERE true AND id_prescription = ".$_POST['prescription_id_eval_hidden'].";"; 
            
            $resultado_prescription = ejecutar($sql,$conexion); 
             while($row = mysqli_fetch_assoc($resultado_prescription)) {
                $Patient_name = $row["Patient_name"];
                $id_patient = $row["Patient_ID"];
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

    }
    $field = '';
    if($_POST['templateCkEditor'] == 'template'){
        $field = ', id_template';
        $value = '1, ckeditor = null ';
        //consulto si hay registros        
        $components="SELECT COUNT(*) AS contador FROM tbl_modal_template"
            . " WHERE type_modal=1 AND id_modal=".$_POST['id_eval'];
        $resultado_components = ejecutar($components,$conexion);
        
         while($dat = mysqli_fetch_assoc($resultado_components)) {
            $array['contador']=$dat['contador'];

        }
        if($array['contador']==0){
        
            $explode_componentes=explode(',',$_POST['componentes']);
            for($i=0;$i<count($explode_componentes);$i++){
                $data_ex=explode('_',$explode_componentes[$i]);
                $array_componentes[]=$data_ex[1];
            }        
            $explode_html_componentes=explode(',',$_POST['html_select']);
            for($i=0;$i<count($explode_html_componentes);$i++){            
                $array_html[]=$explode_html_componentes[$i];
            }        
            $fecha=date('Y-m-d H:i:s');
            for($i=0;$i<count($array_componentes);$i++){            
                $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(1,".$_POST['id_eval'].",".$array_componentes[$i].",'".$array_html[$i]."',".$_SESSION['user_id'].",'".$fecha."')";
                ejecutar($insert,$conexion);
            }
        }else{
            $delete="DELETE FROM tbl_modal_template WHERE type_modal=1 AND id_modal=".$_POST['id_eval'];
            ejecutar($delete,$conexion);
            //inserto nuevamente
            $explode_componentes=explode(',',$_POST['componentes']);
            for($i=0;$i<count($explode_componentes);$i++){
                $data_ex=explode('_',$explode_componentes[$i]);
                $array_componentes[]=$data_ex[1];
            }        
            $explode_html_componentes=explode(',',$_POST['html_select']);
            for($i=0;$i<count($explode_html_componentes);$i++){            
                $array_html[]=$explode_html_componentes[$i];
            }        
            $fecha=date('Y-m-d H:i:s');
            for($i=0;$i<count($array_componentes);$i++){            
                $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(1,".$_POST['id_eval'].",".$array_componentes[$i].",'".$array_html[$i]."',".$_SESSION['user_id'].",'".$fecha."')";
                ejecutar($insert,$conexion);
            }
        }
        
    }
    else{
        $field = ', ckeditor';
        $value = '\''.urldecode($_POST['editor_evaluaciones'])."', id_template = null ";
    }
    //signature
   
    if($_POST['firma_eval']!=''){
            $therapista_signed=1;
            $fecha_firma='\''.date('Y-m-d H:i:s').'\'';
            $latitude=$_POST['latitud_firma_eval'];
            $longitud=$_POST['longitud_firma_eval'];  
            $therapista_signature=$_POST['ruta_firma_eval'];
    }else{
       $therapista_signed=0;
        $fecha_firma='null';
        $latitude=0.00;
        $longitud=0.00;  
        $therapista_signature=""; 
    }
    if($latitude == ''){
        $latitude = 0.00;        
    }
    if($longitud == ''){
        $longitud = 0.00;
    }
    
    $variable=0;
    $from = urldecode($_POST['from_eval']);
    
    $to = urldecode($_POST['to_eval']);

    $date_of_signed = urldecode($_POST['date_of_signed']);
    
    if($_POST['cpt'] == ''){
        $_POST['cpt'] = $_POST['cpt_h'];
    }
    
    //echo $_POST['field_disabled'];
    if($_POST['field_disabled']=='false'){
     $update=" UPDATE tbl_evaluations SET "
        . "`from` = '".$from."', `to` = '".$to."' , "
        . "therapist_signed = '".$therapista_signed."', "
        . "therapist_signed_date = ".$fecha_firma.", "
        . "therapist_signed_location_latitude = '".$latitude."', "
        . "therapist_signed_location_longitude = '".$longitud."', "
        . "therapist_signature = '".$therapista_signature."', "
        . "md_signed = '".$_POST['md_signed']."', diagnostic = '".$_POST['diagnostic_id_eval']."', cpt = '".$_POST['cpt']."', date_signed = '".$date_of_signed."', "
        . "units = '".$_POST['unid_of_eval']."',company = '".$_POST['company_id']."', minutes = '".$_POST['min_of_eval']."'$field=".$value.""
        . "WHERE id = ".$_POST['id_eval'];    
    }else{
        
      $update="UPDATE tbl_evaluations SET  md_signed = '".$_POST['md_signed']."', date_signed = '".$date_of_signed."' WHERE id='".$_POST['id_eval']."'  ;" ;
    }
    //die;
    ejecutar($update,$conexion);


///////////////////////////////////////////////////
    // CONSULTA DESPUES DEL UPDATE PARA CAPTAR VARIABLES
    //////////////////////////////////////////////////
    $reportes = "SELECT e.patient_id as patient_id, e.therapist_signed as therapist_signed , d.Name as Name, e.md_signed as md_signed, e.discipline_id as discipline_id
                FROM tbl_evaluations e  
                LEFT JOIN discipline d on  e.discipline_id=d.DisciplineId
                WHERE e.id='".$_POST['id_eval']."' ";
    $resultado_reportes = ejecutar($reportes,$conexion); 
    while($row = mysqli_fetch_array($resultado_reportes)){
    $id_pat_1 = $row['patient_id'];
    $signed_1 = $row['therapist_signed'];
    $discipline_1 = $row['Name'];
    $discipline_id_1 = $row['discipline_id'];
    $md_signed_1 = $row['md_signed'];
    }

 ////////       // IF para saber si ya la firmo el therapista Y pasar el reporte a pedir firma del Doctor
    if($signed_1 == '1'){
    $update_new_report= "UPDATE patients_copy SET eval_patient_".$discipline_1." ='0' , doctor_signature_".$discipline_1." = '1' 
                            WHERE id='".$id_pat_1."' and eval_patient_".$discipline_1." ='1'  "; 
        $resultado2 = ejecutar($update_new_report,$conexion); 
    }
////////        // IF para saber si LA FIRMA del DOCTOR ya llego 
    if($md_signed_1 == '1' ){
        
            $consulta_if_need_auth_eval = " SELECT * 
                                FROM  patients
                                    JOIN tbl_patient_seguros s on s.patient_id=patients.id
                                        JOIN tbl_seguros_table st on s.insure_id=st.id_seguros
                                            AND st.id_seguros_type_person=patients.id_seguros_type_person
                                                AND st.id_seguros_type_task='4'
                                                    AND st.discipline='".$discipline_id_1."'
                                WHERE patients.id='".$id_pat_1."'  ";
$result_consulta_if_need_auth_eval = ejecutar($consulta_if_need_auth_eval,$conexion);
$row_cnt_need_eval = mysqli_num_rows($result_consulta_if_need_auth_eval);
if($row_cnt_need_eval > 0){

                    // ESTA CONSULTA ES PARA LOS SEGUROS QUE SON LAS PRIMERAS VISITAS NO NECESITAN AUTHS
                $consulta_si_es_nuevo = "SELECT p.id 
                                            FROM  patients p
                                                JOIN tbl_patient_seguros s on s.patient_id=p.id
                                                    and s.`status`=1
                                                JOIN tbl_seguro_special_relation sr on s.insure_id=sr.id_seguro
                                                    AND sr.id_seguro_special='1'
                                                JOIN tbl_seguro_special_relation_discipline sdr 
                                                    ON sdr.id_seguro_special_relation= sr.id_seguro_special_relation
                                                    AND sdr.discipline='".$discipline_id_1."'                
                                                LEFT join tbl_visits v on p.id=v.pat_id
                                                    AND v.visit_discip_id='".$discipline_id_1."'
                                                    and v.deleted='0'
                        
                                        WHERE
                                            p.active=1
                                                AND v.id is null 
                                                    AND   p.id='".$id_pat_1."'   ";                                      
                $resultado_nuevo = ejecutar($consulta_si_es_nuevo,$conexion);

                $datos_verificar_nuevos = null;
                while ($row=mysqli_fetch_array($resultado_nuevo)) { 
                      $datos_verificar_nuevos = $row['id'];
                      } 

                      // ESTA CONSULTA ES PARA CUANDO LAS AUTH VIENEN JUNTAS
                      $consulta_si_juntas = "SELECT * 
                                            FROM  patients p
                                                JOIN tbl_patient_seguros s on s.patient_id=p.id
                                                    and s.`status`=1
                                                JOIN patient_insurers_auths pa on pa.seguro_id=s.id
                                                    AND pa.discipline_id='".$discipline_id_1."'
                                                    AND pa.cpt_type='TX'
                                                    and pa.`status`='1'
                                                LEFT JOIN tbl_visits v on v.auth_id=pa.id
                                                    and v.visit_discip_id='".$discipline_id_1."'
                                            WHERE
                                                    p.active=1
                                                and  v.id is null
                                                AND p.id='".$id_pat_1."'   ";
                                        $resultado_juntas = ejecutar($consulta_si_juntas,$conexion);
                                        $datos_juntas = null;
                while ($row=mysqli_fetch_array($resultado_juntas)) { 
                      $datos_juntas = $row['id'];
                      } 

    
         if($datos_verificar_nuevos != null || $datos_juntas != null){       

    $update_new_report="UPDATE patients_copy SET waiting_signature_".$discipline_1."='0' , ready_treatment_".$discipline_1."='1' , hold_".$discipline_1."='0'
                        , waiting_signature_date_".$discipline_1." = '0000-00-00'    WHERE id='".$id_pat_1."' and waiting_signature_".$discipline_1."='1'   "; 
        ejecutar($update_new_report,$conexion);

     } else {  

      $update_new_report="UPDATE patients_copy SET waiting_signature_".$discipline_1."='0' , tx_auth_".$discipline_1."='1' , waiting_tx_auth_".$discipline_1."='0'
                             , waiting_signature_date_".$discipline_1." = '0000-00-00'       WHERE id='".$id_pat_1."' and waiting_signature_".$discipline_1."='1'   "; 
        ejecutar($update_new_report,$conexion);
            }

}else{
    $update_new_report="UPDATE patients_copy SET waiting_signature_".$discipline_1."='0' , ready_treatment_".$discipline_1."='1', hold_".$discipline_1."='0'
    ,  waiting_signature_date_".$discipline_1." = '0000-00-00' WHERE id='".$id_pat_1."' and waiting_signature_".$discipline_1."='1'   "; 
    ejecutar($update_new_report,$conexion);
}


    ////fin de if de cuando llega firma de doctor



}
    ///////////////////////////////////






    $amendment = -1;
    $query_amendment = "SELECT id_amendment FROM tbl_amendment WHERE id_evaluations=".$_POST['id_eval'];
    $resultado_query_amendment = ejecutar($query_amendment,$conexion);					
    while($row_amendment = mysqli_fetch_array($resultado_query_amendment)){
        $amendment = $row_amendment['status_id'];
    }
    if($_POST['amendment'] == 1){
             
        if($amendment != -1){
            $update_amendment = 'UPDATE tbl_amendment SET ckeditor = \''.$_POST['editor_amendment_evaluaciones'].'\', id_template = \''.$_POST['template_amendment_id'].'\'WHERE id_evaluations = '.$_POST['id_eval'].';';
            $resultado_update_amendment = ejecutar($update_amendment,$conexion);
        }else{
            $insert_amendment = 'INSERT INTO tbl_amendment (id_evaluations,ckeditor,id_template) VALUES ('.$_POST['id_eval'].',\''.$_POST['editor_amendment_evaluaciones'].'\',\''.$_POST['template_amendment_id'].'\')';
            $resultado_insert_amendment = ejecutar($insert_amendment,$conexion);
        }
        
        //consulto si hay registros        
        $components_amend="SELECT COUNT(*) AS contador FROM tbl_modal_template"
            . " WHERE type_modal=0 AND id_modal=".$_POST['id_eval'];
        $resultado_components_amend = ejecutar($components_amend,$conexion);
        
         while($dat_amend = mysqli_fetch_assoc($resultado_components_amend)) {
            $array['contador']=$dat_amend['contador'];

        }
        if($array['contador']==0){
        
            $explode_componentes_amend=explode(',',$_POST['componentes_amend']);
            for($i=0;$i<count($explode_componentes_amend);$i++){
                $data_ex_amend=explode('_',$explode_componentes_amend[$i]);
                $array_componentes_amend[]=$data_ex_amend[1];
            }        
            $explode_html_componentes_amend=explode(',',$_POST['html_select_amend']);
            for($i=0;$i<count($explode_html_componentes_amend);$i++){            
                $array_html_amend[]=$explode_html_componentes_amend[$i];
            }        
            $fecha=date('Y-m-d H:i:s');
            for($i=0;$i<count($array_componentes_amend);$i++){            
                $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(0,".$_POST['id_eval'].",".$array_componentes_amend[$i].",'".$array_html_amend[$i]."',".$_SESSION['user_id'].",'".$fecha."')";
                ejecutar($insert,$conexion);
            }
        }else{
            $delete_amend="DELETE FROM tbl_modal_template WHERE type_modal=0 AND id_modal=".$_POST['id_eval'];
            ejecutar($delete_amend,$conexion);
            //inserto nuevamente
            $explode_componentes_amend=explode(',',$_POST['componentes_amend']);
            for($i=0;$i<count($explode_componentes_amend);$i++){
                $data_ex_amend=explode('_',$explode_componentes_amend[$i]);
                $array_componentes_amend[]=$data_ex_amend[1];
            }        
            $explode_html_componentes_amend=explode(',',$_POST['html_select_amend']);
            for($i=0;$i<count($explode_html_componentes_amend);$i++){            
                $array_html_amend[]=$explode_html_componentes_amend[$i];
            }        
            $fecha=date('Y-m-d H:i:s');
            for($i=0;$i<count($array_componentes_amend);$i++){            
                $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(0,".$_POST['id_eval'].",".$array_componentes_amend[$i].",'".$array_html_amend[$i]."',".$_SESSION['user_id'].",'".$fecha."')";
                ejecutar($insert,$conexion);
            }
        }
    }

    //para actualizar status
    $status_p = "SELECT status_id FROM tbl_evaluations WHERE id=".$_POST['id_eval'];
    $resultado_status_p = ejecutar($status_p,$conexion);					
    while($row_status = mysqli_fetch_array($resultado_status_p)){
        $status_c = $row_status['status_id'];
    }
    if($status_c!=$_POST['status_eval']){

        //consulto si ya el status lo tiene otra prescription

        $sql_status="SELECT count(*) as contador from prescription 
                     WHERE Patient_ID = '".$_POST['patients_eval_hidden']."' 
                     AND Discipline = '".$_POST['discipline_id_eval_hidden']."' 
                     AND status='".$_POST['status_eval']."' 
                     AND status!='3'
                     AND (deleted = 0 OR deleted IS NULL)  " ; 
        $resultado_sql_status= ejecutar($sql_status,$conexion);
        while($row_sql_staus = mysqli_fetch_array($resultado_sql_status)){
            $contador_status = $row_sql_staus['contador'];
        }

        if($contador_status==0){     

            //actualizo las tablas dependientes
            $actualizacion_status="select P.id_prescription,E.id as id_eval, C.id_careplans from prescription P
                                    left join tbl_evaluations E ON (E.id_prescription=P.id_prescription)
                                    left join careplans C ON (C.evaluations_id=E.id) WHERE E.id=".$_POST['id_eval'];
            $resultado_actualizacion_status = ejecutar($actualizacion_status,$conexion);					
            
            while($row_status_act = mysqli_fetch_array($resultado_actualizacion_status)){
                $id_prescription_act = $row_status_act['id_prescription'];
                $id_eval_act = $row_status_act['id_eval'];
                $id_careplans_act = $row_status_act['id_careplans'];
            }
            if($_POST['status_eval'] != ''){
                if($id_prescription_act != ''){
                    $update_prescription="UPDATE prescription SET "
                    . "status = ".$_POST['status_eval']." "        
                    . "WHERE id_prescription = ".$id_prescription_act;
                    ejecutar($update_prescription,$conexion);
                }                
                if($id_eval_act != ''){
                    $update_eval="UPDATE tbl_evaluations SET "
                        . "status_id = ".$_POST['status_eval']." "        
                        . "WHERE id = ".$id_eval_act;
                    ejecutar($update_eval,$conexion);
                }
                if($id_careplans_act != ''){
                    $update_poc="UPDATE careplans SET "
                    . "status = ".$_POST['status_eval']." "        
                    . "WHERE id_careplans = ".$id_careplans_act;
                    ejecutar($update_poc,$conexion);
                }
            }
        }else{
            $variable=$variable+1;

        }

    }

    if($ruta1 != ''){
        $sql_delete = "DELETE FROM tbl_documents WHERE id_type_document = 3 AND id_type_person = 1 AND id_person = '".$id_patient."' AND id_table_relation = ".$_POST['id_eval'];
        ejecutar($sql_delete,$conexion);

        $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
                . " VALUES ('3','1',".$_POST['id_eval'].",'".$id_patient."','".$ruta1."')";
        ejecutar($sql_insert_document,$conexion);
    }

    if($variable==0){
        $mensaje='ok';
    }else{
        $mensaje='duplicated';
    }
    $arreglo=array('mensaje'=>$mensaje,
                   'disciplina'=>$_POST['discipline_id_eval_hidden'],
                   'pat_id'=>$_POST['patients_eval_hidden'],
                   'flag' => $_GET['flag']
                );
              
echo json_encode($arreglo);


