<div class="modal fade" id="modalAddNote" name = "modalAddNote" tabindex="-1" role="dialog" aria-labelledby="modalAddNote" data-backdrop="static">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
<!--                        <div class="col-md-9">
                            <div class="panel-heading nav nav-tabs text-right">
                                <h5 class="widget-user-username"><strong>Coloca,</strong></h5>
                                <h5 class="widget-user-desc">Un slogan aqui</h5>
                            </div> /.widget-user-image 
                        </div>-->
                    </div>
                </div>

                <div class="modal-header bg-card-header">
                    <h4 class="modal-title"><?php if(isset($_GET['note'])){echo "Edit note #".$_GET['id_note'];}else{echo 'New note';}?></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <form id="formNote" <?php if(isset($_GET['note'])){?>onsubmit="return validar_form_note('edit');"<?php }else{?> onsubmit="return validar_form_note('insertar');"<?php } ?> enctype="multipart/form-data">
                            
                            
                        <div>
                        <div >

                          <div class="col-lg-9">


                            <div class="row">
                                <input type="hidden" name="id_notes" id="id_notes" value="<?=$_GET['id_note']?>">
                                <input type="hidden" name="id_careplans" id="id_careplans" value="<?=$note[0]['id_careplans']?>">
                                <input type="hidden" name="patients_id" id="patients_id" value="<?=$note[0]['patient_id']?>">
                                <input type="hidden" name="discipline_id" id="discipline_id" value="<?=$note[0]['disciplineId']?>">
                                <input type="hidden" name="poc_id_note_hidden" id="poc_id_note_hidden" value="<?=$note[0]['id_careplans']?>">
                            </div>
                                 
                                  <div class="row">                                                                 
                                <label class="col-lg-1  withoutPadding">Patients: </label>
                                <div class="col-lg-4 withoutPadding">
                                  <?php echo $note[0]['patient']."<b> ( ".$note[0]['Pat_id'].")</b>"?>
                                </div>
                                

                               
                                <label class="col-lg-1 withoutPadding">DOB/Age: </label>
                                <div class="col-lg-4 withoutPadding">
                                    <div id="npi_eval_modal" style="font-size:13px">
                                      &nbsp;&nbsp;
                                        <?php echo $note[0]['pdob']?>
                                    </div>
                                </div>
                                


                            </div>

                            <br>
                            
                            <div class="row" style="margin-top: 15px;">
                               
                                <label class="col-lg-1 withoutPadding">Visit Date:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <?php echo $note[0]['visit_date']?>                                
                                </div>
                                


                               
                                <label class="col-lg-1 withoutPadding">Duration:</label>
                                <div class="col-lg-3 withoutPadding">
                                    <select name="duration" id="duration">
                                        <option value="15" <?php if($note[0]['duration']==15){?>selected=""<?php }?>>15</option>
                                        <option value="30" <?php if($note[0]['duration']==30){?>selected=""<?php }?>>30</option>
                                        <option value="45" <?php if($note[0]['duration']==45){?>selected=""<?php }?>>45</option>
                                        <option value="60" <?php if($note[0]['duration']==60){?>selected=""<?php }?>>60</option>
                                        
                                    </select>                                    
                               
                                </div>
                                <br>

                            </div> 
                            <br>
                            <div class="row" >
                                
                              
                                <label  class="col-lg-1 withoutPadding" >Therapist:</label>
                                <div  class="col-lg-4">
                                    <?php echo $note[0]['employee']." ".$note[0]['NPI']?>
                                </div>
                               


                                
                                
                                     <label class="col-lg-1 withoutPadding">Discipline:</label>
                                <div class="col-lg-4 withoutPadding" >
                                  &nbsp;&nbsp;  <?php echo $note[0]['disciplina']?>                                
                                </div>

                               
                           
                            </div> 



                            <!-- DIV PARA CERRAR LAS DOS PRIMERAS COLUMNAS  -->
                            </div>


                            <div class="col-lg-3">
                             
                              <div>

                                    <label class="col-lg-12 withoutPadding">Company:</label>
                                    <div class="col-lg-12 withoutPadding">                                    

                                            <select name='company_id' id='company_id' class="form-control">                                                       
                                                <?php
                                                    $sql_companie  = "Select * from companies";

                                                    $resultado_companie = ejecutar($sql_companie,$conexion);
                                                    while ($row=mysqli_fetch_array($resultado_companie)) 
                                                    {                                                        

                                                        if($note[0]['company_poc']==$row["id"]){
                                                            print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["id"]."'>".$row["company_name"]." </option>"); 
                                                        }
                                                        
                                                    }

                                                ?>
                                            </select> 
                                    </div>

                                    <div class="col-lg-12 withoutPadding"  style="font-size:18px; font-weight:bold;"> <font color='#1e2c51'>                                                                
                                        
                                        <?=$note[0]['company_name']?><br>
                                        <?=$note[0]['facility_address']?><br>
                                        <?=$note[0]['facility_city'].",".$note[0]['facility_state'].",".$note[0]['facility_zip']?><br>
                                    (P) <?=$note[0]['facility_phone']?><br>
                                    (F) <?=$note[0]['facility_fax']?><br>
                                    
                                    </font>

                                    </div>
                             </div>   
                             
                             <!-- DIV PARA CERRAR LA COLUMNA DE COMPANY -->   
                            </div> 


                    <!-- DIV FINAL ANTES DE LA LINEA  -->        
                   </div>

                   </div>
                            
                            
                            
                            <br><hr>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-lg-10"><button type="button" class="btn" onclick="agregar_cpt();"><img src="../../../images/agregar.png" width="15" height="15"/>Añadir Cpt</button></div><br>
                                <br><hr>
                                <br><table class="table" id="add_cpt" border="0">                                    
                                    <tr class="success">
                                        <td><b>Cpt</b></td>
                                        <td><b>Units</b></td>
                                        <td><b>Location</b></td>
                                        <td><b>Start</b></td>
                                        <td><b>End</b></td>
                                        <td><b>Duration</b></td>
                                        <td><b>Diagnosis</b></td>
                                        <td><b>Actions</b></td>
                                    </tr>
                                    <?php
                                    
                                        $cpt_consult  = "Select N.*,N.start as start_date,N.end as end_date,C.cpt,L.name as location,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnosis from tbl_note_cpt_relation N left join cpt C ON(N.id_cpt=C.ID)"
                                                . "     LEFT JOIN tbl_location_appoiments L ON(N.location=L.id) LEFT JOIN diagnosiscodes D ON(N.id_diagnosis=D.DiagCodeId) WHERE N.id_note=".$_GET['id_note'];

                                        $resultado_cpt = ejecutar($cpt_consult,$conexion);
                                        
                                        while ($row_cpt=mysqli_fetch_array($resultado_cpt)) 
                                        {
                                            $cpt_result[]=$row_cpt;
                                        }
                                        
                                        for($j=0;$j<count($cpt_result);$j++){
                                        
                                    ?>                                    
                                        <tr class="info">
                                            <td><?=$cpt_result[$j]['cpt']?></td>
                                            <td><?=$cpt_result[$j]['units']?></td>
                                            <td><?=$cpt_result[$j]['location']?></td>
                                            <td><?=$cpt_result[$j]['start_date']?></td>
                                            <td><?=$cpt_result[$j]['end_date']?></td>
                                            <td><?=$cpt_result[$j]['duration']?></td>
                                            <td><?=$cpt_result[$j]['diagnosis']?></td>
                                            <td>
                                                <div class="btn-group">            
                                                    <a href="#" data-rel="tooltip" data-original-title="Editar" class="dropdown-toggle btn btn-primary" data-toggle="dropdown"><i class="fa fa-cogs"></i>&nbsp;</a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li align= "center" style="hidden-align: center">                                                    
                                                            <a onclick="modificarCpt(<?=$note[0]['patient_id']?>,<?=$note[0]['discipline_id']?>,'<?=$cpt_result[$j]['id_note_cpt_relation']?>',<?=$_GET['id_note']?>);" style="cursor: pointer;" class="ruta">Edit</a>
                                                        </li>
                                                        <li align= "center" style="hidden-align: center">
                                                            <a onclick="eliminarCpt('<?=$cpt_result[$j]['id_note_cpt_relation']?>',<?=$_GET['id_note']?>);" style="cursor: pointer;" class="ruta">Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    
                                    
                                </table>
                            </div>
                           <hr>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-12">Subject:</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control" id="snote" name="snote"><?php echo $note[0]['snotes']?></textarea>                                    
                                </div>
                            </div>

                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-12">Objective:</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control" id="onote" name="onote"><?php echo $note[0]['onotes']?> </textarea>                                    
                                </div>
                            </div>                                                        
                                                                                   
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-12">Assessment:</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control" id="anote" name="anote"><?php echo $note[0]['anotes']?> </textarea>                                    
                                </div>
                            </div>

                              <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-12">Plan:</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control" id="pnote" name="pnote"><?php echo $note[0]['pnotes']?> </textarea>                                    
                                </div>
                            </div> 
                            <br><hr>
                            
                            <div class="row"> 
                                <?php         
                                
                                    $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                                            . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                            . " WHERE cg.careplan_id = ".$note[0]['id_careplans']." AND cg.goal_term_id = 3 ORDER BY goal_area;";
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {     
                                        
                                        $reporte.='<div class="row" id="div_long_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="checkbox" id="long_note_check_'.$datos_careplan_goals['goal_library_id'].'" name="long_note_check_'.$datos_careplan_goals['goal_library_id'].'"></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="long_note_'.$datos_careplan_goals['goal_library_id'].'" name="long_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="long_note_area_'.$datos_careplan_goals['goal_library_id'].'" name="long_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-5"><textarea class="form-control" id="long_note_library_'.$datos_careplan_goals['goal_library_id'].'" name="long_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1 withoutPadding"><select class="form-control withoutPadding" id="long_note_assist_'.$datos_careplan_goals['goal_library_id'].'" name="long_note_assist_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_types  = "SELECT * FROM tbl_goals_assist_types WHERE gat_discipline_id = ".$note[0]['disciplineId'].";";
                                        $resultado_goals_assist_types = ejecutar($sql_goals_assist_types,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_types)) 
                                        {                                                        
                                            if($note[0]['company_poc']==$row["id"]){
                                                $reporte .= "<option value='".$row["gat_id"]."' selected>".$row["gat_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gat_id"]."'>".$row["gat_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="long_note_level_'.$datos_careplan_goals['goal_library_id'].'" name="long_note_level_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_levels  = "SELECT * FROM tbl_goals_assist_levels WHERE gal_discipline_id = ".$note[0]['disciplineId'].";";
                                        $resultado_goals_assist_levels = ejecutar($sql_goals_assist_levels,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_levels)) 
                                        {                                                        
                                            if($note[0]['company_poc']==$row["id"]){
                                                $reporte .= "<option value='".$row["gal_id"]."' selected>".$row["gal_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gal_id"]."'>".$row["gal_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="long_note_ach_'.$datos_careplan_goals['goal_library_id'].'" name="long_note_ach_'.$datos_careplan_goals['goal_library_id'].'">';
                                            $p = 0;
                                            while($p <=100){
                                                $reporte .= '<option value="'.$p.'">'.$p.'</option>';
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
                                    $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                                            . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                            . " WHERE cg.careplan_id = ".$note[0]['id_careplans']." AND cg.goal_term_id = 2 ORDER BY goal_area;";
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {
                                        $reporte.='<div class="row" id="div_short_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="checkbox" id="short_note_check_'.$datos_careplan_goals['goal_library_id'].'" name="short_note_check_'.$datos_careplan_goals['goal_library_id'].'"></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="short_note_'.$datos_careplan_goals['goal_library_id'].'" name="short_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="short_note_area_'.$datos_careplan_goals['goal_library_id'].'" name="short_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-5"><textarea class="form-control" id="short_note_library_'.$datos_careplan_goals['goal_library_id'].'" name="short_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1 withoutPadding"><select class="form-control withoutPadding" id="short_note_assist_'.$datos_careplan_goals['goal_library_id'].'" name="short_note_assist_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_types  = "SELECT * FROM tbl_goals_assist_types WHERE gat_discipline_id = ".$note[0]['disciplineId'].";";
                                        $resultado_goals_assist_types = ejecutar($sql_goals_assist_types,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_types)) 
                                        {                                                        
                                            if($note[0]['company_poc']==$row["id"]){
                                                $reporte .= "<option value='".$row["gat_id"]."' selected>".$row["gat_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gat_id"]."'>".$row["gat_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="short_note_level_'.$datos_careplan_goals['goal_library_id'].'" name="short_note_level_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_levels  = "SELECT * FROM tbl_goals_assist_levels WHERE gal_discipline_id = ".$note[0]['disciplineId'].";";
                                        $resultado_goals_assist_levels = ejecutar($sql_goals_assist_levels,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_levels)) 
                                        {                                                        
                                            if($note[0]['company_poc']==$row["id"]){
                                                $reporte .= "<option value='".$row["gal_id"]."' selected>".$row["gal_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gal_id"]."'>".$row["gal_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';

                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="short_note_ach_'.$datos_careplan_goals['goal_library_id'].'" name="short_note_ach_'.$datos_careplan_goals['goal_library_id'].'">';
                                            $p = 0;
                                            while($p <=100){
                                                $reporte .= '<option value="'.$p.'">'.$p.'</option>';
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
                                
                                    $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                                            . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                            . " WHERE cg.careplan_id = ".$note[0]['id_careplans']." AND cg.goal_term_id = 1 ORDER BY goal_area;";
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {
                                        $reporte.='<div class="row" id="div_na_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="checkbox" id="na_note_check_'.$datos_careplan_goals['goal_library_id'].'" name="na_note_check_'.$datos_careplan_goals['goal_library_id'].'"></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="na_note_'.$datos_careplan_goals['goal_library_id'].'" name="na_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="na_note_area_'.$datos_careplan_goals['goal_library_id'].'" name="na_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-5"><textarea class="form-control" id="na_note_library_'.$datos_careplan_goals['goal_library_id'].'" name="na_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1 withoutPadding"><select class="form-control withoutPadding" id="na_note_assist_'.$datos_careplan_goals['goal_library_id'].'" name="na_note_assist_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_types  = "SELECT * FROM tbl_goals_assist_types WHERE gat_discipline_id = ".$note[0]['disciplineId'].";";
                                        $resultado_goals_assist_types = ejecutar($sql_goals_assist_types,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_types)) 
                                        {                                                        
                                            if($note[0]['company_poc']==$row["id"]){
                                                $reporte .= "<option value='".$row["gat_id"]."' selected>".$row["gat_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gat_id"]."'>".$row["gat_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="na_note_level_'.$datos_careplan_goals['goal_library_id'].'" name="na_note_level_'.$datos_careplan_goals['goal_library_id'].'" >';
                                        $sql_goals_assist_levels  = "SELECT * FROM tbl_goals_assist_levels WHERE gal_discipline_id = ".$note[0]['disciplineId'].";";
                                        $resultado_goals_assist_levels = ejecutar($sql_goals_assist_levels,$conexion);
                                        while ($row=mysqli_fetch_array($resultado_goals_assist_levels)) 
                                        {                                                        
                                            if($note[0]['company_poc']==$row["id"]){
                                                $reporte .= "<option value='".$row["gal_id"]."' selected>".$row["gal_text"]." </option>"; 
                                            }else{
                                                $reporte .= "<option value='".$row["gal_id"]."'>".$row["gal_text"]." </option>"; 
                                            }
                                        }
                                        $reporte .= '</select></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="na_note_ach_'.$datos_careplan_goals['goal_library_id'].'" name="na_note_ach_'.$datos_careplan_goals['goal_library_id'].'">';
                                            $p = 0;
                                            while($p <=100){
                                                $reporte .= '<option value="'.$p.'">'.$p.'</option>';
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
                            </div>
                           <!--  <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Signed:</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" id="signedNote" name="signedNote" style="width: 15px;height: 15px;" value="1" class="form-control" <?php if($note[0]['user_signed']==1){?>checked=""<?php } ?>>
                                </div>
                                <div class="col-lg-5">
                                    <input type="text" id="dateSigned" name="dateSigned" class="form-control" placeholder="Signed Date" value="<?=$note[0]['user_signed_date']?>">
                                </div>
                            </div> -->
                            
                            <br>


                            
                            <?php   //if($therapist_type['assistant']==1){
                                 //consulto si tiene la firma agregada para supervisor
                                $firma="SELECT count(id) as contador,therapist_signature   from tbl_signature_note 
                                 WHERE id_note='".$_GET['id_note']."'  Group by therapist_signature";
                                
                                $resultado_firma = ejecutar($firma,$conexion);                                   
                                while ($row_firma=mysqli_fetch_array($resultado_firma)) 
                                {
                                    $valor_firma['contador']=$row_firma['contador'];
                                    $valor_firma['therapist_signature']=$row_firma['therapist_signature'];
                                }
                                
                                // firma del supervisor 
                                   $firma2="SELECT count(id) as contador,therapist_signature as super  from tbl_signature_note 
                                 WHERE id_note='".$_GET['id_note']."' 
                                AND actor='SUPERVISOR' Group by therapist_signature
                                ";
                                $resultado_firma2 = ejecutar($firma2,$conexion);                                        
                                while ($row_firma2=mysqli_fetch_array($resultado_firma2)) 
                                {
                                   // $valor_firma['contador']=$row_firma['contador'];
                                    $valor_firma2['super']=$row_firma2['super'];
                                }

                                //consulto el supervisor del terapista 
                                $sql_supervisor="SELECT supervisor from employee WHERE id='".$note[0]['user_id']."' ";
                                $resultado_supervisor = ejecutar($sql_supervisor,$conexion);                                        
                                while ($row_supervisor=mysqli_fetch_array($resultado_supervisor)) 
                                {
                                    $valor_supervisor['supervisor']=$row_supervisor['supervisor'];
                                    
                                }
                               
                                //consulto LA FIRMA DEL supervisor en tabla employee
                                $sql_supervisor_firma="SELECT signature from employee WHERE id='".$valor_supervisor['supervisor']."' ";
                                $resultado_supervisor_firma = ejecutar($sql_supervisor_firma,$conexion);                                        
                                while ($row_supervisor_firma=mysqli_fetch_array($resultado_supervisor_firma)) 
                                {
                                    $valor_supervisor_firma['signature']=$row_supervisor_firma['signature'];
                                    
                                }


                                
                           //}     
                             if($therapist_type['assistant']==1){    

                               ?>

                            <div class="row" style="margin-top: 15px;">
                                    <label class="col-lg-1">Supervisor:</label>
                                    <div class="col-lg-1">
                                        <button type="button" class="btn btn-primary" onclick="signature('signatureNoteSupervisor','firma_note_supervisor');" <?php if($_SESSION['user_id']!=$therapist_type['user2']){?>disabled=""<?php }?>><i class="fa fa-check"></i>&nbsp;Signature</button>
                                    </div>
                                    <div class="col-lg-offset-1 col-lg-3">
                                        <?php 
                                            if(isset($_GET['id_note']) && $valor_firma['contador']>1){
                                        ?>
                                            <div id="signatureNoteSupervisor">
                                                <?php }else{?>
                                                     <div id="signatureNoteSupervisor" style="display:none">
                                                <?php } ?>
                                                     <?php 
                                                        if(!isset($_GET['id_note'])){
                                                    ?>
                                                            <img src="../../../images/sign/signature.png" style="width: 100px">
                                                        <?php }elseif($valor_firma['contador']>1){?>
                                                            <img src="../../../images/sign/<?=$valor_supervisor_firma['signature']?>" style="width: 100px">
                                                        <?php }else{ ?>
                                                            <img src="../../../images/sign/<?=$valor_supervisor_firma['signature']?>" style="width: 100px">
                                                        <?php } ?>
                                                </div>
                                                <input type="hidden" name="firma_note_supervisor" id="firma_note_supervisor"/>
                                                <input type="hidden" name="ruta_firma_note_supervisor" id="ruta_firma_note_supervisor"/>
                                                <input type="hidden" name="latitud_firma_note_supervisor" id="latitud_firma_note_supervisor"/>
                                                <input type="hidden" name="longitud_firma_note_supervisor" id="longitud_firma_note_supervisor"/>
                                            </div>
                                    </div>
                            </div>
                            <?php } ?>
                            
                            <?php 
                                //consulto si tiene la firma agregada para terapista
                                $firma_terapista="SELECT count(id) as contador,therapist_signature from tbl_signature_note WHERE id_note=".$_GET['id_note']." 
                                AND actor='THERAPIST' Group by therapist_signature
                                ";
                                $resultado_firma_terapista = ejecutar($firma_terapista,$conexion);                                        
                                while ($row_firma_terapista=mysqli_fetch_array($resultado_firma_terapista)) 
                                {
                                    $valor_firma_terapista['contador']=$row_firma_terapista['contador'];
                                    $valor_firma_terapista['therapist_signature']=$row_firma_terapista['therapist_signature'];
                                }
                            ?>
                            <div class="row">
                                    <label class="col-lg-1">&nbsp;&nbsp;&nbsp;Therapist:</label>
                                    <div class="col-lg-1">
                                        <button type="button" class="btn btn-primary" onclick="signature('signatureNoteTherapist','firma_note_terapist');" <?php if($_SESSION['user_id']!=$therapist_type['user1']){?>disabled=""<?php }?>><i class="fa fa-check"></i>&nbsp;Signature<?php //echo $note[0]['signature2'];?></button>
                                    </div>
                                    <div class="col-lg-offset-1 col-lg-3">
                                        <?php 
                                            if(isset($_GET['id_note']) && $valor_firma_terapista['contador']>0){
                                        ?>
                                            <div id="signatureNoteTherapist">
                                                <?php }else{?>
                                                     <div id="signatureNoteTherapist" style="display:none">
                                                <?php } ?>
                                                     <?php 
                                                        if(!isset($_GET['id_note'])){
                                                    ?>
                                                            <img src="../../../images/sign/signature.png" style="width: 100px">
                                                        <?php }elseif($note[0]['therapist_signed']==1){?>
                                                            <img src="../../../images/sign/<?=$note[0]['signature2']?>" style="width: 100px">
                                                        <?php }else{ ?>
                                                            <img src="../../../images/sign/<?=$note[0]['signature2']?>" style="width: 100px">
                                                        <?php } ?>
                                                </div>
                                                <input type="hidden" name="firma_note_terapist" id="firma_note_terapist"/>
                                                <input type="hidden" name="ruta_firma_note_terapist" id="ruta_firma_note_terapist"/>
                                                <input type="hidden" name="latitud_firma_note_terapist" id="latitud_firma_note_terapist"/>
                                                <input type="hidden" name="longitud_firma_note_terapist" id="longitud_firma_note_terapist"/>
                                            </div>
                                    </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-1">&nbsp;&nbsp;&nbsp;Attachment:</label>
                                <div class="col-lg-5">
                                    <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                                </div>
                            </div>
                            <hr/>
                            <div class="row card_footer">                                                                            
                                    <div class="col-sm-offset-4 col-sm-2 mg-top-sm">

                                        <button type="submit"  class="btn btn-success">Save</button>
                                        <?php 
                                        if($therapist_type['user1']==$_SESSION['user_id'] || $therapist_type['user2']==$_SESSION['user_id']){


                                            if($therapist_type['assistant']==1 && $valor_firma['contador']>1){
                                        ?>
                        <button type="submit" disabled="" class="btn btn-success"><?php if(isset($_GET['note'])){echo "Edit";}else{echo "Save";}?></button>
                                        <?php }elseif($therapist_type['assistant']==0 && $valor_firma['contador']>0){ ?>
                        <button type="submit" disabled="" class="btn btn-success"><?php if(isset($_GET['note'])){echo "Edit";}else{echo "Save";}?></button>
                                        <?php }else{?>
                                            <button type="submit" class="btn btn-success"><?php if(isset($_GET['note'])){echo "Edit";}else{echo "Save";}?></button>
                                        <?php }
                                          }      ?>
                                    </div>                                                                            
                                    <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                                        <button type="button" class="btn btn-danger" onclick="cancelar(<?=$note[0]['patient_id']?>,<?=$note[0]['disciplineId']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                                    </div>
                                    <a href="pdf/note.php?id=<?=$_GET['id_note']?>" class="btn btn-default" target="_blank" style="cursor: pointer;" class="ruta"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
                            </div>
                            <hr>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>