         
<?php
error_reporting(0);
session_start();
require_once("../../../conex.php");

if(!isset($_SESSION['user_id'])){ ?>
	<script>alert('MUST LOG IN')</script>
	<script>window.location="../../../index.php";</script>
<?php }


if(isset($_GET['id_treatments'])){ 
    
    $accion = 'modificar';
    $titulo = 'Modificar';
    $generar_input = '<input type="hidden" id="id_treatments" Name="id_treatments" value="'.$_GET['id_treatments'].'">';
} else {
    $accion = 'insertar';
    $titulo = 'Registrar';
    $generar_input = null;
}

//echo $_GET['campo_5'];die;
$conexion = conectar();
if(isset($_GET['campo_9'])){
    $sql = 'SELECT id FROM employee WHERE licence_number = \''.str_replace('|',' ',$_GET['license_number']).'\';';

    $resultadoemp = ejecutar($sql,$conexion);

    $employee_id = '';
    while ($row_emp=mysqli_fetch_array($resultadoemp)) {	

        $employee_id = $row_emp['id'];

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
  
    <script type="text/javascript" language="javascript">

    function Validar_Formulario_Gestion_Treatments(nombre_formulario) {              
         
    var nombres_campos = '';
    
    <?php  if(!isset($_GET['id_treatments'])){ ?>
                          if($('#id_treatments').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Id Treatments</td></tr></table>';
                                
        }
        <?php } ?>
                        
                  if($('#campo_1').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Treatments Date</td></tr></table>';
                                
        }
          if($('#campo_2').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Treatments Place</td></tr></table>';
                                
        }
//          if($('#campo_3').val() == ''){
//                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 3</td></tr></table>';
//                                
//        }
//          if($('#campo_4').val() == ''){
//                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 4</td></tr></table>';
//                                
//        }
          if($('#campo_5').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient Id</td></tr></table>';
                                
        }
          if($('#campo_6').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient Name</td></tr></table>';
                                
        }
          if($('#campo_7').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient Insurance</td></tr></table>';
                                
        }
          if($('#campo_8').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 8</td></tr></table>';
                                
        }
          if($('#campo_9').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Therapyst Name</td></tr></table>';
                                
        }
          if($('#license_number').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * License Number</td></tr></table>';
                                
        }
          if($('#campo_10').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Discipline</td></tr></table>';
                                
        }
          if($('#campo_11').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 11</td></tr></table>';
                                
        }
//          if($('#campo_12').val() == ''){
//                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 12</td></tr></table>';
//                                
//        }
//          if($('#campo_13').val() == ''){
//                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 13</td></tr></table>';
//                                
//        }
//          if($('#campo_14').val() == ''){
//                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 14</td></tr></table>';
//                                
//        }
          if($('#campo_15').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Minutes</td></tr></table>';
                                
        }
//          if($('#campo_16').val() == ''){
//                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 16</td></tr></table>';
//                                
//        }
          if($('#campo_17').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 17</td></tr></table>';
                                
        }
          if($('#campo_18').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 18</td></tr></table>';
                                
        }
          if($('#campo_19').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 19</td></tr></table>';
                                
        }
          if($('#campo_20').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 20</td></tr></table>';
                                
        }
          if($('#campo_21').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 21</td></tr></table>';
                                
        }
          if($('#campo_22').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 22</td></tr></table>';
                                
        }
          if($('#campo_23').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 23</td></tr></table>';
                                
        }
          if($('#campo_24').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Campo 24</td></tr></table>';
                                
        }
          if($('#pay').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Pay</td></tr></table>';
                                
        }
          if($('#adults_progress_notes').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Adults Progress Notes</td></tr></table>';
                                
        }
          if($('#pedriatics_progress_notes').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Pedriatics Progress Notes</td></tr></table>';
                                
        }
           
    if(nombres_campos != ''){ 
            
        swal({
          title: "<h3><b>Complet the follow fields<b></h3>",          
          type: "info",
          html: "<h4>"+nombres_campos+"</h4>",
          showCancelButton: false,
          animation: "slide-from-top",
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        });
            
            return false; 
        
                         } else {  

                        var campos_formulario = $("#form_gestion_treatments").serialize();
                        
                        $.post(
                                "../../controlador/treatments/gestionar_treatments.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    mostrar_datos(resultado_controlador);
                                    resetear_formulario(nombre_formulario);
                                    
                                },
                                "json" 
                                );
                        
                        return false;
                    }
            }            
            
            function mostrar_datos(resultado_controlador) {                                         
           
            $('#resultado').html(resultado_controlador.resultado);
                      
            swal({
                title: resultado_controlador.mensaje,
                text: "Do you want to see the registered data?",
                type: "success",
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: "#d33",   
                confirmButtonText: "Consultar",   
                closeOnConfirm: false,
                closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                    
                    $( "#conexion" ).load("../treatments/consultar_treatments.php?&consultar=si");                    
                  }
                  else{
                      window.location.href = "../treatments/registrar_treatments.php";
                  }
                    });             
                                            
           }    

            function consultar_employee(valor,identificador){
              if(valor != ''){
                    $.post(
                        "../../vista/employee/consultar_employee.php",
                        '&emp_id='+valor,
                        function (resultado_controlador) {

                           $('#'+identificador).val(resultado_controlador.licence_number);
                           $('#therapyst_name').val(resultado_controlador.therapyst_name);
                           
                        },
                        "json" 
                    );
              }else{
                  
                  $('#'+identificador).val('');
              }
                
                        
                        return false;                                              
            } 
       
        </script>

<body>
    
    <?php $perfil = $_SESSION['perfil']; include "../nav_bar/nav_bar.php"; ?>
    <br><br><br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../imagenes/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">

<form id="form_gestion_treatments" onSubmit="return Validar_Formulario_Gestion_Treatments('form_gestion_treatments');">      
        
        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD"><?php echo $titulo?> Treatments</font></h3>   </div>                  
        </div>                    
    
                                         <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Treatments Date</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_1" name="campo_1" placeholder="Campo 1" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_1'])) { echo str_replace('|',' ',$_GET['campo_1']); }?>"></div>
                                        
                                              
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Treatments Place</font></label>
                                            
                                            <div class="col-sm-3">
                                                <select class="populate placeholder" style="width: 250px" id="campo_2" name="campo_2">
                                                    <option value="">--- SELECT PLACE ---</option>
                                                    <option value="03">Outside</option>
                                                    <option value="11">Office</option>                                                    
                                                </select>                                                
                                            </div>
                                        </div>                   
                                           
<!--                                        <div class="form-group row">
                                                           null
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 3</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="campo_3" name="campo_3" placeholder="Campo 3" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_3'])) { echo str_replace('|',' ',$_GET['campo_3']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           null
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 4</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="campo_4" name="campo_4" placeholder="Campo 4" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_4'])) { echo str_replace('|',' ',$_GET['campo_4']); }?>"></div>                                                               
                                            </div>-->
                                        
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Patient Name</font></label>

                                            <div class="col-sm-3">
                                                <input type="hidden" id="patient_name" name="patient_name" value="">
                                                <select class="populate placeholder" style="width: 250px" id="campo_6" name="campo_6"><option value="">--- SELECT PATIENT ---</option></select>
                                            </div>
                                              
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Patient Id</font></label>

                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="campo_5" name="campo_5" placeholder="Campo 5" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_5'])) { echo str_replace('|',' ',$_GET['campo_5']); }?>">
                                            </div>
                                        </div> 
                                         
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Patient Insurance</font></label>

                                            <div class="col-sm-3">
                                                <select class="populate placeholder" style="width: 250px" id="campo_7" name="campo_7"><option value="">--- SELECT INSURANCE ---</option></select>                                                
                                            </div>                                                               
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 8</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_8" name="campo_8" value="patient" placeholder="Campo 8" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_8'])) { echo str_replace('|',' ',$_GET['campo_8']); }?>"></div>                                                               
                                        </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Therapyst Name</font></label>

                                            <div class="col-sm-3">
                                                <input type="hidden" id="therapyst_name" name="therapyst_name" value="">
                                                <select class="populate placeholder" style="width: 250px" id="campo_9" name="campo_9"><option value="">--- SELECT THERAPYST ---</option></select> 
                                            </div>
                                        
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">License Number</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="license_number" name="license_number" placeholder="License Number" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['license_number'])) { echo str_replace('|',' ',$_GET['license_number']); }?>"></div>                                                               
                                        </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Discipline</font></label>

                                            <div class="col-sm-8">
                                                <select class="populate placeholder" style="width: 250px" id="campo_10" name="campo_10"><option value="">--- SELECT DISCIPLINE ---</option></select>
                                                <!--<input type="text" class="form-control" id="campo_10" name="campo_10" value="IT" placeholder="Campo 10" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_10'])) { echo str_replace('|',' ',$_GET['campo_10']); }?>">-->
                                            </div>
                                       
                                        </div>
                                           
                                        
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">CPT CODE</font></label>

                                            <div class="col-sm-8">

                                            <select class="populate placeholder" style="width: 250px" id="campo_11" name="campo_11"><option value="">--- SELECT CPT ---</option></select>

                                            </div>
                                            

                                            </div>
                                           
<!--                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 12</font></label>
                                            null
                                            <div class="col-sm-8"><input type="text" class="form-control" id="campo_12" name="campo_12" placeholder="Campo 12" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_12'])) { echo str_replace('|',' ',$_GET['campo_12']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                             null              
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 13</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="campo_13" name="campo_13" placeholder="Campo 13" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_13'])) { echo str_replace('|',' ',$_GET['campo_13']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                            null               
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 14</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="campo_14" name="campo_14" placeholder="Campo 14" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_14'])) { echo str_replace('|',' ',$_GET['campo_14']); }?>"></div>                                                               
                                            </div>-->
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Minutes</font></label>

                                            <div class="col-sm-3">
                                                <select class="populate placeholder" style="width: 250px" id="campo_15" name="campo_15">
                                                    <option value="">--- SELECT MINUTES ---</option>
                                                    <option value="15">15</option>
                                                    <option value="30">30</option>
                                                    <option value="45">45</option>
                                                    <option value="60">60</option>
                                                </select>                                                
                                            </div>
                                           
<!--                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 16</font></label>
                                            null
                                            <div class="col-sm-8"><input type="text" class="form-control" id="campo_16" name="campo_16" placeholder="Campo 16" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_16'])) { echo str_replace('|',' ',$_GET['campo_16']); }?>"></div>                                                               
                                            </div>-->
                                           
                                       
                                                          
                                           <!-- <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 17</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_17" name="campo_17" placeholder="Campo 17" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_17'])) { echo str_replace('|',' ',$_GET['campo_17']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 18</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_18" name="campo_18" placeholder="Campo 18" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_18'])) { echo str_replace('|',' ',$_GET['campo_18']); }else {echo '1';}?>"></div>
                                                                                                       
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 19</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_19" name="campo_19" placeholder="Campo 19" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_19'])) { echo str_replace('|',' ',$_GET['campo_19']); }else {echo '1';}?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 20</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_20" name="campo_20" placeholder="Campo 20" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_20'])) { echo str_replace('|',' ',$_GET['campo_20']); }else{echo '1';}?>"></div>
                                        
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 21</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_21" name="campo_21" placeholder="Campo 21" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_21'])) { echo str_replace('|',' ',$_GET['campo_21']); }else{echo '1';}?>"></div>
                                        </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 22</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_22" name="campo_22" placeholder="Campo 22" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_22'])) { echo str_replace('|',' ',$_GET['campo_22']); }else{echo '1677';}?>"></div>
                                            
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 23</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_23" name="campo_23" placeholder="Campo 23" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_23'])) { echo str_replace('|',' ',$_GET['campo_23']); }else{echo '99465';}?>"></div>
                                        </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Campo 24</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="campo_24" name="campo_24"  placeholder="Campo 24" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_24'])) { echo str_replace('|',' ',$_GET['campo_24']); }else{echo '6505';}?>"></div>
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Pay</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="pay" name="pay" placeholder="Pay" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['pay'])) { echo str_replace('|',' ',$_GET['pay']); }else{echo '0';}?>"></div>
                                        </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Adults Progress Notes</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="adults_progress_notes" name="adults_progress_notes" placeholder="Adults Progress Notes" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['adults_progress_notes'])) { echo str_replace('|',' ',$_GET['adults_progress_notes']); }else{echo '0';}?>"></div>
                                                                                                       
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Pedriatics Progress Notes</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="pedriatics_progress_notes" name="pedriatics_progress_notes" value="0" placeholder="Pedriatics Progress Notes" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['pedriatics_progress_notes'])) { echo str_replace('|',' ',$_GET['pedriatics_progress_notes']); }else{echo '0';}?>"></div>                                                               
                                        </div>-->
                                                                                                       
                             </div>
                           
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Aceptar</button> </div>
        </div>
    <input type="hidden" id="accion" name="accion" value="<?php echo $accion?>">
    <?php echo $generar_input;?>
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
        <script>
            function DemoTimePicker(){
                    $('#input_time').timepicker({setDate: new Date()});
            }
            $(document).ready(function() {

                    $('#campo_1').datepicker({setDate: new Date()});	
                    $('#campo_1').prop('readonly', false);
                    
                    
                    $('#campo_5').prop('readonly', true);
                    $('#license_number').prop('readonly', true);

            });   
            

            
            $('#campo_9').change(function(){
                                
                consultar_employee($(this).val(),'license_number')
                                                                              
            });
            
            $('#campo_6').change(function(){
                 
                $('#campo_5').val($(this).val()); 
                $('#patient_name').val($('option:selected',this).text()); 
                
                                                                              
            });   
            $('#campo_15').change(function(){
                 
                $('#campo_17').val($(this).val());                 
                                                                              
            });
            
            
            
                LoadSelect2ScriptExt(function(){

                    $('#campo_2').select2();
                    $('#campo_6').select2();
                    $('#campo_7').select2();
                    $('#campo_9').select2();
                    $('#campo_10').select2();
                    $('#campo_11').select2();
                    
                    $('#campo_15').select2();                    

                });
                
            autocompletar_select('Pat_id','Patient_name','campo_6',"Select Distinct Pat_id, concat(trim(Last_name),', ',trim(First_name)) as Patient_name from patients order by Patient_name");       
            autocompletar_select('insurance','insurance','campo_7',"SELECT Distinct id as id_insurance,insurance FROM seguros order by insurance");                                            
            autocompletar_select('id','employee_name','campo_9',"SELECT Distinct id,concat(last_name,', ',first_name) as employee_name FROM employee WHERE kind_employee = 'Therapist' order by employee_name");                                            
            autocompletar_select('Name','Name','campo_10',"Select Distinct Name from discipline order by Name");     
            autocompletar_select('cpt','type','campo_11',"Select Distinct cpt, type from cpt where DisciplineID=99 ");   


            
            
            
            <?php  if(isset($_GET['id_treatments'])){?>
                setTimeout(function(){                    
                    $("#campo_2").val('<?php echo $_GET['campo_2'];?>').change();
                    $("#campo_6").val('<?php echo str_replace('|', ' ', $_GET['campo_5']);?>').change();
                    $("#campo_7").val('<?php echo $_GET['campo_7'];?>').change();
                    $("#campo_9").val('<?php echo $employee_id;?>').change();
                    $("#campo_10").val('<?php echo $_GET['campo_10'];?>').change();                                       
                    $("#campo_15").val('<?php echo $_GET['campo_15'];?>').change();                                       
                },1000);
                
            <?php  }else{?>
                setTimeout(function(){
                    $("#campo_10").val('IT').change();                                                           
                },1000);
            <?php }?>
                </script>