<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
	}
}


?>
      
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<link href="../../../css/portfolio-item.css" rel="stylesheet">
<script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>

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

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="../../../js/devoops_ext.js"></script>    
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css"/>        
    <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>  
    
    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css"> 
    <script type="text/javascript" language="javascript">
    
       function validar_formulario(){
                var parametros_formulario = $('#form_consultar_horas').serialize();
                               
                $("#resultado").empty().html('<img src="../../../imagenes/loader.gif" width="30" height="30"/>');                                
                $("#resultado").load("../assistance/result_horas.php?"+parametros_formulario);
            
            return false;
        }
                            
        </script>

    <?php $perfil = $_SESSION['perfil']; include "../nav_bar/nav_bar.php"; ?>
    <br><br><br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../imagenes/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">  

<form id="form_consultar_horas"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD">Report of hour per employee</font></h3>   </div>                  
        </div>                    
                   
        <div class="form-group row">                              
        <div class="col-lg-2"></div>            
            <div class="col-sm-4 text-center">
                <label><font color="#585858">EMPLOYEE</font></label>
            </div>         
        <div class="col-lg-2"></div>
    </div>
    
       <div class="form-group row">        
            <div class="col-lg-2"></div>                   
            <div class="col-sm-4 text-center">
                <select id="employee" name="employee"><option value="">Seleccione..</option></select>
            </div>
            <div class="col-lg-2"></div>
        </div>
    
    
    
    
    <hr>
        <div class="form-group row">
                           
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Fecha Inicio</font></label>
            
                <div class="col-sm-3"><input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" placeholder=""  value=""></div>                                           
              
                   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Fecha Fin</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="fecha_fin" name="fecha_fin" placeholder=""  value=""></div>                                                               
                                    
                                        </div>                                                                                       
                             </div>
                           
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
        </div>
</form>

        </div>
    </div>


        
        <div id="resultado" class="col-lg-12"></div>
        <br><br>
        <footer> 
            <div class="row"> 
                <div class="col-lg-12 text-center"> 
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
                </div> 
            </div> 
            <!-- /.row --> 
        </footer>              
<script>
    $(document).ready(function() {
            $('#fecha_inicio').datepicker({setDate: new Date(), format: 'yyyy-mm-dd'});	
            //$('#fecha_inicio').prop('readonly', false);

            $('#fecha_fin').datepicker({setDate: new Date(), format: 'yyyy-mm-dd'});	
            //$('#fecha_fin').prop('readonly', true);                                              
    }); 
            LoadSelect2ScriptExt(function(){

                    $('#employee').select2();                
                });
            autocompletar_radio('concat(last_name,\', \',first_name) as texto','id','employee','selector',null,null,null,null,'employee');                         
//            
                </script>

      <?php if(isset($_GET['consultar']) && $_GET['consultar'] == 'si') { echo '<script>validar_formulario();</script>'; } ?>


        