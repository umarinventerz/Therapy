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
                 
        $tipo_persona_agregar = $_POST['tipo_persona_agregar'];
        $valor_tipo_documento = strtoupper($_POST['valor_tipo_documento']);
             
        $conexion = conectar();    
        
        if(!isset($_POST['id_type_documents_persons'])) {
    

        $insert="INSERT into tbl_doc_type_documents (type_documents) values ('".$valor_tipo_documento."');";
        ejecutar($insert,$conexion);  
        
        
        $sql_consulta = "SELECT id_type_documents from tbl_doc_type_documents where type_documents = '".$valor_tipo_documento."'";
        $resultado = ejecutar($sql_consulta,$conexion);

        while ($row=mysqli_fetch_array($resultado)) {
                $id_type_documents = $row["id_type_documents"];
                break;
        }        
        
        $insert="INSERT into tbl_doc_type_persons_documents (id_type_persons,id_type_documents) values ('".$tipo_persona_agregar."','".$id_type_documents."');";
        ejecutar($insert,$conexion);          
            
        
        $json_resultado['resultado'] = 'INSERTADO';   
        $json_resultado['cargar_resultado'] = 'null'; 
        $json_resultado['identificador_resultado'] = 'null'; 
        
        } else {
            
           $id_type_documents_persons = $_POST['id_type_documents_persons'];
         
            $sql_consulta = "SELECT id_type_documents from tbl_doc_type_persons_documents where id_type_documents_persons = '".$id_type_documents_persons."'";
            $resultado = ejecutar($sql_consulta,$conexion);

            while ($row=mysqli_fetch_array($resultado)) {
                    $id_type_documents = $row["id_type_documents"];
                    break;
            }               
           
           
        $update="UPDATE tbl_doc_type_documents SET type_documents = '".$valor_tipo_documento."' WHERE id_type_documents = ".$id_type_documents.";";
        ejecutar($update,$conexion);   
        
        $update="UPDATE tbl_doc_type_persons_documents SET id_type_persons = '".$tipo_persona_agregar."' WHERE id_type_documents_persons = ".$id_type_documents_persons.";";
        ejecutar($update,$conexion);                                
            
        
        $json_resultado['resultado'] = 'MODIFICADO'; 
        $json_resultado['cargar_resultado'] = 'result_type_persons_documents.php'; 
        $json_resultado['identificador_resultado'] = 'resultado_type_persons_documents'; 
        
        }
                       
         echo json_encode($json_resultado);   


?>

                