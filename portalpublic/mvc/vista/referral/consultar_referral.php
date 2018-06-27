
            
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
                var parametros_formulario = $('#form_consultar_referral').serialize();
                               
                $("#resultado").empty().html('<img src="../../../imagenes/loader.gif" width="30" height="30"/>');                                
                $("#resultado").load("../referral/result_referral.php?"+parametros_formulario);
            
            return false;
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

    <?php $perfil = $_SESSION['perfil']; include "../nav_bar/nav_bar.php"; ?>
    <br><br><br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../imagenes/LOGO_1.png" alt="">
            </div>
        </div>

        <div class="row">  

<form id="form_consultar_referral"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">                
            <div class="col-sm-2"></div>
            <div class="col-sm-10" align="left"><h3><font color="#BDBDBD">Consultar Referral</font></h3>   </div>                  
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
                           
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Name</font></label>
            
                <div class="col-sm-3"><select id="Last_name_First_name" name="Last_name_First_name"><option value="">Seleccione..</option></select></div>                                           
              
                   
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Sex</font></label>

                                            <div class="col-sm-3"><select id="Sex" name="Sex"><option value="">Seleccione..</option><option value="M">Masculino</option><option value="F">Femenino</option></select></div>                                                               
                                    
                                        </div>
                                 
                                   
                                           
                                        <div class="form-group row">
                                                           
                                                       
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Active</font></label>

                                            <div class="col-sm-3">
                                                <select id="active" name="active">
                                                    <option value="">Seleccione..</option>
                                                    <option value="">All</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                            
                                            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Converted</font></label>

                                            <div class="col-sm-3">
                                                <select id="converted" name="converted">
                                                    <option value="">Seleccione..</option>
                                                    <option value="">All</option>
                                                    <option value="1">Converted</option>
                                                    <option value="0">No converted</option>
                                                </select>                                                
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
                LoadSelect2ScriptExt(function(){

                    $('#Last_name_First_name').select2();
                    $('#Sex').select2();
                    $('#type_patient').select2();
                    $('#active').select2();
                    $('#Sex').select2();
                    $('#converted').select2();                    

                });
                
            autocompletar_radio('concat(Last_name,\', \',First_name) as texto','id_referral','tbl_referral','selector',null,null,null,null,'Last_name_First_name');   
            autocompletar_radio('seguros_type_person','id_seguros_type_person','tbl_seguros_type_person','selector','<?php echo $_GET['type_patient'];?>',null,null,null,'type_patient');                         
//            
                </script>

      <?php if(isset($_GET['consultar']) && $_GET['consultar'] == 'si') { echo '<script>validar_formulario();</script>'; } ?>


        