<?php  
         session_start();
require_once '../../../conex.php';

if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}                                                    
                    
                    $nombre_tabla = $_GET['nombre_tabla'];
                    $variables_formulario = $_GET['variables_formulario'];                                   
                    
                    if($_GET['campo_filtro'] != 'null' && $_GET['valor_filtro'] != 'null') {
                    $filtro_valores = ' WHERE '.$_GET['campo_filtro'].'  '.$_GET['valor_filtro'];                         
                    } else {
                        
                       $filtro_valores = null;
                        
                    }

                    $accion = 'consultar';
                    
                    $tablas = $nombre_tabla;
                    $variables = $variables_formulario;
                
                    
                    $sql = 'SELECT '.$variables.' FROM  '.$tablas.$filtro_valores;
                    
                    if(!strpos($_GET['id_campo'],'as')){
                        $id_campo = $_GET['id_campo'];
                    }else{
                        list($ign,$id_campo) = explode(' as ',$_GET['id_campo']);
                    }
                    
                    if(!strpos($_GET['texto_campo'],'as')){
                        $texto_campo = $_GET['texto_campo'];
                    }else{
                        list($ign,$texto_campo) = explode(' as ',$_GET['texto_campo']);
                    }                    

                    $conexion = conectar();									

        
                    
                    
                    
                        $resultado = ejecutar($sql,$conexion);

                        $i=0;
                        while ($row=mysqli_fetch_array($resultado)) 
                        {	
$reporte[$i][$id_campo] = mb_convert_encoding($row[$id_campo], 'UTF-8', 'UTF-8') ;
  $reporte[$i][$texto_campo] = mb_convert_encoding($row[$texto_campo], 'UTF-8', 'UTF-8') ;
                         //   $reporte[$i][$id_campo] = $row[$id_campo];
                         //   $reporte[$i][$texto_campo] = $row[$texto_campo];
                            $reporte[$i]['value'] = $id_campo;
                            $reporte[$i]['description'] = $texto_campo;

                        $i++;        
                        }                                         
                        
                        if(!isset($reporte)){
                             $reporte[0][$id_campo] = '';
                             $reporte[0][$texto_campo] = '';
                        }
                
                
                echo json_encode($reporte);                    
                