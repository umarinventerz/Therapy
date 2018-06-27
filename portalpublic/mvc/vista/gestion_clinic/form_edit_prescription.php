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

$sql  = "SELECT * FROM prescription p"
        . " LEFT JOIN tbl_documents d ON d.id_table_relation = p.id_prescription "
        . " LEFT JOIN discipline di ON di.DisciplineId = p.Discipline "
        . "WHERE p.id_prescription = ".$_GET['id_document'].";"; 
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

function validar_form_edit(){
        $('.error-message').hide('slow');
        $('.error-message').html('');
        var nombres_campos = '';
        var error = 0;
        if($("#companies_id_edit").val() == ''){
                    nombres_campos += ' * The field Company is required';
                    error = 1;
                                
        }
        if($('#diagnostic_id_edit').val() == '' && error == 0){
                    nombres_campos += '* The field Diagnostic is required';
                    error = 1;
        }
        if($('#from_hidden_edit').val() == '' && error == 0){
                    nombres_campos += '* The field Diagnostic is required';
                    error = 1;
        }
        if($('#to_hidden_edit').val() == '' && error == 0){
                    nombres_campos += '* The field Diagnostic is required';
                    error = 1;
        }
          if($('#physician_id_edit').val() == '' && error == 0){
                    nombres_campos += '* The field Physician is require';
                    error = 1;            
        }         
        if(nombres_campos != ''){             
            $('.error-message').show('slow');
            $('.error-message').html(nombres_campos);
            return false; 
        
        } else {
        //var campos_formulario = $("#formPrescription").serialize();  
        var form = $('#formPrescriptionEdit')[0]; 
        //var formData = new FormData(form);                                                        

        var campos_formulario = $("#formPrescriptionEdit").serialize();
        var data = new FormData();    
        $('#formPrescriptionEdit input[type=file]').each(function(){                
            data.append(this.name,$(this)[0].files[0]);                       
        });
                
        data.append('campos_formulario',campos_formulario);                   
        $.ajax({
            url: "../../controlador/prescriptions/edit_prescriptions.php",
            type: "POST",
            data: data, 
            processData: false,
            contentType: false,                                 
            success : function(resultado_controlador_archivo){               
               var active = resultado_controlador_archivo.indexOf("active");               
               var extension = resultado_controlador_archivo.indexOf("extension");               
               var ok = resultado_controlador_archivo.indexOf("ok");
               var duplicated = resultado_controlador_archivo.indexOf("duplicated");
               if(active != -1){                
                    swal({
                      type: 'error',                          
                      html: '<h3><b>To Activate a New RX you must Not Have an Active One</b></h3>',
                      timer: 3000    
                    });                     
                } else {
                    if(extension != -1){                                        
                        swal({
                            type: 'error',                          
                            html: '<h3><b>Error extension</b></h3>',
                            timer: 3000    
                        });                         
                    }else{
                        if(ok != -1){                
                            swal({
                                type: 'success',                          
                                html: '<h3><b>Record edited successfully</b></h3>',
                                timer: 3000    
                            }); 
                            $("#modalEditDocument").modal('hide');
                            search_result();
                        }else{
                            if(duplicated != -1){
                                swal({
                                    type: 'error',                          
                                    html: '<h3><b>There is another Prescription with that Status, Check Documentation of the Patient</b></h3>',
                                    timer: 5000    
                                });
                            }
                        }
                    }                    
                }
            }
        });

            return false; 
        }
          
          
      }

</script>
    </head>
<body>
<form id="formPrescriptionEdit" onsubmit="return validar_form_edit();" enctype="multipart/form-data">
    <div class="row">
        <label class="col-lg-2">Patients:</label>
        <div class="col-lg-3">
            <input type="hidden" id="prescription_id_hidden_edit" name="prescription_id_hidden_edit" value="<?=$reporte['id_prescription']?>"/>
            <input type="hidden" id="patients_id_hidden_edit" name="patients_id_hidden_edit" value="<?=$reporte['Patient_ID']?>"/>
            <input type="hidden" name="discipline_id_hidden_edit" id="discipline_id_hidden_edit" value="<?=$reporte['Discipline']?>">
            <div id="patients_name_modal_edit"><?=$reporte['Patient_name']?></div>
        </div>                         
        <label class="col-lg-1">Discipline:</label>
        <div class="col-lg-4">                                    
            <div id="discipline_modal_edit"><?=$reporte['Name']?></div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Company:</label>
        <div class="col-lg-5">
            <select id="companies_id_edit" name="companies_id_edit">
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
        <div class="col-lg-5">
            <select id="diagnostic_id_edit" name="diagnostic_id_edit"><option value=''>Select..</option>


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
            <input type="text" name="from_hidden_edit" id="from_hidden_edit" class="form-control" placeholder="From" value="<?=$reporte['Issue_date']?>"/>
        </div>
    </div>                            
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">To:</label>
        <div class="col-lg-5">
            <input type="text" name="to_hidden_edit" id="to_hidden_edit" class="form-control" placeholder="To" value="<?=$reporte['End_date']?>"/>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Physician Name:</label>
        <div class="col-lg-5">
            <select id="physician_id_edit" name="physician_id_edit"><option value=''>Select..</option>
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
    <div class="row" style="margin-top: 15px;">
        <label class="col-lg-2">Status:</label>
        <div class="col-lg-5">
            <select id="status_id_edit" name="status_id_edit">                
                <option value='1'>In Progress</option>
                <option value='2'>Active</option>
                <option value='3'>Inactive</option>
            </select>
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
            <a href="pdf/preescription.php?id=<?=$_GET['id_document']?>" class="btn btn-default" target="_blank" style="cursor: pointer;" class="ruta"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
            
    </div>
</form>

</body>
</html>

<script>  
    
$(document).ready(function() {

        $('#from_hidden_edit').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	        
        $('#from_hidden_edit').prop('readonly', true);
        $('#to_hidden_edit').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	
        $('#to_hidden_edit').prop('readonly', true);                      
        
        LoadSelect2ScriptExt(function(){                                  
            $('#companies_id_edit').select2();
            $('#diagnostic_id_edit').select2();
            $('#physician_id_edit').select2();
            $('#status_id_edit').select2();
        });
        
        autocompletar_radio('concat(DiagCodeValue,\', \',DiagCodeDescrip) as texto','DiagCodeID','diagnosiscodes d LEFT JOIN discipline di ON di.DisciplineId = d.TreatDiscipId','selector',null,null,'di.DisciplineId = \'<?=$reporte['Discipline']?>\' AND DiagCodeValue <> \'\'','','diagnostic_id_edit');
        autocompletar_radio('company_name','company_name','companies','selector',null,null,null,null,'companies_id_edit');                         
        autocompletar_radio('Name','NPI','physician','selector',null,null,null,null,'physician_id_edit');
        
        setTimeout(function(){            
            $('#diagnostic_id_edit').val('<?=$reporte['Diagnostic']?>').change();
            $('#companies_id_edit').val('<?=$reporte['Table_name']?>').change();
            $('#physician_id_edit').val('<?=$reporte['Physician_NPI']?>').change();
            $('#status_id_edit').val('<?=$reporte['status']?>').change();
        },1000);
        
});
</script>