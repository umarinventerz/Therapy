<?php
include("../../../mail/phpmailer.php");
require_once '../../modelo/ScheduledEmails_RingCentral.php';
require_once '../../modelo/ringcentral.php';
require_once '../../modelo/PushNotification.php';
require_once '../../../conex.php';
//ejecuto la funcion send_email
$id = $_POST['id'];
$subject=$_POST['subject'];
$id_template = $_POST['id_template'];
$fecha=$_POST['date'];
$particion=  explode('/', $fecha);       
$mes=$particion[0];
$dia=$particion[1]; 
$anio=$particion[2];                                
$month=$mes+0;
$day=$dia+0;
$array=array($month,$day,$anio);
$fecha_formateada=implode("/",$array);

if($id_template=='Without'){
   $Without='si';
   $variables= $_POST['variable'];
   $text_date_change=$_POST['text_date_change'];
}else{
   $Without='no'; 
}

if(isset($id) && count($id)>0){
    
    $rc = new RingCentral('3ogDyAyMRxeTf-vMDfdY7w','N_eujHxQQ9eQFzjIhm28XQEp2lrRISTkim5XlI4PMh2w',RingCentral::RC_SERVER_PRODUCTION);
    $autorizacion=$rc->authorize('13059856122','','Start1974$');   
    unset($rc);
    $data_autorizacion=$autorizacion['access_token'];
    $programar_email= new ScheduledEmails();
    
    $si=0;
    $no=0;
    if($Without=='si'){
        for($i=0;$i<count($id);$i++){             
            //ejecuto la funcion send para el envio del sms
            //$enviar=$send_phone->send_sms_interfaz($id[$i],$subject,$id_templates,$type_person);
            $enviar=$programar_email->send_without($id[$i],$subject,$id_template,$text_date_change,$data_autorizacion,$fecha_formateada);

            if($enviar=='si'){
                $si=$si+1;
            }else{
                $no=$no+1;
            }
        }
    }else{
        for($i=0;$i<count($id);$i++){             
            //ejecuto la funcion send para el envio del sms
            $enviar=$programar_email->send_email($id[$i],$subject,$id_template,$data_autorizacion,$fecha_formateada);
            
            if($enviar=='si'){
                $si=$si+1;
            }else{
                $no=$no+1;
            }
        }
    }
    unset($rc);
?>
    <script>
       
        alert("Message sent: <?=$si?>,  Unissued message: <?=$no?>");
        window.location="../../vista/scheduleEmail/index_RingCentral.php";
    </script>
<?php  
}else{    
?>
    <script>
        alert("Select a Person");
        window.location="../../vista/scheduleEmail/index_RingCentral.php";
    </script>
<?php
}

