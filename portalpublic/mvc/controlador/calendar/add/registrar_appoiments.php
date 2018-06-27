<?php
session_start();
require_once("../../../../conex.php");
require_once("../date.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
    $conexion = conectar();

    $pat_id=$_POST['pat_id'];
    $therapista_id=$_POST['therapista_id'];
    $subject=$_POST['subject'];
    $location=$_POST['location'];
    $date=$_POST['date'];
    $type=$_POST['type'];
    $star_time=$_POST['star_time'];
    $end_time=$_POST['end_time'];
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
$companies=$_POST['companies'];

    if($period=='weeks'){
        
        $type_period=1;
    }else{
        $type_period=2;
    }
    if($type_appoiments==1){
            if($conflicto=='no'){

                    $sql="SELECT count(*) as contador FROM calendar_appoiment_date CP 
               				LEFT JOIN calendar_appointments C on(C.id=CP.calendar_appoiment_id) 
                    		WHERE   
                     		((CP.start>='".$star_time."' AND CP.start<='".$end_time."') OR
       						(CP.end>='".$star_time."' AND CP.end<='".$end_time."'))
      						AND 	( (C.Pat_id='".$pat_id."' AND C.Pat_id!='' )  OR  (C.therapist_id='".$therapista_id."' AND C.therapist_id!='') )

                    				# CP.start between '".$star_time."' AND '".$end_time."' 
                   						# AND (C.Pat_id='".$pat_id."' OR C.therapist_id='".$therapista_id."')
                  
                   				";
                    $json = ejecutar($sql,$conexion);
                    $i=0;
                    while ($row=mysqli_fetch_array($json)) {
                        $contador = $row['contador'];                    
                        $i++;        
                    } 
                    if($contador>0){

                        $array=array('success'=>false,
                                     'duplicated'=>'si',
                                     'body'=>'<h3><div style="color: #6c1422"><b>Sorry, there is already an appointment added at the time specified for the patient or user selected.</b></div></h3><br><div align="center"><button type="button" class="btn btn-danger" onclick="conflictos(\''.$pat_id.'\',\''.$therapista_id.'\',\''.$subject.'\',\''.$location.'\',\''.$date.'\',\''.$recurring.'\',\''.$next_weeks.'\',\''.$biweekly.'\',\''.$type.'\',\''.$star_time.'\',\''.$end_time.'\',\''.$attendance.'\');">Ignore conflicts</button></div>');
                        echo json_encode($array);

                    }else{
                        $insert = "INSERT into calendar_appointments (Pat_id,therapist_id,subject,location,date,type,attendance,user_id,quantity,period,type_appoiment)
                        values ('".$pat_id."','".$therapista_id."','".$subject."','".$location."','".$date."','".$type."','".$attendance."','".$_SESSION['user_id']."','".$quantity."','N/A','time');";
                        $resultado = ejecutar($insert,$conexion);
                            
                           
                        //obteniendo el ultimo id insertado en calendar appoiments            
                        $last_sql  = "SELECT max(id) as id FROM calendar_appointments;";
                        $last_resultado = ejecutar($last_sql,$conexion); 
                        $j = 0;      
                        $id = '';
                        while($datos = mysqli_fetch_assoc($last_resultado)) {            
                            $id = $datos['id'];
                            $j++;
                        }
                        ///////////////////////////////////////////////////////////////
                         //// update para sacarlo del reporte de schedule_patient/////
                            /////////////////////////////////////////////////////////////
                                $consulta_discipline_count = "SELECT e.discipline_id , d.Name  , c.Pat_id
                                                                FROM calendar_appointments c
                                                                LEFT JOIN employee e on    c.therapist_id= e.id
                                                                LEFT JOIN discipline d on d.DisciplineId=e.discipline_id
                                                                where c.id='".$id."'
                                                                and e.discipline_id is not null 
                                                                and e.discipline_id!=0
                                                                and e.discipline_id!=''
                                                                and c.Pat_id is not null
                                                                and c.Pat_id!=''
                                                                and c.Pat_id!='0'
                                ; ";
                                $result_consulta = ejecutar($consulta_discipline_count,$conexion);
                                while($row = mysqli_fetch_array($result_consulta)){
                                $d_name = $row['Name'];
                                $pat_id_new = $row['Pat_id'];
                                }

                            
                            if($pat_id_new != null){
                                        $update_new_report= "UPDATE patients_copy 
                                                                SET ready_treatment_".$d_name." ='0' 
                                    
                                                                , scheduled_".$d_name."='1' 
                                                            WHERE  id='".$pat_id_new."' and ready_treatment_".$d_name." ='1'  "; 
                                        $resultado22 = ejecutar($update_new_report,$conexion);
                                }

                            //FIN DE UPDATE DE REPORTE /////

                        $fecha=date('Y-m-d');
                        $insert_date = "INSERT into calendar_appoiment_date (calendar_appoiment_id,start,end,created_at)
                        values ('".$id."','".$star_time."','".$end_time."','".$fecha."');";
                        $resultado_date = ejecutar($insert_date,$conexion);
                        
                        if($insert){
                            $array=array('success'=>true);
                        }else{
                            $array=array('success'=>false);
                        }    
                        echo json_encode($array);
                    }
            }
            if($conflicto=='si'){

                    $insert = "INSERT into calendar_appointments (company_id,Pat_id,therapist_id,subject,location,date,recurring,next_weeks,biweekly,type,start,end,attendance,user_id)
                                    values ('".$companies."','".$pat_id."','".$therapista_id."','".$subject."','".$location."','".$date."','".$recurring."','".$next_weeks."','".$biweekly."', '".$type."','".$star_time."','".$end_time."','".$attendance."','".$_SESSION['user_id']."');";
                                    $resultado = ejecutar($insert,$conexion);

                    if($insert){
                        $array=array('success'=>true);
                    }else{
                        $array=array('success'=>false);
                    }    
                    echo json_encode($array);
            }
    }
    
    if($type_appoiments==2){
        
        if($conflicto=='no'){
            
            $objeto=new CalculosFecha();
            
            if($hour_mon !=''){                
                $monday['monday']=$objeto->lunes($quantity, $type_period);
            }
            if($hour_tue !=''){                
                $tuesday['tuesday']=$objeto->martes($quantity, $type_period);
            }
            if($hour_wed !=''){                
                $wednesday['wednesday']=$objeto->miercoles($quantity, $type_period);
            }
            if($hour_thu !=''){                
                $thursday['thursady']=$objeto->jueves($quantity, $type_period);
            }
            if($hour_fri !=''){                
                $friday['friday']=$objeto->viernes($quantity, $type_period);
            }
            if($hour_sat !=''){                
                $saturday['saturday']=$objeto->sabado($quantity, $type_period);
            }
            if($hour_sun !=''){                
                $sunday['sunday']=$objeto->domingo($quantity, $type_period);
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
                $hora_fin[]=$hour_mon_to;
            }
            if(isset($tuesday)){
                array_push($data_insertar, $tuesday);
                $dia[]='tuesday';
                $hora_inicio[]=$objeto->Hora($hour_tue);
                $hora_fin[]=$hour_tue_to;
            }
            if(isset($wednesday)){
                array_push($data_insertar, $wednesday);
                $dia[]='wednesday';
                $hora_inicio[]=$objeto->Hora($hour_wed);
                $hora_fin[]=$hour_wed_to;
            }
            if(isset($thursday)){
                array_push($data_insertar, $thursday);
                $dia[]='thursady';
                $hora_inicio[]=$objeto->Hora($hour_thu);
                $hora_fin[]=$hour_thu_to;
            }
            if(isset($friday)){
                array_push($data_insertar, $friday);
                $dia[]='friday';
                $hora_inicio[]=$objeto->Hora($hour_fri);
                $hora_fin[]=$hour_fri_to;
            }
            if(isset($saturday)){
                array_push($data_insertar, $saturday);
                $dia[]='saturday';
                $hora_inicio[]=$objeto->Hora($hour_sat);
                $hora_fin[]=$hour_sat_to;
            }
            if(isset($sunday)){
                array_push($data_insertar, $sunday);
                $dia[]='sunday';
                $hora_inicio[]=$objeto->Hora($hour_sun);
                $hora_fin[]=$hour_sun_to;
            }
            
            //inserto en calendar appoiments
            
            $insert = "INSERT into calendar_appointments (company_id,Pat_id,therapist_id,subject,location,date,type,attendance,user_id,quantity,period,type_appoiment)
                        values ('".$companies."','".$pat_id."','".$therapista_id."','".$subject."','".$location."','".$date."','".$type."','".$attendance."','".$_SESSION['user_id']."','".$quantity."','".$period."','recurring');";
                        $resultado = ejecutar($insert,$conexion);
            
            //obteniendo el ultimo id insertado en calendar appoiments            
            $last_sql  = "SELECT max(id) as id FROM calendar_appointments;";
            $last_resultado = ejecutar($last_sql,$conexion); 
            $j = 0;      
            $id = '';
            while($datos = mysqli_fetch_assoc($last_resultado)) {            
                $id = $datos['id'];
                $j++;
            }

             ///////////////////////////////////////////////////////////////
            //// update para sacarlo del reporte de schedule_patient/////
            /////////////////////////////////////////////////////////////
                                $consulta_discipline_count = "SELECT e.discipline_id , d.Name  , c.Pat_id
                                                                FROM calendar_appointments c
                                                                LEFT JOIN employee e on    c.therapist_id= e.id
                                                                LEFT JOIN discipline d on d.DisciplineId=e.discipline_id
                                                                where c.id='".$id."'
                                                                and e.discipline_id is not null 
                                                                and e.discipline_id!=0
                                                                and e.discipline_id!=''
                                                                and c.Pat_id is not null
                                                                and c.Pat_id!=''
                                                                and c.Pat_id!='0'
                                ; ";
                                $result_consulta = ejecutar($consulta_discipline_count,$conexion);
                                while($row = mysqli_fetch_array($result_consulta)){
                                $d_name = $row['Name'];
                                $pat_id_new = $row['Pat_id'];
                                }

                             if($pat_id_new != null){
                                        $update_new_report= "UPDATE patients_copy 
                                                                SET ready_treatment_".$d_name." ='0' 
                                    
                                                                , scheduled_".$d_name."='1' 
                                                            WHERE  id='".$pat_id_new."' and ready_treatment_".$d_name." ='1'  "; 
                                        $resultado22 = ejecutar($update_new_report,$conexion);
                                }

                        //FIN DE UPDATE DE REPORTE /////


            $fecha=date('Y-m-d');
            
            for($i=0;$i<count($data_insertar);$i++){
                
                for($j=1;$j<=count($data_insertar[$i][$dia[$i]]);$j++){
                    
                    $insert_date = "INSERT into calendar_appoiment_date (calendar_appoiment_id,start,end,created_at)
                        values ('".$id."','".$data_insertar[$i][$dia[$i]][$j]." ".$hora_inicio[$i]."','".$data_insertar[$i][$dia[$i]][$j]." ".$hora_fin[$i]."','".$fecha."');";
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
                                        values ('".$id."','".$hour_mon." ".$hour_mon_to."','".$hour_tue." ".$hour_tue_to."','".$hour_wed." ".$hour_wed_to."','".$hour_thu." ".$hour_thu_to."','".$hour_fri." ".$hour_fri_to."','".$hour_sat." ".$hour_sat_to."','".$hour_sun." ".$hour_sun_to."','".$fecha."','".$id_date."');";
                                        $resultado_recurring = ejecutar($insert_recurring,$conexion);
                        
                }
            }
            if($insert){
                $array=array('success'=>true);
            }else{
                $array=array('success'=>false);
            }    
            echo json_encode($array);
        }
    }
?>
