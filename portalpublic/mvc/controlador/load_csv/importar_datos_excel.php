<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
error_reporting(0);

$conexion = conectar();

$tabla = $_POST['nombre_tabla'];
$caracter_separacion = $_POST['caracter_separacion'];
$crear_tabla = $_POST['crear_tabla'];
$no_update_files = $_POST['no_update_files'];
$borrar_contenido_tabla = $_POST['borrar_contenido_tabla'];

if($_POST['numero_linea_comprobacion'] != ''){
    $numero_linea_comprobacion = explode(",",$_POST['numero_linea_comprobacion']);
} else {

    $numero_linea_comprobacion = null;
    
}

    $tipo = $_FILES['archivo']['type'];

    $tamanio = $_FILES['archivo']['size'];

    $archivotmp = $_FILES['archivo']['tmp_name'];
  
    $fp = fopen ( $archivotmp , "r" );
    $lineas = file($archivotmp);

    $i=0;
    $validando_columna = null;
    foreach ($lineas as $linea_num => $linea) {  
       
        if($i == 0){
            
            $nombres_primeros_campos = explode($caracter_separacion,$linea);            
            
        }
        
        
        
        if($i == 2){
        
        $datos = explode($caracter_separacion,$linea);         
        $numero_columnas_excel = count($datos);                               
                      
            $g=0;
            $campos_vacios= 'si';
            while(isset($datos[$g])){
                
                $datos[$g] = trim($datos[$g]);
                
                if($datos[$g] != null && $datos[$g] != ''){                    
                    $campos_vacios = 'no';                    
                }
                
                $g++;
            }
        
            
        if($numero_columnas_excel == 0 || $campos_vacios == 'si') {        
            $validando_columna = 'validar';
        
            
        } else {
            
                $iniciar = 'primera';
            
        }
        
        }
        
        if($i == 3 && $validando_columna == 'validar'){
            
            
            if($numero_columnas_excel > 0) {
                $iniciar = 'despues_tercera';
                $nombres_primeros_campos = explode($caracter_separacion,$linea);
                
                
            }
            
            
        }
        
        if($i == 3){
            break;
        }
        
        $i++;
    }
    
    
    
    
    

    $query_crear_tabla = null;  

    if($crear_tabla == 'si'){
        $tabla = $_POST['nombre_tabla'];
     
    $query_tabla = "show tables like '".$tabla."';";     
    $resultado = ejecutar($query_tabla,$conexion);            
    $row = mysqli_fetch_array($resultado);
    
    if($row != null){    
    
        $json_resultado['resultado'] = 'error_tabla_existente';        
        echo json_encode($json_resultado);  
        die;
        
    }  
                                       
          
  $query_crear_tabla .= '

CREATE TABLE `kidswork_therapy`.`'.$tabla.'` 
(';
 
    
  $query_crear_tabla .= '`id_'.  str_replace('tbl_','',$tabla).'` INT NOT NULL AUTO_INCREMENT,
          ';  
  
    $i=0;  
    while(isset($nombres_primeros_campos[$i])){                
                            
        $query_crear_tabla .= '`'. str_replace('"','', str_replace(' ','_',(strtolower(trim($nombres_primeros_campos[$i]))))).'` TEXT NULL';                     
        
        $query_crear_tabla .= ',
                ';  
        
            $i++;
        }

        
        

  $query_crear_tabla .= '        
  PRIMARY KEY (`id_'.str_replace('tbl_','',$tabla).'`));';  
  
  ejecutar($query_crear_tabla,$conexion);
                
    } 

    $query_columnas = "SELECT column_name, data_type,column_default,is_nullable FROM Information_Schema.Columns WHERE Table_Name = '".$tabla."'  and TABLE_SCHEMA='".BD."' ORDER  BY ordinal_position;";     
    
    $resultado = ejecutar($query_columnas,$conexion);
    
    $row = mysqli_fetch_array($resultado);
    
    if($row == null){
        
        $json_resultado['resultado'] = 'error_tabla';        
        echo json_encode($json_resultado);               
    
    } else {
    
    
    
    
        $k=0;
        while ($row=mysqli_fetch_array($resultado)) {	           
            $reporte_columnas[$k]['column_name'] = $row['column_name'];                              
        $k++;        
        }  
            
    $numero_columnas_tabla = (count($reporte_columnas));
    
    if( $tabla == 'tbl_treatments_charges')
        $numero_columnas_tabla = $numero_columnas_tabla - 2;
    
    $columnas_tabla = null;
    
    $i=0;
    $p=0;
    while (isset($reporte_columnas[$i])){
        $columnas_tabla .= "`".$reporte_columnas[$i]['column_name']."`";

        if(isset($reporte_columnas[$i+1])){

            $columnas_tabla .= ',';

        }

        if($numero_linea_comprobacion[0] != null) {
            if($numero_linea_comprobacion[$p] == ($i+1)){

                $columna_comprobar[$p] = $reporte_columnas[$i]['column_name'];
                $p++;

            }        
        }

        $i++;
    }
     
    if( $tabla == 'tbl_treatments_charges'){
        $columnas_tabla = str_replace(',`status`,`status_paid`', '', $columnas_tabla);        
    }
    
if($borrar_contenido_tabla == 'si'){
    $delete = 'DELETE FROM '.$tabla;
    $resultadoDelete = ejecutar($delete,$conexion);
}
        
$query_insercion_mostrar = null;
$i=0;
$iniciar_ciclo='no';
while (( $linea = fgetcsv ( $fp , 1000 , $caracter_separacion )) !== FALSE ) {
 
    if($iniciar == 'despues_tercera' && $iniciar_ciclo == 'no') {        
        $i++;
        
        if($i == 3){
            $iniciar_ciclo = 'si';            
            $i= 0;
        }
        
    } else {

    if($i == 0) {      
       $datos = $linea;
       $numero_columnas_excel =  count($datos);                      
       
       if($numero_columnas_tabla != $numero_columnas_excel) {
         
           $json_resultado['resultado'] = 'error_columnas';
           echo json_encode($json_resultado);
           die;
       }
       
    
    } else {                        
        
        $datos = $linea;
        
        $valor_columnas = null;
        $valor_columnas_update = null;
        
        $c=0;
        $p = 0;
        $whereSelect = null;
        while(isset($datos[$c])){
            
            $datos[$c] = trim($datos[$c]);            
            
              if($datos[$c] == null || $datos[$c] == ''){
                  
                 $datos[$c] = 'null'; 
                  
              }
              
              $valor_columnas .= "'".str_replace('"', "", $datos[$c])."'";
              $valor_columnas_update .= "'".str_replace('"', "", $datos[$c])."'";
              
              if(isset($datos[$c+1])){
              
                  $valor_columnas .= ',';
                  $valor_columnas_update .= '|';
                  
              }
              //$camposSelect = '';
              if($numero_linea_comprobacion[0] != null) {              
                    if($numero_linea_comprobacion[$p] == ($c+1)){
                        $camposSelect .= str_replace('"', "", $columna_comprobar[$p]).",";
                        $whereSelect .= " AND `".str_replace('"', "", $columna_comprobar[$p])."` = '".str_replace('"', "", $datos[$c])."'";
                        $p++;
                    }
                    
                    
                    
            
              } 
              
            
            $c++;
        } 
        
   
        if($whereSelect != null) {
            $camposSelect = substr ($camposSelect, 0, strlen($camposSelect) - 1);           
            $query_tabla_comprobacion = "SELECT * FROM  ".$tabla." WHERE TRUE ".$whereSelect.";";
            $resultado = ejecutar($query_tabla_comprobacion,$conexion);            
            $datos_comprobacion = mysqli_fetch_array($resultado);        

            $numero_registros_comprobacion = count($datos_comprobacion);

            if($numero_registros_comprobacion == 0){

                $accion_comprobacion = 'insertar';

            } else {

                $accion_comprobacion = 'modificar';
                $valor_where = $whereSelect;

            }
        }else {
                  
                  $accion_comprobacion = 'insertar';
                  
              }
                                          
        
        
        if($accion_comprobacion == 'insertar') {
            
            $query_insercion = "INSERT INTO ".$tabla." (".$columnas_tabla.") VALUES (".$valor_columnas.");";               
            $resultado = ejecutar($query_insercion,$conexion);
            
        }
        
        if($accion_comprobacion == 'modificar' && $no_update_files == 'no' && $borrar_contenido_tabla == 'no') {
            
            $reporte_datos_comprobacion_col = explode(',',$columnas_tabla);           
            $reporte_datos_comprobacion_val = explode('|',$valor_columnas_update);
            
            
             $query_modificacion = 'UPDATE '.$tabla.' SET ';
            
            $r=0;
            while(isset($reporte_datos_comprobacion_col[$r])){
                
                $query_modificacion .= '`'.$reporte_datos_comprobacion_col[$r].'` = '.$reporte_datos_comprobacion_val[$r];
                
                if(isset($reporte_datos_comprobacion_col[$r+1])){
                    $query_modificacion .= ',';
                }
                
                $r++;
            }
            
            $query_modificacion .= " WHERE TRUE ".$valor_where.";";
            $query_modificacion = str_replace("``","`",$query_modificacion);
            $resultado = ejecutar($query_modificacion,$conexion); 
        }        
        
    
    
    if(!$resultado){
        
        $json_resultado['resultado'] = 'error_query';
        
        echo json_encode($json_resultado);
        die;
    }
    
    
    
   }
 
   $i++;
   
    }
        $n++;
   
}

    $query_insercion_mostrar =  '<br><br><font size="2">'.$query_crear_tabla.'</font>';

    $json_resultado['resultado'] = 'datos_importados';
    $json_resultado['querys'] = $query_insercion_mostrar;    
    echo json_encode($json_resultado); 

    
    }
?>