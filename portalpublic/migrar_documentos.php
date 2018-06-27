<?php
require_once("conex.php");

$conexion = conectar();


//$sql = "Select * from signed_doctor where route_file like '%signed_doctor%'"; // CAMBIAR AQUI POR LA TABLA QUE QUIERES MIGRAR
$sql = "Select * from authorizations where route_file like '%authorizations/%'"; // CAMBIAR AQUI POR LA TABLA QUE QUIERES MIGRAR
//$sql = "Select * from signed_doctor where route_file like '%signed_doctor%'"; // CAMBIAR AQUI POR LA TABLA QUE QUIERES MIGRAR
$resultado = ejecutar($sql,$conexion);
$i = 0;
while ($row=mysqli_fetch_assoc($resultado)) {
        $datos[$i] = $row;
        $i++;
}

$r = 0;
while(isset($datos[$r])){
    
    $patient_name_directorio = $patient_name = str_replace(',','_',str_replace(' ','',$datos[$r]['Patient_name']));
    if (!file_exists('patients')) {        
        mkdir('patients', 0777, false);  
    }

    if (!file_exists('patients/'.$patient_name_directorio)) {        
        mkdir('patients/'.$patient_name_directorio, 0777, false);  
    }    

    //$sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 3"; //signed_doctor
    $sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 2"; //Authorizations
    //$sql_type_document  = "SELECT * FROM tbl_doc_type_documents t where id_type_documents = 1"; //prescriptions
    $resultado_type_document = ejecutar($sql_type_document,$conexion);

    while ($row=mysqli_fetch_array($resultado_type_document)) {
             $reporte_tipo_documento = $row["type_documents"];
    }

    if (!file_exists('patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento)) {        
        mkdir('patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento, 0777, false);  
    }
    
    list($ignorar,$nombre_archivo) = explode('/',$datos[$r]['Route_file']); //VERIFICAR ELN LA TABLA EL FORMATO CON EL QUE ESTA GUARDADO A VER SI SIRVE ESTA LINEA
    echo $ruta = 'patients/'.$patient_name_directorio.'/'.$reporte_tipo_documento.'/'.$nombre_archivo;
    rename($datos[$r]['route_file'],$ruta);            
    //chmod($ruta, 0777);
    //update = " UPDATE signed_doctor SET Route_file = '../../../".$ruta."' WHERE id_signed_doctor = ".$datos[$r]['id_signed_doctor'];// signed_doctor
    $update = " UPDATE authorizations SET Route_file = '../../../".$ruta."' WHERE id_authorizations = ".$datos[$r]['id_authorizations'];// Authorizations
    echo '<br>';    
    echo '<br>';
    
    ejecutar($update,$conexion);
    
    $r++;
}
//echo '<pre>';
//print_r($datos);
?>