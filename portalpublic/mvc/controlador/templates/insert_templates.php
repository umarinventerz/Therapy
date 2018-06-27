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
   
    $insert = " INSERT INTO tbl_templates(created,id_user,discipline_id,type_document_id, name, description) "
            . "VALUES (now(),".$_SESSION['user_id'].",'".$_POST["discipline_id"]."',"
            . "'".$_POST["type_document"]."','".$_POST["template"]."','".$_POST["description"]."');";
    $resultado = ejecutar($insert,$conexion);     
        
    if(!$resultado) {           
        $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';
    } else {
        $sql = 'SELECT max(id) as id_template FROM tbl_templates;';
        $resultado = ejecutar($sql,$conexion);
        $row=mysqli_fetch_array($resultado);
        $id_template = $row['id_template'];  
         
        $_POST['cadenaComponents'] = substr ($_POST['cadenaComponents'], 0, strlen($_POST['cadenaComponents']) - 1);                    
        $ids_components = explode(',',$_POST['cadenaComponents']);
        $i=0;
        while(isset($ids_components[$i])){
            $insertTenCom = " INSERT INTO tbl_templates_components (id_templates,id_components) VALUES (".$id_template.",".$ids_components[$i].")";
            $resultadoTenCom = ejecutar($insertTenCom,$conexion);     
            if(!$resultadoTenCom) {
                $json_resultado['resultado'] = '<h3><font color="red">Error components ID:'.$ids_components[$i].'</font></h3>';
                echo json_encode($json_resultado);
                die;                
            }
            $i++;
        }
        
        $mensaje_almacenamiento = 'Register Successfull';
        $json_resultado['resultado'] = '<h3><font color="blue">'.$mensaje_almacenamiento.'</font></h3>';

        $json_resultado['mensaje'] = $mensaje_almacenamiento;
      
    } 
}else{
    $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';
}
echo json_encode($json_resultado);
?>