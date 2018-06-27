<?php
session_start();
require_once("../../../../conex.php");
?>
<script type="text/javascript">
$(document).ready(function() {
    $(".js-example-basic-single").select2();  
    $('#start').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	        
    $('#start').prop('readonly', true);
    $('#end').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	
    $('#end').prop('readonly', true);
});

function insertar(){
    var pacientes=$("#patient_id").val();
    var discipline_id=$("#discipline_id").val();
    var id_table=$("#id_table").val();  
    var seguro=$("#insurance").val();  
    var cpt=$("#cpt_select").val();
    var auth=$("#auth").val();
    var discipline=$("#discipline").val();
    var type=$("#type").val();
    var start=$("#start").val();
    var end=$("#end").val();
    var amount=$("#amount").val();
    var type_v_u=$("#visit").val();
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
    
    if(cpt !=='' && auth !=='' && discipline !=='' && type !=='' && start !=='' && end !=='' && amount !=='' && type_v_u !==''){
        $.ajax({
                url: '../../../mvc/controlador/gestion_clinic/insert_auth.php',
                data: {
                    seguro: seguro,
                    cpt: cpt,
                    auth: auth,
                    status: status,
                    discipline:discipline,
                    type: type,
                    start: start,
                    end:end,
                    amount:amount,
                    type_v_u: type_v_u,
                    id_table:id_table,
                    referral:enviar_referral
                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success===true){                    
                        $('#authorizationFormulario').modal('toggle');
                        $("#id_table").val('');  
                        $("#insurance").val('');  
                        $("#cpt_select").val('');
                        $("#auth").val('');
                        $("#discipline").val('');
                        $("#type").val('');
                        $("#start").val('');
                        $("#end").val('');
                        $("#amount").val('');
                        $("#visit").val('');
                        $("#id_table_update").val('');
                        var externo=$("#externo").val();
                        if(externo==='si'){
                            location.reload();
                        }else{
                            window.location="../../../mvc/vista/gestion_clinic?autorizacion=si&patient_id="+pacientes+"&disciplina="+discipline_id;
                        }
                        //search_result();
                    }else if(data.success==='duplicated'){
                        alert("There is another active Authorization Or No Pescription(RX) Available");
                        return false;
                    }else{                                
                        alert("An error has occurred, please try again");
                        return false;
                    }
                }

            });
    }else{
        alert("Please, select all fields");
    }
}

function update(){
    var pacientes=$("#patient_id").val();
    var discipline_id=$("#discipline_id").val();
    var id_table=$("#id_table_update").val();  
    var id_seguro=$("#id_table").val();  
    var seguro=$("#insurance").val();  
    var cpt=$("#cpt_select").val();
    var pre_cpt=$("#pre_cpt").val();
    if(cpt !==undefined){
        var cpt_enviar=cpt;
    }else{
        cpt_enviar=pre_cpt;
    }
    var auth=$("#auth").val();
    var discipline=$("#discipline").val();
    var type=$("#type").val();
    var pre_type=$("#pre_type").val();
    if(type!==undefined){
        var type_enviar=type;
    }else{
        type_enviar=pre_type;
    }
    
    var start=$("#start").val();
    var end=$("#end").val();
    var amount=$("#amount").val();
    var type_v_u=$("#visit").val();
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
    
    if(auth !=='' && discipline !==''  && start !=='' && end !=='' && amount !=='' && type_v_u !==''){
        $.ajax({
                url: '../../../mvc/controlador/gestion_clinic/update_auth.php',
                data: {
                    seguro: seguro,
                    cpt: cpt_enviar,
                    auth: auth,
                    status: status,
                    discipline:discipline,
                    type: type_enviar,
                    start: start,
                    end:end,
                    amount:amount,
                    type_v_u: type_v_u,
                    id_table:id_table,
                    id_seguro:id_seguro,
                    referral:enviar_referral
                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success===true){                    
                       $('#authorizationFormulario').modal('toggle');
                        $("#id_table").val('');  
                        $("#insurance").val('');  
                        $("#cpt_select").val('');
                        $("#auth").val('');
                        $("#discipline").val('');
                        $("#type").val('');
                        $("#start").val('');
                        $("#end").val('');
                        $("#amount").val('');
                        $("#visit").val('');
                        $("#id_table_update").val('');
                        var externo=$("#externo").val();
                        if(externo==='si'){
                            location.reload();
                        }else{
                            window.location="../../../mvc/vista/gestion_clinic?autorizacion=si&patient_id="+pacientes+"&disciplina="+discipline_id;
                            //search_result();
                        }

                    }else if(data.success==='duplicated'){
                        alert("There is another active Authorization");
                        return false;
                    }else{                                
                        alert("An error has occurred, please try again");
                        return false;
                    }
                }

            });
    }else{
        alert("Please, select all fields");
    }
}
function type_select(id){
    $("#type_inicial").hide();
    $("#type").val('');
    $("#type_load").load("../gestion_clinic/select_dependientes.php?type=si&id="+id);
}
function type_select_update(id){
   
    $("#type_upd").hide();
    $("#type").val('');
    $("#type_load_update").load("../gestion_clinic/select_dependientes.php?type_update=si&id="+id);
}
function cpt_select(){
   
   var id_discipline= $("#discipline").val();
   var id_type= $("#type").val();
   $("#cpt_load").load("../gestion_clinic/select_dependientes.php?cpt=si&id_discipline="+id_discipline+"&id_type="+id_type);
}
function cpt_select_update(){
   $("#cpt_upd").hide();
   var id_discipline= $("#discipline").val();
   var id_type= $("#type").val();
   $("#cpt_load_update").load("../gestion_clinic/select_dependientes.php?cpt=si&id_discipline="+id_discipline+"&id_type="+id_type);
}
/*function cancelar(){
    
    $('#authorizationFormulario').modal('hide');
    $("#id_table").val('');  
    $("#insurance").val('');  
    $("#cpt_select").val('');
    $("#auth").val('');
    $("#discipline").val('');
    $("#type").val('');
    $("#start").val('');
    $("#end").val('');
    $("#amount").val('');
    $("#visit").val('');
}*/
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

    <?php 
        if(!isset($_GET['update'])){
    ?>
            <!--Para modales externos a gestion clinic-->
            <input type="hidden" name="externo" id="externo" value="<?=$_GET['externo']?>"/>
            <input type="hidden" name="referral" id="referral" value="<?=$_GET['referral']?>"/>
            
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <h4 align="center" class="modal-title" id="exampleModalLabel">AUTHORIZATION</h4>
            </div>
            <div id="newUserHTMLBody">
                <form method="post" action="#" role="form" id="new_user_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" class="nofocus">
                    <input type="hidden" id="id_table" name="id_table" value="<?=$_GET['id_table']?>" class="nofocus">
                    <div class="modal-body">
                        
                                    <div class="row">

                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                
                                                <select style="width:280px; padding: 3px 1px; "  name='insurance' disabled id='insurance' class="populate placeholder" required>
                                                    <option value=''>--- SELECT ---</option>        
                                                    <?php
                                                      $conexion = conectar();                                                      
                                                      $sql  = "Select Distinct id, insurance from seguros order by insurance ASC ";  
                                                      $resultado = ejecutar($sql,$conexion);
                                                      while ($row=mysqli_fetch_array($resultado)) 
                                                      {     
                                                            if($_GET['id']==$row["id"]){
                                                                print("<option value='".$row["id"]."' selected='true'> ".$row["insurance"]."</option>");
                                                            }else{
                                                                print("<option value='".$row["id"]."'>".$row["insurance"]."</option>");
                                                            }
                                                          
                                                      }
                                                      ?>
                                               </select> 
                                                <label for="last_name">
                                                    INSURER
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="auth"
                                                       name="auth"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="first_name">
                                                    # AUTH
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <select style="width:280px; padding: 3px 1px; "  name='discipline' id='discipline' class="populate placeholder" required onchange="type_select(this.value);">
                                                    <option value=''>--- SELECT ---</option>        
                                                    <?php
                                                      $conexion = conectar();
                                                      $sql  = "Select DisciplineId as id, Name from discipline";  
                                                      $resultado = ejecutar($sql,$conexion);
                                                      while ($row=mysqli_fetch_array($resultado)) 
                                                      {     
                                                          
                                                              print("<option value='".$row["id"]."'>".$row["Name"]."</option>");
                                                          
                                                      }
                                                      ?>
                                               </select> 
                                                <label for="email">
                                                    DISCIPLINE
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">                                                
                                                <div id="type_load"><select id="load_type"></select></div>
                                                <label for="username">
                                                    TYPE
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                    <div id="cpt_load"><select id="load_cpt"></select></div>
                                                    <div>
                                                        <label for="username">
                                                            CPT
                                                        </label>
                                                    </div>
                                            </div>
                                        </div>
                                        
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       ondrop="return false;"
                                                       id="start"
                                                       name="start"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="last_name">
                                                    START DATE
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="end"
                                                       name="end"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="first_name">
                                                    END DATE
                                                </label>
                                            </div>
                                        </div>
                               
                                         <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       ondrop="return false;"
                                                       id="amount"
                                                       name="amount"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="last_name">
                                                    AMOUNT
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <select id="visit" name="visit">
                                                    <option value="visits">Visits</option>
                                                    <option value="units">Units</option>
                                                </select>
                                                <label  for="first_name">
                                                    TYPE: visit or units
                                                </label>
                                            </div>
                                        </div>

                                        <div align="center" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                          <div  class="checkbox checkbox-success checkbox-circle ">
                                                <input id="status" class="styled" type="checkbox" checked>
                                                <label style="font-weight:bold;" for="checkbox12">
                                                ACTIVE
                                                </label>
                                            </div>
                                        </div>
                                          <br>

                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="">
      
                                <a onclick="cancelar();" class="btn btn-default">
                                    <i class="fa fa-ban"></i> Cancel
                                </a>
                                <button type="button" class="btn btn-primary" name="save" id="newUserButton" onclick="insertar();">
                                    <i class="fa fa-floppy-o"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php }else{ ?>
            <!--Para modales externos a gestion clinic-->
            <input type="hidden" name="externo" id="externo" value="<?=$_GET['externo']?>"/>
            <input type="hidden" name="referral" id="referral" value="<?=$_GET['referral']?>"/>
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <h4 align="center" class="modal-title" id="exampleModalLabel">AUTHORIZATION</h4>
            </div>
            <div id="newUserHTMLBody">
                <form method="post" action="#" role="form" id="new_user_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" class="nofocus">
                    <input type="hidden" id="id_table_update" name="id_table_update" value="<?=$_GET['id']?>" class="nofocus">
                    <input type="hidden" id="id_table" name="id_table" value="<?=$_GET['seguro_Id']?>" class="nofocus">
                    <div class="modal-body">
                        
                                    <div class="row">

                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                
                                                <select style="width:280px; padding: 3px 1px; "  name='insurance' disabled id='insurance' class="populate placeholder" required>
                                                    <option value=''>--- SELECT ---</option>        
                                                    <?php
                                                      $conexion = conectar();                                                      
                                                      $sql  = "Select Distinct id, insurance from seguros order by insurance ASC ";  
                                                      $resultado = ejecutar($sql,$conexion);
                                                      while ($row=mysqli_fetch_array($resultado)) 
                                                      {     
                                                            if($_GET['insure']==$row["id"]){
                                                                print("<option value='".$row["id"]."' selected='true'> ".$row["insurance"]."</option>");
                                                            }else{
                                                                print("<option value='".$row["id"]."'>".$row["insurance"]."</option>");
                                                            }
                                                          
                                                      }
                                                      ?>
                                               </select> 
                                                <label for="last_name">
                                                    INSURER
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="auth"
                                                       name="auth"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required" value="<?=$_GET['num_aut']?>">
                                                <label for="first_name">
                                                    # AUTH
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <select style="width:280px; padding: 3px 1px; "  name='discipline' id='discipline' class="populate placeholder" required onchange="type_select_update(this.value);">
                                                    <option value=''>--- SELECT ---</option>        
                                                    <?php
                                                      $conexion = conectar();
                                                      $sql  = "Select DisciplineId as id, Name from discipline";  
                                                      $resultado = ejecutar($sql,$conexion);
                                                      while ($row=mysqli_fetch_array($resultado)) 
                                                      {     
                                                            if($_GET['disciplina']==$row["id"]){
                                                                print("<option value='".$row["id"]."' selected>".$row["Name"]."</option>");
                                                            }else{
                                                                print("<option value='".$row["id"]."'>".$row["Name"]."</option>");
                                                            }
                                                      }
                                                      ?>
                                               </select> 
                                                <label for="email">
                                                    DISCIPLINE
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">                                                
                                                <div id="type_upd">
                                                    <select id="pre_type" disabled>
                                                        <option value="<?=$_GET['cpt_type']?>"><?=$_GET['cpt_type']?></option>
                                                    </select>
                                                </div>
                                                <div id="type_load_update"></div>
                                                <label for="username">
                                                    TYPE
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                    <div id="cpt_upd">
                                                        <select id="pre_cpt" disabled>
                                                            <option value="<?=$_GET['cpt']?>"><?=$_GET['cpt_name']?></option>
                                                        </select>
                                                    </div>
                                                <div id="cpt_load_update"></div>
                                                    <div>
                                                        <label for="username">
                                                            CPT
                                                        </label>
                                                    </div>
                                            </div>
                                        </div>
                                        
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       ondrop="return false;"
                                                       id="start"
                                                       name="start"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required" value="<?=$_GET['start']?>">
                                                <label for="last_name">
                                                    START DATE
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="end"
                                                       name="end"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required" value="<?=$_GET['end']?>">
                                                <label for="first_name">
                                                    END DATE
                                                </label>
                                            </div>
                                        </div>
                               
                                         <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       ondrop="return false;"
                                                       id="amount"
                                                       name="amount"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required" value="<?=$_GET['amount']?>">
                                                <label for="last_name">
                                                    AMOUNT
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <select id="visit" name="visit">
                                                    
                                                    <option value="visits" <?php if($_GET['type_visit']=='visits'){ ?> checked<?php } ?>>Visits</option>
                                                    <option value="units" <?php if($_GET['type_visit']=='units'){ ?> checked<?php } ?>>Units</option>
                                                </select>
                                                <label  for="first_name">
                                                    TYPE: visit or units
                                                </label>
                                            </div>
                                        </div>

                                        <div align="center" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                          <div  class="checkbox checkbox-success checkbox-circle ">
                                              <?php 
                                                if($_GET['status']==1){
                                              ?>
                                                <input id="status" class="styled" type="checkbox" checked>
                                                <?php }else{ ?>
                                                <input id="status" class="styled" type="checkbox">
                                                <?php } ?>
                                                <label style="font-weight:bold;" for="checkbox12">
                                                ACTIVE
                                                </label>
                                            </div>
                                        </div>
                                          <br>

                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="">
      
                                <a onclick="cancelar();" class="btn btn-default">
                                    <i class="fa fa-ban"></i> Cancel
                                </a>
                                <button type="button" class="btn btn-success" name="save" id="newUserButton" onclick="update();">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>  
        <?php } ?>
<script type="text/javascript">

    $(document).ready(function() {
        $('#insurance').select2();
        $('#discipline').select2();
        $('#type').select2();
        $('#cpt').select2();
        $('#visit').select2();
        $('#load_type').select2();
        $('#load_cpt').select2();
        $("#pre_type").select2();
        $("#pre_cpt").select2();
        
        $('#dob').datepicker({setDate: new Date()});
        
        
    });

</script>