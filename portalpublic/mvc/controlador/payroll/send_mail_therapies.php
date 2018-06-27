<?php
	session_start();
	require_once("../../../conex.php");
	require_once('../../../mail/phpmailer.php');

    	
	$conexion = conectar();
	$identificadores = substr (sanitizeString($conexion, $_REQUEST['identificadores_check']), 0, strlen($_REQUEST['identificadores_check']) - 1);
	$sql  = "SELECT * FROM tbl_treatments t WHERE id_treatments IN (".$identificadores.");";
	$resultado = ejecutar($sql,$conexion);

        $reporte = array();
        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$i] = $datos;
            $i++;
        }
	$datos_result = '';
	$datos_result .= '<table id="example" class="table table-striped table-bordered" border="1" cellpanding="0" cellspacing="0" width="100%">';
	                       
        
            
            $datos_result .= '
                    <thead>
                        <tr>
                            <th>Identifiquer</th>
                            <th>VISIT ID</th>
                            <th>PATIENT</th>
                            <th>PRI. INS</th>
                            <th>PROVIDER</th>
                            <th>DIAGNOSES</th>
                        </tr>
                    </thead>

            <tbody>';
				
            $i=0;						
            while (isset($reporte[$i])){ 

                    $datos_result .= '<tr>';				
                    $datos_result .= '<td>'.$reporte[$i]['id_treatments'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_3'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_6'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_7'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_9'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['campo_13'].'</td>';                    
                    $datos_result .= '</tr>';

                    $i++;		
            }			
			 				
			$datos_result .= '</tbody>';                                                                                                                        
                        
                        
			$datos_result .='</table>';

    $datos = $datos_result;
    $correo = sanitizeString($conexion, $_POST['correo']);
    $asunto = 'Envío de Terapias';
    
    

    $smtp=new PHPMailer();

    # Indicamos que vamos a utilizar un servidor SMTP
    $smtp->IsSMTP();

    # Definimos el formato del correo con UTF-8
    $smtp->CharSet="UTF-8";

    # autenticación contra nuestro servidor smtp
    $smtp->SMTPAuth   = true;						// enable SMTP authentication
    $smtp->Host       = "smtp.gmail.com";			// sets MAIL as the SMTP server
    $smtp->Username   = "lguerra1075@gmail.com";	// MAIL username
    $smtp->Password   = "l18486480";
    $smtp->SMTPSecure = 'ssl';			// MAIL password
    $smtp->Port = 465;
    # datos de quien realiza el envio
    $smtp->From       = "lguerra1075@gmail.com"; // from mail
    $smtp->FromName   = "Luis Guerra"; // from mail name

    # Indicamos las direcciones donde enviar el mensaje con el formato
    #   "correo"=>"nombre usuario"
    # Se pueden poner tantos correos como se deseen
    $mailTo=array(
        $correo=>$correo
    );

    # establecemos un limite de caracteres de anchura
    $smtp->WordWrap   = 50; // set word wrap
     
    # Definimos el contenido en formato Texto del correo
    $contenidoTexto="Contenido en formato Texto";
    $contenidoTexto.="\n\nhttp://www.lawebdelprogramador.com";

    # Definimos el subject
    $smtp->Subject = $asunto;

    //die();
    # Indicamos el contenido
    $smtp->AltBody=$contenidoTexto; //Text Body
    $smtp->MsgHTML($datos); //Text body HTML

    foreach($mailTo as $mail=>$name)
    {
        $smtp->ClearAllRecipients();
        $smtp->AddAddress($mail,$name);

        if(!$smtp->Send())
        {
           $json_resultado['resultado'] = 'no_enviado';
        }else{
           $json_resultado['resultado'] = 'enviado'; 

        }
    }
    
      echo json_encode($json_resultado);
    
    
    
    ?>
