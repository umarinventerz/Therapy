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
		echo '<script>window.location="../../home/home.php";</script>';
	}
}
$conexion = conectar();
?>
  
<script type="text/javascript">

    $(document).ready(function() {
        $('.tree').treegrid({
            expanderExpandedClass: 'glyphicon glyphicon-minus',
            expanderCollapsedClass: 'glyphicon glyphicon-plus',
            initialState: 'expanded',
            nodeIcon: 'glyphicon glyphicon-bookmark'
        });

    });
    
    function eliminar(id){
        var pacientes=$("#patient_id").val();
        var discipline_id=$("#discipline_id").val();
        var confirmar=confirm("¿Are you sure of delete is authorization?");
        if(confirmar){
            $.ajax({
                    url: '../../../mvc/controlador/gestion_clinic/delete_auth.php',
                    data: {
                        id:id
                    },
                    type: 'POST',
                    async:false,
                    dataType: "json",
                    success: function(data){
                        if(data.success){
                            window.location="../../../mvc/vista/gestion_clinic?autorizacion=si&patient_id="+pacientes+"&disciplina="+discipline_id;
                            //search_result();
                        }else{                                
                            bootbox.alert("An error has occurred, please try again");
                        }
                    }

                });
        }else{
            return false;
        }
    }
    $("a[data-target=#authorizationFormulario]").click(function(ev) {
    ev.preventDefault();
    var target = $(this).attr("href");

    // load the url and show modal on success
    $("#authorizationFormulario .modal-body").load(target, function() {             
            $("#authorizationFormulario").modal("toggle");
            $("#authorizationFormulario").modal("show");
            
    });
});
</script>
                <input type="hidden" name="patient_id" id="patient_id" value="<?=$_GET['patient']?>"/>
                <table style="background:whitesmoke;border:2px solid lightgray;border-radius:5px;color:#0084b4;" class="table tree table-striped table-condensed">


                          <tr class="treegrid-1">

                                  <td > INSURER</td> 

                                  <td>STATUS</td>  

                                  <td colspan="2"> 
                                      <a href="../gestion_clinic/authorizations_modales/modal_insurance.php" data-toggle="modal" data-target="#insurerFormulario" data-backdrop="static">
                                      <img src="../../../images/agregar.png" width="20px">
                                       ADD PAYEE   </a>
                                  </td>
                                  <td></td>

                          </tr>

                            <?php 
                                $sql_patients_seguro="SELECT PT.*,S.insurance FROM tbl_patient_seguros PT LEFT JOIN seguros S ON(PT.insure_id=S.id) where PT.patient_id=".$_GET['patient'];
                                $resultDiscipline = ejecutar($sql_patients_seguro,$conexion);
                                $discipline_id=array();
                                while($datos = mysqli_fetch_assoc($resultDiscipline)) {
                                    $discipline_id[] = $datos;                            
                                }
                                
                                
                                $j=2;
                                $z=3;
                                for($i=0;$i<count($discipline_id);$i++){
                                    
                                    if($i>0){                                        
                                        $j= $j+1;
                                        $z=$z+1;
                                    }
                            ?> 
                            
                                <tr class="treegrid-<?=$j?> treegrid-parent-1">
                                
                                  <td ><?=$discipline_id[$i]['insurance']?></td> 

                                   <td>
                                     <div style="max-height:1px !important; margin-top: 0.5px;"  class="checkbox checkbox-success checkbox-circle">
                                        <?php if($discipline_id[$i]['status']==1){?>
                                         <input id="checkbox8" class="styled" type="checkbox" checked disabled="">
                                        <?php }else{?>
                                         
                                         <input id="checkbox8" class="styled" type="checkbox" disabled="">
                                         <?php }?>
                                      <label for="checkbox8">
                                      Active
                                      </label>
                                      </div>
                                    </td> 

                                  <td>
                                      <?php 
                                        $ruta="../gestion_clinic/authorizations_modales/modal_insurance.php?update=si&seguro=".$discipline_id[$i]['insure_id']."&member=".$discipline_id[$i]['member']."&group=".$discipline_id[$i]['group_name']."&status=".$discipline_id[$i]['status']."&id=".$discipline_id[$i]['id'];
                                      ?>
                                      <a href="<?=$ruta?>" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#insurerFormulario" data-backdrop="static">
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
                                
                                <tr class="treegrid-<?=$z?> treegrid-parent-<?=$j?>">
                                
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
                                               <?php $ruta="../gestion_clinic/authorizations_modales/modal_authorization.php?id_table=".$discipline_id[$i]['id']."&id=".$discipline_id[$i]['insure_id'];?>
                                            <a  href="<?=$ruta?>" class="btn btn-info btn-xs" data-toggle="modal" data-target="#authorizationFormulario" data-backdrop="static">
                                            ADD AUTH
                                            </a>
                                           </th>

                                          </thead>
                                          <tbody>
                                          <?php 
                                          
                                            $sql_autorizacion="SELECT A.*,PS.insure_id,PS.id as seguroId,S.insurance,D.Name as disciplina,CP.cpt as cpt_name,CP.ID as id_cpt from patient_insurers_auths A LEFT JOIN tbl_patient_seguros PS ON(A.seguro_id=PS.id)LEFT JOIN seguros S ON(PS.insure_id=S.ID) LEFT JOIN discipline D ON (A.discipline_id=D.DisciplineId) LEFT JOIN cpt CP ON (A.cpt_cpt=CP.ID)  WHERE A.seguro_id=".$discipline_id[$i]['id'];
                                                $autorizaciones = ejecutar($sql_autorizacion,$conexion);
                                                $autorizaciones_id=array();
                                                while($datos = mysqli_fetch_assoc($autorizaciones)) {
                                                    $autorizaciones_id[] = $datos;                            
                                                }
                                                
                                               for($p=0;$p<count($autorizaciones_id);$p++){
                                          ?>
                                          <TR>
                                            <TD align="center"><?=$autorizaciones_id[$p]['num_aut']?></TD>
                                            <TD align="center"><?=$autorizaciones_id[$p]['cpt_name']?></TD>
                                            <TD align="center"><?=$autorizaciones_id[$p]['disciplina']?></TD>
                                            <TD align="center"><?=$autorizaciones_id[$p]['start']?></TD>
                                            <TD align="center"><?=$autorizaciones_id[$p]['end']?></TD>
                                            <TD align="center"><?=$autorizaciones_id[$p]['amount']?></TD>
                                            <TD align="center"><?=$autorizaciones_id[$p]['cpt_type']?></TD>
                                            <TD align="center">
                                                <div style="max-height:1px !important; margin-top: 0.5px;"  class="checkbox checkbox-success checkbox-circle">
                                                <?php if($autorizaciones_id[$p]['status']==1){?>
                                                    <input id="checkbox10" class="styled" type="checkbox" checked disabled="">
                                                <?php }else{?>
                                                    <input id="checkbox10" class="styled" type="checkbox" disabled="">
                                                <?php } ?>
                                                <label for="checkbox10">
                                                </label>
                                                </div>
                                            </TD>

                                            <TD align="center">
                                              <label style="white-space: nowrap;" >
                                                <?php $ruta_update="../gestion_clinic/authorizations_modales/modal_authorization.php?update=si&id=".$autorizaciones_id[$p]['id']."&insure=".$autorizaciones_id[$p]['insure_id']."&num_aut=".$autorizaciones_id[$p]['num_aut']."&cpt=".$autorizaciones_id[$p]['id_cpt']."&disciplina=".$autorizaciones_id[$p]['discipline_id']."&start=".$autorizaciones_id[$p]['start']."&end=".$autorizaciones_id[$p]['end']."&amount=".$autorizaciones_id[$p]['amount']."&cpt_type=".$autorizaciones_id[$p]['cpt_type']."&status=".$autorizaciones_id[$p]['status']."&type_visit=".$autorizaciones_id[$p]['type_visit']."&cpt_name=".$autorizaciones_id[$p]['cpt_name']."&seguro_Id=".$autorizaciones_id[$p]['seguroId']?>
                                                <a href="<?=$ruta_update?>" data-toggle="modal" data-target="#authorizationFormulario" data-backdrop="static">
                                                <img src="../../../images/edit22.png" width="20px">    
                                                </a>
                                                &nbsp; &nbsp;
                                                <a onclick="eliminar(<?=$autorizaciones_id[$p]['id']?>);">
                                                <img src="../../../images/delete22.png" width="20px">    
                                                </a>
                                              </label>   
                                            </TD>

                                          </TR>
                                               <?php } ?>
                                          </tbody>
                                      </TABLE>
                                    </TD>
                                </tr>
                        <?php $j++;$z++;} ?>

                              





                    </table>




<table  class="table tree table-striped table-condensed">


    <thead>
    <th style="text-align: center;color:black;">Auth #</th>
    <th style="text-align: center;color:black;">CPT</th>
    <th style="text-align: center;color:black;">Discipline</th>
    <th style="text-align: center;color:black;">Type</th>

    <th style="text-align: center;color:black;">Start</th>
    <th style="text-align: center;color:black;">End</th>
    <th style="text-align: center;color:black;">Company</th>

    <th style="text-align: center;color:black;">Enabled</th>


    </thead>


    <tbody>
    <?php

    $info_pat="SELECT * FROM patients
                    WHERE  id =".$_GET['patient'];
    $info_pat_id = ejecutar($info_pat,$conexion);
    while ($row=mysqli_fetch_array($info_pat_id)) {
        $salida['Pat_id'] = $row['Pat_id'];
    }

    $sql_autorizacion1="SELECT A.*,CP.type as cpt_type from authorizations A  LEFT JOIN cpt CP ON (A.CPT=CP.cpt)  WHERE A.Pat_id=".$salida['Pat_id'];
    $autorizaciones1 = ejecutar($sql_autorizacion1,$conexion);
    $autorizaciones_id1=array();
    while($datos1 = mysqli_fetch_assoc($autorizaciones1)) {
        $autorizaciones_id1[] = $datos1;
    }

    for($p=0;$p<count($autorizaciones_id1);$p++){
    ?>


    <TR>


        <TD align="center"><?=$autorizaciones_id1[$p]['Auth_#']?></TD>
        <TD align="center"><?=$autorizaciones_id1[$p]['CPT']?></TD>
        <TD align="center"><?=$autorizaciones_id1[$p]['Discipline']?></TD>
        <TD align="center"><?=$autorizaciones_id1[$p]['cpt_type']?></TD>
        <TD align="center"><?=$autorizaciones_id1[$p]['Auth_start']?></TD>
        <TD align="center"><?=$autorizaciones_id1[$p]['Auth_thru']?></TD>
        <TD align="center"><?=$autorizaciones_id1[$p]['Company']?></TD>
        <TD align="center"><?php if($autorizaciones_id1[$p]['status']==0){echo 'Inactive';} if($autorizaciones_id1[$p]['status']==1) {echo 'Active';}?></TD>





    </TR>

    <?php } ?>
    </tbody>


</table>
