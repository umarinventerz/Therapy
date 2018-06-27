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

$sql  = "SELECT *,c.status as status_poc FROM careplans c"
        . " LEFT JOIN tbl_evaluations e ON e.id = c.evaluations_id"
        . " LEFT JOIN prescription p ON p.id_prescription = e.id_prescription"
        . " LEFT JOIN tbl_documents d ON d.id_table_relation = c.id_careplans AND id_type_document = 2"
        . " WHERE c.id_careplans = ".$_GET['id_document'].";"; 
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
function validar_form_poc_edit(){
    $('.error-message').hide('slow');
    $('.error-message').html('');
    var nombres_campos = '';
    var error = 0;
    if($("#from_poc_edit").val() == ''){
                nombres_campos += ' * The field From is required';
                error = 1;

    }
    if($("#to_poc_edit").val() == '' && error == 0){
                nombres_campos += ' * The field to is required';
                error = 1;

    }

    if(error == 0){
        var form = $('#formPocEdit')[0]; 
            //var formData = new FormData(form);                                                        

            var campos_formulario = $("#formPocEdit").serialize();
            var data = new FormData();    
            $('#formPocEdit input[type=file]').each(function(){                
                data.append(this.name,$(this)[0].files[0]);                       
            });

            data.append('campos_formulario',campos_formulario);                   
            $.ajax({
                url: "../../controlador/careplans/edit_careplans.php",
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
//        var campos_formulario = $("#formPocEdit").serialize();
//
//        $.post(
//            "../../controlador/careplans/edit_careplans.php",
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
<form id="formPocEdit" onsubmit="return validar_form_poc_edit();" enctype="multipart/form-data">
    <div class="row">
        <label class="col-lg-2">Patients:</label>
        <div class="col-lg-3">
            <input type="hidden" name="id_careplans_edit" id="id_careplans_edit" value="<?=$reporte['id_careplans']?>">            
            <div id="patients_name_poc_modal"><?=$reporte['Last_name'].', '.$reporte['First_name']?></div>
        </div>                         
        <label class="col-lg-1">Discipline:</label>
        <div class="col-lg-4">                                    
            <div id="discipline_poc_modal"><?=$reporte['Discipline'];?></div>
        </div>
    </div>
    <div class="row">
        <label class="col-lg-2">Diagnostic:</label>
        <div class="col-lg-4">                                    
            <div><?=$reporte['diagnostic']?></div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">From:</label>
        <div class="col-lg-5">
            <input type="text" name="from_poc_edit" id="from_poc_edit" class="form-control" value="<?=$reporte['POC_due']?>" placeholder="From"/>
        </div>
    </div>                            
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">To:</label>
        <div class="col-lg-5">
            <input type="text" name="to_poc_edit" id="to_poc_edit" class="form-control" value="<?=$reporte['Re_Eval_due']?>" placeholder="To"/>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Therapist:</label>
        <div class="col-lg-5">
            <?=$reporte['Physician_name'];?>
        </div>
    </div>       
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Status:</label>
        <div class="col-lg-5">
            <select id="status_id_poc_edit" name="status_id_poc_edit">
                <option value='1'>In Progress</option>
                <option value='2'>Active</option>
                <option value='3'>Inactive</option>
            </select>
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
          
        $('#from_poc_edit').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	        
        $('#from_poc_edit').prop('readonly', true);
        $('#to_poc_edit').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	
        $('#to_poc_edit').prop('readonly', true);
        
        LoadSelect2ScriptExt(function(){                                  
            $('#status_id_poc_edit').select2();        
        });
        
        setTimeout(function(){            
            $('#status_id_poc_edit').val('<?=$reporte['status_poc']?>').change();            
        },1000); 
    
});
</script>