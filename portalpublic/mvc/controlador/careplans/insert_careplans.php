 <?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
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
$patients_id = $_POST['patient_id'];
$discipline_id = $_POST['discipline_id'];
/*
if(!isset($_FILES['file-1']['name'][0])){
       // echo "<script type=\"text/javascript\">alert(\"Error Uploading File, Try again\");</script>";
        //echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
        $respuesta="Error Uploading File, Try again";
        
}else*/
if(isset($_FILES['file-1']['name'][0]) && $_FILES['file-1']['type'][0] != 'application/pdf'){
        echo "<script type=\"text/javascript\">alert(\"File Extencion must be PDF\");</script>";
        echo '<script>window.location="../../vista/gestion_clinic/index.php";</script>';
        $flag = 1;
        $respuesta="File Extencion must be PDF";
}elseif(isset($_FILES['file-1']['name'][0]) && $_FILES['file-1']['type'][0] == 'application/pdf'){ 
       $sql  = "SELECT * FROM patients "
            . "WHERE true AND id = '".$_POST['patient_id']."' ;"; 
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
list($Last_name,$First_name) = explode(', ',$_POST['name_last_name_patients']);
if(!isset($flag)){
    
    if($_POST['firma_poc']!=''){
        $therapista_signed=1;
        $fecha_firma=date('Y-m-d H:i:s');
        $latitude=$_POST['latitud_firma_poc'];
        $longitud=$_POST['longitud_firma_poc'];  
        $therapista_signature=$_POST['ruta_firma_poc'];
    }else{
       $therapista_signed=0;
        $fecha_firma="";
        $latitude="";
        $longitud="";  
        $therapista_signature=""; 
    }
    if($_POST['template_id_Poc'] == ''){
        $template_id_Poc = 'null';
    }else{
        $template_id_Poc = $_POST['template_id_Poc'];
    }

    $info_pat="SELECT * FROM patients
                    WHERE  id =".$_POST['patient_id'];
    $info_pat_id = ejecutar($info_pat,$conexion);
    while ($row=mysqli_fetch_array($info_pat_id)) {
        $salida['Pat_id'] = $row['Pat_id'];
    }

    $info_pat="SELECT * FROM discipline
                    WHERE  DisciplineId =".$_POST['discipline_id'];
    $info_pat_id = ejecutar($info_pat,$conexion);
    while ($row=mysqli_fetch_array($info_pat_id)) {
        $salida1['Name'] = $row['Name'];
    }
    $info_pat="SELECT * FROM companies
                    WHERE  id =".$_POST['company_id'];
    $info_pat_id = ejecutar($info_pat,$conexion);
    while ($row=mysqli_fetch_array($info_pat_id)) {
        $salida2['company_name'] = $row['company_name'];
    }


    $insert="INSERT into careplans (`Last_name`,`First_name`,  Patient_ID, Discipline, `POC_due` , `Re_Eval_due` , user_id , diagnostic, status, evaluations_id, Company,id_template,ckeditor,therapist_signed,therapist_signed_date,therapist_signed_location_latitude,therapist_signed_location_longitude,therapist_signature) 
    values ('".trim(urldecode($_POST['name_patient']))."','".trim(urldecode($_POST['last_name_patient']))."','".$salida['Pat_id']."', '".$salida1['Name']."',"
         . "'".$_POST['from_poc']."','".$_POST['to_poc']."','".$_SESSION['user_id']."','".$_POST['diagnostic_id_poc']."',1,"
         . $_POST['id_eval'].",'".$salida2['company_name']."','".$_POST['template_id_Poc']."','".$_POST['editor_poc']."','".$therapista_signed."','".$fecha_firma."','".$latitude."','".$longitud."','".$therapista_signature."');";
    ejecutar($insert,$conexion);
    
    $max_id_evaluation = "SELECT MAX(id_careplans) AS id_poc FROM careplans";
    $result_evaluation = mysqli_query($conexion, $max_id_evaluation);
    $id_tbl_careplans = 0;
    while($row = mysqli_fetch_array($result_evaluation,MYSQLI_ASSOC)){
            $id_tbl_careplans = $row['id_poc'];
    }

    $sql_insert_document = "INSERT INTO tbl_documents (`id_type_document`,`id_type_person`,`id_table_relation`,`id_person`,`route_document`) "
            . " VALUES ('2','1',".$id_tbl_careplans.",'".$salida['Pat_id']."','".$ruta1."')";
    ejecutar($sql_insert_document,$conexion);
}

$k = 0;        
while(isset($long_id[$k])){
    $insert = "INSERT INTO careplan_goals (careplan_id,
    goal_lib_id,
    goal_term_id,
    cpg_area,
    cpg_text,pct_ach) VALUES (".$id_tbl_careplans.","
    .$_POST['long_'.$long_id[$k]].","
    . "3,'".urldecode($_POST['long_area_'.$long_id[$k]])."',"
    . "'".urldecode($_POST['long_library_'.$long_id[$k]])."',".$_POST['long_ach_'.$long_id[$k]].");";
    ejecutar($insert,$conexion);
    $k++;
}
$k = 0;
while(isset($short_id[$k])){
    $insert = "INSERT INTO careplan_goals (careplan_id,
    goal_lib_id,
    goal_term_id,
    cpg_area,
    cpg_text,pct_ach) VALUES (".$id_tbl_careplans.","
    .$_POST['short_'.$short_id[$k]].","
    . "2,'".urldecode($_POST['short_area_'.$short_id[$k]])."',"
    . "'".urldecode($_POST['short_library_'.$short_id[$k]])."',".$_POST['short_ach_'.$short_id[$k]].");";
    ejecutar($insert,$conexion);
    $k++;
}
$k = 0;
while(isset($na_id[$k])){
    $insert = "INSERT INTO careplan_goals (careplan_id,
    goal_lib_id,
    goal_term_id,
    cpg_area,
    cpg_text,pct_ach) VALUES (".$id_tbl_careplans.","
    .$_POST['na_'.$na_id[$k]].","
    . "1,'".urldecode($_POST['na_area_'.$na_id[$k]])."',"
    . "'".urldecode($_POST['na_library_'.$na_id[$k]])."',".$_POST['na_ach_'.$na_id[$k]].");";
    ejecutar($insert,$conexion);
    $k++;
}

if(!isset($flag)){
    $array=array('mensaje'=>'ok');
}else{
    $array=array('mensaje'=>'no',"response"=>$respuesta);
}
echo json_encode($array);
