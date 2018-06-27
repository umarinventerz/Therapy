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
    
    if(substr($key, 0, 12) == 'long_summary'){ 
        
        if(is_numeric(substr($key, 13, strlen($key)))){
            if(isset($_POST['long_summary_check_'.substr($key, 13, strlen($key))]))
            $long_id[] = substr($key, 13, strlen($key));
        }
    }
    if(substr($key, 0, 13) == 'short_summary'){                  
        if(is_numeric(substr($key, 14, strlen($key)))){            
            if($_POST['short_summary_check_'.substr($key, 14, strlen($key))] == 'on')
            $short_id[] = substr($key, 14, strlen($key));
        }
    }
    if(substr($key, 0, 2) == 'na_summary'){        
        if(is_numeric(substr($key, 10, strlen($key)))){
            if(isset($_POST['na_summary_check_'.substr($key, 11, strlen($key))]))
            $na_id[] = substr($key, 11, strlen($key));
        }
    }
}

list($name, $lastname) = explode(",",urldecode($_POST['patients_discharge_hidden']));
$name = trim($name);
$lastname = trim($lastname);

if(isset($_FILES['file-1']['name'][0]) && $_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
}else{  
    if(isset($_FILES['file-1']['name'][0])){
       
        $patient_name_directorio = $name.'_'.$lastname;

        if (!file_exists('../../../patients')) {        
            mkdir('../../../patients', 0777, false);  
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
            mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
        }    

        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 143";
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
if($_POST['templateCkEditorDischarge'] == 'template'){
    $field = ', id_template = '.$_POST['template_id_discharge'];
}
else{
    $field = ', ckeditor = '.'\''.$_POST['ckeditorDischarge'].'\'';
}
if($_POST['signatureDischargeStatus'] == 1){
    $date_signature = '';
}else{
    $date_signature = "`date_signature` ='". $_POST['date_of_discharge']."',";    
}
$update=" UPDATE tbl_discharge SET "
    ."`created_discharge` ='". $_POST['date_of_discharge']."'," 
    ."`start_discharge` ='". $_POST['from_discharge']."'," 
    ."`end_discharge` ='". $_POST['to_discharge']."',"
    ."`id_user` ='". $_SESSION['user_id']."'," 
    ."`signature_discharge` ='". $_POST['signatureDischargeStatus']."'," 
    .$date_signature 
    ."`status`  = 1 ".$field        
    . " WHERE id_discharge = ".$_POST['id_discharge_hidden'];

ejecutar($update,$conexion);

$delete_goals = "DELETE FROM tbl_discharge_documentation_goals WHERE id_discharge = ".$_POST['id_discharge_hidden'];
ejecutar($delete_goals,$conexion);

$k = 0;        
while(isset($long_id[$k])){
    //`summary_goal_comments`,
    $sql_insert_discharge = "INSERT INTO `kidswork_therapy`.`tbl_discharge_documentation_goals`
    (
        `id_discharge`,
        `careplan_goal_id`,
        `selected`,
        `discharge_goal_area`,
        `discharge_goal_text`,
        `discharge_goal_number`,
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$_POST['id_discharge_hidden'].",
        ".$long_id[$k].",
        true,
        '".urldecode($_POST['long_summary_area_'.$long_id[$k]])."',
        '".urldecode($_POST['long_summary_library_'.$long_id[$k]])."',
        ".$_POST['long_summary_level_'.$long_id[$k]].",
        ".$_POST['long_summary_ach_'.$long_id[$k]].",
        3,
        ".$_POST['long_summary_level_'.$long_id[$k]].",
        ".$_POST['long_summary_assist_'.$long_id[$k]]."
    );
    ";
    ejecutar($sql_insert_discharge,$conexion);
    $k++;
}

$k = 0;        
while(isset($short_id[$k])){
    $sql_insert_discharge = "INSERT INTO `kidswork_therapy`.`tbl_discharge_documentation_goals`
    (
        `id_discharge`,
        `careplan_goal_id`,
        `selected`,
        `discharge_goal_area`,
        `discharge_goal_text`,
        `discharge_goal_number`,
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$_POST['id_discharge_hidden'].",
        ".$short_id[$k].",
        true,
        '".urldecode($_POST['short_summary_area_'.$short_id[$k]])."',
        '".urldecode($_POST['short_summary_library_'.$short_id[$k]])."',
        ".$_POST['short_summary_level_'.$short_id[$k]].",
        ".$_POST['short_summary_ach_'.$short_id[$k]].",
        2,
        ".$_POST['short_summary_level_'.$short_id[$k]].",
        ".$_POST['short_summary_assist_'.$short_id[$k]]."
    );
    ";
    ejecutar($sql_insert_discharge,$conexion);
    $k++;
}

$k = 0;        
while(isset($na_id[$k])){
    $sql_insert_discharge = "INSERT INTO `kidswork_therapy`.`tbl_discharge_documentation_goals`
    (
        `id_discharge`,
        `careplan_goal_id`,
        `selected`,
        `discharge_goal_area`,
        `discharge_goal_text`,
        `discharge_goal_number`,
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$_POST['id_discharge_hidden'].",
        ".$na_id[$k].",
        true,
        '".urldecode($_POST['na_summary_area_'.$na_id[$k]])."',
        '".urldecode($_POST['na_summary_library_'.$na_id[$k]])."',
        ".$_POST['na_summary_level_'.$na_id[$k]].",
        ".$_POST['na_summary_ach_'.$na_id[$k]].",
        1,
        ".$_POST['na_summary_level_'.$na_id[$k]].",
        ".$_POST['na_summary_assist_'.$na_id[$k]]."
    );
    ";
    ejecutar($sql_insert_discharge,$conexion);
    $k++;
}


if($ruta1 != ''){
    $sql_delete = "DELETE FROM tbl_documents WHERE id_type_document = 143 AND id_type_person = 1 AND id_person = '".$_POST['patients_id_discharge_hidden']."' AND id_table_relation = ".$_POST['id_discharge_hidden'];
    ejecutar($sql_delete,$conexion);
    
    $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
            . " VALUES ('143','1',".$_POST['id_discharge_hidden'].",'".$_POST['patients_id_discharge_hidden']."','".$ruta1."')";
    ejecutar($sql_insert_document,$conexion);

}

if ($_POST['template_discharge']=='si'){
    //consulto si hay registros        
    $components="SELECT COUNT(*) AS contador FROM tbl_modal_template"
        . " WHERE type_modal=4 AND id_modal=".$_POST['id_discharge_hidden'];
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
            $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(4,".$_POST['id_discharge_hidden'].",".$array_componentes[$i].",'".$array_html[$i]."',".$_SESSION['user_id'].",'".$fecha."')";
            ejecutar($insert,$conexion);
        }
    }else{
        $delete="DELETE FROM tbl_modal_template WHERE type_modal=4 AND id_modal=".$_POST['id_discharge_hidden'];
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
            $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(4,".$_POST['id_discharge_hidden'].",".$array_componentes[$i].",'".$array_html[$i]."',".$_SESSION['user_id'].",'".$fecha."')";
            ejecutar($insert,$conexion);
        }
    }
}

$json_resultado['mensaje'] = 'ok'; 
                       
echo json_encode($json_resultado);


