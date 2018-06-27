<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}
$conexion = conectar();

$sql  = "SELECT *,n.date_signed as date_signed_note,n.signed as signed_note,n.discipline as discipline_note FROM tbl_notes_documentation n "
        . " LEFT JOIN careplans c ON c.id_careplans = n.id_careplans"
        . " LEFT JOIN tbl_evaluations e ON e.id = c.evaluations_id"
        . " LEFT JOIN prescription p ON p.id_prescription = e.id_prescription"
        . " LEFT JOIN tbl_documents d ON d.id_table_relation = n.id_notes "
        . " WHERE n.id_notes = ".$_GET['id_document'].";"; 
$resultado = ejecutar($sql,$conexion);   
$reporte = [];
$i = 1;      
while($datos = mysqli_fetch_assoc($resultado)) {
    $reporte = $datos;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
<link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<script src="../../../js/fileinput.js" type="text/javascript"></script>
<script>
function validar_form_note_edit(){
    $('.error-message').hide('slow');
    $('.error-message').html('');
    var nombres_campos = '';
    var error = 0;
    if($("#snote_edit").val() == ''){
                nombres_campos += ' * The field Snote is required';
                error = 1;

    }
    if($("#onote_edit").val() == ''){
                nombres_campos += ' * The field Onote is required';
                error = 1;

    }
    if($("#anote_edit").val() == ''){
                nombres_campos += ' * The field Anote is required';
                error = 1;

    }
    if($("#pnote_edit").val() == ''){
                nombres_campos += ' * The field Pnote is required';
                error = 1;

    }
    if($("#signedNoteEdit").prop('checked')){        
        if($("#dateSignedEdit").val() == ''){
            nombres_campos += ' * The field Signed date is required';
            error = 1;
        }                                                
    }

    if(error == 0){
        //var campos_formulario = $("#formNoteEdit").serialize();
        var form = $('#formNoteEdit')[0]; 
            //var formData = new FormData(form);                                                        

            var campos_formulario = $("#formNoteEdit").serialize();
            var data = new FormData();    
            $('#formNoteEdit input[type=file]').each(function(){                
                data.append(this.name,$(this)[0].files[0]);                       
            });

            data.append('campos_formulario',campos_formulario);                   
            $.ajax({
                url: "../../controlador/gestion_clinic/edit_note.php",
                type: "POST",
                data: data, 
                processData: false,
                contentType: false,                                 
                success : function(resultado_controlador_archivo){               
                    //alert(resultado_controlador_archivo);
                   var result = resultado_controlador_archivo.indexOf("insertada");
                   if(result != null){                
                        swal({
                          type: 'success',                          
                          html: '<h3><b>Record edited successfully</b></h3>',
                          timer: 3000    
                        }
                        ); 
                        $("#modalEditDocument").modal('hide');
                        search_result();
                    } else {
                        swal({
                          title: "<h4><b>Error, Failed to save the prescription</h4>",          
                          type: "error",              
                          showCancelButton: false,              
                          closeOnConfirm: true,
                          showLoaderOnConfirm: false,
                        });                                     
                    }
                }
            });
//        $.post(
//            "../../controlador/gestion_clinic/edit_note.php",
//            campos_formulario,
//            function (resultado_controlador) {
//                if(resultado_controlador.mensaje == 'ok'){
//                    swal({
//                        type: 'success',                          
//                        html: '<h3><b>Record saved successfully</b></h3>',
//                        timer: 3000    
//                    }); 
//                    $("#modalEditDocument").modal('hide');
//                }                    
//                search_result();                                                
//            },
//            "json" 
//        );
    }else{
        $('.error-message').show('slow');
        $('.error-message').html(nombres_campos);            
    }
    return false;
}
</script>
    </head>
<body>
<form id="formNoteEdit" onsubmit="return validar_form_note_edit();" enctype="multipart/form-data">
    <div class="row">
        <label class="col-lg-2">Patients:</label>
        <div class="col-lg-3">
            <input type="hidden" name="id_note_edit" id="id_note_edit" value="<?=$reporte['id_notes']?>">           
            <div id="patients_name_note_modal"><?=$reporte['Patient_name']?></div>
        </div>                         
        <label class="col-lg-1">Discipline:</label>
        <div class="col-lg-4">                                    
            <div id="discipline_note_modal"><?=$reporte['discipline_note'];?></div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Snote:</label>
        <div class="col-lg-5">
            <textarea class="form-control" id="snote_edit" name="snote_edit"><?=$reporte['snotes'];?></textarea>                                    
        </div>
    </div>                                                        
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">ONote:</label>
        <div class="col-lg-5">
            <textarea class="form-control" id="onote_edit" name="onote_edit"><?=$reporte['onotes'];?></textarea>                                    
        </div>
    </div>                                                        
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">ANote:</label>
        <div class="col-lg-5">
            <textarea class="form-control" id="anote_edit" name="anote_edit"><?=$reporte['anotes'];?></textarea>                                    
        </div>
    </div>                                                        
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">PNote:</label>
        <div class="col-lg-5">
            <textarea class="form-control" id="pnote_edit" name="pnote_edit"><?=$reporte['pnotes'];?></textarea>                                    
        </div>
    </div>                                                        
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Therapist:</label>
        <div class="col-lg-5">
            <?=$reporte['Physician_name'];?>
        </div>
    </div> 
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Signed:</label>
        <div class="col-lg-1">            
            <input type="checkbox" id="signedNoteEdit" name="signedNoteEdit" style="width: 15px;height: 15px;" value="1" <?=($reporte['signed_note']==1)?'checked':'';?> class="form-control">
        </div>
        <div class="col-lg-5">
            <input type="text" id="dateSignedEdit" name="dateSignedEdit" class="form-control" value="<?=date('Y-m-d', strtotime($reporte['date_signed_note']));?>" placeholder="Signed Date">
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Visit Date:</label>                                
        <div class="col-lg-5">
            <input type="text" id="visit_date_edit" name="visit_date_edit" class="form-control" value="<?=date('Y-m-d', strtotime($reporte['visit_date']));?>" placeholder="Visit Date">
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Document:</label>
        <div class="col-lg-10">
            <a href="../../../<?=$reporte['route_document']?>" target="_blank"><?=$reporte['route_document']?></a>
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

</body>
</html>

<script>  
    
$(document).ready(function() {
    $('#dateSignedEdit').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	        
    $('#dateSignedEdit').prop('readonly', true);    
    
    $('#visit_date_edit').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	        
    $('#visit_date_edit').prop('readonly', true);        
});
</script>