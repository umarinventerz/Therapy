<?php 
session_start(); 
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){ 
    echo '<script>alert(\'Must LOG IN First\')</script>'; 
   echo '<script>window.location="../../../index.php";</script>';
} 

if(isset($_SESSION['name'])){ 
    $_POST['name'] = trim($_SESSION['name']); 
    $_POST['find'] = $_SESSION['find']; 
} 
 
if(isset($_GET['name'])){
  $_POST['name'] = $_GET['name'];
  $_POST['find'] = ' Submit ';  
}
  
 
if($_POST['find'] == " Submit "){ 


   
$conexion = conectar(); 
 
unset($_SESSION['name']); 
unset($_SESSION['find']); 
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

    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <!-- CSS -->
 
    <!-- End Style -->
    <script type="text/javascript" language="javascript" class="init">  
         
    $(document).ready(function() { 
        $('#example').DataTable({ 
            dom: 'Bfrtip', 
            buttons: [ 
                'copyHtml5', 
                'excelHtml5', 
                'csvHtml5', 
                'pdfHtml5' 
            ] 
        } ); 
    } ); 
 
    </script> 
    <script> 
 

function Validar_Formulario_Gestion_Patients() {              
         
    var nombres_campos = '';
    if($('#name').val() == ''){
        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Patient</td></tr></table>';
    }   
    if(($("input[name=PT]:checked").length != 1) && ($("input[name=ST]:checked").length != 1) && ($("input[name=OT]:checked").length != 1)){    
        nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Discipline</td></tr></table>';
    }
    
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

//                        swal({
//                            title: "Confirmation",
//                            text: "Are you sure?",
//                            type: "success",
//                            showCancelButton: true,   
//                            confirmButtonColor: "#3085d6",   
//                            cancelButtonColor: "#d33",   
//                            confirmButtonText: "Consultar",   
//                            closeOnConfirm: false,
//                            closeOnCancel: false
//                        }).then(function(isConfirm) {
//                            if (isConfirm === true) {    
                                var r = confirm("Are you sure that you want to make this modify?");
                                if (r == true) {
                                    var campos_formulario = $("#myForm").serialize();
                                    $.post(
                                    "../../controlador/patients/gestionar_discharge_patients.php",
                                    campos_formulario,
                                    function (resultado_controlador) {
                                        var r= 0;
                                        var mensaje_discharge = '';
                                        while(resultado_controlador.result_discharge[r]){
                                            //alert(resultado_controlador.result_discharge[r]);
                                            mensaje_discharge += '<h5><b>'+resultado_controlador.result_discharge[r]+'</b></h5><br/>';
                                            //alert(mensaje_discharge);
                                            r++;
                                        }
                                        swal({
                                        title: mensaje_discharge,                
                                        type: resultado_controlador.type
                                        }).then(function(){
                                            location.reload(true);
                                        });       

                                    },
                                    "json" 
                                    );
                                    return false;    
                                }else{
                                    return false;    
                                }
//                            }
//                        });
                        
                    }
            }
            
     
function showDiscipline(){
    if($("#name").val() != ""){
        $("#discipline_check").show();
    }else{
        $("#discipline_check").hide();
    }
}
    </script> 
</head> 
 
<body> 
 
    <!-- Navigation --> 
    
    <?php 
    if(!isset($_GET['name']))
    $perfil = $_SESSION['user_type']; include "../../vista/nav_bar/nav_bar.php"; ?>
 
 
    <!-- Page Content --> 
    <div class="container"> 
 
        <!-- Portfolio Item Heading --> 
        <div class="row"> 
            <div class="col-lg-12"> 
               <br>              
            <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div> 
        </div> 
        <!-- /.row --> 
 
        <!-- Portfolio Item Row --> 
        <div class="row"> 
  <?php if(!isset($_GET['name'])){?>
            <div class="col-md-12"> 
             <form class="form-horizontal" id="myForm" onSubmit="return Validar_Formulario_Gestion_Patients('myForm');" > 
            <br>
            <div class="col-lg-12"> 
            <h2 class="page-header">DISCHARGE PATIENT</h2> 
                <h3 class="page-header">Choose Patient from List</h3> 
            </div> 
            <div class="row">             
            <div class="col-xs-3"> 
                <div class="input-group"> 
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span> 
                <select style="width:250px;" name='name' id='name'  onchange="showDiscipline();">
                    <option value=''>--- SELECT ---</option>                 
                    <?php 
                    $sql  = "Select Distinct Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name "; 
                    $conexion = conectar(); 
                    $resultado = ejecutar($sql,$conexion); 
                    while ($row=mysqli_fetch_array($resultado))  
                    {     
                        if((trim($row["Pat_id"])."-".$row["Table_name"]) == (trim($patient_id)."-".$company)) 
                            print("<option value='".$row["Pat_id"]."-".$row["Table_name"]."' selected>".$row["Patient_name"] .$row["Table_name"]    ." </option>"); 
                        else 
                            print("<option value='".$row["Pat_id"]."-".$row["Table_name"]."'>".$row["Patient_name"] .$row["Table_name"]    ." </option>"); 
                    } 
             
                    ?> 
                       
                    </select> 
                </div> 
                
                
            </div> 
                
            <div class="form-group" id = "discipline_check" style="display:none;">
            <label class="col-sm-2 control-label">DISCIPLINE</label>
            <div class="col-sm-2">
              <div class="checkbox">
                <label>
                  <input  type="checkbox" value="1" name="OT" id="OT" 
                  <?php echo $disabled;?> <?php if($OT <> '') echo 'checked';?>/> OT
                  <i class="fa fa-square-o small"></i>
                </label>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="checkbox">
                <label>
                  <input  type="checkbox" value="1" name="PT" id="PT" 
                  <?php       echo $disabled;?> <?php if($PT <> '') echo 'checked';?>/> PT
                  <i class="fa fa-square-o small"></i>
                </label>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="checkbox">
                <label>
                  <input  type="checkbox"  value="1" name="ST" id="ST" 
                  <?php echo $disabled;?> <?php if($ST <> '') echo 'checked';?>/> ST
                  <i class="fa fa-square-o small"></i>
                </label>
              </div>
            </div>
          </div>


          
<!-- <div class="form-group" >

CHOOSE DISCIPLINES?<br />
<input type="checkbox" name="OT" value="1" />OT<br />
<input type="checkbox" name="PT" value="1" />PT<br />
<input type="checkbox" name="ST" value="1" />ST<br />

 
</div> -->
 





            
            </div> 
           
                <hr> 
            <div class="row"> 
            <div class="col-xs-12"> 
                <div class="input-group"> 
                <input id='find' name='find' type='submit' value=" Submit " class="btn btn-success btn-lg"> 
        &nbsp&nbsp           <input name='reset' type='button' value=" Reset " onclick= "window.location.href = 'discharge_patient.php';" class="btn btn-danger btn-lg">             
                </div> 
            </div>             
            </div> 
        </form>               
            </div>  
            <?php }?>       
        </div> 
        <!-- /.row --> 
    <hr> 
        <!-- Related Projects Row --> 
        <div class="row"> 
 
            <div class="col-lg-12"> 
        <?php    
    
        if($_POST['find'] == ' Submit ') { 
             
        ?> 
                
    



 
</div> 
 
 
                 
            <tbody/> 
             
        <?php }?>     
            </div>         
        </div> 
        <!-- /.row --> 
        <!-- Footer --> 
        <footer> 
            <div class="row"> 
                <div class="col-lg-12"> 
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
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


</html>
