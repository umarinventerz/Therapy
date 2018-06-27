<div class="modal fade" id="modalAddPoc" name = "modalAddPoc" tabindex="-1" role="dialog" aria-labelledby="modalAddPoc" data-backdrop="static">
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
                    <h4 class="modal-title"><?=$titulo?></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <form id="formPoc" <?php if(!isset($_GET['id_poc'])){?>onsubmit="return validar_form_poc('insertar');"<?php }else{?> onsubmit="return validar_form_poc('edit');"<?php } ?>enctype="multipart/form-data">
                           
                    <div>

                        <div class="col-lg-9">

                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Patients: </label>
                                <div class="col-lg-6 withoutPadding">
                                   &nbsp;&nbsp; <?php echo $data[0]['patient']." <b>(".$data[0]['Pat_id'].")</b>"?>
                                </div>
                                <label class="col-lg-1 withoutPadding">Created: </label>
                                <div class="col-lg-3 withoutPadding">
                                    <input type="text" name="created_poc" id="created_poc" disabled="" class="form-control" value="<?=$data[0]["created"]?>"/>
                                </div>
                                
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-1">From:</label>
                                <div class="col-lg-4">
                                    <input type="text" name="from_poc" id="from_poc" class="form-control" placeholder="From" <?php if(isset($_GET['id_poc'])){?>value="<?=$data_poc[0]['POC_due']?>"<?php } ?>/>
                                </div>
                                
                                <label class="col-lg-1">To:</label>
                                <div class="col-lg-4">
                                    <input type="text" name="to_poc" id="to_poc" class="form-control" placeholder="To" <?php if(isset($_GET['id_poc'])){?>value="<?=$data_poc[0]['Re_Eval_due']?>"<?php } ?>/>
                                </div>
                                
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Therapist:</label>
                                <div class="col-lg-3 withoutPadding">
                                 &nbsp;&nbsp;&nbsp;   <?php echo $discipline['employee']?>
                                </div>
                                
                                <label class="col-lg-1 withoutPadding">Diag.:</label>
                                <div class="col-lg-6 withoutPadding">
                                    <select name='diagnostic_id_poc' id='diagnostic_id_poc' class="">                                                				
                                            <?php
                                                $sql_diagnostic  = "Select DiagCodeId,concat(DiagCodeValue,', ',DiagCodeDescrip) as name from diagnosiscodes where TreatDiscipId=".$data[0]['discipline_id'];
                                                
                                                $resultado_diagnostic = ejecutar($sql_diagnostic,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_diagnostic)) 
                                                {
                                                    if(!isset($_GET['id_poc'])){
                                                        if($data[0]['diagnostic']==$row["DiagCodeId"]){
                                                            print("<option value='".$row["DiagCodeId"]."' selected>".$row["name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                        }
                                                    }else{
                                                        if($data_poc[0]['diagnostic']==$row["DiagCodeId"]){
                                                            print("<option value='".$row["DiagCodeId"]."' selected>".$row["name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                        }
                                                    }
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">DOB:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <?php echo $data[0]['DOB']?>
                                </div>
                                
                                <label class="col-lg-1 withoutPadding">Age:</label>
                                <div class="col-lg-4 withoutPadding" style="font-size:13px">                                    
                                    <?php echo $data[0]['pdob']?>
                                </div>
                            </div>
                           
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Physician:</label>
                                <div class="col-lg-4 withoutPadding">
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo $data[0]['physician']?>
                                </div>
                              
                              </div>
                              <br>  
                              <div class="row"><?=$data[0]['status']?>
                                <label class="col-lg-2 withoutPadding">status:</label>
                                <div class="col-lg-10 withoutPadding">
                                    <select id="status_eval" name="status_eval" style="width: 100px;" class="form-control">
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data_poc[0]['status']==1 || !isset($_GET['id_poc'])){?>selected=""<?php }?>>In progress</option>
                                        <option value='2' <?php if($data_poc[0]['status']==2){?>selected=""<?php }?>>Active</option>                                        
                                        <option value='3' <?php if($data_poc[0]['status']==3){?>selected=""<?php }?>>Inactive</option>
                                    </select>                                                                      
                                </div>
                            </div>
                            
                              <hr>

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
                                                    if(!isset($_GET['id_poc'])){
                                                        if($data[0]['company']==$row["id"]){
                                                            print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["id"]."'>".$row["company_name"]." </option>"); 
                                                        }
                                                    }else{
                                                        
                                                        if($data_poc[0]['Company']==$row["id"]){
                                                            print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["id"]."'>".$row["company_name"]." </option>"); 
                                                        }
                                                    }
                                                }
                                                    
                                            ?>
                                        </select> 
                                    
                                </div>
                                
                                <div class="col-lg-12 withoutPadding"  style="font-size:18px; font-weight:bold;"> <font color='#1e2c51'>                                                                
                                    <?php 
                                        if(!isset($_GET['id_poc'])){
                                    ?>
                                    <?=$data[0]['company_name']?><br>
                                    <?=$data[0]['facility_address']?><br>
                                    <?=$data[0]['facility_city'].",".$data[0]['facility_state'].",".$data[0]['facility_zip']?><br>
                                (P) <?=$data[0]['facility_phone']?><br>
                                (F) <?=$data[0]['facility_fax']?><br>
                                <?php }else{?>
                                    <?=$data_poc[0]['company_name']?><br>
                                    <?=$data_poc[0]['facility_address']?><br>
                                    <?=$data_poc[0]['facility_city'].",".$data[0]['facility_state'].",".$data[0]['facility_zip']?><br>
                                (P) <?=$data_poc[0]['facility_phone']?><br>
                                (F) <?=$data_poc[0]['facility_fax']?><br>
                                <?php } ?>
                                </font>
                                
                                </div>
                            </div>

                          </div>
                        </div>
                          <br>
                            <hr>
                            
                            <div class="row"> 
                                <div class="col-lg-12">
                                    <button type="button" class="btn" onclick="agregar_goals();"><img src="../../../images/agregar.png" width="15" height="15"/>Add Goal Library</button>
                                </div>                                
                            </div>
                            <br>
                            <?php  if(!isset($_GET['id_poc'])){ ?>
                                <input type="hidden" name="discipline_id" id="discipline_id" value="<?=$data[0]['discipline_id']?>">
                             <?php }else{ ?>
                             	<input type="hidden" name="discipline_id" id="discipline_id" value="<?=$data_poc[0]['Discipline']?>">
                            <?php } ?>
                            <div  class="row" id="goaldsPoc"> 
                              
                              

                            </div>
                            <div class="row" id="goaldsPocAddLong"> 
                                <?php
                                if($_GET['id_poc'] != ''){
                                    $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                                            . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                            . " WHERE cg.careplan_id = ".$_GET['id_poc']." AND cg.goal_term_id = 3 ORDER BY goal_area;";
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {
                                        $reporte.='<div class="row" id="div_long_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><img src="../../../images/papelera.png" style="width:20px;height:20px;cursor:pointer;" onclick="eliminarDiv(\'div_long_'.$datos_careplan_goals['goal_library_id'].'\')"></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="long_'.$datos_careplan_goals['goal_library_id'].'" name="long_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="long_area_'.$datos_careplan_goals['goal_library_id'].'" name="long_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-7"><textarea class="form-control" id="long_library_'.$datos_careplan_goals['goal_library_id'].'" name="long_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="long_ach_'.$datos_careplan_goals['goal_library_id'].'" name="long_ach_'.$datos_careplan_goals['goal_library_id'].'">';
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
                                            <div class="col-lg-7 text-center"> Long Term Goal </div>
                                            <div class="col-lg-1 text-center"> Pct Ach </div>
                                        </div>
                                    </div>
                                    <?= $reporte ?>
                                <?php endif; }?>
                            </div>
                            
                            <div class="row" id="goaldsPocAddShort"> 
                                <?php
                                if($_GET['id_poc'] != ''){
                                    $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                                            . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                            . " WHERE cg.careplan_id = ".$_GET['id_poc']." AND cg.goal_term_id = 2 ORDER BY goal_area;";
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {
                                        $reporte.='<div class="row" id="div_short_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><img src="../../../images/papelera.png" style="width:20px;height:20px;cursor:pointer;" onclick="eliminarDiv(\'div_short_'.$datos_careplan_goals['goal_library_id'].'\')"></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="short_'.$datos_careplan_goals['goal_library_id'].'" name="short_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="short_area_'.$datos_careplan_goals['goal_library_id'].'" name="short_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-7"><textarea class="form-control" id="short_library_'.$datos_careplan_goals['goal_library_id'].'" name="short_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="short_ach_'.$datos_careplan_goals['goal_library_id'].'" name="short_ach_'.$datos_careplan_goals['goal_library_id'].'">';
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
                                            <div class="col-lg-7 text-center"> Short Term Goal </div>
                                            <div class="col-lg-1 text-center"> Pct Ach </div>
                                        </div>
                                    </div>
                                    <?= $reporte ?>
                                <?php endif; }?>
                            </div>
                            <div class="row" id="goaldsPocAddNa"> 
                                <?php
                                if($_GET['id_poc'] != ''){
                                    $sql_careplan_goals  = "SELECT *,cg.goal_lib_id as goal_library_id,cg.cpg_area as goal_area ,cg.cpg_text as goal_text FROM careplan_goals cg"
                                            . " LEFT JOIN tbl_goals_terms gt ON gt.goal_term_id = cg.goal_term_id "       
                                            . " WHERE cg.careplan_id = ".$_GET['id_poc']." AND cg.goal_term_id = 1 ORDER BY goal_area;";
                                    
                                    $resultado_careplan_goals = ejecutar($sql_careplan_goals,$conexion);   
                                    $reporte = '';
                                    while($datos_careplan_goals = mysqli_fetch_assoc($resultado_careplan_goals)) {
                                        $reporte.='<div class="row" id="div_na_'.$datos_careplan_goals['goal_library_id'].'">';    
                                        $reporte .= '<div class="col-lg-1 text-center"><img src="../../../images/papelera.png" style="width:20px;height:20px;cursor:pointer;" onclick="eliminarDiv(\'div_na_'.$datos_careplan_goals['goal_library_id'].'\')"></div>';
                                        $reporte .= '<div class="col-lg-1 text-center"><input type="text" class="form-control" value="'.$datos_careplan_goals['goal_library_id'].'" id="na_'.$datos_careplan_goals['goal_library_id'].'" name="na_'.$datos_careplan_goals['goal_library_id'].'" readonly></div>';
                                        $reporte .= '<div class="col-lg-2"><textarea class="form-control" id="na_area_'.$datos_careplan_goals['goal_library_id'].'" name="na_area_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_area']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-7"><textarea class="form-control" id="na_library_'.$datos_careplan_goals['goal_library_id'].'" name="na_library_'.$datos_careplan_goals['goal_library_id'].'">'.rtrim($datos_careplan_goals['goal_text']).'</textarea></div>';
                                        $reporte .= '<div class="col-lg-1"><select class="form-control withoutPadding" id="na_ach_'.$datos_careplan_goals['goal_library_id'].'" name="na_ach_'.$datos_careplan_goals['goal_library_id'].'">';
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
                                            <div class="col-lg-7 text-center"> N/A Term Goal </div>
                                            <div class="col-lg-1 text-center"> Pct Ach </div>
                                        </div>
                                    </div>
                                    <?= $reporte ?>
                                <?php endif; }?>
                            </div>

							
                            <div class="row">
                                
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <input type="radio" name="templateCkEditorPoc" id="templateCkEditorPoc1" value="ckeditor" onclick="mostrarCkEditor('templatePoc','templatePoc');" checked/>&nbsp;CkEditor &nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="templateCkEditorPoc" id="templateCkEditorPoc" value="template" onclick="mostrarTemplate('templatePoc','templatePoc');"/>&nbsp;Template
                                    </div>                                                                    
                                </div>
                                <div class="row" style="margin-top: 15px;display:none;" id="templatePoc">
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">                                        
                                        <select id="template_id_Poc" name="template_id_Poc" onchange="modificarCkeditor(this,'editor_poc',<?=$data[0]['discipline_id']?>,2);">
                                            <option value=''>Select..</option>
                                            <?php
                                                $templates="SELECT * FROM tbl_templates WHERE type_document_id=2 AND discipline_id=".$data[0]['discipline_id'];
                                                $resultados_tem = ejecutar($templates,$conexion);   
                                                while($row_tem = mysqli_fetch_assoc($resultados_tem)) { 

                                            ?>
                                                <option value='<?=$row_tem['id']?>'><?=$row_tem['name']?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>                                                                
                                </div><br>
                                <div id="template_panel_poc" style="display:none">
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse9">Components</a>
                                              </h4>
                                            </div>                                                      
                                            <div id="collapse9" class="panel-collapse collapse in">
                                                <div id="components_id_poc"></div><hr>
                                            </div>
                                        </div>
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse10">Components Select</a>
                                              </h4>
                                            </div>                                                      
                                            <div id="collapse10" class="panel-collapse collapse in">
                                                <div id="newTemplatePoc"></div><hr>
                                            </div>
                                        </div>
                                    </div> 
                                </div>  
                                <div class="row" style="margin-top: 15px;" id="divCkeditorPoc" >
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <?php 
                                            if(!isset($_GET['id_poc'])){
                                        ?>
                                        
                                        <div id="cke_poc"><textarea class="ckeditor" name="editorPoc" id="editorPoc"></textarea></div>
                                        <?php }else{ ?>
                                        <div id="cke_poc"><textarea class="ckeditor form-control" name="editorPoc" id="editorPoc"><?=$data_poc[0]['ckeditor']?></textarea></div>
                                        <?php }?>
                                    </div>                                                                    
                                </div>
                                
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-lg-offset-2 col-lg-2">
                                        <button type="button" class="btn btn-primary" onclick="signature('signaturePoc','firma_poc');" <?php if(!isset($_GET['id_poc'])){ if($_SESSION['user_id']!=$data[0]['id_user']){?>disabled=""<?php }}?> <?php if(isset($_GET['id_poc'])){ if($_SESSION['user_id']!=$data_poc[0]['user_id']){?>disabled=""<?php }}?>><i class="fa fa-check"></i>&nbsp;Signature</button>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php 
                                            if(isset($_GET['id_poc']) && $data_poc[0]['therapist_signed']==1){
                                        ?>
                                            <div id="signaturePoc">
                                                <?php }else{?>
                                                     <div id="signaturePoc" style="display:none">
                                                <?php } ?>
                                                     <?php 
                                                        if(!isset($_GET['id_poc'])){
                                                    ?>
                                                            <img src="../../../images/sign/<?=$data[0]['sign_before']?>" style="width: 100px">
                                                        <?php }elseif($data_poc[0]['therapist_signed']==1){?>
                                                            <img src="../../../images/sign/<?=$data[0]['sign_before']?>" style="width: 100px">
                                                        <?php }else{ ?>
                                                            <img src="../../../images/sign/<?=$data[0]['sign_before']?>" style="width: 100px">
                                                        <?php } ?>
                                                </div>
                                                <input type="hidden" name="firma_poc" id="firma_poc"/>
                                                <input type="hidden" name="ruta_firma_poc" id="ruta_firma_poc"/>
                                                <input type="hidden" name="latitud_firma_poc" id="latitud_firma_poc"/>
                                                <input type="hidden" name="longitud_firma_poc" id="longitud_firma_poc"/>
                                            </div>
                                    </div>
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-4"  id="to_sign_discharge"></div>                                                                
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input type="hidden" name="patient_id" id="patient_id" value="<?=$data[0]['patient_id']?>">
                                    <input type="hidden" name="id_eval" id="id_eval" value="<?=$_GET['id_eval']?>">
                                    <input type="hidden" name="discipline_id" id="discipline_id" value="<?=$data[0]['discipline_id']?>">
                                    <?php 
                                        if(isset($_GET['id_poc'])){
                                    ?>
                                        <input type="hidden" name="id_careplans_edit" id="id_careplans_edit" value="<?=$_GET['id_poc']?>">
                                    <?php } ?>
                                    <?php
                                        $particion=explode(',',$data[0]['patient']);                                       
                                    ?>
                                    <input type="hidden" name="name_patient" id="name_patient" value="<?=$particion[0]?>">
                                    <input type="hidden" name="last_name_patient" id="last_name_patient" value="<?=$particion[1]?>">
                                </div>                         
                                
                            </div>
                            
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Attachment:</label>
                                <div class="col-lg-5">
                                    <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                                </div>
                            </div>
                            <hr/>
                            <div class="row card_footer">                                                                            
                                    <div class="col-sm-offset-4 col-sm-2 mg-top-sm">                                         
<?php if((isset($_GET['id_eval']) && $_SESSION['user_id']==$data[0]['id_user']) || (isset($_GET['id_poc']) && $_SESSION['user_id']==$data_poc[0]['user_id'])) {?> 
                                        
                                        <button type="submit" class="btn btn-success" <?php if(isset($_GET['id_poc']) && $data_poc[0]['therapist_signed']==1){?>disabled=""<?php }?>><?=$button?></button>
                                        <?php } ?>

                                    </div>                                                                            
                                    <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                                        <button type="button" class="btn btn-danger" onclick="cancelar(<?=$data[0]['patient_id']?>,<?=$data[0]['discipline_id']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                                    </div>
                                    <a href="pdf/poc.php?id=<?=$_GET['id_poc']?>" class="btn btn-default" target="_blank" style="cursor: pointer;" class="ruta"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>