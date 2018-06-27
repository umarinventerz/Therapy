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
list($ig,$id_tbl_prescription) = explode('=',$datos_formulario[0]);
list($ig,$patients_id) = explode('=',$datos_formulario[1]);
list($ig,$discipline_id) = explode('=',$datos_formulario[2]);
list($ig,$companies_id) = explode('=',$datos_formulario[3]);
list($ig,$diagnostic_id) = explode('=',$datos_formulario[4]);
list($ig,$Issue_date) = explode('=',$datos_formulario[5]);
list($ig,$End_date) = explode('=',$datos_formulario[6]);
list($ig,$physician_id) = explode('=',$datos_formulario[7]);
list($ig,$status_id_edit) = explode('=',$datos_formulario[8]);

$ruta1 = '';
$conexion = conectar();

 $info_pat="SELECT * FROM prescription
                    WHERE  id_prescription =".$id_tbl_prescription;
 $info_pat_id = ejecutar($info_pat,$conexion);
 while ($row=mysqli_fetch_array($info_pat_id)) {
     $patients_id = $row['Patient_ID'];
 }



$sql  = "SELECT * FROM patients  WHERE true AND Pat_id = ".$patients_id;
        $resultado_patiens = ejecutar($sql,$conexion); 
         while($row = mysqli_fetch_assoc($resultado_patiens)) {
            $Patient_name = trim($row["Last_name"]).', '.trim($row["First_name"]);
        }

    $prescription = [];
    if($status_id_edit == 2){
        $sql  = "SELECT * FROM prescription WHERE Patient_ID = ".$patients_id." AND Discipline = '".$discipline_id."' AND status = 2;";
        $resultado = ejecutar($sql,$conexion);       
        while($datos = mysqli_fetch_assoc($resultado)) {
            $prescription = $datos;
        }
    }

if(!empty($prescription)){
    echo 'active';    
}else{
    $variable=0;

    if(isset($_FILES['file-1']['name'][0]) && $_FILES['file-1']['type'][0] != 'application/pdf'){
            echo 'extension';
    }else{     
        if(isset($_FILES['file-1']['name'][0])){
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
    }

    $physician = "SELECT * FROM physician WHERE NPI = '".$physician_id."';";
    $resultadoPhysician = ejecutar($physician,$conexion);					
    while($row = mysqli_fetch_array($resultadoPhysician)){
        $Name = $row['Name'];
    }

    $update="UPDATE prescription SET "
            . "patient_name='".$Patient_name."', "
            . "Discipline ='".$discipline_id."', "
            . "Diagnostic ='".$diagnostic_id."', "
            . "Issue_date ='".$Issue_date."', "
            . "End_date = '".$End_date."', "
            . "Physician_name ='".$Name."', "
            . "Physician_NPI ='".$physician_id."', "
            . "Table_name = '".$companies_id."' "             
        . "WHERE id_prescription = ".$id_tbl_prescription;

    ejecutar($update,$conexion);

    //para actualizar status
    $status_p = "SELECT status FROM prescription WHERE id_prescription=".$id_tbl_prescription;
    $resultado_status_p = ejecutar($status_p,$conexion);					
    while($row_status = mysqli_fetch_array($resultado_status_p)){
        $status_c = $row_status['status'];
    }
    if($status_c!=$status_id_edit){
        
        //consulto si ya el status lo tiene otra prescription
        
        $sql_status="SELECT count(*) as contador from prescription 
                            WHERE Patient_ID = '".$patients_id."'
                            AND Discipline = '".$discipline_id."' 
                            AND status='".$status_id_edit."'
                            AND status!='3'
                            AND (deleted = 0 OR deleted IS NULL)  "; 
        $resultado_sql_status= ejecutar($sql_status,$conexion);
        while($row_sql_staus = mysqli_fetch_array($resultado_sql_status)){
            $contador_status = $row_sql_staus['contador'];
        }
       
        if($contador_status==0){     
        
            //actualizo las tablas dependientes  si y solo si hayan evaluaciones asociadas a esas prescripcionesss    ojo con eso
            $actualizacion_status="select P.id_prescription,E.id as id_eval, C.id_careplans from prescription P
                                    left join tbl_evaluations E ON (E.id_prescription=P.id_prescription)
                                    left join careplans C ON (C.evaluations_id=E.id) 
                                    WHERE P.id_prescription=".$id_tbl_prescription;
            $resultado_actualizacion_status = ejecutar($actualizacion_status,$conexion);					
            while($row_status_act = mysqli_fetch_array($resultado_actualizacion_status)){
                $id_prescription_act = $row_status_act['id_prescription'];
                $id_eval_act = $row_status_act['id_eval'];
                $id_careplans_act = $row_status_act['id_careplans'];
            }

            $update_prescription="UPDATE prescription SET "
                . "status = ".$status_id_edit." "        
                . "WHERE id_prescription = ".$id_prescription_act;
            ejecutar($update_prescription,$conexion);

            $update_eval="UPDATE tbl_evaluations SET "
                . "status_id = ".$status_id_edit." "        
                . "WHERE id = ".$id_eval_act;
            ejecutar($update_eval,$conexion);

            $update_poc="UPDATE careplans SET "
                . "status = ".$status_id_edit." "        
                . "WHERE id_careplans = ".$id_careplans_act;


            ejecutar($update_poc,$conexion);
        }else{
            $variable=$variable+1;
            
        }
            
    }
    
    
    if($ruta1 != ''){
        $sql_delete = "DELETE FROM tbl_documents WHERE id_type_document = 1 AND id_type_person = 1 AND id_person = '".$patients_id."' AND id_table_relation = ".$id_tbl_prescription;
        ejecutar($sql_delete,$conexion);

        $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
                . " VALUES ('1','1',".$id_tbl_prescription.",'".$patients_id."','".$ruta1."')";
        ejecutar($sql_insert_document,$conexion);
    }
    if($variable==0){
        echo 'ok';
    }else{
        echo "duplicated";
    }
}

