<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="../../home/home.php";</script>';
    }
}

$conexion = conectar();

if(isset($_POST)){
 //   if(isset($_POST["Pat_id"]) && $_POST["Pat_id"] != null){ $Pat_id = $_POST["Pat_id"]; } else { $Pat_id = 'null'; }
 //   if(isset($_POST["email"]) && $_POST["email"] != null){ $email_to = $_POST["email"]; } else { $email_to = 'null'; }
 //   if(isset($_POST["content"]) && $_POST["content"] != null){ $content = $_POST["Pat_id"]; } else { $Pat_id = 'null'; }

if (isset($_POST["email"]) && isset($_POST["offset"]) && isset($_POST["rec_limit"])) {
	$email = sanitizeString($conexion, $_POST["email"]);	
	$offset = sanitizeString($conexion, $_POST["offset"]);
	$rec_limit = sanitizeString($conexion, $_POST["rec_limit"]);
	$query = "SELECT id, Last_name, First_name, Pat_id, tipe ,send_to, content, created_at, aswer_to FROM comunications_portal_patients WHERE send_to ='$email' ORDER BY created_at DESC LIMIT $offset, $rec_limit;";	   
    $resultado = ejecutar($query, $conexion);
	$i = 0;
	$json_resultado = array();
	while($row = mysqli_fetch_array($resultado, MYSQLI_ASSOC))
	{
		$json_resultado[$i] = $row;
		$i++;
	}
} 
 
 
if(isset($_POST["id"]) && isset($_POST["action"]) && $_POST["id"] != null  && $_POST["action"] != null){
    if($_POST["action"]=='ver_questions'){

        $id=$_POST["id"];
        $sql = 'SELECT content FROM comunications_portal_patients WHERE id = \''.$id.'\';';
        $resultado = ejecutar($sql,$conexion);
        $i=0;
        while ($row=mysqli_fetch_array($resultado)) {

            $content = $row['content'];

            $i++;
        }

        $json_resultado = $content;
       # $json_resultado['contenido'] = 'vamos a ver';
#        echo json_encode($json_resultado);


    }

    if($_POST["action"]=='mostrar_form_correo'){

        $id=$_POST["id"];
        $sql = 'SELECT form FROM comunications_portal_patients WHERE id = \''.$id.'\';';
        $resultado = ejecutar($sql,$conexion);
        $i=0;
        while ($row=mysqli_fetch_array($resultado)) {

            $form = $row['form'];

            $i++;
        }

        $json_resultado = $form;

    }

    if($_POST["action"]=='enviar_correo'){


        $where = ' WHERE id = '.$_POST["id"];
        $aswer=$_POST["content"];
        $id_questions=$_POST["id"];

       // $sql = 'SELECT Pat_id,Last_name,First_name FROM patients WHERE Pat_id = \'' . $Pat_id . '\';';
        $sql = 'SELECT form,Pat_id,send_to FROM comunications_portal_patients WHERE id = \''.$id_questions.'\';';
        $resultado = ejecutar($sql,$conexion);
        $i=0;
$form=array();
        while ($row=mysqli_fetch_array($resultado)) {

            $form[$i] = $row;
            $i++;
        }

        $json_resultado='ok';
        ##seccion donde pongo en uno aswer_to para que el paciente sepa que fue repondida la pregunta
        $valor_uno=1;
        $update =" UPDATE comunications_portal_patients SET aswer_to = '".$valor_uno."'".$where;
        $resultado = ejecutar($update,$conexion);

#############
        ##########parte donde guardo la respuesta de los doctores
$date=new DateTime();
$date1=$date->format('Y-m-d H:i:s');
        $insert = " INSERT into response_to_comunications_portal_patients (Pat_id,tipe,content,form,send_to,aswer_to,created_at,updated_at)
                    values ('".$form[0]['Pat_id']."','aswer','".$aswer."','".$form[0]['send_to']."','".$form[0]['form']."','".$id_questions."','".$date1."','".$date1."');";
        $resultado = ejecutar($insert,$conexion);




    }



}

}
##$json_resultado['correo_paciente'] = 'bien';
#$json_resultado['contenido'] = 'vamos a ver';
#$json_resultado['forma'] = 'error';
echo json_encode($json_resultado);

function sanitizeString($cnx, $var) {
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return $cnx->real_escape_string($var);
}


?>