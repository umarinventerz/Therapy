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

?>
      
    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/portfolio-item.css" rel="stylesheet">     
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">  
    
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>    
    <script src="../../../js/devoops_ext.js" type="text/javascript"></script>    
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>    
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>
    <script src="../../../js/promise.min.js" type="text/javascript"></script>    
    <script src="../../../js/funciones.js" type="text/javascript"></script>    
    
    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css"> 
    <script type="text/javascript" language="javascript">

       function validar_formulario(){
                var parametros_formulario = $('#form_consultar_seguros').serialize();
                               
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../seguros/result_seguros.php?"+parametros_formulario);

            return false;
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


<form id="form_consultar_seguros"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">Consultar Seguros</font></h3>   </div>
            <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("../../../includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"><img class="img-responsive" src="../../../imagenes/imagen_sistema.jpg" alt="" width="50px" height="50px"></a></div>
        </div>
    
        <div class="form-group row">              
            <div class="col-sm-6 text-center" style="float: none;margin: 0 auto;">
                <label><font color="#585858">NAME</font></label>
            </div>
            <div class="col-sm-6" style="float: none;margin: 0 auto;">
                <input type="text" class="form-control" id="name" name="name" placeholder="NAME" style="text-align: center" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['name'])) { echo str_replace('|',' ',$_GET['name']); }?>">
            </div>  
        </div>
    <hr>
    
        <div class="form-group row">               
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Address</font></label>            
            <div class="col-sm-8">
                <textarea class="form-control" id="address" name="address" placeholder="Address" cols="30" rows="2" onkeyup="Mayusculas(event, this)"><?php if(isset($_GET['address'])) { echo str_replace('|',' ',$_GET['address']); }?></textarea>
            </div>
        </div>
    <div class="form-group row">    
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">City</font></label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="city" name="city" placeholder="City" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['city'])) { echo str_replace('|',' ',$_GET['city']); }?>">
            </div>            
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">State</font></label>            
            <div class="col-sm-3">
                <input type="text" class="form-control" id="state" name="state" placeholder="State" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['state'])) { echo str_replace('|',' ',$_GET['state']); }?>">
            </div> 
    </div>
    <div class="form-group row">  
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Zip</font></label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['zip'])) { echo str_replace('|',' ',$_GET['zip']); }?>">
            </div>              
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Phone</font></label>            
            <div class="col-sm-3">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['phone'])) { echo str_replace('|',' ',$_GET['phone']); }?>">
            </div>  
    </div>
    <div class="form-group row">  
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Fax</font></label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="fax" name="fax" placeholder="Fax" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['fax'])) { echo str_replace('|',' ',$_GET['fax']); }?>">
            </div>
<!--            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Reporting System</font></label>
            <div class="col-sm-3">
                <select id="id_reporting_system" name="id_reporting_system">
                    <option value="">Seleccione..</option>
                </select>
            </div>-->
    </div>
    <div class="form-group row"> 
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Provider</font></label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="provider" name="provider" placeholder="Provider" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['provider'])) { echo str_replace('|',' ',$_GET['provider']); }?>">
            </div>
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Provider</font></label>
        <div class="col-sm-3">
            <select id="id_type_provider" name="id_type_provider">
                <option value="">Seleccione..</option>
            </select>
        </div>   
    </div>
    <div class="form-group row"> 
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Claim Ind</font></label>
            <div class="col-sm-3">
                <select id="id_claim_ind" name="id_claim_ind">
                    <option value="">Seleccione..</option>
                </select>                
            </div>
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Submitter Id</font></label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="submitter_id" name="submitter_id" placeholder="Submitter Id" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['submitter_id'])) { echo str_replace('|',' ',$_GET['submitter_id']); }?>">
        </div>   
    </div>
    <div class="form-group row"> 
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Edi Gateway</font></label>
            <div class="col-sm-3">
                <select id="id_edi_gateway" name="id_edi_gateway">
                    <option value="">Seleccione..</option>
                </select>                
            </div>
        <label class="col-sm-2 form-control-label text-right"><font color="#585858">Payer Id</font></label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="payer_id" name="payer_id" placeholder="Payer Id" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['payer_id'])) { echo str_replace('|',' ',$_GET['payer_id']); }?>">
        </div>   
    </div>  
  
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
        </div>
</form>      
        <script>
                LoadSelect2ScriptExt(function(){

                    $('#id_reporting_system').select2();                  
                    $('#id_type_provider').select2();                  
                    $('#id_claim_ind').select2();                  
                    $('#id_edi_gateway').select2();                  

                });   
            
            autocompletar_radio('reporting_system','id_reporting_system','tbl_seguros_reporting_system','selector',null,null,null,null,'id_reporting_system');   
            autocompletar_radio('type_provider','id_type_provider','tbl_seguros_type_provider','selector',null,null,null,null,'id_type_provider');             
            autocompletar_radio('claim_ind','id_claim_ind','tbl_seguros_claim_ind','selector',null,null,null,null,'id_claim_ind'); 
            autocompletar_radio('edi_gateway','id_edi_gateway','tbl_seguros_edi_gateway','selector',null,null,null,null,'id_edi_gateway');             
                            
            
            
            
        </script>            
         <div id="resultado" class="col-lg-12"></div>        
              
             
    <?php if(isset($_GET['consultar']) && $_GET['consultar'] == 'si') {    
    
        echo '<script type="text/javascript" language="javascript">validar_formulario();</script>';
        
    } ?>
