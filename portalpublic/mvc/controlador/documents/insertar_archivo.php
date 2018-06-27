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
    $reporte_id_insurance_array = explode('|',  substr($reporte['c_id_insurance_c'],0,-1));
    $reporte_id_physician_array = explode('|',  substr($reporte['c_id_physician_c'],0,-1));
    $reporte_id_referral_array = explode('|',  substr($reporte['c_id_referral_c'],0,-1));
    $reporte_id_contacto_array = explode('|',  substr($reporte['c_id_persona_contacto_c'],0,-1));
    $reporte_tipo_persona_array = explode('|',  substr($reporte['c_tipo_persona_c'],0,-1));
    $reporte_tipo_documento_array = explode('|',  substr($reporte['c_tipo_documento_c'],0,-1));
    
    
    $i=0;
    while(isset($reporte_tipo_persona_array[$i])){                            

$Patient_name = null;
$Employee_name = null;        
        
   $e = $i+1;


$conexion = conectar();

if($reporte_pat_id_array[0] == 'null'){
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
       
        $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = '".$reporte_tipo_documento_array[$i]."'";
        $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                $reporte_tipo_documento = $row["type_documents"];
        }
        
       if (!file_exists('../../../patients/'.$Patient_name.'/'.$reporte_tipo_documento)) {        
           mkdir('../../../patients/'.$Patient_name.'/'.$reporte_tipo_documento, 0777, false);  
       }
       
       $ruta = 'patients/'.$Patient_name.'/'.$reporte_tipo_documento.'/'.$_FILES['file-'.$e]['name'];
       

 

    rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);
//	chmod("'../../../'.$ruta", 0644);
       
}


if($reporte_id_employee_array[0] == 'null'){
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
       
       $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = '".$reporte_tipo_documento_array[$i]."'";
       $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                $reporte_tipo_documento = $row["type_documents"];
        }
        
       if (!file_exists('../../../employee/'.$Employee_name.'/'.$reporte_tipo_documento)) {        
           mkdir('../../../employee/'.$Employee_name.'/'.$reporte_tipo_documento, 0777, false);  
       }
       
       $ruta = 'employee/'.$Employee_name.'/'.$reporte_tipo_documento.'/'.$_FILES['file-'.$e]['name'];
       
    rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);
	$chmod = '../../../'.$ruta;
	chmod($chmod, 0644);
            
}




if($reporte_id_insurance_array[0] == 'null'){
    $reporte_id_insurance_array[$i] = null;   
   
} else {
    
        $sql_insurance  = "Select Distinct insurance from seguros where id = '".$reporte_id_insurance_array[$i]."' order by insurance";
        $resultado = ejecutar($sql_insurance,$conexion);

        while ($row=mysqli_fetch_array($resultado)) {
                $insurance_name = $row["insurance"];
        }
        
       if (!file_exists('../../../insurance')) {        
           mkdir('../../../insurance', 0777, false);  
       }
       
       if (!file_exists('../../../insurance/'.$insurance_name)) {        
           mkdir('../../../insurance/'.$insurance_name, 0777, false);  
       }
       
       $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = '".$reporte_tipo_documento_array[$i]."'";
       $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                $reporte_tipo_documento = $row["type_documents"];
        }
        
       if (!file_exists('../../../insurance/'.$insurance_name.'/'.$reporte_tipo_documento)) {        
           mkdir('../../../insurance/'.$insurance_name.'/'.$reporte_tipo_documento, 0777, false);  
       }
       
       $ruta = 'insurance/'.$insurance_name.'/'.$reporte_tipo_documento.'/'.$_FILES['file-'.$e]['name'];
       
    rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);
            
}        
 


if($reporte_id_physician_array[$i] == 'null'){
    $reporte_id_physician_array[$i] = null;   
   
} else {
    
         $sql_physician  = "Select Distinct Name as physician from physician where Phy_id = '".$reporte_id_physician_array[$i]."' order by Name";
        $resultado = ejecutar($sql_physician,$conexion);

        while ($row=mysqli_fetch_array($resultado)) {
                $physician_name = $row["physician"];
        }
        
       if (!file_exists('../../../physician')) {        
           mkdir('../../../physician', 0777, false);  
       }
       
       if (!file_exists('../../../physician/'.$physician_name)) {        
           mkdir('../../../physician/'.$physician_name, 0777, false);  
       }
       
       $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = '".$reporte_tipo_documento_array[$i]."'";
       $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                $reporte_tipo_documento = $row["type_documents"];
        }
        
       if (!file_exists('../../../physician/'.$physician_name.'/'.$reporte_tipo_documento)) {        
           mkdir('../../../physician/'.$physician_name.'/'.$reporte_tipo_documento, 0777, false);  
       }
       
       $ruta = 'physician/'.$physician_name.'/'.$reporte_tipo_documento.'/'.$_FILES['file-'.$e]['name'];
       
       rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);
            
}

//referral

if($reporte_id_referral_array[$i] == 'null'){
    $reporte_id_referral_array[$i] = null;   
   
} else {
    
         $sql_physician  = "Select First_name from tbl_contacto_persona where id_ferral = '".$reporte_id_referral_array[$i]."' order by First_name";
        $resultado = ejecutar($sql_physician,$conexion);

        while ($row=mysqli_fetch_array($resultado)) {
                $referral_name = $row["First_name"];
        }
        
       if (!file_exists('../../../referral')) {        
           mkdir('../../../referral', 0777, false);  
       }
       
       if (!file_exists('../../../referral/'.$referral_name)) {        
           mkdir('../../../referral/'.$referral_name, 0777, false);  
       }
       
       $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = '".$reporte_tipo_documento_array[$i]."'";
       $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                $reporte_tipo_documento = $row["type_documents"];
        }
        
       if (!file_exists('../../../referral/'.$referral_name.'/'.$reporte_tipo_documento)) {        
           mkdir('../../../referral/'.$referral_name.'/'.$reporte_tipo_documento, 0777, false);  
       }
       
       $ruta = 'referral/'.$referral_name.'/'.$reporte_tipo_documento.'/'.$_FILES['file-'.$e]['name'];
       
       rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);
            
}

//contacts
if($reporte_id_contacto_array[$i] == 'null'){
    $reporte_id_contacto_array[$i] = null;   
   
} else {
    
         $sql_physician  = "Select persona_contacto from tbl_contacto_persona where id_persona_contacto = '".$reporte_id_contacto_array[$i]."' order by persona_contacto";
        $resultado = ejecutar($sql_physician,$conexion);

        while ($row=mysqli_fetch_array($resultado)) {
                $contacto_name = $row["persona_contacto"];
        }
        
       if (!file_exists('../../../contacts')) {        
           mkdir('../../../contacts', 0777, false);  
       }
       
       if (!file_exists('../../../contacts/'.$contacto_name)) {        
           mkdir('../../../contacts/'.$contacto_name, 0777, false);  
       }
       
       $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = '".$reporte_tipo_documento_array[$i]."'";
       $resultado_type_document = ejecutar($sql_type_document,$conexion);

        while ($row=mysqli_fetch_array($resultado_type_document)) {
                $reporte_tipo_documento = $row["type_documents"];
        }
        
       if (!file_exists('../../../contacts/'.$contacto_name.'/'.$reporte_tipo_documento)) {        
           mkdir('../../../contacts/'.$contacto_name.'/'.$reporte_tipo_documento, 0777, false);  
       }
       
       $ruta = 'contacts/'.$contacto_name.'/'.$reporte_tipo_documento.'/'.$_FILES['file-'.$e]['name'];
       
       rename($_FILES['file-'.$e]['tmp_name'],'../../../'.$ruta);
            
}
        

 $insert="INSERT into tbl_documents (id_type_document, id_type_person, id_table_relation, id_person , route_document) 
values ('".$reporte_tipo_documento_array[$i]."','".$reporte_tipo_persona_array[$i]."','0','".$reporte_pat_id_array[$i].$reporte_id_employee_array[$i].$reporte_id_physician_array[$i].$reporte_id_insurance_array[$i].$reporte_id_referral_array[$i].$reporte_id_contacto_array[$i]."','".$ruta."');";


ejecutar($insert,$conexion);       
    

  
                        
        $i++;
    }
       
                                      

?>
