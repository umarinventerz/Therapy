
            
<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
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
                var parametros_formulario = $('#form_consultar_treatments').serialize();
                               
                $("#resultado").empty().html('<img src="../../../imagenes/loader.gif" width="30" height="30"/>');                                
                $("#resultado").load("../treatments/result_treatments.php?"+parametros_formulario);
            
            return false;
        }
        
        </script>

    <?php $perfil = $_SESSION['perfil'];  include "../nav_bar/nav_bar.php"; ?>
    <br><br><br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../imagenes/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">  

<form id="form_consultar_treatments"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD">Consultar Treatments</font></h3>   </div>                  
        </div>                    
                   
        <div class="form-group row">
                           
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Treatments Date</font></label>
            
            <div class="col-sm-8"><input type="text" class="form-control" id="campo_1" name="campo_1" placeholder="Campo 1" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_1'])) { echo str_replace('|',' ',$_GET['campo_1']); }?>"></div>                                                               
            </div>                                                   
                                           
                                        <div class="form-group row">                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Patient Name</font></label>

                                            <div class="col-sm-8">
                                                <select class="populate placeholder" style="width: 250px" id="campo_6" name="campo_6"><option value="">--- SELECT PATIENT ---</option></select>
                                            </div>
                                        </div>
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Patient Insurance</font></label>

                                            <div class="col-sm-8">
                                                <select class="populate placeholder" style="width: 250px" id="campo_7" name="campo_7"><option value="">--- SELECT INSURANCE ---</option></select>                                                
                                            </div>  
                                        </div>                                                                                    
                                           
                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Therapyst Name</font></label>

                                            <div class="col-sm-8">
                                                <select class="populate placeholder" style="width: 250px" id="campo_9" name="campo_9"><option value="">--- SELECT THERAPYST ---</option></select> 

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                                           
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Discipline</font></label>

                                            <div class="col-sm-8">
                                                <select class="populate placeholder" style="width: 250px" id="campo_10" name="campo_10"><option value="">--- SELECT DISCIPLINE ---</option></select>
                                                <!--<input type="text" class="form-control" id="campo_10" name="campo_10" placeholder="Campo 10" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_10'])) { echo str_replace('|',' ',$_GET['campo_10']); }?>">-->
                                            </div>
                                        </div>                                                                                                                                                
                             </div>
                           
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
        </div>
</form>

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

        <script>
            function DemoTimePicker(){
                    $('#input_time').timepicker({setDate: new Date()});
            }
            $(document).ready(function() {

                    $('#campo_1').datepicker({setDate: new Date()});	
                    $('#campo_1').prop('readonly', false);

            });   
             
                LoadSelect2ScriptExt(function(){
                    $('#campo_6').select2();
                    $('#campo_7').select2();
                    $('#campo_9').select2();
                    $('#campo_10').select2();

                });
                
            autocompletar_select('Pat_id','Patient_name','campo_6',"Select Distinct Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name");       
            autocompletar_select('id_insurance','insurance','campo_7',"SELECT Distinct id as id_insurance,insurance FROM seguros order by insurance");                                            
            autocompletar_select('id','employee_name','campo_9',"SELECT Distinct id,concat(last_name,', ',first_name) as employee_name FROM employee WHERE kind_employee = 'Therapist' order by employee_name");                                            
            autocompletar_select('Name','Name','campo_10',"Select Distinct Name from discipline order by Name");       
            
            setTimeout(function(){
                    $("#campo_10").val('IT').change();                                                           
                },1000);
            
            <?php // if(isset($_GET['Sex'])){?>
                //$("#Sex").val('<?php echo $_GET['Sex'];?>').change();
                
//                $("#Sex").val('<?php echo $_GET['Sex'];?>').change();
//                $("#type_patient").val('<?php echo $_GET['type_patient'];?>').change();
//                setTimeout(function(){
//                    $("#pcp").val(<?php echo $pcp_name;?>).change();
//                    $("#Ref_Physician").val('<?php echo $Ref_Physician_name;?>').change();
//                    $("#Pri_Ins").val('<?php echo $Pri_Ins_insurance;?>').change();
//                    $("#Sec_INS").val('<?php echo $Sec_INS_insurance;?>').change();
//                    $("#Ter_Ins").val('<?php echo $Ter_Ins_insurance;?>').change();                                       
//                },1000);
//                
                
                
                
            <?php // }?>    
                </script>
      <?php if(isset($_GET['consultar']) && $_GET['consultar'] == 'si') { echo '<script>validar_formulario();</script>'; } ?>


        