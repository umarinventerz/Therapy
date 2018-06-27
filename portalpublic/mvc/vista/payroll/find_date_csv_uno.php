<?php
session_start();
require_once("../../../conex.php");
require_once("../../../queries.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
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
    
    <title>.: KIDWORKS THERAPY :.</title>
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
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>

<script src="../../../js/devoops_ext.js"></script>
    <script>







                
                function mostrar_resultados(){
                
                var datos_formulario = $('#form_find_date').serialize();              
                
                $('#resultado1').load('result_find_date_csv_uno.php?'+datos_formulario);
                return false;
                }



    </script>
</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

    <!-- Page Content -->
    <div class="container" style="width:90%">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <br>
<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
				 			  
				</ul>
		      </li>		
		

            </div>
        </div>

    </div>


<br/>

    <div id="collapse1" class="panel-collapse collapse in">
<div class="row">
	<div class="col-xs-12 col-sm-12">					
				<form class="form-horizontal" id="form_find_date" onSubmit="return mostrar_resultados(this);">
<div class="form-group">




    <label class="col-sm-2 control-label">Start Date</label>
    <div class="col-sm-2">
        <input type="text" id="input_date_start" name="input_date_start" class="form-control" placeholder="Date" readonly="true" onchange="validar_campos_llenos();">
        <span class="fa fa-calendar txt-danger form-control-feedback"></span>
    </div>
    <label class="col-sm-1 control-label">End Date</label>
    <div class="col-sm-2">
        <input type="text" id="input_date_end" name="input_date_end" class="form-control" placeholder="Date" readonly="true" onchange="validar_campos_llenos();">
        <span class="fa fa-calendar txt-danger form-control-feedback"></span>
    </div>

    <div class="col-sm-2">
        <button type="submit" class="btn btn-primary btn-label-left" style="width: 100px;height: 30px" value="submit" name="submit" id="submit" >
            SUBMIT
        </button>
    </div>

</div>








                                        
				</form>					
	</div>
</div>


        <!-- /.row -->
        <div class="row">
            <div id="resultado1" class="col-sm-12"></div>
        </div>
        <!-- Related Projects Row -->
        <div class="row">
            <div id="resultado" class="col-sm-12"><?php include ('calendario_uno.php'); ?></div>
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; KIDWORKS THERAPY 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->
</body>
<script type="text/javascript">
// Run Select2 plugin on elements
function DemoSelect2(){
	$('#s2_with_tag').select2({placeholder: "Select Status"});	
	$('#status').select2();
	$('#timePay').select2();
}
// Run timepicker
function DemoTimePicker(){
	$('#input_time').timepicker({setDate: new Date()});
}
$(document).ready(function() {
	// Create Wysiwig editor for textare
	TinyMCEStart('#wysiwig_simple', null);
	TinyMCEStart('#wysiwig_full', 'extreme');
	// Add slider for change test input length
	FormLayoutExampleInputLength($( ".slider-style" ));
	// Initialize datepicker
	$('#input_date_start').datepicker({setDate: new Date()});
	$('#input_date_end').datepicker({setDate: new Date()});
	// Load Timepicker plugin
	LoadTimePickerScript(DemoTimePicker);
	// Add tooltip to form-controls
	$('.form-control').tooltip();
	LoadSelect2ScriptExt(DemoSelect2);
	// Load example of form validation
	LoadBootstrapValidatorScript(DemoFormValidator);
	// Add drag-n-drop feature to boxes
	WinMove();
        
        $('#name_terapist').attr('disabled',true);
      
});
</script>
</html>
