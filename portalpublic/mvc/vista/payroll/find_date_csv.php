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
		    window.open('../../controlador/payroll/factura.php?&identificadores_check='+valores_check+'&name_terapist='+name_terapist+'&input_date_start='+input_date_start+'&input_date_end='+input_date_end+'&cantHours='+cantHours,'factura','width=900, height=800, scrollbars=1, location=no,left=300,top=50');
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
		
		<h3>SELECT EMPLOYEE</h3>
            </div>
        </div>

    </div>


<br/>

    <div id="collapse1" class="panel-collapse collapse in">
<div class="row">
	<div class="col-xs-12 col-sm-12">					
				<form class="form-horizontal" id="form_find_date" onSubmit="return mostrar_resultados();">										
<div class="form-group">
						<label class="col-sm-1 control-label">EMPLOYEE</label>
						<div class="col-sm-2">
                                                    <select name='type_terapist' id='type_terapist' class='form-control' onchange="validar_tipo_status();showTypeSalary(this.value);">
                                                            <option value=''>--- SELECT ---</option>				
                                                        <?php
                                                            $sql  = "select distinct kind_employee from employee where kind_employee is not null and kind_employee <> '' AND kind_employee <> 'Administrative-Therapist'";
                                                            $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){	                                                                    
                                                                            print("<option value='".$row["kind_employee"]."' >".utf8_decode($row["kind_employee"])." </option>");
                                                            }

                                                        ?>

                                                    </select>                                                   
						</div>	
                                                <label class="col-sm-1 control-label">STATUS</label>
						<div class="col-sm-1">
                                                    <select name='status_terapist' id='status_terapist' class='form-control' onchange="validar_tipo_status();">
                                                            <option value=''>--- SELECT ---</option>				
                                                        <?php
                                                            $sql  = "select distinct status from employee where status is not null";
                                                            $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=mysqli_fetch_array($resultado)){
									    if($row["status"] == 1) $status = 'Active';
	    								    else $status = 'Inactive';	                                                                    
                                                                            print("<option value='".$row["status"]."' >".$status." </option>");
                                                            }

                                                        ?>

                                                    </select>                                                   
						</div>
    <label class="col-sm-1 control-label"> NAME </label>
    <div class="col-sm-2" id="nombre_terapista">
        <select name='name_terapist' id='name_terapist' class='form-control' onchange="validar_campos_llenos();">
            <option value=''>--- SELECT ---</option>
            <?php
            $sql  = "Select concat(last_name,', ',first_name) as terapist_name,time_pay,licence_number from employee order by last_name ";
            $conexion = conectar();
            $resultado = ejecutar($sql,$conexion);
            while ($row=mysqli_fetch_array($resultado)){
                print("<option value='".$row["licence_number"]."-".$row["time_pay"]."' >".utf8_decode($row["terapist_name"])."-".$row["licence_number"]." </option>");
            }

            ?>

        </select>
    </div>

    <label class="col-sm-1 control-label">Start Date</label>
    <div class="col-sm-1">
        <input type="text" id="input_date_start" name="input_date_start" class="form-control" placeholder="Date" readonly="true" onchange="validar_campos_llenos();">
        <span class="fa fa-calendar txt-danger form-control-feedback"></span>
    </div>
    <label class="col-sm-1 control-label">End Date</label>
    <div class="col-sm-1">
        <input type="text" id="input_date_end" name="input_date_end" class="form-control" placeholder="Date" readonly="true" onchange="validar_campos_llenos();">
        <span class="fa fa-calendar txt-danger form-control-feedback"></span>
    </div>


					</div>

                    <div class="form-group"  style="display:none;" id="divTypeSalary">

						<label class="col-sm-1 control-label"> TYPE SALARY </label>
						<div class="col-sm-2">
		                                    <select name='type_salary' id='type_salary' class='form-control' onchange="validar_tipo_status();">
		                                            <option value=''>--- SELECT ---</option>				
		                                            <option value='Perdiem'>Perdiem</option>				
							    <option value='Salary'>Salary</option>				
		                                    </select>                                                   
						</div>
                        <div class="col-sm-9">
                        </div>
					</div> 					




					<div class="clearfix"></div>
					<div class="form-group">												
                                            <div class="col-sm-5"></div>
                                                <div class="col-sm-2">
							<button type="submit" class="btn btn-primary btn-label-left" style="width: 120px" value="submit" name="submit" id="submit" disabled>							
								SUBMIT
							</button>
						</div>	
						<div class="col-sm-2">
                                                    <button type="button" class="btn btn-primary btn-label-left" style="width: 120px" value="reset" name="reset" id="reset" onclick= "window.location.href = 'find_date_csv.php';">							
								Reset
							</button>
						</div>				
					</div>
                                        
				</form>					
	</div>
</div>


        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">
            <div id="resultado" class="col-sm-12"><?php include ('calendario.php'); ?></div>
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
