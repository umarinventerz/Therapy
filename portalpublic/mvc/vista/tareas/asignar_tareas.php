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

if(isset($_GET['id_tareas'])){
    $accion = 'modificar';
} else {
    $accion = 'insertar';
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
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>

<script src="../../../js/devoops_ext.js"></script>
    <script type="text/javascript" language="javascript">

function Validar_Formulario_Asignar_Tareas() {              
         
    var nombres_campos = '';
    
  if($('#user_system').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * User System</td></tr></table>';

        }
  if($('#start_date').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Start Date</td></tr></table>';

        }
  if($('#end_date').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * End Date</td></tr></table>';

        }
  if($('#tareas').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Task</td></tr></table>';

        }     
        
/*tabladinamicasvalidacion*/

    if(nombres_campos != ''){ 
            
        swal({
          title: "<h3><b>Complete the following fields<b></h3>",          
          type: "info",
          html: "<h4>"+nombres_campos+"</h4>",
          showCancelButton: false,          
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        });
            
            return false; 
        
                         } else {

                        var campos_formulario = $("#form_asignar_tareas").serialize();
                        
                        $.post(
                                "../../controlador/tareas/gestionar_tareas.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                                 
                                    mostrar_datos(resultado_controlador);                                    
                                
                                
                                    
                                },
                                "json" 
                                );
                        
                        return false;
                    }
            }            
            
            function mostrar_datos(resultado_controlador) {                                         
           
            $('#resultado').html(resultado_controlador.resultado);
                      
/*pdf*/

/*word*/


            swal({
                title: resultado_controlador.mensaje,
                text: "Deseas Consultar los Registros Insertados?",
                type: "success",
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: "#d33",   
                confirmButtonText: "Consultar",   
                closeOnConfirm: false,
                closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                       
                    window.location.href = '../tareas/consultar_tareas.php?&consultar=si';  
                  }else window.location.href = 'asignar_tareas.php';  

                    });             
                                            
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


<form id="form_asignar_tareas"  onSubmit="return Validar_Formulario_Asignar_Tareas();">      

        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">Assign Task</font></h3>   </div>
            <div class="col-sm-2"></div>
        </div>
        <div class="form-group row">              
            <div class="col-sm-2"></div>
            <div class="col-sm-8 text-center">
                <label><font color="#585858">USER</font></label>
            </div>                                   
            <div class="col-sm-2"></div>
        </div>
        <div class="form-group row">   
            <div class="col-lg-2"></div>             
            <div class="col-sm-8 text-center">
                <select name="user_system" id="user_system" id="type_report">
                    <option value="">Select..</option>
                </select> 
                <input type="hidden" id="user_registrer" name="user_registrer" value="<?php echo $_SESSION['user_id']; ?>">
                <input type="hidden" id="accion" name="accion" value="<?php echo $accion; ?>">
            </div>   
            <div class="col-lg-2"></div>
        </div>  
    <hr>        
    
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
            <div class="col-lg-2"></div>
            <div class="col-sm-8 text-center">
                <textarea id="tareas" name="tareas" class="form-control" placeholder="TASK" rows="3" cols="20" ></textarea>
            </div>           
            <div class="col-lg-2"></div>
        </div>
           
  
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-6" align="left"> <button type="submit" class="btn btn-primary text-left">Save</button> </div>
            <div class="col-sm-2" align="left"></div>
        </div>
</form>      
        <script>
            function DemoTimePicker(){
                    $('#input_time').timepicker({setDate: new Date()});
            }
            $(document).ready(function() {

                    $('#start_date').datepicker({setDate: new Date()});	
                    $('#start_date').prop('readonly', true);
                    
                    $('#end_date').datepicker({setDate: new Date()});	
                    $('#end_date').prop('readonly', true);                                        
                                        
            });   
            

                LoadSelect2ScriptExt(function(){                    
                    $('#user_system').select2();                   
                });              
         
            <?php if($_SESSION['user_type'] != 10 && $_SESSION['user_type'] != 11) {              
            $usuario_session = "'".$_SESSION['user_id']."'"; } else { $usuario_session = 'null'; }?>
                              
            autocompletar_radio('CONCAT(Last_name,\' \', First_name) as texto','user_id','user_system','selector',<?php echo $usuario_session;?>,null,null,null,'user_system');                                  
            

        </script>  
        <br><br><br>
         <div id="resultado" class="col-lg-12 text-center"></div>        
      