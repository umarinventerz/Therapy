<?php
//parte de las cosas que hizo yoel
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
              
        var parametros_formulario = $('#form_consultar_tareas').serialize();
                       
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../tareas/result_tareas.php?"+parametros_formulario);

                <?php if($_SESSION['user_type'] == 1) { $admin = 'no'; } else { $admin = 'si'; } ?>

                
                $("#grafico_tareas").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#grafico_tareas").load("../tareas/grafico_tareas.php?"+parametros_formulario+"&admin=<?php echo $admin;?>&user_id=<?php echo $_SESSION['user_id'];?>");
                

            return false;
        }
                                                                
        </script>

    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br>
    
    <div class="panel-collapse collapse in" style="padding-top: 30px;padding-bottom: 10px;padding-right: 30px;padding-left: 30px;">

        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="center"><h3><font color="#BDBDBD">Consult Task</font></h3>   </div>
            <div class="col-sm-2"></div>
        </div>

        <div class="row">
            <div class="row col-lg-12">

<form id="form_consultar_tareas"  onSubmit="return validar_formulario();">      
    <br><br>
        <div class="form-group row">              

            <div class="col-sm-1 text-right">
                <label><font color="#585858">USER</font></label>
            </div>
            <div class="col-sm-2 text-center">
                <select name="user_system" id="user_system">
                    <option value="">Select..</option>
                </select>
            </div>

            <div class="col-sm-1 text-right">
                <label><font color="#585858">STATUS</font></label>
            </div>
            <div class="col-sm-2 text-center">
                <select name="id_status_tareas" id="id_status_tareas">
                    <option value="">Select..</option>
                </select>
            </div>

            <div class="col-sm-1 text-right">
                <label><font color="#585858">Start Date</font></label>
            </div>
            <div class="col-sm-1 text-center">
                <input style="height: 36px;" type="text" id="start_date" name="start_date" class="form-control" placeholder="Start Date" readonly="true">
                <span class="fa fa-calendar txt-danger form-control-feedback"></span>
            </div>
            <div class="col-sm-1 text-right">
                <label><font color="#585858">End Date</font></label>
            </div>

            <div class="col-sm-1 text-center">
                <input style="height: 36px;" type="text" id="end_date" name="end_date" class="form-control" placeholder="End Date" readonly="true">
                <span class="fa fa-calendar txt-danger form-control-feedback"></span>
            </div>

            <div class="col-sm-1 text-center" > <button  type="submit" class="btn btn-primary text-left" style="width: 150px;height: 36px">Find</button> </div>

        </div>


    
    <input type="hidden" id="id_tareas" name="id_tareas" readonly="true" value="<?php if(isset($_GET['id_tareas'])){ echo $_GET['id_tareas']; } ?>">    
</form> 
        </div>

    </div>
        <div class="row">
            <div class="row col-lg-12">
                <div class="row col-lg-2"></div>
        <div class="row col-lg-8" id="grafico_tareas"></div>

                <div class="row col-lg-2"></div>
            </div>
            </div>

        <script>
            function DemoTimePicker(){
                    $('#input_time').timepicker({setDate: new Date()});
            }
            $(document).ready(function() {

                    $('#start_date').datepicker({setDate: new Date()});	
                    $('#start_date').prop('readonly', true);
                    
                    $('#end_date').datepicker({setDate: new Date()});	
                    $('#end_date').prop('readonly', true);                                                                              
                    
            });   
            
            
                LoadSelect2ScriptExt(function(){                    
                    $('#user_system').select2();                   
                    $('#id_status_tareas').select2();                   
                });              
            
            autocompletar_radio('CONCAT(Last_name,\' \', First_name) as texto','user_id','user_system','selector',<?php echo $_SESSION['user_id']; ?>,null,null,null,'user_system');                                  
            autocompletar_radio('status_tareas as texto','id_status_tareas','tbl_tareas_status','selector','1',null,null,null,'id_status_tareas');                                  
            
            
            <?php if($_SESSION['user_type'] == 1) { echo "$('#user_system').prop('readonly',true);"; }?>  
        
        
                 
        var date = new Date();
        $('#start_date').val((date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear());
        $('#end_date').val((date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear());             
           
        setTimeout(function(){
            validar_formulario();             
        },500);
        
        </script>  
        
      
        <br><br><br>
         <div id="resultado" class="col-lg-12"></div>        
      