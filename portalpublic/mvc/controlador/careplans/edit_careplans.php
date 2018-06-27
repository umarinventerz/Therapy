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

foreach ($_POST as $key => $post){    
    if(substr($key, 0, 4) == 'long'){        
        if(is_numeric(substr($key, 5, strlen($key)))){
            $long_id[] = substr($key, 5, strlen($key));
        }
    }
    if(substr($key, 0, 5) == 'short'){        
        if(is_numeric(substr($key, 6, strlen($key)))){
            $short_id[] = substr($key, 6, strlen($key));
        }
    }
    if(substr($key, 0, 2) == 'na'){        
        if(is_numeric(substr($key, 3, strlen($key)))){
            $na_id[] = substr($key, 3, strlen($key));
        }
    }
}

$sql  = "SELECT *,c.status as status_poc FROM careplans c"
        . " LEFT JOIN tbl_evaluations e ON e.id = c.evaluations_id"
        . " LEFT JOIN prescription p ON p.id_prescription = e.id_prescription"
        . " WHERE c.id_careplans = ".$_POST['id_careplans_edit'].";"; 
$resultado = ejecutar($sql,$conexion);
$reporte = [];
$i = 1;      
while($datos = mysqli_fetch_assoc($resultado)) {
    $reporte = $datos;
}

if(isset($_FILES['file-1']['name'][0]) && $_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
}else{  
    if(isset($_FILES['file-1']['name'][0])){
            
        $Patient_name = $reporte['Last_name'].', '.$reporte['name'];
        $id_patient = $reporte['Patient_ID'];
        $patient_name_directorio = str_replace(',','',str_replace(' ','_',$Patient_name));

        if (!file_exists('../../../patients')) {        
            mkdir('../../../patients', 0777, false);  
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
            mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
        }    

        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 2";
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
//signature
if($_POST['firma_poc']!=''){
        $therapista_signed=1;
        $fecha_firma="'".date('Y-m-d H:i:s')."'";
        $latitude=$_POST['latitud_firma_poc'];
        $longitud=$_POST['longitud_firma_poc'];  
        $therapista_signature=$_POST['ruta_firma_poc'];
}else{
   $therapista_signed=0;
    $fecha_firma="null";
    $latitude=0;
    $longitud="";  
    $therapista_signature="null"; 
}
$variable=0;

if($_POST['template_id_Poc'] == ''){
    $_POST['template_id_Poc'] = 'null';
}
if($_POST['template_id_Poc'] != ''){
    $_POST['template_id_Poc'] = '1';  
    //consulto si hay registros        
    $components="SELECT COUNT(*) AS contador FROM tbl_modal_template
         WHERE type_modal=2 AND id_modal='".$_POST['id_careplans_edit']."'  ;";
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
            if($array_componentes[$i] == '') $array_componentes[$i] = 'null';
            $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(2,'".$_POST['id_careplans_edit']."','".$array_componentes[$i]."','".$array_html[$i]."','".$_SESSION['user_id']."','".$fecha."')";
            ejecutar($insert,$conexion);
        }
    }else{
        $delete="DELETE FROM tbl_modal_template WHERE type_modal=2 AND id_modal='".$_POST['id_careplans_edit']."' ;";
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
          if($array_componentes[$i] == '') $array_componentes[$i] = 'null';
          $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(2,'".$_POST['id_careplans_edit']."',".$array_componentes[$i].",'".$array_html[$i]."',".$_SESSION['user_id'].",'".$fecha."')";
            ejecutar($insert,$conexion);
        }
    }
}
$update=" UPDATE careplans SET "
. "`POC_due` = '".$_POST['from_poc']."' , "
. "`Re_Eval_due` = '".$_POST['to_poc']."', "

. "diagnostic = '".$_POST['diagnostic_id_poc']."', "
. "id_template = ".$_POST['template_id_Poc'].", "
. "ckeditor = '".$_POST['editor_poc']."', "
. "therapist_signed = '".$therapista_signed."', "
. "therapist_signed_date = '".$fecha_firma."', "
. "therapist_signed_location_latitude = '".$latitude."', "
. "therapist_signed_location_longitude = '".$longitud."', "
. "therapist_signature = '".$therapista_signature."', "
. "Company = '".$_POST['company_id']."'"
. " WHERE id_careplans = '". $_POST['id_careplans_edit']."';";
ejecutar($update,$conexion);

$delete = "DELETE FROM careplan_goals WHERE careplan_id = ".$_POST['id_careplans_edit'];
ejecutar($delete,$conexion);

$k = 0;        
while(isset($long_id[$k])){
    $insert = "INSERT INTO careplan_goals (careplan_id,
    goal_lib_id,
    goal_term_id,
    cpg_area,
    cpg_text,pct_ach) VALUES ('".$_POST['id_careplans_edit']."',"
    .$_POST['long_'.$long_id[$k]].","
    . "3,'".urldecode($_POST['long_area_'.$long_id[$k]])."',"
    . "'".urldecode($_POST['long_library_'.$long_id[$k]])."','".$_POST['long_ach_'.$long_id[$k]]."');";
    ejecutar($insert,$conexion);
    $k++;
}
$k = 0;
while(isset($short_id[$k])){
     $insert = "INSERT INTO careplan_goals (careplan_id,
    goal_lib_id,
    goal_term_id,
    cpg_area,
    cpg_text,pct_ach) 
    VALUES ('".$_POST['id_careplans_edit']."',
    '".$_POST['short_'.$short_id[$k]]."',
    '2',
    '".urldecode($_POST['short_area_'.$short_id[$k]])."',
    '".urldecode($_POST['short_library_'.$short_id[$k]])."',
    '".$_POST['short_ach_'.$short_id[$k]]."');";
    ejecutar($insert,$conexion);
    $k++;
}
$k = 0;
while(isset($na_id[$k])){
    $insert = "INSERT INTO careplan_goals (careplan_id,
    goal_lib_id,
    goal_term_id,
    cpg_area,
    cpg_text,pct_ach) 
    VALUES ('".$_POST['id_careplans_edit']."',
    '".$_POST['na_'.$na_id[$k]]."',
    '1','".urldecode($_POST['na_area_'.$na_id[$k]])."'
    ,'".urldecode($_POST['na_library_'.$na_id[$k]])."','".$_POST['na_ach_'.$long_id[$k]]."');";
    ejecutar($insert,$conexion);
    $k++;
}

//para actualizar status
$status_p = "SELECT status FROM careplans WHERE id_careplans=".$_POST['id_careplans_edit'];
$resultado_status_p = ejecutar($status_p,$conexion);					
while($row_status = mysqli_fetch_array($resultado_status_p)){
    $status_c = $row_status['status'];
}
if($status_c!=$_POST['status_eval']){

    //consulto si ya el status lo tiene otra prescription

    $sql_status="SELECT count(*) as contador from prescription 
                    WHERE Patient_ID = '".$_POST['patient_id']."' 
                    AND Discipline = '".$_POST['discipline_id']."' 
                    AND status=".$_POST['status_eval']." 
                    AND status!='3'
                    AND (deleted = 0 OR deleted IS NULL)"; 
    $resultado_sql_status= ejecutar($sql_status,$conexion);
    while($row_sql_staus = mysqli_fetch_array($resultado_sql_status)){
        $contador_status = $row_sql_staus['contador'];
    }

    if($contador_status==0){     

        //actualizo las tablas dependientes
        $actualizacion_status="SELECT P.id_prescription,E.id as id_eval, C.id_careplans from prescription P
                                left join tbl_evaluations E ON (E.id_prescription=P.id_prescription)
                                left join careplans C ON (C.evaluations_id=E.id) WHERE C.id_careplans=".$_POST['id_careplans_edit'];
        $resultado_actualizacion_status = ejecutar($actualizacion_status,$conexion);					
        while($row_status_act = mysqli_fetch_array($resultado_actualizacion_status)){
            $id_prescription_act = $row_status_act['id_prescription'];
            $id_eval_act = $row_status_act['id_eval'];
            $id_careplans_act = $row_status_act['id_careplans'];
        }

        $update_prescription="UPDATE prescription SET "
            . "status = '".$_POST['status_eval']."' "
            . "WHERE id_prescription = ".$id_prescription_act;
        ejecutar($update_prescription,$conexion);

        $update_eval="UPDATE tbl_evaluations SET "
            . "status_id = '".$_POST['status_eval']."' "
            . "WHERE id = ".$id_eval_act;
        ejecutar($update_eval,$conexion);

        $update_poc="UPDATE careplans SET "
            . "status = '".$_POST['status_eval']."' "
            . "WHERE id_careplans = ".$id_careplans_act;
        ejecutar($update_poc,$conexion);
    }else{
        $variable=$variable+1;

    }

}
if($ruta1 != ''){
    $sql_delete = "DELETE FROM tbl_documents WHERE id_type_document = 2 AND id_type_person = 1 AND id_person = '".$id_patient."' AND id_table_relation = ".$_POST['id_careplans_edit'];
    ejecutar($sql_delete,$conexion);
    
    $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
            . " VALUES ('2','1','".$_POST['id_careplans_edit']."','".$id_patient."','".$ruta1."')";
    ejecutar($sql_insert_document,$conexion);
}
if($variable==0){
    $array=array('mensaje'=>'ok');
}else{
    $array=array('mensaje'=>'duplicated');
}
echo json_encode($array);



