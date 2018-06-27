<?php
session_start();
require_once("../../../../conex.php");
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'Must LOG IN First\')</script>';
    echo '<script>window.location=../../../"index.php";</script>';
}

$conexion = conectar();
$sql2  = "SELECT first_name,last_name,permission_calendar FROM employee WHERE id=".$_SESSION['user_id'];
$resultado1 = ejecutar($sql2,$conexion);
while($datos = mysqli_fetch_assoc($resultado1)){
    $respuesta['permission_calendar'] = $datos['permission_calendar'];
    $respuesta['first_name'] = $datos['first_name'];
    $respuesta['last_name'] = $datos['last_name'];
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
$datos= array();

while($row = mysqli_fetch_array($resultado)) {

    $datos[$i]=$row;

    $i++;
}
?>



<!DOCTYPE html>

<html>
<head>
    <meta charset='utf-8' />
    <link href="../../../../plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href='../../../../plugins/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
    <link href='../../../../plugins/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
    <link href="../../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../../plugins/fullcalendar/jquery.bootstrapvalidator/dist/css/bootstrapValidator.min.css" rel="stylesheet" />
    <link href="../../../../plugins/fullcalendar/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
    <link href="../../../../plugins/fullcalendar/css/bootstrap-timepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../../../../css/bootstrap-multiselect.css">
    <script src='../../../../plugins/fullcalendar/js/moment.min.js'></script>
    <script src="../../../../plugins/fullcalendar/js/jquery.min.js"></script>
    <script src="../../../../plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../../../js/funciones.js" type="text/javascript"></script>
    <script type="text/javascript" src="../../../../plugins/fullcalendar/jquery.bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>
    <script src="../../../../plugins/fullcalendar/js/fullcalendar.min.js"></script>
    <script src='../../../../plugins/fullcalendar/js/bootstrap-colorpicker.min.js'></script>
    <script src='../../../../plugins/fullcalendar/js/bootstrap-timepicker.min.js'></script>
    <script src='../../../../plugins/fullcalendar/js/main.js'></script>
    <script src='../../../../plugins/fullcalendar/js/bootbox.min.js'></script>
    <script src="../../../../plugins/select2/select2.js"></script>
    <script type="text/javascript" src="../../../../plugins/datepiker/jquery.simple-dtpicker.js"></script>
    <script type="text/javascript" language="javascript" src="../../../../js/bootstrap-multiselect.js"></script>

    <link type="text/css" href="../../../../plugins/datepiker/jquery.simple-dtpicker.css" rel="stylesheet" />

    <script>
        $(document).ready(function(){

            $("#modalAddNote").modal('show');




            });

        </script>


    <script>

        function validar_form_note(accion){
            $('.error-message').hide('slow');
            $('.error-message').html('');
            var nombres_campos = '';
            var error = 0;
            if($("#snote").val() == ''){
                nombres_campos += ' * The field Snote is required';
                error = 1;

            }
            if($("#onote").val() == ''){
                nombres_campos += ' * The field Onote is required';
                error = 1;

            }
            if($("#anote").val() == ''){
                nombres_campos += ' * The field Anote is required';
                error = 1;

            }
            if($("#pnote").val() == ''){
                nombres_campos += ' * The field Pnote is required';
                error = 1;

            }
            if($("#signedNote").prop('checked')){
                if($("#dateSigned").val() == ''){
                    nombres_campos += ' * The field Signed date is required';
                    error = 1;
                }
            }

            if(error == 0){

                var form = $('#formNote')[0];
                var formData = new FormData(form);

                var campos_formulario = $("#formNote").serialize();
                var data = new FormData();
                $('#formNote :input[type=file]').each(function(){
                    data.append(this.name,$(this)[0].files[0]);
                });
                if(accion==='insertar'){
                    var url="../../../controlador/gestion_clinic/insert_note.php";
                    var mensaje='Saved';

                }else{
                    url="../../controlador/gestion_clinic/edit_note.php";
                    mensaje='Updated';
                }
                data.append('campos_formulario',campos_formulario);
                $.ajax({
                    url: url,
                    type: "POST",
                    async:false,
                    dataType: "json",
                    data: data,
                    processData: false,
                    contentType: false,
                    success : function(data){
                        if(data.mensaje === 'ok'){
                            swal({
                                    type: 'success',
                                    html: '<h3><b>Record '+mensaje+' successfully</b></h3>',
                                    timer: 3000
                                }
                            );
                            /*$('#formPoc')[0].reset();
                            $("#modalAddPoc").modal('hide');
                            search_result();*/
                            window.location="../../../mvc/vista/gestion_clinic?patient_id="+data.pat_id+"&disciplina="+data.disciplina;
                        }else{
                            swal({
                                title: "<h4><b>"+data.response+"</b></h4>",
                                type: "error",
                                showCancelButton: false,
                                closeOnConfirm: true,
                                showLoaderOnConfirm: false,
                            });
                        }
                    }
                });
            }else{
                $('.error-message').show('slow');
                $('.error-message').html(nombres_campos);
            }
            return false;
        }


        function agregar_cpt(){
            $("#modalCpt").modal('show');
        }

    </script>


</head>


<body>

<?php #$perfil = $_SESSION['user_type']; include "nav_bar.php"; ?>

<br><br>

<div class="container">

    <div class="row">
        <div class="alert alert-danger error-message error-message"></div>
        <div class="col-lg-12">
            <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
        </div>
    </div>
    <div class="col-lg-12">
        <form id="formNote" <?php if(isset($_GET['note'])){?>onsubmit="return validar_form_note('edit');"<?php }else{?> onsubmit="return validar_form_note('insertar');"<?php } ?> enctype="multipart/form-data">


            <div>
                <div >

                    <div class="col-lg-9">


                        <div class="row">
                            <input type="hidden" name="id_notes" id="id_notes" value="<?=$_GET['id_note']?>">
                            <input type="hidden" name="id_careplans" id="id_careplans" value="<?=$datos[0]['id_careplans']?>">
                            <input type="hidden" name="patients_id" id="patients_id" value="<?=$datos[0]['patient_id']?>">
                            <input type="hidden" name="discipline_id" id="discipline_id" value="<?=$datos[0]['discipline_id']?>">
                        </div>

                        <div class="row">

                            <label class="col-lg-1  withoutPadding">Patients: </label>
                            <div class="col-lg-4 withoutPadding">
                                <?php   echo $datos[0]['First_name'].' '.$datos[0]['Last_name']."<b> ( ".$datos[0]['Pat_id'].")</b>"?>
                            </div>



                            <label class="col-lg-1 withoutPadding">DOB/Age: </label>
                            <div class="col-lg-4 withoutPadding">
                                <div id="npi_eval_modal" style="font-size:13px">
                                    &nbsp;&nbsp;
                                    <?php echo $datos[0]['DOB'].'/'.'22'?>
                                </div>
                            </div>



                        </div>

                        <br>

                        <div class="row" style="margin-top: 15px;">

                            <label class="col-lg-1 withoutPadding">Visit Date:</label>
                            <div class="col-lg-4 withoutPadding">



                                <?php

                                $sql_evaluations  = "SELECT *  FROM tbl_visits tv "
                                    . " LEFT JOIN tbl_notes_documentation tnd ON tnd.visit_id = tv.id "
                                    . "WHERE tv.id='".$datos[0]['visit_id']."' ";
                                $resultado = ejecutar($sql_evaluations,$conexion);

                                $i = 0;
                                $visit= array();

                                while($row = mysqli_fetch_array($resultado)) {

                                    $visit[$i]=$row;

                                    $i++;
                                }

                                echo $visit[0]['visit_date']?>
                            </div>




                            <label class="col-lg-1 withoutPadding">Duration:</label>
                            <div class="col-lg-3 withoutPadding">
                                <select name="duration" id="duration">
                                    <option value="15" <?php if($datos[0]['minutes']==15){?>selected=""<?php }?>>15</option>
                                    <option value="30" <?php if($datos[0]['minutes']==30){?>selected=""<?php }?>>30</option>
                                    <option value="45" <?php if($datos[0]['minutes']==45){?>selected=""<?php }?>>45</option>
                                    <option value="60" <?php if($datos[0]['minutes']==60){?>selected=""<?php }?>>60</option>

                                </select>

                            </div>
                            <br>

                        </div>
                        <br>
                        <div class="row" >


                            <label  class="col-lg-1 withoutPadding" >Therapist:</label>
                            <div  class="col-lg-4">
                                <?php echo $datos[0]['first_name']." ".$datos[0]['last_name'].'('.$datos[0]['npi_number'].")"?>
                            </div>





                            <label class="col-lg-1 withoutPadding">Discipline:</label>
                            <div class="col-lg-4 withoutPadding" >
                                &nbsp;&nbsp;  <?php echo $datos[0]['Description'].' ( '.$datos[0]['Name'].' )'?>
                            </div>



                        </div>



                        <!-- DIV PARA CERRAR LAS DOS PRIMERAS COLUMNAS  -->
                    </div>


                    <div class="col-lg-3">

                        <div>

                            <label class="col-lg-12 withoutPadding">Company:</label>
                            <div class="col-lg-12 withoutPadding">

                                <select name='company_id' id='company_id' class="form-control">
                                    <?php
                                    $sql_companie  = "Select * from companies";

                                    $resultado_companie = ejecutar($sql_companie,$conexion);
                                    while ($row=mysqli_fetch_array($resultado_companie))
                                    {

                                        if($note[0]['company_poc']==$row["id"]){
                                            print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>");
                                        }else{
                                            print("<option value='".$row["id"]."'>".$row["company_name"]." </option>");
                                        }

                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="col-lg-12 withoutPadding"  style="font-size:18px; font-weight:bold;"> <font color='#1e2c51'>

                                    <?=$datos[0]['company_name']?><br>
                                    <?=$datos[0]['facility_address']?><br>
                                    <?=$datos[0]['facility_city'].",".$datos[0]['facility_state'].",".$datos[0]['facility_zip']?><br>
                                    (P) <?=$datos[0]['facility_phone']?><br>
                                    (F) <?=$datos[0]['facility_fax']?><br>

                                </font>

                            </div>
                        </div>

                        <!-- DIV PARA CERRAR LA COLUMNA DE COMPANY -->
                    </div>


                    <!-- DIV FINAL ANTES DE LA LINEA  -->
                </div>

            </div>



            <br><hr>
           <br>

            <hr>
            <hr>
            <div class="row" style="margin-top: 15px;">
                <label class="col-lg-12">Subject:</label>
                <div class="col-lg-12">
                    <textarea class="form-control" id="snote" name="snote"></textarea>
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                <label class="col-lg-12">Objective:</label>
                <div class="col-lg-12">
                    <textarea class="form-control" id="onote" name="onote"> </textarea>
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                <label class="col-lg-12">Assessment:</label>
                <div class="col-lg-12">
                    <textarea class="form-control" id="anote" name="anote"></textarea>
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                <label class="col-lg-12">Plan:</label>
                <div class="col-lg-12">
                    <textarea class="form-control" id="pnote" name="pnote"> </textarea>
                </div>
            </div>
            <br><hr>





            <!--  <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Signed:</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" id="signedNote" name="signedNote" style="width: 15px;height: 15px;" value="1" class="form-control" <?php if($note[0]['user_signed']==1){?>checked=""<?php } ?>>
                                </div>
                                <div class="col-lg-5">
                                    <input type="text" id="dateSigned" name="dateSigned" class="form-control" placeholder="Signed Date" value="<?=$note[0]['user_signed_date']?>">
                                </div>
                            </div> -->

            <br>








            <br>
            <div class="row">
                <label class="col-lg-1">&nbsp;&nbsp;&nbsp;Attachment:</label>
                <div class="col-lg-5">
                    <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                </div>
            </div>
            <hr/>
            <div class="row card_footer">
                <div class="col-sm-offset-4 col-sm-2 mg-top-sm">

                    <button type="submit" enable="" class="btn btn-success"><?php echo "Save"?></button>


                </div>
                <div class="col-sm-offset-1 col-sm-2 mg-top-sm">
                    <button type="button" class="btn btn-danger" onclick="cancelar(<?=$datos[0]['patient_id']?>,<?=$datos[0]['discipline_id']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                </div>

            </div>
            <hr>
        </form>
    </div>


    <div id="resultado"></div>

</div>
</body>
</html>
