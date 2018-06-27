<?php

class SendPhoneSms{
    
    public function send($Pat_id,$Discipline,$type){
        
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

        //$prueba='7864207704@tmomail.net';        
        $smtp->WordWrap   = 50; 

        // evaluo diciplinas        
        switch ($Discipline){
            
            case 'ST':
                $dicipline=1;
            break;
        
            case 'OT':
                $dicipline=2;
            break;
        
            case 'PT':
                $dicipline=3;
            break;
        
            case 'MH':
                $dicipline=4;
            break;
            
            case 'DP':
                $dicipline=5;
            break;
        
            case 'IT':
                $dicipline=6;
            break;
        }
        
        //EVALUO LOS TIPOS DE REPORTES
        
        switch ($type){
            
            case 'POC EXPIRED NO PRESCRIPTION':
                $type_report=1;
            break;
        
            case 'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL':
                $type_report=2;
            break;
        
            case 'NOT SIGNED BY DOCTOR YET':
                $type_report=3;
            break;
        
            case 'ASK FOR AUTHORIZATION FOR TX':
                $type_report=4;
            break;
        
            case 'PROGRESS NOTES ADULTS':
                $type_report=5;
            break;
        
            case 'PROGRESS NOTES PEDRIATICS':
                $type_report=6;
            break;
        
            case 'WAITING FOR PRESCRIPTION':
                $type_report=7;
            break;
        
            case 'WAITING FOR AUTH EVAL':
                $type_report=8;
            break;
        
            case 'NEED EVALUTATION':
                $type_report=9;
            break;
        
            case 'WAITING FOR DOCTOR SIGNATURE':
                $type_report=10;
            break;
        
            case 'WAITING FOR AUTHORIZATION TX':
                $type_report=11;
            break;
        
            case 'READY FOR TREATMENT':
                $type_report=12;
            break;
            
            case 'PATIENTS ON HOLD':
                $type_report=13;
            break;
        
            case 'ACTIVE PATIENTS NOT BEEN SEEN':
                $type_report=14;
            break;
        }
        
            //consulto phone,id_patients_carriers del paciente
            $conexion = conectar();	
            $sql  = "SELECT Phone,id_carriers FROM patients where Pat_id = ".$Pat_id;
            $resultado = ejecutar($sql,$conexion); 
            $j = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$j] = $datos;
                $j++;
            }
            //guardo el telefono del paciente
            $phone_send=$reporte[0]['Phone'];
            //depuro el telefono de ()-
            $phone_send_depurado=$this->depurar_telefono($phone_send);            
            $id_patients_carriers=$reporte[0]['id_carriers'];
       
            //consulto carriers        
            $sql1  = "SELECT sms_gateway_domain FROM tbl_patients_carriers where id_patients_carriers = ".$id_patients_carriers;
            $resultado1 = ejecutar($sql1,$conexion); 
        
            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado1)) {            
                $reportes[$i] = $datos;
                $i++;
            }
            //guardo el dominio de la linea telefonica
            $dominio=$reportes[0]['sms_gateway_domain'];
            
            //compilo el telefono con el dominio de la linea
            $email_send_sms=$phone_send_depurado.'@'.$dominio;
            
            //CONSULTO SUBJET Y MESSAGE            
            $sql2  = "SELECT subject,message FROM tbl_patients_carriers_message where id_type_report = ".$type_report." AND id_discipline=".$dicipline;
            $resultado2 = ejecutar($sql2,$conexion);
            
            $k = 0;      
            while($datos = mysqli_fetch_assoc($resultado2)) {            
                $reportes[$k] = $datos;
                $k++;
            }
            
            ///////////////////////////////////////////////////////codigo para enviar push notification//////////////////////
            
            $notification=new PushNotification();
            $push=$notification->consultar_usuarios();
            
            $array_push=json_decode($push,true);
           
            for($i=0;$i<count($array_push['players']);$i++){
                
                if($array_push['players'][$i]['tags']['phone']==$phone_send_depurado){
                    
                    $notification->send_push_notification($array_push['players'][$i]['id'],$reportes[0]['message']);
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
                   'text' => $reportes[0]['message']
               )
           );
           //ejecuto la funcion de envio
            $rc->post('https://platform.devtest.ringcentral.com/restapi/v1.0/account/~/extension/~/sms', $params);
            */
            if(!isset($send_notification)){
                $smtp->Subject = $reportes[0]['subject'];
                $smtp->AltBody=$reportes[0]['message']; //Text Body
                $smtp->MsgHTML('s');
                $smtp->ClearAllRecipients();
                $smtp->AddAddress($email_send_sms,'');
                $smtp->Send();
            }
            
    }
    
    public function depurar_telefono($phone){
        
        $tlefono=  str_replace('(', '', $phone);
        $telefono1=str_replace(')', '', $tlefono);
        $telefono2=str_replace('-', '', $telefono1);
        
        return $telefono2;
    }
    
    public function send_sms_interfaz($pat_id,$subject,$sms,$type_person,$id_templates){
            
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
            //$prueba='7864207704@tmomail.net';        
            $smtp->WordWrap   = 50; 

            //consulto phone,id_patients_carriers del paciente
            $conexion = conectar();
            switch ($type_person){
                case 1:
                    $sql  = "SELECT Phone,id_carriers,Last_name as apellido,First_name as nombre  FROM patients where Pat_id = ".$pat_id;
                break;
                case 2:
                    $sql  = "SELECT phone_number as Phone,id_carriers,last_name as apellido,first_name as nombre FROM employee where id = ".$pat_id;
                break;
                case 3:
                    $sql  = "SELECT phone as Phone,id_carriers,insurance as nombre FROM seguros where id = ".$pat_id;
                break;
                case 4:
                    $sql  = "SELECT Mobile_phone as Phone,id_carriers,Name as nombre FROM physician where Phy_id = ".$pat_id;
                break;
                case 5:
                    $sql  = "SELECT Phone,id_carriers,Last_name as apellido,First_name as nombre FROM tbl_referral where id_referral = ".$pat_id;
                break;
                case 6:
                    $sql  = "SELECT telefono as Phone,id_carriers,persona_contacto as nombre FROM tbl_contacto_persona where id_persona_contacto = ".$pat_id;
                break;
            }
            $resultado = ejecutar($sql,$conexion);             
            $j = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$j] = $datos;
                $j++;
            }
            $first_name_patients=$reporte[0]['nombre'];
            $last_name_patients=$reporte[0]['apellido'];
            
            $sql3  = "SELECT * FROM templates WHERE  id='".$id_templates."'";
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
            $send_description=$body_prueba2;
                  
            //guardo el telefono del paciente
            $phone_send=$reporte[0]['Phone'];
            //depuro el telefono de ()-
            $phone_send_depurado=$this->depurar_telefono($phone_send); 
            
            $id_patients_carriers=$reporte[0]['id_carriers'];            
            //consulto carriers        
            $sql1  = "SELECT sms_gateway_domain FROM tbl_patients_carriers where id_patients_carriers = ".$id_patients_carriers;
            $resultado1 = ejecutar($sql1,$conexion); 
        
            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado1)) {            
                $reportes[$i] = $datos;
                $i++;
            }
            //guardo el dominio de la linea telefonica
            $dominio=$reportes[0]['sms_gateway_domain'];
            
            //compilo el telefono con el dominio de la linea
            $email_send_sms=$phone_send_depurado.'@'.$dominio;
            
            ///////////////////////////////////////////////////////codigo para enviar push notification//////////////////////
            
            $notification=new PushNotification();
            $push=$notification->consultar_usuarios();
            
            $array_push=json_decode($push,true);
           
            for($i=0;$i<count($array_push['players']);$i++){
                
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
                   'text' => $body
               )
           );
           //ejecuto la funcion de envio
           $resSms = $rc->post('https://platform.devtest.ringcentral.com/restapi/v1.0/account/~/extension/~/sms', $params);
           */
            if(!isset($send_notification)){
                $smtp->Subject = $subject;
                $smtp->AltBody=$send_description;           
                $smtp->MsgHTML('s');
                $smtp->ClearAllRecipients();
                $smtp->AddAddress($email_send_sms,'');
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
    
    public function send_sms_interfaz_without($pat_id,$subject,$body,$type_person,$variables){
            
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
            //$prueba='7864207704@tmomail.net';        
            $smtp->WordWrap   = 50; 

            //consulto phone,id_patients_carriers del paciente
            $conexion = conectar();
            switch ($type_person){
                case 1:
                    $sql  = "SELECT Phone,id_carriers,Last_name as apellido,First_name as nombre  FROM patients where Pat_id = ".$pat_id;
                break;
                case 2:
                    $sql  = "SELECT phone_number as Phone,id_carriers,last_name as apellido,first_name as nombre FROM employee where id = ".$pat_id;
                break;
                case 3:
                    $sql  = "SELECT phone as Phone,id_carriers,insurance as nombre FROM seguros where id = ".$pat_id;
                break;
                case 4:
                    $sql  = "SELECT Mobile_phone as Phone,id_carriers,Name as nombre FROM physician where Phy_id = ".$pat_id;
                break;
                case 5:
                    $sql  = "SELECT Phone,id_carriers,Last_name as apellido,First_name as nombre FROM tbl_referral where id_referral = ".$pat_id;
                break;
                case 6:
                    $sql  = "SELECT telefono as Phone,id_carriers,persona_contacto as nombre FROM tbl_contacto_persona where id_persona_contacto = ".$pat_id;
                break;
            }
            $resultado = ejecutar($sql,$conexion);             
            $j = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$j] = $datos;
                $j++;
            }
            $first_name_patients=$reporte[0]['nombre'];
            $last_name_patients=$reporte[0]['apellido'];
            
            $sql3  = "SELECT * FROM templates WHERE  id='".$id_templates."'";
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
            $send_description=$body_prueba2;            
            
            //guardo el telefono del paciente
            $phone_send=$reporte[0]['Phone'];
            //depuro el telefono de ()-
            $phone_send_depurado=$this->depurar_telefono($phone_send); 
            
            $id_patients_carriers=$reporte[0]['id_carriers'];            
            //consulto carriers        
            $sql1  = "SELECT sms_gateway_domain FROM tbl_patients_carriers where id_patients_carriers = ".$id_patients_carriers;
            $resultado1 = ejecutar($sql1,$conexion); 
        
            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado1)) {            
                $reportes[$i] = $datos;
                $i++;
            }
            //guardo el dominio de la linea telefonica
            $dominio=$reportes[0]['sms_gateway_domain'];
            
            //compilo el telefono con el dominio de la linea
            $email_send_sms=$phone_send_depurado.'@'.$dominio;
            
            ///////////////////////////////////////////////////////codigo para enviar push notification//////////////////////
            
            $notification=new PushNotification();
            $push=$notification->consultar_usuarios();
            
            $array_push=json_decode($push,true);
           
            for($i=0;$i<count($array_push['players']);$i++){
                
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
                   'text' => $body
               )
           );
           //ejecuto la funcion de envio
           $resSms = $rc->post('https://platform.devtest.ringcentral.com/restapi/v1.0/account/~/extension/~/sms', $params);
           */
            
            if(!isset($send_notification)){
                $smtp->Subject = $subject;
                $smtp->AltBody=$send_description;           
                $smtp->MsgHTML('s');
                $smtp->ClearAllRecipients();
                $smtp->AddAddress($email_send_sms,'');
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
    
}