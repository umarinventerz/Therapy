<?php

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

?>
      
 <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<link href="../../../css/portfolio-item.css" rel="stylesheet">
<script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>
<script src="../../../js/funciones.js"></script>

<link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
<link href="../../../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="../../../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
<link href="../../../plugins/xcharts/xcharts.min.css" rel="stylesheet">
<link href="../../../plugins/select2/select2.css" rel="stylesheet">
<link href="../../../plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
<link href="../../../css/style_v1.css" rel="stylesheet">
<link href="../../../plugins/chartist/chartist.min.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<link href="../../../css/sweetalert2.min.css" rel="stylesheet">

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>
    <script type="text/javascript" language="javascript">

       function validar_formulario(){
                var parametros_formulario = $('#form_consultar_notas').serialize();
                           
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../notas/result_notas.php?"+parametros_formulario);

            return false;
        }
                                
        </script>

    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br><br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">    


<form id="form_consultar_notas"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">NOTES</font></h3>   </div>
            <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("../../../includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"><img class="img-responsive" src="../../../imagenes/imagen_sistema.jpg" alt="" width="50px" height="50px"></a></div>
        </div>
<div class="form-group row">              
            <div class="col-sm-12 text-center" style="float: none;margin: 0 auto;">
                <label><font color="#585858">DISCIPLINE</font></label>
            </div>
            <div class="col-lg-2"></div>
            <div class="col-sm-8 text-center">
                ST <input type="checkbox" name="discipline_st" id="discipline_st" value="ST">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                OT <input type="checkbox" name="discipline_ot" id="discipline_ot" value="OT">            
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                PT <input type="checkbox" name="discipline_pt" id="discipline_pt" value="PT">
            </div>
        </div>  
    <hr>
        <div class="form-group row">              
            <div class="col-sm-2"></div>
            <div class="col-sm-4 text-center">
                <label><font color="#585858">PATIENT</font></label>
            </div>
            <div class="col-sm-4 text-center">
                <label><font color="#585858">TYPE REPORT</font></label>
            </div>                        
            <div class="col-sm-2"></div>
        </div>
    <div class="form-group row">   
            <div class="col-lg-2"></div>
            <div class="col-sm-4 text-center">
                <select name="patients" id="patients">
                    <option value="">Seleccione..</option>
                </select>                
            </div>  
            <div class="col-sm-4 text-center">
                <select name="type_report" id="type_report">
                    <option value="">Seleccione..</option>
                     <?php
                            $sql  = "select distinct(type_report) as type_report from tbl_notes";
                            $conexion = conectar();
                            $resultado = ejecutar($sql,$conexion);
                            while ($row=  mysqli_fetch_assoc($resultado)){	                                                                    
                                            print("<option value='".$row["type_report"]."' >".utf8_decode($row["type_report"])." </option>");
                            }

                        ?>
                </select>                
            </div>   
            <div class="col-lg-2"></div>
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
                <input type="text" id="input_date_start" name="input_date_start" class="form-control" placeholder="Start Date" readonly="true" >
                    <span class="fa fa-calendar txt-danger form-control-feedback"></span>               
            </div>  
            <div class="col-sm-4 text-center">
                    <input type="text" id="input_date_end" name="input_date_end" class="form-control" placeholder="End Date" readonly="true" >
                    <span class="fa fa-calendar txt-danger form-control-feedback"></span>             
            </div>   
            <div class="col-lg-2"></div>
        </div>    
    
           
  
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-6" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
            <div class="col-sm-2" align="left"></div>
        </div>
</form>      
        <script>
            function DemoTimePicker(){
                    $('#input_time').timepicker({setDate: new Date()});
            }
            $(document).ready(function() {

                    $('#input_date_start').datepicker({setDate: new Date()});	
                    $('#input_date_start').prop('readonly', true);
                    
                    $('#input_date_end').datepicker({setDate: new Date()});	
                    $('#input_date_end').prop('readonly', true);
                    
            });   
            
            
                LoadSelect2ScriptExt(function(){
                    
                    $('#patients').select2();
                    $('#type_report').select2();

                });              
            
            autocompletar_radio('CONCAT(Last_name,\' \', First_name)','Pat_id','patients','selector',null,null,null,null,'patients');            
            //autocompletar_radio('type_report','type_report','tbl_notes','selector',null,null,null,null,'type_report');            
            
            
        </script>  
        <br><br><br>
         <div id="resultado" class="col-lg-12"></div>        
      