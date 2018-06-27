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

$sql_departaments = "select us.user_name, us.first_name, us.last_name, us.user_id, us.status_id, us.user_type, dc.departaments_company, dc.id_departaments_company "
    . "from user_system us "
    . "left join tbl_user_system_departaments_company utdc on utdc.id_user_system = us.user_id "
    . "left join tbl_departaments_company dc on dc.id_departaments_company = utdc.id_departaments_company "
    . "where user_id ='".$_POST['id_user']."' order by dc.departaments_company";
$resultado_departaments = ejecutar($sql_departaments,$conexion);
$reporte_departaments = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado_departaments)) {
    
    if($datos['id_departaments_company'] == null) {
        $json_resultado['resultado_query'] = 'No existen datos';
        break;   
    }
    $json_resultado['departament'][$i] = $datos['id_departaments_company']; 
    $json_resultado['departament_name'][$i] = $datos['departaments_company'];
    
    $sql_tabs = "select * from tbl_user_system_tabs ust "
        . "left join tbl_tabs t on t.id_tabs = ust.id_tabs "
        . "left join tbl_departaments_company dc on dc.id_departaments_company = ust.id_departaments_company "
        . "where ust.id_user_system = ".$_POST['id_user']." "
        . " AND  ust.id_departaments_company = ".$datos['id_departaments_company']." ORDER BY t.id_tabs";
    $resultado_tabs = ejecutar($sql_tabs,$conexion);
    $reporte_tabs = array();

    $t = 0;      
    while($datos_tabs = mysqli_fetch_assoc($resultado_tabs)) {                    
        $json_resultado['tabs'][$datos_tabs['id_departaments_company']][$t] = $datos_tabs['id_tabs'];
        $t++;
    }
    
    $i++;
}
//echo '<pre>';
//print_r($json_resultado);
echo json_encode($json_resultado);
?>
