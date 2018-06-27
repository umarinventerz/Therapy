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
$conexion = conectar();
    
    $sql  = "SELECT * FROM user_system WHERE user_id ='".$_SESSION['user_id']."'  ;"; 
    $resultado = ejecutar($sql,$conexion);   
    
    while($datos = mysqli_fetch_assoc($resultado)) {

        $therapist = $datos['First_name'].' '.$datos['Last_name'];
        $getSignature = "SELECT signature,npi_number,discipline_id , assistant from employee where id = '".$_SESSION['user_id']."'   ";
        $resultadoSignature = ejecutar($getSignature,$conexion); 
        while($datosSignature = mysqli_fetch_assoc($resultadoSignature)) { 
            $signature = $datosSignature['signature'];
            $npi = $datosSignature['npi_number'];
            $discipline_id = $datosSignature['discipline_id'];
            $assistant_11 = $datosSignature['assistant'];
        }
    }
    
    //consulto los datos desde la bd para la evaluacion creada desde el calendario 
    if(isset($_GET['id_poc'])){
        
        $sql_poc="SELECT CA.*,C.* FROM careplans CA LEFT JOIN companies C ON(CA.Company=C.id)  WHERE CA.id_careplans='".$_GET['id_poc']."'   ; ";
        $resultados_poc = ejecutar($sql_poc,$conexion);   
        while($row_poc = mysqli_fetch_assoc($resultados_poc)) { 

            $data_poc[]=$row_poc;

        }
        $evaluacion_id=$data_poc[0]['evaluations_id'];
        $titulo="Edit care of plans #".$_GET['id_poc'];
        $button='Edit';
    }else{
        $evaluacion_id=$_GET['id_eval'];
        $titulo="New plan of care";
        $button='Save';
    }
    
    if($evaluacion_id != ''){
        
     $appoiment="SELECT E.*,concat(P.Last_name,', ',P.First_name) as patient,P.Pat_id,P.DOB,concat(P.PCP,', ',P.PCP_NPI) AS physician,C.*,concat(U.Last_name,', ',U.First_name) as employee,
                DI.DisciplineId as discipline_id,DI.Name as disciplina,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation, 
                CONCAT(P.PCP, ' , NPI ',P.PCP_NPI)    as ppcp , 
                CONCAT(P.dob , ' / ', TIMESTAMPDIFF( YEAR, P.dob, now() ) ,' Yrs.,  ' 
                      , TIMESTAMPDIFF( MONTH, P.dob, now()) % 12,' Months, ' 
                      , FLOOR( TIMESTAMPDIFF( DAY, P.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob,
                      em.signature as sign_before,DOC.route_document,AMEND.id_amendment as id_amendment,AMEND.ckeditor as ckeditor_amendment
                
                FROM tbl_evaluations E 
                LEFT JOIN employee em ON E.id_user=em.id
                LEFT JOIN patients P ON(E.patient_id=P.id)
                LEFT JOIN companies C ON(E.company=C.id) LEFT JOIN diagnosiscodes D ON(E.diagnostic=D.DiagCodeId)
                LEFT JOIN discipline DI ON(E.discipline_id=DI.DisciplineId)
                LEFT JOIN user_system U ON(U.user_id=id_user) 
                LEFT JOIN tbl_documents DOC ON DOC.id_table_relation = E.id
                LEFT JOIN tbl_amendment AMEND ON AMEND.id_evaluations = E.id
                
            WHERE E.id='".$evaluacion_id."' ";
    
    $resultados = ejecutar($appoiment,$conexion);   
     while($row = mysqli_fetch_assoc($resultados)) { 
         
         $data[]=$row;
         
     }

        if($data[0]['id_template']==1){

           $sql_cke="SELECT ckeditor FROM tbl_modal_template WHERE type_modal=1 AND id_modal='".$_GET['id_eval']."'  ORDER BY id desc LIMIT 1";

           $res_cke = ejecutar($sql_cke,$conexion); 
           while($row_cke = mysqli_fetch_assoc($res_cke)) { 

               $data_cke[]=$row_cke;

           }        
        }
     }
     // para consultar si hay templates cargados para amend en evaluaciones
     
    $sql_amend="SELECT COUNT(*) AS contador FROM tbl_modal_template WHERE type_modal='0' AND id_modal='".$_GET['id_eval']."'  ; " ;
    $res_amend = ejecutar($sql_amend,$conexion); 
    while($row_amend = mysqli_fetch_assoc($res_amend)) { 

        $data_amend[]=$row_amend;

    }
    
    //para consultar template de poc
    if(isset($_GET['id_poc'])){
        $sql_pocs="SELECT id_template FROM careplans WHERE id_careplans=".$_GET['id_poc'];
        $res_poc = ejecutar($sql_pocs,$conexion); 
        while($row_pocs = mysqli_fetch_assoc($res_poc)){         
            $data_pocs[]=$row_pocs;

        } 
    }
     
//consulto  disciplina de la tabla employee     
      $sql_employee="SELECT discipline.Name,discipline_id,assistant,concat(Last_name,', ',First_name) as employee "
             . " FROM employee "
             . " INNER JOIN discipline ON discipline.DisciplineId = employee.discipline_id"
             . " WHERE id=".$_SESSION['user_id'];
     $resultados_employee = ejecutar($sql_employee,$conexion);   
     while($row = mysqli_fetch_assoc($resultados_employee)){         
        $discipline['discipline_id']=$row['discipline_id'];
        $discipline['discipline']=$row['Name'];
        $discipline['assistant']=$row['assistant']; 
        $discipline['employee']=$row['employee'];         
     }
//Conculto las notas desde tbl_notes_documentation
   if(isset($_GET['id_note'])){
       
       $id_note=$_GET['id_note'];
   }else{
        $id_note='';
   }
      $sql_notas="SELECT N.*,concat(P.Last_name,', ',P.First_name) as patient,P.Pat_id,P.DOB,E.npi_number as NPI ,C.*,concat(E.last_name,', ',E.first_name) as employee,
                DI.Name as disciplina,DI.DisciplineId as disciplineId,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation, CA.Discipline as discipline_poc,CA.Company as company_poc,
                CA.diagnostic as diagnostic_poc,CA.id_careplans,  
                CONCAT(P.PCP, ' , NPI ', P.PCP_NPI )    as ppcp , V.visit_date,
                CONCAT(P.dob , ' / ', TIMESTAMPDIFF( YEAR, P.dob, now() ) ,' Yrs.,  ' 
                      , TIMESTAMPDIFF( MONTH, P.dob, now()) % 12,' Months, ' 
                      , FLOOR( TIMESTAMPDIFF( DAY, P.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob
                      ,E.signature as signature2
                
                FROM tbl_notes_documentation N
                
                LEFT JOIN careplans CA ON(N.id_careplans=CA.id_careplans)
                LEFT JOIN patients P ON(N.patient_id=P.id)
                LEFT JOIN companies C ON(CA.Company=C.id) LEFT JOIN diagnosiscodes D ON(CA.diagnostic=D.DiagCodeId)
                LEFT JOIN discipline DI ON(CA.Discipline=DI.DisciplineId)
                LEFT JOIN user_system U ON(U.user_id=N.user_id) 
                LEFT JOIN tbl_visits V ON(N.visit_id=V.id)
                LEFT JOIN employee E ON(E.id=N.user_id)
            WHERE N.id_notes='".$id_note."'";
    
    $resultados_note = ejecutar($sql_notas,$conexion);   
    while($row_note = mysqli_fetch_assoc($resultados_note)) { 
         
         $note[]=$row_note;
         
    }

// 
      $sql_nota2 = "SELECT *,e.assistant as assistant , e.id as user1, e.supervisor as user2 , e.signature as signature FROM tbl_notes_documentation n  
                                                LEFT JOIN employee e on e.id=n.user_id
                                                where n.id_notes='".$_GET['id_note']."'
                                                    ";

               $resultados_note2 = ejecutar($sql_nota2,$conexion);   
    while($row_note2 = mysqli_fetch_assoc($resultados_note2)) { 
         
         $therapist_type['signature']=$row_note2['signature'];
         $therapist_type['assistant']=$row_note2['assistant'];
         $therapist_type['user1']=$row_note2['user1'];
         $therapist_type['user2']=$row_note2['user2'];
         
    }
    

?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gestión clinic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>

    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/build.css">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../css/font-awesome1.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
    <link href="../../../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="../../../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="../../../plugins/xcharts/xcharts.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
    <link href="../../../plugins/chartist/chartist.min.css" rel="stylesheet">

    <script src="../../../plugins/jquery/jquery.min.js"></script>
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
    <script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
    <script src="../../../plugins/tinymce/tinymce.min.js"></script>
    <script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
    <!-- All functions for this theme + document.ready processing -->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="../../../js/devoops_ext.js"></script>    
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css"/>        
    <script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>       
    <link rel="stylesheet" type="text/css" href="../../../css/bootstrap-treefy.css"/>   
    <link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <script src="../../../js/fileinput.js" type="text/javascript"></script> 
    <script type="text/javascript" src="../../../js/jquery.treegrid.js"></script>
    <script type="text/javascript" src="../../../js/jquery.treegrid.bootstrap3.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/bootstrap-treefy.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script src="../../../js/gestion_clinic.js" type="text/javascript"></script>
    

<script>
    $(document).ready(function(){

    $('#from').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
    $('#from').prop('readonly', true);
    $('#to').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
    $('#to').prop('readonly', true);

        $('#dateSigned').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#dateSigned').prop('readonly', true);



        $('#from_hidden').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_hidden').prop('readonly', true);
        $('#to_hidden').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_hidden').prop('readonly', true);

        //$('#start_note').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        //$('#to_note').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});


        $('#from_eval').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_eval').prop('readonly', true);
        $('#to_eval').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_eval').prop('readonly', true);

        $('#from_poc').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_poc').prop('readonly', true);
        $('#created_poc').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#created_poc').prop('readonly', true);
        $('#to_poc').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_poc').prop('readonly', true);

        $('#from_summary').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_summary').prop('readonly', true);
        $('#to_summary').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_summary').prop('readonly', true);
        $('#date_of_summary').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#date_of_summary').prop('readonly', true);
        $('#date_of_signed_summary').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#date_of_signed_summary').prop('readonly', true);

        $('#from_discharge').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#from_discharge').prop('readonly', true);

        $('#to_discharge').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#to_discharge').prop('readonly', true);


        $('#date_of_eval').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#date_of_eval').prop('readonly', true);
        $('#date_of_signed').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#date_of_signed').prop('readonly', true);

        $('#visit_date').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});
        $('#visit_date').prop('readonly', true);

    });

</script>



    <link href="../../../css/gestion_clinic.css" media="all" rel="stylesheet" type="text/css" />  
</head>
<body>
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br/>
    <div class="row">
        <div class="col-lg-12" style="padding-top:10px;">            
            <div class="col-lg-3">
                <select id="patients_id" name="patients_id">
                    <option value="">Patients..</option>     
<?php
                    $sql  = "Select id, concat(Last_name,' ',First_name) as lista from patients order by lista ";
                    $conexion = conectar();
                    $resultado = ejecutar($sql,$conexion);
                    while ($row=mysqli_fetch_array($resultado))
                    {

                        print("<option value='".$row["id"]."'>".$row["lista"]." </option>");
                    }

                    ?>                                 
                </select>
            </div>
            <div class="col-lg-2">
                <select name="discipline_id" id="discipline_id" onchange="loadDiscipline('discipline_id');">
                    <?php 
                        $sqlUser = "SELECT * FROM user_system WHERE user_id = ".$_SESSION['user_id'];
                        $resultUser = ejecutar($sqlUser,$conexion);
                        while($datos = mysqli_fetch_assoc($resultUser)) {
                            $first_name = $datos['First_name'];
                            $last_name = $datos['Last_name'];
                            $user_id = $datos['user_id'];
                        }

                     $sqlDiscipline  = "SELECT *,discipline.Name as disciplina FROM discipline ";
                        $resultDiscipline = ejecutar($sqlDiscipline,$conexion);
                        $discipline_id = '';
                        $name = '-1';
                        while($datos = mysqli_fetch_assoc($resultDiscipline)) {
                            $discipline_id = $datos['discipline_id'];                             
                            $name =    $datos['disciplina'];                         
                        }                          
                        if(!empty($discipline_id) && !is_null($discipline_id)){
                            echo "<option value='".$discipline_id."'>".$name."</option>";
                        }else{
                            echo "<option value=''>Discipline..</option>";
                        }                 
                    ?>                      
                </select>
                <input type="hidden" id="discipline_description" name="discipline_description" value="<?php echo ($name!= -1)?$name:'';?>"/>
                
            </div>
            <div class="col-lg-2">
                <input type="text" name="from" id="from" class="form-control" placeholder="From" style="height: 26px;"/>
            </div>
            <div class="col-lg-2">
                <input type="text" name="to" id="to" class="form-control" placeholder="To" style="height: 26px;"/>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-primary" onclick="search_result();" ><img src="../../../images/lupa.png" width="15" height="15"/>&nbsp;Search</button>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-lg-12" style="padding-top:10px;">            
            
        </div>
    </div>
    <input type="hidden" name="template_show" id="template_show" value="<?=$data[0]['id_template']?>"/>
    <!--<input type="hidden" name="ckeditor_data" id="ckeditor_data" value="<?$data_cke[0]['ckeditor']?>"/>-->
    <input type="hidden" name="poc_template" id="poc_template" value="<?=$data_pocs[0]['id_template']?>"/>
    <?php 
        if($data_amend[0]['contador']>0){
    ?>
        <input type="hidden" name="template_amend" id="template_amend" value="si"/>
    <?php 
        }else{
    ?>
        <input type="hidden" name="template_amend" id="template_amend" value="no"/>
    <?php 
        }
    ?>
    <div class="row" >
        <div class="col-lg-12" style="padding-bottom:10px;padding-top:10px;border: 1px solid transparent;border-color: #e7e7e7;">
            <div class="col-lg-1"><button type="button" class="btn" onclick="openModal('modalAddPrescription','patients_id','un Paciente','prescription','Prescription');"><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;RX</button></div>
            <div class="col-lg-1"><button type="button" class="btn btn-warning"  onclick="openModal('modalAddEvaluation','prescSelect','una Prescription activa','eval','Evaluation');"><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;Eval</button></div>
            <div class="col-lg-1"><button type="button" class="btn" onclick="openModal('modalAddPoc','evalSelect','una Evaluacion activa','poc','POC');" <?php  if($discipline['assistant']!=0 || $discipline['discipline_id']==0 || $discipline['discipline_id']=='' || $discipline['discipline_id']==null){?> disabled=""<?php }?>><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;POC</button></div>              
            <div class="col-lg-1"><button type="button" class="btn btn-warning" onclick="openModal('modalAddNote','pocSelect','un POC activa','note','Note');"><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;Note</button></div>
            <?php if($discipline_id != null):?>
            <div class="col-lg-1"><button type="button" class="btn" onclick="openModal('modalAddSummary','evalSelect','una Evaluacion activa','summary','Summary');"><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;Summary</button></div>
            
            <div class="col-lg-1"><button type="button" class="btn" onclick="openModal('modalAddDischarge','evalSelect','una Evaluacion activa','discharge','Discharge');"><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;Discharge</button></div>
            <?php endif;?>
        </div>
    </div>
    
    <div class="panel-group" id="accordion">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                Documentation</a>
              </h4>
            </div>
            <div id="collapse1" class="panel-collapse collapse in">
                <div class="row">  
                    <div class="col-lg-12 text-left" style="padding-top: 30px;padding-bottom: 10px;padding-left: 30px;">
                        <div class="col-lg-3" id="name_patiens"></div>
                        <div class="col-lg-4" id="dob"></div>
                    </div>                    
                    <div class="col-lg-12" style="padding-left: 30px;">

                        <div class="col-lg-12" id="resultado_consulta"></div>

                    </div>
                </div>
            </div>
      </div>
        <div class="panel panel-success">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                AUTHORIZATIONS</a>
              </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="panel-heading">AUTHORIZATIONS</div>
                        <div class="panel-body panel-resizable">

                            <div id="result_autorizacion"></div>  
                        </div>
                </div>
            </div>
        </div>
  
    </div> 
    <footer> 
        <div class="row"> 
            <div class="col-lg-12 text-center"> 
                <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
            </div> 
        </div> 
        <!-- /.row --> 
    </footer>
    <?php 
        if($_GET['poc'] == 'si'){
           $valueDocumentAdd= 'POC';
           
           $valueDocumentDiscipline = $_GET['documentDiscipline'];
        }else{
            $valueDocumentAdd = '';
            $valueDocumentDiscipline = '';
        }
   ?>
    <input type="hidden" id="documentAdd" name="documentAdd" value="<?=$valueDocumentAdd?>" readonly>
    <input type="hidden" id="documentDiscipline" name="documentDiscipline" value="<?=$valueDocumentDiscipline?>" readonly>
    <?php
        include "modales/modalAddPrescription.php";
        include "modales/modalEditDocument.php";
        include "modales/modalAddEvaluation.php";   
        include "modales/modalAddPoc.php";
        include "modales/modalAddNote.php";
        include "modales/modalCpt.php";
        include "modales/modalGoals.php";
        include "modales/modalAddDocument.php";
        include "modal_summary.php";
        include "modal_discharge.php";
        
        //include "modales/modalTest.php";
     ?>
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
<script>
        //CKEDITOR.replace('editor');
        $(document).ready(function(){
            
            $.fn.modal.Constructor.prototype.enforceFocus = function () {
                modal_this = this
                $(document).on('focusin.modal', function (e) {
                    if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
                    // add whatever conditions you need here:
                    &&
                    !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                        modal_this.$element.focus()
                    }
                })
            };
            
            var from_calendar='<?=$_GET['calendar']?>';
            var from_view='<?=$_GET['edit']?>';
            if(from_calendar==='si' || from_view==='si'){
                var md=$("#md_signed_hidden").val();
                
                if(md==='1'){                   
                    $("#date_signed").show();
                    $("#attachment").show();
                }
                $("#modalAddEvaluation").modal('show');
                
                var template=$("#template_show").val();
                if(template==='1'){         
                    $("#template").show();
                    $("#templateCkEditor2").attr("checked",true);
                    $("#templateCkEditor1").attr('disabled',true);
                    $("#template_panel").show();
                    var id_eval='<?=$_GET['id_eval']?>';
                    $.post(
                        "../../controlador/gestion_clinic/getContentTemplate.php",
                        "template_id=si&document=1&discipline="+$("#discipline_id_eval_hidden").val()+"&id_modal="+id_eval+"&editor_eval=editor",
                        function (resultado_controlador) {  
                            
                            $("#template_id").attr('disabled',true);
                            $("#components_id").show();                            
                            $("#components_id").html(resultado_controlador.contentComponents);
                            $("#newTemplate").html(resultado_controlador.contentTemplate);
                            for(i=0;i<=resultado_controlador.total_category;i++){
                                $('#category_id'+i).DataTable({
                                    pageLength: 5,                    
                                    "scrollX": true,
                                    "pagingType": "full_numbers",
                                    "lengthMenu": [ 5, 25, 50, 75, 100 ],
                                });
                            }
                            $("#titulos").html("<h2><div align='center'><u>Components Select</u></div></h2>");                            
                        },
                        "json" 
                    );
                    $("#cked").hide();
                }
                var template_amend=$("#template_amend").val();
                if(template_amend==='si'){                    
                    $("#templateAmendment").show();
                    $("#templateCkEditorAmendment").attr("checked",true);
                    $("#templateCkEditorAmendment1").attr('disabled',true);
                    $("#template_panel_amendment").show();
                    var id_eval='<?=$_GET['id_eval']?>';
                    $.post(
                        "../../controlador/gestion_clinic/getContentTemplate.php",
                        "template_id=si&document=0&discipline="+$("#discipline_id_eval_hidden").val()+"&id_modal="+id_eval+"&editor_eval=editor_amendment",
                        function (resultado_controlador) {  
                            
                            $("#template_amendment_id").attr('disabled',true);
                            $("#components_id_amendment").show();                            
                            $("#components_id_amendment").html(resultado_controlador.contentComponents);
                            $("#newTemplateAmendment").html(resultado_controlador.contentTemplate);
                            for(i=0;i<=resultado_controlador.total_category;i++){
                                $('#category_id'+i).DataTable({
                                    pageLength: 5,                    
                                    "scrollX": true,
                                    "pagingType": "full_numbers",
                                    "lengthMenu": [ 5, 25, 50, 75, 100 ],
                                });
                            }
                            $("#titulos").html("<h2><div align='center'><u>Components Select</u></div></h2>");                            
                        },
                        "json" 
                    );
                    $("#cked_amendment").hide();
                }
            }
            var pat_id='<?=$_GET['patient_id']?>';
            var disciplinas='<?=$_GET['disciplina']?>';

            if(pat_id!==''){                    
                search_result_edit(pat_id,disciplinas);  
            }
            //consulto modal poc 
            var poc='<?=$_GET['poc']?>';

            if(poc==='si'){
                var template_poc=$("#poc_template").val();
                if(template_poc==='1'){         
                    $("#templatePoc").show();
                    $("#templateCkEditorPoc").attr("checked",true);
                    $("#templateCkEditorPoc1").attr('disabled',true);
                    $("#template_panel_poc").show();
                    var id_poc='<?=$_GET['id_poc']?>';
                    $.post(
                        "../../controlador/gestion_clinic/getContentTemplate.php",
                        "template_id=si&document=2&discipline="+$("#discipline_id_eval_hidden").val()+"&id_modal="+id_poc+"&editor_eval=editor_poc",
                        function (resultado_controlador) {  
                            
                            $("#template_id_Poc").attr('disabled',true);
                            $("#components_id_poc").show();                            
                            $("#components_id_poc").html(resultado_controlador.contentComponents);
                            $("#newTemplatePoc").html(resultado_controlador.contentTemplate);
                            for(i=0;i<=resultado_controlador.total_category;i++){
                                $('#category_id'+i).DataTable({
                                    pageLength: 5,                    
                                    "scrollX": true,
                                    "pagingType": "full_numbers",
                                    "lengthMenu": [ 5, 25, 50, 75, 100 ],
                                });
                            }
                            $("#titulos").html("<h2><div align='center'><u>Components Select</u></div></h2>");                            
                        },
                        "json" 
                    );
                    $("#cke_poc").hide();
                }
                $("#modalAddPoc").modal('show');
            }

            //consulto modal notas 
            var note='<?=$_GET['note']?>';

            if(note==='si'){
                $("#modalAddNote").modal('show');
            }
            //consulto modal notas 
            var autorizacion='<?=$_GET['autorizacion']?>';

            if(autorizacion==='si'){
                $("#collapse1").removeClass("in");
                $("#collapse2").addClass("in");
            }
            //para modificar cpt 

            var cpt_edit='<?=$_GET['modificar_cpt']?>';
            if(cpt_edit==='si'){                        
                 $("#modalCpt").modal('show'); 
            }


            $('.tree').treegrid({
                expanderExpandedClass: 'glyphicon glyphicon-minus',
                expanderCollapsedClass: 'glyphicon glyphicon-plus',
                initialState: 'expanded',
                nodeIcon: 'glyphicon glyphicon-bookmark'
            });
                       
        
        LoadSelect2ScriptExt(function(){
            $('#patients_id').select2();
            $('#discipline_id').select2();            
            $('#companies_id').select2();
            //$('#diagnostic_id').select2();
            $('#physician_id').select2();  
            $('#template_id').select2();
            $('#template_amendment_id').select2();            
            $('#unid_of_eval').select2();  
            $('#min_of_eval').select2(); 
            $('#unid_of_summary').select2();    
            $('#min_of_summary').select2();   
            $("#diagnostic_id_eval").select2();
            $("#diagnostic_id_poc").select2();
            $("#diagnostic_id_note").select2();
            $("#template_id_Poc").select2();
            $("#duration").select2();
            $("#unid_note").select2();
            $("#unid_note_edit").select2();
            $("#diagnostic_id_note_edit").select2();
            
            
            
        });
        
        <?php if($discipline_id == ''):?>
            autocompletar_radio('Name','DisciplineID','discipline','selector',null,null,null,null,'discipline_id');             
        <?php endif;?>
        
        autocompletar_radio('concat(Last_name,\', \',First_name) as texto','id','patients','selector',null,null,null,null,'patients_id');                         
        
        //Elementos del modal        
        autocompletar_radio('company_name','company_name','companies','selector',null,null,null,null,'companies_id');                         
        autocompletar_radio('Name','NPI','physician','selector',null,null,null,null,'physician_id');
        //autocompletar_radio('name as texto','id','tbl_templates','selector',null,null,null,null,'template_id');   
       
        var poc='<?=$_GET['poc']?>';
        if(poc==='si'){
            //autocompletar_radio('name as texto','id','tbl_templates','selector',null,null,null,null,'template_id_Poc');   
        }
        var id_eval='<?=$_GET['id_eval']?>';
        if(id_eval!=''){
            //autocompletar_radio('name as texto','id','tbl_templates','selector',null,null,null,null,'template_amendment_id');  
        }
       });
    </script>
</html>
