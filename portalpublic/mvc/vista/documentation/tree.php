<?php
session_start();
require_once("../../../conex.php");
// if(!isset($_SESSION['user_id'])){
//   echo '<script>alert(\'Must LOG IN First\')</script>';
//   echo '<script>window.location=../../../"index.php";</script>';
// }
?>






<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>Jquery-treegrid basic example</title>

   <!-- Bootstrap core CSS -->
             
        
        <link rel="stylesheet" href="../../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../../css/font-awesome.css">
        <link href="../../../css/sweetalert2.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../../css/jquery.treegrid.css">
        <link rel="stylesheet" href="../../../css/build.css">

        <link href="../../../plugins/select2/select2.css" rel="stylesheet">



    


        <script src="../../../js/jquery.min.js"></script>
        <script src="../../../js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>  
        <script type="text/javascript" src="../../../js/jquery.treegrid.js"></script>
        <script type="text/javascript" src="../../../js/jquery.treegrid.bootstrap3.js"></script>
         
        <script src="../../../plugins/select2/select2.js"></script>   


   <style>
/*CHECBOX
*/





/*SWITCH
*/
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 2px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 14px;
}

.slider.round:before {
  border-radius: 50%;
}



/* RESIZABLE DIV */
 .panel-resizable {
            resize: vertical;
          overflow: auto
        }

</style>     

        <script type="text/javascript">

            $(document).ready(function() {
                $('.tree').treegrid({
                    expanderExpandedClass: 'glyphicon glyphicon-minus',
                    expanderCollapsedClass: 'glyphicon glyphicon-plus',
                    initialState: 'expanded',
                    nodeIcon: 'glyphicon glyphicon-bookmark'
                });

            });


function agregar_new_insurer(identificador_patient){
        
 swal({
          title: "Nueva Persona de Contacto",
          text: "Seleccione la Persona de Contacto",                    
          imageUrl: "../../../images/agregar.png",
          html: '<select id="id_persona_contacto" name="id_persona_contacto" class="form-control"><option value="">Seleccione..</option></select>',
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Agregar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {        
        
        
        if($('#id_persona_contacto').val() == ''){
            
        swal({
          title: "<h3>Seleccione una Persona de Contacto</h3>",      
          type: 'warning'          
        });                        
            
        } else {
       
        
        $.post(
                "../../controlador/contacts/agregar_nueva_persona_contacto.php",
                '&identificador_patient='+identificador_patient+'&id_persona_contacto='+$('#id_persona_contacto').val(),
                function (resultado_controlador) {
                    
                    swal({
                        title: resultado_controlador.mensaje,                      
                        type: "success"
                        });    
                        setTimeout(function(){$("#buttonReload").val("RECARGADO");findData()},1500);
                                        

                },
                "json"
        );

        return false;
          
          }
          
          }
        });  
        
        autocompletar_radio('persona_contacto','id_persona_contacto','tbl_persona_contacto','selector',null,null,null,null,'id_persona_contacto');
        
    }



function edit_authorizations(identificador_patient){
        
 swal({
          title: "Nueva Persona de Contacto",
          text: "Seleccione la Persona de Contacto",                    
          imageUrl: "../../../images/agregar.png",
          html: '<select id="id_persona_contacto" name="id_persona_contacto" class="form-control"><option value="">Seleccione..</option></select>',
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Agregar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {        
        
        
        if($('#id_persona_contacto').val() == ''){
            
        swal({
          title: "<h3>Seleccione una Persona de Contacto</h3>",      
          type: 'warning'          
        });                        
            
        } else {
       
        
        $.post(
                "../../controlador/contacts/agregar_nueva_persona_contacto.php",
                '&identificador_patient='+identificador_patient+'&id_persona_contacto='+$('#id_persona_contacto').val(),
                function (resultado_controlador) {
                    
                    swal({
                        title: resultado_controlador.mensaje,                      
                        type: "success"
                        });    
                        setTimeout(function(){$("#buttonReload").val("RECARGADO");findData()},1500);
                                        

                },
                "json"
        );

        return false;
          
          }
          
          }
        });  
        
        autocompletar_radio('persona_contacto','id_persona_contacto','tbl_persona_contacto','selector',null,null,null,null,'id_persona_contacto');
        
    }


        </script>   

  </head>
  <body>

 <div class="panel panel-success">
       
      <div class="panel-heading">DOCUMENTATION</div>
        <div class="panel-body panel-resizable" style="height:80px">

        </div>
        
      </div>








    <div class="panel panel-info">
      <div class="panel-heading">AUTHORIZATIONS</div>
        <div class="panel-body panel-resizable">

    


    <table style="background:whitesmoke;border:2px solid lightgray;border-radius:5px;color:#0084b4;" class="table tree table-striped table-condensed">
        

          <tr class="treegrid-1 ">
     
                  <td > INSURER</td> 

                  <td>STATUS</td>  
      
                  <td colspan="2"> 
                      <a href="modal_insurance.php" data-toggle="modal" data-target="#insurerFormulario" >
                      <img src="../../../images/agregar.png" width="20px">
                       ADD PAYEE   </a>
                  </td> 

          </tr>


          <tr class="treegrid-2 treegrid-parent-1">
            <td >SEGURO</td> 

             <td >
             <div style="max-height:1px !important; margin-top: 0.5px;"  class="checkbox checkbox-success checkbox-circle">
                  <input id="checkbox8" class="styled" type="checkbox" checked>
                <label for="checkbox8">
                Active
                </label>
                </div>
              </td> 
            
            <td>
                <a href="modal_insurance.php" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#insurerFormulario">
                  EDIT PAYEE
              </a>
            </td> 


          <td>
            <label style="white-space: nowrap;"  class="switch">
                <input type="checkbox" name="rx_request_ot" id="rx_request_ot" onchange="validar_ot(1);" <?php if($rx_request_ot==1){?> checked="" <?php }?>>
                <div class="slider round"></div>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       SHOW DISABLE
            </label>   
             </td>
          </tr>

              <tr class="treegrid-3 treegrid-parent-2">
                <TD colspan="4"> 
                  <TABLE  align="center"  style="width:90%" class="table tree table-bordered table-striped table-condensed">
                     <thead>
                       <th style="text-align: center;color:black;">Auth #</th>
                       <th style="text-align: center;color:black;">CPT</th>
                       <th style="text-align: center;color:black;">Discipline</th>
                       <th style="text-align: center;color:black;">Start</th>
                       <th style="text-align: center;color:black;">End</th>
                       <th style="text-align: center;color:black;">Amount</th>
                       <th style="text-align: center;color:black;">Type</th>
                       <th style="text-align: center;color:black;">Enabled</th>
                       <th  style="text-align: center;width:10%" >
                        <a  href="modal_authorization.php" class="btn btn-info btn-xs" data-toggle="modal" data-target="#authorizationFormulario">
                        ADD AUTH
                        </a>
                       </th>

                      </thead>
                      <tbody>
                      <TR>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">
                            <div style="max-height:1px !important; margin-top: 0.5px;"  class="checkbox checkbox-success checkbox-circle">
                            <input id="checkbox10" class="styled" type="checkbox" checked>
                            <label for="checkbox10">
                            </label>
                            </div>
                        </TD>
                        
                        <TD align="center">
                          <label style="white-space: nowrap;" >
                            
                            <a href="modal_authorization.php" data-toggle="modal" data-target="#authorizationFormulario" >
                            <img src="../../../images/edit22.png" width="20px">    
                            </a>
                            &nbsp; &nbsp;
                            <a href="modal_authorization.php" data-toggle="modal" data-target="#authorizationFormulario" >
                            <img src="../../../images/delete22.png" width="20px">    
                            </a>
                          </label>   
                        </TD>
                        
                      </TR>
                      </tbody>
                  </TABLE>
                </TD>
              </tr>
       
            <tr class="treegrid-4 treegrid-parent-1">
                <td >SEGURO 2</td> 

                <td >
      <div style="max-height:1px !important; margin-top: 0.5px;"    class="checkbox checkbox-success checkbox-circle">
                        <input  id="checkbox" class="styled" type="checkbox" >
                        <label  for="checkbox">
                            Active
                        </label>
                    </div>
                </td> 
                
                <td>
                <a href="modal_insurance.php" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#insurerFormulario">
                  EDIT PAYEE
              </a>
                  </td> 
              <td>

            <label style="white-space: nowrap;" class="switch">
                <input type="checkbox" name="rx_request_ot" id="rx_request_ot" onchange="validar_ot(1);" <?php if($rx_request_ot==1){?> checked="" <?php }?>>
                <div class="slider round"></div>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       SHOW DISABLE</label>     
             
              </td>
            </tr>
                 <tr class="treegrid-5 treegrid-parent-4">
                <TD colspan="4"> 
                  <TABLE  align="center"  style="width:90%" class="table tree table-bordered table-striped table-condensed">
                     <thead>
                       <th style="text-align: center;color:black;">Auth #</th>
                       <th style="text-align: center;color:black;">CPT</th>
                       <th style="text-align: center;color:black;">Discipline</th>
                       <th style="text-align: center;color:black;">Start</th>
                       <th style="text-align: center;color:black;">End</th>
                       <th style="text-align: center;color:black;">Amount</th>
                       <th style="text-align: center;color:black;">Type</th>
                       <th style="text-align: center;color:black;">Enabled</th>
                       <th  style="text-align: center;width:10%" >
                        <a  href="modal_authorization.php" class="btn btn-info btn-xs" data-toggle="modal" data-target="#authorizationFormulario">
                           
                        ADD AUTH
                        </a>
                       </th>

                      </thead>
                      <tbody>
                      <TR>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">Table</TD>
                        <TD align="center">
                            <div style="max-height:1px !important; margin-top: 0.5px;"  class="checkbox checkbox-success checkbox-circle">
                            <input id="checkbox7" class="styled" type="checkbox">
                            <label for="checkbox7">
                            </label>
                            </div>
                        </TD>
                        
                        <TD align="center">
                          <label style="white-space: nowrap;" >
                            
                            <a href="modal_authorization.php" data-toggle="modal" data-target="#authorizationFormulario" >
                            <img src="../../../images/edit22.png" width="20px">    
                            </a>
                            &nbsp; &nbsp;
                            <a href="modal_authorization.php" data-toggle="modal" data-target="#authorizationFormulario" >
                            <img src="../../../images/delete22.png" width="20px">    
                            </a>
                          </label>   
                        </TD>
                        
                      </TR>
                      </tbody>
                  </TABLE>
                </TD>
              </tr>




    </table>    





</div>
       
    </div>






<div  class="modal fade" id="insurerFormulario" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<div  class="modal fade" id="authorizationFormulario" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

  </body>




 <script type="text/javascript">

$(document).ready(function() {
                $('#insurance').select2();
               
                
              
    });

$(".panel-left").resizable({
   handleSelector: ".splitter",
   resizeHeight: false
 });

 $(".panel-top").resizable({
   handleSelector: ".splitter-horizontal",
   resizeWidth: false
 });

</script>


</html>

