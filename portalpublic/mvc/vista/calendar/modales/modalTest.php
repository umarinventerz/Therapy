<button type="button" data-toggle="modal" data-target="#modalAddBrand">Launch modal</button>
<div class="modal fade" id="modalAddBrand" tabindex="-1" role="dialog" aria-labelledby="modalAddBrandLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="modalAddBrandLabel">add</h4>

            </div>
            <form id="formEvaluation1" onsubmit="return validar_form_eval('edit');" enctype="multipart/form-data">            
                <div class="row" style="margin-top: 15px;" id="attachment" >
                    <label class="col-lg-2">Attachment:</label>
                    <div class="col-lg-5">
                        <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                    </div>
                </div>
                <div class="row card_footer">                                                                            
                        <div class="col-sm-offset-4 col-sm-2 mg-top-sm">                                        
                            <button type="submit" class="btn btn-success"><?php if(isset($_GET['id_eval'])){echo "Edit";}else{echo "Save";}?></button>
                        </div>                                                                            
                        <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                            <button type="button" class="btn btn-danger" onclick="cancelar(<?=$data[0]['patient_id']?>,<?=$data[0]['discipline_id']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                        </div>
                        <a href="pdf/evaluation.php?id=<?=$_GET['id_eval']?>" class="btn btn-default" target="_blank" style="cursor: pointer;" class="ruta"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
                </div>
            </form>
        </div>
    </div>
</div>
