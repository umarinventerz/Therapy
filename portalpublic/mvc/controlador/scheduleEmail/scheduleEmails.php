<?php
include("../../../mail/phpmailer.php");
require_once '../../modelo/ScheduledEmails.php';
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
    //creo el objeto de la clase para envio de mensajes de texto
    $programar_email= new ScheduledEmails();
    //ejecuto un bucle para enviar N cantidad de id
    $si=0;
    $no=0;
    if($Without=='si'){
        for($i=0;$i<count($id);$i++){       
            //ejecuto la funcion send para el envio del sms
            //$enviar=$send_phone->send_sms_interfaz($id[$i],$subject,$id_templates,$type_person);
            $enviar=$programar_email->send_without($id[$i],$subject,$id_template,$text_date_change,$fecha_formateada);
            if($enviar=='si'){
                $si=$si+1;
            }else{
                $no=$no+1;
            }
        }
    }else{
        for($i=0;$i<count($id);$i++){       
            //ejecuto la funcion send para el envio del sms
            $enviar=$programar_email->send_email($id[$i],$subject,$id_template,$fecha_formateada);
            if($enviar=='si'){
                $si=$si+1;
            }else{
                $no=$no+1;
            }
        }
    }
?>
    <script>
        alert("Message sent: <?=$si?>,  Unissued message: <?=$no?>");
        window.location="../../vista/scheduleEmail/index.php";
    </script>
<?php    
}else{    
?>
    <script>
        alert("Select a Person");
        window.location="../../vista/scheduleEmail/index.php";
    </script>
<?php
}

