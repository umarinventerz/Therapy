
            
<?php
error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../../home/home.php";</script>';
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
                               
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../treatments_charge/result_treatments.php?"+parametros_formulario);
            
            return false;
        }



       function aceptar(id){


           var action='insert';

           $.ajax({
               type: "POST",
               url: '../../controlador/treatments_charge/ingresar_eventos.php',
               data: {id_treatment: id,action:action },
               dataType: 'JSON',
               success: function (data) {

                   swal("Event Update", "Congrtulations", "success");

                   //location.reload();


               },

               error: function (data) {
                   swal("Error!", " Contact with administrator!", "error");
               }
           })



       }

       function edit(id){

           swal("Event Update", "Congrtulations"+id, "success");
       }
        </script>

    <?php $perfil = $_SESSION['perfil'];  include "../nav_bar/nav_bar.php"; ?>
  
    
    <div class="container">



    </div>


<div id="collapse1" class="panel-collapse collapse in" style="padding-top: 30px;padding-bottom: 10px;padding-right: 30px;padding-left: 30px;">

<div class="row">


            <form id="form_consultar_treatments"  onSubmit="return validar_formulario(this);">


                   
        <div class="form-group row">
                  <div class="col-sm-1" style="width: 5%;">
            <label  class="  text-right"><font color="#585858">Desde</font></label>
                  </div>
            <div class="col-sm-1" >
                <input type="text" class="form-control" id="campo_1" name="campo_1" placeholder="Desde" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_1'])) { echo str_replace('|',' ',$_GET['campo_1']); }else{;
                    $today=date("m/d/Y",time());
                    $date=date_create($today);
                    date_sub($date,date_interval_create_from_date_string("30 days"));
                    echo date_format($date,"m/d/Y");
                   // echo $today;
            }?>">
            </div>
            <div class="col-sm-1" style="width: 5%;">
            <label class="text-right" ><font color="#585858">Hasta</font></label>
            </div>

            <div class="col-sm-1"><input type="text"  class="form-control" id="campo_90" name="campo_90" placeholder="Hasta" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_90'])) { echo str_replace('|',' ',$_GET['campo_90']); }else{;
                    echo date("m/d/Y",time());}?>"></div>

            <label class="col-sm-1  text-right"><font color="#585858">Patient Name</font></label>

            <div class="col-sm-1">
                <select class="populate placeholder"  id="campo_6" name="campo_6" style="width: 120%;"><option value="">--- SELECT PATIENT ---</option></select>
            </div>








                                            <label class="col-sm-1 text-right"><font color="#585858">Patient Insurance</font></label>

                                            <div class="col-sm-1">
                                                <select class="populate placeholder"  id="campo_7" name="campo_7" style="width: 120%;"><option value="">--- SELECT INSURANCE ---</option></select>
                                            </div>

            <?php

            $info_pat="SELECT * FROM employee
                    WHERE  id =".$_SESSION['user_id'];
            $info_pat_id = ejecutar($info_pat,$conexion);
            while ($row=mysqli_fetch_array($info_pat_id)) {
                $salida['kind_employee'] = $row['kind_employee'];

            }

            if($salida['kind_employee']=='Administrative') {   ?>
                                            <label class="col-sm-1  text-right"><font color="#585858">Therapyst Name</font></label>




                                            <div class="col-sm-1">
                                                <select class="populate placeholder"  id="campo_9" name="campo_9" style="width: 120%;"><option value="">--- SELECT THERAPYST ---</option></select>

                                            </div>
               <?php }  ?>
                                            <label class="col-sm-1  text-right"><font color="#585858">Discipline</font></label>

                                            <div class="col-sm-1">
                                                <select class="populate placeholder"  id="campo_10" name="campo_10"><option value="">--- SELECT DISCIPLINE ---</option></select>
                                                <!--<input type="text" class="form-control" id="campo_10" name="campo_10" placeholder="Campo 10" onkeyup="Mayusculas(event, this)" value="<?php if(isset($_GET['campo_10'])) { echo str_replace('|',' ',$_GET['campo_10']); }?>">-->

                                            </div>



                                        </div>
                                           





                           
        <div class="form-group row">
            <div class="col-sm-5" align="left"></div>
            <div class="col-sm-2" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
            <div class="col-sm-5" align="left"></div>
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
                $('#campo_90').datepicker({setDate: new Date()});
                $('#campo_90').prop('readonly', false);

            });   
             
                LoadSelect2ScriptExt(function(){
                    $('#campo_6').select2();
                    $('#campo_7').select2();
                    $('#campo_9').select2();
                    $('#campo_10').select2();

                });
                
            autocompletar_select('Pat_id','Patient_name','campo_6',"Select Distinct Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name");       
            autocompletar_select('insurance','insurance','campo_7',"SELECT Distinct id as id_insurance,insurance FROM seguros order by insurance");
            autocompletar_select('employee_name','employee_name','campo_9',"SELECT Distinct id,concat(last_name,', ',first_name) as employee_name FROM employee WHERE kind_employee = 'Therapist' order by employee_name");
            autocompletar_select('Name','Name','campo_10',"Select Distinct Name from discipline order by Name");       
            
            setTimeout(function(){
                validar_formulario();
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


        