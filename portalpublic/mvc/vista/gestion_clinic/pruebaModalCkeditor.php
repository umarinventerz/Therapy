<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<script src="ckeditor/ckeditor.js"></script>

<script>
function validar_form_eval(variable){
        
        var campos_formulario = $("#formEvaluation").serialize();
        var data = new FormData();    
        data.append('campos_formulario',campos_formulario); 
                     
        $.ajax({
            url: "../../controlador/evaluations/edit_evaluations.php?flag="+$("#flag").val(),
            type: "POST",
            async:false,
            dataType: "json",
            data: data,  
            processData: false,
            contentType: false, 

            success : function(data){                              
                $('#xxxx').trigger('click');
                $("#modalAddEvaluation").modal('hide');                               
            }
        });
            
        
        return false;
      }
      
</script>
<input id="flag" value="1" name="flag" type="text">
<button type="button" data-toggle="modal" data-target="#modalAddBrand">Launch modal</button>
<div class="modal fade" id="modalAddBrand" tabindex="-1" role="dialog" aria-labelledby="modalAddBrandLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="modalAddBrandLabel">add</h4>

            </div>
            <form id="formEvaluation" onsubmit="return validar_form_eval('edit');" enctype="multipart/form-data">
                <div class="modal-body">
                        <textarea name="editor11" id="editor11" rows="10" cols="80">This is my textarea to be replaced with CKEditor.</textarea>
                </div>
                <div class="modal-footer">           
                    <button type="submit" class="btn btn-success" id="xxxx">Edit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace('editor11');
    
</script>
