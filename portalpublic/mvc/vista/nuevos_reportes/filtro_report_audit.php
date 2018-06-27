<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo "<script>alert('Must LOG IN First')</script>";
	echo "<script>window.location='../../../index.php';</script>";
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo "<script>alert('PERMISION DENIED FOR THIS USER')</script>";
		echo "<script>window.location='../../home/home.php';</script>";
	}
}
?>
        
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>
    
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/portfolio-item.css" rel="stylesheet">    
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">  
    <link href="../../../css/dataTables/dataTables.bootstrap.css"rel="stylesheet" type="text/css">
    <link href="../../../css/dataTables/buttons.dataTables.css"rel="stylesheet" type="text/css">    
    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>    
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js/devoops_ext.js" type="text/javascript"></script>    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../js/AjaxConn.js" type="text/javascript" ></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>    
    <script src="../../../js/promise.min.js" type="text/javascript"></script>    
    <script src="../../../js/funciones.js" type="text/javascript"></script>        
    <script src="../../../js/dataTables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../../../js/resources/shCore.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.buttons.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/buttons.html5.js" type="text/javascript"></script>    


<!-- ################### PARA EL CALENDARIO EN LAS GRAFICAS######################-->
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>

    <script type="text/javascript" language="javascript">
    

       function validar_formulario(){
                var tipo_reporte=$("#tipo_grafico").val();
                $("#type_report").val(tipo_reporte);
                var parametros_formulario = $('#form_report_audit').serialize();
                     
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../nuevos_reportes/grafico_report_audit.php?"+parametros_formulario);
            
            return false;
        }
        
        </script>
  
    
    
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">        
    <div class="row">
        <div class="col-lg-12">
            <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">                        
            <h3 class="page-header">Report Audit</h3>                        
        </div>
    </div>

    
     
    <div id="content">  
        <br>
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Audit</font></label>
            <div class="col-sm-8">
                <select class="form-control" id="tipo_grafico" name="tipo_grafico"> 
                         <option value="all">- ALL -</option>
                        <option value="report_amount">REPORTS</option>     
                        <option value="referral">Referral</option>
                        <option value="patients">Patients</option>     
                        <option value="authorizations">Authorizations</option>
                        <option value="signed_doctor">Signed Doctor</option>     
                        <option value="prescriptions">Prescriptions</option>
                       
                </select>
            </div>
        </div>
        <form id="form_report_audit"  onSubmit="return validar_formulario(this);">              
                <input type="hidden" name="type_report" id="type_report"/>
                <br>
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label text-right"><font color="#585858">Tipo de Grafico</font></label>
                    <div class="col-sm-8">
                        <select class="form-control" id="tipo_grafico" name="tipo_grafico" onchange="validar_formulario()">                    
                            <option value="Column3D">Barra 3D  &nbsp;&nbsp;&nbsp; </option>     
                            <option value="Pareto3D">Pareto 3D  &nbsp;&nbsp;&nbsp; </option>   
                        </select>
                    </div>
                </div>    

                <div class="form-group row">
                        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Time</font></label>
                        <div class="col-sm-8">
                            <select id="calendar" name="calendar" class='form-control' onchange="validar_formulario()">
                                <option value="days" <?php echo ($calendar=='days')?'selected':''; ?>>---Days---</option>
                                <option value="weeks" <?php echo ($calendar=='weeks')?'selected':''; ?>>Weeks</option>
                                <option value="months" <?php echo ($calendar=='months')?'selected':''; ?>>Months</option>
                            </select>
                        </div>  
                </div> 

                <div class="form-group row">              
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4 text-center">
                        <label><font color="#585858">Start Date</font></label>
                    </div>
                    <div class="col-sm-4 text-center">
                        <label><font color="#585858">End Date</font></label>
                    </div>                        
                    <div class="col-sm-2"></div>
                </div>          

                <div class="form-group row">   
                    <div class="col-lg-2"></div>
                    <div class="col-sm-4 text-center">
                        <input type="text" id="start_date" name="start_date" class="form-control" placeholder="Start Date" readonly="true">
                            <span class="fa fa-calendar txt-danger form-control-feedback"></span>               
                    </div>  
                    <div class="col-sm-4 text-center">
                            <input type="text" id="end_date" name="end_date" class="form-control" placeholder="End Date" readonly="true">
                            <span class="fa fa-calendar txt-danger form-control-feedback"></span>             
                    </div>   
                    <div class="col-lg-2"></div>
                </div>                

                <div class="form-group row">
                    <div class="col-sm-2" align="left"></div>
                    <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
                </div>
        </form>
        <div id="resultado" class="text-center"></div>
        
    </div>

<script>
    function DemoTimePicker(){
        $('#input_time').timepicker({setDate: new Date()});
    }
    
    /*function type_report(){
        var tipo_reporte=$("#tipo_grafico").val();
        $("#type_report").val(tipo_reporte);
    }*/
    $(document).ready(function() {            
            $('#start_date').datepicker({setDate: new Date()}); 
            $('#start_date').prop('readonly', true);

            $('#end_date').datepicker({setDate: new Date()});   
            $('#end_date').prop('readonly', true);                                                                              

    });   
               
    var date = new Date();
    $('#start_date').val((date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear());
    $('#end_date').val((date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear());             

    setTimeout(function(){
        validar_formulario();             
    },500);
        
</script>        