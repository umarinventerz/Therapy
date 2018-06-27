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

<!-- CSS 
     <script type="text/javascript" language="javascript" class="init">  
         
    $(document).ready(function() { 
        $('#table_physician').DataTable({ 
            dom: 'Bfrtip', 
            buttons: [ 
                'copyHtml5', 
                'excelHtml5', 
                'csvHtml5', 
                'pdfHtml5' 
            ] 
        } ); 
    } ); 


 
    </script> -->


    <script type="text/javascript" language="javascript">

       function validar_formulario(){
                var parametros_formulario = $('#form_consultar_patients').serialize();
                               
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../patients/result_patients.php?"+parametros_formulario);

            return false;
        }
        
        
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
        
        
$('#id_patients').attr('onkeypress','return SoloLetras(event)');
$('#State').attr('onkeypress','return SoloLetras(event)');
$('#Zip').attr('onkeypress','return SoloLetras(event)');
$('#Ref_Physician').attr('onkeypress','return SoloLetras(event)');
                
        </script>

    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>

    
    <div class="panel-collapse collapse in" style="padding-top: 30px;padding-bottom: 10px;padding-right: 30px;padding-left: 30px;">


        <div class="row">    


<form id="form_consultar_patients"  onSubmit="return validar_formulario(this);">      

    
    <div class="form-group row"> 
        <div class="col-lg-2"><h3><font color="#BDBDBD">Consultar Patients</font></h3></div>
            <div class="col-sm-4 text-center">
                <label><font color="#585858">PATIENT ID</font></label>
            </div>
            <div class="col-sm-4 text-center">
                <label><font color="#585858">TYPE PATIENT</font></label>
            </div>         
        <div class="col-lg-2"></div>
    </div>
    
    
        <div class="form-group row">        
            <div class="col-lg-2"></div>
            <div class="col-sm-4 text-center">
                <input type="text" class="form-control" id="Pat_id" name="Pat_id" placeholder="PATIENT ID" style="text-align: center" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Pat_id'])) { echo str_replace('|',' ',$_GET['Pat_id']); }?>">
            </div>             
            <div class="col-sm-4 text-center">
                <select id="type_patient" name="type_patient"><option value="">Seleccione..</option></select>
            </div>
            <div class="col-lg-2"></div>
        </div>
    <hr>
    
        <div class="form-group row">   
            
            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Last Name</font></label>

    <div class="col-sm-3"><select id="Last_name_First_name" name="Last_name_First_name"><option value="">Seleccione..</option><option value="M">Masculino</option><option value="F">Femenino</option></select></div>


            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Active</font></label>
            <div class="col-sm-3"><input type="checkbox" id="active" name="active" <?php if(isset($_GET['active']) && $_GET['active'] == '1') { echo 'checked'; }?>></div>

            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Sex</font></label>
            <div class="col-sm-3"><select id="Sex" name="Sex"><option value="">Seleccione..</option><option value="M">Masculino</option><option value="F">Femenino</option></select></div>

        </div>



   

   
        <div class="form-group row">
        <label class="col-sm-1 form-control-label text-right"><font color="#585858">Dob</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="DOB" name="DOB" placeholder="Dob" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['DOB'])) { echo str_replace('|',' ',$_GET['DOB']); }?>"></div>   
                                            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Guardian</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Guardian" name="Guardian" placeholder="Guardian" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Guardian'])) { echo str_replace('|',' ',$_GET['Guardian']); }?>"></div>

            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Admision Date</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="admision_date" name="admision_date" placeholder="Admision Date" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['admision_date'])) { echo str_replace('|',' ',$_GET['admision_date']); }?>"></div>

        </div>
    
        <div class="form-group row">
          <label class="col-sm-1 form-control-label text-right"><font color="#585858">Discharge Date</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="discharge_date" name="discharge_date" placeholder="Discharge Date" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['discharge_date'])) { echo str_replace('|',' ',$_GET['discharge_date']); }?>"></div>

            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Social</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Social" name="Social" placeholder="Social" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Social'])) { echo str_replace('|',' ',$_GET['Social']); }?>"></div>
            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Address</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Address" name="Address" placeholder="Address" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Address'])) { echo str_replace('|',' ',$_GET['Address']); }?>"></div>

        </div>
   

   
        <div class="form-group row">
        <label class="col-sm-1 form-control-label text-right"><font color="#585858">City</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="City" name="City" placeholder="City" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['City'])) { echo str_replace('|',' ',$_GET['City']); }?>"></div>   
                                            <label class="col-sm-1 form-control-label text-right"><font color="#585858">State</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="State" name="State" placeholder="State" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['State'])) { echo str_replace('|',' ',$_GET['State']); }?>"></div>

            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Zip</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Zip" name="Zip" placeholder="Zip" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Zip'])) { echo str_replace('|',' ',$_GET['Zip']); }?>"></div>


        </div>
   
        <div class="form-group row">
                              <label class="col-sm-1 form-control-label text-right"><font color="#585858">County</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="county" name="county" placeholder="County" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['county'])) { echo str_replace('|',' ',$_GET['county']); }?>"></div>

            <label class="col-sm-1 form-control-label text-right"><font color="#585858">E Mail</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="E_mail" name="E_mail" placeholder="E Mail" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['E_mail'])) { echo str_replace('|',' ',$_GET['E_mail']); }?>"></div>
            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Phone</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Phone" name="Phone" placeholder="Phone" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Phone'])) { echo str_replace('|',' ',$_GET['Phone']); }?>"></div>

        </div>
   

    
        <div class="form-group row">
        <label class="col-sm-1 form-control-label text-right"><font color="#585858">PCP</font></label>
        <div class="col-sm-3"><select id="pcp" name="pcp"><option value="">Seleccione..</option></select></div>   
                                            <label class="col-sm-1 form-control-label text-right"><font color="#585858">PCP_NPI</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Phy_NPI" name="Phy_NPI" placeholder="PCP NPI" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Phy_NPI'])) { echo str_replace('|',' ',$_GET['Phy_NPI']); }?>"></div>

            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Ref Physician</font></label>
            <div class="col-sm-3"><select id="Ref_Physician" name="Ref_Physician"><option value="">Seleccione..</option></select></div>

        </div>
   
        <div class="form-group row">
                                            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Ref Physician NPI</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Ref_Physician_npi" name="Ref_Physician_npi" placeholder="Ref Physician NPI" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Ref_Physician_npi'])) { echo str_replace('|',' ',$_GET['Ref_Physician_npi']); }?>"></div>

            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Pri Ins</font></label>
            <div class="col-sm-3"><select id="Pri_Ins" name="Pri_Ins"><option value="">Seleccione..</option></select></div>
            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Auth</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Auth" name="Auth" placeholder="Auth" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Auth'])) { echo str_replace('|',' ',$_GET['Auth']); }?>"></div>


        </div>
   

        <div class="form-group row">
                                            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Sec Ins</font></label>
<div class="col-sm-3"><select id="Sec_INS" name="Sec_INS"><option value="">Seleccione..</option></select></div>
        <label class="col-sm-1 form-control-label text-right"><font color="#585858">Auth 2</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Auth_2" name="Auth_2" placeholder="Auth 2" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Auth_2'])) { echo str_replace('|',' ',$_GET['Auth_2']); }?>"></div>
            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Ter Ins</font></label>
            <div class="col-sm-3"><select id="Ter_Ins" name="Ter_Ins"><option value="">Seleccione..</option></select></div>

        </div>
   
        <div class="form-group row">
<label class="col-sm-1 form-control-label text-right"><font color="#585858">Auth 3</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Auth_3" name="Auth_3" placeholder="Auth 3" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Auth_3'])) { echo str_replace('|',' ',$_GET['Auth_3']); }?>"></div>
            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Mem #</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Mem_N" name="Mem_N" placeholder="Mem N" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Mem_N'])) { echo str_replace('|',' ',$_GET['Mem_N']); }?>"></div>
            <label class="col-sm-1 form-control-label text-right"><font color="#585858">Grp #</font></label>
            <div class="col-sm-3"><input type="text" class="form-control" id="Grp_N" name="Grp_N" placeholder="Grp N" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Grp_N'])) { echo str_replace('|',' ',$_GET['Grp_N']); }?>"></div>


        </div>
   

   
        <div class="form-group row">
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Intake Agmts</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Intake_Agmts" name="Intake_Agmts" placeholder="Intake Agmts" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Intake_Agmts'])) { echo str_replace('|',' ',$_GET['Intake_Agmts']); }?>"></div>
<label class="col-sm-2 form-control-label text-right"><font color="#585858">Cell</font></label>
<div class="col-sm-3"><input type="text" class="form-control" id="Cell" name="Cell" placeholder="Cell" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['Cell'])) { echo str_replace('|',' ',$_GET['Cell']); }?>"></div>           
        </div>

 

  
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
        </div>
</form>      
        <script>


            function DemoTimePicker(){
                    $('#input_time').timepicker({setDate: new Date()});
            }
            $(document).ready(function() {

                    $('#DOB').datepicker({setDate: new Date()});	
                    $('#DOB').prop('readonly', true);
                    
                    $('#admision_date').datepicker({setDate: new Date()});	
                    $('#admision_date').prop('readonly', true);
                    
                    $('#discharge_date').datepicker({setDate: new Date()});	
                    $('#discharge_date').prop('readonly', true);                    
                    
                    
                    $('#Phy_NPI').prop('readonly', true);
                    $('#Ref_Physician_npi').prop('readonly', true);

            });   
            
           
            autocompletar_radio('Name','Phy_id','physician','selector',null,null,null,null,'pcp');   
            autocompletar_radio('Name','Phy_id','physician','selector',null,null,null,null,'Ref_Physician'); 
            
            autocompletar_radio('insurance','ID','seguros','selector',null,null,null,null,'Pri_Ins'); 
            autocompletar_radio('insurance','ID','seguros','selector',null,null,null,null,'Sec_INS'); 
            autocompletar_radio('insurance','ID','seguros','selector',null,null,null,null,'Ter_Ins');
            autocompletar_radio('seguros_type_person','id_seguros_type_person','tbl_seguros_type_person','selector',null,null,null,null,'type_patient'); 
            
            autocompletar_radio('concat(First_name, Last_name) as texto','id','patients','selector',null,null,null,null,'Last_name_First_name');             
            
            $('#pcp').change(function(){
                                
                consultar_npi($(this).val(),'Phy_NPI')
                                                                              
            });
            
            $('#Ref_Physician').change(function(){
                                
                consultar_npi($(this).val(),'Ref_Physician_npi')
                                                                              
            });            
            
            
            
                LoadSelect2ScriptExt(function(){
                     $('#pcp').select2();
                    $('#pcp').select2();
                    $('#Ref_Physician').select2();
                    $('#Sex').select2();
                    $('#Last_name_First_name').select2();
                    
                    $('#Pri_Ins').select2();
                    $('#Sec_INS').select2();
                    $('#Ter_Ins').select2();
                    $('#type_patient').select2();

                });             
            
            
            
        </script>            
         <div id="resultado" class="col-lg-12"></div>        
              
             
    <?php if(isset($_GET['consultar']) && $_GET['consultar'] == 'si') {    
    
        echo '<script type="text/javascript" language="javascript">validar_formulario();</script>';
        
    } ?>
