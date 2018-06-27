 <?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}

$conexion = conectar();
$id = $_POST['id'];       
$delete = "DELETE FROM patient_insurers_auths WHERE id=".$id;
$resultado=ejecutar($delete,$conexion); 

if($resultado){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}    
echo json_encode($array);
