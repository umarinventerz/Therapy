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
                 
    $tipo_documento = $_POST['tipo_documento'];
    $departament = $_POST['id_departaments'];
              
    
        $sql_departaments_type_documents  = 'SELECT version_document from tbl_documents_business WHERE id_departaments = '.$departament.' AND id_type_documents = '.$tipo_documento.';';
        $resultado_departament_documents = ejecutar($sql_departaments_type_documents,$conexion);

        
        
        $i=0;
        while ($row=mysqli_fetch_array($resultado_departament_documents)) {
                $version[] = $row["version_document"];
                $i++;
        }   
              
        
        if(isset($version)){                        
            $version_final = array_pop($version);
            $version_final = $version_final + 1;            
        } else {            
            $version_final = '1';                                    
        }
      
        $json_resultado['version'] = $version_final;                 
                       
         echo json_encode($json_resultado);  
                
?>

                
