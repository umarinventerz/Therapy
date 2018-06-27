<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../home/home.php";</script>';
	}
}

if(isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end'])&& isset($_POST['descriptions'])){
    
    $descripcion = $_POST['title'];
    $inicio = $_POST['start'];
    $fin = $_POST['end'];
    $descrptions = $_POST['descriptions'];
    
        $conexion = conectar();
        $insert = "INSERT into tbl_event (date_start,date_end,descripcion,descrptions) values ('".$inicio."','".$fin."','".$descripcion."','".$descrptions."');";
        ejecutar($insert,$conexion);            

            $json_resultado['resultado'] = 'creado';

            echo json_encode($json_resultado);
}


if(isset($_POST['action']) ){

    if ($_POST['action']=='update')
    {

        $title = $_POST['asunto'];
        $id = $_POST['id_event'];
        $descrptions = $_POST['descriptions'];




        $conexion = conectar();

        $update = " UPDATE tbl_event SET "
            . "descripcion = '" . $title . "'"
            . ",descrptions = '" . $descrptions . "'"
            . " WHERE id_event = " . $id . ";";

        ejecutar($update,$conexion);

        $json_resultado['resultado'] = 'creado';

        echo json_encode($json_resultado);
    }


    if ($_POST['action']=='delete')
    {
        $title = $_POST['id_event'];
        $id = $_POST['id_event'];


        $conexion = conectar();
        $delete="DELETE FROM tbl_event WHERE id_event=".$id;
        ejecutar($delete,$conexion);

        $json_resultado['resultado'] =$title;




        echo json_encode($json_resultado);

    }


}

if(isset($_GET['calendar']) ){

    $consulta = 'SELECT * FROM tbl_event';


    $conexion = conectar();
    $ejecucion_consulta = mysqli_query($conexion, $consulta);

    $reporte = array();

    $e = 0;
    while($datos = mysqli_fetch_assoc($ejecucion_consulta)) {
        $reporte_eventos[$e] = $datos;
        $e++;
    }


    $i=0;
    $cadena_eventos = null;
    while (isset($reporte_eventos[$i])){

        $cadena_eventos .= "{
                                        
                                        id: '".$reporte_eventos[$i]['id_event']."',
                                        title: '".$reporte_eventos[$i]['descripcion']."',
                                        
                                        start: '".$reporte_eventos[$i]['date_start']."'";

        if($reporte_eventos[$i]['date_end'] != null){

            $cadena_eventos .= ",
                        end: '".$reporte_eventos[$i]['date_end']."'
                        ";
        }

        $cadena_eventos .= "}";

        if(isset($reporte_eventos[$i+1])){

            $cadena_eventos .= ",";

        }


        $i++;
    }

    echo json_encode($cadena_eventos);
}
	