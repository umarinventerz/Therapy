<?php
session_start();

require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

//consulto si el appoiments enviado tiene alguna evaluacion asociada

$appoiment="SELECT COUNT(*) as contador,id FROM tbl_visits v
WHERE (v.deleted = 0 OR v.deleted IS NULL) AND
app_id=".$_POST['id_date'];

$active_appoiment = ejecutar($appoiment,$conexion);
while ($row_appoiment=mysqli_fetch_array($active_appoiment)) {
    $valor_appoiment['contador'] = $row_appoiment['contador'];
    $valor_appoiment['id'] = $row_appoiment['id'];
}

if($valor_appoiment['contador']>0){    
    //evaluaciones 
    $eval="SELECT COUNT(*) as contador,id FROM tbl_evaluations 
    WHERE (deleted = 0 OR deleted IS NULL) AND
    visit_id=".$valor_appoiment['id'];

    $active_eval = ejecutar($eval,$conexion);
    while ($row_eval=mysqli_fetch_array($active_eval)) {
        $valor_eval['contador'] = $row_eval['contador'];
        $valor_eval['id'] = $row_eval['id'];
    }

    //teraphy 
     $therapy="SELECT COUNT(*) as contador,id_notes FROM tbl_notes_documentation 
     WHERE (deleted = 0 OR deleted IS NULL) AND
     visit_id=".$valor_appoiment['id'];

    $active_therapy = ejecutar($therapy,$conexion);
    while ($row_therapy=mysqli_fetch_array($active_therapy)) {
        $valor_therapy['contador'] = $row_therapy['contador'];
        $valor_therapy['id_notes'] = $row_therapy['id_notes'];
    }
    
    if($valor_eval['contador']>0){
        $id=$valor_eval['id'];
        $ruta="evaluacion";
    }else{
        $id=$valor_therapy['id_notes'];
        $ruta="therapy";
    }
    $mensaje="<b>There is a Visit already assign to this Appoitment, Do you want to continue to this Visit:</b>";
    $success='reenvio';    
    $id_appoiment=$_POST['id_date'];    
    
    $arreglo=array('mensaje'=>$mensaje,'success'=>$success,'id_appoiment'=>$id_appoiment,'id'=>$id,'ruta'=>$ruta);
    echo json_encode($arreglo); 
    
}else{    
    $success=true;
    $arreglo=array('success'=>$success);
    echo json_encode($arreglo); 
}