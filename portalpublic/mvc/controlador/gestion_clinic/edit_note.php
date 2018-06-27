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
            if(isset($_POST['long_note_check'.substr($key, 5, strlen($key))]))
            $long_id[] = substr($key, 5, strlen($key));
        }
    }
    if(substr($key, 0, 5) == 'short'){                  
        if(is_numeric(substr($key, 6, strlen($key)))){            
            if($_POST['short_note_check_'.substr($key, 6, strlen($key))] == 'on')
            $short_id[] = substr($key, 6, strlen($key));
        }
    }
    if(substr($key, 0, 2) == 'na'){        
        if(is_numeric(substr($key, 3, strlen($key)))){
            if(isset($_POST['na_note_check'.substr($key, 3, strlen($key))]))
            $na_id[] = substr($key, 3, strlen($key));
        }
    }
}


 $sql  = "SELECT *,n.user_signed_date as date_signed_note,n.user_signed as signed_note,n.discipline_id as discipline_note FROM tbl_notes_documentation n "
        . " LEFT JOIN careplans c ON c.id_careplans = n.id_careplans"
        . " LEFT JOIN tbl_evaluations e ON e.id = c.evaluations_id"
        . " LEFT JOIN prescription p ON p.id_prescription = e.id_prescription"        
        . " WHERE n.id_notes = ".$_POST['id_notes'].";"; 

$resultado = ejecutar($sql,$conexion);   


$reporte = [];
$i = 1;      
while($datos = mysqli_fetch_assoc($resultado)) {
    $reporte = $datos;
}
if(isset($_FILES['file-1']['name'][0]) && $_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index_1.php";</script>';
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

        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 141";
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

if(isset($_POST['signedNote'])){
    $signedNote = $_POST['signedNote'];
    $dateSigned = "'".$_POST['dateSigned']."'";
}else{
    $signedNote = 0;
    $dateSigned = "null";
}
    
 //$snote = mysql_real_escape_string($_POST['snote']);
// $snote= ereg_replace("[^A-Za-z0-9]", " ", $snote);
// $onote = mysql_real_escape_string($_POST['onote']);
// $onote= ereg_replace("[^A-Za-z0-9]", " ", $onote);
// $anote = mysql_real_escape_string($_POST['anote']);
// $anote= ereg_replace("[^A-Za-z0-9]", " ", $anote);
 //$pnote = mysql_real_escape_string($_POST['pnote']);
// $pnote= ereg_replace("[^A-Za-z0-9]", " ", $pnote);

 $snote = $_POST['snote'];

 $onote = $_POST['onote'];

 $anote = $_POST['anote'];

 $pnote = $_POST['pnote'];


 if($_POST['visit_date'] == '') $_POST['visit_date'] = 'null';
 else $_POST['visit_date'] = "'".$_POST['visit_date']."'";
 
 $update = "UPDATE tbl_notes_documentation SET snotes = '$snote', 
    onotes = '$onote', 
    anotes = '$anote', 
    pnotes = '$pnote', 
    duration = '".$_POST['duration']."', 
    modified = '1', 
    modified_date = now(), 
    #user_id = ".$_SESSION['user_id'].", 
    user_signed = ".$signedNote.", 
    user_signed_date = ".$dateSigned.", 
    visit_date = ".$_POST['visit_date']."     
    WHERE id_notes = ".$_POST['id_notes'].";";

ejecutar($update,$conexion);

$poc_updated=" UPDATE careplans SET "       
. "Company = '".$_POST['company_id']."'"
. " WHERE id_careplans = ". $_POST['id_careplans'].";";
ejecutar($poc_updated,$conexion);

//signature
//supervisor
if($_POST['firma_note_supervisor']!=''){
        $actor_s="supervisor";
        $therapista_signed_supervisor=1;
        $fecha_firma_supervisor=date('Y-m-d H:i:s');
        $latitude_supervisor=$_POST['latitud_firma_note_supervisor'];
        $longitud_supervisor=$_POST['longitud_firma_note_supervisor'];  
        $therapista_signature_supervisor=$_POST['ruta_firma_note_supervisor'];
}else{
   $actor_s="";
   $therapista_signed_supervisor=0;
    $fecha_firma_supervisor="";
    $latitude_supervisor="";
    $longitud_supervisor="";  
    $therapista_signature_supervisor=""; 
}
//therapist
if($_POST['firma_note_terapist']!=''){
        $actor_t="therapist";
        $therapista_signed=1;
        $fecha_firma=date('Y-m-d H:i:s');
        $latitude=$_POST['latitud_firma_note_terapist'];
        $longitud=$_POST['longitud_firma_note_terapist'];  
        $therapista_signature=$_POST['ruta_firma_note_terapist'];
}else{
   $actor_t="";
   $therapista_signed=0;
    $fecha_firma="";
    $latitude="";
    $longitud="";  
    $therapista_signature=""; 
}

//sql para insertar en tbl_signature_note
if($actor_s!==''){
    
    $sql_insert="INSERT INTO tbl_signature_note(id_note,therapist_signed,therapist_signed_date,therapist_signed_location_latitude,therapist_signed_location_longitude,therapist_signature,actor)"
            . " VALUES ('".$_POST['id_notes']."','".$therapista_signed_supervisor."','".$fecha_firma_supervisor."','".$latitude_supervisor."','".$longitud_supervisor."','".$therapista_signature_supervisor."','SUPERVISOR')";
    ejecutar($sql_insert,$conexion); 
}
if($actor_t!==''){
    
    $sql_insert_t="INSERT INTO tbl_signature_note(id_note,therapist_signed,therapist_signed_date,therapist_signed_location_latitude,therapist_signed_location_longitude,therapist_signature,actor)"
            . " VALUES ('".$_POST['id_notes']."','".$therapista_signed."','".$fecha_firma."','".$latitude."','".$longitud."','".$therapista_signature."','THERAPIST')";
    ejecutar($sql_insert_t,$conexion); 
}

$sql_delete = "DELETE FROM tbl_notes_documentation_goals WHERE id_note = ".$_POST['id_notes'];
ejecutar($sql_delete,$conexion);

$k = 0;        
while(isset($long_id[$k])){
    $sql_insert_note = "INSERT INTO tbl_notes_documentation_goals
    (
        `id_note`,
        `careplan_goal_id`,
        `selected`,
        `note_goal_area`,
        `note_goal_text`,
        `note_goal_number`,
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$_POST['id_notes'].",
        ".$short_id[$k].",
        true,
        '".urldecode($_POST['long_area_'.$short_id[$k]])."',
        '".urldecode($_POST['long_library_'.$short_id[$k]])."',
        ".$_POST['long_note_level_'.$short_id[$k]].",
        ".$_POST['long_note_ach_'.$short_id[$k]].",
        3,
        ".$_POST['long_note_level_'.$short_id[$k]].",
        ".$_POST['long_note_assist_'.$short_id[$k]]."
    );
    ";
    ejecutar($sql_insert_note,$conexion);
    $k++;
}

$k = 0;        
while(isset($short_id[$k])){
    $sql_insert_note = "INSERT INTO tbl_notes_documentation_goals
    (
        `id_note`,
        `careplan_goal_id`,
        `selected`,
        `note_goal_area`,
        `note_goal_text`,
        `note_goal_number`,
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$_POST['id_notes'].",
        ".$short_id[$k].",
        true,
        '".urldecode($_POST['short_area_'.$short_id[$k]])."',
        '".urldecode($_POST['short_library_'.$short_id[$k]])."',
        ".$_POST['short_note_level_'.$short_id[$k]].",
        ".$_POST['short_note_ach_'.$short_id[$k]].",
        2,
        ".$_POST['short_note_level_'.$short_id[$k]].",
        ".$_POST['short_note_assist_'.$short_id[$k]]."
    );
    ";
    ejecutar($sql_insert_note,$conexion);
    $k++;
}

$k = 0;        
while(isset($na_id[$k])){
    $sql_insert_note = "INSERT INTO tbl_notes_documentation_goals
    (
        `id_note`,
        `careplan_goal_id`,
        `selected`,
        `note_goal_area`,
        `note_goal_text`,
        `note_goal_number`,
        `pct_ach`,
        `goal_term_id`,
        `goal_assist_level_id`,
        `goal_assist_type_id`
    )
    VALUES
    (
        ".$_POST['id_notes'].",
        ".$short_id[$k].",
        true,
        '".urldecode($_POST['na_area_'.$short_id[$k]])."',
        '".urldecode($_POST['na_library_'.$short_id[$k]])."',
        ".$_POST['na_note_level_'.$short_id[$k]].",
        ".$_POST['na_note_ach_'.$short_id[$k]].",
        1,
        ".$_POST['na_note_level_'.$short_id[$k]].",
        ".$_POST['na_note_assist_'.$short_id[$k]]."
    );
    ";
    ejecutar($sql_insert_note,$conexion);
    $k++;
}

if($ruta1 != ''){
    $sql_delete = "DELETE FROM tbl_documents WHERE id_type_document = 141 AND id_type_person = 1 AND id_person = '".$id_patient."' AND id_table_relation = ".$_POST['id_note_edit'];
    ejecutar($sql_delete,$conexion);
    
    $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
            . " VALUES ('141','1',".$_POST['id_note_edit'].",'".$id_patient."','".$ruta1."')";
    ejecutar($sql_insert_document,$conexion);
}
$arreglo=array('mensaje'=>'ok',
               'disciplina'=>$_POST['discipline_id'],
               'pat_id'=>$_POST['patients_id']);



###########parte de la tabla treatmento que editamos  ############
#######saber cual es la parte de treatments que se edita segun la nota que entra


 $info_treatments="SELECT * FROM tbl_treatment_notes
                    WHERE  id_notes =".$_POST['id_notes'];
 $info_treatments_sql = ejecutar($info_treatments,$conexion);
 while ($row=mysqli_fetch_array($info_treatments_sql)) {
     $salida['id_treatments']=$row['id_treatments'];
 }

if (count($info_treatments_sql)>0) {
    $campo_15 = $_POST['duration'];


    if ($_POST['firma_note_terapist'] == 1) {

        $campo_20 = 'ü';


    } else {

        $campo_20 = 'ÃƒÂ¼';
    }


    $treatments_updated = " UPDATE tbl_treatments SET 
        campo_20 = ' $campo_20 ',
        campo_15 = '$campo_15 '
        WHERE id_treatments = ". $salida['id_treatments'] .";";

  //  ejecutar($treatments_updated, $conexion);
}

 ###########################################

echo json_encode($arreglo);

