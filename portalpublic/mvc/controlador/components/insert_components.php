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
    if(isset($_POST["editor"]) && $_POST["editor"] != null){ $editor = $_POST["editor"]; } else { $editor = 'null'; }
    
    $insert = " INSERT INTO tbl_components(components,created,id_user,discipline_id,document_id, name, description) "
            . "VALUES ('".$_POST["editor"]."',now(),".$_SESSION['user_id'].",'".$_POST["discipline_id"]."',"
            . "'".$_POST["type_document"]."','".$_POST["name"]."','".$_POST["description"]."');";
    $resultado = ejecutar($insert,$conexion);     
    if(!$resultado) {           
        $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';
    } else {
        $mensaje_almacenamiento = 'Register Successfull';
        $json_resultado['resultado'] = '<h3><font color="blue">'.$mensaje_almacenamiento.'</font></h3>';

        $json_resultado['mensaje'] = $mensaje_almacenamiento;
      
    } 
}else{
    $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';
}
echo json_encode($json_resultado);
?>