<?php 

             session_start();
             
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../home/home.php";</script>';
	}
}      


    $conexion = conectar();
                 

       if (!file_exists('../../../document_business')) {        
           mkdir('../../../document_business', 0777, false);  
       }    
    
    $campos_formulario_array = explode('&',$_POST['campos_formulario']);
              
    
    
    $i=0;
    while(isset($campos_formulario_array[$i])){
                
        $campos_detallados_array = explode('=',$campos_formulario_array[$i]);
        
        $reporte[$campos_detallados_array[0]] = str_replace('%7C','|',str_replace('+',' ',$campos_detallados_array[1]));
                                
        $i++;
    }
        
    $reporte_id_departaments_array = explode('|',  substr($reporte['c_id_departaments_c'],0,-1));
    $reporte_id_type_documents_array = explode('|',  substr($reporte['c_id_type_documents_c'],0,-1));
    $reporte_version_array = explode('|',  substr($reporte['c_version_c'],0,-1));
    $reporte_description_array = explode('|',  substr($reporte['c_description_c'],0,-1));
  
    
    
    
    $i=0;
    while(isset($reporte_id_departaments_array[$i])){
      
        $e = $i+1;
        
        $sql_departaments  = "SELECT departaments from tbl_departaments WHERE id_departaments = ".$reporte_id_departaments_array[$i].';';
        $resultado_departament = ejecutar($sql_departaments,$conexion);

        while ($row=mysqli_fetch_array($resultado_departament)) {
                $departament = str_replace(',','_',str_replace(' ','',$row["departaments"]));
        }   
        
        $sql_type_document  = "SELECT type_documents from tbl_doc_type_documents WHERE id_type_documents = ".$reporte_id_type_documents_array[$i].';';
        $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                $type_document = str_replace(',','_',str_replace(' ','',$row["type_documents"]));
        }          
        
        
    if (!file_exists('../../../document_business/'.$departament)) {        
        mkdir('../../../document_business/'.$departament, 0777, false);  
    }     

    if (!file_exists('../../../document_business/'.$departament.'/'.$type_document)) {        
        mkdir('../../../document_business/'.$departament.'/'.$type_document, 0777, false);  
    }  

    if (!file_exists('../../../document_business/'.$departament.'/'.$type_document.'/'.$reporte_version_array[$i])) {        
        mkdir('../../../document_business/'.$departament.'/'.$type_document.'/'.$reporte_version_array[$i], 0777, false);  
    }     

    $ruta = 'document_business/'.$departament.'/'.$type_document.'/'.$reporte_version_array[$i].'/'.$_FILES['file-'.$e]['name'];        
    $ruta_name = 'document_business/'.$departament.'/'.$type_document.'/'.$reporte_version_array[$i].'/'.$_FILES['file-'.$e]['name'];        
    rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);
    
        
    
   echo $insert="INSERT into tbl_documents_business (id_type_documents, id_departaments, version_document, ruta, description) 
    values ('".$reporte_id_type_documents_array[$i]."','".$reporte_id_departaments_array[$i]."','".$reporte_version_array[$i]."','".$ruta."','".$reporte_description_array[$i]."');";
    ejecutar($insert,$conexion);   

        $i++;

    }                                    


?>

                
