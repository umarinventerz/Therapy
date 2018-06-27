<?php
// 12_6_2018V2
################################   VARIABLES FIJAS  #################################

$fecha=date('Y-m-d');
$fecha2=date('d/m/Y');
$hora1= time();
$hora=date("G:i:s", $hora1);

################################   CONEXION BD  #####################################

$path  = "d:/um/htdocs/therapy-aid-master/database.php";   
include_once($path);

define('HOST',$ip);
define('USER',$user);
define('PASS',$pw);
define('BD',$db);

function conectar(){

$con = mysqli_connect (HOST,USER,PASS) or die ("ERROR EN CONEXION: ".mysqli_error());

$base_datos = mysqli_select_db ($con, BD)or die ("ERROR AL SELECCIONAR BASE DE DATOS: ".mysqli_error($con));

return $con;

}

function ejecutar($sql,$con){

$result = mysqli_query ($con, $sql);

if(!$result)
{
     echo "ERROR QUERY: ".mysqli_error($con);
	 
}

return $result;

mysqli_free_result($result);

mysqli_close($con);

}



    function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
                                                    } 



function restaHoras($horaIni, $horaFin){
 
return (date("H:i:s", strtotime("00:00:00") + strtotime($horaFin) - strtotime($horaIni) ));
 
}

function sanitizeString($cnx, $var) {
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return $cnx->real_escape_string($var);
}

?>
