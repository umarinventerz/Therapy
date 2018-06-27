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
                var parametros_formulario = $('#form_consultar_contacts').serialize();
                               
                $("#resultado").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
                $("#resultado").load("../contacts/result_contacts.php?"+parametros_formulario);

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


<form id="form_consultar_contacts"  onSubmit="return validar_formulario(this);">      

        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" align="left"><h3><font color="#BDBDBD">Consultar Contacts</font></h3>   </div>
            <div class="col-sm-2">&nbsp;<a onclick='$("#panel_derecho").load("../../../includes/texto_imagen_panel_derecho.php");' style="cursor:pointer;"><img class="img-responsive" src="../../../imagenes/imagen_sistema.jpg" alt="" width="50px" height="50px"></a></div>
        </div>
    
        <div class="form-group row">   
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Type Person</font></label>            
            <div class="col-sm-3">
                <select name="type_person" id="type_person">
                    <option value="">Seleccione..</option>
                    <option value="physician">PHYSICIAN</option>
                    <option value="patients">PATIENT</option>
                    <option value="seguros">SEGURO</option>
                    <option value="other">OTHER</option>
                </select>
            </div>
            <label class="col-sm-2 form-control-label text-right"><font color="#585858">Contact</font></label>            
            <div class="col-sm-4">
                <select name="contact" id="contact">
                    <option value="">Seleccione..</option>
                </select>
            </div>
        </div>        
  
        <div class="form-group row">
            <div class="col-sm-2" align="left"></div>
            <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Consultar</button> </div>
        </div>
</form>      
         <div id="resultado" align="center" style="width:1300px"></div>        
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p>Copyright &copy; THERAPY  AID 2016</p>
                    </div>
                </div>
                
            </footer>         
         <script>
         
                    $('#type_person').change(function(){
                        
                        var valor = $(this).val();
                        
                        if(valor == 'physician'){                       
                            autocompletar_radio('Name','Phy_id',valor,'selector',null,null,null,null,'contact');                             
                        }
                        
                        if(valor == 'patients'){                       
                            autocompletar_radio('CONCAT(Last_name,\' \', First_name)','Pat_id',valor,'selector',null,null,null,null,'contact');                             
                        }  
                        
                        if(valor == 'seguros'){                       
                            autocompletar_radio('insurance','ID',valor,'selector',null,null,null,null,'contact');                             
                        }                          
                                                                                                                       
                        
                    });  
                    
                LoadSelect2ScriptExt(function(){

                    $('#type_person').select2();
                    $('#contact').select2();

                });                     
                    
                    
         
         </script>
