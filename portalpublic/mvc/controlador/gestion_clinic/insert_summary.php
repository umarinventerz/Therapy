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
$ruta1 = '';
if(isset($_FILES['file-1']['name'][0])){  
        $sql  = "SELECT * FROM patients "
            . "WHERE true AND id = ".$_POST['patients_id_summary_hidden'].";"; 
        $resultado_patiens = ejecutar($sql,$conexion); 
         while($row = mysqli_fetch_assoc($resultado_patiens)) {
            $Patient_name = trim($row["Last_name"]).', '.trim($row["First_name"]);
            $Patient_id = $row["id"];
        }
        
        $patient_name_directorio = str_replace(',','',str_replace(' ','_',$Patient_name));
                        
        if (!file_exists('../../../patients')) {        
            mkdir('../../../patients', 0777, false);  
        }

        if (!file_exists('../../../patients/'.$patient_name_directorio)) {        
            mkdir('../../../patients/'.$patient_name_directorio, 0777, false);  
        }    

        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 142";
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
if($_POST['CkEditorSummary'] == 'template'){
    $field = ', id_template';
    $value = ',1';
        
}
else{
    $field = ', ckeditor';
    $value = ',\''.$_POST['editorSummary'].'\'';
}
if($_POST['md_signed_summary']== ''){
    $_POST['md_signed_summary'] = 0;
    $_POST['date_of_signed_summary'] = 'null';
} 
$conexion = conectar();
$insert="INSERT into tbl_summary (`id_evaluations`, created, start_date, end_date , id_user, signed, 
signed_date, status ".$field.") 
values ('".$_POST['evaluation_id_summary_hidden']."','".$_POST['date_of_summary']."','".$_POST['from_summary']."',"
    . "'".$_POST['to_summary']."','".$_SESSION['user_id']."',".$_POST['md_signed_summary'].",".$_POST['date_of_signed_summary'].",1 ".$value.");";
ejecutar($insert,$conexion);

$max_id_evaluation = "SELECT MAX(id_summary) AS id_summary FROM tbl_summary";
$result_evaluation = mysqli_query($conexion, $max_id_evaluation);
$id_tbl_evaluation = 0;
while($row = mysqli_fetch_array($result_evaluation,MYSQLI_ASSOC)){
        $id_tbl_summary = $row['id_summary'];
}

$k = 0;        
while(isset($long_id[$k])){
    //`summary_goal_comments`,
    $sql_insert_summary = "INSERT INTO `kidswork_therapy`.`tbl_summary_documentation_goals`
    (
        `id_summary`,
        `careplan_goal_id`,
        `selected`,
        `summary_goal_area`,
        `summary_goal_text`,
        `summary_goal_number`,        
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$id_tbl_summary.",
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
    ejecutar($sql_insert_summary,$conexion);
    $k++;
}

$k = 0;        
while(isset($short_id[$k])){
    $sql_insert_summary = "INSERT INTO `kidswork_therapy`.`tbl_summary_documentation_goals`
    (
        `id_summary`,
        `careplan_goal_id`,
        `selected`,
        `summary_goal_area`,
        `summary_goal_text`,
        `summary_goal_number`,
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$id_tbl_summary.",
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
    ejecutar($sql_insert_summary,$conexion);
    $k++;
}

$k = 0;        
while(isset($na_id[$k])){
    $sql_insert_summary = "INSERT INTO `kidswork_therapy`.`tbl_summary_documentation_goals`
    (
        `id_summary`,
        `careplan_goal_id`,
        `selected`,
        `summary_goal_area`,
        `summary_goal_text`,
        `summary_goal_number`,
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$id_tbl_summary.",
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
    ejecutar($sql_insert_summary,$conexion);
    $k++;
}

if($ruta1!= ''){
    $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
        . " VALUES ('142','1',".$id_tbl_summary.",'".$_POST['patients_id_summary_hidde']."','".$ruta1."')";
    ejecutar($sql_insert_document,$conexion);
}

//inserto en los modales de template

if($_POST['CkEditorSummary'] == 'template'){
    
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
        $insert="INSERT INTO tbl_modal_template(type_modal,id_modal,componentes,ckeditor,user_id,created) VALUES(3,".$id_tbl_summary.",".$array_componentes[$i].",'".$array_html[$i]."',".$_SESSION['user_id'].",'".$fecha."')";
        ejecutar($insert,$conexion);
    }
}

$json_resultado['mensaje'] = 'ok';  
                       
echo json_encode($json_resultado);

