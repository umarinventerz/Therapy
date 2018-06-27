<?php
session_start();
require_once("../../../../conex.php");
require_once("../date.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
    $conexion = conectar();

$time=$_GET['time'];
$data  = "SELECT * FROM calendar_time";
$row = ejecutar($data,$conexion);
while($datos = mysqli_fetch_assoc($row)) {            
        $time_result = $datos['time'];
        
}
if(count($time_result)>0){    
    //update    
    $update = "UPDATE calendar_time SET time='".$time."'";
    $resultado = ejecutar($update,$conexion); 
    
    if($update){
        $array=array('success'=>true);
    }else{
        $array=array('success'=>false);
    }    
    echo json_encode($array);
    
}else{
    //insert
    $insert = "INSERT into calendar_time (time)
            values ('".$time."');";
            $resultado = ejecutar($insert,$conexion);
            
    if($insert){
        $array=array('success'=>true);
    }else{
        $array=array('success'=>false);
    }    
    echo json_encode($array);
}

