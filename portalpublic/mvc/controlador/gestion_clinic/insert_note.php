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
     //   echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
      //  echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
   //     $flag = 1;
}
else
if($_FILES['file-1']['type'][0] != 'application/pdf'){
    //    echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
   //     echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
    //    $flag = 1;
}
else
    {
        $sql  = "SELECT * FROM patients "
            . "WHERE true AND id = ".$_POST['patients_id'].";";
        $resultado_patiens = ejecutar($sql,$conexion); 
         while($row = mysqli_fetch_assoc($resultado_patiens)) {
            $Patient_name = trim($row["Last_name"]).', '.trim($row["First_name"]);
             $Pat_id=$row["Pat_id"];
             $insurrance=$row["Pri_Ins"];
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



$insert="INSERT INTO tbl_notes_documentation (discipline_id, `snotes`, `onotes`, `anotes`, `pnotes`, `id_careplans` , created, modified, user_id, user_signed, user_signed_date, patient_name, patient_id,duration,visit_date)
values ('".$_POST['discipline_id']."','".$snote."','".$onote."','".$anote."','".$pnote."','".$_POST['id_careplans']."',now(),now(),'".$_SESSION['user_id']."','".$signedNote."','.$dateSigned.','.$Patient_name.','".$_POST['patients_id']."','".$_POST['duration']."','".$_POST['visit_date']."');";



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


$sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`)
 ". " VALUES ('141','1',".$id_tbl_note.",'".$_POST['patients_id_note_hidden']."','".$ruta1."')";
ejecutar($sql_insert_document,$conexion);


$json_resultado['mensaje'] = 'ok';


###############

 ##TBL_TREATMENTS

 $sql  = "SELECT * FROM patients "
     . "WHERE true AND id = ".$_POST['patients_id'].";";
 $resultado_patiens = ejecutar($sql,$conexion);
 while($row = mysqli_fetch_assoc($resultado_patiens)) {
     $Patient_name = trim($row["Last_name"]).', '.trim($row["First_name"]);
     $Pat_id=$row["Pat_id"];
     $insurrance=$row["Pri_Ins"];
 }



 $info_pat="SELECT * FROM discipline
                    WHERE  DisciplineId =".$_POST['discipline_id'];
 $info_pat_id = ejecutar($info_pat,$conexion);
 while ($row=mysqli_fetch_array($info_pat_id)) {
     $salida['name_discipline'] = $row['Name'];
    }

    $sql_am='SELECT * FROM careplans C 
      LEFT JOIN tbl_evaluations E ON(C.evaluations_id=E.id) 
      LEFT JOIN diagnosiscodes DC ON(DC.DiagCodeId=E.diagnostic) 
      WHERE C.id_careplans='.$_POST['id_careplans'];
 $sql_amount = ejecutar($sql_am,$conexion);
 while($datos_amont = mysqli_fetch_assoc($sql_amount)){
     #campo 16 diagnostic
     $campo_16 = $datos_amont['DiagCodeValue'];
  #campo 17 Unirs

     $campo_17=$datos_amont['units'];
      }


 $old_date = time();
 $new_date = date("d/m/Y",$old_date);
 #campo_2 representa el lugar donde se va a realizar la consulta

 $campo_2=03;
 $campo_3=324; #not use
 $campo_4=2343; #not use
 $campo_8='patients'; #not use
 $campo_11=122;#CPT_code
 $cpt=$campo_11;
 $campo_12='NULL';
 $campo_13='NULL';
 $campo_14='NULL';
 $campo_15=$_POST['duration'];  #durations
 $campo_18='NULL';
 $campo_19='ü';

 $campo_21='NULL';
 $campo_22='NULL';
 $campo_23='NULL';
 $campo_24='NULL';
 $campo_25='NULL';
 $campo_26='NULL';



 $info_pat="SELECT * FROM employee
                    WHERE  id =".$_SESSION['user_id'];
 $info_pat_id = ejecutar($info_pat,$conexion);
 while ($row=mysqli_fetch_array($info_pat_id)) {
     $salida['employee_name'] = $row['last_name'].' '.$row['first_name'];
     $salida['employee_licence']=$row['licence_number'];
 }



 if ($_POST['firma_note_terapist'] == 1) {

     $campo_20 = 'ü';


 } else {

     $campo_20 = 'ÃƒÂ¼';
 }



 $insert = " INSERT INTO tbl_treatments(campo_1,campo_2,campo_3,campo_4,campo_5,campo_6,campo_7,campo_8,campo_9,license_number,campo_10,campo_11,campo_12,campo_13,campo_14,campo_15,campo_16,campo_17,campo_18,campo_19,campo_20,campo_21,campo_22,campo_23,campo_24,pay,adults_progress_notes,pedriatics_progress_notes) 
 VALUES ('".$new_date."','".$campo_2."','".$campo_3."','".$campo_4."','".$Pat_id."','".$Patient_name."','".$insurrance."','".$campo_8."','".$salida['employee_name']."','".$salida['employee_licence']."','".$salida['name_discipline']."','".$cpt."','".$campo_12."','".$campo_13."','".$campo_14."','".$campo_15."','".$campo_16."','".$campo_17."','".$campo_18."','".$campo_19."','".$campo_20."','".$campo_21."','".$campo_22."','".$campo_23."','".$campo_24."','0','0','0');";
 $insert = str_replace("'null'", "null", $insert);
 $resultado = ejecutar($insert,$conexion);


 ####################esto me asegura cuando se editen las notas poder editar el reporte en especifico

 $max_id_treatmant = "SELECT MAX(id_treatments) AS id_treat FROM tbl_treatments";
 $result_note1 = mysqli_query($conexion, $max_id_treatmant);
 $id_tbl_treatments = 0;
 while($row = mysqli_fetch_array($result_note1,MYSQLI_ASSOC)){
     $id_tbl_treatments = $row['id_treat'];
 }

 $insert_in_tbl_treatment_notes="INSERT INTO tbl_treatment_notes ( id_notes,id_treatments)
values ('".$id_tbl_note."','".$id_tbl_treatments."');";
 $resultado = ejecutar($insert_in_tbl_treatment_notes,$conexion);

 #######ya esto esta bien




 echo json_encode($json_resultado);


