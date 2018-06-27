<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'Must LOG IN First\')</script>';
    echo '<script>window.location=../../../"index.php";</script>';
}

$conexion = conectar();


$sql2  = "SELECT first_name,last_name,permission_calendar FROM employee WHERE id=".$_SESSION['user_id'];
$resultado1 = ejecutar($sql2,$conexion);
while($datos_good1 = mysqli_fetch_assoc($resultado1)){
    $respuesta['permission_calendar'] = $datos_good1['permission_calendar'];
    $respuesta['first_name'] = $datos_good1['first_name'];
    $respuesta['last_name'] = $datos_good1['last_name'];
}
if($respuesta !=null){
    $permisos=explode(',',$respuesta['permission_calendar']);
}else{
    //SIN PERMISOS
    $permisos='S/P';
}



//$_GET['prescriptions_id_select']
$evaluations_id=24;
$sql_evaluations  = "SELECT *, pat.Pat_id as pat_id_long, pat.Address, pat.First_name,pat.Last_name,pat.id, d.DisciplineId  FROM tbl_evaluations te "
    . " LEFT JOIN patients pat ON pat.id = te.patient_id "
    . " LEFT JOIN discipline d ON d.DisciplineID = te.discipline_id "
    . " LEFT JOIN diagnosiscodes dc ON dc.DiagCodeId = te.diagnostic "
    . " LEFT JOIN employee e ON e.id = te.id_user "

    . " LEFT JOIN companies com ON com.id = te.company "


    . " LEFT JOIN prescription pr ON pr.id_prescription = te.id_prescription "
    . "WHERE te.id='".$evaluations_id."' ";
$resultado = ejecutar($sql_evaluations,$conexion);

$i = 0;
$datos_good= array();

while($row = mysqli_fetch_array($resultado)) {

    $datos_good[$i]=$row;

    $i++;
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Prescriptions</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>

    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/build.css">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../css/font-awesome1.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
    <link href="../../../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="../../../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="../../../plugins/xcharts/xcharts.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
    <link href="../../../plugins/chartist/chartist.min.css" rel="stylesheet">

    <script src="../../../plugins/jquery/jquery.min.js"></script>
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
    <script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
    <script src="../../../plugins/tinymce/tinymce.min.js"></script>
    <script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
    <!-- All functions for this theme + document.ready processing -->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="../../../js/devoops_ext.js"></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css"/>
    <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/bootstrap-treefy.css"/>
    <link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <script src="../../../js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../../js/jquery.treegrid.js"></script>
    <script type="text/javascript" src="../../../js/jquery.treegrid.bootstrap3.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/bootstrap-treefy.js"></script>
    <script src="ckeditor/ckeditor.js"></script>

    <script>
        $(document).ready(function(){

            $('#from_hidden').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
            $('#from_hidden').prop('readonly', true);
            $('#to_hidden').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
            $('#to_hidden').prop('readonly', true);




        });

    </script>


<script>

    function validar_form(){
        $('.error-message').hide('slow');
        $('.error-message').html('');
        var nombres_campos = '';
        var error = 0;
        if($("#companies_id").val() == ''){
            nombres_campos += ' * The field Company is required';
            error = 1;

        }
        if($('#diagnostic_id').val() == '' && error == 0){
            nombres_campos += '* The field Diagnostic is required';
            error = 1;
        }
        if($('#from_hidden').val() == '' && error == 0){
            nombres_campos += '* The field Diagnostic is form hidde required';
            error = 1;
        }
        if($('#to_hidden').val() == '' && error == 0){
            nombres_campos += '* The field Diagnostic is to hidde required';
            error = 1;
        }
        if($('#physician_id').val() == '' && error == 0){
            nombres_campos += '* The field Physician is required';
            error = 1;
        }

        if($('#formPrescription :input[type=file]').val() == '' && error == 0){
            nombres_campos += '* The field Attachment is required';
            error = 1;
        }

        if(nombres_campos != ''){
            $('.error-message').show('slow');
            $('.error-message').html(nombres_campos);
            return false;

        } else {
            //var campos_formulario = $("#formPrescription").serialize();
            var form = $('#formPrescription')[0];
            var formData = new FormData(form);

            var campos_formulario = $("#formPrescription").serialize();
            var data = new FormData();
            $('input[type=file]').each(function(){
                data.append(this.name,$(this)[0].files[0]);
            });
            data.append('campos_formulario',campos_formulario);
            $.ajax({
                url: "../../controlador/prescriptions/insert_prescriptions.php",
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
                                html: '<h3><b>Record saved successfully</b></h3>',
                                timer: 3000
                            }
                        );
                        $("#modalAddPrescription").modal('hide');
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

            return false;
        }


    }


</script>


<body>

<?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

<div class="container">
    <div class="row">

        <div class="col-lg-12">
            <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
        </div>
    </div>
    <div class="col-lg-12">

        <div class="alert alert-danger error-message error-message"></div>

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
</div>
</body>

</html>



