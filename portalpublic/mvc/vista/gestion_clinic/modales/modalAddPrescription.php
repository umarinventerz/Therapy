<div class="modal fade" id="modalAddPrescription" name = "modalAddPrescription" tabindex="-1" role="dialog" aria-labelledby="modalAddPrescription">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
                    <h4 class="modal-title"><?='New prescription'?></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <form id="formPrescription" onsubmit="return validar_form();" enctype="multipart/form-data">
                            <div class="row">
                                <label class="col-lg-2">Patients:</label>
                                <div class="col-lg-3">
                                    <input type="hidden" id="patients_id_hidden" name="patients_id">                                    
                                    <input type="hidden" name="discipline_id_hidden" id="discipline_id_hidden">
                                    <div id="patients_name_modal"></div>
                                </div>                         
                                <label class="col-lg-1">Discipline:</label>
                                <div class="col-lg-4">                                    
                                    <div id="discipline_modal"></div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Company:</label>
                                <div class="col-lg-5">
                                    <select id="companies_id" name="companies_id">
                                        <option value=''>Select..</option>

                                        <?php
                                        $sql  = "Select company_name  from companies  ";
                                        $conexion = conectar();
                                        $resultado = ejecutar($sql,$conexion);
                                        while ($row=mysqli_fetch_array($resultado))
                                        {

                                            print("<option value='".$row["company_name"]."'>".$row["company_name"]." </option>");
                                        }

                                        ?>




                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Diagnostic:</label>
                                <div class="col-lg-3">
                                    <select id="diagnostic_id" name="diagnostic_id"><option value=''>Select..</option>


                                        <?php
                                        $sql  = "Select DiagCodeId,DiagCodeDescrip,DiagCodeValue  from diagnosiscodes  ";
                                        $conexion = conectar();
                                        $resultado = ejecutar($sql,$conexion);
                                        while ($row=mysqli_fetch_array($resultado))
                                        {
                                            print("<option value='".$row["DiagCodeValue"]."'>".$row["DiagCodeDescrip"]." </option>");
                                        }

                                        ?>



                                    </select>

                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">From:</label>
                                <div class="col-lg-5">
                                    <input type="text" name="Issue_date" id="from_hidden" class="form-control" placeholder="From"/>
                                </div>
                            </div>                            
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">To:</label>
                                <div class="col-lg-5">
                                    <input type="text" name="End_date" id="to_hidden" class="form-control" placeholder="To"/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Physician Name:</label>
                                <div class="col-lg-5">
                                    <select id="physician_id" name="physician_id"><option value=''>Select..</option>

                                        <?php
                                        $sql  = "Select Name,NPI  from physician  ";
                                        $conexion = conectar();
                                        $resultado = ejecutar($sql,$conexion);
                                        while ($row=mysqli_fetch_array($resultado))
                                        {

                                            print("<option value='".$row["NPI"]."'>".$row["Name"]." </option>");
                                        }

                                        ?>
                                    </select>



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
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>                                                                            
                                    <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                                    </div>						
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

