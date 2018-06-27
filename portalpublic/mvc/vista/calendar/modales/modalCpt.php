<div class="modal fade" id="modalCpt" name = "modalCpt" tabindex="-1" role="dialog" aria-labelledby="modalEditDocument" data-backdrop="static">
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
                    <h4 class="modal-title"><div id="title"><?php if(!isset($_GET['modificar_cpt'])){?>Añadir Cpt<?php }else{?><?php echo "Edit cpt #".$_GET['id'];}?></div></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                <?php if(!isset($_GET['modificar_cpt'])){?>
                <div class="modal-body">
                    <div class="panel-body">  
                        <div id="content_modal">
                          <div class="row">                                
                                <label class="col-lg-1 withoutPadding"> Cpt:</label>
                                <div class="col-lg-4">
                                    <select name='cpt_note' id='cpt_note' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from cpt where type='TX' AND DisciplineId=".$note[0]['discipline_id'];

                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {
                                                    
                                                    print("<option value='".$row["ID"]."'>".$row["cpt"]." </option>"); 
                                                    
                                                }

                                            ?>
                                    </select>
                                </div>
                              
                                <label class="col-lg-1">Units:</label>  
                                <div class="col-lg-4">
                                    <select id="unid_note" name="unid_note" style="width: 100px;">
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data[0]['units']==1){?>selected=""<?php }?>>1</option>
                                        <option value='2' <?php if($data[0]['units']==2){?>selected=""<?php }?>>2</option>
                                        <option value='3' <?php if($data[0]['units']==3){?>selected=""<?php }?>>3</option>
                                        <option value='4' <?php if($data[0]['units']==4){?>selected=""<?php }?>>4</option>
                                    </select>   
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding"> Location:</label>
                                <div class="col-lg-4">
                                    <select name='location_note' id='location_note' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from tbl_location_appoiments";

                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {                                                    
                                                    print("<option value='".$row["id"]."'>".$row["name"]." </option>"); 
                                                    
                                                }

                                            ?>
                                    </select>
                                </div>
                                <label class="col-lg-1">Duration:</label>
                                <div class="col-lg-4">
                                    <select name="duration_note" id="duration_note" class="form-control">
                                    
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                        <option value="60">60</option>
                                    </select>                                    
                                </div>
                             </div>                                                       
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-sm-1">From:</label>
                                <div class="col-sm-5">
                                    <!--<input type="text" name="start_note" id="start_note" class="form-control" placeholder="start"/>-->
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_hour" id="start_note_hour" class="form-control">
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_minute" id="start_note_minute" class="form-control">
                                        <option value="00">00</option>
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                        <option value="60">60</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_type" id="start_note_type" class="form-control">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>                                        
                                    </select>
                                    </font>
                                    </div>
                                </div>
                                </div>
                                 <div class="row">
                                <label class="col-lg-1">To:</label>
                                <div class="col-sm-5">
                                    <!--<input type="text" name="to_note" id="to_note" class="form-control" placeholder="End"/>-->
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_hour" id="to_note_hour" class="form-control">
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_minute" id="to_note_minute" class="form-control">
                                        <option value="00">00</option>
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                        <option value="60">60</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_type" id="to_note_type" class="form-control">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>                                        
                                    </select>
                                    </font>
                                    </div>
                                </div>
                                
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Diag.:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select name='diagnostic_id_note' id='diagnostic_id_note' class="">                                                				
                                            <?php
                                                $sql_diagnostic  = "Select DiagCodeId,concat(DiagCodeValue,', ',DiagCodeDescrip) as name from diagnosiscodes where TreatDiscipId=".$note[0]['discipline_id'];
                                                
                                                $resultado_diagnostic = ejecutar($sql_diagnostic,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_diagnostic)) 
                                                {                                                    
                                                    print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-offset-4 col-sm-2 mg-top-sm">                                        
                            <button type="submit" class="btn btn-success" onclick="guardar_cpt(<?=$_GET['id_note']?>);">Save</button>
                        </div>                                                                            
                        <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                            <button type="button" class="btn btn-danger" onclick="cancelar_note(<?=$_GET['id_note']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                        </div>
                    </div>
                </div>
                <?php
                }else{
                    $sql_cpt_edit="SELECT * FROM tbl_note_cpt_relation WHERE id_note_cpt_relation=".$_GET['id'];
                    $resultados_edit_cpt = ejecutar($sql_cpt_edit,$conexion);   
                    while($row_edit_cpt = mysqli_fetch_assoc($resultados_edit_cpt)) { 

                        $data_edit_cpt[]=$row_edit_cpt;

                    }
                    //para start
                    $variable_start=explode(":",$data_edit_cpt[0]['start']);
                    $start_hour=$variable_start[0];
                    $start_minute=$variable_start[1];
                    $start_type=$variable_start[2];
                    //para end
                    $variable_end=explode(":",$data_edit_cpt[0]['end']);
                    $end_hour=$variable_end[0];
                    $end_minute=$variable_end[1];
                    $end_type=$variable_end[2];
                    
                    
                ?>
                <div class="modal-body">
                    <div class="panel-body">  
                        <div id="content_modal">
                          <div class="row">                                
                                <label class="col-lg-1 withoutPadding"> Cpt:</label>
                                <div class="col-lg-4">
                                    <select name='cpt_note_edit' id='cpt_note_edit' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from cpt where type='TX' AND DisciplineId=".$note[0]['discipline_id'];

                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {
                                                    if($data_edit_cpt[0]['id_cpt']==$row["ID"]){
                                                        print("<option value='".$row["ID"]."' selected>".$row["cpt"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["ID"]."'>".$row["cpt"]." </option>"); 
                                                    }
                                                }

                                            ?>
                                    </select>
                                </div>
                              
                                <label class="col-lg-1">Units:</label>  
                                <div class="col-lg-4">
                                    <select id="unid_note_edit" name="unid_note_edit" style="width: 100px;">
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data_edit_cpt[0]['units']==1){?>selected=""<?php }?>>1</option>
                                        <option value='2' <?php if($data_edit_cpt[0]['units']==2){?>selected=""<?php }?>>2</option>
                                        <option value='3' <?php if($data_edit_cpt[0]['units']==3){?>selected=""<?php }?>>3</option>
                                        <option value='4' <?php if($data_edit_cpt[0]['units']==4){?>selected=""<?php }?>>4</option>
                                    </select>   
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding"> Location:</label>
                                <div class="col-lg-4">
                                    <select name='location_note_edit' id='location_note_edit' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from tbl_location_appoiments";

                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {      
                                                    if($data_edit_cpt[0]['location']==$row["id"]){
                                                        print("<option value='".$row["id"]."' selected>".$row["name"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["id"]."'>".$row["name"]." </option>"); 
                                                    }
                                                }

                                            ?>
                                    </select>
                                </div>
                                <label class="col-lg-1">Duration:</label>
                                <div class="col-lg-4">
                                    <select name="duration_note_edit" id="duration_note_edit" class="form-control">
                                    
                                        <option value="15" <?php if($data_edit_cpt[0]['duration']==15){?>selected=""<?php }?>>15</option>
                                        <option value="30" <?php if($data_edit_cpt[0]['duration']==30){?>selected=""<?php }?>>30</option>
                                        <option value="45" <?php if($data_edit_cpt[0]['duration']==45){?>selected=""<?php }?>>45</option>
                                        <option value="60" <?php if($data_edit_cpt[0]['duration']==60){?>selected=""<?php }?>>60</option>
                                    </select>                                    
                                </div>
                             </div>                                                       
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-sm-1">From:</label>
                                <div class="col-sm-5">
                                    <!--<input type="text" name="start_note" id="start_note" class="form-control" placeholder="start"/>-->
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_hour_edit" id="start_note_hour_edit" class="form-control">
                                        <option value="01" <?php if($start_hour==01){?>selected=""<?php } ?>>01</option>
                                        <option value="02" <?php if($start_hour==02){?>selected=""<?php } ?>>02</option>
                                        <option value="03" <?php if($start_hour==03){?>selected=""<?php } ?>>03</option>
                                        <option value="04" <?php if($start_hour==04){?>selected=""<?php } ?>>04</option>
                                        <option value="05" <?php if($start_hour==05){?>selected=""<?php } ?>>05</option>
                                        <option value="06" <?php if($start_hour==06){?>selected=""<?php } ?>>06</option>
                                        <option value="07" <?php if($start_hour==07){?>selected=""<?php } ?>>07</option>
                                        <option value="08" <?php if($start_hour==08){?>selected=""<?php } ?>>08</option>
                                        <option value="09" <?php if($start_hour==09){?>selected=""<?php } ?>>09</option>
                                        <option value="10" <?php if($start_hour==10){?>selected=""<?php } ?>>10</option>
                                        <option value="11" <?php if($start_hour==11){?>selected=""<?php } ?>>11</option>
                                        <option value="12" <?php if($start_hour==12){?>selected=""<?php } ?>>12</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_minute_edit" id="start_note_minute_edit" class="form-control">
                                        <option value="00" <?php if($start_minute==00){?>selected=""<?php } ?>>00</option>
                                        <option value="15" <?php if($start_minute==15){?>selected=""<?php } ?>>15</option>
                                        <option value="30" <?php if($start_minute==30){?>selected=""<?php } ?>>30</option>
                                        <option value="45" <?php if($start_minute==45){?>selected=""<?php } ?>>45</option>
                                        <option value="60" <?php if($start_minute==60){?>selected=""<?php } ?>>60</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_type_edit" id="start_note_type_edit" class="form-control">
                                        <option value="AM" <?php if($start_type=='AM'){?>selected=""<?php } ?>>AM</option>
                                        <option value="PM" <?php if($start_type=='PM'){?>selected=""<?php } ?>>PM</option>                                        
                                    </select>
                                    </font>
                                    </div>
                                </div>
                                </div>
                                 <div class="row">
                                <label class="col-lg-1">To:</label>
                                <div class="col-sm-5">
                                    <!--<input type="text" name="to_note" id="to_note" class="form-control" placeholder="End"/>-->
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_hour_edit" id="to_note_hour_edit" class="form-control">
                                        <option value="01" <?php if($end_hour==01){?>selected=""<?php } ?>>01</option>
                                        <option value="02" <?php if($end_hour==02){?>selected=""<?php } ?>>02</option>
                                        <option value="03" <?php if($end_hour==03){?>selected=""<?php } ?>>03</option>
                                        <option value="04" <?php if($end_hour==04){?>selected=""<?php } ?>>04</option>
                                        <option value="05" <?php if($end_hour==05){?>selected=""<?php } ?>>05</option>
                                        <option value="06" <?php if($end_hour==06){?>selected=""<?php } ?>>06</option>
                                        <option value="07" <?php if($end_hour==07){?>selected=""<?php } ?>>07</option>
                                        <option value="08" <?php if($end_hour==08){?>selected=""<?php } ?>>08</option>
                                        <option value="09" <?php if($end_hour==09){?>selected=""<?php } ?>>09</option>
                                        <option value="10" <?php if($end_hour==10){?>selected=""<?php } ?>>10</option>
                                        <option value="11" <?php if($end_hour==11){?>selected=""<?php } ?>>11</option>
                                        <option value="12" <?php if($end_hour==12){?>selected=""<?php } ?>>12</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_minute_edit" id="to_note_minute_edit" class="form-control">
                                        <option value="00" <?php if($end_minute==00){?>selected=""<?php } ?>>00</option>
                                        <option value="15" <?php if($end_minute==15){?>selected=""<?php } ?>>15</option>
                                        <option value="30" <?php if($end_minute==30){?>selected=""<?php } ?>>30</option>
                                        <option value="45" <?php if($end_minute==45){?>selected=""<?php } ?>>45</option>
                                        <option value="60" <?php if($end_minute==60){?>selected=""<?php } ?>>60</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_type_edit" id="to_note_type_edit" class="form-control">
                                        <option value="AM" <?php if($end_type=='AM'){?>selected=""<?php } ?>>AM</option>
                                        <option value="PM" <?php if($end_type=='PM'){?>selected=""<?php } ?>>PM</option>                                        
                                    </select>
                                    </font>
                                    </div>
                                </div>
                                
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Diag.:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select name='diagnostic_id_note_edit' id='diagnostic_id_note_edit' class="">                                                				
                                            <?php
                                                $sql_diagnostic  = "Select DiagCodeId,concat(DiagCodeValue,', ',DiagCodeDescrip) as name from diagnosiscodes where TreatDiscipId=".$note[0]['discipline_id'];
                                                
                                                $resultado_diagnostic = ejecutar($sql_diagnostic,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_diagnostic)) 
                                                {          
                                                    if($data_edit_cpt[0]['id_diagnosis']==$row["DiagCodeId"]){
                                                        print("<option value='".$row["DiagCodeId"]."' selected>".$row["name"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                    }
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-offset-4 col-sm-2 mg-top-sm">                                        
                            <button type="submit" class="btn btn-success" onclick="edit_cpt(<?=$_GET['id_note']?>,<?=$_GET['id']?>);">Update</button>
                        </div>                                                                            
                        <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                            <button type="button" class="btn btn-danger" onclick="cancelar_note(<?=$_GET['id_note']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>