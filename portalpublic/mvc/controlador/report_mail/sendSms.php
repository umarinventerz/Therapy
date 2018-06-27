<?php

include("../../../mail/phpmailer.php");
include("send_phone_sms.php");
require_once '../../modelo/ringcentral.php';
require_once '../../modelo/PushNotification.php';
require_once '../../../conex.php';

$id = $_POST['id'];
$type_person=$_POST['tipo_persona'];
$subject = $_POST['subject'];
$sms = $_POST['sms'];
$id_templates=$_POST['id_template'];

if($id_templates=='Without'){
    $Without='si';
   $variables= $_POST['variable'];
   $text_date_change=$_POST['text_date_change'];
}else{
   $Without='no'; 
}
if(isset($id) && count($id)>0){
    //creo el objeto de la clase para envio de mensajes de texto
    $send_phone=new SendPhoneSms();
    //ejecuto un bucle para enviar N cantidad de id
    $si=0;
    $no=0;
    if($Without=='si'){
        for($i=0;$i<count($id);$i++){       
            //ejecuto la funcion send para el envio del sms
            //$enviar=$send_phone->send_sms_interfaz($id[$i],$subject,$id_templates,$type_person);
            $enviar=$send_phone->send_sms_interfaz_without($id[$i],$subject,$text_date_change,$type_person,$variables);
            if($enviar=='si'){
                $si=$si+1;
            }else{
                $no=$no+1;
            }
        }
    }else{
        for($i=0;$i<count($id);$i++){       
            //ejecuto la funcion send para el envio del sms
            //$enviar=$send_phone->send_sms_interfaz($id[$i],$subject,$id_templates,$type_person);
            $enviar=$send_phone->send_sms_interfaz($id[$i],$subject,$sms,$type_person,$id_templates);
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
        window.location="../../vista/report_mail/sendmsj.php";
    </script>
<?php    
}else{    
?>
    <script>
        alert("Select a Person");
        window.location="../../vista/report_mail/sendmsj.php";
    </script>
<?php
}




       
