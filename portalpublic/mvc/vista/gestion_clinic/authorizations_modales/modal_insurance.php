<?php
session_start();
require_once("../../../../conex.php");
// if(!isset($_SESSION['user_id'])){
//   echo '<script>alert(\'Must LOG IN First\')</script>';
//   echo '<script>window.location=../../../"index.php";</script>';
// }
?>


<!doctype html>
<html>


<script type="text/javascript">
$(document).ready(function() {
  $(".js-example-basic-single").select2();
});
function insertar_insure(){
    
    var pacientes=$("#patient_id").val();
    var discipline=$("#discipline_id").val();
    var insure=$("#insurance").val();
    var member=$("#member").val();
    var group=$("#group").val();
    if($("#status").is(":checked")){
        var status="si";
    }else{
        status="no";
    }
    var referral=$("#referral").val();
    if(referral==='si'){        
        var enviar_referral='si';
    }else{
        enviar_referral='no';
    }
    
    if(pacientes!=='' && insure !=='' && member !=='' && group !==''){
        $.ajax({
                url: '../../../mvc/controlador/gestion_clinic/insert_patients_seguro.php',
                data: {
                    insure: insure,
                    member: member,
                    group: group,
                    status: status,
                    pacientes:pacientes,
                    referral:enviar_referral
                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success){    
                        $('#insurerFormulario').modal('toggle');
                        $("#patients_id_hidden").val('');    
                        $("#insurance").val('');
                        $("#member").val('');
                        $("#group").val('');
                        var externo=$("#externo").val();
                        if(externo==='si'){
                            location.reload();
                        }else{
                            window.location="../../../mvc/vista/gestion_clinic?autorizacion=si&patient_id="+pacientes+"&disciplina="+discipline;
                            //search_result('');
                        }
                    }else{                                
                        alert(data.mensaje);
                    }
                }

            });
    }else{
        alert("Please, select all fields");
    }
}

function update_insure(){    
    var pacientes=$("#patient_id").val();
    var discipline=$("#discipline_id").val();    
    var insure=$("#insurance").val();
    var id=$("#id_seguro").val();
    var member=$("#member").val();
    var group=$("#group").val();
    if($("#status").is(":checked")){
        var status="si";
    }else{
        status="no";
    }
    var referral=$("#referral").val();
    if(referral==='si'){        
        var enviar_referral='si';
    }else{
        enviar_referral='no';
    }
    
    if(insure !=='' && member !=='' && group !==''){
        $.ajax({
                url: '../../../mvc/controlador/gestion_clinic/update_seguros.php',
                data: {
                    insure: insure,
                    member: member,
                    group: group,
                    status: status,
                    id:id,
                    pacientes:pacientes,
                    referral:enviar_referral
                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success){   
                        $('#insurerFormulario').modal('toggle');
                        $("#patients_id_hidden").val('');    
                        $("#insurance").val('');
                        $("#member").val('');
                        $("#group").val('');
                        var externo=$("#externo").val();
                        if(externo==='si'){
                            location.reload();
                        }else{
                            window.location="../../../mvc/vista/gestion_clinic?autorizacion=si&patient_id="+pacientes+"&disciplina="+discipline;
                            //search_result('');
                        }
                    }else{                                
                        alert(data.mensaje);
                    }
                }

            });
    }else{
        alert("Please, select all fields");
    }
}
function cancelar(){
    var externo=$("#externo").val();
    if(externo==='si'){
        location.reload();
    }else{
        var pacientes=$("#patient_id").val();
        var discipline=$("#discipline_id").val(); 
        window.location="../../../mvc/vista/gestion_clinic?autorizacion=si&patient_id="+pacientes+"&disciplina="+discipline;
    }
}
</script>
<body>
    <?php 
        if(!isset($_GET['update'])){
    ?>

            <!--Para modales externos a gestion clinic-->
            <input type="hidden" name="externo" id="externo" value="<?=$_GET['externo']?>"/>
            <input type="hidden" name="referral" id="referral" value="<?=$_GET['referral']?>"/>
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <h4 align="center" class="modal-title" id="exampleModalLabel">P A Y E E</h4>
            </div>
            <div id="newUserHTMLBody">
                <form method="post" action="#" role="form" id="new_user_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" class="nofocus">
                    <div class="modal-body">
                        
                                      <div class="row">

                                  <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                     
                                          <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                            <select style="width:280px; padding: 3px 1px; "  name='insurance' id='insurance' class="populate placeholder" required>
                                                    <option value=''>--- SELECT ---</option>        
                                                    <?php
                                                      $conexion = conectar();
                                                      $sql  = "Select Distinct id, insurance from seguros order by insurance ASC ";  
                                                      $resultado = ejecutar($sql,$conexion);
                                                      while ($row=mysqli_fetch_array($resultado)) 
                                                      {                                                       
                                                      print("<option value='".$row["id"]."'>".$row["insurance"]."</option>");
                                                      }
                                                      ?>
                                               </select>                                   
                                         <label  for="insurance">
                                                
                                                    INSURER
                                                </label>
                                     </div>
                                  </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="member"
                                                       name="member"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="first_name">
                                                   MEMBER #
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="group"
                                                       name="group"
                                                       class="form-control tooltips"
                                                       placeholder=""
                                                       data-original-title=""
                                                       maxlength=20
                                                       minlength=3
                                                       required="required">
                                                <label for="username">
                                                   GROUP #
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div style="max-height:1px !important; margin-top: 1.5px;"  class="checkbox checkbox-success checkbox-circle ">
                                                <input id="status" class="styled" type="checkbox" checked>
                                                <label style="font-weight:bold;" for="checkbox10">
                                                ACTIVE
                                                </label>
                                            </div>
                                        </div>
                                      

                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="">
                                  
                                <a class="btn btn-default" id="cancel" name="cancel" onclick="cancelar();">
                                    <i class="fa fa-ban"></i> Cancel
                                </a>
                                <button type="button" class="btn btn-primary" name="save" id="newUserButton" onclick="insertar_insure();">
                                    <i class="fa fa-floppy-o"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


        <?php }else{?>
                <!--Para modales externos a gestion clinic-->
                <input type="hidden" name="externo" id="externo" value="<?=$_GET['externo']?>"/>
                <input type="hidden" name="referral_update" id="referral" value="<?=$_GET['referral']?>"/>
                <div class="modal-header">
               <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <h4 align="center" class="modal-title" id="exampleModalLabel">P A Y E E</h4>
            </div>
            <div id="newUserHTMLBody">
                <form method="post" action="#" role="form" id="new_user_form">
                    <input type="hidden" name="id_seguro" id="id_seguro" value="<?=$_GET['id']?>"/>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" class="nofocus">
                    <div class="modal-body">
                        
                                      <div class="row">

                                  <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                     
                                          <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                            <select style="width:280px; padding: 3px 1px; "  name='insurance' id='insurance' class="populate placeholder" required>
                                                    <option value=''>--- SELECT ---</option>        
                                                    <?php
                                                      $conexion = conectar();
                                                      $sql  = "Select Distinct id, insurance from seguros order by insurance ASC ";  
                                                      $resultado = ejecutar($sql,$conexion);
                                                      while ($row=mysqli_fetch_array($resultado)) 
                                                      {     
                                                          if($_GET['seguro']==$row["id"]){
                                                                print("<option value='".$row["id"]."' selected>".$row["insurance"]."</option>");
                                                          }else{
                                                              print("<option value='".$row["id"]."'>".$row["insurance"]."</option>");
                                                          }
                                                      }
                                                      ?>
                                               </select>                                   
                                         <label  for="insurance">
                                                
                                                    INSURER
                                                </label>
                                     </div>
                                  </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="member"
                                                       name="member"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required" value="<?=$_GET['member']?>">
                                                <label for="first_name">
                                                   MEMBER #
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="group"
                                                       name="group"
                                                       class="form-control tooltips"
                                                       placeholder=""
                                                       data-original-title=""
                                                       maxlength=20
                                                       minlength=3
                                                       required="required" value="<?=$_GET['group']?>">
                                                <label for="username">
                                                   GROUP #
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div style="max-height:1px !important; margin-top: 1.5px;"  class="checkbox checkbox-success checkbox-circle ">
                                                <?php
                                                    if($_GET['status']==1){?>
                                                ?>
                                                    <input id="status" class="styled" type="checkbox" checked>
                                                    <?php }else{ ?>
                                                    <input id="status" class="styled" type="checkbox">
                                                    <?php } ?>
                                                <label style="font-weight:bold;" for="checkbox10">
                                                ACTIVE
                                                </label>
                                            </div>
                                        </div>
                                      

                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="">
                                  
                                <a class="btn btn-default" id="cancel" name="cancel" onclick="cancelar();">
                                    <i class="fa fa-ban"></i> Cancel
                                </a>
                                <button type="button" class="btn btn-success" name="save" id="newUserButton" onclick="update_insure();">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>    
        <?php } ?>
</body>

    <script type="text/javascript">

$(document).ready(function() {
                $('#insurance').select2();
               
                
              
    });

</script>


</html>


      