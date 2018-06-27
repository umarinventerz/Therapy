
    <div class="modal fade" id="modalAddSummary" name = "modalAddSummary" tabindex="-1" role="dialog" aria-labelledby="modalAddSummary">
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
                            
    <form id="formSummary" onsubmit="return validar_form_summary();" enctype="multipart/form-data">

 <div>  


    <div  class="col-lg-9">

     <div class="row">
        <label class="col-lg-1 withoutPadding">Patients:</label>
        <div class="col-lg-5">
            <input type="hidden" name="evaluation_id_summary_hidden" id="evaluation_id_summary_hidden">
            <input type="hidden" name="id_summary_hidden" id="id_summary_hidden">
            <input type="hidden" name="patients_id_summary_hidden" id="patients_id_summary_hidden">
            <input type="hidden" name="patients_summary_hidden" id="patients_summary_hidden">
            <input type="hidden" name="discipline_id_summary_hidden" id="discipline_id_summary_hidden">
            <input type="hidden" name="diagnostic_summary_hidden" id="diagnostic_summary_hidden">
            <input type="hidden" name="company_id_summary_hidden" id="company_id_summary_hidden">   
            <input type="hidden" name="template_summary" id="template_summary" value="">   
            <div id="patients_name_summary_modal"></div>
        </div>                         
        <label class="col-lg-1 withoutPadding">Pat ID:</label>
        <div class="col-lg-5 withoutPadding">
            <div id="pat_id_summary_modal"></div>
        </div>
       
    </div>
    <br>

    <div class="row">
        <label class="col-lg-2 withoutPadding">Date of Summary:</label>
        <div class="col-lg-4 withoutPadding">
            <input type="text" name="date_of_summary" id="date_of_summary" class="form-control" style="width: 180px;" placeholder="Date of summary" readonly="readonly"/>                                    
        </div>
        <label class="col-lg-1 withoutPadding">DOB/Age:</label>
        <div class="col-lg-5">
            <div id="pdob_summary_modal"></div>
        </div>

    </div>

    <br>
    <div class="row">
        <label class="col-lg-2 withoutPadding">Visit date:</label>
        <div class="col-lg-4 withoutPadding">
            <input type="text" name="from_summary" id="from_summary" class="form-control" style="width: 180px;" placeholder="From"/>
        </div>
        <label class="col-lg-1 withoutPadding">Eval due:</label>
        <div class="col-lg-5 withoutPadding">
            <input type="text" name="to_summary" id="to_summary" class="form-control" style="width: 180px;" placeholder="To"/>
        </div>
    </div>
    <br>  
    <div class="row">
        <label class="col-lg-1 withoutPadding">Discipline:</label>
        <div class="col-lg-5">                                    
            <div id="discipline_summary_modal"></div>
        </div>
        <label class="col-lg-1 withoutPadding">Diagnostic:</label>
        <div class="col-lg-5">                                    
            <div id="diagnostic_name_summary_modal"></div>
        </div>
    </div>
    <br>
    <div class="row">

        <label class="col-lg-2 withoutPadding">Therapist/NPI:</label>
        <div class="col-lg-4 withoutPadding">
            <div id="npi_summary_modal">
            <?=$therapist;?> &nbsp;/ &nbsp; <?=$npi?>
            </div>
        </div>

        <label class="col-lg-2 withoutPadding">Ref. Provider/NPI:</label>
        <div class="col-lg-4 withoutPadding">
           <div id="pcp_summary_modal">
                
            </div>
        </div>
    </div>
    </div>
    
    <br>
   


</div>




    
        <div class="col-lg-3">

                <div class="col-lg-12" > <font color='#1e2c51'>

            
                                <div style="font-size:18px;font-weight:bold;" class="col-lg-12 withoutPadding">                                    
                                    <div id="company_summary_name"></div>
                                </div>
                                

                                 <div style="font-size:17px;" class="col-lg-12 withoutPadding">                                    
                                    <div id="company_summary_address"></div>
                                </div>
                                
                                 <div style="font-size:17px;"  class="col-lg-12 withoutPadding">                                    
                                    <div id="company_summary_city"></div>
                                </div>

                                 <div style="font-size:17px;"  class="col-lg-12 withoutPadding">                                    
                                    <div id="company_summary_phone"></div>
                                </div>

                                 <div style="font-size:17px;" class="col-lg-12 withoutPadding">                                    
                                    <div id="company_summary_fax"></div>
                                </div>

                            
                </div>

        </div>


    <hr><br><br><br>

                            
    <div id="data_goals"> 

    </div>


    <hr/>
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-12"></div>
        <div class="col-lg-12">
            <input type="radio" name="CkEditorSummary" id="CkEditorSummary1" value="ckeditor" onclick="mostrarCkEditor('templateSummary','editorSummary','');" checked/>&nbsp;CkEditor &nbsp;&nbsp;&nbsp;
            <input type="radio" name="CkEditorSummary" id="CkEditorSummary" value="template" onclick="mostrarTemplate('templateSummary','editorSummary');"/>&nbsp;Template
        </div>                                                                    
    </div>    
    <div class="row" style="margin-top: 15px;display:none;" id="templateSummary">
        <div id="mostrar_select"></div>                                                                       
    </div><br>
    <div id="template_panel_summary" style="display:none">
        <div class="panel-group" id="accordion">
            <div class="panel panel-primary">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse12">Components</a>
                  </h4>
                </div>                                                      
                <div id="collapse12" class="panel-collapse collapse in">
                    <div id="components_id_summary"></div><hr>
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse13">Components Select</a>
                  </h4>
                </div>                                                      
                <div id="collapse13" class="panel-collapse collapse in">
                    <div id="newTemplateSummary"></div><hr>
                </div>
            </div>
        </div> 
    </div>
    <div class="row" style="margin-top: 15px;" id="divCkeditorSummary" >
        <div class="col-lg-12"></div>
        <div class="col-lg-12">
            <textarea class="ckeditor" name="editorSummary" id="editorSummary"></textarea>
        </div>                                                                    
    </div>
    
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-offset-2 col-lg-2">
            <button type="button" class="btn btn-primary" onclick="signature('signatureSummary');"><i class="fa fa-check"></i>&nbsp;Signature</button>
        </div>
        <div class="col-lg-3">
            <div id="signatureSummary" style="display:none">
                <img src="../../../images/sign/<?=$signature?>" style="width: 100px">
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-2"></div>
        <div class="col-lg-4"  id="to_sign_summary"></div>                                                                
    </div>
  
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">MD Signed:</label>
        <div class="col-lg-1">
            <input type="checkbox" value="1" name="md_signed_summary" id="md_signed_summary" onclick="mdSignature(this,'date_signed_summary','attachment_summary')"/>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;display:none;" id="date_signed_summary">
        <label class="col-lg-2">Date of Signed:</label>
        <div class="col-lg-5">
            <input type="text" name="date_of_signed_summary" id="date_of_signed_summary" class="form-control" placeholder="Date of Signed"/>                                    
        </div>
    </div>  
    <div class="row" style="margin-top: 15px;display:none;" id="current_document_summary">
        <label class="col-lg-2">Document:</label>
        <div class="col-lg-10" id="route_document_summary">

        </div>
    </div>
    <div class="row" style="margin-top: 15px;display:none;" id="attachment_summary" >
        <label class="col-lg-2">Attachment:</label>
        <div class="col-lg-5">
            <input name="file-1[]" id="fileSummary" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
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