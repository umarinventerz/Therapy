<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}

$conexion = conectar();
$name=$_POST['name'];
$description=$_POST['description'];
$date=date('Y-m-d');
$id_template=$_POST['id_template'];
$variable=$_POST['variable'];
$type=$_POST['type'];

if($_POST['accion']=='insertar'){
    
        $insert = "INSERT into templates (name,description,created_at,variable,type)
                    values ('".$name."','".$description."','".$date."','".$variable."','".$type."');";
        $resultado = ejecutar($insert,$conexion);
        if($resultado){
            $conexion = conectar();
            
                $sql2  = "SELECT id FROM  templates order by id desc limit 1";
           
            $resultado1 = ejecutar($sql2,$conexion); 
            $j = 0;      
            while($datos = mysqli_fetch_assoc($resultado1)){            
                $template[$j] = $datos;
                $j++;
            }
            $array=array('succes'=>'Templates created successfully','id'=>$template[0]['id']);
        }else{
            $array=array('succes'=>'There was an error saving the template, please try again');
        }
        $response=  json_encode($array);
        echo $response;
}

if($_POST['accion']=='modificar'){    
    $modify = "UPDATE templates set name='".$name."', description='".$description."',variable='".$variable."' WHERE id=".$id_template;
    $modificar = ejecutar($modify,$conexion); 
    
    if($modificar){
        $array=array('succes'=>'Templates updated successfully','id'=>$id_template);
    }else{
        $array=array('succes'=>'There was an error updating the template, please try again');
    }
    $response=  json_encode($array);
    echo $response;
}
