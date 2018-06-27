<?php
error_reporting(0);
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
$conexion = conectar();
$id_template=$_POST['template_id'];
$id_eval=$_POST['eval'];
$type=$_POST['type_modal'];
$components=$_POST['variable'];


$sql  = "SELECT * FROM tbl_modal_temporal WHERE type_modal=".$type." AND id_modal=".$id_eval; 
$resultado = ejecutar($sql,$conexion);
while($datos = mysqli_fetch_assoc($resultado)) {
    $data[]=$datos;
}

if(count($data)>0){
    
    $componentes=explode('_',$components);
    if($componentes[2]=='si'){
        $insert="INSERT INTO tbl_modal_temporal(type_modal,id_modal,componentes) VALUES(".$type.",".$id_eval.",".$componentes[1].")";
        ejecutar($insert,$conexion);
    }else{
        $delete="DELETE FROM tbl_modal_temporal WHERE type_modal=".$type." AND id_modal=".$id_eval." AND componentes=".$componentes[1]; 
        ejecutar($delete,$conexion);
        
    }
    $sql_res="SELECT * FROM tbl_modal_temporal T left join tbl_components C ON(C.id=T.componentes)
            WHERE T.id_modal=".$id_eval." AND type_modal=".$type;
    $respuesta=ejecutar($sql_res,$conexion);    
    $reportes = '';
    while($datos = mysqli_fetch_assoc($respuesta)) {
        $reportes.='<div id="elemento'.$datos["id"].'">';
        $reportes .= $datos['components'];
        $reportes.='</div>';
    }    
   
}else{
    
    $sql  = "SELECT * FROM tbl_templates t"
        . " LEFT JOIN tbl_templates_components tc ON tc.id_templates = t.id "
        . " LEFT JOIN tbl_components c ON c.id = tc.id_components "
        . " WHERE t.id = ".$id_template.";"; 
    $resultados = ejecutar($sql,$conexion);   
    $reporte = '';
    $i = 1;     
    while($datos = mysqli_fetch_assoc($resultados)){        
        $reporte[]= $datos['id'];
    }
    $componentes=explode('_',$components);
    if($componentes[2]=='si'){
        $reporte[]=$componentes[1];
    }else{
        for($i=0;$i<count($reporte);$i++){
            if($componentes[1]==$reporte[$i]){
                unset($reporte[$i]);
            }
        }
    }
    $claves= array_values($reporte);    
    for($i=0;$i<count($claves);$i++){
        $insert="INSERT INTO tbl_modal_temporal(type_modal,id_modal,componentes) VALUES(".$type.",".$id_eval.",".$claves[$i].")";
        ejecutar($insert,$conexion);
    }
    $sql_res="SELECT * FROM tbl_modal_temporal T left join tbl_components C ON(C.id=T.componentes)
            WHERE T.id_modal=".$id_eval." AND type_modal=".$type;
    $respuesta=ejecutar($sql_res,$conexion);
    
    $reportes = '';
    while($datos = mysqli_fetch_assoc($respuesta)) {
        $reportes.='<div id="elemento'.$datos["id"].'">';
        $reportes .= $datos['components'];
        $reportes.='</div>';
    }
}
$json_resultado['contentTemplate'] = $reportes;


echo json_encode($json_resultado); 

?>