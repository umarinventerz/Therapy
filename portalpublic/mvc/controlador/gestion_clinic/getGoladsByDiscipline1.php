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

if($_GET['term'] == 'Short') $term = 2;
if($_GET['term'] == 'Long') $term = 3;
if($_GET['term'] == 'Na') $term = 1;

if($_GET['id_careplans_edit']!= 'undefined' && $_GET['id_careplans_edit']!= ''){
    $goalsString = '';
    $sql_careplan_goals_selected  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                    . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                    . " WHERE cg.careplan_id = ".$_GET['id_careplans_edit']." AND cg.goal_term_id = ".$term." ORDER BY goal_area;";
    $resultado_careplan_goals_selected = ejecutar($sql_careplan_goals_selected,$conexion);   
    $reporte_selected = [];
    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals_selected)) {
        $reporte_selected[] = $datos_careplan_goals['goal_library_id'];
        $goalsString .= $datos_careplan_goals['goal_library_id'].',';
    }
    if($goalsString != ''){
        $goalsString = substr($goalsString, '0', (strlen($goalsString)-1));
    }   
}

 $sql  = "SELECT * FROM tbl_goals_library gl
         LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = gl.goal_term_id       
         WHERE gl.goal_discipline_id = '".$_GET['discipline_id']."' 
         ORDER BY goal_area; "; 
$resultado = ejecutar($sql,$conexion);   
$reporte = '';
$i = 1;     
$class = '';
while($datos = mysqli_fetch_assoc($resultado)) {
    
    if (in_array($datos['goal_library_id'], $reporte_selected)) {
        $checked = 'checked';
    }else{
        $checked = '';
    }
    if($class == 'header-gray'){
        $class = '';
    }else{
        $class = 'header-gray';
    }
    $reporte.='<div  class="row '.$class.'">';    
    $reporte .= '<div class="col-lg-2 text-center"><input type="checkbox" onclick="loadStringGoals()" value="'.$datos['goal_library_id'].'" name="goal_library_id_'.$datos['goal_library_id'].'" id="goal_library_id_'.$datos['goal_library_id'].'" '.$checked.'></div>';
    $reporte .= '<div class="col-lg-4">'.$datos['goal_area'].'</div>';
    $reporte .= '<div class="col-lg-6">'.$datos['goal_text'].'</div>';
    $reporte.='</div>';
}

?>
<input id="goalsString" name="goalsString" value="<?=$goalsString?>" type="hidden" readonly>
<div class="row">
    <div class="col-lg-12 bg-card-header">
        <div class="col-lg-1">
            Term:
        </div>
        <div class="col-lg-1">
            <input type="radio" id="term" name="term" value="Na" onclick="selectetByTerm()" <?=($_GET['term'] =='Na')?'checked':''?> > N/A
        </div>
        <div class="col-lg-1">
            <input type="radio" id="term" name="term" value="Short" onclick="selectetByTerm()" <?=($_GET['term'] =='Short')?'checked':''?>> Short
        </div>
        <div class="col-lg-1">
            <input type="radio" id="term" name="term" value="Long" onclick="selectetByTerm()" <?=($_GET['term'] =='Long')?'checked':''?>> Long
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 header-blue">
        <div class="col-lg-2 text-center"> </div>
        <div class="col-lg-4 text-center"> Area of Concern</div>
        <div class="col-lg-6 text-center"> Goal </div>
    </div>
</div>
<div style="overflow-y:scroll; height:350px;" >
<?= $reporte ?>
</div>
<div class="row">
    <div class="col-lg-12 bg-card-header text-center">
        <button type="button" class="btn btn-primary" onclick="addGoalds()"><i class="fa fa-check"></i>&nbsp;Add Goals</button>
    </div>
</div>