 <?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}

$conexion = conectar();

$codigo = $_POST['codigo'];
$date = $_POST['date_of_admission'];
$hour_in = $_POST['hour_admission_in'].':'.$_POST['minutes_admission_in'].':'.$_POST['seconds_admission_in'];
$hour_out = $_POST['hour_admission_out'].':'.$_POST['minutes_admission_out'].':'.$_POST['seconds_admission_out'];

$sql  = " SELECT * FROM tbl_assistance WHERE id_usuario = $codigo AND date_of_admission='".$date."' "
        ." ORDER BY date_datetime;";
$resultado = ejecutar($sql,$conexion);
$i = 0;      
$assistances = array();

while($datos = mysqli_fetch_assoc($resultado)) { 
    $assistances[$i]['operacion'] = $datos['operacion'];
    $assistances[$i]['hour_of_admission'] = $datos['hour_of_admission'];
    $i++;
}

$t = 0;
$bloques = array();
$pos = 0;
$allowRegister = 'si';
while(isset($assistances[$t])){
    if($assistances[$t]['operacion'] == 'in' && $assistances[$t+1]['operacion'] == 'out'){
       
        if($hour_in >= $assistances[$t]['hour_of_admission'] && $hour_in <= $assistances[$t+1]['hour_of_admission']){
            $allowRegister = 'no';
            break;
        }else{
            if($hour_out >= $assistances[$t]['hour_of_admission'] && $hour_out <= $assistances[$t+1]['hour_of_admission']){
               $allowRegister = 'no'; 
               break;
            }
        }
        $t += 2;
    }else{

        $t ++;
    }
    $pos++;
}

if($allowRegister=='si'){
    $insert_in  = "INSERT INTO tbl_assistance (
id_usuario,
date_of_admission,
hour_of_admission,operacion,date_datetime) VALUES ('".$codigo."','".$date."','".$hour_in."','in','".$date." ".$hour_in."')";

$resultado = ejecutar($insert_in,$conexion);

if($hour_out != '00:00:00'){
    $insert_out  = "INSERT INTO tbl_assistance (
    id_usuario,
    date_of_admission,
    hour_of_admission,operacion,date_datetime) VALUES ('".$codigo."','".$date."','".$hour_out."','out','".$date." ".$hour_out."')";

    $resultado = ejecutar($insert_out,$conexion);
}
$resultado_mensaje = '<h3><font color="blue">Admission Register</font></h3>';
$type_message = 'success';
}else{
    $resultado_mensaje = '<h3><font color="blue">Error. Esta intentando registrar un bloque incorrecto</font></h3>';
    $type_message = 'error';
}



$json_resultado['resultado'] = $resultado_mensaje; 
$json_resultado['type_message'] = $type_message;
     
echo json_encode($json_resultado); 


?>