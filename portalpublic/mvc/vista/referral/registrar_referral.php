         
<?php

session_start();
require_once("../../../conex.php");

if(!isset($_SESSION['user_id'])){ ?>
	<script>alert('MUST LOG IN')</script>
	<script>window.location="../../../index.php";</script>
<?php }

if(isset($_GET['accion']) && $_GET['accion'] == 'convertir'){
    $accion = 'convertir';
    $titulo = 'Convertir';
    $generar_input = '<input type="hidden" id="Ref_id" Name="Ref_id" value="'.$_GET['Ref_id'].'">'
    . '<input type="hidden" id="id_referral" Name="id_referral" value="'.$_GET['id_referral'].'">';
}else{
    if(isset($_GET['Ref_id'])){     
        $accion = 'modificar';
        $titulo = 'Modificar';
        $generar_input = '<input type="hidden" id="Ref_id" Name="Ref_id" value="'.$_GET['Ref_id'].'">'
            . '<input type="hidden" id="id_referral" Name="id_referral" value="'.$_GET['id_referral'].'">';
    } else {
        $accion = 'insertar';
        $titulo = 'Registrar';
        $generar_input = null;
    }
}


$conexion = conectar(); 

//consulto si el referral ya ha sido convertido

$convertido_consulta = "SELECT convertido FROM tbl_referral WHERE  id_referral ='".$_GET['id_referral']."' ;";
$resultado_convertido= ejecutar($convertido_consulta,$conexion);
$referral_convertido;
while ($row_convertido=mysqli_fetch_array($resultado_convertido)) {	
    $referral_convertido = $row_convertido['convertido'];
   
}
//consulto el id del patients si $referral_convertido es igual a 1
if($referral_convertido==1){
    
    $id_patients_convertido = "SELECT id FROM patients WHERE  Pat_id ='".$_GET['Ref_id']."' ;";
    $resultado_id_convertido= ejecutar($id_patients_convertido,$conexion);
    $referral_id_convertido;
    while ($row_id_convertido=mysqli_fetch_array($resultado_id_convertido)) {	
        $referral_id_convertido = $row_id_convertido['id'];

    }

    //consulto el codigo de barra 
     $barcode_consulta = "SELECT barcode FROM tbl_barcodes WHERE  id_type_person=1 and id_relation ='".$referral_id_convertido."' ;";
    $resultado_codigo = ejecutar($barcode_consulta,$conexion);
    $codigo_barra;
    while ($row_barcode=mysqli_fetch_array($resultado_codigo)) {	
        $codigo_barra = $row_barcode['barcode'];

    }

}

                
                
                if(isset($_GET['PCP'])){
                $sql = 'SELECT Phy_id FROM physician WHERE Name = \''.str_replace('|',' ',$_GET['PCP']).'\';';

                    $resultadopcp = ejecutar($sql,$conexion);

                    $pcp_name;
                    while ($row_pcp=mysqli_fetch_array($resultadopcp)) {	

                        $pcp_name = $row_pcp['Phy_id'];
   
                    }
                }

                if(isset($_GET['Ref_Physician'])){
                    $sql = 'SELECT Phy_id FROM physician WHERE Name = \''.str_replace('|',' ',$_GET['Ref_Physician']).'\';';

                    $resultadoRef_Physician = ejecutar($sql,$conexion);

                    $Ref_Physician_name;
                    while ($row_Ref_Physician_name=mysqli_fetch_array($resultadoRef_Physician)) {	

                        $Ref_Physician_name = $row_Ref_Physician_name['Phy_id'];

                    }
                }        
                 
                if(isset($_GET['Pri_Ins'])){                
                    $sql = 'SELECT ID FROM seguros WHERE insurance = \''.str_replace('|',' ',$_GET['Pri_Ins']).'\';';

                    $resultadoPri_Ins = ejecutar($sql,$conexion);

                    $Pri_Ins_insurance;
                    while ($row_Pri_Ins=mysqli_fetch_array($resultadoPri_Ins)) {	

                        $Pri_Ins_insurance = $row_Pri_Ins['ID'];

                    }
                }
                
                if(isset($_GET['Sec_INS'])){ 
                    $sql = 'SELECT ID FROM seguros WHERE insurance = \''.str_replace('|',' ',$_GET['Sec_INS']).'\';';

                    $resultadoSec_INS = ejecutar($sql,$conexion);

                    $Sec_INS_insurance;
                    while ($row_Sec_INS=mysqli_fetch_array($resultadoSec_INS)) {	

                        $Sec_INS_insurance = $row_Sec_INS['ID'];

                    }
                }
                if(isset($_GET['Ter_Ins'])){ 
                    $sql = 'SELECT ID FROM seguros WHERE insurance = \''.str_replace('|',' ',$_GET['Ter_Ins']).'\';';

                    $resultadoTer_Ins = ejecutar($sql,$conexion);

                    $Ter_Ins_insurance;
                    while ($row_Ter_Ins=mysqli_fetch_array($resultadoTer_Ins)) {	

                        $Ter_Ins_insurance = $row_Ter_Ins['ID'];

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

    function Validar_Formulario_Gestion_Referral(nombre_formulario) {              
         
    var nombres_campos = '';
    
        if($('#Ref_id').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Referral Id</td></tr></table>';
                                
        }
        if($('#type_patient').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Type patient</td></tr></table>';
                                
        }
          if($('#Last_name').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Last Name</td></tr></table>';
                                
        }
          if($('#First_name').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * First Name</td></tr></table>';
                                
        }        
          if($('#Sex').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Sex</td></tr></table>';
                                
        }
          if($('#DOB').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * DOB</td></tr></table>';
                                
        }
          if($('#Guardian').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Guardian</td></tr></table>';
                                
        }
          if($('#Social').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Social</td></tr></table>';
                                
        }
          if($('#Address').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Address</td></tr></table>';
                                
        }
          if($('#City').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * City</td></tr></table>';
                                
        }
          if($('#State').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * State</td></tr></table>';
                                
        }
          if($('#Zip').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Zip</td></tr></table>';
                                
        }
          if($('#county').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * County</td></tr></table>';
                                
        }
          if($('#E_mail').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * E Mail</td></tr></table>';
                                
        }
       /*   if($('#Phone').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Phone</td></tr></table>';
                                
        }*/
        if($('#Phone').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Phone</td></tr></table>';
                                
        }
        if($('#id_carriers').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Telephone provider</td></tr></table>';
                                
        }
          if($('#Cell').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Cell</td></tr></table>';
                                
        }
          if($('#PCP').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * PCP</td></tr></table>';
                                
        }
          if($('#PCP_NPI').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * PCP NPI</td></tr></table>';
                                
        }
        //   if($('#Ref_Physician').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Ref Physician</td></tr></table>';
                                
        // }
        //   if($('#Phy_NPI').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Phy NPI</td></tr></table>';
                                
        // }
          if($('#Pri_Ins').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Pri Ins</td></tr></table>';
                                
        }
        //   if($('#Auth').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Auth</td></tr></table>';
                                
        // }
        //   if($('#Sec_INS').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Sec INS</td></tr></table>';
                                
        // }
        //   if($('#Auth_2').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Auth 2</td></tr></table>';
                                
        // }
        //   if($('#Ter_Ins').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Ter Ins</td></tr></table>';
                                
        // }
        //   if($('#Auth_3').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Auth 3</td></tr></table>';
                                
        // }
        //   if($('#Mem_n').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Mem #</td></tr></table>';
                                
        // }
        //   if($('#Grp_n').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Grp #</td></tr></table>';
                                
        // }
        //   if($('#Intake_Agmts').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Intake Agmts</td></tr></table>';
                                
        // }
        //   if($('#Table_name').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Table Name</td></tr></table>';
                                
        // }
        //   if($('#Thi_Ins').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Thi Ins</td></tr></table>';
                                
        // }
          if($('#active').val() == '' && $('#accion').val() == 'convertir'){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Active</td></tr></table>';
                                
        }
        if($('#barcode').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Barcode</td></tr></table>';
                                
        }
        //   if($('#admision_date').val() == '' && $('#accion').val() == 'convertir'){
        //             nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Admision Date</td></tr></table>';
                                
        // }
           
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
                if($('#DOB').prop('checked')) { $('#DOB').val(true);}
                
                if($('#Intake_Agmts').prop('checked')) { $('#Intake_Agmts').val(true);}
                
                if($('#admision_date').prop('checked')) { $('#admision_date').val(true);}
                 

                        var campos_formulario = $("#form_gestion_referral").serialize();
                        
                        $.post(
                                "../../controlador/referral/gestionar_referral.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    if(resultado_controlador.repetido == 'si'){
            
                                    swal({
                                                title: '<h4><b>Identifier of Referral repeat</b></h4>',                
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
                    window.location.href = "../referral/consultar_referral.php?&consultar=si";                    
                  }else{
                      window.location.href = "../referral/registrar_referral.php";
                  }
                    });             
                                            
           }    
           
           $('#DOB').datepicker({ format: 'dd-mm-yyyy'});
                                     $('#DOB').prop('readonly', true);
                                     $('#Intake_Agmts').datepicker({ format: 'dd-mm-yyyy'});
                                     $('#Intake_Agmts').prop('readonly', true);
                                     $('#admision_date').datepicker({ format: 'dd-mm-yyyy'});
                                     $('#admision_date').prop('readonly', true);
                                      
       function consultar_npi(valor,identificador){
              
                $.post(
                    "../../controlador/patients/consultar_npi.php",
                    '&Phy_id='+valor,
                    function (resultado_controlador) {
                       
                       $('#'+identificador).val(resultado_controlador.npi);
                       
                    },
                    "json" 
                );
                        
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

<form id="form_gestion_referral" onSubmit="return Validar_Formulario_Gestion_Referral('form_gestion_referral');">      
    <?php if(isset($referral_id_convertido)){?>
    <input type="hidden" name="id_patients" id="id_patients" value="<?=$referral_id_convertido?>"/>
    <?php } ?>
        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD"><?php echo $titulo?> Referral</font></h3>   </div>                  
        </div>                    
        <div class="form-group row">                              
        <div class="col-lg-2"></div>
            <div class="col-sm-4 text-center">
                <label><font color="#585858">REFERRAL ID</font></label>
            </div>
            <div class="col-sm-4 text-center">
                <label><font color="#585858">TYPE PATIENT</font></label>
            </div>         
        <div class="col-lg-2"></div>
    </div>
    
       <div class="form-group row">        
            <div class="col-lg-2"></div>
            <div class="col-sm-4 text-center">
                <input type="text" class="form-control" id="Ref_id" name="Ref_id" placeholder="REFERRAL ID" style="text-align: center" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Ref_id'])) { echo str_replace('|',' ',$_GET['Ref_id']); }?>">
            </div>             
            <div class="col-sm-4 text-center">
                <select id="type_patient" name="type_patient"><option value="">Seleccione..</option></select>
            </div>
            <div class="col-lg-2"></div>
        </div>
    
    
    
    
    <hr>
        <div class="form-group row">
                           
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Last Name</font></label>
            
            <div class="col-sm-3"><input type="text" class="form-control" id="Last_name" name="Last_name" placeholder="Last Name"  value="<?php if(isset($_GET['Last_name'])) { echo str_replace('|',' ',$_GET['Last_name']); }?>"></div>                                                               
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">First Name</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="First_name" name="First_name" placeholder="First Name"  value="<?php if(isset($_GET['First_name'])) { echo str_replace('|',' ',$_GET['First_name']); }?>"></div>                                                               
                                            </div>
                                           
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Sex</font></label>

                                            <div class="col-sm-3"><select id="Sex" name="Sex"><option value="">Seleccione..</option><option value="M">Masculino</option><option value="F">Femenino</option></select></div>                                                               
                                        
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">DOB</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="DOB" name="DOB" placeholder="DOB"  value="<?php if(isset($_GET['DOB'])) { echo str_replace('|',' ',$_GET['DOB']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Guardian</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Guardian" name="Guardian" placeholder="Guardian"  value="<?php if(isset($_GET['Guardian'])) { echo str_replace('|',' ',$_GET['Guardian']); }?>"></div>                                                               
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Social</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Social" name="Social" placeholder="Social"  value="<?php if(isset($_GET['Social'])) { echo str_replace('|',' ',$_GET['Social']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Address</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Address" name="Address" placeholder="Address"  value="<?php if(isset($_GET['Address'])) { echo str_replace('|',' ',$_GET['Address']); }?>"></div>                                                                                                           
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">City</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="City" name="City" placeholder="City"  value="<?php if(isset($_GET['City'])) { echo str_replace('|',' ',$_GET['City']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">State</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="State" name="State" placeholder="State"  value="<?php if(isset($_GET['State'])) { echo str_replace('|',' ',$_GET['State']); }?>"></div>                                                               
                                            
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Zip</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Zip" name="Zip" placeholder="Zip"  value="<?php if(isset($_GET['Zip'])) { echo str_replace('|',' ',$_GET['Zip']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">County</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="county" name="county" placeholder="County"  value="<?php if(isset($_GET['county'])) { echo str_replace('|',' ',$_GET['county']); }?>"></div>                                                               
                                            
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">E Mail</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="E_mail" name="E_mail" placeholder="E Mail"  value="<?php if(isset($_GET['E_mail'])) { echo str_replace('|',' ',$_GET['E_mail']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Phone</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Phone" name="Phone" placeholder="Phone"  value="<?php if(isset($_GET['Phone'])) { echo str_replace('|',' ',$_GET['Phone']); }?>"></div>                                                                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Telephone provider</font></label>
                                            <div class="col-sm-3">
                                                <select class="populate placeholder" id="id_carriers" name="id_carriers">
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
                                            
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Cell</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Cell" name="Cell" placeholder="Cell"  value="<?php if(isset($_GET['Cell'])) { echo str_replace('|',' ',$_GET['Cell']); }?>"></div> 
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">PCP</font></label>

                                            <div class="col-sm-3"><select id="pcp" name="pcp"><option value="">Seleccione..</option></select></div>                                                                                                           
                                                           
                                                                                                          
                                            </div>
                                         
                                        <div class="form-group row">
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">PCP NPI</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="PCP_NPI" name="PCP_NPI" placeholder="PCP NPI"  value="<?php if(isset($_GET['PCP_NPI'])) { echo str_replace('|',' ',$_GET['PCP_NPI']); }?>"></div>                
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Ref Physician</font></label>

                                            <div class="col-sm-3"><select id="Ref_Physician" name="Ref_Physician"><option value="">Seleccione..</option></select></div>                                                               
                                                           
                                                                                                           
                                            </div>
                                           
                                        <div class="form-group row">
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Phy NPI</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Phy_NPI" name="Phy_NPI" placeholder="Phy NPI"  value="<?php if(isset($_GET['Phy_NPI'])) { echo str_replace('|',' ',$_GET['Phy_NPI']); }?>"></div>               
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Pri Ins</font></label>

                                            <div class="col-sm-3"><select id="Pri_Ins" name="Pri_Ins"><option value="">Seleccione..</option></select></div>                                                                                                           
                                                           
                                                                                                          
                                            </div>
                                           
                                        <div class="form-group row">
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Auth</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Auth" name="Auth" placeholder="Auth"  value="<?php if(isset($_GET['Auth'])) { echo str_replace('|',' ',$_GET['Auth']); }?>"></div>                
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Sec INS</font></label>

                                            <div class="col-sm-3"><select id="Sec_INS" name="Sec_INS"><option value="">Seleccione..</option></select></div>                                                                                                           
                                                           
                                                                                                          
                                            </div>
                                           
                                        <div class="form-group row">
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Auth 2</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Auth_2" name="Auth_2" placeholder="Auth 2"  value="<?php if(isset($_GET['Auth_2'])) { echo str_replace('|',' ',$_GET['Auth_2']); }?>"></div>                
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Ter Ins</font></label>

                                            <div class="col-sm-3"><select id="Ter_Ins" name="Ter_Ins"><option value="">Seleccione..</option></select></div>                                                               
                                            
                                                                                                          
                                            </div>
                                           
                                        <div class="form-group row">
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Auth 3</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Auth_3" name="Auth_3" placeholder="Auth 3"  value="<?php if(isset($_GET['Auth_3'])) { echo str_replace('|',' ',$_GET['Auth_3']); }?>"></div>                
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Mem #</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Mem_n" name="Mem_n" placeholder="Mem #"  value="<?php if(isset($_GET['Mem_n'])) { echo str_replace('|',' ',$_GET['Mem_n']); }?>"></div>                                                                                                           
                                                           
                                                                                                           
                                            </div>
                                           
                                        <div class="form-group row">
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Grp #</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Grp_n" name="Grp_n" placeholder="Grp #"  value="<?php if(isset($_GET['Grp_n'])) { echo str_replace('|',' ',$_GET['Grp_n']); }?>"></div>               
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Intake Agmts</font></label>

                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="Intake_Agmts" name="Intake_Agmts" placeholder="Intake Agmts"  value="<?php if(isset($_GET['Intake_Agmts'])) { echo str_replace('|',' ',$_GET['Intake_Agmts']); }?>">                                                
                                            </div>
                                                           
                                                                                                           
                                            </div>
                                           
                                        <div class="form-group row">
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Table Name</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Table_name" name="Table_name" placeholder="Table Name"  value="<?php if(isset($_GET['Table_name'])) { echo str_replace('|',' ',$_GET['Table_name']); }?>"></div>               
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Thi Ins</font></label>

                                            <div class="col-sm-3"><input type="text" class="form-control" id="Thi_Ins" name="Thi_Ins" placeholder="Thi Ins"  value="<?php if(isset($_GET['Thi_Ins'])) { echo str_replace('|',' ',$_GET['Thi_Ins']); }?>"></div>                                                               
                                                                                                       
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 form-control-label text-right"><font color="#585858">Active</font></label>

                                                <div class="col-sm-3"><input type="checkbox" id="active" name="active" <?php if((isset($_GET['active']) && $_GET['active'] == '1') || ($accion == 'insertar')) { echo 'checked'; }?>></div>  
                                                <?php if($accion=='convertir' || $referral_convertido==1){?>
                                                    <label class="col-sm-2 form-control-label text-right"><font color="#585858">Barcode</font></label>

                                                    <div class="col-sm-3"><input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode"  value="<?php if(isset($codigo_barra)){echo $codigo_barra;}?>"></div>                                                               
                                                <?php } ?>
                                            </div>
                                       <!--  <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Admision Date</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="admision_date" name="admision_date" placeholder="Admision Date"  value="<?php if(isset($_GET['admision_date'])) { echo str_replace('|',' ',$_GET['admision_date']); }?>"></div>                                                               
                                            </div>
                                                     -->                                                   
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

                    $('#DOB').datepicker({setDate: new Date()});	
                    $('#DOB').prop('readonly', false);
                    
                    $('#admision_date').datepicker({setDate: new Date()});	
                    $('#admision_date').prop('readonly', true);
                    
                    $('#discharge_date').datepicker({setDate: new Date()});	
                    $('#discharge_date').prop('readonly', true);                    
                    
                    $('#Intake_Agmts').datepicker({setDate: new Date()});	
                    $('#Intake_Agmts').prop('readonly', true);                    
                    
                    $('#Phy_NPI').prop('readonly', true);
                    $('#PCP_NPI').prop('readonly', true);

            });   
            

            
            $('#pcp').change(function(){
                                
                consultar_npi($(this).val(),'PCP_NPI')
                                                                              
            });
            
            $('#Ref_Physician').change(function(){
                                
                consultar_npi($(this).val(),'Phy_NPI')
                                                                              
            });            
            
            
            
                LoadSelect2ScriptExt(function(){

                    $('#pcp').select2();
                    $('#Ref_Physician').select2();
                    $('#Sex').select2();
                    
                    $('#Pri_Ins').select2();
                    $('#Sec_INS').select2();
                    $('#Ter_Ins').select2();
                    $('#type_patient').select2();
                    $('#id_carriers').select2();

                });
                
            autocompletar_radio('Name','Phy_id','physician','selector',null,null,null,null,'pcp');   
            autocompletar_radio('Name','Phy_id','physician','selector',null,null,null,null,'Ref_Physician');
            
            autocompletar_radio('insurance','ID','seguros','selector','<?php echo $_GET['Pri_Ins'];?>',null,null,null,'Pri_Ins'); 
            autocompletar_radio('insurance','ID','seguros','selector','<?php echo $_GET['Sec_INS'];?>',null,null,null,'Sec_INS'); 
            autocompletar_radio('insurance','ID','seguros','selector','<?php echo $_GET['Ter_Ins'];?>',null,null,null,'Ter_Ins');
            
            autocompletar_radio('seguros_type_person','id_seguros_type_person','tbl_seguros_type_person','selector','<?php echo $_GET['type_patient'];?>',null,null,null,'type_patient');                         
            
            <?php if(isset($_GET['Sex'])){?>
                //$("#Sex").val('<?php echo $_GET['Sex'];?>').change();
                
                $("#Sex").val('<?php echo $_GET['Sex'];?>').change();
                $("#type_patient").val('<?php echo $_GET['type_patient'];?>').change();
                setTimeout(function(){
                    $("#pcp").val(<?php echo $pcp_name;?>).change();
                    $("#Ref_Physician").val('<?php echo $Ref_Physician_name;?>').change();
                    $("#Pri_Ins").val('<?php echo $Pri_Ins_insurance;?>').change();
                    $("#Sec_INS").val('<?php echo $Sec_INS_insurance;?>').change();
                    $("#Ter_Ins").val('<?php echo $Ter_Ins_insurance;?>').change();                                       
                },1000);
                
                
                
                
            <?php }?>    
                </script>