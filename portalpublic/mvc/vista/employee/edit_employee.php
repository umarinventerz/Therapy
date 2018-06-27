<?php 
	if(!isset($_SESSION)) {
		session_start();
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
<!--<link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />-->

<!--<script src="../../../js/fileinput.js" type="text/javascript"></script>-->
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
<!--<script src="../../../js/bootstrap.min.js"></script>-->
<script src="../../../js/listas.js" type="text/javascript" ></script>


  <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css"> 


    <script type="text/javascript" language="javascript" class="init">  
         
    $(document).ready(function() { 
        $('#therapistlist').DataTable({ 
            dom: 'Bfrtip', 
            buttons: [ 
                'copyHtml5', 
                'excelHtml5', 
                'csvHtml5', 
                'pdfHtml5' 
            ] 
        } ); 
    } ); 
 
 
                                                            
//    $conexion = conectar();
//    $resultado = ejecutar($sql,$conexion);
//    while ($row=mysqli_fetch_array($resultado)){	                                                                    
//                    print("<option value='".utf8_decode($row["terapist_name"])."-".$row["id"]."' >".utf8_decode($row["terapist_name"])."-".$row["licence_number"]." </option>");
//    }

    function loadSelect(where){        
        autocompletar_radio('concat(concat(Last_name,\', \',First_name),\'-\',licence_number) as texto','concat(concat(Last_name,\', \',First_name),\'-\',id) as id','employee','selector',null,null,where,'ORDER BY texto','name_terapist');
    }
    </script> 

 <script type="text/javascript" language="javascript">
    

       function validar_formulario(){
                var parametros_formulario = $('#form_upcoming_appointments').serialize();
                     
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../employee/employee_table.php?"+parametros_formulario);
            
            return false;
        }
        
        </script>
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
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

    <!-- Page Content -->
    <div class="container">

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
<br/>
<div class="row">
	<div class="col-xs-12 col-sm-12">					
				<form class="form-horizontal" id="form_find_date" action="load_employee.php" method="post">										
					<div class="form-group has-feedback centered">
                                        <div class="row">
						<label class="col-sm-2 control-label">ACTIVE</label>
                                                <div class="col-sm-4">
                                                    <input type="checkbox" id="active" name="active" value="1" onclick="loadSelect('status = 1');">
                                                </div>    
                                        </div>
                                        <br/>
                                        <div class="row">
						<label class="col-sm-2 control-label">EMPLOYEE</label>
						<div class="col-sm-4">
                                                    <select name='name_terapist' id='name_terapist'  onchange="validar_campos_llenos();">
                                                            <option value=''>--- SELECT ---</option>
                                                            
                                                           <?php
                                                           //modifique aqui Cesar
                                                            $sql  = "select * from employee where id is not null";
                                                            $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                      
                                                                            print("<option value='".utf8_decode($row["first_name"])."-".$row["id"]."' >".utf8_decode($row["first_name"])." ".utf8_decode($row["last_name"])." </option>");
                                                            }

                                                        ?>     
                                                                                  
                                                    </select> 
						    <input type="hidden" value="edit_submit" id="action" name="action">                                                  
						</div>
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



<form id="form_upcoming_appointments"  onSubmit="return validar_formulario(this);">              
                <div class="row">
                    <div class="col-lg-12">                        
                        <h3 class="page-header">Filters</h3>                        
                    </div>
                </div>   
    
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Salary</font></label>
            <div class="col-sm-8">
                <select id="type_salary" name="type_salary" onchange="validar_formulario()">
                                                    <option value=''>--- SELECT ---</option>                
                                                    <option value='Perdiem'>Perdiem</option>                
                                                     <option value='Salary'>Salary</option>      
                                 
                </select>
                      </div>
        </div>



        <div class="form-group row">
                             <label class="col-sm-2 form-control-label text-right"><font color="#585858">Status</font></label>
                        <div class="col-sm-8">
                           
                                    <select name='status_terapist' id='status_terapist' onchange="validar_formulario()">
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
                    </div> 



         <div class="form-group row">
                             <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Employee</font></label>
                        <div class="col-sm-8">
                           
                                    <select id="type_terapist" name="type_terapist" onchange="validar_formulario()">
                       <option value=''>--- SELECT ---</option>                
                                                        <?php
                                                            $sql  = "select * from employee where id is not null";
                                                            $conexion = conectar();
                                                            $resultado = ejecutar($sql,$conexion);
                                                            while ($row=  mysqli_fetch_assoc($resultado)){                                                                      
                                                                            print("<option value='".$row["first_name"]."' >".utf8_decode($row["first_name"])." </option>");
                                                            }

                                                        ?>
                                         </select>  
                                                                                
                        </div>  
        </div> 






        
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
        </div>
</form> 


 



        <div id="resultado" class="text-center"></div>
 



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
	//LoadSelect2Script(DemoSelect2);
        LoadSelect2ScriptExt(function(){

            $('#name_terapist').select2();
            $('#type_salary').select2();
            $('#status_terapist').select2();
            $('#type_terapist').select2();            

        });
    
	// Load example of form validation
	LoadBootstrapValidatorScript(DemoFormValidator);
	// Add drag-n-drop feature to boxes
	WinMove();
});
loadSelect('1');
</script>
</html>
