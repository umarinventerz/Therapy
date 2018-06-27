<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

$insert_note_cpt_relation="INSERT INTO tbl_note_cpt_relation(id_note,id_cpt,id_diagnosis,location,units,duration,start,end) "
                    . "VALUES('".$_POST['id_note']."','".$_POST['id_cpt']."','".$_POST['id_diagnosis']."','".$_POST['location']."','".$_POST['units']."','".$_POST['duration']."','".$_POST['start']."','".$_POST['end']."')";

$resultado = ejecutar($insert_note_cpt_relation,$conexion);

if($resultado_nota){
        $id_note=$_POST['id_note'];
        $mensaje="<b>Information was loaded correctly</b>";
        $success=true;
}else{
    $mensaje="<b>An error has occurred, please try again</b>";
    $success=false;
    $id_note='';
}
$arreglo=array('mensaje'=>$mensaje,'success'=>$success, 'id_note'=>$id_note);

#############tbl_treatments


###############

##TBL_TREATMENTS

$sql_first  = "SELECT * FROM tbl_notes_documentation "
    . "WHERE true AND id_notes = ".$_POST['id_note'].";";
$resultado_notas = ejecutar($sql_first,$conexion);
while($row = mysqli_fetch_assoc($resultado_notas)) {
    $discipline_id = $row["discipline_id"];
    $id_careplans=$row["id_careplans"];
    $user_id=$row["user_id"];
    $visit_id=$row["visit_id"];
    $patient_id=$row["patient_id"];
 $supervisor_id=$row["supervisor_id"];
 $user_signed=$row["user_signed"];
 $sup_signed=$row["sup_signed"];
 $discipline_id=$row["discipline_id"];
}

if ($user_signed == 1) {

    $campo_20 = 'ü';


} else {

    $campo_20 = 'ÃƒÂ¼';
}

$sql  = "SELECT * FROM patients "
    . "WHERE true AND id = ".$patient_id.";";
$resultado_patiens = ejecutar($sql,$conexion);
while($row = mysqli_fetch_assoc($resultado_patiens)) {
    $Patient_name = trim($row["Last_name"]).', '.trim($row["First_name"]);
    $Pat_id=$row["Pat_id"];
    $insurrance=$row["Pri_Ins"];
    }

$sql_cpt  = "SELECT * FROM cpt "
    . "WHERE true AND ID = ".$_POST['id_cpt'].";";
$resultado_cpt = ejecutar($sql_cpt,$conexion);
while($row = mysqli_fetch_assoc($resultado_cpt)) {
    $cpt=$row["cpt"];
}

$sql_diagnostic  = "SELECT * FROM diagnosiscodes "
    . "WHERE true AND DiagCodeId = ".$_POST['id_diagnosis'].";";
$resultado_diagnostic = ejecutar($sql_diagnostict,$conexion);
while($row = mysqli_fetch_assoc($resultado_diagnostic)) {
    $DiagCodeValue=$row["DiagCodeValue"];
}


$info_pat="SELECT * FROM discipline
                    WHERE  DisciplineId =".$discipline_id;
$info_pat_id = ejecutar($info_pat,$conexion);
while ($row=mysqli_fetch_array($info_pat_id)) {
    $salida['name_discipline'] = $row['Name'];
}

//$sql_am='SELECT * FROM careplans C
 //     LEFT JOIN tbl_evaluations E ON(C.evaluations_id=E.id)
//      LEFT JOIN diagnosiscodes DC ON(DC.DiagCodeId=E.diagnostic)
 //     WHERE C.id_careplans='.$id_careplans;
//$sql_amount = ejecutar($sql_am,$conexion);
//while($datos_amont = mysqli_fetch_assoc($sql_amount)){
//    #campo 16 diagnostic
//    $campo_16 = $datos_amont['DiagCodeValue'];
// /   #campo 17 Unirs

  //  $campo_17=$datos_amont['units'];
//}


$info_pat="SELECT * FROM employee
                    WHERE  id =".$user_id;
$info_pat_id = ejecutar($info_pat,$conexion);
while ($row=mysqli_fetch_array($info_pat_id)) {
    $salida['employee_name'] = $row['last_name'].','.$row['first_name'];
    $salida['employee_licence']=$row['licence_number'];
}


$old_date = time();
$new_date = date("d/m/Y",$old_date);
#campo_2 representa el lugar donde se va a realizar la consulta
$campo_1=$new_date;
$campo_2=$_POST['location'];
$campo_3=324; #not use
$campo_4=2343; #not use
$campo_5=$Pat_id;
$campo_6=$Patient_name;
$campo_7=$insurrance;
$campo_8='Patients'; #not use
$campo_9=$salida['employee_name'];
$campo_10=$salida['name_discipline'];
$campo_11=$cpt;#CPT_code
$cpt=$campo_11;

$campo_12='NULL';
$campo_13='NULL';
$campo_14='NULL';
$campo_15=$_POST['duration'];  #durations
$campo_16=$DiagCodeValue;#diagnostic code
$campo_17=$_POST['units']#units
$campo_18='NULL';
$campo_19='ü';

$campo_21='NULL';
$campo_22='NULL';
$campo_23='NULL';
$campo_24='NULL';
$campo_25='NULL';
$campo_26='NULL';


$insert = " INSERT INTO tbl_treatments(campo_1,campo_2,campo_3,campo_4,campo_5,campo_6,campo_7,campo_8,campo_9,license_number,campo_10,campo_11,campo_12,campo_13,campo_14,campo_15,campo_16,campo_17,campo_18,campo_19,campo_20,campo_21,campo_22,campo_23,campo_24,pay,adults_progress_notes,pedriatics_progress_notes) 
 VALUES ('".$new_date."','".$campo_2."','".$campo_3."','".$campo_4."','".$Pat_id."','".$Patient_name."','".$insurrance."','".$campo_8."','".$salida['employee_name']."','".$salida['employee_licence']."','".$salida['name_discipline']."','".$cpt."','".$campo_12."','".$campo_13."','".$campo_14."','".$campo_15."','".$campo_16."','".$campo_17."','".$campo_18."','".$campo_19."','".$campo_20."','".$campo_21."','".$campo_22."','".$campo_23."','".$campo_24."','0','0','0');";
#$insert = str_replace("'null'", "null", $insert);
$resultado = ejecutar($insert,$conexion);
 echo $insert;

#####################
#####cuando se editan donde debo buscar las cosas de este reporte

 $max_id_treatmant = "SELECT MAX(id_treatments) AS id_treat FROM tbl_treatments";
 $result_note1 = ejecutar($conexion, $max_id_treatmant);
 $id_tbl_treatments = 0;
 #while($row = mysqli_fetch_array($result_note1,MYSQLI_ASSOC)){
 #    $id_tbl_treatments = $row['id_treat'];
 #}

 $insert_in_tbl_treatment_notes="INSERT INTO tbl_treatment_notes ( id_notes,id_treatments)
values ('".$_POST['id_note']."','".$id_tbl_treatments."');";
 #$resultado = ejecutar($insert_in_tbl_treatment_notes,$conexion);


######


echo json_encode($arreglo);