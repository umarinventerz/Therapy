<?php
/**
 * Envio de correo mediante un servidor SMTP
 */
session_start();
include("../../../mail/phpmailer.php");
include("send_phone_sms.php");
include("send_phone_sms_RingCentral.php");
require_once '../../modelo/ringcentral.php';
require_once '../../modelo/PushNotification.php';
require_once '../../../conex.php';

//consulto si el campo check de envio de sms esta activo
if($_POST['check_sms']=='on'){
    $sms='si';
}else{
    $sms='no';
}

if($_POST['check_sms_ring_central']=='on'){
    $sms_ring_central='si';
}else{
    $sms_ring_central='no';
}



$files_upload = preg_grep('/^([^.])/', scandir('../../../server/php/files'));
$files_upload = array_values($files_upload);
$files = 
$i = 0;
$p = 0;
$length = count($files_upload);

while($i <= ($length-1)){
	if($files_upload[$i] != 'thumbnail' ){
		chmod("../../../server/php/files/".$files_upload[$i], 0777);	
		//echo substr(sprintf('%o', fileperms("server/php/files/".$files_upload[$i])), -4);
		rename ("../../../server/php/files/".$files_upload[$i],"../../../file_mail/".$files_upload[$i]);
		$file_attachment[$p] = $files_upload[$i];
		$p++;
		chmod("../../../file_mail/".$files_upload[$i], 0777);	
		//unlink("server/php/files/".$files_upload[$i]);	
	}	
$i++;
}

//	$temporal = $_FILES['file-1']['tmp_name'];
//	$nombre_archivo = $_FILES['file-1']['name'];
//	$id=fopen($temporal,'r');   

//Chequeo que el archivo alli ha sido cargado correctamente al servidor para guardarlo

//	if (is_uploaded_file($temporal)) {
//	move_uploaded_file($temporal,"file_mail/$nombre_archivo");
//	chmod("file_mail/$nombre_archivo", 0777);
//	}






$Pat_id = $_POST['Patient_id'];
$Company = $_POST['Company'];
$datos = explode(" ",$_POST['Discipline']);
$Discipline = $datos[0];
$patient_name = $_POST['patient_name'];
$type = $_POST['type'];
$to = $_POST['to'];
$subject = $_POST['subject'];
$content_mail = str_replace("\r","<br>",$_POST['mail']);

$smtp=new PHPMailer();

# Indicamos que vamos a utilizar un servidor SMTP
$smtp->IsSMTP();

# Definimos el formato del correo con UTF-8
$smtp->CharSet="UTF-8";

# autenticación contra nuestro servidor smtp
$smtp->SMTPAuth   = true;						// enable SMTP authentication
$smtp->Host       = "smtp.gmail.com";			// sets MAIL as the SMTP server
$smtp->Username   = "kqtherapy@gmail.com";	// MAIL username
$smtp->Password   = "Reilynn72914";
$smtp->SMTPSecure = 'ssl';			// MAIL password
$smtp->Port = 465;
# datos de quien realiza el envio
$smtp->From       = "kqtherapy@gmail.com"; // from mail
$smtp->FromName   = "Therapy Aid"; // from mail name

# Indicamos las direcciones donde enviar el mensaje con el formato
#   "correo"=>"nombre usuario"
# Se pueden poner tantos correos como se deseen
$mailTo=array(
    $to=>$to
);

# establecemos un limite de caracteres de anchura
$smtp->WordWrap   = 50; // set word wrap

# NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
# cualquier programa de correo pueda leerlo.

# Definimos el contenido HTML del correo
$contenidoHTML="<head>";
$contenidoHTML.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
$contenidoHTML.="</head><body>";
$contenidoHTML.=$content_mail;
$contenidoHTML.="</body>\n";

# Definimos el contenido en formato Texto del correo
$contenidoTexto="Contenido en formato Texto";
$contenidoTexto.="\n\nhttp://www.lawebdelprogramador.com";

# Definimos el subject
$smtp->Subject = $subject;

$i = 0;
while($file_attachment[$i] != null){	
	$smtp->AddAttachment("../../../file_mail/".$file_attachment[$i], $file_attachment[$i]);
$i++;
}

$conexion = conectar();	
   echo $sql  = "SELECT max(id_form_pif) as identificador FROM tbl_form_pif where Pat_id = ".$Pat_id;
    $resultado = ejecutar($sql,$conexion); 
    
    if(isset($resultado) && $resultado != null) {
    
    $j = 0;      
    while($datos = mysqli_fetch_assoc($resultado)) {            
        $reporte[$j] = $datos;
        $j++;
    }
    
    $identificador = $reporte[0]['identificador'];

    if ($identificador != null) 
     $smtp->AddAttachment('../../../pdf_form_pif/'.$identificador.'.pdf',$identificador.'.pdf');
    
    }


//	$smtp->AddAttachment("file_mail/$nombre_archivo", $nombre_archivo);

# Indicamos el contenido
$smtp->AltBody=$contenidoTexto; //Text Body
$smtp->MsgHTML($contenidoHTML); //Text body HTML

foreach($mailTo as $mail=>$name)
{
    $smtp->ClearAllRecipients();
    $smtp->AddAddress($mail,$name);

    if(!$smtp->Send())
    {
        echo "<br>Error (".$mail."): ".$smtp->ErrorInfo;
	echo "<script>alert(\"The mail was not sent\");window.close();</script>";
    }else{
        if($sms=='si'){
            //elimino el objeto smtp
            unset($smtp);
            //creo el objeto de la clase para envio de mensajes de texto
            $send_phone=new SendPhoneSms();
            //ejecuto la funcion send para el envio del sms
            $send_phone->send($Pat_id,$Discipline,$type);
        }
        if($sms_ring_central=='si'){
            //elimino el objeto smtp
            unset($smtp);
            //creo el objeto de la clase para envio de mensajes de texto
            $send_phone=new SendPhoneSmsRing();
            //ejecuto la funcion send para el envio del sms
            $send_phone->send($Pat_id,$Discipline,$type);
        }
        /* 
          //elimino el objeto smtp
          unset($smtp);
          //creo el objeto de la clase para envio de mensajes de texto
          $send_phone=new SendPhoneSms();
          //ejecuto la funcion send para el envio del sms
          $send_phone->send($Pat_id,$Discipline,$type);*/
        
       /////////////***************inicio de codigo para manejo de auditoria************************///////////////////////// 
        //inserto auditoria        
        $user=$_SESSION['user_id'];
        $fecha=date('Y-m-d H:i:s');        
        $audit_general="INSERT INTO tbl_audit_generales(user_id,Pat_id,type,created_at) VALUES('".$user."','".$Pat_id."','6','".$fecha."')";
        ejecutar($audit_general,$conexion);         
        //consulto el ultimo id de auditoria generales         
        $sql  = "SELECT max(id) as identificador FROM tbl_audit_generales;";
                $resultado_audit_general = ejecutar($sql,$conexion); 
                $j = 0;      
                $id_audit_general = '';
                while($datos = mysqli_fetch_assoc($resultado_audit_general)) {            
                    $id_audit_general = $datos['identificador'];
                    $j++;
                }
        //inserto en tbl_audit_report_amount_reporting_dicipline
        $audit_general_reporting_dicipline="INSERT INTO tbl_audit_report_amount_reporting_dicipline(id_audit_report,type_report,dicipline) VALUES('".$id_audit_general."','".$type."','".$Discipline."')";
        ejecutar($audit_general_reporting_dicipline,$conexion);
    /////////////***************FIN de codigo para manejo de auditoria************************////////////////////////////// 
        
        echo "<br>Envio realizado a ".$name." (".$mail.")";        
	
	//echo $_POST['type'];
	if($_POST['type'] == 'POC EXPIRED NO PRESCRIPTION'){
		//echo 'AAAAAAA'; // luego quitar
		 $update = "UPDATE careplans SET mail_sent = 1 , mail_sent_time = now() WHERE Patient_ID = '".$Pat_id."'  AND Discipline = '".$Discipline."' and status=1";
		 $update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$Pat_id."' AND discipline = '".$Discipline."'  AND type_report = 'POC EXPIRED NO PRESCRIPTION' ";

 // UPDATE DEL NUEVO REPORTE CUANDO YA SE ENVIA EL FAX PARA PONER EN = 1 QUE ESTA EN
     $update_new_report= "UPDATE patients_copy SET prescription_".$Discipline." ='0' , waiting_prescription_".$Discipline."='1' WHERE Pat_id='".$Pat_id."'  ";

	}else{
		if($_POST['type'] == 'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL'){
		$update = "UPDATE prescription SET mail_sent_eval = 1 , sent_eval_time=now() WHERE Patient_ID = '".$Pat_id."'  AND Discipline = '".$Discipline."' and status=1";	
		
    /////// NOTES 
    $update_notes = " UPDATE tbl_notes set status = 0 WHERE type_report = 'POC EXPIRED NO PRESCRIPTION' AND type_report = 'WAITING FOR PRESCRIPTION'
                        AND  type_report = 'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL' AND type_report = 'WAITING FOR AUTH EVAL'
                         AND pat_id = '".$Pat_id."' AND discipline = '".$Discipline."'   ";

 // UPDATE DEL NUEVO REPORTE CUANDO YA NECESITA AUTH FOR EVAL SE ENVIA EL FAX Y SE PONE EN ESPERANDO
     $update_new_report= "UPDATE patients_copy SET eval_auth_".$Discipline." ='0' , waiting_auth_eval_".$Discipline."='1' WHERE Pat_id='".$Pat_id."'  ";	
	
  	}else{
			if($_POST['type'] == 'NOT SIGNED BY DOCTOR YET'){
				$update = "UPDATE prescription SET mail_sent_not_signed = 1 , not_signed_time = now() WHERE Patient_ID = '".$Pat_id."'  AND Discipline = '".$Discipline."' and status=1";	
               
          //     $update2 = "UPDATE careplans  join patients on careplans.Patient_ID=patients.Pat_id
            //                set careplans.mail_sent_tx=1
             //               where patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' and careplans.status=1";	
        $update_notes = " UPDATE tbl_notes set status = 0 where type_report = 'POC EXPIRED NO PRESCRIPTION' AND  type_report = 'WAITING FOR PRESCRIPTION' 
        AND pat_id = '".$Pat_id."' AND discipline = '".$Discipline."'   ";   
            
            // UPDATE DEL NUEVO REPORTE CUANDO YA NECESITA AUTH FOR EVAL SE ENVIA EL FAX Y SE PONE EN ESPERANDO
    echo  $update_new_report= "UPDATE patients_copy SET doctor_signature_".$Discipline."='0' , waiting_signature_".$Discipline."='1' 
                          WHERE Pat_id='".$Pat_id."' and doctor_signature_".$Discipline."='1' ";  



			}else{
				if($_POST['type'] == 'ASK FOR AUTHORIZATION FOR TX'){

          ////////// UPDATE NUEVO REPORTE////////////////////

          $update_new_report= "UPDATE patients_copy SET tx_auth_".$Discipline."='0' , waiting_tx_auth_".$Discipline."='1' 
                          WHERE Pat_id='".$Pat_id."' and tx_auth_".$Discipline."='1' "; 

            //$update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$Pat_id."' AND discipline = '".$Discipline."'  AND type_report = 'READY FOR TREATMENT' ";  


				$consulta_careplans = "select * from careplans where Patient_ID = '".$Pat_id."' AND Discipline = '".$Discipline."' and status=1 ";
                                        $resultado_careplans = ejecutar($consulta_careplans,$conexion);
                                        $id_careplans_verificar = null;
                                            while ($row=mysqli_fetch_array($resultado_careplans)) { 
                                                $id_careplans_verificar = $row['id_careplans'];
                                            } 
    
                                        if($id_careplans_verificar != null){                                                                                                                 
                                            $update = "UPDATE careplans SET  mail_sent_tx = 1 , tx_sent_time = now() WHERE Patient_ID = '".$Pat_id."'  AND Discipline = '".$Discipline."' and status=1";    
                                            $update2 = "UPDATE prescription SET  tx_request_sent = 1 , tx_request_sent_time = now() WHERE Patient_ID = '".$Pat_id."'  AND Discipline = '".$Discipline."' and status=1"; 
                                            $update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$Pat_id."' AND discipline = '".$Discipline."'  AND type_report = 'READY FOR TREATMENT' ";                                          
                                        } else {                                            
                                            $update = "UPDATE prescription SET  tx_request_sent = 1 , tx_request_sent_time = now() WHERE Patient_ID = '".$Pat_id."'  AND Discipline = '".$Discipline."' and status=1";
                                            $update_notes = " UPDATE tbl_notes set status = 0 where pat_id = '".$Pat_id."' AND discipline = '".$Discipline."'  AND type_report = 'READY FOR TREATMENT' ";                                              
                                        }

				}else{
                    if($_POST['type'] == 'PROGRESS NOTES ADULTS'){

                      //Declaro la variable para USAR EN  LIMIT 
                       $limit = "SELECT visits from patients 
                                    JOIN seguros on seguros.insurance= patients.Pri_ins
                                        JOIN tbl_seguros_table st on seguros.ID=st.id_seguros and st.id_seguros_type_person=patients.id_seguros_type_person
                                            JOIN tbl_seguros_progress sp ON   sp.id_seguros_table=st.id_seguros_table
                                                WHERE   patients.id_seguros_type_person='2'  and Pat_id='".$Pat_id."' and st.discipline='".$Discipline."' ";
                       $result_limit = ejecutar($limit,$conexion);                
                        $b = 0;    
                        $reporte_limit = array();  
                while($datos_limit = mysqli_fetch_assoc($result_limit)) {
                          $reporte_limit[$b] = $datos_limit;
                      
                     $update = "UPDATE tbl_treatments SET adults_progress_notes = 1 WHERE 
                     campo_3 in (select * from (select campo_3
                                    from tbl_treatments
                                    join cpt on tbl_treatments.campo_11=cpt.cpt
                                    and tbl_treatments.campo_10=cpt.Discipline
                                    where 
                                    type='TX'
                                    and tbl_treatments.adults_progress_notes=0
                                    and    campo_5='".$Pat_id."'
                                    and campo_10='".$Discipline."'
                                   group by campo_3
                                   limit ".$reporte_limit[$b]['visits']."    ) as t 
                                )
                     ";      

                              $b++;
                    }   //////////////////////////////////// fin del while del LIMIT del adults

               
                    }else{
                    if($_POST['type'] == 'PROGRESS NOTES PEDRIATICS'){

                      //Declaro la variable para USAR EN  LIMIT 
                       $limit = "SELECT visits from patients 
                                    JOIN seguros on seguros.insurance= patients.Pri_ins
                                        JOIN tbl_seguros_table st on seguros.ID=st.id_seguros and st.id_seguros_type_person=patients.id_seguros_type_person
                                            JOIN tbl_seguros_progress sp ON   sp.id_seguros_table=st.id_seguros_table
                                                WHERE   patients.id_seguros_type_person='1'  and Pat_id='".$Pat_id."' and st.discipline='".$Discipline."' ";
                       $result_limit = ejecutar($limit,$conexion);                
                        $b = 0;    
                        $reporte_limit = array();  
                while($datos_limit = mysqli_fetch_assoc($result_limit) )
                            {
                          $reporte_limit[$b] = $datos_limit;

                     $update = "UPDATE tbl_treatments SET pedriatics_progress_notes = 1 WHERE 
                     campo_3 in (SELECT * from (select campo_3
                                    from tbl_treatments
                                    join cpt on tbl_treatments.campo_11=cpt.cpt
                                    and tbl_treatments.campo_10=cpt.Discipline
                                    WHERE
                                     type='TX'                                  
                                     and tbl_treatments.pedriatics_progress_notes=0
                                     and    campo_5='".$Pat_id."'
                                    and campo_10='".$Discipline."'
                                   group by campo_3
                                   limit ".$reporte_limit[$b]['visits']."    ) as t 
                                )
                     ";   

                       $b++;
                            }        //////////////////////////////////// fin del while del LIMIT del pedratic



                          }else{
                    if($_POST['type'] == 'DISCHARGES'){
      $update = "UPDATE tbl_audit_discharge_patient SET status = 0  WHERE patient_id = '".$Pat_id."'  AND discipline = '".$Discipline."' and status=1";

                      $update2 = "UPDATE tbl_patients_copy SET discharge_".$Discipline." = '0'  WHERE Pat_id = '".$Pat_id."'   ";  
                            }         
               }

        }  
			}
		}
	}

 }
	//echo $update;
	//die();
	if($update != '')
	ejecutar($update,$conexion);
        echo "<script>alert(\"Mail Sent\");window.opener.location.reload();window.close();</script>"; //window.close(); Luego colocarlo para que cierre la ventana
  if($update2 != '')
    ejecutar($update2,$conexion);
        echo "<script>alert(\"Mail Sent\");window.opener.location.reload();window.close();</script>"; //window.close(); Luego colocarlo para que cierre la ventana
 if($update_notes != '')
	ejecutar($update_notes,$conexion);
        echo "<script>alert(\"Mail Sent\");window.opener.location.reload();window.close();</script>";

 if($update_new_report != '')
  ejecutar($update_new_report,$conexion);
        echo "<script>alert(\"Mail Sent\");window.opener.location.reload();window.close();</script>";

    }
}
?>
