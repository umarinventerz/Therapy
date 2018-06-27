<div class="modal fade" id="modalAddDischarge" name = "modalAddDischarge" tabindex="-1" role="dialog" aria-labelledby="modalAddDischarge">
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
                        <div class="col-md-offset-2 col-md-3" >
                            <h4 class="modal-title" style="margin-top: 45px;color: #FFF;"><div id="title_add"></div></h4>
                        </div>
                        
                    </div>
                </div>
                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <div id="content_modal_add">
                            
    <form id="formDischarge" onsubmit="return validar_form_discharge();" enctype="multipart/form-data">
    <div class="row">
        <label class="col-lg-1 withoutPadding">Patients:</label>
        <div class="col-lg-3">
            <input type="hidden" name="evaluation_id_discharge_hidden" id="evaluation_id_discharge_hidden">
            <input type="hidden" name="patients_id_discharge_hidden" id="patients_id_discharge_hidden">
            <input type="hidden" name="id_discharge_hidden" id="id_discharge_hidden">
            <input type="hidden" name="patients_discharge_hidden" id="patients_discharge_hidden">
            <input type="hidden" name="discipline_id_discharge_hidden" id="discipline_id_discharge_hidden">
            <input type="hidden" name="diagnostic_discharge_hidden" id="diagnostic_discharge_hidden">
            <input type="hidden" name="company_id_discharge_hidden" id="company_id_discharge_hidden">
             <input type="hidden" name="template_discharge" id="template_discharge" value="">  
            <div id="patients_name_discharge_modal"></div>
        </div>                         
        <label class="col-lg-1 withoutPadding">Pat ID:</label>
        <div class="col-lg-2 withoutPadding">
            <div id="pat_id_discharge_modal"></div>
        </div>
        <label class="col-lg-1 withoutPadding">DOB:</label>
        <div class="col-lg-2 withoutPadding" id="patients_dob_discharge_modal"></div>
        
        <label class="col-lg-1 withoutPadding">Phone:</label>
        <div class="col-lg-1 withoutPadding">                                    
            <div id="phone_discharge_modal"></div>
        </div>
    </div>
    <div class="row">
        <label class="col-lg-1 withoutPadding">Visit date:</label>
        <div class="col-lg-3 withoutPadding">
            <input type="text" name="from_discharge" id="from_discharge" class="form-control" style="width: 180px;" placeholder="From"/>
        </div>
        <label class="col-lg-1 withoutPadding">Eval due:</label>
        <div class="col-lg-3 withoutPadding">
            <input type="text" name="to_discharge" id="to_discharge" class="form-control" style="width: 180px;" placeholder="To"/>
        </div>                
    </div>  
    <div class="row">
        <label class="col-lg-1 withoutPadding">Discipline:</label>
        <div class="col-lg-3 withoutPadding">                                    
            <div id="discipline_discharge_modal"></div>
        </div>
        <label class="col-lg-1 withoutPadding">Diagnostic:</label>
        <div class="col-lg-4 withoutPadding">                                    
            <div id="diagnostic_discharge_modal"></div>
        </div>
    </div>
    <div class="row">

        <label class="col-lg-1 withoutPadding">Therapist:</label>
        <div class="col-lg-3 withoutPadding">
            <?=$therapist;?>
        </div>
        <label class="col-lg-1 withoutPadding">NPI:</label>
        <div class="col-lg-2 withoutPadding">
            <div id="npi_discharge_modal">
                <?=$npi?>
            </div>
        </div>
    </div>
        <div class="row">

        <label class="col-lg-1 withoutPadding">Doctor:</label>
        <div class="col-lg-3 withoutPadding">
            <div id="doctor_discharge_modal">
                
            </div>
        </div>
        <label class="col-lg-1 withoutPadding">NPI:</label>
        <div class="col-lg-2 withoutPadding">
            <div id="doctor_npi_discharge_modal">
                
            </div>
        </div>
    </div>
    <div class="row">
        <label class="col-lg-1 withoutPadding">Company:</label>
        <div class="col-lg-1 withoutPadding">                                    
            <div id="company_discharge_modal"></div>
        </div>
        <label class="col-lg-1 withoutPadding">Address:</label>
        <div class="col-lg-2 withoutPadding">                                    
            <div id="company_address_discharge_modal"></div>
        </div>
        <label class="col-lg-1 withoutPadding">Phone:</label>
        <div class="col-lg-1 withoutPadding">                                    
            <div id="company_phone_discharge_modal"></div>
        </div>
    </div>
    <div class="row">
        <label class="col-lg-1 withoutPadding">Date of Discharge:</label>
        <div class="col-lg-3 withoutPadding">
            <input type="text" name="date_of_discharge" id="date_of_discharge" value="<?=date("Y-m-d");?>" class="form-control" style="width: 180px;" placeholder="Date of discharge" readonly="readonly"/>                                    
        </div>
    </div>
    
    <hr>
                            
    <div id="data_discharge_goals"> 

    </div>
    
    <hr/>
    <div class="row" style="margin-top: 15px;">
        
        <div class="col-lg-12">
            <input type="radio" name="templateCkEditorDischarge" id="templateCkEditorDischarge1" value="ckeditor" onclick="mostrarCkEditor('divCkeditorDischarge','templateDischarge');" checked/>&nbsp;CkEditor &nbsp;&nbsp;&nbsp;
            <input type="radio" name="templateCkEditorDischarge" id="templateCkEditorDischarge" value="template" onclick="mostrarTemplate('templateDischarge','templateDischarge');"/>&nbsp;Template
        </div>                                                                    
    </div>    
    <div class="row" style="margin-top: 15px;display:none;" id="templateDischarge">
        <div id="mostrar_select_discharge"></div>                                                                       
    </div><br>
    <div id="template_panel_discharge" style="display:none">
        <div class="panel-group" id="accordion">
            <div class="panel panel-primary">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse14">Components</a>
                  </h4>
                </div>                                                      
                <div id="collapse14" class="panel-collapse collapse in">
                    <div id="components_id_discharge"></div><hr>
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse15">Components Select</a>
                  </h4>
                </div>                                                      
                <div id="collapse15" class="panel-collapse collapse in">
                    <div id="newTemplateDischarge"></div><hr>
                </div>
            </div>
        </div> 
    </div>
    <div class="row" style="margin-top: 15px;" id="divCkeditorDischarge" >                
        <div class="col-lg-12">
            <textarea class="ckeditor" name="editorDischarge" id="editorDischarge"></textarea>
        </div>                                                                    
    </div>
    
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-offset-2 col-lg-2">
            <button type="button" class="btn btn-primary" onclick="signature('signatureDischarge','signatureDischargeStatus');"><i class="fa fa-check"></i>&nbsp;Signature</button>
            <input type="hidden" name="signatureDischargeStatus" id="signatureDischargeStatus" value="0">
            
        </div>
        <div class="col-lg-3">
            <div id="signatureDischarge" style="display:none">
                <img src="../../../images/sign/<?=$signature?>" style="width: 100px">
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-2"></div>
        <div class="col-lg-4"  id="to_sign_discharge"></div>                                                                
    </div>
    <div class="row" style="margin-top: 15px;">    
        <label class="col-lg-2">Document:</label>
        <div class="col-lg-5"  id="document_route"></div>                                                                
    </div>
    <div class="row" style="margin-top: 15px;" id="attachment_discharge" >
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
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
