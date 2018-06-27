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
                var parametros_formulario = $('#form_consultar_physician').serialize();
                               
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../physician/result_physician.php?"+parametros_formulario);

            return false;
        }
$('#Phy_id').attr('onkeypress','return SoloLetras(event)');
                
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

<form id="form_consultar_physician"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">SEARCH Physician</font></h3>   </div>
            <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("../../../includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"></a></div>
        </div>

        <div class="form-group row">

            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Name</font></label>

<div class="col-sm-8">
    <!--<input type="text" class="form-control" id="name" name="Name" placeholder="Name"  autocomplete="off" >-->
    <select style="width:250px;" name='Name' id='name'  onchange="blockCheckBox();">
        <option value=''>--- SELECT ---</option>                 
        <?php 
        $sql  = "Select Distinct Name from physician order by Name "; 
        $conexion = conectar(); 
        $resultado = ejecutar($sql,$conexion); 
        while ($row=mysqli_fetch_array($resultado))  
        {     
//            if((trim($row["Pat_id"])."-".$row["Table_name"]) == (trim($patient_id)."-".$company)) 
//                print("<option value='".$row["Pat_id"]."-".$row["Table_name"]."' selected>".$row["Patient_name"] .$row["Table_name"]    ." </option>"); 
//            else 
                print("<option value='".$row["Name"]."'>".$row["Name"]." </option>"); 
        } 

        ?> 

    </select> 
</div>
            </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">NPI</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="NPI" name="NPI" placeholder="Npi"   autocomplete="off"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Taxonomy</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="Taxonomy" name="Taxonomy"  placeholder="Taxonomy"  autocomplete="off"></div>
                                            </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Organization</font></label>

<div class="col-sm-8"><input type="text" class="form-control" id="Organization" name="Organization"  placeholder="Organization"  autocomplete="off"></div>
                                            </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Address</font></label>

<div class="col-sm-8"><input type="text" class="form-control" id="Address" name="Address"  placeholder="Address"  autocomplete="off"></div>
                                            </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">City</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="City" name="City"  placeholder="City"  autocomplete="off"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">State</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="State" name="State"  placeholder="State"  autocomplete="off"></div>
                                            </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Zip</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="Zip" name="Zip"  placeholder="Zip"  autocomplete="off"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Contact</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="Contact" name="Contact"  placeholder="Contact"  autocomplete="off"></div>
                                            </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Email</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="Email" name="Email"  placeholder="Email"  autocomplete="off"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Office Phone</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="Office_phone" name="Office_phone"  placeholder="Office Phone"  autocomplete="off"></div>
                                            </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Mobile Phone</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="Mobile_phone" name="Mobile_phone"  placeholder="Mobile Phone"  autocomplete="off"></div>   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Fax</font></label>

<div class="col-sm-3"><input type="text" class="form-control" id="Fax" name="Fax" placeholder="Fax"   autocomplete="off"></div>
                                            </div>
  
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">SEARCH</button> </div>
        </div>
</form>
            
        </div>
    </div>
       

    
    
    
         <div id="resultado" class="col-lg-12"></div>        
              
             
    <?php if(isset($_GET['consultar']) && $_GET['consultar'] == 'si') {    
    
        echo '<script type="text/javascript" language="javascript">validar_formulario();</script>';
        
    } ?>

         <script type="text/javascript">
// Run Select2 plugin on elements
function DemoSelect2(){
  $('#name').select2(); 
}
// Run timepicker

$(document).ready(function() {
  // Create Wysiwig editor for textare
  //TinyMCEStart('#wysiwig_simple', null);
  //TinyMCEStart('#wysiwig_full', 'extreme');
  // Add slider for change test input length
  //FormLayoutExampleInputLength($( ".slider-style" ));
  // Initialize datepicker
  //$('#input_date_licence').datepicker({setDate: new Date()});
  //$('#input_date_finger').datepicker({setDate: new Date()});
  //$('#dob').datepicker({setDate: new Date()});
  //$('#hireDate').datepicker({setDate: new Date()});
  //$('#terminationDate').datepicker({setDate: new Date()});
  // Load Timepicker plugin
  //LoadTimePickerScript(DemoTimePicker);
  // Add tooltip to form-controls
  $('.form-control').tooltip();
  LoadSelect2ScriptExt(DemoSelect2);
  // Load example of form validation
  //LoadBootstrapValidatorScript(DemoFormValidator);
  // Add drag-n-drop feature to boxes
  //WinMove();
  //ShowDivEdit();
  //enableField();
});
</script>