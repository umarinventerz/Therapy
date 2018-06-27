<?php

if(!isset($_SESSION)) {
    session_start();
}
require_once("../../../conex.php");



$user = '';
$password = '';
$user = $_POST['inputUser'];
$password = $_POST['inputPassword'];
$user_browser = $_SERVER['HTTP_USER_AGENT'];

$conexion = conectar();

//$mysqli =new mysqli($host, $users, $pass, $db);
$mysqli=$conexion;

if (isset($_POST['inputUser'], $_POST['inputPassword'])) {
   // $user = $_POST['inputUser'];
   // $password = $_POST['inputPassword'];

    $user = sanitizeString($conexion, $_POST["inputUser"]);
    $password = sanitizeString($conexion, $_POST["inputPassword"]);

    if (login($user, $password, $mysqli) == true) {
        echo '<script>window.location="../../vista/home/home.php";</script>';

        // Inicio de sesión exitosa
     //   header('Location: ../protected_page.php');
    }

    else

        {









            // Inicio de sesión exitosa
            //   header('Location: ../index.php?error=1');
            // $_SESSION['error_login'] = 'User or Password are incorrect!';
            // echo '<script>window.location="index2.php";</script>';
            echo "<script type=\"text/javascript\">alert(\"INVALID USER OR PASSWORD\");</script>";
            echo '<script>window.location="../../../index.php";</script>';

    }
}
else {
    // Las variables POST correctas no se enviaron a esta página.
    echo 'Solicitud no válida';
}




 $query2 = "select user_name, first_name, last_name, user_id, status_id, user_type from user_system where user_name ='".$user."' AND password='".$password."' AND status_id = 1"; //,Company,Insurance_name

//$result2 = mysqli_query($conexion, $query2);
//$user_id  = '';

//while($row = mysqli_fetch_array($result2,MYSQLI_ASSOC)){
//	$last_name = $row['last_name'];
//	$first_name = $row['first_name'];
//	$user_id = $row['user_id'];
//	$status_id = $row['status_id'];
//	$user_type = $row['user_type'];
	
//}


//if($user_id == ''){
	//$_SESSION['error_login'] = 'User or Password are incorrect!';
	//echo '<script>window.location="index2.php";</script>';
//	echo "<script type=\"text/javascript\">alert(\"INVALID USER OR PASSWORD\");</script>";
//	echo '<script>window.location="../../../index.php";</script>';
//}else{
//	$_SESSION['last_name'] = $last_name;
//	$_SESSION['first_name'] = $first_name;
//	$_SESSION['user_id'] = $user_id;
//	$_SESSION['status_id'] = $status_id;
//	$_SESSION['user_type'] = $user_type;

	

//	echo '<script>window.location="../../vista/home/home.php";</script>';
	
//}




































function login($user_name, $password, $mysqli) {
    // Usar declaraciones preparadas significa que la inyección de SQL no será posible.
    $query2 = "select user_name, first_name, last_name, user_id, status_id, user_type,password from user_system where user_name ='".$user_name."' AND status_id = 1"; //,Company,Insurance_name

    if ($query2) {

       // $stmt->bind_param('s', $user_name);  // Une “$email” al parámetro.
       // $stmt->execute();    // Ejecuta la consulta preparada.


       // $result2 = mysqli_query($mysqli, $stmt->bind_param('s', $user_name));



        // Obtiene las variables del resultado.
//        $stmt->bind_result($user_id,$first_name,$last_name,$status_id,$user_type,$db_password);
  //      $stmt->fetch();

        $result2 = mysqli_query($mysqli, $query2);


        while($row = mysqli_fetch_array($result2,MYSQLI_ASSOC)){
            $last_name = $row['last_name'];
            $first_name = $row['first_name'];
            $user_id = $row['user_id'];
            $status_id = $row['status_id'];
            $user_type = $row['user_type'];
            $db_password = $row['password'];

        }



        // Hace el hash de la contraseña con una sal única.
         if (count($result2) == 1) {


            if (checkbrute($user_id, $mysqli) == true) {


                //
                // Envía un correo electrónico al usuario que le informa que su cuenta está bloqueada.

                echo "<script type=\"text/javascript\">alert(\"Su cuenta esta bloqueda\");</script>";
                echo '<script>window.location="../../../index.php";</script>';

                $query_mail= "select email from employee where id ='".$user_id."' ";

                $result_mail = mysqli_query($mysqli, $query_mail);


                while($row = mysqli_fetch_array($result_mail,MYSQLI_ASSOC)){
                    $email = $row['email'];
                                    }

                if (sendmail($email, 'bloqueado') == true) {
                    echo "<script type=\"text/javascript\">alert(\"Se envio el correo\");</script>";

                }else{
                    echo "<script type=\"text/javascript\">alert(\"No se pudo enviar el correo \");</script>";
                }




                return false;
            } else


            	{
                // Revisa que la contraseña en la base de datos coincida
                // con la contraseña que el usuario envió.


                if ($db_password == $password) {


                    $ip = $_SERVER['REMOTE_ADDR'];

                    $date = new DateTime();
                    $date1 = $date->format('Y-m-d H:i:s');

                    $user_os        = getOS();
                    $user_browser   = getBrowser();
                    $locations= geolocalizacion();

                    $good="INSERT INTO conections_informations(user_id, date,ip_conections,brouser,os,country,city,region,locations,id_conections_fail)
                                   VALUES ('$user_id','$date1','$ip','$user_browser','$user_os','$locations->country','$locations->city','$locations->region','$locations->loc','SUCCES')";

                    ejecutar($good,$mysqli);

                    // ¡La contraseña es correcta!
                    // Obtén el agente de usuario del usuario.

                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    //  Protección XSS ya que podríamos imprimir este valor.
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);


                    $_SESSION['user_id'] = $user_id;

                    // Protección XSS ya que podríamos imprimir este valor.

                    $first_name = preg_replace("/[^a-zA-Z0-9_\-]+/",
                        "", $first_name);
                    $_SESSION['first_name'] = $first_name;

                    $last_name = preg_replace("/[^a-zA-Z0-9_\-]+/",
                        "", $last_name);
                    $_SESSION['last_name'] = $last_name;

                    $status_id = preg_replace("/[^a-zA-Z0-9_\-]+/",
                        "", $status_id);
                    $_SESSION['status_id'] = $status_id;

                    $user_type = preg_replace("/[^a-zA-Z0-9_\-]+/",
                        "", $user_type);
                    $_SESSION['user_type'] = $user_type;


                    // Inicio de sesión exitoso

                    return true;
                } else


                	{
                    // La contraseña no es correcta.
                    // Se graba este intento en la base de datos.
                    $now = time();
                    $fail="INSERT INTO secure_login(user_id, time)
                                   VALUES ('$user_id', '$now')";

                        ejecutar($fail,$mysqli);
                    return false;
                }
            }

        } else {

           return false;
        }
    }
}














/////////////////////////////////////////////
///
///        holaaaa /////////////////////////
///
///
///




function checkbrute($user_id, $mysqli) {
    // Obtiene el timestamp del tiempo actual.
    $now = time();

    // Todos los intentos de inicio de sesión se cuentan desde las 2 horas anteriores.
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time 
                             FROM secure_login 
                             WHERE user_id = ? 
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);

        // Ejecuta la consulta preparada.
        $stmt->execute();
        $stmt->store_result();

        // Si ha habido más de 5 intentos de inicio de sesión fallidos.
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}


function sendmail($to,$subject) {

    if($subject=='bloqueado')
    {$content_mail='Si cuenta ha sido bloqueada por superar los intento , disculpe las molestias , politicas de seguridad, contacte a su administrador, SALUDOS';

    }
    /**
     * Envio de correo mediante un servidor SMTP
     */
    include("../../../mail/phpmailer.php");
    $smtp=new PHPMailer();
    $smtp->IsSMTP();
    $smtp->CharSet="UTF-8";


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
    $contenidoTexto.=$content_mail;;

# Definimos el subject
    $smtp->Subject = $subject;


    $smtp->AltBody=$contenidoTexto; //Text Body
    $smtp->MsgHTML($contenidoHTML); //Text body HTML


foreach($mailTo as $mail=>$name) {
    $smtp->ClearAllRecipients();
    $smtp->AddAddress($mail, $name);


    if(!$smtp->Send())
    {

        echo "<br>Error (".$mail."): ".$smtp->ErrorInfo;
        echo "<script>alert(\"The mail was not sent\");window.close();</script>";


    }

}




}


$user_agent = $_SERVER['HTTP_USER_AGENT'];





function getOS() {


    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
        '/windows nt 10/i'      =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $browser        = "Unknown Browser";

    $browser_array = array(
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}

function geolocalizacion(){

    $ip = $_SERVER['REMOTE_ADDR'];
    $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

    return $details;
}









?>



