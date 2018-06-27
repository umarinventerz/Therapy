
<?php




session_start();
require_once '../../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="../../../index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="../../home/home.php";</script>';
    }
}
$conexion = conectar();

$prescriptions_id=$_GET['prescriptions_id_select'];
$sql_evaluations  = "SELECT *, pat.Pat_id as pat_id_long, pat.Address, pat.First_name,pat.Last_name,pat.id, d.DisciplineId  FROM prescription p "
    . " LEFT JOIN patients pat ON pat.id = Patient_ID "
    . " LEFT JOIN discipline d ON d.DisciplineID = p.Discipline "
    . "WHERE p.id_prescription='".$prescriptions_id."' ";
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










<style>
    .span12{    
        box-shadow: 0 0 0.5px #9e9e9e;
        padding: 15px 0px 25px 120px; 
        margin-right: 20px;
    }
</style>


<script type="text/javascript" language="javascript">

    $(document).ready(function(){

        $('#from').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from').prop('readonly', true);
        $('#to').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to').prop('readonly', true);

        $('#dateSigned').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#dateSigned').prop('readonly', true);



        $('#from_hidden').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_hidden').prop('readonly', true);
        $('#to_hidden').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_hidden').prop('readonly', true);

        //$('#start_note').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        //$('#to_note').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});


        $('#from_eval').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_eval').prop('readonly', true);
        $('#to_eval').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_eval').prop('readonly', true);

        $('#from_poc').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_poc').prop('readonly', true);
        $('#created_poc').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#created_poc').prop('readonly', true);
        $('#to_poc').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_poc').prop('readonly', true);

        $('#from_summary').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_summary').prop('readonly', true);
        $('#to_summary').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_summary').prop('readonly', true);
        $('#date_of_summary').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#date_of_summary').prop('readonly', true);
        $('#date_of_signed_summary').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#date_of_signed_summary').prop('readonly', true);

        $('#from_discharge').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_discharge').prop('readonly', true);

        $('#to_discharge').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_discharge').prop('readonly', true);


        $('#date_of_eval').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#date_of_eval').prop('readonly', true);
        $('#date_of_signed').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#date_of_signed').prop('readonly', true);

        $('#visit_date').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#visit_date').prop('readonly', true);

    });


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










                <div class="col-md-offset-2 col-md-3" >
                    <h4 class="modal-title" style="margin-top: 45px;color: #FFF;"><?php if(isset($_GET['id_eval'])){echo "Edit Evaluation #".$_GET['id_eval'];}else{echo 'New Evaluation';}?></h4>
                </div>


                <div class="alert alert-danger error-message error-message"></div>

    <div class="col-lg-12">

                        <form id="formEvaluation" <?php if(isset($_GET['calendar']) || isset($_GET['edit'])){?>onsubmit="return validar_form_eval('edit');"<?php }else{?> onsubmit="return validar_form_eval('insert');"<?php } ?> enctype="multipart/form-data">
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Patients:</label>
                                <div class="col-lg-3">

                                    <?php  echo $datos[0]['Patient_name'];
                                        if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){


                                    ?>
                                        <input type="hidden" name="field_disabled" id="field_disabled" value="true">
                                    <?php
                                        }else{
                                    ?>
                                        <input type="hidden" name="field_disabled" id="field_disabled" value="false">
                                    <?php } ?>
                                    <input type="hidden" name="prescription_id_eval_hidden" id="prescription_id_eval_hidden" value="<?php if(isset($_GET['id_preescription'])){echo $_GET['id_preescription'];}?>">
                                    <input type="hidden" name="id_eval" id="id_eval" value="<?php if(isset($_GET['id_eval'])){echo $_GET['id_eval'];}?>">
                                    <input type="hidden" name="patients_eval_hidden" id="patients_eval_hidden" value="<?=$data[0]['patient_id']?>">
                                    <input type="hidden" name="discipline_id_eval_hidden" id="discipline_id_eval_hidden" value="<?=$data[0]['discipline_id']?>">
                                    <input type="hidden" name="diagnostic_eval_hidden" id="diagnostic_eval_hidden" value="<?=$data[0]['diagnostic']?>">
                                    <input type="hidden" name="company_id_eval_hidden" id="company_id_eval_hidden" value="<?=$data[0]['company']?>">
                                    <div id="patients_name_eval_modal"><?php echo $data[0]['name'];?></div>
                                </div>                         
                                <label class="col-lg-1 withoutPadding">Pat ID: </label>
                                <div class="col-lg-2 withoutPadding">
                                    <div id="pat_id_eval_modal"><?php echo $data[0]['Pat_id']?></div>
                                </div>
                                <label class="col-lg-1 withoutPadding">Company:</label>
                                <div class="col-lg-2 withoutPadding">                                    
                                    
                 <select name='company_id' id='company_id' class="form-control" <?php if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>   >
                                            <?php
                                                $sql_companie  = "Select * from companies";
                                                
                                                $resultado_companie = ejecutar($sql_companie,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_companie)) 
                                                {

                                                        print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>"); 


                                                }
                                                    
                                            ?>
                                        </select> 
                                    <input type="hidden" name="company_id_eval_hidden" id="company_id_eval_hidden" value="<?=$data[0]['company']?>"> 
                                </div><br><hr>
                                
                                
                            </div>
                            


                            <div class="row">

                              <div class="col-lg-9" >

                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Date From:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <input  <?php if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>   type="text" name="from_eval" id="from_eval" class="form-control" style="width: 180px;" placeholder="From" value="<?=$datos[0]['Issue_date']?>"/>
                                </div>

                                <label class="col-lg-1 withoutPadding">Eval due:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <input <?php echo $data[0]['id_user']; if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>   type="text" name="to_eval" id="to_eval" class="form-control" style="width: 180px;" placeholder="To" value="<?=$data[0]['to']?>"/>
                                </div>
                            </div>  <br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Discipline:&nbsp;&nbsp;</label>
                                <div class="col-lg-4 withoutPadding">                                    
                                    <div id="discipline_eval_modall">&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php echo $datos[0]['Name']?></div>
                                </div>
                                <label class="col-lg-1 withoutPadding">Diag :</label>
                                <div class="col-lg-4 withoutPadding">
                        <select name='diagnostic_id_evall' id='diagnostic_id_evall'   >
                                            <?php
                                                $sql_diagnostic  = "Select DiagCodeId,concat(DiagCodeValue,', ',DiagCodeDescrip) as name from diagnosiscodes where TreatDiscipId=".$datos[0]['DisciplineId'];
                                                
                                                $resultado_diagnostic = ejecutar($sql_diagnostic,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_diagnostic)) 
                                                {
                                                    if($datos[0]['Diagnostic']==$row["DiagCodeId"]){
                                                        print("<option value='".$row["DiagCodeId"]."' selected>".$row["name"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                    }
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Therapist:</label>
                                <div class="col-lg-4 withoutPadding">
                                   &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data[0]['employee']?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $datos[0]['Physician_name']?>


                                </div>
                                <label class="col-lg-1 withoutPadding">NPI:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <div id="npi_eval_modal">
                                        <?=$npi?>
                                        <?php echo  $datos[0]['Physician_NPI']?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Date of Eval:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <input type="text" name="date_of_eval" style="width: 200px;" id="date_of_eval" value="<?php echo $data[0]['created']?>" class="form-control" disabled=""led placeholder="Date of Evaluation"/>
                                </div>
                                <label class="col-lg-1 withoutPadding"> Cpt:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select name='cpt' id='cpt' class="form-control" onchange="loadCptH(this.value)"   >
                                            <?php
                                                $sql_cpt  = "Select * from cpt where type='EVAL' AND DisciplineId=".$datos[0]['DisciplineId'];
                                                
                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {

                                                        print("<option value='".$row["ID"]."'>".$row["cpt"]." </option>"); 

                                                }
                                                    
                                            ?>
                                    </select>   
                                    <input type="hidden" name="cpt_h" id="cpt_h" value="<?=$data[0]['cpt']?>" readonly="readonly"/>
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Units:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select id="unid_of_eval" name="unid_of_eval" style="width: 100px;"  <?php if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>  >
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data[0]['units']==1){?>selected=""<?php }?>>1</option>
                                        <option value='2' <?php if($data[0]['units']==2){?>selected=""<?php }?>>2</option>
                                        <option value='3' <?php if($data[0]['units']==3){?>selected=""<?php }?>>3</option>
                                        <option value='4' <?php if($data[0]['units']==4){?>selected=""<?php }?>>4</option>
                                    </select>                                    
                                </div>
                                
                                <label class="col-lg-1 withoutPadding">Minutes:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select id="min_of_eval" name="min_of_eval" style="width: 100px;" <?php if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>  >
                                        <option value=''>Select..</option>
                                        <option value='15' <?php if($data[0]['minutes']==15){?>selected=""<?php }?>>15</option>
                                        <option value='30' <?php if($data[0]['minutes']==30){?>selected=""<?php }?>>30</option>
                                        <option value='45' <?php if($data[0]['minutes']==45){?>selected=""<?php }?>>45</option>
                                        <option value='60' <?php if($data[0]['minutes']==60){?>selected=""<?php }?>>60</option>
                                    </select>                                                                      
                                </div>
                            </div><br>

                           <div class="row">
                                <label class="col-lg-1 withoutPadding">Phy. Referral: </label>
                                <div class="col-lg-4 withoutPadding">
                                    <?php echo $data[0]['ppcp']?>
                                </div>
                                <label class="col-lg-1 withoutPadding">DOB/Age: </label>
                                <div class="col-lg-4 withoutPadding">
                                    <div id="npi_eval_modal" style="font-size:13px">
                                      &nbsp;&nbsp;
                                        <?php echo $data[0]['pdob']?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-2 withoutPadding">status:</label>
                                <div class="col-lg-10 withoutPadding">
                                    <select id="status_eval" name="status_eval" style="width: 100px;" class="form-control" <?php if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>  >
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data[0]['status_id']==1){?>selected=""<?php }?>>In progress</option>
                                        <option value='2' <?php if($data[0]['status_id']==2){?>selected=""<?php }?>>Active</option>                                        
                                        <option value='3' <?php if($data[0]['status_id']==3){?>selected=""<?php }?>>Inactive</option>
                                    </select>                                                                      
                                </div>
                            </div>
                            
                            </div>
                              <div class="col-lg-3" align="left" style="text-align:left !important">
                     <div align="left" class="col-lg-12 withoutPadding"  style="text-align:left !important;align:left !important ; font-size:18px; font-weight:bold;"> <font color='#1e2c51'>                                                                
                                    <?=$data[0]['company_name']?><br>
                                    <?=$data[0]['facility_address']?><br>
                                    <?=$data[0]['facility_city'].",".$data[0]['facility_state'].",".$data[0]['facility_zip']?><br>
                                (P) <?=$data[0]['facility_phone']?><br>
                                (F) <?=$data[0]['facility_fax']?><br>
                                </font>
                                </div>
                                </div>
                              

                              </div>  
                              <br><hr>

                            
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-lg-12"></div>
                                <div class="col-lg-12">
        <input <?php if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>   type="radio" name="templateCkEditor" id="templateCkEditor1" value="ckeditor" onclick="mostrarCkEditor('template','editor',0);" checked/>&nbsp;CkEditor &nbsp;&nbsp;&nbsp;
        <input <?php if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>   type="radio" name="templateCkEditor" id="templateCkEditor2" value="template" onclick="mostrarTemplate('template','editor');"/>&nbsp;Template
                                </div>                                                                    
                            </div>
                              <div class="row" style="margin-top: 15px;display:none;" id="template">
                                <div class="col-lg-12"></div>
                                <div class="col-lg-12">
                                    <?php if($data[0]['discipline_id'] == '') $data[0]['discipline_id'] = 0;?>
                                    <select id="template_id" name="template_id" onchange="modificarCkeditor(this,'editor',<?=$data[0]['discipline_id']?>,1);">
                                        <option value=''>Select..</option>
                                        <?php
                                        if($data[0]['discipline_id']!= ''){
                                            $templates="SELECT * FROM tbl_templates WHERE type_document_id=1 AND discipline_id=".$data[0]['discipline_id'];
                                            $resultados_tem = ejecutar($templates,$conexion);   
                                            while($row_tem = mysqli_fetch_assoc($resultados_tem)) { 

                                        ?>
                                            <option value='<?=$row_tem['id']?>'><?=$row_tem['name']?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>                                                                
                            </div>
                            <div class="row" style="margin-top: 15px;" >
                                <div class="col-lg-12"></div>
                                <div class="col-lg-12">
                                    <?php 
                                        if(!isset($_GET['id_eval'])){
                                        ?>   
                                            <div id="template_panel" style="display:none">
                                                <div class="panel-group" id="accordion">
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading">
                                                          <h4 class="panel-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Components</a>
                                                          </h4>
                                                        </div>                                                      
                                                        <div id="collapse3" class="panel-collapse collapse in">
                                                            <div  id="components_id"></div><hr>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-success">
                                                        <div class="panel-heading">
                                                          <h4 class="panel-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Components Select</a>
                                                          </h4>
                                                        </div>                                                      
                                                        <div id="collapse4" class="panel-collapse collapse in">
                                                            <div id="newTemplate"></div><hr>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <input type="hidden" name="cadenaComponents" id="cadenaComponents" readonly="readonly">                                            
                                            <div id="cked"><textarea  name="editor" id="editor"></textarea></div>                                             
                                    <?php }else{ ?>  
                                        
                                                <div id="template_panel" style="display:none">
                                                    <div class="panel-group" id="accordion">
                                                        <div class="panel panel-primary">
                                                            <div class="panel-heading">
                                                              <h4 class="panel-title">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Components</a>
                                                              </h4>
                                                            </div>                                                      
                                                            <div id="collapse3" class="panel-collapse collapse in">
                                                                <div  id="components_id"></div><hr>
                                                            </div>
                                                        </div>
                                                        <div class="panel panel-success">
                                                            <div class="panel-heading">
                                                              <h4 class="panel-title">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Components Select</a>
                                                              </h4>
                                                            </div>                                                      
                                                            <div id="collapse4" class="panel-collapse collapse in">
                                                                <div id="newTemplate"></div><hr>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>                                             
                                            <input type="hidden" name="cadenaComponents" id="cadenaComponents" readonly="readonly">                                          
                                            <div id="cked"><textarea <?php if($data[0]['therapist_signed']==1 || $_SESSION['user_id']<>$data[0]['id_user']){?> enable <?php }?>   name="editor" id="editor"><?=$data[0]['ckeditor']?></textarea></div>
                                    <?php } ?>                                   
                                </div>                                                                    
                            </div>
                            
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-lg-offset-2 col-lg-2">

<!--                                    <button type="button" class="btn btn-primary" onclick="signature('signature','signatureEvaluationStatus');"><i class="fa fa-check"></i>&nbsp;Signature</button>-->
                                    <button type="button" class="btn btn-primary" onclick="signature('signature','firma_eval');" <?php if($_SESSION['user_id']!=$data[0]['id_user']){?>disabled=""<?php }?>><i class="fa fa-check"></i>&nbsp;Signature</button>
                                    <input type="hidden" name="signatureEvaluationStatus" id="signatureEvaluationStatus" value="0">

                                </div>
                                <div class="col-lg-3">
                                    <?php 
                                        if(isset($_GET['id_eval']) && $data[0]['therapist_signed']==1){
                                    ?>
                                        <div id="signature">
                                            <?php }else{?>
                                                 <div id="signature" style="display:none">
                                            <?php } ?>
                                                 <?php 
                                                    if(!isset($_GET['id_eval'])){
                                                ?>
                                                        <img src="../../../images/sign/signature.png" style="width: 100px">
                                                    <?php }elseif($data[0]['therapist_signed']==1){?>
                                                        <img src="../../../images/sign/<?=$data[0]['sign_before']?>" style="width: 100px">
                                                    <?php }else{ ?>
                                                        <img src="../../../images/sign/<?=$data[0]['sign_before']?>" style="width: 100px">
                                                    <?php } ?>
                                            </div>
                                            <input type="hidden" name="firma_eval" id="firma_eval"/>
                                            <input type="hidden" name="ruta_firma_eval" id="ruta_firma_eval"/>
                                            <input type="hidden" name="latitud_firma_eval" id="latitud_firma_eval"/>
                                            <input type="hidden" name="longitud_firma_eval" id="longitud_firma_eval"/>
                                        </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-4"  id="to_sign"></div>                                                                
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">AMENDMENT:</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" value="1" name="amendment" id="amendment" onclick="amendmentShow(this)" <?php if($data[0]['id_amendment']!= null && $data[0]['id_amendment']!= ''){$displayA = 'block';?>checked=""<?php }else{$displayA = 'none';}?>/>
                                </div>
                            </div>
                            
                            <div id='elementAmendment' style="display:<?=$displayA?>;">
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <input type="radio" name="templateCkEditorAmendment" id="templateCkEditorAmendment1" onclick="mostrarCkEditor('templateAmendment','editor_amendment',1);" checked/>&nbsp;CkEditor &nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="templateCkEditorAmendment" id="templateCkEditorAmendment" onclick="mostrarTemplate('templateAmendment','editor_amendment');"/>&nbsp;Template
                                    </div>                                                                    
                                </div>
                                <div class="row" style="margin-top: 15px;display:none;" id="templateAmendment">
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <select id="template_amendment_id" name="template_amendment_id" onchange="modificarCkeditor(this,'editor_amendment',<?=$data[0]['discipline_id']?>,1);">
                                            <option value=''>Select..</option>
                                            <?php
                                                $templates="SELECT * FROM tbl_templates WHERE type_document_id=1 AND discipline_id=".$data[0]['discipline_id'];
                                                $resultados_tem = ejecutar($templates,$conexion);   
                                                while($row_tem = mysqli_fetch_assoc($resultados_tem)) { 

                                            ?>
                                                <option value='<?=$row_tem['id']?>'><?=$row_tem['name']?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>                                                                
                                </div><br>
                                <div id="template_panel_amendment" style="display:none">
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">Components</a>
                                              </h4>
                                            </div>                                                      
                                            <div id="collapse6" class="panel-collapse collapse in">
                                                <div id="components_id_amendment"></div><hr>
                                            </div>
                                        </div>
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse7">Components Select</a>
                                              </h4>
                                            </div>                                                      
                                            <div id="collapse7" class="panel-collapse collapse in">
                                                <div id="newTemplateAmendment"></div><hr>
                                            </div>
                                        </div>
                                    </div> 
                                </div>   
                                <div class="row" style="margin-top: 15px;" id="ckeditorAmendment" >
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <div id="cked_amendment"><textarea class="ckeditor" name="editor_amendment" id="editor_amendment"><?=$data[0]['ckeditor_amendment']?></textarea></div>
                                    </div>                                                                    
                                </div>                                
                            </div>
                            
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">MD Signed:</label>
                                <div class="col-lg-1">
                                    <input  type="checkbox" value="1" name="md_signed" id="md_signed" onclick="mdSignature(this,'date_signed','attachment','attachment_register')" <?php if($data[0]['md_signed']==1){ $display = 'block';?>checked=""<?php }else{$display = 'none';}?>/>
                                    <input type="hidden" name="md_signed_hidden" id="md_signed_hidden" value="<?=$data[0]['md_signed']?>">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;display:<?=$display?>;" id="date_signed">
                                <label class="col-lg-2">Date of Signed:</label>
                                <div class="col-lg-5">
                                    <input type="text" name="date_of_signed" id="date_of_signed" class="form-control" placeholder="Date of Signed" value="<?=$data[0]['date_signed']?>"/>                                    
                                </div>
                            </div> 
<!--                            <div class="row" style="margin-top: 15px;display:block;" id="current_document">
                                <label class="col-lg-2">Document:</label>
                                <div class="col-lg-10" id="route_document">
                                    
                                </div>
                            </div>-->
                            <div class="row" style="margin-top: 15px;display:<?=$display?>;" id="attachment_register">
                                <label class="col-lg-2">Document:</label>
                                <div class="col-lg-10">
                                    <a href="../../../<?=$data[0]['route_document']?>" target="_blank"><?=$data[0]['route_document']?></a>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;display:<?=$display?>;" id="attachment" >
                                <label class="col-lg-2">Attachment:</label>
                                <div class="col-lg-5">
                                    <input name="file-1[]" id="fileEvaluation" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                                </div>
                            </div>
                            <hr/>
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

                    <div class="modal-footer">
                        <p></p>
                    </div>
</div>
</body>
</html>
