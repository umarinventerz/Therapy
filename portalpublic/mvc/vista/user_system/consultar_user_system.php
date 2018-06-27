
            
<?php
/*
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['id_usuario'])){
    header("Location: http://10.100.1.250/KIDWORKS/index.php?&session=finalizada", true);
    
}
*/

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
      
   <!DOCTYPE html> 
<html lang="en"> 
 
<head> 
 
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0"> 
    <meta name="description" content=""> 
    <meta name="author" content=""> 
     
    <title>.: KIDWORKS THERAPY :.</title> 
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
 Include all compiled plugins (below), or include individual files as needed 
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>
<script src="../../../js/devoops_ext.js"></script>


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

<script src="../../../js/devoops_ext.js"></script>
      
      
    <script type="text/javascript" language="javascript">
    
       function validar_formulario(){
                var parametros_formulario = $('#form_consultar_user_system').serialize();
                               
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');                                
                $("#resultado").load("../user_system/result_user_system.php?"+parametros_formulario);
            
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

<form id="form_consultar_user_system"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD">Consultar User System</font></h3>   </div>                  
        </div>     

        
 <div class="form-group row">

            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Name</font></label>


        <div class="col-sm-8">
    <!--<input type="text" class="form-control" id="name" name="Name" placeholder="Name"  autocomplete="off" >-->
    <select style="width:250px;" name='Name' id='Name'  onchange="blockCheckBox();">
        <option value=''>--- SELECT ---</option>                 
        <?php 
        $sql  = "Select user_id ,concat(Last_name, First_name) as Name from user_system "; 
        $conexion = conectar(); 
        $resultado = ejecutar($sql,$conexion); 
        while ($row=mysqli_fetch_array($resultado))  
        {     
//            if((trim($row["Pat_id"])."-".$row["Table_name"]) == (trim($patient_id)."-".$company)) 
//                print("<option value='".$row["Pat_id"]."-".$row["Table_name"]."' selected>".$row["Patient_name"] .$row["Table_name"]    ." </option>"); 
//            else 
                print("<option value='".$row["user_id"]."'>".$row["Name"]." </option>"); 
        } 

        ?> 

    </select> 
</div>
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
                                           
                                       <!--  <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Password</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="password" name="password" placeholder="Password"  value="<?php if(isset($_GET['password'])) { echo str_replace('|',' ',$_GET['password']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Status Id</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="status_id" name="status_id" placeholder="Status Id"  value="<?php if(isset($_GET['status_id'])) { echo str_replace('|',' ',$_GET['status_id']); }?>"></div>                                                               
                                            </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">User Type</font></label>

                                            <div class="col-sm-8"><input type="text" class="form-control" id="User_type" name="User_type" placeholder="User Type"  value="<?php if(isset($_GET['User_type'])) { echo str_replace('|',' ',$_GET['User_type']); }?>"></div>                                                               
                                            </div> -->
                                                                                                       
                             </div>
                           
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
        </div>
</form>

<script>

// Run Select2 plugin on elements
function DemoSelect2(){
  $('#Name').select2(); 
  $('#Last_name').select2(); 
  $('#First_name').select2(); 
  $('#User_name').select2(); 
}
 //autocompletar_radio('concat(First_name, Last_name)','id','patients','selector',null,null,null,null,'Last_name_First_name');     



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

        </div>
    </div>


        
        <div id="resultado" class="col-lg-12"></div>
        <br><br>
        <footer> 
            <div class="row"> 
                <div class="col-lg-12 text-center"> 
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
                </div> 
            </div> 
            <!-- /.row --> 
        </footer>              


      <?php if(isset($_GET['consultar']) && $_GET['consultar'] == 'si') { echo '<script>validar_formulario();</script>'; } ?>


        