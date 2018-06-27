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
    echo '<script>window.location="home.php";</script>';
  }
} 
?>

<html>
    <head>
        <title>CK EDITOR</title>
        <link href="../../../plugins/select2/select2.css" rel="stylesheet">
        <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
        <script src="../../../plugins/jquery/jquery.min.js"></script>
        <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>    
        <script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
        <script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/bootstrap-multiselect.js"></script>
        <script src="../../../js/listas.js" type="text/javascript" ></script>
        <link rel="stylesheet" type="text/css" href="../../../css/bootstrap-multiselect.css">
    
        <link href="../../../css/portfolio-item.css" rel="stylesheet">
        <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
        <link href="../../../css/sweetalert2.min.css" rel="stylesheet">

        <script src="../../../js/funciones.js" type="text/javascript"></script>    
        <script src="../../../js/listas.js" type="text/javascript" ></script>



<!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
        <script src="ckeditor/ckeditor.js"></script>
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
                      if($('#editor').val() == ''){
                                nombres_campos += '<table align="center" border="0" width="400px"><tr><td align="left"> * Element of Component</td></tr></table>';

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
                                "../../controlador/components/insert_components.php",
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
            
        </script>
    </head>
    <body>
         <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">
                
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-2">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>                        
        </div>               
        <div class="row">
            <div class="col-lg-3"><h3><b>NEW COMPONENTS</b></h3></div>
        </div>
        <hr>
        <form id="form_gestion_component" onSubmit="return Validar_Formulario_component('form_gestion_component');">       
            <div class="form-group row">
                <div class="form-group">
                    <label class="col-lg-2">Component Name:</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group">
                    <label class="col-lg-2">Description:</label>
                    <div class="col-lg-4">
                        <textarea class="form-control" id="description" name="description" cols="30" rows="2" ></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group">
                    <label class="col-lg-2">Discipline:</label>
                    <div class="col-lg-4">
                        <select id="discipline_id" name="discipline_id"><option value="">Seleccione..</option></select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group">
                    <label class="col-lg-2">Type Documents:</label>
                    <div class="col-lg-4">
                        <select id="type_document" name="type_document" style="width: 450px;"><option value="">Seleccione..</option></select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <textarea class="ckeditor" name="editor"></textarea>
            </div>
            <div class="form-group row">
                <div class="form-group">
                    <div class="col-lg-4">
                        <button type="submit" name="accion" id="accion" class="btn btn-primary text-left" value="Save">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>
<script>
 LoadSelect2ScriptExt(function(){

        $('#type_document').select2();                  
        $('#discipline_id').select2();                          
    });             


autocompletar_radio('Name','DisciplineId','discipline','selector',null,null,null,null,'discipline_id');   
autocompletar_radio('type_documents','id_type_documents','tbl_doc_type_documents','selector',null,null,null,null,'type_document');
</script>

