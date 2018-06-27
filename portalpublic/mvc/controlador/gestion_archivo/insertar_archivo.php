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
                
    
        if($reporte_tipo_persona_array[$i] == 'p'){
            
            $type_person = 'patients';
            
        }
        
        if($reporte_tipo_persona_array[$i] == 'e'){
            
            $type_person = 'employee';
            
        }        

$Patient_name = null;
$Employee_name = null;        
        
   $e = $i+1;


$conexion = conectar();

if($reporte_pat_id_array[$i] == 'null'){
    $reporte_pat_id_array[$i] = null;
    
} else {
    
        $sql_patients  = "Select distinct Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients where Pat_id = '".$reporte_pat_id_array[$i]."' order by Patient_name";
        $resultado = ejecutar($sql_patients,$conexion);

        while ($row=mysqli_fetch_array($resultado)) {
                $Patient_name = str_replace(',','_',str_replace(' ','',$row["Patient_name"]));
        }
        
       if (!file_exists('../../../patients')) {        
           mkdir('../../../patients', 0777, false);  
       }
       
       if (!file_exists('../../../patients/'.$Patient_name)) {        
           mkdir('../../../patients/'.$Patient_name, 0777, false);  
       }    
       
       if (!file_exists('../../../patients/'.$Patient_name.'/'.$reporte_tipo_documento_array[$i])) {        
           mkdir('../../../patients/'.$Patient_name.'/'.$reporte_tipo_documento_array[$i], 0777, false);  
       }
       
       $ruta = 'patients/'.$Patient_name.'/'.$reporte_tipo_documento_array[$i].'/'.$_FILES['file-'.$e]['name'];
       

 

    rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);
       
}


if($reporte_id_employee_array[$i] == 'null'){
    $reporte_id_employee_array[$i] = null;   
   
} else {
    
        $sql_employee  = "Select Distinct concat(first_name,' ',last_name) as Employee_name from employee where id = '".$reporte_id_employee_array[$i]."' order by first_name";
        $resultado = ejecutar($sql_employee,$conexion);

        while ($row=mysqli_fetch_array($resultado)) {
                $Employee_name = str_replace(',','_',str_replace(' ','',$row["Employee_name"]));
        }
        
       if (!file_exists('../../../employee')) {        
           mkdir('../../../employee', 0777, false);  
       }
       
       if (!file_exists('../../../employee/'.$Employee_name)) {        
           mkdir('../../../employee/'.$Employee_name, 0777, false);  
       }
       
       $ruta = 'employee/'.$Employee_name.'/'.$_FILES['file-'.$e]['name'];
       
    rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);   
            
}
     

echo $insert="INSERT into tbl_documents (type_document, type_person, id_table_relation, id_person , route_document) 
values ('".$reporte_tipo_documento_array[$i]."','".$type_person."','0','".$reporte_pat_id_array[$i].$reporte_id_employee_array[$i]."','".$ruta."');";
ejecutar($insert,$conexion);       
    

    
                        
        $i++;
    }
       
                                      

?>

                