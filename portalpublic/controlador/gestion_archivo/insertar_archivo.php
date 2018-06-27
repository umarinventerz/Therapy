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
                 
    $campos_formulario_array = explode('&',$_POST['campos_formulario']);
              
    
    
    $i=0;
    while(isset($campos_formulario_array[$i])){
                
        $campos_detallados_array = explode('=',$campos_formulario_array[$i]);
        
        $reporte[$campos_detallados_array[0]] = str_replace('%7C','|',str_replace('+','',$campos_detallados_array[1]));
                                
        $i++;
    }
    
    $reporte_pat_id_array = explode('|',  substr($reporte['c_Pat_id_c'],0,-1));
    $reporte_id_employee_array = explode('|',  substr($reporte['c_id_employee_c'],0,-1));
    $reporte_tipo_persona_array = explode('|',  substr($reporte['c_tipo_persona_c'],0,-1));
    $reporte_tipo_documento_array = explode('|',  substr($reporte['c_tipo_documento_c'],0,-1));
    
 
    
    
    $i=0;
    while(isset($reporte_tipo_persona_array[$i])){
        
        if(isset($reporte_pat_id_array[$i])){
            
            $table_relation = 'patients';
        }
        
        if(isset($reporte_id_employee_array[$i])){
            
            $table_relation = 'employee';
    }      
    
        if($reporte_tipo_persona_array[$i] == 'p'){
            
            $type_person = 'patients';
            
        }
        
        if($reporte_tipo_persona_array[$i] == 'e'){
            
            $type_person = 'employee';
            
        }        
    
$conexion = conectar();

if(!isset($reporte_pat_id_array[$i])){
    $reporte_pat_id_array[$i] = null;
}

if(!isset($reporte_id_employee_array[$i])){
    $reporte_id_employee_array[$i] = null;
}

    $e = $i+1;

    if(move_uploaded_file($_FILES['file-'.$e]['tmp_name'], '../../../archivos/'.$_FILES['file-'.$e]['name'])){
        echo 'insertada';                                
    } else {                            
        echo 'error';
    }  


$insert="INSERT into tbl_documents (table_relation, type_person, id_table_relation, id_person , route_document) 
values ('".$table_relation."','".$type_person."','0','".$reporte_pat_id_array[$i].$reporte_id_employee_array[$i]."','../../../archivos/".$_FILES['file-'.$e]['name']."');";
ejecutar($insert,$conexion);       
    

    
                        
        $i++;
    }
       
                                      

?>

                