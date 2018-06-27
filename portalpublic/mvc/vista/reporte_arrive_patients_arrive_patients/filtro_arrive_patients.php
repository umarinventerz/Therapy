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
                var parametros_formulario = $('#form_arrive_patients').serialize();
                               
                $("#resultado").empty().html('<img src="../../../imagenes/loader.gif" width="30" height="30"/>');                                
                $("#resultado").load("../reporte_arrive_patients_arrive_patients/grafico_arrive_patients.php?"+parametros_formulario);
            
            return false;
        }
        
        </script>

    <?php $perfil = $_SESSION['perfil']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
               <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>


<form id="form_arrive_patients"  onSubmit="return validar_formulario(this);">      
        
        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD">Report Arrived Patients&nbsp;</font></h3>   </div>                  
        </div>  
    
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Tipo de Grafico</font></label>
            <div class="col-sm-8">
                <select class="form-control" id="tipo_grafico" name="tipo_grafico" onchange="validar_formulario()">
                    <option value="Column3D">Barra 3D  &nbsp;&nbsp;&nbsp; </option>                   
                </select>
                      </div>
        </div>    


                     
    
    <div class="form-group row">   
    <label class="col-sm-2 form-control-label text-right"><font color="#585858">Date</font></label> 
         
            <div class="col-sm-4 text-center">
                <input type="text" id="start_date" name="start_date" class="form-control" placeholder="Start Date" readonly="true">
                    <span class="fa fa-calendar txt-danger form-control-feedback"></span>               
            </div>  
           
            <div class="col-lg-2"></div>
        </div>                


<script>
            function DemoTimePicker(){
                    $('#input_time').timepicker({setDate: new Date()});
            }
            $(document).ready(function() {

                    $('#start_date').datepicker({setDate: new Date()}); 
                    $('#start_date').prop('readonly', false);
                    
                                                                                      
                    
            });   
            
                   
        
                 
        var date = new Date();
        $('#start_date').val((date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear());
       // $('#end_date').val((date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear());             
           
        setTimeout(function(){
            validar_formulario();             
        },500);
        
        </script> 

        
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">REPORT</button> </div>
        </div>
</form>


        </div>
    </div>


        <div id="resultado" class="text-center"></div>
        <br><br>
        <footer> 
            <div class="row"> 
                <div class="col-lg-12 text-center"> 
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
                </div> 
            </div> 
            <!-- /.row --> 
        </footer>

