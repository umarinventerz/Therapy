<?php
session_start();
include("../../../../mail/phpmailer.php");
require_once("../../../../conex.php");
require_once("../date.php");
require_once '../../../modelo/ScheduledEmails_RingCentral.php';
require_once '../../../modelo/ringcentral.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
    $conexion = conectar();    
    
    $data  = "SELECT * FROM calendar_time";
    $row = ejecutar($data,$conexion);
    while($datos = mysqli_fetch_assoc($row)) {            
            $time_result = $datos['time'];

    }
    
    $objeto=new CalculosFecha();
    $hora_bd=$objeto->Hora($time_result);
    $hora_bd_hour_minute=  explode(':', $hora_bd);
    //hora BD
    $hora_seteada=$hora_bd_hour_minute[0].":".$hora_bd_hour_minute[1];    
    //hora actual del servidor
    $hoy_actual = date("H:i");
    
    if($hora_seteada==$hoy_actual){
    
        $dia_manana = date('d',time()+84600);
        $mes_manana = date('m',time()+84600);
        $ano_manana = date('Y',time()+84600);
        $fecha=$ano_manana."-".$mes_manana."-".$dia_manana;   

        $sql="select C.*,C.id as id_calendar,CP.*,CP.id as id_date,CP.start as inicio,CP.end as fin,concat(P.Last_name,', ',P.First_name) as full_name,P.Phone as phone_paciente,concat(E.Last_name,', ',E.First_name) as employee,E.phone_number as phone_empleado  FROM calendar_appointments C
        left join calendar_appoiment_date CP on(C.id=CP.calendar_appoiment_id)             
        left join patients P on(C.Pat_id=P.id) 
        left join employee E on (C.therapist_id=E.id)              
        WHERE date(CP.start)='".$fecha."' AND C.attendance=1 ORDER BY CP.start ASC LIMIT 50";
        
        $json = ejecutar($sql,$conexion);
        $i=0;
        while ($row=mysqli_fetch_array($json)) {
            $arreglo[] = $row;                    
            $i++;        
        } 
        
        for($i=0;$i<count($arreglo);$i++){
            
            $sms_send= new ScheduledEmails();
            //mensaje para terapista  
            $body="They are reminded of the appointment scheduled for the day ".$arreglo[$i]['start']." with the patient ".$arreglo[$i]['full_name'];
            $sms_send->send_sms_terapista($arreglo[$i]['phone_empleado'],$body);  
            //mensaje paciente
            $body="They are reminded of the appointment scheduled for the day ".$arreglo[$i]['start']." with the doctor ".$arreglo[$i]['employee'];
            $sms_send->send_sms_terapista($arreglo[$i]['phone_paciente'],$body);
            
            //actualizo los appoiments            
            $update = "UPDATE calendar_appointments SET attendance=1 WHERE id=".$arreglo[$i]['id_calendar'];
            $resultado = ejecutar($update,$conexion); 
    
        }     
        
    }