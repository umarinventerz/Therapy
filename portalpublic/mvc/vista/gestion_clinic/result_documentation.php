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
$conexion = conectar();
if(isset($_GET['patient_id'])){


    $info_pat="SELECT * FROM patients
                    WHERE  id =".$_GET['patient_id'];
    $info_pat_id = ejecutar($info_pat,$conexion);
    while ($row=mysqli_fetch_array($info_pat_id)) {
        $salida['Pat_id'] = $row['Pat_id'];
    }


    if(isset($_GET['discipline_id']) && $_GET['discipline_id']!= '') {
        $info_pat = "SELECT * FROM discipline
                    WHERE  DisciplineId =" . $_GET['discipline_id'];
        $info_pat_id = ejecutar($info_pat, $conexion);
        while ($row = mysqli_fetch_array($info_pat_id)) {
            $salida['company'] = $row['Name'];
        }
    }
    # $info_pat="SELECT * FROM patients
 #                   WHERE  id =".$_GET['patient_id'];
 ##   while ($row=mysqli_fetch_array($info_pat_id)) {
 #       $salida['Pat_id'] = $row['Pat_id'];
 #   }

#echo $info_pat;
    $patient_id = ' AND Patient_ID = \''.$salida['Pat_id'].'\''; }


    else{
    $patient_id = '';

}



if(isset($_GET['discipline_id']) && $_GET['discipline_id']!= ''){$discipline_id = ' AND Discipline = \''.$_GET['discipline_id'].'\''; }else{ $discipline_id = ''; }
if(isset($_GET['from']) && $_GET['from']!= ''){$from = ' AND Issue_date BETWEEN \''.date('Y-m-d', strtotime($_GET['from'])).'\' AND \''.date('Y-m-d', strtotime($_GET['to'])).'\''; }else{ $from = ''; }
if(isset($_GET['to']) && $_GET['to']!= ''){$to = ' AND End_date BETWEEN \''.date('Y-m-d', strtotime($_GET['from'])).'\' AND \''.date('Y-m-d', strtotime($_GET['to'])).'\''; }else{ $to = ''; }





$reporte = array();
if($patient_id != ''){
    $sql  = "SELECT *, pat.Pat_id as pat_id_long, pat.Address, pat.Phone FROM prescription p "
            . " INNER JOIN patients pat ON pat.Pat_id = Patient_ID "
            . " LEFT JOIN discipline d ON d.DisciplineID = p.Discipline "
            . "WHERE true ".$patient_id.$discipline_id.$from.$to." AND (p.deleted = 0 OR p.deleted IS NULL) ORDER BY discipline,status asc , Issue_date desc;"; 


    $resultado = ejecutar($sql,$conexion);

    $i = 1;      
    while($datos = mysqli_fetch_assoc($resultado)) {            
        $reporte[$i]['documents'] = 'Prescriptions';
        $reporte[$i]['id_documents'] = $datos['id_prescription'];
        $reporte[$i]['onclick'] = 'selectPrescription('.$datos['id_prescription'].',\''.$datos['Patient_ID'].'\',\''.$datos['Patient_name'].'\',\''.$datos['Discipline'].'\',\''.$datos['Table_name'].'\',\''.$datos['Diagnostic'].'\',\''.trim($datos['pat_id_long']).'\',\''.$datos['Address'].'\',\''.$datos['Phone'].'\',this);';
        $reporte[$i]['from'] = $datos['Issue_date'];
        $reporte[$i]['to'] = $datos['End_date'];
        $reporte[$i]['user'] = '-';
        $reporte[$i]['discipline'] = $datos['Name'];
        $reporte[$i]['status'] = $datos['status'];
        $reporte[$i]['md_signed'] = $datos['Eval_done'];
        $reporte[$i]['parent'] = '';           
        $reporte[$i]['id'] = $i;           

        $sql  = "SELECT *,d.Name as diname , e.id as id_evaluations,e.status_id as status_eval,pat.id as pat_id_long, pat.Address, 
            pat.Phone,pat.DOB, pat.PCP, pat.PCP_NPI, c.facility_address, c.tin_number,e.id_prescription FROM tbl_evaluations e "
                . " INNER JOIN patients pat ON pat.id = patient_id "
                . " LEFT JOIN user_system us ON us.user_id = e.id_user "
                . " LEFT JOIN discipline d ON d.DisciplineID = e.discipline_id "
                . " LEFT JOIN companies c ON c.company_name = e.company "
            . "WHERE id_prescription = ".$datos['id_prescription'].' AND (e.deleted = 0 OR e.deleted IS NULL) ;';

        

        $resultado_eval = ejecutar($sql,$conexion);
        while($datos_eval = mysqli_fetch_assoc($resultado_eval)) {            
            $i++;            
            $reporte[$i]['documents'] = 'Evaluation';
            $reporte[$i]['id_prescription'] = $datos_eval['id_prescription'];
            $reporte[$i]['id_documents'] = $datos_eval['id_evaluations'];
            $reporte[$i]['onclick'] = 'selectEvaluation('.$datos_eval['id_evaluations'].',\''.$datos_eval['patient_id'].'\',\''.$datos_eval['name'].'\',\''.$datos_eval['discipline_id'].'\',\''.$datos_eval['company'].'\',\''.$datos_eval['diagnostic'].'\',\''.trim($datos_eval['pat_id_long']).'\',\''.$datos_eval['Address'].'\',\''.$datos_eval['Phone'].'\',\''.$datos_eval['DOB'].'\',\''.$datos_eval['PCP'].'\',\''.$datos_eval['PCP_NPI'].'\',\''.$datos_eval['facility_address'].'\',\''.$datos_eval['tin_number'].'\',this);';
            $reporte[$i]['from'] = $datos_eval['from'];
            $reporte[$i]['to'] = $datos_eval['to'];
            $reporte[$i]['status'] = $datos_eval['status_eval'];
            $reporte[$i]['user'] = $datos_eval['First_name'].' '.$datos_eval['Last_name'];
            $reporte[$i]['discipline'] = $datos_eval['diname'];
            $reporte[$i]['md_signed'] = $datos_eval['md_signed'];
            $reporte[$i]['parent'] = ($i - 1);           
            $reporte[$i]['id'] = $i;
            $sql  = "SELECT *,d.Name as diname,d.DisciplineId FROM careplans c"
            . " LEFT JOIN user_system us ON us.user_id = c.user_id "
            . " LEFT JOIN discipline d ON d.DisciplineID = c.Discipline "
            . "WHERE evaluations_id = ".$datos_eval['id_evaluations'].' AND (c.deleted = 0 OR c.deleted IS NULL)  ORDER BY status DESC;'; 

            $resultado_poc = ejecutar($sql,$conexion);       
            $parent = $i;
            $parent_summary = $i;
            $parent_discharge = $i;

            
            
            $sql  = "SELECT *,d.Name as diname FROM tbl_summary s"
            . " LEFT JOIN tbl_evaluations e ON e.id = s.id_evaluations"
            . " LEFT JOIN user_system us ON us.user_id = s.id_user "
            . " LEFT JOIN discipline d ON d.DisciplineID = e.discipline_id "
            . " WHERE id_evaluations = ".$datos_eval['id_evaluations'].' AND (s.deleted = 0 OR s.deleted IS NULL)  ORDER BY status DESC;'; 
            $resultado_sum = ejecutar($sql,$conexion);       
            $parent = $parent_summary;
            while($datos_sum = mysqli_fetch_assoc($resultado_sum)) {
                $i++;
                $reporte[$i]['documents'] = 'Summary';
                $reporte[$i]['id_documents'] = $datos_sum['id_summary'];
                $reporte[$i]['onclick'] = '';
                $reporte[$i]['from'] = $datos_sum['start_date'];
                $reporte[$i]['to'] = $datos_sum['end_date'];
                $reporte[$i]['user'] = $datos_sum['First_name'].' '.$datos_sum['Last_name'];
                $reporte[$i]['discipline'] = $datos_sum['diname'];
                $reporte[$i]['status'] = $datos_sum['status'];
                $reporte[$i]['md_signed'] = $datos_sum['signed'];
                $reporte[$i]['parent'] = $parent;           
                $reporte[$i]['id'] = $i;            
            }
            
            $sql  = "SELECT *,di.Name as diname FROM tbl_discharge d"
            . " LEFT JOIN tbl_evaluations e ON e.id = d.id_evaluation"
            . " LEFT JOIN user_system us ON us.user_id = d.id_user "
            . " LEFT JOIN discipline di ON di.DisciplineID = e.discipline_id "  
            . " WHERE id_evaluation = ".$datos_eval['id_evaluations'].' AND (d.deleted = 0 OR d.deleted IS NULL)  ORDER BY status DESC;'; 
            $resultado_dis = ejecutar($sql,$conexion);       
            $parent = $parent_discharge;
            while($datos_dis = mysqli_fetch_assoc($resultado_dis)) {
                $i++;
                $reporte[$i]['documents'] = 'Discharge';
                $reporte[$i]['id_documents'] = $datos_dis['id_discharge'];
                $reporte[$i]['onclick'] = '';
                $reporte[$i]['from'] = $datos_dis['start_discharge'];
                $reporte[$i]['to'] = $datos_dis['end_discharge'];
                $reporte[$i]['user'] = $datos_dis['First_name'].' '.$datos_dis['Last_name'];
                $reporte[$i]['discipline'] = $datos_dis['diname'];
                $reporte[$i]['status'] = $datos_dis['status'];
                $reporte[$i]['md_signed'] = $datos_dis['signature_discharge'];
                $reporte[$i]['parent'] = $parent;           
                $reporte[$i]['id'] = $i;       
                $reporte[$i]['DisciplineId'] = $datos_note['DisciplineId'];
            }


            while($datos_poc = mysqli_fetch_assoc($resultado_poc)) {
                $i++;
                $reporte[$i]['documents'] = 'POC';
                $reporte[$i]['id_documents'] = $datos_poc['id_careplans'];
                $reporte[$i]['onclick'] = 'selectPoc('.$datos_poc['id_careplans'].',\''.$datos_eval['patient_id'].'\',\''.$datos_eval['name'].'\',\''.$datos_poc['Discipline'].'\',this);';
                $reporte[$i]['from'] = $datos_poc['POC_due'];
                $reporte[$i]['to'] = $datos_poc['Re_Eval_due'];
                $reporte[$i]['user'] = $datos_poc['First_name'].' '.$datos_poc['Last_name'];
                $reporte[$i]['discipline'] = $datos_poc['diname'];
                $reporte[$i]['status'] = $datos_poc['status'];
                $reporte[$i]['md_signed'] = $datos_poc['MD_Eval_signed'];
                $reporte[$i]['parent'] = $parent;           
                $reporte[$i]['id'] = $i;
                $reporte[$i]['DisciplineId'] = $datos_poc['DisciplineId'];
                
                $sql  = "SELECT *,d.Name as diname FROM tbl_notes_documentation n"
                . " LEFT JOIN user_system us ON us.user_id = n.user_id "
                . " LEFT JOIN discipline d ON d.DisciplineID = n.discipline_id "    
                . "WHERE id_careplans = ".$datos_poc['id_careplans']." AND (n.deleted = 0 OR n.deleted IS NULL) ORDER BY created DESC;"; 
                $resultado_poc = ejecutar($sql,$conexion);       
                $parent = $i;
                while($datos_note = mysqli_fetch_assoc($resultado_poc)) {
                    $i++;
                    $reporte[$i]['documents'] = 'Note';
                    $reporte[$i]['id_documents'] = $datos_note['id_notes'];
                    $reporte[$i]['onclick'] = '';
                    $reporte[$i]['from'] = date('Y-m-d', strtotime($datos_note['created']));
                   // $reporte[$i]['from'] ='hola';
                    $reporte[$i]['to'] = '-';
                    $reporte[$i]['user'] = $datos_note['First_name'].' '.$datos_note['Last_name'];
                    $reporte[$i]['discipline'] = $datos_note['diname'];
                    $reporte[$i]['status'] = $datos_note['status'];
                    $reporte[$i]['md_signed'] = $datos_note['signed'];
                    $reporte[$i]['parent'] = $parent;           
                    $reporte[$i]['id'] = $i;
                    $reporte[$i]['DisciplineId'] = $datos_note['DisciplineId'];
                } 
            }
            
           
        }
        $i++;
    }
}

?>
<table class="table table-bordered hidden-sm" id="table" >
    <thead>
    <tr class="bg-card-header">
        <th>Documents</th>
        <th>Select</th>
        <th>From Date</th>
        <th>Due Date</th>
        <th>User</th>
        <th>Discipline</th>
        <th>Status</th>
        <th>MD Signed</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
        <?php if(!empty($reporte)):?>
            <?php foreach($reporte as $r):?>               

                    <?php if($r['documents'] == 'Prescriptions' && $r['discipline'] == 'ST') :?>
                    <tr class="warning" data-node="treetable-<?=$r['id']?>" <?=($r['parent'] == '')?'':'data-pnode="treetable-parent-'.$r['parent'].'"';?>>
                    <?php elseif($r['documents'] == 'Prescriptions' && $r['discipline'] == 'OT')  :?>
                    <tr class="info" data-node="treetable-<?=$r['id']?>" <?=($r['parent'] == '')?'':'data-pnode="treetable-parent-'.$r['parent'].'"';?>>
                    <?php elseif($r['documents'] == 'Prescriptions' && $r['discipline'] == 'PT')  :?>
                     <tr class="active" data-node="treetable-<?=$r['id']?>" <?=($r['parent'] == '')?'':'data-pnode="treetable-parent-'.$r['parent'].'"';?>>    
                    <?php elseif($r['documents'] == 'Prescriptions' && $r['discipline'] == 'IT')  :?>
                     <tr class="success" data-node="treetable-<?=$r['id']?>" <?=($r['parent'] == '')?'':'data-pnode="treetable-parent-'.$r['parent'].'"';?>>    
                     <?php elseif($r['documents'] == 'Prescriptions' && $r['discipline'] == 'MH')  :?>
                     <tr class="danger" data-node="treetable-<?=$r['id']?>" <?=($r['parent'] == '')?'':'data-pnode="treetable-parent-'.$r['parent'].'"';?>>   
                    <?php else:?>
                     <tr data-node="treetable-<?=$r['id']?>" <?=($r['parent'] == '')?'':'data-pnode="treetable-parent-'.$r['parent'].'"';?>>  
                    <?php endif;?>

                    <td><?=$r['documents']?></td>


                    <?php if($r['status'] == 1 || $r['status'] == 2):?>
                    <td align="center">
                        <input type="radio" onclick="<?=$r['onclick']?>" id="elementRadio" name="elementRadio"/>
                    </td>
                    <?php else:?>
                    <td align="center">-</td>
                    <?php endif;?>

                <?php if($r['documents'] == 'Note'){?>

                    <td>  <?php echo $r['from'];?></td>

                <?php }?>
                         <?php if($r['documents'] != 'Note'){?>
                    <td><?=($r['from']!='-')?date('Y-m-d', strtotime($r['from'])):'-';?></td>
                    <td><?=($r['to']!='-')?date('Y-m-d', strtotime($r['to'])):'-';?></td>

                <?php }?>
                    <td><?=$r['user']?></td>
                    <td><?=$r['discipline']?></td>
                    


                    
                   
                    <?php if($r['status'] == 1):?>
                 <td style="font-weight:bold;color:#428bca" align="center">
                     <?php elseif($r['status'] == 2):?>   
                    <td  style="font-weight:bold;color:#5cb85c" align="center"> 
                    <?php else:?>
                    <td  style="font-weight:bold;color:#d9534f" align="center"> 
                    <?php endif;?>

                    <?php if(($r['status']==1)){
                                echo 'In Progress'; 
                        }else{ 
                            if(($r['status']==2)){
                                echo 'Active';
                            }else{
                                if(($r['status']==3)){
                                    echo 'Inactive';  
                                }
                            }                                
                        }
                    ?>                                                
                    </td>
                    



                    <td><?=($r['md_signed']==1)?'Signed':'No Signed';?></td>
                    <td>
                        <div class="btn-group">            
                            <a href="#" data-rel="tooltip" data-original-title="Editar" class="dropdown-toggle btn btn-primary" data-toggle="dropdown"><i class="fa fa-cogs"></i>&nbsp;</a>
                            <ul class="dropdown-menu pull-right">
                                <li align= "center" style="hidden-align: center">    
                                   <?php if($r['documents'] == 'Evaluation'):?>
                                    <a onclick="modificarDocumento('<?=$r['id_documents']?>','<?=$r['documents']?>',<?=$r['id_prescription']?>,null);" style="cursor: pointer;" class="ruta">Edit</a>
                                   <?php else:?>
                                    <a onclick="modificarDocumento('<?=$r['id_documents']?>','<?=$r['documents']?>',null,<?=$r['DisciplineId']?>);" style="cursor: pointer;" class="ruta">Edit</a>
                                   <?php endif;?>
                                </li>
                                <li align= "center" style="hidden-align: center">
                                    <a onclick="eliminarDocumento(<?=$r['id_documents']?>,'<?=$r['documents']?>');" style="cursor: pointer;" class="ruta">Delete</a>
                                </li>
                                <?php
                                    if($r['documents']=='Prescriptions'){
                                ?>
                                    <li align= "center" style="hidden-align: center">
                                        <a href="pdf/preescription.php?id=<?=$r['id_documents']?>" target="_blank" style="cursor: pointer;" class="ruta">Print</a>
                                    </li>
                                <?php } ?>
                                <?php
                                    if($r['documents']=='Evaluation'){
                                ?>
                                    <li align= "center" style="hidden-align: center">
                                        <a href="pdf/evaluation.php?id=<?=$r['id_documents']?>" target="_blank" style="cursor: pointer;" class="ruta">Print</a>
                                    </li>
                                <?php } ?>
                                <?php
                                    if($r['documents']=='POC'){
                                ?>
                                    <li align= "center" style="hidden-align: center">
                                        <a href="pdf/poc.php?id=<?=$r['id_documents']?>" target="_blank" style="cursor: pointer;" class="ruta">Print</a>
                                    </li>
                                <?php } ?>
                                    
                                 <?php
                                    if($r['documents']=='Note'){
                                ?>
                                    <li align= "center" style="hidden-align: center">
                                        <a href="pdf/note.php?id=<?=$r['id_documents']?>" target="_blank" style="cursor: pointer;" class="ruta">Print</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </td>
                </tr>                
            <?php endforeach;?>
        <?php else:?>
            <tr data-node="treetable-1">
                <td colspan="9"> No existen registros para mostrar</td>
            </tr>
        <?php endif;?>
    </tbody>
</table>
<input type="hidden" id="prescSelect" name="prescSelect" value="" readonly>
<input type="hidden" id="patieSelect" name="patieSelect" value="" readonly>
<input type="hidden" id="patieLongSelect" name="patieLongSelect" value="" readonly>
<input type="hidden" id="patieAddressSelect" name="patieAddressSelect" value="" readonly>
<input type="hidden" id="patiePhoneSelect" name="patiePhoneSelect" value="" readonly>
<input type="hidden" id="patieNameSelect" name="patieNameSelect" value="" readonly>
<input type="hidden" id="disciSelect" name="disciSelect" value="" readonly>
<input type="hidden" id="compaSelect" name="compaSelect" value="" readonly>
<input type="hidden" id="diagnSelect" name="diagnSelect" value="" readonly>

<input type="hidden" id="evalSelect" name="evalSelect" value="" readonly>
<input type="hidden" id="evalPatieSelect" name="evalPatieSelect" value="" readonly>
<input type="hidden" id="evalPatieLongSelect" name="evalPatieLongSelect" value="" readonly>
<input type="hidden" id="evalPatieAddressSelect" name="evalPatieAddressSelect" value="" readonly>
<input type="hidden" id="evalPatiePhoneSelect" name="evalPatiePhoneSelect" value="" readonly>
<input type="hidden" id="evalPatieDobSelect" name="evalPatieDobSelect" value="" readonly>
<input type="hidden" id="evalPatiePcpSelect" name="evalPatiePcpSelect" value="" readonly>
<input type="hidden" id="evalPatiePcpNpiSelect" name="evalPatiePcpNpiSelect" value="" readonly>
<input type="hidden" id="evalPatieNameSelect" name="evalPatieNameSelect" value="" readonly>
<input type="hidden" id="evalDisciSelect" name="evalDisciSelect" value="" readonly>
<input type="hidden" id="evalCompaSelect" name="evalCompaSelect" value="" readonly>
<input type="hidden" id="evalCompaAddressSelect" name="evalCompaAddressSelect" value="" readonly>
<input type="hidden" id="evalCompaPhoneSelect" name="evalCompaPhoneSelect" value="" readonly>
<input type="hidden" id="evalDiagnSelect" name="evalDiagnSelect" value="" readonly>

<input type="hidden" id="pocSelect" name="pocSelect" value="" readonly>
<input type="hidden" id="pocPatieSelect" name="pocPatieSelect" value="" readonly>
<input type="hidden" id="pocPatieNameSelect" name="pocPatieNameSelect" value="" readonly>
<input type="hidden" id="pocDisciSelect" name="pocDisciSelect" value="" readonly>

<input type="hidden" id="id_document" name="id_document" readonly>
<input type="hidden" id="document" name="document" readonly>

<input type="hidden" id="action" name="action" readonly>
<script>
    $(function() {
        $("#table").treeFy({
            treeColumn: 0
        });
    });
    function selectPrescription(id_prescription,patient,patient_name,discipline,company,diagnostic,pat_long,address,phone,check){  
        limpiarHidden();
        if($("#prescSelect").val()!= '' && $(check).prop('checked')){
            $("#prescSelect").val('');
            $("#patieLongSelect").val('');            
            $("#patieAddressSelect").val('');            
            $("#patiePhoneSelect").val('');            
            $("#patieSelect").val('');
            $("#patieNameSelect").val('');
            $("#disciSelect").val('');
            $("#compaSelect").val('');
            $("#diagnSelect").val('');
            $(check).prop('checked',false);         
        }else{
            $("#prescSelect").val(id_prescription);
            $("#patieSelect").val(patient);
            $("#patieLongSelect").val(pat_long);
            $("#patieAddressSelect").val(address);            
            $("#patiePhoneSelect").val(phone); 
            $("#patieNameSelect").val(patient_name);
            $("#disciSelect").val(discipline);
            $("#compaSelect").val(company);
            $("#diagnSelect").val(diagnostic);
        }                        
    }
    function selectEvaluation(id_eval,patient,patient_name,discipline,company,diagnostic,pat_long,address,phone,dob,pcp,pcp_npi,company_address,company_phone,check){        
        limpiarHidden();
        if($("#evalSelect").val()!= '' && $(check).prop('checked')){
            $("#evalSelect").val('');
            $("#evalPatieSelect").val('');
            $("#evalPatieNameSelect").val('');
            $("#evalDisciSelect").val('');
            $("#evalCompaSelect").val('');
            $("#evalDiagnSelect").val('');
            $("#evalPatieLongSelect").val('');            
            $("#evalPatieAddressSelect").val('');            
            $("#evalPatiePhoneSelect").val('');
            $("#evalPatieDobSelect").val('');
            $("#evalPatiePcpSelect").val('');
            $("#evalPatiePcpNpiSelect").val('');
            $("#evalCompaAddressSelect").val('');
            $("#evalCompaPhoneSelect").val('');
            $(check).prop('checked',false);
        }else{
            $("#evalSelect").val(id_eval);
            $("#evalPatieSelect").val(patient);
            $("#evalPatieNameSelect").val(patient_name);
            $("#evalDisciSelect").val(discipline);
            $("#evalCompaSelect").val(company);
            $("#evalDiagnSelect").val(diagnostic);   
            $("#evalPatieLongSelect").val(pat_long);            
            $("#evalPatieAddressSelect").val(address);            
            $("#evalPatiePhoneSelect").val(phone);
            $("#evalPatieDobSelect").val(dob);    
            $("#evalPatiePcpSelect").val(pcp);
            $("#evalPatiePcpNpiSelect").val(pcp_npi);
            $("#evalCompaAddressSelect").val(company_address);
            $("#evalCompaPhoneSelect").val(company_phone);
        }
    } 
    function selectPoc(id_eval,patient,patient_name,discipline,check){        
        limpiarHidden();
        if($("#pocSelect").val()!= '' && $(check).prop('checked')){
            $("#pocSelect").val('');
            $("#pocPatieSelect").val('');
            $("#pocPatieNameSelect").val('');
            $("#pocDisciSelect").val('');        
            $(check).prop('checked',false);
        }else{
            $("#pocSelect").val(id_eval);
            $("#pocPatieSelect").val(patient);
            $("#pocPatieNameSelect").val(patient_name);
            $("#pocDisciSelect").val(discipline);            
        }
    }
    
    function limpiarHidden(){
        $("#prescSelect").val('');
        $("#patieSelect").val('');
        $("#patieNameSelect").val('');
        $("#disciSelect").val('');
        $("#compaSelect").val('');
        $("#diagnSelect").val('');
        
        $("#evalSelect").val('');
        $("#evalPatieSelect").val('');
        $("#evalPatieNameSelect").val('');
        $("#evalDisciSelect").val('');
        $("#evalCompaSelect").val('');
        $("#evalDiagnSelect").val('');
            
        $("#pocSelect").val('');
        $("#pocPatieSelect").val('');
        $("#pocPatieNameSelect").val('');
        $("#pocDisciSelect").val('');        
    }
   
    function eliminarDocumento(id,document){                        
        campos_formulario = $("#formPoc").serialize();
        var campos_formulario = '&id_document='+id+'&document='+document;
        var confirmar=confirm("¿You are sure to delete this record?");
        if(confirmar){
            $.post(
                "../../controlador/gestion_clinic/delete_documents.php",
                campos_formulario,
                function (resultado_controlador) {
                    if(resultado_controlador.mensaje == 'ok'){
                        swal({
                            type: 'success',                          
                            html: '<h3><b>Record deleted successfully</b></h3>',
                            timer: 3000    
                        });                     
                    }else{
                        if(resultado_controlador.mensaje == 'no'){
                                swal({
                                type: 'error', 
                                html: '<h3><b>Error to deleted the register</b></h3>',
                                timer: 3000    
                            });                     
                        }
                    }
                    search_result();                                                
                },
                "json" 
            ); 
        }else{
            return false;
        }
    }
    
    function modificarDocumento(id,document,id_prescription,discipline){   
        
        var modal;
        $("#id_document").val(id);
        $("#action").val('edit');
        $("#documentAdd").val(document);
        if(document == 'Evaluation') {
            //modal = 'modalAddEvaluation';
            window.location="../../../mvc/vista/gestion_clinic/?id_preescription="+id_prescription+"&edit=si&id_eval="+id;
        }
        if(document === 'POC') {
            //modal = 'modalAddEvaluation';
            window.location="../../../mvc/vista/gestion_clinic/?poc=si&id_poc="+id+"&documentDiscipline="+discipline;
        }
        if(document === 'Note'){            
            window.location="../../../mvc/vista/gestion_clinic/?note=si&id_note="+id;
        }
        if(document == 'Summary') modal = 'modalAddSummary'
        if(document == 'Discharge') modal = 'modalAddDischarge'
        if(document == 'Prescriptions') modal = 'modalEditDocument'
        $("#"+modal).modal('show');
    }
</script>