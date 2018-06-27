<?php

session_start();
require_once '../../../conex.php';


$conexion = conectar();
                




if(isset($_POST)) {

    if (isset($_POST["Pat_id"]) && $_POST["Pat_id"] != null) {
        $Pat_id = $_POST["Pat_id"];
        $sql = 'SELECT Pat_id,Last_name,First_name FROM patients WHERE Pat_id = \'' . $Pat_id . '\';';

        $resultado = ejecutar($sql, $conexion);

        $i = 0;
        $Pat_id_val=array();
        while ($row = mysqli_fetch_array($resultado)) {

            $Pat_id_val[$i] = $row;

            $i++;
        }




        if (count($Pat_id_val)>0) {

          $mensaje_almacenamiento = $Pat_id_val[0]['First_name'].' '.$Pat_id_val[0]['Last_name'].'('.$Pat_id_val[0]['Pat_id'].')';
          //  $mensaje_almacenamiento=$Pat_id_val[0]['Last_name'];
            $json_resultado['resultado'] = '<h3><font color="blue">'.$mensaje_almacenamiento.'</font></h3>';

            $json_resultado['mensaje'] = $mensaje_almacenamiento;
            $json_resultado['repetido'] = 'Hay';
        }
        if(count($Pat_id_val)==0){
            $json_resultado['repetido'] = 'No hay valores repetidos';
        }




    } else {
        $Pat_id = 'null';

    }

    echo json_encode($json_resultado);
  //  echo json_encode($Pat_id);


}
?>