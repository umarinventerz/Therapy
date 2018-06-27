<?php         
          session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
//	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
//		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
//		echo '<script>window.location="../../home/home.php";</script>';
//	}
}

if($_GET['action'] == 'edit'){
    $checked_goal = 'checked';
}else{
    $checked_goal = '';
}
$conexion = conectar();                      
                                   
                                    $sql_pocs="SELECT id_careplans,Discipline FROM careplans WHERE evaluations_id=".$_GET['evaluation_id'];
                                    $res_poc = ejecutar($sql_pocs,$conexion); 
                                    while($row_pocs = mysqli_fetch_assoc($res_poc)){         
                                        $data_pocs[]=$row_pocs;
                                    } 

                                    if($data_pocs[0]['id_careplans'] != ''){
                                        if($_GET['action'] == 'edit' && $_GET['document'] == 'summary'){
                                            $sql_careplan_goals  = "SELECT *,sdg.careplan_goal_id as goal_library_id,sdg.summary_goal_area as goal_area ,sdg.summary_goal_text as goal_text "
                                                . " FROM tbl_summary_documentation_goals sdg"
                                                . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = sdg.goal_term_id "       
                                                . " WHERE sdg.id_summary = ".$_GET['id_summary']." AND sdg.goal_term_id = 3 ORDER BY goal_area;";
                                        }else{
                                            if($_GET['action'] == 'edit' && $_GET['document'] == 'discharge'){
                                                $sql_careplan_goals  = "SELECT *,ddg.careplan_goal_id as goal_library_id,ddg.discharge_goal_area as goal_area ,ddg.discharge_goal_text as goal_text "
                                                . " FROM tbl_discharge_documentation_goals ddg"
                                                . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = ddg.goal_term_id "       
                                                . " WHERE ddg.id_discharge = ".$_GET['id_discharge']." AND ddg.goal_term_id = 3 ORDER BY goal_area;";
                                            }else{
                                                $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text "
                                                    . " FROM careplan_goals cg"
                                                    . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                                    . " WHERE cg.careplan_id = ".$data_pocs[0]['id_careplans']." AND cg.goal_term_id = 3 ORDER BY goal_area;";
                                            }
                                        }
                                            
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {     
                                        
                                        $reporte.='<div class="row" id="div_long_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="checkbox" id="long_summary_check_'.$datos_careplan_goals['goal_library_id'].'" name="long_summary_check_'.$datos_careplan_goals['goal_library_id'].'" '.$checked_goal.'></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="long_summary_'.$datos_careplan_goals['goal_library_id'].'" name="long_summary_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="long_summary_area_'.$datos_careplan_goals['goal_library_id'].'" name="long_summary_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-5"><textarea class="form-control" id="long_summary_library_'.$datos_careplan_goals['goal_library_id'].'" name="long_summary_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1 withoutPadding"><select class="form-control withoutPadding" id="long_summary_assist_'.$datos_careplan_goals['goal_library_id'].'" name="long_summary_assist_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_types  = "SELECT * FROM tbl_goals_assist_types WHERE gat_discipline_id = ".$data_pocs[0]['Discipline'].";";
                                        $resultado_goals_assist_types = ejecutar($sql_goals_assist_types,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_types)) 
                                        {                                                        
                                            if($datos_careplan_goals['goal_assist_type_id']==$row["gat_id"]){
                                                $reporte .= "<option value='".$row["gat_id"]."' selected>".$row["gat_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gat_id"]."'>".$row["gat_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="long_summary_level_'.$datos_careplan_goals['goal_library_id'].'" name="long_summary_level_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_levels  = "SELECT * FROM tbl_goals_assist_levels WHERE gal_discipline_id = ".$data_pocs[0]['Discipline'].";";
                                        $resultado_goals_assist_levels = ejecutar($sql_goals_assist_levels,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_levels)) 
                                        {                                                        
                                            
                                            if($datos_careplan_goals['goal_assist_level_id']==$row["gal_id"]){
                                                $reporte .= "<option value='".$row["gal_id"]."' selected>".$row["gal_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gal_id"]."'>".$row["gal_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="long_summary_ach_'.$datos_careplan_goals['goal_library_id'].'" name="long_summary_ach_'.$datos_careplan_goals['goal_library_id'].'">';
                                            $p = 5;
                                            while($p <=100){
                                                if($datos_careplan_goals['pct_ach']==$p){
                                                    $reporte .= '<option value="'.$p.'" selected>'.$p.'</option>';
                                                }else{
                                                    $reporte .= '<option value="'.$p.'">'.$p.'</option>';
                                                }
                                                $p = $p + 5;
                                            }
                                        $reporte .= '</select></div>'; 
                                        $reporte .= '</div>';
                                    }
                                
                                ?>
                                <?php if($reporte != ''):?>
                                    <div class="row">
                                        <div class="col-lg-12 header-blue">
                                            <div class="col-lg-1 text-center"></div>
                                            <div class="col-lg-1 text-center"> # </div>
                                            <div class="col-lg-2 text-center"> Area of Concern</div>
                                            <div class="col-lg-5 text-center"> Long Term Goal </div>
                                            <div class="col-lg-1 text-center"> Assistance </div>
                                            <div class="col-lg-1 text-center"> Level </div>
                                            <div class="col-lg-1 text-center"> %Ach </div>
                                        </div>
                                    </div>
                                    <?= $reporte ?>
                                <?php endif;?>
                            </div>
                            
                            <div class="row"> 
                                <?php
                                        if($_GET['action'] == 'edit' && $_GET['document'] == 'summary'){
                                            $sql_careplan_goals  = "SELECT *,sdg.careplan_goal_id as goal_library_id,sdg.summary_goal_area as goal_area ,sdg.summary_goal_text as goal_text "
                                                . " FROM tbl_summary_documentation_goals sdg"
                                                . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = sdg.goal_term_id "       
                                                . " WHERE sdg.id_summary = ".$_GET['id_summary']." AND sdg.goal_term_id = 2 ORDER BY goal_area;";
                                        }else{
                                            if($_GET['action'] == 'edit' && $_GET['document'] == 'discharge'){
                                                $sql_careplan_goals  = "SELECT *,ddg.careplan_goal_id as goal_library_id,ddg.discharge_goal_area as goal_area ,ddg.discharge_goal_text as goal_text "
                                                . " FROM tbl_discharge_documentation_goals ddg"
                                                . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = ddg.goal_term_id "       
                                                . " WHERE ddg.id_discharge = ".$_GET['id_discharge']." AND ddg.goal_term_id = 2 ORDER BY goal_area;";
                                            }else{
                                                $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                                               . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                               . " WHERE cg.careplan_id = ".$data_pocs[0]['id_careplans']." AND cg.goal_term_id = 2 ORDER BY goal_area;";
                                            }
                                        }
                                   
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {
                                        $reporte.='<div class="row" id="div_short_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="checkbox" id="short_summary_check_'.$datos_careplan_goals['goal_library_id'].'" name="short_summary_check_'.$datos_careplan_goals['goal_library_id'].'" '.$checked_goal.'></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="short_summary_'.$datos_careplan_goals['goal_library_id'].'" name="short_summary_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="short_summary_area_'.$datos_careplan_goals['goal_library_id'].'" name="short_summary_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-5"><textarea class="form-control" id="short_summary_library_'.$datos_careplan_goals['goal_library_id'].'" name="short_summary_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1 withoutPadding"><select class="form-control withoutPadding" id="short_summary_assist_'.$datos_careplan_goals['goal_library_id'].'" name="short_summary_assist_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_types  = "SELECT * FROM tbl_goals_assist_types WHERE gat_discipline_id = ".$data_pocs[0]['Discipline'].";";
                                        $resultado_goals_assist_types = ejecutar($sql_goals_assist_types,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_types)) 
                                        {                                                                                                    
                                            if($datos_careplan_goals['goal_assist_type_id']==$row["gat_id"]){
                                                $reporte .= "<option value='".$row["gat_id"]."' selected>".$row["gat_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gat_id"]."'>".$row["gat_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="short_summary_level_'.$datos_careplan_goals['goal_library_id'].'" name="short_summary_level_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_levels  = "SELECT * FROM tbl_goals_assist_levels WHERE gal_discipline_id = ".$data_pocs[0]['Discipline'].";";
                                        $resultado_goals_assist_levels = ejecutar($sql_goals_assist_levels,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_levels)) 
                                        {                                                        
                                            if($datos_careplan_goals['goal_assist_level_id']==$row["gal_id"]){
                                                $reporte .= "<option value='".$row["gal_id"]."' selected>".$row["gal_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gal_id"]."'>".$row["gal_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="short_summary_ach_'.$datos_careplan_goals['goal_library_id'].'" name="short_summary_ach_'.$datos_careplan_goals['goal_library_id'].'">';
                                            $p = 5;
                                            while($p <=100){
                                                if($datos_careplan_goals['pct_ach']==$p){
                                                    $reporte .= '<option value="'.$p.'" selected>'.$p.'</option>';
                                                }else{
                                                    $reporte .= '<option value="'.$p.'">'.$p.'</option>';
                                                }
                                                
                                                $p = $p + 5;
                                            }
                                        $reporte .= '</select></div>'; 
                                        $reporte.='</div>';
                                    }
                                ?>
                                <?php if($reporte != ''):?>
                                    <div class="row">
                                        <div class="col-lg-12 header-blue">
                                            <div class="col-lg-1 text-center"></div>
                                            <div class="col-lg-1 text-center"> # </div>
                                            <div class="col-lg-2 text-center"> Area of Concern</div>
                                            <div class="col-lg-5 text-center"> Short Term Goal </div>
                                            <div class="col-lg-1 text-center"> Assistance </div>
                                            <div class="col-lg-1 text-center"> Level </div>
                                            <div class="col-lg-1 text-center"> %Ach </div>
                                        </div>
                                    </div>
                                    <?= $reporte ?>
                                <?php endif; ?>
                            </div>
                            <div class="row"> 
                                <?php
                                    
                                        if($_GET['action'] == 'edit' && $_GET['document'] == 'summary'){
                                            $sql_careplan_goals  = "SELECT *,sdg.careplan_goal_id as goal_library_id,sdg.summary_goal_area as goal_area ,sdg.summary_goal_text as goal_text "
                                                . " FROM tbl_summary_documentation_goals sdg"
                                                . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = sdg.goal_term_id "       
                                                . " WHERE sdg.id_summary = ".$_GET['id_summary']." AND sdg.goal_term_id = 1 ORDER BY goal_area;";
                                        }else{
                                            if($_GET['action'] == 'edit' && $_GET['document'] == 'discharge'){
                                                $sql_careplan_goals  = "SELECT *,ddg.careplan_goal_id as goal_library_id,ddg.discharge_goal_area as goal_area ,ddg.discharge_goal_text as goal_text "
                                                . " FROM tbl_discharge_documentation_goals ddg"
                                                . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = ddg.goal_term_id "       
                                                . " WHERE ddg.id_discharge = ".$_GET['id_discharge']." AND ddg.goal_term_id = 1 ORDER BY goal_area;";
                                            }else{
                                                $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                                               . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                               . " WHERE cg.careplan_id = ".$data_pocs[0]['id_careplans']." AND cg.goal_term_id = 1 ORDER BY goal_area;";
                                            }
                                        }
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {
                                        $reporte.='<div class="row" id="div_na_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="checkbox" id="na_summary_check_'.$datos_careplan_goals['goal_library_id'].'" name="na_summary_check_'.$datos_careplan_goals['goal_library_id'].'" '.$checked_goal.'></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="na_summary_'.$datos_careplan_goals['goal_library_id'].'" name="na_summary_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="na_summary_area_'.$datos_careplan_goals['goal_library_id'].'" name="na_summary_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-5"><textarea class="form-control" id="na_summary_library_'.$datos_careplan_goals['goal_library_id'].'" name="na_summary_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1 withoutPadding"><select class="form-control withoutPadding" id="na_summary_assist_'.$datos_careplan_goals['goal_library_id'].'" name="na_summary_assist_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_types  = "SELECT * FROM tbl_goals_assist_types WHERE gat_discipline_id = ".$data_pocs[0]['Discipline'].";";
                                        $resultado_goals_assist_types = ejecutar($sql_goals_assist_types,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_types)) 
                                        {                              
                                            
                                            if($datos_careplan_goals['goal_assist_type_id']==$row["gat_id"]){
                                                $reporte .= "<option value='".$row["gat_id"]."' selected>".$row["gat_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gat_id"]."'>".$row["gat_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="na_summary_level_'.$datos_careplan_goals['goal_library_id'].'" name="na_summary_level_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_levels  = "SELECT * FROM tbl_goals_assist_levels WHERE gal_discipline_id = ".$data_pocs[0]['Discipline'].";";
                                        $resultado_goals_assist_levels = ejecutar($sql_goals_assist_levels,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_levels)) 
                                        {                                                        
                                            if($datos_careplan_goals['goal_assist_level_id']==$row["gal_id"]){
                                                $reporte .= "<option value='".$row["gal_id"]."' selected>".$row["gal_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gal_id"]."'>".$row["gal_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="na_summary_ach_'.$datos_careplan_goals['goal_library_id'].'" name="na_summary_ach_'.$datos_careplan_goals['goal_library_id'].'">';
                                            $p = 5;
                                            while($p <=100){
                                                if($datos_careplan_goals['pct_ach']==$p){
                                                    $reporte .= '<option value="'.$p.'" selected>'.$p.'</option>';
                                                }else{
                                                    $reporte .= '<option value="'.$p.'">'.$p.'</option>';
                                                }
                                                $p = $p + 5;
                                            }
                                        $reporte .= '</select></div>';                                       
                                        $reporte.='</div>';
                                    }
                                ?>                           
                                <?php if($reporte != ''):?>
                                    <div class="row">
                                        <div class="col-lg-12 header-blue">
                                            <div class="col-lg-1 text-center"></div>
                                            <div class="col-lg-1 text-center"> # </div>
                                            <div class="col-lg-2 text-center"> Area of Concern</div>
                                            <div class="col-lg-5 text-center"> N/A Term Goal </div>
                                            <div class="col-lg-1 text-center"> Assistance </div>
                                            <div class="col-lg-1 text-center"> Level </div>
                                            <div class="col-lg-1 text-center"> %Ach </div>
                                        </div>
                                    </div>
                                    <?= $reporte ?>
                                <?php endif; ?>
                        <?php }?>
                                    
