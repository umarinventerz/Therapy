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

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="../../../js/devoops_ext.js"></script>
    <script>
	
        
        function validar_tipo_status(){
            
            if($('#type_terapist').val() != '' && $('#status_terapist').val() != ''){		
                if($("#type_terapist").val() == 'Therapist'){			
			if($('#type_salary').val() != ''){
				$('#nombre_terapista').load('terapist_status_type.php?type_salary='+$('#type_salary').val()+'&type_terapist='+$('#type_terapist').val()+'&status_terapist='+$('#status_terapist').val());
			}
		}else{
			$('#nombre_terapista').load('terapist_status_type.php?&type_terapist='+$('#type_terapist').val()+'&status_terapist='+$('#status_terapist').val());
		}                 
            }
            
        }
        
    function validar_campos_llenos(){
                    
        if($('#input_date_start').val() != '' && $('#input_date_end').val() != '' && $('#name_terapist').val() != ''){            
            $('#submit').prop('disabled',false);            
        } else {
            $('#submit').prop('disabled',true);   
        }
        
        
    }
    
    function generar_factura(input_date_start,input_date_end,time_pay){
	var valores_check = '';
    
    	$('input:checkbox:checked').map(function() {
    		if(this.value != 'on') {
                    valores_check += this.value+',';
                }
	});

	name_terapist = $('#name_terapist').val();	
	cantHours = $('#cantHours').val();
	if(time_pay == 'Perhour' && cantHours == ''){
		alert('MUST INDICATE HOURS WORKED');
	}else{
		var r = confirm("Are you sure that you want to generate the check?");
		if (r == true) {
		    window.open('../../controlador/factura.php?&identificadores_check='+valores_check+'&name_terapist='+name_terapist+'&input_date_start='+input_date_start+'&input_date_end='+input_date_end+'&cantHours='+cantHours,'factura','width=900, height=800, scrollbars=1, location=no,left=300,top=50');                 
		}	
	}	        
    }	
                
                function mostrar_resultados(){
                
                var datos_formulario = $('#form_find_date').serialize();              
                
                $('#resultado').load('result_find_date_csv.php?'+datos_formulario);
                return false;
                }

                function ver_factura(name_teraphist){
                
                var datos_formulario = $('#form_find_date').serialize();
                $('#resultado').load('result_find_check.php?'+datos_formulario);
                return false;
                }
               	
               	function abrirTerapias(id_check){
               		window.open('ver_terapias.php?id_check='+id_check,'','width=2500px,height=700px,noresize');
               	}
               	/*function showHour(valor){
			arreglo = valor.split("-");
               		if(arreglo[1] == 'Perhour')				
               			$('#amountHour').show();
			else
				$('#amountHour').hide();				
		}*/     
		function showTypeSalary(valor){
			if(valor == 'Therapist'){
				$("#divTypeSalary").show();
			}else{	
				$("#divTypeSalary").hide();
			}
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
		
		<h3>SCHEDULE</h3>
            </div>
        </div>
<br/>



        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">
            <div id="resultado" class="col-sm-12"><?php include ('../payroll/calendario.php'); ?></div>
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
