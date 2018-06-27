<?php
session_start();
require_once("../../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();
$resfresh=$_GET['resfresh'];
$user=$_GET['users'];
$patient=$_GET['patients'];
$inicio=$_GET['start'];
$fin=$_GET['end'];
$usuario_own=$_GET['usuario'];
$own=$_GET['own'];
if($user!=''){
    if($resfresh==null){
        $valor_user=  implode(',', $user);
    }else{
        $valor_user=$user;
    }
    $user_vacio='no';
}else{
    $user_vacio='si';
}
if($patient!=''){
    $valor_patient=  implode(',', $patient);
    $patient_vacio='no';
}else{
    $patient_vacio='si';
}

if($own ==null){

    if($user_vacio=='si' && $patient_vacio=='si'){

         $sql="select C.*,C.id as id_calendar,CP.*,CP.id as id_date,CP.start as inicio,CP.end as fin,concat(P.Last_name,', ',P.First_name) as title,concat(E.Last_name,', ',E.First_name) as employee,T.color as color,L.name as locations,CR.*  FROM calendar_appointments C
             left join calendar_appoiment_date CP on(C.id=CP.calendar_appoiment_id) left join calendar_appoiment_recurring CR ON(CP.id=CR.calendar_date) left join patients P on(C.Pat_id=P.id) left join employee E on (C.therapist_id=E.id) left join tbl_type_appoiments T on (C.type=T.id) left join tbl_location_appoiments L on (C.location=L.id)  WHERE CP.start BETWEEN '".$inicio."' AND '".$fin."' ORDER BY CP.start ASC";
    }
    if($user_vacio !='si' && $patient_vacio=='si'){

         $sql="select C.*,C.id as id_calendar,CP.*,CP.id as id_date,CP.start as inicio,CP.end as fin,concat(P.Last_name,', ',P.First_name) as title,concat(E.Last_name,', ',E.First_name) as employee,T.color as color,L.name as locations,CR.*  FROM calendar_appointments C
        left join calendar_appoiment_date CP on(C.id=CP.calendar_appoiment_id) left join calendar_appoiment_recurring CR ON(CP.id=CR.calendar_date) left join patients P on(C.Pat_id=P.id) left join employee E on (C.therapist_id=E.id) left join tbl_type_appoiments T on (C.type=T.id) left join tbl_location_appoiments L on (C.location=L.id) WHERE  C.therapist_id IN (".$valor_user.") AND CP.start BETWEEN '".$inicio."' AND '".$fin."' ORDER BY CP.start ASC";

    }
    if($user_vacio =='si' && $patient_vacio!='si'){
        $sql="select C.*,C.id as id_calendar,CP.*,CP.id as id_date,CP.start as inicio,CP.end as fin,concat(P.Last_name,', ',P.First_name) as title,concat(E.Last_name,', ',E.First_name) as employee,T.color as color,L.name as locations,CR.*  FROM calendar_appointments C
        left join calendar_appoiment_date CP on(C.id=CP.calendar_appoiment_id) left join calendar_appoiment_recurring CR ON(CP.id=CR.calendar_date) left join patients P on(C.Pat_id=P.id) left join employee E on (C.therapist_id=E.id) left join tbl_type_appoiments T on (C.type=T.id) left join tbl_location_appoiments L on (C.location=L.id) WHERE  C.Pat_id IN (".$valor_patient.") AND CP.start BETWEEN '".$inicio."' AND '".$fin."' ORDER BY CP.start ASC";

    }

    if($user_vacio !='si' && $patient_vacio!='si'){
        $sql="select C.*,C.id as id_calendar,CP.*,CP.id as id_date,CP.start as inicio,CP.end as fin,concat(P.Last_name,', ',P.First_name) as title,concat(E.Last_name,', ',E.First_name) as employee,T.color as color,L.name as locations,CR.*  FROM calendar_appointments C
        left join calendar_appoiment_date CP on(C.id=CP.calendar_appoiment_id) left join calendar_appoiment_recurring CR ON(CP.id=CR.calendar_date) left join patients P on(C.Pat_id=P.id) left join employee E on (C.therapist_id=E.id) left join tbl_type_appoiments T on (C.type=T.id) left join tbl_location_appoiments L on (C.location=L.id) WHERE  C.Pat_id IN (".$valor_patient.") AND C.therapist_id IN (".$valor_user.") AND CP.start BETWEEN '".$inicio."' AND '".$fin."' ORDER BY CP.start ASC";

    }
}else{
    
    if($patient_vacio=='si'){
        
         $sql="select C.*,C.id as id_calendar,CP.*,CP.start as inicio,CP.end as fin,concat(P.Last_name,', ',P.First_name) as title,concat(E.Last_name,', ',E.First_name) as employee,T.color as color,L.name as locations,CR.*  FROM calendar_appointments C
        left join calendar_appoiment_date CP on(C.id=CP.calendar_appoiment_id) left join calendar_appoiment_recurring CR ON(CP.id=CR.calendar_date) left join patients P on(C.Pat_id=P.id) left join employee E on (C.therapist_id=E.id) left join tbl_type_appoiments T on (C.type=T.id) left join tbl_location_appoiments L on (C.location=L.id) WHERE  C.therapist_id IN (".$usuario_own.") AND CP.start BETWEEN '".$inicio."' AND '".$fin."' ORDER BY CP.start ASC";

    }
    
    if($patient_vacio!='si'){
       
        $sql="select C.*,C.id as id_calendar,CP.*,CP.start as inicio,CP.end as fin,concat(P.Last_name,', ',P.First_name) as title,concat(E.Last_name,', ',E.First_name) as employee,T.color as color,L.name as locations,CR.*  FROM calendar_appointments C
        left join calendar_appoiment_date CP on(C.id=CP.calendar_appoiment_id) left join calendar_appoiment_recurring CR ON(CP.id=CR.calendar_date) left join patients P on(C.Pat_id=P.id) left join employee E on (C.therapist_id=E.id) left join tbl_type_appoiments T on (C.type=T.id) left join tbl_location_appoiments L on (C.location=L.id) WHERE  C.Pat_id IN (".$valor_patient.") AND C.therapist_id IN (".$usuario_own.") AND CP.start BETWEEN '".$inicio."' AND '".$fin."' ORDER BY CP.start ASC";

    }
}
$json = ejecutar($sql,$conexion);
$i=0;
while ($row=mysqli_fetch_array($json)) {
    $arreglo[] = $row;                    
    $i++;        
} 

for($i=0;$i<count($arreglo);$i++){
    
    if($arreglo[$i]['title']==''){
        $arreglo[$i]['title']=' ';
    }
}
///*******************************agregar mas eventos al calendario con nuevas consultas***************************///

//consulto presuntos eventos en careplans
$sql_careplan="SELECT id_careplans,concat(Last_name,', ',First_name) as title,POC_due FROM careplans WHERE status=1 AND POC_due BETWEEN '".$inicio."' AND '".$fin."'";
$json_careplan = ejecutar($sql_careplan,$conexion);
while ($row_careplan=mysqli_fetch_array($json_careplan)) {
    $valor_careplan[] = $row_careplan; 
} 
for($i=0;$i<count($valor_careplan);$i++){
    $valor_careplans['title'] = $valor_careplan[$i]['title'];
    $valor_careplans['start'] = $valor_careplan[$i]['POC_due'];
    $valor_careplans['end'] = $valor_careplan[$i]['POC_due'];
    $valor_careplans['inicio'] = $valor_careplan[$i]['POC_due'];
    $valor_careplans['fin'] = $valor_careplan[$i]['POC_due'];
    $valor_careplans['allDay'] = true;
    $valor_careplans['dia_completo']='si';
    $valor_careplans['color']='red';
    array_push($arreglo, $valor_careplans);
}
echo json_encode($arreglo);