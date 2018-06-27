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

if(!isset($_FILES['file-1']['name'][0])){
        echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
}
else
if($_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
}
else
    {
        $sql  = "SELECT * FROM patients "
            . "WHERE true AND id = ".$_POST['patients_id'].";";
        $resultado_patiens = ejecutar($sql,$conexion); 
         while($row = mysqli_fetch_assoc($resultado_patiens)) {
          echo  $Patient_name = trim($row["Last_name"]).', '.trim($row["First_name"]);
        }
        
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


if(isset($_POST['signedNote'])){
    $signedNote = $_POST['signedNote'];
    $dateSigned = "'".$_POST['dateSigned']."'";
}else{
    $signedNote = 0;
    $dateSigned = "null";
}
$snote = $_POST['snote'];
$snote= ereg_replace("[^A-Za-z0-9]", " ", $snote);	
$onote = $_POST['onote'];
$onote= ereg_replace("[^A-Za-z0-9]", " ", $onote);
$anote = $_POST['anote'];
$anote= ereg_replace("[^A-Za-z0-9]", " ", $anote);
$pnote = $_POST['pnote'];
$pnote= ereg_replace("[^A-Za-z0-9]", " ", $pnote);



$insert="INSERT INTO tbl_notes_documentation (discipline_id, `snotes`, `onotes`, `anotes`, `pnotes`, `id_careplans` , created, modified, user_id, user_signed, user_signed_date, patient_name, patient_id, visit_date)
values ('".$_POST['discipline_id']."','".$snote."','".$onote."','".$anote."','".$pnote."',"
     . "'".$_POST['id_careplans']."',now(),now(),'".$_SESSION['user_id']."',".$signedNote.",".$dateSigned.",'".$Patient_name."',".$_POST['patients_id'].",'".$_POST['visit_date']."');";

echo $insert;

ejecutar($insert,$conexion);

$max_id_note = "SELECT MAX(id_notes) AS id_notes FROM tbl_notes_documentation";
$result_note = mysqli_query($conexion, $max_id_note);
$id_tbl_note = 0;
while($row = mysqli_fetch_array($result_note,MYSQLI_ASSOC)){
        $id_tbl_note = $row['id_notes'];
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
        ".$id_tbl_note.",
        ".$short_id[$k].",
        true,
        ".$_POST['short_area_'.$short_id[$k]].",
        ".$_POST['short_library_'.$short_id[$k]].",
        ".$_POST['short_note_level_'.$short_id[$k]].",
        ".$_POST['short_note_ach_'.$short_id[$k]].",
        3,
        ".$_POST['short_note_level_'.$short_id[$k]].",
        ".$_POST['short_note_assist_'.$short_id[$k]]."
    );
    ";
    ejecutar($insert,$conexion);
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
        ".$id_tbl_note.",
        ".$short_id[$k].",
        true,
        ".$_POST['short_area_'.$short_id[$k]].",
        ".$_POST['short_library_'.$short_id[$k]].",
        ".$_POST['short_note_level_'.$short_id[$k]].",
        ".$_POST['short_note_ach_'.$short_id[$k]].",
        2,
        ".$_POST['short_note_level_'.$short_id[$k]].",
        ".$_POST['short_note_assist_'.$short_id[$k]]."
    );
    ";
    ejecutar($insert,$conexion);
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
        ".$id_tbl_note.",
        ".$short_id[$k].",
        true,
        ".$_POST['na_area_'.$short_id[$k]].",
        ".$_POST['na_library_'.$short_id[$k]].",
        ".$_POST['na_note_level_'.$short_id[$k]].",
        ".$_POST['na_note_ach_'.$short_id[$k]].",
        1,
        ".$_POST['na_note_level_'.$short_id[$k]].",
        ".$_POST['na_note_assist_'.$short_id[$k]]."
    );
    ";
    ejecutar($insert,$conexion);
    $k++;
}


$sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
        . " VALUES ('141','1',".$id_tbl_note.",'".$_POST['patients_id_note_hidden']."','".$ruta1."')";
ejecutar($sql_insert_document,$conexion);


$json_resultado['mensaje'] = 'ok';  
                       
echo json_encode($json_resultado);


