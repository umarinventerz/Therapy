<?php
session_start();
require_once("conex.php");
require_once("queries.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
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
<link rel="stylesheet" href="css/bootstrap.min.css" type='text/css'/>
<link href="css/portfolio-item.css" rel="stylesheet">
<link href="css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<script src="js/bootstrap.min.js"></script>
<script src="js/fileinput.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript" src="js/AjaxConn.js"></script>

<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">
<link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
<link href="plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
<link href="plugins/xcharts/xcharts.min.css" rel="stylesheet">
<link href="plugins/select2/select2.css" rel="stylesheet">
<link href="plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
<link href="css/style_v1.css" rel="stylesheet">
<link href="plugins/chartist/chartist.min.css" rel="stylesheet">

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<script src="plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="plugins/tinymce/tinymce.min.js"></script>
<script src="plugins/tinymce/jquery.tinymce.min.js"></script>
<!-- All functions for this theme + document.ready processing -->
<script src="js/devoops.js"></script>
    <script>
	
    function validar_campos_llenos(){
        
        if($('#name_terapist').val() != ''){            
            $('#submit').prop('disabled',false);            
        } else {
            $('#submit').prop('disabled',true);   
        }
        
        
    }
    
    function generar_factura(){
		
                var datos_formulario = $('#form_find_date').serialize();
                
                window.open('factura.php?'+datos_formulario,'factura','width=700, height=800, scrollbars=1, location=no,left=300,top=50'); 
                
	}	
    </script>
</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "nav_bar.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <br>
<img class="img-responsive portfolio-item" src="images/LOGO_1.png" alt="">
				 			  
				</ul>
		      </li>		
		
		<h3>SELECT EMPLOYEE</h3>
            </div>
        </div>
<br/>
<div class="row">
	<div class="col-xs-12 col-sm-12">					
				<form class="form-horizontal" id="form_find_date" action="load_employee.php" method="post">										
					<div class="form-group has-feedback centered">
						<label class="col-sm-2 control-label">EMPLOYEE</label>
						<div class="col-sm-4">
                                                    <select name='name_terapist' id='name_terapist' class='form-control' onchange="validar_campos_llenos();">
                                                            <option value=''>--- SELECT ---</option>				
                                                        <?php
                                                            $sql  = "Select Distinct concat(first_name,' ',last_name) as terapist_name,id,licence_number from employee order by first_name ";
                                                            $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=mysqli_fetch_array($resultado)){	                                                                    
                                                                            print("<option value='".utf8_decode($row["terapist_name"])."-".$row["id"]."' >".utf8_decode($row["terapist_name"])."-".$row["licence_number"]." </option>");
                                                            }

                                                        ?>

                                                    </select> 
						    <input type="hidden" value="edit_submit" id="action" name="action">                                                  
						</div>																
					</div>  					                                   	                                  
					<div class="clearfix"></div>
					<div class="form-group">												
						<div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left" value="submit" name="submit" id="submit" disabled>
							<span><i class="fa fa-clock-o"></i></span>
								Edit
							</button>
						</div>
					</div>
				</form>					
	</div>
</div>
        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">

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
});
</script>
</html>
