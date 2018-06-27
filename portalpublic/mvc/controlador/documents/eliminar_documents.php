<?php 

             session_start();
             
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
	}
}      


    $conexion = conectar();
                 
    $id_document = $_POST['id_document'];
    $ruta_archivo = $_POST['ruta_archivo'];
              
    
    $delete = "DELETE FROM tbl_documents WHERE id_document = ".$id_document;
    ejecutar($delete,$conexion);   
    
    unlink('../../../'.$ruta_archivo);
      
    $resultado['resultado'] = 'eliminado';
    
    
    echo json_encode($resultado);    

?>

                
