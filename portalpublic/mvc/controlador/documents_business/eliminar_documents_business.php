<?php 

             session_start();
             
require_once("../../../conex.php");
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
                 
    $id_document_business = $_POST['id_document_business'];
    $ruta_archivo = $_POST['ruta_archivo'];
              
    
    $delete = "DELETE FROM tbl_documents_business WHERE id_documents_business = ".$id_document_business;
    ejecutar($delete,$conexion);   
    
    unlink('../../../'.$ruta_archivo);
      
    $resultado['resultado'] = 'eliminado';
    
    
    echo json_encode($resultado);    

?>

                
