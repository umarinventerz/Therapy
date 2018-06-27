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

$sql  = "SELECT * FROM tbl_goals_library gl"
        . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = gl.goal_library_id "       
        . " WHERE gl.goal_library_id IN( ".$_GET['cadena'].") ORDER BY goal_area;"; 
$resultado = ejecutar($sql,$conexion);   
$reporte = '';
$i = 1;     
while($datos = mysqli_fetch_assoc($resultado)) {
    $reporte.='<div class="row" id="div_'. strtolower($_GET['term']).'_'.$datos['goal_library_id'].'">';    
    $reporte .= '<div class="col-lg-1 text-center"><img src="../../../images/papelera.png" style="width:20px;height:20px;cursor:pointer;" onclick="eliminarDiv(\'div_'. strtolower($_GET['term']).'_'.$datos['goal_library_id'].'\')"></div>';
    $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos['goal_library_id'].'" id="'. strtolower($_GET['term']).'_'.$datos['goal_library_id'].'" name="'. strtolower($_GET['term']).'_'.$datos['goal_library_id'].'" readonly></div>';
    $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="'. strtolower($_GET['term']).'_area_'.$datos['goal_library_id'].'" name="'. strtolower($_GET['term']).'_area_'.$datos['goal_library_id'].'">'.rtrim($datos['goal_area']).'</textarea></div>';
    $reporte .= '<div class="col-lg-7"><textarea class="form-control" id="'. strtolower($_GET['term']).'_library_'.$datos['goal_library_id'].'" name="'. strtolower($_GET['term']).'_library_'.$datos['goal_library_id'].'">'.rtrim($datos['goal_text']).'</textarea></div>';
    $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="'. strtolower($_GET['term']).'_ach_'.$datos['goal_library_id'].'" name="'. strtolower($_GET['term']).'_ach_'.$datos['goal_library_id'].'">';
                    $p = 0;
                    while($p <=100){
                        $reporte .= '<option value="'.$p.'">'.$p.'</option>';
                        $p = $p + 5;
                    }
    $reporte .= '</select></div>';
    $reporte.='</div>';
}
?>

<div class="row">
    <div class="col-lg-12 header-blue">
        <div class="col-lg-1 text-center"></div>
        <div class="col-lg-1 text-center"> # </div>
        <div class="col-lg-2 text-center"> Area of Concern</div>
        <div class="col-lg-7 text-center"> <?=($_GET['term'] == 'Na')?'N/A':$_GET['term']?> Term Goal </div>
        <div class="col-lg-1 text-center"> Pct Ach</div>
    </div>
</div>
<?= $reporte ?>