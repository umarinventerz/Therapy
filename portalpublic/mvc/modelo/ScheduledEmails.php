<?php

class ScheduledEmails{
    
    public function send_email($id,$subject,$templates,$fecha_formateada){
        
        $smtp=new PHPMailer();
        $smtp->IsSMTP();        
        $smtp->CharSet="UTF-8";        
        $smtp->SMTPAuth   = true;						
        $smtp->Host       = "smtp.gmail.com";			
        $smtp->Username   = "kidworks.appointments@gmail.com";	
        $smtp->Password   = "Therapy2017*";
        $smtp->SMTPSecure = 'ssl';			
        $smtp->Port = 465;        
        $smtp->From       = "kidworks.appointments@gmail.com";
        $smtp->FromName   = "Therapy Aid";       
        $smtp->WordWrap   = 50;       
        
        
        $conexion = conectar();               
        //consulto las citas de los clientes para el dia siguiente a la fecha actual
        //$sql  = "SELECT * FROM appointments_cs WHERE  Pat_id='".$id."'";
        $sql="SELECT * FROM appointments_cs WHERE Pat_id='".$id."' and location='Office' and type <> 'Outside Therapy' and type <> 'Pending Authorization' and date='".$fecha_formateada."' and STR_TO_DATE(hour, '%l:%i %p') IN (SELECT MIN(STR_TO_DATE(hour, '%l:%i %p')) FROM appointments_cs WHERE Pat_id='".$id."' and location='Office' and type <> 'Outside Therapy' and type <> 'Pending Authorization' and date='".$fecha_formateada."')";
        $resultado = ejecutar($sql,$conexion); 
        $j = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$j] = $datos;
            $j++;
        }
        //capturo las variables de la base de datos y se las asigno a las creada en los templates        
        $first_name_doctor=$reporte[0]['user_first_name'];
        $last_name_doctor=$reporte[0]['user_last_name'];
        $first_name_patients=$reporte[0]['patient_first_name'];
        $last_name_patients=$reporte[0]['patient_last_name'];
        $location=$reporte[0]['location'];
        $phone=$reporte[0]['phone'];
        $date=$reporte[0]['date'];
        $hour=$reporte[0]['hour'];
        $type=$reporte[0]['type'];
        $description=$reporte[0]['description'];
        
        $sql3  = "SELECT * FROM templates WHERE  id='".$templates."'";
        $resultado3 = ejecutar($sql3,$conexion); 
        $k = 0;      
        while($datos = mysqli_fetch_assoc($resultado3)) {            
            $template[$k] = $datos;
            $k++;
        } 
        $body=$template[0]['description'];    
       
        //reemplazo el body por las variables de la base de datos
        $body_prueba1=str_replace('$first_name_patients', $first_name_patients,$body);
        $body_prueba2=str_replace('$last_name_patients', $last_name_patients,$body_prueba1);
        $body_prueba3=str_replace('$first_name_doctor', $first_name_doctor,$body_prueba2);
        $body_prueba4=str_replace('$last_name_doctor', $last_name_doctor,$body_prueba3);
        $body_prueba5=str_replace('$location', $location,$body_prueba4);
        $body_prueba6=str_replace('$phone', $phone,$body_prueba5);
        $body_prueba7=str_replace('$date', $date,$body_prueba6);
        $body_prueba8=str_replace('$hour', $hour,$body_prueba7);
        $body_prueba9=str_replace('$type', $type,$body_prueba8);
        $body_prueba10=str_replace('$description', $description,$body_prueba9);
        
        $send_description=$body_prueba10;        
        $sql1="SELECT P.Phone,PC.sms_gateway_domain FROM patients P LEFT JOIN tbl_patients_carriers PC ON(P.id_carriers=PC.id_patients_carriers) WHERE P.Pat_id=".$id;
        $resultado1 = ejecutar($sql1,$conexion); 

        while($datos = mysqli_fetch_assoc($resultado1)) {            
            $patients[] = $datos;
        }
        
        //guardo el telefono del paciente
        $phone_send=$patients[0]['Phone'];
        //depuro el telefono de ()-
        $phone_send_depurado=$this->depurar_telefono($phone_send);
        //compilo el telefono con el dominio
        $phone_send_sms=$phone_send_depurado."@".$patients[0]['sms_gateway_domain'];   
       
        ///////////////////////////////////////////////////////codigo para enviar push notification//////////////////////
            
            $notification=new PushNotification();
            $push=$notification->consultar_usuarios();
            
            $array_push=json_decode($push,true);
            
            for($i=0;$i<count($array_push);$i++){
                
                if($array_push['players'][$i]['tags']['phone']==$phone_send_depurado){
                    
                    $notification->send_push_notification($array_push['players'][$i]['id'],$send_description);
                    $send_notification=1;
                    
                }
                
            }
            
        /////////////////////////////////////////////////////fin codigo para enviar push notification//////////////////////   
           
            
        /*
        //conecto a ring central
        $rc = new RingCentral('9tREFpHoTYucwtvLEPToAQ','i06Pc5yXReKktowBnr6wlAHYkY0ShMSlmlv6w_6S7jew',RingCentral::RC_SERVER_SANDBOX );
        $rc->authorize('14242181277','','P@ssw0rd');
        
        //establezco los parametros de envio del sms
       $params = array(
           'json'     => array(
               'from' => array('phoneNumber' => '+14242181277'), // From a valid RingCentral number
               'to'   => array( array('phoneNumber' => '+1'.$phone_send_depurado)),
               'text' => $send_description
           )
       );
       //ejecuto la funcion de envio
       $resSms = $rc->post('https://platform.devtest.ringcentral.com/restapi/v1.0/account/~/extension/~/sms', $params);
       */
       if(!isset($send_notification)){
            $smtp->Subject = $subject;
            $smtp->AltBody=$send_description; //Text Body
            $smtp->MsgHTML('s');
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($phone_send_sms,'');
            $enviar=$smtp->Send();

            if($enviar){
                    $response='si';
            }else{
                $response='no';
            }
       }else{
            $response='si';
       }
        return $response;
          
    }
    
    public function send_without($id,$subject,$templates,$body,$fecha_formateada){
        
        $smtp=new PHPMailer();
        $smtp->IsSMTP();        
        $smtp->CharSet="UTF-8";        
        $smtp->SMTPAuth   = true;						
        $smtp->Host       = "smtp.gmail.com";			
        $smtp->Username   = "kidworks.appointments@gmail.com";	
        $smtp->Password   = "Therapy2017*";
        $smtp->SMTPSecure = 'ssl';			
        $smtp->Port = 465;        
        $smtp->From       = "kidworks.appointments@gmail.com";
        $smtp->FromName   = "Therapy Aid";       
        $smtp->WordWrap   = 50;       
        
        
        $conexion = conectar();               
        //consulto las citas de los clientes para el dia siguiente a la fecha actual
        //$sql  = "SELECT * FROM appointments_cs WHERE  Pat_id='".$id."'";
        $sql="SELECT * FROM appointments_cs WHERE Pat_id='".$id."' and location='Office' and type <> 'Outside Therapy' and type <> 'Pending Authorization' and date='".$fecha_formateada."' and STR_TO_DATE(hour, '%l:%i %p') IN (SELECT MIN(STR_TO_DATE(hour, '%l:%i %p')) FROM appointments_cs WHERE Pat_id='".$id."' and location='Office' and type <> 'Outside Therapy' and type <> 'Pending Authorization' and date='".$fecha_formateada."')";
        $resultado = ejecutar($sql,$conexion); 
        $j = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$j] = $datos;
            $j++;
        }
        //capturo las variables de la base de datos y se las asigno a las creada en los templates        
        $first_name_doctor=$reporte[0]['user_first_name'];
        $last_name_doctor=$reporte[0]['user_last_name'];
        $first_name_patients=$reporte[0]['patient_first_name'];
        $last_name_patients=$reporte[0]['patient_last_name'];
        $location=$reporte[0]['location'];
        $phone=$reporte[0]['phone'];
        $date=$reporte[0]['date'];
        $hour=$reporte[0]['hour'];
        $type=$reporte[0]['type'];
        $description=$reporte[0]['description'];
        
        $sql3  = "SELECT * FROM templates WHERE  id='".$templates."'";
        $resultado3 = ejecutar($sql3,$conexion); 
        $k = 0;      
        while($datos = mysqli_fetch_assoc($resultado3)) {            
            $template[$k] = $datos;
            $k++;
        } 
        //$body=$template[0]['description'];    
       
        //reemplazo el body por las variables de la base de datos
        $body_prueba1=str_replace('$first_name_patients', $first_name_patients,$body);
        $body_prueba2=str_replace('$last_name_patients', $last_name_patients,$body_prueba1);
        $body_prueba3=str_replace('$first_name_doctor', $first_name_doctor,$body_prueba2);
        $body_prueba4=str_replace('$last_name_doctor', $last_name_doctor,$body_prueba3);
        $body_prueba5=str_replace('$location', $location,$body_prueba4);
        $body_prueba6=str_replace('$phone', $phone,$body_prueba5);
        $body_prueba7=str_replace('$date', $date,$body_prueba6);
        $body_prueba8=str_replace('$hour', $hour,$body_prueba7);
        $body_prueba9=str_replace('$type', $type,$body_prueba8);
        $body_prueba10=str_replace('$description', $description,$body_prueba9);
        
        $send_description=$body_prueba10;        
         $sql1="SELECT P.Phone,PC.sms_gateway_domain FROM patients P LEFT JOIN tbl_patients_carriers PC ON(P.id_carriers=PC.id_patients_carriers) WHERE P.Pat_id=".$id;
        $resultado1 = ejecutar($sql1,$conexion); 

        while($datos = mysqli_fetch_assoc($resultado1)) {            
            $patients[] = $datos;
        }
        
        //guardo el telefono del paciente
        $phone_send=$patients[0]['Phone'];
        //depuro el telefono de ()-
        $phone_send_depurado=$this->depurar_telefono($phone_send);
        //compilo el telefono con el dominio
        $phone_send_sms=$phone_send_depurado."@".$patients[0]['sms_gateway_domain'];   
       
         ///////////////////////////////////////////////////////codigo para enviar push notification//////////////////////
            
            $notification=new PushNotification();
            $push=$notification->consultar_usuarios();
            
            $array_push=json_decode($push,true);
            
            for($i=0;$i<count($array_push);$i++){
                
                if($array_push['players'][$i]['tags']['phone']==$phone_send_depurado){
                    
                    $notification->send_push_notification($array_push['players'][$i]['id'],$send_description);
                    $send_notification=1;                 
                    
                }
                
            }
            
        /////////////////////////////////////////////////////fin codigo para enviar push notification//////////////////////   
           
        
        /*
        //conecto a ring central
        $rc = new RingCentral('9tREFpHoTYucwtvLEPToAQ','i06Pc5yXReKktowBnr6wlAHYkY0ShMSlmlv6w_6S7jew',RingCentral::RC_SERVER_SANDBOX );
        $rc->authorize('14242181277','','P@ssw0rd');
        
        //establezco los parametros de envio del sms
       $params = array(
           'json'     => array(
               'from' => array('phoneNumber' => '+14242181277'), // From a valid RingCentral number
               'to'   => array( array('phoneNumber' => '+1'.$phone_send_depurado)),
               'text' => $send_description
           )
       );
       //ejecuto la funcion de envio
       $resSms = $rc->post('https://platform.devtest.ringcentral.com/restapi/v1.0/account/~/extension/~/sms', $params);
       */
        if(!isset($send_notification)){
            $smtp->Subject = $subject;
            $smtp->AltBody=$send_description; //Text Body
            $smtp->MsgHTML('s');
            $smtp->ClearAllRecipients();
            $smtp->AddAddress($phone_send_sms,'');
            $enviar=$smtp->Send();

            if($enviar){
                    $response='si';
            }else{
                $response='no';
            }
       }else{
           $response='si';
       }
        return $response;
          
    }
    
    public function depurar_telefono($phone){
        
        $tlefono=  str_replace('(', '', $phone);
        $telefono1=str_replace(')', '', $tlefono);
        $telefono2=str_replace('-', '', $telefono1);
        
        return $telefono2;
    }
    

    
    
    
}