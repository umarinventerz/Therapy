<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
	}
}

    $conexion = conectar();

    //$_POST['name']
    $_POST['fieldsDepartaments'] = str_replace('|','', $_POST['fieldsDepartaments']);            
    
    $array_fieldsDepartaments = explode(',',$_POST['fieldsDepartaments']);
    
    $delete_1 = "DELETE FROM tbl_user_system_departaments_company WHERE id_user_system = ".$_POST['name'];
    $resultado = ejecutar($delete_1,$conexion);
    $delete_2 = "DELETE FROM tbl_user_system_tabs WHERE id_user_system = ".$_POST['name'];
    $resultado = ejecutar($delete_2,$conexion);
    
    
    $i= 0;
    while($array_fieldsDepartaments[$i]){        
        $sql_insert = "INSERT INTO tbl_user_system_departaments_company (id_user_system,id_departaments_company) VALUES (".$_POST['name'].",".$array_fieldsDepartaments[$i]."); ";        
        $resultado = ejecutar($sql_insert,$conexion);    
        
        if($_POST['div_departaments_'.$array_fieldsDepartaments[$i].'_tabsHidden']!=''){
            $_POST['div_departaments_'.$array_fieldsDepartaments[$i].'_tabsHidden'] = str_replace('|','', $_POST['div_departaments_'.$array_fieldsDepartaments[$i].'_tabsHidden']);
            $array_tabs = explode(',',$_POST['div_departaments_'.$array_fieldsDepartaments[$i].'_tabsHidden']);        
            $f= 0;
            while($array_tabs[$f]){
                list($departament,$idTabs) = explode('-',$array_tabs[$f]);
                $sql_insert1 = "INSERT INTO tbl_user_system_tabs (id_user_system,id_tabs,id_departaments_company) VALUES (".$_POST['name'].",".$idTabs.",".$departament."); ";
                $resultado_tabs = ejecutar($sql_insert1,$conexion);    
                $f++;
            }
        }
        $i++;
    }
 
    $mensaje_almacenamiento = 'Almacenamiento Satisfactorio';
    if(($resultado) && ($resultado_tabs)) {
                
        $json_resultado['resultado'] = '<h3><font color="blue">'.$mensaje_almacenamiento.'</font></h3>';
        $json_resultado['mensaje'] = $mensaje_almacenamiento;
        
    } else {
                
        $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';

    } 
    $json_resultado['query_1'] = $delete_1;             
    $json_resultado['query_2'] = $delete_2;
    echo json_encode($json_resultado);


?>