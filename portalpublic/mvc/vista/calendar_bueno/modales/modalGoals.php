<div class="modal fade" id="modalGoals" name = "modalGoals" tabindex="-1" role="dialog" aria-labelledby="modalEditDocument" data-backdrop="static">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">                        
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
                    </div>
                </div>

                <div class="modal-header bg-card-header">
                    <h4 class="modal-title"><div id="title"><?php if(!isset($_GET['modificar_cpt'])){?>Add Goals<?php }else{?><?php echo "Edit Goals #".$_GET['id'];}?></div></h4>
                </div> 
                </div>

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <div id="content_modal">
                          <div class="row">                                
                                <label class="col-lg-1 withoutPadding"> Goals:</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" id="goal_text" name="goal_text"></textarea>
                                </div>
                                                              
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-1">Discipline:</label>  
                                <div class="col-lg-4">
                                    <?php 
                                        $select_discipline = 'SELECT * FROM discipline;';
                                    ?>
                                    <select id="goal_discipline_id" name="goal_discipline_id" style="width: 100px;" class="form-control">
                                        <option value=''>Select..</option>
                                        <?php 
                                            $resultado_discipline = ejecutar($select_discipline,$conexion);
                                            while ($row=mysqli_fetch_array($resultado_discipline)) 
                                            {                                                    
                                                print("<option value='".$row["DisciplineId"]."'>".$row["Name"]." </option>"); 

                                            }
                                        ?>
                                    </select>   
                                </div>
                                
                                <label class="col-lg-2">Goal Area:</label>  
                                <div class="col-lg-4">
                                    <textarea class="form-control" id="goal_area" name="goal_area"></textarea>  
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding"> Goal Term:</label>
                                <div class="col-lg-4">
                                    <select name='goal_term' id='goal_term' class="form-control">    
                                            <option value=''>Select..</option>
                                            <option value='1'>N/A</option>
                                            <option value='2'>Short</option>
                                            <option value='3'>Long</option>
                                    </select>
                                </div>
                                
                             </div>                                                       
                     
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-offset-4 col-sm-2 mg-top-sm">                                        
                            <button type="submit" class="btn btn-success" onclick="guardar_goal(<?=$_GET['id_poc']?>,<?=$_GET['documentDiscipline']?>);">Save</button>
                        </div>                                                                            
                        <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                            <button type="button" class="btn btn-danger" onclick="cancelar_goal(<?=$_GET['id_poc']?>,<?=$_GET['documentDiscipline']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>