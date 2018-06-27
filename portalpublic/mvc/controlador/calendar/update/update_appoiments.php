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

$id_calendar=$_POST['id_calendar'];
$id_date=$_POST['id_date'];
$pat_id=$_POST['pat_id'];
$therapista_id=$_POST['therapista_id'];
$subject=$_POST['subject'];
$location=$_POST['location'];
$date=$_POST['date'];
$type=$_POST['type'];
$star_time=$_POST['star_time'];
$date_start=  explode(' ', $star_time);
$end_time=$_POST['end_time'];
$date_end=  explode(' ', $end_time);
$attendance=$_POST['attendance'];
$conflicto=$_POST['conflicto'];
$type_appoiments=$_POST['type_appoiments'];
$hour_mon=$_POST['hour_mon'];
$hour_mon_to=$_POST['hour_mon_to'];
$hour_tue=$_POST['hour_tue'];
$hour_tue_to=$_POST['hour_tue_to'];
$hour_wed=$_POST['hour_wed'];
$hour_wed_to=$_POST['hour_wed_to'];
$hour_thu=$_POST['hour_thu'];
$hour_thu_to=$_POST['hour_thu_to'];
$hour_fri=$_POST['hour_fri'];
$hour_fri_to=$_POST['hour_fri_to'];
$hour_sat=$_POST['hour_sat'];
$hour_sat_to=$_POST['hour_sat_to'];
$hour_sun=$_POST['hour_sun'];
$hour_sun_to=$_POST['hour_sun_to'];
$quantity=$_POST['quantity'];
$period=$_POST['period'];
$cuantos=$_POST['cuantos'];
$sms_terapista=$_POST['sms_terapista'];
$sms_paciente=$_POST['sms_paciente'];


if($period=='weeks'){
        
    $type_period=1;
}else{
    $type_period=2;
}

if($type_appoiments==1){

    $update = "UPDATE calendar_appointments SET Pat_id='".$pat_id."', therapist_id='".$therapista_id."',subject='".$subject."',location='".$location."',date='".$date."',type='".$type."',attendance='".$attendance."',user_id='".$_SESSION['user_id']."' WHERE id=".$id_calendar;
    $resultado = ejecutar($update,$conexion); 
    $update_date = "UPDATE calendar_appoiment_date SET start='".$star_time."',end='".$end_time."' WHERE id=".$id_date;
    $resultado_date = ejecutar($update_date,$conexion); 

           
    //consultando datos de employee
    $phone_sms  = "SELECT phone_number,first_name,last_name FROM employee where id=".$therapista_id;
    $last_phone_sms = ejecutar($phone_sms,$conexion); 


    while($datos_phone = mysqli_fetch_assoc($last_phone_sms)) {            
        $number_phone = $datos_phone['phone_number'];
        $first_name_employee = $datos_phone['first_name'];
        $last_name_employee = $datos_phone['last_name'];

    }

    //consultando datos de patients
    $datos_patients  = "SELECT Phone,First_name,Last_name FROM patients where id=".$therapista_id;
    $last_datos_patients = ejecutar($datos_patients,$conexion); 


    while($datos_datos_patients = mysqli_fetch_assoc($last_datos_patients)) {            
        $number_patients = $datos_datos_patients['Phone'];
        $first_name_patients = $datos_datos_patients['First_name'];
        $last_name_patients = $datos_datos_patients['Last_name'];

    }

    if($sms_terapista=='si'){
        $body="The appointment scheduled with the patient ".$first_name_patients.",".$last_name_patients."has been modified for From:".$star_time." To:".$end_time;
        $sms_send= new ScheduledEmails();
        $sms_send->send_sms_terapista($number_phone,$body);
    }

    if($sms_paciente=='si'){
        $body="The appointment scheduled with the doctor ".$first_name_employee.",".$last_name_employee."has been modified for From:".$star_time." To:".$end_time;
        $sms_send= new ScheduledEmails();
        $sms_send->send_sms_terapista($number_patients,$body);
    }

}

if($type_appoiments==2){
    
    $update = "UPDATE calendar_appointments SET Pat_id='".$pat_id."', therapist_id='".$therapista_id."',subject='".$subject."',location='".$location."',date='".$date."',type='".$type."',attendance='".$attendance."',user_id='".$_SESSION['user_id']."' WHERE id=".$id_calendar;
    $resultado = ejecutar($update,$conexion); 
        
    $sql='SELECT period,quantity FROM calendar_appointments WHERE id='.$id_calendar;
    $json = ejecutar($sql,$conexion);
    $i=0;
    while ($row=mysqli_fetch_array($json)) {
        $arreglo['period'] = $row['period'];  
        $arreglo['quantity'] = $row['quantity'];  
        $i++;        
    }
    if($quantity==$arreglo['quantity'] && $period==$arreglo['period']){
        
        $sql_recurrin="select * from calendar_appoiment_recurring where calendar_appoiment_id=".$id_calendar;
        $ejecutar = ejecutar($sql_recurrin,$conexion);
        $k=0;
        while ($row=mysqli_fetch_array($ejecutar)){
            $day_recurrin['monday'] = $row['monday'];  
            $day_recurrin['tuesday'] = $row['tuesday'];  
            $day_recurrin['wednesday'] = $row['wednesday'];  
            $day_recurrin['thursday'] = $row['thursday'];  
            $day_recurrin['friday'] = $row['friday'];  
            $day_recurrin['saturday'] = $row['saturday'];  
            $day_recurrin['sunday'] = $row['sunday'];  
            $k++;        
        }
        if($day_recurrin['monday']!=' '){
            $day_recurrin_seteado[]='monday';
        }
        if($day_recurrin['tuesday']!=' '){
            $day_recurrin_seteado[]='tuesday';
        }
        if($day_recurrin['wednesday']!=' '){
            $day_recurrin_seteado[]='wednesday';
        }
        if($day_recurrin['thursday']!=' '){
            $day_recurrin_seteado[]='thursday';
        }
        if($day_recurrin['friday']!=' '){
            $day_recurrin_seteado[]='friday';
        }
        if($day_recurrin['saturday']!=' '){
            $day_recurrin_seteado[]='saturday';
        }
        if($day_recurrin['sunday']!=' '){
            $day_recurrin_seteado[]='sunday';
        }
        
        
       $objeto=new CalculosFecha();
       
        //actualizaciones   
        
        if($hour_mon !=''){          
            if(in_array('monday', $day_recurrin_seteado)){
                if($cuantos=='all'){
                    $update_monday = "UPDATE calendar_appoiment_recurring SET monday='".$hour_mon." ".$hour_mon_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{                    
                    $update_monday = "UPDATE calendar_appoiment_recurring SET monday='".$hour_mon." ".$hour_mon_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_monday = ejecutar($update_monday,$conexion);
                
                //consulto los datos del registro a actualizar
                $sql_search="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Monday' AND DATE(start)='".$date_start[0]."' AND DATE(end)='".$date_end[0]."'";
                $json = ejecutar($sql_search,$conexion);
                $i=0;
                while ($row=mysqli_fetch_array($json)){
                    $valor['id'] = $row['id'];
                    $valor['start'] = $row['start'];
                    $valor['end'] = $row['end'];
                    $i++;        
                }
                
                //actualizo en calendar_appoiment_date
                $hora_inicio_act=$objeto->Hora($hour_mon);
                $hora_fin_act=$objeto->Hora($hour_mon_to);                
                if($cuantos=='all'){
                    $sql_search_date="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Monday';";
                    $json_date = ejecutar($sql_search_date,$conexion);
                    $i=0;
                    while ($row_date=mysqli_fetch_array($json_date)){
                        $valor_date[] = $row_date;
                        
                        $i++;        
                    }
                    
                    for($i=0;$i<count($valor_date);$i++){
                        
                        $variable_start=explode(' ',$valor_date[$i]['start']);
                        $fecha_seteada_start_thu=$variable_start[0]." ".$hora_inicio_act;
                        $variable_end=explode(' ',$valor_date[$i]['end']);
                        $fecha_seteada_end_thu=$variable_start[0]." ".$hora_fin_act;
                        $update_monday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start_thu."',end='".$fecha_seteada_end_thu."' WHERE id=".$valor_date[$i]['id'];
                        $resultado_monday_date = ejecutar($update_monday_date,$conexion);
                    } 
                    
                }else{
                    $variable_start=explode(' ',$valor['start']);
                    $fecha_seteada_start=$variable_start[0]." ".$hora_inicio_act;
                    $variable_end=explode(' ',$valor['end']);
                    $fecha_seteada_end=$variable_start[0]." ".$hora_fin_act;
                    $update_monday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start."',end='".$fecha_seteada_end."' WHERE id=".$valor['id'];
                    $resultado_monday_date = ejecutar($update_monday_date,$conexion);
                }           
                
                $valor='';
                $valor_date='';
            }else{
                $monday['monday']=$objeto->lunes($quantity, $type_period);
                if($cuantos=='all'){
                    $update_monday = "UPDATE calendar_appoiment_recurring SET monday='".$hour_mon." ".$hour_mon_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_monday = "UPDATE calendar_appoiment_recurring SET monday='".$hour_mon." ".$hour_mon_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_monday = ejecutar($update_monday,$conexion);
            }
        }else{
            
            if($day_recurrin['monday']!=' '){
                
                $delete_date = "DELETE FROM calendar_appoiment_date WHERE id=".$id_date;
                $resultado_date = ejecutar($delete_date,$conexion);
                
                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_date=".$id_date;
                $resultado_recurring = ejecutar($delete_recurring,$conexion);
            }
            
        }
        if($hour_tue !=''){          
            if(in_array('tuesday', $day_recurrin_seteado)){
                if($cuantos=='all'){
                    $update_tuesday = "UPDATE calendar_appoiment_recurring SET tuesday='".$hour_tue." ".$hour_tue_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_tuesday = "UPDATE calendar_appoiment_recurring SET tuesday='".$hour_tue." ".$hour_tue_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_tuesday = ejecutar($update_tuesday,$conexion);
                
                //consulto los datos del registro a actualizar
                $sql_search="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Tuesday' AND DATE(start)='".$date_start[0]."' AND DATE(end)='".$date_end[0]."'";
                $json = ejecutar($sql_search,$conexion);
                $i=0;
                while ($row=mysqli_fetch_array($json)){
                    $valor['id'] = $row['id'];
                    $valor['start'] = $row['start'];
                    $valor['end'] = $row['end'];
                    $i++;        
                }
                
                //actualizo en calendar_appoiment_date
                $hora_inicio_act=$objeto->Hora($hour_tue);
                $hora_fin_act=$objeto->Hora($hour_tue_to);
                 
                if($cuantos=='all'){
                    $sql_search_date="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Tuesday';";
                    $json_date = ejecutar($sql_search_date,$conexion);
                    $i=0;
                    while ($row_date=mysqli_fetch_array($json_date)){
                        $valor_date[] = $row_date;
                        
                        $i++;        
                    }
                    for($i=0;$i<count($valor_date);$i++){
                        
                        $variable_start=explode(' ',$valor_date[$i]['start']);
                        $fecha_seteada_start_thu=$variable_start[0]." ".$hora_inicio_act;
                        $variable_end=explode(' ',$valor_date[$i]['end']);
                        $fecha_seteada_end_thu=$variable_start[0]." ".$hora_fin_act;
                        $update_tuesday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start_thu."',end='".$fecha_seteada_end_thu."' WHERE id=".$valor_date[$i]['id'];
                        $resultado_tuesday_date = ejecutar($update_tuesday_date,$conexion);
                    }                    
                    
                }else{
                    $variable_start=explode(' ',$valor['start']);
                    $fecha_seteada_start=$variable_start[0]." ".$hora_inicio_act;
                    $variable_end=explode(' ',$valor['end']);
                    $fecha_seteada_end=$variable_start[0]." ".$hora_fin_act; 
                    $update_tuesday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start."',end='".$fecha_seteada_end."' WHERE id=".$valor['id'];
                    $resultado_tuesday_date = ejecutar($update_tuesday_date,$conexion);
                }
                
                
                $valor='';
                $valor_date='';
            }else{
                $tuesday['tuesday']=$objeto->martes($quantity, $type_period);
                if($cuantos=='all'){
                    $update_tuesday = "UPDATE calendar_appoiment_recurring SET tuesday='".$hour_tue." ".$hour_tue_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_tuesday = "UPDATE calendar_appoiment_recurring SET tuesday='".$hour_tue." ".$hour_tue_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_tuesday = ejecutar($update_tuesday,$conexion);
            }
        }else{
            
            if($day_recurrin['tuesday']!=' '){
                
                $delete_date = "DELETE FROM calendar_appoiment_date WHERE id=".$id_date;
                $resultado_date = ejecutar($delete_date,$conexion);
                
                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_date=".$id_date;
                $resultado_recurring = ejecutar($delete_recurring,$conexion);
            }
            
        }
        if($hour_wed !=''){          
            if(in_array('wednesday', $day_recurrin_seteado)){ 
                if($cuantos=='all'){
                    $update_wednesday = "UPDATE calendar_appoiment_recurring SET wednesday='".$hour_wed." ".$hour_wed_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_wednesday = "UPDATE calendar_appoiment_recurring SET wednesday='".$hour_wed." ".$hour_wed_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_wednesday = ejecutar($update_wednesday,$conexion);
                
                //consulto los datos del registro a actualizar
                $sql_search="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Wednesday' AND DATE(start)='".$date_start[0]."' AND DATE(end)='".$date_end[0]."'";
                $json = ejecutar($sql_search,$conexion);
                $i=0;
                while ($row=mysqli_fetch_array($json)){
                    $valor['id'] = $row['id'];
                    $valor['start'] = $row['start'];
                    $valor['end'] = $row['end'];
                    $i++;        
                }
                
                //actualizo en calendar_appoiment_date
                $hora_inicio_act=$objeto->Hora($hour_wed);
                $hora_fin_act=$objeto->Hora($hour_wed_to);
                  
                if($cuantos=='all'){
                    $sql_search_date="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Wednesday';";
                    $json_date = ejecutar($sql_search_date,$conexion);
                    $i=0;
                    while ($row_date=mysqli_fetch_array($json_date)){
                        $valor_date[] = $row_date;
                        
                        $i++;        
                    }
                    for($i=0;$i<count($valor_date);$i++){
                        
                        $variable_start=explode(' ',$valor_date[$i]['start']);
                        $fecha_seteada_start_thu=$variable_start[0]." ".$hora_inicio_act;
                        $variable_end=explode(' ',$valor_date[$i]['end']);
                        $fecha_seteada_end_thu=$variable_start[0]." ".$hora_fin_act;
                        $update_wednesday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start_thu."',end='".$fecha_seteada_end_thu."' WHERE id=".$valor_date[$i]['id'];
                        $resultado_wednesday_date = ejecutar($update_wednesday_date,$conexion);
                    }
                    
                }else{
                    $variable_start=explode(' ',$valor['start']);
                    $fecha_seteada_start=$variable_start[0]." ".$hora_inicio_act;
                    $variable_end=explode(' ',$valor['end']);
                    $fecha_seteada_end=$variable_start[0]." ".$hora_fin_act; 
                    $update_wednesday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start."',end='".$fecha_seteada_end."' WHERE id=".$valor['id'];
                    $resultado_wednesday_date = ejecutar($update_wednesday_date,$conexion);
                }
                
                $valor='';
                $valor_date='';
               
            }else{
                $wednesday['wednesday']=$objeto->miercoles($quantity, $type_period);
                if($cuantos=='all'){
                    $update_wednesday = "UPDATE calendar_appoiment_recurring SET wednesday='".$hour_wed." ".$hour_wed_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_wednesday = "UPDATE calendar_appoiment_recurring SET wednesday='".$hour_wed." ".$hour_wed_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_wednesday = ejecutar($update_wednesday,$conexion);
                
            }
        }else{
            
            if($day_recurrin['wednesday']!=' '){
                
                $delete_date = "DELETE FROM calendar_appoiment_date WHERE id=".$id_date;
                $resultado_date = ejecutar($delete_date,$conexion);
                
                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_date=".$id_date;
                $resultado_recurring = ejecutar($delete_recurring,$conexion);
            }
            
        }
        if($hour_thu !=''){          
            if(in_array('thursday', $day_recurrin_seteado)){
                if($cuantos=='all'){
                    $update_thursday = "UPDATE calendar_appoiment_recurring SET thursday='".$hour_thu." ".$hour_thu_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_thursday = "UPDATE calendar_appoiment_recurring SET thursday='".$hour_thu." ".$hour_thu_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_thursday = ejecutar($update_thursday,$conexion);
                
                //consulto los datos del registro a actualizar
                $sql_search="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Thursday' AND DATE(start)='".$date_start[0]."' AND DATE(end)='".$date_end[0]."'";
                $json = ejecutar($sql_search,$conexion);
                $i=0;
                while ($row=mysqli_fetch_array($json)){
                    $valor['id'] = $row['id'];
                    $valor['start'] = $row['start'];
                    $valor['end'] = $row['end'];
                    $i++;        
                }
                
                $hora_inicio_act=$objeto->Hora($hour_thu);
                $hora_fin_act=$objeto->Hora($hour_thu_to);
                
                if($cuantos=='all'){
                    $sql_search_date="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Thursday';";
                    $json_date = ejecutar($sql_search_date,$conexion);
                    $i=0;
                    while ($row_date=mysqli_fetch_array($json_date)){
                        $valor_date[] = $row_date;
                        
                        $i++;        
                    }
                    for($i=0;$i<count($valor_date);$i++){
                        
                        $variable_start=explode(' ',$valor_date[$i]['start']);
                        $fecha_seteada_start_thu=$variable_start[0]." ".$hora_inicio_act;
                        $variable_end=explode(' ',$valor_date[$i]['end']);
                        $fecha_seteada_end_thu=$variable_start[0]." ".$hora_fin_act;
                        $update_thursday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start_thu."',end='".$fecha_seteada_end_thu."' WHERE id=".$valor_date[$i]['id'];
                        $resultado_thursday_date = ejecutar($update_thursday_date,$conexion);
                    }
                    
                }else{
                    //actualizo en calendar_appoiment_date
                                    
                    $variable_start=explode(' ',$valor['start']);
                    $fecha_seteada_start_thu=$variable_start[0]." ".$hora_inicio_act;
                    $variable_end=explode(' ',$valor['end']);
                    $fecha_seteada_end_thu=$variable_start[0]." ".$hora_fin_act;
                    $update_thursday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start_thu."',end='".$fecha_seteada_end_thu."' WHERE id=".$valor['id'];
                    $resultado_thursday_date = ejecutar($update_thursday_date,$conexion);
                }      
                
               $valor='';
               $valor_date='';
            }else{
                $thursday['thursady']=$objeto->jueves($quantity, $type_period);
                if($cuantos=='all'){
                    $update_thursday = "UPDATE calendar_appoiment_recurring SET thursday='".$hour_thu." ".$hour_thu_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_thursday = "UPDATE calendar_appoiment_recurring SET thursday='".$hour_thu." ".$hour_thu_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_thursday = ejecutar($update_thursday,$conexion);
            }
            
            
        }else{
            
            if($day_recurrin['thursady']!=' '){
                
                $delete_date = "DELETE FROM calendar_appoiment_date WHERE id=".$id_date;
                $resultado_date = ejecutar($delete_date,$conexion);
                
                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_date=".$id_date;
                $resultado_recurring = ejecutar($delete_recurring,$conexion);
            }
            
        }
        if($hour_fri !=''){          
            if(in_array('friday', $day_recurrin_seteado)){    
                if($cuantos=='all'){
                    $update_friday = "UPDATE calendar_appoiment_recurring SET friday='".$hour_fri." ".$hour_fri_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_friday = "UPDATE calendar_appoiment_recurring SET friday='".$hour_fri." ".$hour_fri_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_friday = ejecutar($update_friday,$conexion);
                
                //consulto los datos del registro a actualizar
                $sql_search="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Friday' AND DATE(start)='".$date_start[0]."' AND DATE(end)='".$date_end[0]."'";
                $json = ejecutar($sql_search,$conexion);
                $i=0;
                while ($row=mysqli_fetch_array($json)){
                    $valor['id'] = $row['id'];
                    $valor['start'] = $row['start'];
                    $valor['end'] = $row['end'];
                    $i++;        
                }
                
                //actualizo en calendar_appoiment_date
                $hora_inicio_act=$objeto->Hora($hour_fri);
                $hora_fin_act=$objeto->Hora($hour_fri_to);
                
                if($cuantos=='all'){
                    $sql_search_date="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Friday';";
                    $json_date = ejecutar($sql_search_date,$conexion);
                    $i=0;
                    while ($row_date=mysqli_fetch_array($json_date)){
                        $valor_date[] = $row_date;
                        
                        $i++;        
                    }
                    for($i=0;$i<count($valor_date);$i++){
                        
                        $variable_start=explode(' ',$valor_date[$i]['start']);
                        $fecha_seteada_start_thu=$variable_start[0]." ".$hora_inicio_act;
                        $variable_end=explode(' ',$valor_date[$i]['end']);
                        $fecha_seteada_end_thu=$variable_start[0]." ".$hora_fin_act;
                        $update_friday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start_thu."',end='".$fecha_seteada_end_thu."' WHERE id=".$valor_date[$i]['id'];
                        $resultado_friday_date = ejecutar($update_friday_date,$conexion);
                    }                    
                    
                }else{
                    $variable_start=explode(' ',$valor['start']);
                    $fecha_seteada_start=$variable_start[0]." ".$hora_inicio_act;
                    $variable_end=explode(' ',$valor['end']);
                    $fecha_seteada_end=$variable_start[0]." ".$hora_fin_act; 
                    $update_friday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start."',end='".$fecha_seteada_end."' WHERE id=".$valor['id'];
                    $resultado_friday_date = ejecutar($update_friday_date,$conexion);
                }
                
                $valor='';
                $valor_date='';
               
            }else{
                $friday['friday']=$objeto->viernes($quantity, $type_period);
                if($cuantos=='all'){
                    $update_friday = "UPDATE calendar_appoiment_recurring SET friday='".$hour_fri." ".$hour_fri_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_friday = "UPDATE calendar_appoiment_recurring SET friday='".$hour_fri." ".$hour_fri_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_friday = ejecutar($update_friday,$conexion);
            }
        }else{
            
            if($day_recurrin['friday']!=' '){
                
                $delete_date = "DELETE FROM calendar_appoiment_date WHERE id=".$id_date;
                $resultado_date = ejecutar($delete_date,$conexion);
                
                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_date=".$id_date;
                $resultado_recurring = ejecutar($delete_recurring,$conexion);
            }
            
        }
        if($hour_sat !=''){          
            if(in_array('saturday', $day_recurrin_seteado)){   
                if($cuantos=='all'){
                    $update_saturday = "UPDATE calendar_appoiment_recurring SET saturday='".$hour_sat." ".$hour_sat_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_saturday = "UPDATE calendar_appoiment_recurring SET saturday='".$hour_sat." ".$hour_sat_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_saturday = ejecutar($update_saturday,$conexion);
                
                //consulto los datos del registro a actualizar
                $sql_search="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Saturday' AND DATE(start)='".$date_start[0]."' AND DATE(end)='".$date_end[0]."'";
                $json = ejecutar($sql_search,$conexion);
                $i=0;
                while ($row=mysqli_fetch_array($json)){
                    $valor['id'] = $row['id'];
                    $valor['start'] = $row['start'];
                    $valor['end'] = $row['end'];
                    $i++;        
                }
                
                //actualizo en calendar_appoiment_date
                $hora_inicio_act=$objeto->Hora($hour_sat);
                $hora_fin_act=$objeto->Hora($hour_sat_to);                  
                if($cuantos=='all'){
                    
                    $sql_search_date="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Saturday';";
                    $json_date = ejecutar($sql_search_date,$conexion);
                    $i=0;
                    while ($row_date=mysqli_fetch_array($json_date)){
                        $valor_date[] = $row_date;
                        
                        $i++;        
                    }
                    for($i=0;$i<count($valor_date);$i++){
                        
                        $variable_start=explode(' ',$valor_date[$i]['start']);
                        $fecha_seteada_start_thu=$variable_start[0]." ".$hora_inicio_act;
                        $variable_end=explode(' ',$valor_date[$i]['end']);
                        $fecha_seteada_end_thu=$variable_start[0]." ".$hora_fin_act;
                        $update_saturday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start_thu."',end='".$fecha_seteada_end_thu."' WHERE id=".$valor_date[$i]['id'];
                        $resultado_saturday_date = ejecutar($update_saturday_date,$conexion);
                    }
                    
                }else{
                    $variable_start=explode(' ',$valor['start']);
                    $fecha_seteada_start=$variable_start[0]." ".$hora_inicio_act;
                    $variable_end=explode(' ',$valor['end']);
                    $fecha_seteada_end=$variable_start[0]." ".$hora_fin_act;
                    $update_saturday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start."',end='".$fecha_seteada_end."' WHERE id=".$valor['id'];
                    $resultado_saturday_date = ejecutar($update_saturday_date,$conexion);
                }
                
                $valor='';
                $valor_date='';
            }else{
                $saturday['saturday']=$objeto->sabado($quantity, $type_period);
                if($cuantos=='all'){
                    $update_saturday = "UPDATE calendar_appoiment_recurring SET saturday='".$hour_sat." ".$hour_sat_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_saturday = "UPDATE calendar_appoiment_recurring SET saturday='".$hour_sat." ".$hour_sat_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_saturday = ejecutar($update_saturday,$conexion);
            }
        }else{
            
            if($day_recurrin['saturday']!=' '){
                
                $delete_date = "DELETE FROM calendar_appoiment_date WHERE id=".$id_date;
                $resultado_date = ejecutar($delete_date,$conexion);
                
                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_date=".$id_date;
                $resultado_recurring = ejecutar($delete_recurring,$conexion);
            }
            
        }
        if($hour_sun !=''){          
            if(in_array('sunday', $day_recurrin_seteado)){ 
                if($cuantos=='all'){
                    $update_sunday = "UPDATE calendar_appoiment_recurring SET sunday='".$hour_sun." ".$hour_sun_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_sunday = "UPDATE calendar_appoiment_recurring SET sunday='".$hour_sun." ".$hour_sun_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_sunday = ejecutar($update_sunday,$conexion);
                
                //consulto los datos del registro a actualizar
                $sql_search="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Sunday' AND DATE(start)='".$date_start[0]."' AND DATE(end)='".$date_end[0]."'";
                $json = ejecutar($sql_search,$conexion);
                $i=0;
                while ($row=mysqli_fetch_array($json)){
                    $valor['id'] = $row['id'];
                    $valor['start'] = $row['start'];
                    $valor['end'] = $row['end'];
                    $i++;        
                }
                
                //actualizo en calendar_appoiment_date
                $hora_inicio_act=$objeto->Hora($hour_sun);
                $hora_fin_act=$objeto->Hora($hour_sun_to);
                 
                if($cuantos=='all'){
                    $sql_search_date="select id,start,end from calendar_appoiment_date where calendar_appoiment_id=".$id_calendar." AND DAYNAME(start)='Sunday';";
                    $json_date = ejecutar($sql_search_date,$conexion);
                    $i=0;
                    while ($row_date=mysqli_fetch_array($json_date)){
                        $valor_date[] = $row_date;
                        
                        $i++;        
                    }
                    for($i=0;$i<count($valor_date);$i++){
                        
                        $variable_start=explode(' ',$valor_date[$i]['start']);
                        $fecha_seteada_start_thu=$variable_start[0]." ".$hora_inicio_act;
                        $variable_end=explode(' ',$valor_date[$i]['end']);
                        $fecha_seteada_end_thu=$variable_start[0]." ".$hora_fin_act;
                        $update_sunday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start_thu."',end='".$fecha_seteada_end_thu."' WHERE id=".$valor_date[$i]['id'];
                        $resultado_sunday_date = ejecutar($update_sunday_date,$conexion);
                    }                    
                    
                }else{
                    $variable_start=explode(' ',$valor['start']);
                    $fecha_seteada_start=$variable_start[0]." ".$hora_inicio_act;
                    $variable_end=explode(' ',$valor['end']);
                    $fecha_seteada_end=$variable_start[0]." ".$hora_fin_act;
                    $update_sunday_date = "UPDATE calendar_appoiment_date SET start='".$fecha_seteada_start."',end='".$fecha_seteada_end."' WHERE id=".$valor['id'];
                    $resultado_sunday_date = ejecutar($update_sunday_date,$conexion);
                }
                
                $valor='';
                $valor_date='';
            }else{
                $sunday['sunday']=$objeto->domingo($quantity, $type_period);
                if($cuantos=='all'){
                    $update_sunday = "UPDATE calendar_appoiment_recurring SET sunday='".$hour_sun." ".$hour_sun_to."' WHERE calendar_appoiment_id=".$id_calendar;
                }else{
                    $update_sunday = "UPDATE calendar_appoiment_recurring SET sunday='".$hour_sun." ".$hour_sun_to."' WHERE calendar_date=".$id_date;
                }
                $resultado_sunday = ejecutar($update_sunday,$conexion);
            }
        }else{
            
            if($day_recurrin['sunday']!=' '){
                
                $delete_date = "DELETE FROM calendar_appoiment_date WHERE id=".$id_date;
                $resultado_date = ejecutar($delete_date,$conexion);
                
                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_date=".$id_date;
                $resultado_recurring = ejecutar($delete_recurring,$conexion);
            }
            
        }
        
        $data_insertar=array();
        $dia=array();
        $hora_inicio=array();
        $hora_fin=array();
        //agrego en un arreglo todos las fechas seleccionadas
        if(isset($monday)){
            array_push($data_insertar, $monday);
            $dia[]='monday';
            $hora_inicio[]=$objeto->Hora($hour_mon);
            $hora_fin[]=$objeto->Hora($hour_mon_to);
        }
        if(isset($tuesday)){
            array_push($data_insertar, $tuesday);
            $dia[]='tuesday';
            $hora_inicio[]=$objeto->Hora($hour_tue);
            $hora_fin[]=$objeto->Hora($hour_tue_to);
        }
        if(isset($wednesday)){
            array_push($data_insertar, $wednesday);
            $dia[]='wednesday';
            $hora_inicio[]=$objeto->Hora($hour_wed);
            $hora_fin[]=$objeto->Hora($hour_wed_to);
        }
        if(isset($thursday)){
            array_push($data_insertar, $thursday);
            $dia[]='thursady';
            $hora_inicio[]=$objeto->Hora($hour_thu);
            $hora_fin[]=$objeto->Hora($hour_thu_to);
        }
        if(isset($friday)){
            array_push($data_insertar, $friday);
            $dia[]='friday';
            $hora_inicio[]=$objeto->Hora($hour_fri);
            $hora_fin[]=$objeto->Hora($hour_fri_to);
        }
        if(isset($saturday)){
            array_push($data_insertar, $saturday);
            $dia[]='saturday';
            $hora_inicio[]=$objeto->Hora($hour_sat);
            $hora_fin[]=$objeto->Hora($hour_sat_to);
        }
        if(isset($sunday)){
            array_push($data_insertar, $sunday);
            $dia[]='sunday';
            $hora_inicio[]=$objeto->Hora($hour_sun);
            $hora_fin[]=$objeto->Hora($hour_sun_to);
        }
      
        for($i=0;$i<count($data_insertar);$i++){
                
                for($j=1;$j<=count($data_insertar[$i][$dia[$i]]);$j++){
                    
                    $insert_date = "INSERT into calendar_appoiment_date (calendar_appoiment_id,start,end,created_at)
                        values ('".$id_calendar."','".$data_insertar[$i][$dia[$i]][$j]." ".$hora_inicio[$i]."','".$data_insertar[$i][$dia[$i]][$j]." ".$hora_fin[$i]."','".$fecha."');";
                        $resultado = ejecutar($insert_date,$conexion);
                        
                    //guardando para cada appoiments u recurrin
                    $last_sql_date  = "SELECT max(id) as id FROM calendar_appoiment_date;";
                    $last_resultado_date = ejecutar($last_sql_date,$conexion); 
                      
                    $id_date = '';
                    while($datos_date = mysqli_fetch_assoc($last_resultado_date)) {            
                        $id_date = $datos_date['id'];
                        
                    }
                    
                    //inserto los dias seleccionados con sus horas en la tabla de calendar_appoiment_recurring
                    $insert_recurring = "INSERT into calendar_appoiment_recurring(calendar_appoiment_id,monday,tuesday,wednesday,thursday,friday,saturday,sunday,created_at,calendar_date)
                                        values ('".$id_calendar."','".$hour_mon." ".$hour_mon_to."','".$hour_tue." ".$hour_tue_to."','".$hour_wed." ".$hour_wed_to."','".$hour_thu." ".$hour_thu_to."','".$hour_fri." ".$hour_fri_to."','".$hour_sat." ".$hour_sat_to."','".$hour_sun." ".$hour_sun_to."','".$fecha."','".$id_date."');";
                                        $resultado_recurring = ejecutar($insert_recurring,$conexion);
                }
        }
        
        //codigo para envio de sms a pacientes o terapistas
        
        if($sms_terapista=='si' || $sms_paciente=='si'){
            
            //CONSULTO FECHA DE ACTUALIZACION 
            
            $date_from_to="select start,end from calendar_appoiment_date where id=".$id_date;
            $json_date = ejecutar($date_from_to,$conexion);
            $i=0;
            while ($row_date=mysqli_fetch_array($json_date)){
                
                $sms_valor['start'] = $row_date['start'];
                $sms_valor['end'] = $row_date['end'];
                $i++;        
            }
            
            //consultando datos de employee
            $phone_sms  = "SELECT phone_number,first_name,last_name FROM employee where id=".$therapista_id;
            $last_phone_sms = ejecutar($phone_sms,$conexion); 

            
            while($datos_phone = mysqli_fetch_assoc($last_phone_sms)) {            
                $number_phone = $datos_phone['phone_number'];
                $first_name_employee = $datos_phone['first_name'];
                $last_name_employee = $datos_phone['last_name'];

            }
            
            //consultando datos de patients
            $datos_patients  = "SELECT Phone,First_name,Last_name FROM patients where id=".$therapista_id;
            $last_datos_patients = ejecutar($datos_patients,$conexion); 

            
            while($datos_datos_patients = mysqli_fetch_assoc($last_datos_patients)) {            
                $number_patients = $datos_datos_patients['Phone'];
                $first_name_patients = $datos_datos_patients['First_name'];
                $last_name_patients = $datos_datos_patients['Last_name'];

            }
            
            if($sms_terapista=='si'){
                $body="The appointment scheduled with the patient ".$first_name_patients.",".$last_name_patients."has been modified for From:".$sms_valor['start']." To:".$sms_valor['end'];
                $sms_send= new ScheduledEmails();
                $sms_send->send_sms_terapista($number_phone,$body);
            }
            
            if($sms_paciente=='si'){
                $body="The appointment scheduled with the doctor ".$first_name_employee.",".$last_name_employee."has been modified for From:".$sms_valor['start']." To:".$sms_valor['end'];
                $sms_send= new ScheduledEmails();
                $sms_send->send_sms_terapista($number_patients,$body);
            }
        }
        
        die();
    }
    
}
if($resultado && $resultado_date){
    $array=array('success'=>true);
}elseif($type_appoiments==2 && $quantity==$arreglo['quantity'] && $period==$arreglo['period']){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}   
echo json_encode($array);
?>

