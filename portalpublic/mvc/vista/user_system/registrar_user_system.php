         
<?php

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




/*
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['id_usuario'])){
	header("Location: http://10.100.1.250/KIDWORKS/index.php?&session=finalizada", true);
	
}
*/

if(isset($_GET['Phy_id'])){ 
    
$accion = 'modificar';
$titulo = 'Modificar';
$generar_input = '<input type="hidden" id="Phy_id" Name="Phy_id" value="'.$_GET['Phy_id'].'">';
} else {
$accion = 'insertar';
$titulo = 'Registrar';
$generar_input = null;
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

    function Validar_Formulario_Gestion_User_system(nombre_formulario) {              
         
    var nombres_campos = '';
    
    <?php  if(!isset($_GET['user_id'])){ ?>
                          if($('#user_id').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * User Id</td></tr></table>';
                                
        }
        <?php } ?>
                        
                  if($('#Last_name').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Last Name</td></tr></table>';
                                
        }
          if($('#First_name').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * First Name</td></tr></table>';
                                
        }
          if($('#user_name').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * User Name</td></tr></table>';
                                
        }
          if($('#password').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Password</td></tr></table>';
                                
        }
          if($('#status_id').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Status Id</td></tr></table>';
                                
        }
          if($('#User_type').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * User Type</td></tr></table>';
                                
        }
           
    if(nombres_campos != ''){ 
            
        swal({
          title: "<h3><b>Complete los Siguientes Campos<b></h3>",          
          type: "info",
          html: "<h4>"+nombres_campos+"</h4>",
          showCancelButton: false,
          animation: "slide-from-top",
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        });
            
            return false; 
        
                         } else {  

                        var campos_formulario = $("#form_gestion_user_system").serialize();
                        
                        $.post(
                                "../../controlador/user_system/gestionar_user_system.php",
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
                    $( "#conexion" ).load("../user_system/consultar_user_system.php?&consultar=si");                    
                  }
                    });             
                                            
           }    
           
            
       
        </script>

<body>
    
   <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br><br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../imagenes/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">

<form id="form_gestion_user_system" onSubmit="return Validar_Formulario_Gestion_User_system('form_gestion_user_system');">      
        
        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD"><?php echo $titulo?> User System</font></h3>   </div>                  
        </div>                    
                   
        <div class="form-group row">
                           
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Last Name</font></label>
            
            <div class="col-sm-8"><input type="text" class="form-control" id="Last_name" name="Last_name" placeholder="Last Name"  value="<?php if(isset($_GET['Last_name'])) { echo str_replace('|',' ',$_GET['Last_name']); }?>"></div>                                                               
            </div>
           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">First Name</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="First_name" name="First_name" placeholder="First Name"  value="<?php if(isset($_GET['First_name'])) { echo str_replace('|',' ',$_GET['First_name']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">User Name</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name"  value="<?php if(isset($_GET['user_name'])) { echo str_replace('|',' ',$_GET['user_name']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Password</font></label>

                                            <div class="col-sm-8"><input type="password" class="form-control" id="password" name="password" placeholder="Password"  value="<?php if(isset($_GET['password'])) { echo str_replace('|',' ',$_GET['password']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Status Id</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="status_id" name="status_id" placeholder="Status Id"  value="<?php if(isset($_GET['status_id'])) { echo str_replace('|',' ',$_GET['status_id']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">User Type</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="User_type" name="User_type" placeholder="User Type"  value="<?php if(isset($_GET['User_type'])) { echo str_replace('|',' ',$_GET['User_type']); }?>"></div>                                                               
                                            </div>
                                                                                                       
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
        