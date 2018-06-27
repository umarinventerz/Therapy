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
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>
    <script type="text/javascript" language="javascript">

       function Validar_Formulario_component(nombre_formulario) {
                
        var nombres_campos = '';
        if($('#name').val() == ''){
            nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Component Name</td></tr></table>';

        }
          if($('#description').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Description</td></tr></table>';

        }
          if($('#discipline_id').val() == ''){
                    nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Discipline</td></tr></table>';

        }                                        
        if(nombres_campos != ''){ 

            swal({
              title: "<h3><b>Complete the following field<b></h3>",          
              type: "info",
              html: "<h4>"+nombres_campos+"</h4>",
              showCancelButton: false,
              animation: "slide-from-top",
              closeOnConfirm: true,
              showLoaderOnConfirm: false,
            });

            return false; 

        } else { 
                var campos_formulario = $("#"+nombre_formulario).serialize();
                
                $.post(
                        "../../controlador/templates/insert_templates.php",
                        campos_formulario,
                        function (resultado_controlador) {
                            swal({
                                title: "<h3><b>Message<b></h3>",          
                                type: "info",
                                html: "<h4>"+resultado_controlador.mensaje+"</h4>",
                                showCancelButton: false,
                                animation: "slide-from-top",
                                closeOnConfirm: true,
                                showLoaderOnConfirm: false,
                              });                                    
                        },
                        "json" 
                        );

                return false;
            }
        }
        function llenarDiv(divClonar,check){                        
            if($("#"+check.id).is(':checked')) {                
                $('#cadenaComponents').val($('#cadenaComponents').val()+$("#"+check.id).val()+',');
                $( "#"+divClonar ).clone().prop('id', 'clone'+divClonar ).appendTo( "#newTemplate" );
            }else{                
                var valor = $('#cadenaComponents').val().replace($("#"+check.id).val()+',','');                
                $('#cadenaComponents').val(valor);
                $( "#clone"+divClonar ).remove();
            }
        }                        
        </script>
        <style>
        .span12{    
            box-shadow: 0 0 0.5px #9e9e9e;
            padding: 15px 0px 25px 45px; 
            margin-right: 20px;
        }
        .template{            
            padding: 15px 25px 25px 45px;                         
        }
        </style>

    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br>    
    <div class="container">        
        <div class="row">            
            <div class="col-lg-3">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>                        
        </div>               
        <div class="row">
            <div class="col-lg-3">
                <h3><b>NEW TEMPLATES</b></h3>
            </div>
        </div>
        <hr>
        <form id="form_gestion_templates" onSubmit="return Validar_Formulario_component('form_gestion_templates');">
        <div class="form-group row">               
            <label class="col-lg-2">Template Name:</label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="template" name="template">
            </div>
            <label class="col-lg-2">Description:</label>
            <div class="col-lg-4">
                <textarea class="form-control" id="description" name="description" cols="30" rows="2" ></textarea>
            </div>
        </div>
        <div class="form-group row">               
            <label class="col-lg-2">Discipline:</label>
            <div class="col-lg-4">
                <select id="discipline_id" name="discipline_id"><option value="">Seleccione..</option></select>
            </div>
            <label class="col-lg-2">Type Documents:</label>
            <div class="col-lg-4">
                <select id="type_document" name="type_document"><option value="">Seleccione..</option></select>
            </div>
        </div>
        <hr style="margin-top:5px;margin-bottom:5px;">
        <div class="row">
            <div class="col-lg-5">
                <h6><b>Seleccione los componentes para construir el template:</b></h6>
                <input type="hidden" name="cadenaComponents" id="cadenaComponents" readonly="readonly">
                <hr style="margin-top:5px;margin-bottom:5px;">
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-6">
                <h6><b>New template:</b></h6>
                <hr style="margin-top:5px;margin-bottom:5px;">
            </div>
        </div>                        
        
        <div class="row">
            <?php
                $sql  = "select * from tbl_components";
                $conexion = conectar();
                $resultado = ejecutar($sql,$conexion);
                $i = 0;
            ?>   
            <div class="col-lg-6">                
                <?php
                    while ($row=  mysqli_fetch_assoc($resultado)):?>  
                    <div class="row" style="padding: 10px 0 0 0;">
                        <!-- Checkbox de los componentes que se desean elegir -->
                        <div class="col-lg-1" style="vertical-align: central;">
                            <input type="checkbox" value="<?php echo $row['id'];?>" id="checkDiv<?php echo $row['id'];?>" onclick="llenarDiv('div<?php echo $row['id'];?>',this)" id="checkDiv<?php echo $row['id'];?>">
                        </div>
                        <!-- Listar los componentes que existen luego de haber seleccionado la disciplina y el documento-->
                        <div class="col-lg-9">
                            <div class="col-sm-12"><?php echo $row['name'];?></div>
                            <div class="col-sm-12 span12" id="div<?php echo $row['id'];?>"><?php echo $row['components']; ?></div>
                        </div>
                        <!-- Fin de listar los componentes -->
                    </div>
                <?php endwhile;?>                    
            </div>
            <div class="col-lg-6">
                <div class="col-lg-12 template" id="newTemplate">
                        
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <div class="col-lg-5" align="left"></div>
            <div class="col-sm-2" align="center"> <button type="submit" class="btn btn-primary text-left">Generar</button> </div>
            <div class="col-sm-5" align="left"></div>
        </div>
        </form>
    </div>     
<script>
 LoadSelect2ScriptExt(function(){

        $('#type_document').select2();                  
        $('#discipline_id').select2();                          
    });             


autocompletar_radio('Name','DisciplineId','discipline','selector',null,null,null,null,'discipline_id');   
autocompletar_radio('type_documents','id_type_documents','tbl_doc_type_documents','selector',null,null,null,null,'type_document');
</script>
      