<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../home/home.php";</script>';
	}
}

if(isset($_GET['Phy_id'])){ 
    
$accion = 'modificar';
$titulo = 'Modificar';
$generar_input = '<input type="hidden" id="Phy_id" Name="Phy_id" value="'.$_GET['Phy_id'].'">';
} else {
$accion = 'insertar';
$titulo = 'Register';
$generar_input = null;
}

?>
        <link href="../../../css/portfolio-item.css" rel="stylesheet">
        <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
        <link href="../../../plugins/select2/select2.css" rel="stylesheet">
        <link href="../../../css/style_v1.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css">  
        <link href="../../../css/datepicker.css" rel="stylesheet" type="text/css">  
        <link href="../../../css/fileinput.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript" language="javascript" src="../../../plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" language="javascript" src="plugins/jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../plugins/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/devoops_ext.js"></script>        
        <script type="text/javascript" language="javascript" src="../../../js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/listas.js"></script> 
        <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/promise.min.js"></script> 
        <script type="text/javascript" language="javascript" src="../../../js/jquery.price_format.2.0.min.js"></script> 
        <script type="text/javascript" language="javascript" src="../../../js/funciones.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/fileinput.js"></script>

    <script type="text/javascript" language="javascript">

    function Validar_Formulario_Gestion_Physician(nombre_formulario) {              
         
    var nombres_campos = '';
    
<?php  if(!isset($_GET['Phy_id'])){ ?>
  if($('#Phy_id').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Id Physician</td></tr></table>';

        }
<?php } ?>

  if($('#Name').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Name</td></tr></table>';

        }
  if($('#NPI').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Npi</td></tr></table>';

        }
 // if($('#Taxonomy').val() == ''){
//nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Taxonomy</td></tr></table>';

    //    }
  if($('#Organization').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Organization</td></tr></table>';

        }
  if($('#Address').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Address</td></tr></table>';

        }
  if($('#City').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * City</td></tr></table>';

        }
  if($('#State').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * State</td></tr></table>';

        }
  if($('#Zip').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Zip</td></tr></table>';

        }
  if($('#Contact').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Contact</td></tr></table>';

        }
  if($('#Email').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Email</td></tr></table>';

        }
  if($('#Office_phone').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Office Phone</td></tr></table>';

        }
  if($('#Mobile_phone').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Mobile Phone</td></tr></table>';

        }
  if($('#Fax').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Fax</td></tr></table>';

        }
    if($('#id_carriers').val() == ''){
nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Telephone provider</td></tr></table>';

    }

    if(nombres_campos != ''){ 
            
        swal({
          title: "<h3><b>Complete los Siguientes Campos<b></h3>",          
          type: "info",
          html: "<h4>"+nombres_campos+"</h4>",
          showCancelButton: false,          
          closeOnConfirm: true,
          showLoaderOnConfirm: false,
        });
            
            return false; 
        
                         } else {

                        var campos_formulario = $("#form_gestion_physician").serialize();
                        
                        $.post(
                                "../../controlador/physician/gestionar_physician.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    if(resultado_controlador.repetido == 'si'){
            
                                        swal({
                                                    title: '<h4><b>NPI is repeated</b></h4>',                
                                                    type: "error"
                                                    });       


                                            } else {
                                                mostrar_datos(resultado_controlador);                                    
                                            }
                                    
                                },
                                "json" 
                                );
                        
                        return false;
                    }
            }            
            
            function mostrar_datos(resultado_controlador) {                                         
           
            swal({
                title: resultado_controlador.mensaje,
                text: "DO YOU WANNA CHECK REGISTERED DOCTOR??",
                type: "success",
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: "#d33",   
                confirmButtonText: "SEARCH",   
                closeOnConfirm: false,
                closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) { 
                    window.location.href = '../physician/consultar_physician.php?&consultar=si';                   
                  }
                    });             
                                            
           }
                   
$('#Phy_id').attr('onkeypress','return SoloLetras(event)');



        </script>
<body>
    
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">

<form id="form_gestion_physician" onSubmit="return Validar_Formulario_Gestion_Physician('form_gestion_physician');">      
 
                <div class="form-group row">                
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8" align="left"><h3><font color="#BDBDBD"><?php echo $titulo?> Physician</font></h3>   </div>                  
                    <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("../../../includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"></a></div>
                </div>                                                                 
        <div class="form-group row">   
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Name</font></label>            
<div class="col-sm-8"><input type="text" class="form-control" id="Name" Name="Name" placeholder="Name" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Name'])) { echo str_replace('|',' ',$_GET['Name']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Npi</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="NPI" Name="NPI" placeholder="Npi" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['NPI'])) { echo str_replace('|',' ',$_GET['NPI']); }?>" REQUIRED></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Taxonomy</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Taxonomy" Name="Taxonomy" placeholder="Taxonomy" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Taxonomy'])) { echo str_replace('|',' ',$_GET['Taxonomy']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Organization</font></label>
<div class="col-sm-8"><input type="text" class="form-control" id="Organization" Name="Organization" placeholder="Organization" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Organization'])) { echo str_replace('|',' ',$_GET['Organization']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Address</font></label>
<div class="col-sm-8"><input type="text" class="form-control" id="Address" Name="Address" placeholder="Address" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Address'])) { echo str_replace('|',' ',$_GET['Address']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">City</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="City" Name="City" placeholder="City" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['City'])) { echo str_replace('|',' ',$_GET['City']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">State</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="State" Name="State" placeholder="State" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['State'])) { echo str_replace('|',' ',$_GET['State']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Zip</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Zip" Name="Zip" placeholder="Zip" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Zip'])) { echo str_replace('|',' ',$_GET['Zip']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Contact</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Contact" Name="Contact" placeholder="Contact" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Contact'])) { echo str_replace('|',' ',$_GET['Contact']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Email</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Email" Name="Email" placeholder="Email" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Email'])) { echo str_replace('|',' ',$_GET['Email']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Office Phone</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Office_phone" Name="Office_phone" placeholder="Office Phone" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Office_phone'])) { echo str_replace('|',' ',$_GET['Office_phone']); }?>"></div>
        </div>
   
        <div class="form-group row">
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Mobile Phone</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Mobile_phone" Name="Mobile_phone" placeholder="Mobile Phone" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Mobile_phone'])) { echo str_replace('|',' ',$_GET['Mobile_phone']); }?>"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Fax</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Fax" Name="Fax" placeholder="Fax" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Fax'])) { echo str_replace('|',' ',$_GET['Fax']); }?>"></div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Telephone provider</font></label>
                <div class="col-sm-3">
                    <select class="form-control" id="id_carriers" name="id_carriers">
                        <option value="">Seleccione</option>
                        <?php 
                            $sql  = "Select id_patients_carriers, carrier from tbl_patients_carriers order by id_patients_carriers asc"; 
                              $conexion = conectar(); 
                              $resultado = ejecutar($sql,$conexion); 
                              while ($row=mysqli_fetch_array($resultado))  
                              {   
                                  if(isset($_GET['id_carriers'])){
                                        if($row["id_patients_carriers"]==$_GET['id_carriers']){
                                            print("<option value='".$row["id_patients_carriers"]."' selected>".$row["carrier"]."</option>"); 
                                        }else{
                                                print("<option value='".$row["id_patients_carriers"]."'>".$row["carrier"]."</option>"); 
                                        }
                                  }else{
                                      print("<option value='".$row["id_patients_carriers"]."'>".$row["carrier"]."</option>"); 
                                  }
                              }       

                        ?> 
                    </select>
                </div>
        </div>
           
            <div class="form-group row">
                <div class="col-sm-2" align="left"></div>
                <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Aceptar</button> </div>
            </div>
  
    <input type="hidden" id="accion" Name="accion" value="<?php echo $accion?>">

    <?php echo $generar_input;?>
</form>
        </div>
    </div>
      
        <div id="resultado"></div>
