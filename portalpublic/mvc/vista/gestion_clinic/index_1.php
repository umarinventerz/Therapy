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
$conexion = conectar();
    $sql  = "SELECT * FROM user_system WHERE user_id =".$_SESSION['user_id'].";"; 
    $resultado = ejecutar($sql,$conexion);   
    
    while($datos = mysqli_fetch_assoc($resultado)) {

        $therapist = $datos['First_name'].' '.$datos['Last_name'];
        $getSignature = "Select signature,npi_number,discipline_id , assistant from employee where id = '".$_SESSION['user_id']."'";
        $resultadoSignature = ejecutar($getSignature,$conexion); 
        while($datosSignature = mysqli_fetch_assoc($resultadoSignature)) { 
            $signature = $datosSignature['signature'];
            $npi = $datosSignature['npi_number'];
            $discipline_id = $datosSignature['discipline_id'];
            $assistant_11 = $datosSignature['assitant'];
        }
    }
    
    //consulto los datos desde la bd para la evaluacion creada desde el calendario 
    if(isset($_GET['id_poc'])){
        
        $sql_poc="SELECT CA.*,C.* FROM careplans CA LEFT JOIN companies C ON(CA.Company=C.id)  WHERE CA.id_careplans=".$_GET['id_poc'];
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
    
    $appoiment="SELECT E.*,concat(P.Last_name,', ',P.First_name) as patient,P.Pat_id,P.DOB,concat(P.PCP,', ',P.PCP_NPI) AS physician,C.*,concat(U.Last_name,', ',U.First_name) as employee,
                DI.Name as disciplina,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation, 
                CONCAT(P.PCP, ' , NPI ', p.PCP_NPI )    as ppcp , 
                CONCAT(p.dob , ' / ', TIMESTAMPDIFF( YEAR, p.dob, now() ) ,' Yrs.,  ' 
                      , TIMESTAMPDIFF( MONTH, p.dob, now()) % 12,' Months, ' 
                      , FLOOR( TIMESTAMPDIFF( DAY, p.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob,
                      em.signature as sign_before
                
                FROM tbl_evaluations E 
                LEFT JOIN employee em ON E.id_user=em.id
                LEFT JOIN patients P ON(E.patient_id=P.id)
                LEFT JOIN companies C ON(E.company=C.id) LEFT JOIN diagnosiscodes D ON(E.diagnostic=D.DiagCodeId)
                LEFT JOIN discipline DI ON(E.discipline_id=DI.DisciplineId)
                LEFT JOIN user_system U ON(U.user_id=id_user) 
            WHERE E.id='".$evaluacion_id."' ";
    
    $resultados = ejecutar($appoiment,$conexion);   
     while($row = mysqli_fetch_assoc($resultados)) { 
         
         $data[]=$row;
         
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
                DI.Name as disciplina,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation, CA.Discipline as discipline_poc,CA.Company as company_poc,
                CA.diagnostic as diagnostic_poc,CA.id_careplans,  V.visit_date,
                CONCAT(P.PCP, ' , NPI ', p.PCP_NPI )    as ppcp , 
                CONCAT(p.dob , ' / ', TIMESTAMPDIFF( YEAR, p.dob, now() ) ,' Yrs.,  ' 
                      , TIMESTAMPDIFF( MONTH, p.dob, now()) % 12,' Months, ' 
                      , FLOOR( TIMESTAMPDIFF( DAY, p.dob, now() ) % 30.4375 ) ,' Days' ) AS  pdob
                
                FROM tbl_notes_documentation N
                LEFT JOIN tbl_visits V ON(N.visit_id=V.id)
                LEFT JOIN careplans CA ON(N.id_careplans=CA.id_careplans)
                LEFT JOIN patients P ON(N.patient_id=P.id)
                LEFT JOIN companies C ON(CA.Company=C.id) LEFT JOIN diagnosiscodes D ON(CA.diagnostic=D.DiagCodeId)
                LEFT JOIN discipline DI ON(N.discipline_id=DI.DisciplineId)
                LEFT JOIN user_system U ON(U.user_id=N.user_id) 
                LEFT JOIN employee E ON(E.id=N.user_id)
            WHERE N.id_notes='".$id_note."'";
    
    $resultados_note = ejecutar($sql_notas,$conexion);   
    while($row_note = mysqli_fetch_assoc($resultados_note)) { 
         
         $note[]=$row_note;
         
    }

      $sql_nota2 = "SELECT *,e.assistant as assistant , e.id as user1, e.supervisor as user2 FROM tbl_notes_documentation n  
                                                LEFT JOIN employee e on e.id=n.user_id
                                                where n.id_notes='".$_GET['id_note']."'
                                                    ";

               $resultados_note2 = ejecutar($sql_nota2,$conexion);   
    while($row_note2 = mysqli_fetch_assoc($resultados_note2)) { 
         
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
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
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
<script src="../../../js/devoops_ext.js"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css"/>        
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>       
<link rel="stylesheet" type="text/css" href="../../../css/bootstrap-treefy.css"/>   
<link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<script src="../../../js/fileinput.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>  
<script type="text/javascript" src="../../../js/jquery.treegrid.js"></script>
<script type="text/javascript" src="../../../js/jquery.treegrid.bootstrap3.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/bootstrap-treefy.js"></script>
<script src="ckeditor/ckeditor.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }    
    .sizeModal{
        width: 75% !important;
    }
    .select2-container {
        width: 100%;
      }
      .alert-danger{
          background-color: #bf0b0b;
          color: #FFF;
      }
      .bg-card-header{
        background: #95a5a6 !important;
        color: #fff;
    }
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
.withoutPadding{
    padding-left: 0px;
    padding-right: 0px;
}


/* RESIZABLE DIV */
 .panel-resizable {
            resize: vertical;
          overflow: auto
        }
  </style>
  <script>
      //variable global 
      var campo;
      
      function result_documentation(datos){
          $("#resultado_consulta").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
          $("#resultado_consulta").load("../gestion_clinic/result_documentation.php?"+datos);
      }
      
      function result_autorizacion(patient){
          $("#result_autorizacion").empty().html('<img src="../../../images/loader.gif" width="30" height="30"/>');
          $("#result_autorizacion").load("../gestion_clinic/result_autorizacion.php?patient="+patient);
      }
      
    function validar_form_discharge(){
        $('.error-message').hide('slow');
        $('.error-message').html('');

        var nombres_campos = '';
        var error = 0;
        if($("#from_discharge").val() == ''){
                    nombres_campos += ' * The field From discharge is required';
                    error = 1;

        }
        if($("#to_discharge").val() == '' && error == 0){
                    nombres_campos += ' * The field to discharge is required';
                    error = 1;

        }
        if($("#date_of_discharge").val() == '' && error == 0){
                    nombres_campos += ' * The field date of discharge is required';
                    error = 1;

        }

        if(error == 0){            
            var form = $('#formDischarge')[0]; 
            var formData = new FormData(form);                                                        

            var campos_formulario = $("#formDischarge").serialize();
            var data = new FormData();    
            $('#formDischarge :input[type=file]').each(function(){                                
                data.append(this.name,$(this)[0].files[0]);                       
            });
            var url;
            if($('#action').val() == 'edit'){
                url = "../../controlador/gestion_clinic/edit_discharge.php";
            }else{
                url = "../../controlador/gestion_clinic/insert_discharge.php";
            }
            data.append('campos_formulario',campos_formulario);                   
            $.ajax({
                url: url,
                type: "POST",
                data: data, 
                processData: false,
                contentType: false,                                 
                success : function(resultado_controlador_archivo){               
                    //alert(resultado_controlador_archivo);
                   var result = resultado_controlador_archivo.indexOf("insertada");
                   if(result != null){                
                        swal({
                          type: 'success',                          
                          html: '<h3><b>Record saved successfully</b></h3>',
                          timer: 3000    
                        }
                        ); 
                        $('#formSummary')[0].reset();
                        $("#modalAddSummary").modal('hide');
                        search_result();
                    } else {
                        swal({
                          title: "<h4><b>Error, Failed to save the evaluation</h4>",          
                          type: "error",              
                          showCancelButton: false,              
                          closeOnConfirm: true,
                          showLoaderOnConfirm: false,
                        });                                     
                    }
                }
            });
        }else{
            $('.error-message').show('slow');
            $('.error-message').html(nombres_campos);            
        }
        return false;

    }
      function validar_form_summary(){
        $('.error-message').hide('slow');
        $('.error-message').html('');
              
        var nombres_campos = '';
        var error = 0;
        if($("#from_summary").val() == ''){
                    nombres_campos += ' * The field From is required';
                    error = 1;
                                
        }
        if($("#to_summary").val() == '' && error == 0){
                    nombres_campos += ' * The field to is required';
                    error = 1;
                                
        }
        if($("#date_of_summary").val() == '' && error == 0){
                    nombres_campos += ' * The field date of eval is required';
                    error = 1;
                                
        }
        
        if(error == 0){            
            var form = $('#formSummary')[0]; 
            var formData = new FormData(form);                                                        

            var campos_formulario = $("#formSummary").serialize();
            var data = new FormData();    
            $('#formSummary :input[type=file]').each(function(){                                
                data.append(this.name,$(this)[0].files[0]);                       
            });
            var url;
            if($('#action').val() == 'edit'){
                url = "../../controlador/gestion_clinic/edit_summary.php";
            }else{
                url = "../../controlador/gestion_clinic/insert_summary.php";
            }
            data.append('campos_formulario',campos_formulario);                   
            $.ajax({
                url: url,
                type: "POST",
                data: data, 
                processData: false,
                contentType: false,                                 
                success : function(resultado_controlador_archivo){               
                    //alert(resultado_controlador_archivo);
                   var result = resultado_controlador_archivo.indexOf("insertada");
                   if(result != null){                
                        swal({
                          type: 'success',                          
                          html: '<h3><b>Record saved successfully</b></h3>',
                          timer: 3000    
                        }
                        ); 
                        $('#formSummary')[0].reset();
                        $("#modalAddSummary").modal('hide');
                        search_result();
                    } else {
                        swal({
                          title: "<h4><b>Error, Failed to save the evaluation</h4>",          
                          type: "error",              
                          showCancelButton: false,              
                          closeOnConfirm: true,
                          showLoaderOnConfirm: false,
                        });                                     
                    }
                }
            });
        }else{
            $('.error-message').show('slow');
            $('.error-message').html(nombres_campos);            
        }
        return false;
      }
      function validar_form_note(accion){
        $('.error-message').hide('slow');
        $('.error-message').html('');
        var nombres_campos = '';
        var error = 0;
        if($("#snote").val() == ''){
                    nombres_campos += ' * The field Snote is required';
                    error = 1;
                                
        }
        if($("#onote").val() == ''){
                    nombres_campos += ' * The field Onote is required';
                    error = 1;
                                
        }
        if($("#anote").val() == ''){
                    nombres_campos += ' * The field Anote is required';
                    error = 1;
                                
        }
        if($("#pnote").val() == ''){
                    nombres_campos += ' * The field Pnote is required';
                    error = 1;
                                
        }
        if($("#signedNote").prop('checked')){        
            if($("#dateSigned").val() == ''){
                nombres_campos += ' * The field Signed date is required';
                error = 1;
            }                                                
        }
        
        if(error == 0){
        
        var form = $('#formNote')[0]; 
            var formData = new FormData(form);                                                        

            var campos_formulario = $("#formNote").serialize();
            var data = new FormData();    
            $('#formNote :input[type=file]').each(function(){                                
                data.append(this.name,$(this)[0].files[0]);                       
            });
            if(accion==='insertar'){
                var url="../../controlador/gestion_clinic/insert_note.php";
                var mensaje='Saved';
                
            }else{
                url="../../controlador/gestion_clinic/edit_note.php";
                mensaje='Updated';
            }
            data.append('campos_formulario',campos_formulario);                   
            $.ajax({
                url: url,
                type: "POST",
                async:false,
                dataType: "json",
                data: data,  
                processData: false,
                contentType: false,                                
                success : function(data){                   
                    if(data.mensaje === 'ok'){                
                        swal({
                          type: 'success',                          
                          html: '<h3><b>Record '+mensaje+' successfully</b></h3>',
                          timer: 3000    
                        }
                        ); 
                        /*$('#formPoc')[0].reset();
                        $("#modalAddPoc").modal('hide');
                        search_result();*/
                        window.location="../../../mvc/vista/gestion_clinic?patient_id="+data.pat_id+"&disciplina="+data.disciplina;
                    }else{
                        swal({
                          title: "<h4><b>"+data.response+"</b></h4>",          
                          type: "error",              
                          showCancelButton: false,              
                          closeOnConfirm: true,
                          showLoaderOnConfirm: false,
                        });                                     
                    }
                }
            });
        }else{
            $('.error-message').show('slow');
            $('.error-message').html(nombres_campos);            
        }
        return false;
      }
      
      function validar_form_poc(ruta){
        $('.error-message').hide('slow');
        $('.error-message').html('');
        var nombres_campos = '';
        var error = 0;
        if($("#from_poc").val() == ''){
                    nombres_campos += ' * The field From is required';
                    error = 1;
                                
        }
        if($("#to_poc").val() == '' && error == 0){
                    nombres_campos += ' * The field to is required';
                    error = 1;
                                
        }
        
        if(error == 0){
    
            var form = $('#formPoc')[0]; 
            var formData = new FormData(form);                                                        

            var campos_formulario = $("#formPoc").serialize();
            var data = new FormData();    
            $('#formPoc :input[type=file]').each(function(){                                
                data.append(this.name,$(this)[0].files[0]);                       
            });

            data.append('campos_formulario',campos_formulario); 
            var disciplina=$("#discipline_id").val();
            var pat_id=$("#patient_id").val();
            if(ruta==='insertar'){
                var accion="../../controlador/careplans/insert_careplans.php";
                var mensaje='Saved';
            }else{
                accion="../../controlador/careplans/edit_careplans.php";
                mensaje='Updated';
            }
            $.ajax({
                url: accion,
                type: "POST",
                async:false,
                dataType: "json",
                data: data,  
                processData: false,
                contentType: false,                                
                success : function(data){                   
                    if(data.mensaje === 'ok'){                
                        swal({
                          type: 'success',                          
                          html: '<h3><b>Record '+mensaje+' successfully</b></h3>',
                          timer: 3000    
                        }
                        ); 
                        /*$('#formPoc')[0].reset();
                        $("#modalAddPoc").modal('hide');
                        search_result();*/
                        window.location="../../../mvc/vista/gestion_clinic?patient_id="+pat_id+"&disciplina="+disciplina;
                    }else if(data.mensaje === 'duplicated'){
                        swal({
                            title: "<h4><b>There is another POC with that Status, Check Documentation of the Patient</b></h4>",          
                            type: "error",              
                            showCancelButton: false,              
                            closeOnConfirm: true,
                            showLoaderOnConfirm: false,
                        });
                        
                    }else{
                        swal({
                          title: "<h4><b>"+data.response+"</b></h4>",          
                          type: "error",              
                          showCancelButton: false,              
                          closeOnConfirm: true,
                          showLoaderOnConfirm: false,
                        });                                     
                    }
                }
            });
        }else{
            $('.error-message').show('slow');
            $('.error-message').html(nombres_campos);            
        }
        return false;
      }
      
      function validar_form_eval(variable){
        $('.error-message').hide('slow');
        $('.error-message').html('');
              
        var nombres_campos = '';
        var error = 0;
        if($("#from_eval").val() == ''){
                    nombres_campos += ' * The field From is required';
                    error = 1;
                                
        }
        if($("#to_eval").val() == '' && error == 0){
                    nombres_campos += ' * The field to is required';
                    error = 1;
                                
        }
        if($("#date_of_eval").val() == '' && error == 0){
                    nombres_campos += ' * The field date of eval is required';
                    error = 1;
                                
        }
        
        if(error == 0){            
            var form = $('#formEvaluation')[0]; 
            var formData = new FormData(form);                                                        

            var campos_formulario = $("#formEvaluation").serialize();
            var data = new FormData();    
            $('#formEvaluation :input[type=file]').each(function(){                                
                data.append(this.name,$(this)[0].files[0]);                       
            });
            var url_;
            if($("#action").val()== 'edit'){
                variable = "edit"
            }
            
            data.append('campos_formulario',campos_formulario); 
            if(variable==='insert'){
                
                $.ajax({
                    url: url_,
                    type: "POST",
                    data: data, 
                    processData: false,
                    contentType: false,                                 
                    success : function(resultado_controlador_archivo){               
                        //alert(resultado_controlador_archivo);
                       var result = resultado_controlador_archivo.indexOf("insertada");
                       if(result != null){                
                            swal({
                              type: 'success',                          
                              html: '<h3><b>Record saved successfully</b></h3>',
                              timer: 3000    
                            }
                            ); 
                            $('#formEvaluation')[0].reset();
                            $("#modalAddEvaluation").modal('hide');
                            search_result();
                        } else {
                            swal({
                              title: "<h4><b>Error, Failed to save the evaluation</h4>",          
                              type: "error",              
                              showCancelButton: false,              
                              closeOnConfirm: true,
                              showLoaderOnConfirm: false,
                            });                                     
                        }
                    }
                });
            }else{               
                $.ajax({
                    url: "../../controlador/evaluations/edit_evaluations.php",
                    type: "POST",
                    async:false,
                    dataType: "json",
                    data: data,  
                    processData: false,
                    contentType: false, 

//                   data: {
//                        from_eval_edit:$("#from_eval").val(),
//                        to_eval_edit:$("#to_eval").val(),
//                        date_of_eval_edit:$("#date_of_eval").val(),
//                        unid_of_eval_edit:$("#unid_of_eval").val(),
//                        min_of_eval_edit:$("#min_of_eval").val(),
//                        md_signed_edit:$("#md_signed").val(),
//                        date_of_signed_edit:$("#date_of_signed").val(),
//                        prescription_id_eval_hidden_edit:$("#prescription_id_eval_hidden").val(),
//                        id_evaluation_edit:$("#id_eval").val(),
//                        patients_eval_hidden_edit:$("#patients_eval_hidden").val(),
//                        discipline_id_eval_hidden_edit:$("#discipline_id_eval_hidden").val(),
//                        diagnostic_eval_hidden_edit:$("#diagnostic_eval_hidden").val(),
//                        company_id_eval_hidden_edit:$("#company_id_eval_hidden").val(),
//                        phone:$("#phone").val(),
//                        company_id:$("#company_id").val(),
//                            
//                    },                                            
                    success : function(data){               
                        
                        if(data.mensaje ==='ok'){                
                            swal({
                              type: 'success',                          
                              html: '<h3><b>Record updated successfully</b></h3>',
                              timer: 5000    
                            }
                            ); 
                            $('#formEvaluation')[0].reset();
                            $("#modalAddEvaluation").modal('hide');                           
                            window.location="../../../mvc/vista/gestion_clinic?patient_id="+data.pat_id+"&disciplina="+data.disciplina;
                            
                        }else if(data.mensaje ==='duplicated'){
                            swal({
                                title: "<h4><b>There is another Evaluation with that Status, Check Documentation of the Patient</h4>",          
                                type: "error",              
                                showCancelButton: false,              
                                closeOnConfirm: true,
                                showLoaderOnConfirm: false,
                            }); 
                        }else{
                            swal({
                              title: "<h4><b>Error, Failed to save the evaluation</h4>",          
                              type: "error",              
                              showCancelButton: false,              
                              closeOnConfirm: true,
                              showLoaderOnConfirm: false,
                            });                                     
                        }
                    }
                });
            }
        }else{
            $('.error-message').show('slow');
            $('.error-message').html(nombres_campos);            
        }
        return false;
      }
      
      function validar_form(){
        $('.error-message').hide('slow');
        $('.error-message').html('');
        var nombres_campos = '';
        var error = 0;
        if($("#companies_id").val() == ''){
                    nombres_campos += ' * The field Company is required';
                    error = 1;
                                
        }
        if($('#diagnostic_id').val() == '' && error == 0){
                    nombres_campos += '* The field Diagnostic is required';
                    error = 1;
        }
        if($('#from_hidden').val() == '' && error == 0){
                    nombres_campos += '* The field Diagnostic is required';
                    error = 1;
        }
        if($('#to_hidden').val() == '' && error == 0){
                    nombres_campos += '* The field Diagnostic is required';
                    error = 1;
        }
          if($('#physician_id').val() == '' && error == 0){
                    nombres_campos += '* The field Physician is required';
                    error = 1;            
        }    
        
         if($('#formPrescription :input[type=file]').val() == '' && error == 0){
                    nombres_campos += '* The field Attachment is required';
                    error = 1;            
        } 
        
        if(nombres_campos != ''){             
            $('.error-message').show('slow');
            $('.error-message').html(nombres_campos);
            return false; 
        
        } else {
        //var campos_formulario = $("#formPrescription").serialize();  
        var form = $('#formPrescription')[0]; 
        var formData = new FormData(form);                                                        

        var campos_formulario = $("#formPrescription").serialize();
        var data = new FormData();    
        $('input[type=file]').each(function(){                
            data.append(this.name,$(this)[0].files[0]);                       
        });
                
        data.append('campos_formulario',campos_formulario);                   
        $.ajax({
            url: "../../controlador/prescriptions/insert_prescriptions.php",
            type: "POST",
            data: data, 
            processData: false,
            contentType: false,                                 
            success : function(resultado_controlador_archivo){               
                //alert(resultado_controlador_archivo);
               var result = resultado_controlador_archivo.indexOf("insertada");
               if(result != null){                
                    swal({
                      type: 'success',                          
                      html: '<h3><b>Record saved successfully</b></h3>',
                      timer: 3000    
                    }
                    ); 
                    $("#modalAddPrescription").modal('hide');
                    search_result();
                } else {
                    swal({
                      title: "<h4><b>Error, Failed to save the prescription</h4>",          
                      type: "error",              
                      showCancelButton: false,              
                      closeOnConfirm: true,
                      showLoaderOnConfirm: false,
                    });                                     
                }
            }
        });

            return false; 
        }
          
          
      }
      function get_patients(valor,fields,modal){
              
            $.post(
                "../../controlador/patients/get_data_patients.php",
                '&patients_id='+valor+'&fields='+fields,
                function (resultado_controlador) {
                    if(modal == ''){
                        $("#name_patiens").html('<b>Patients:</b>&nbsp;'+($.trim(resultado_controlador.patients.First_name)+' '+$.trim(resultado_controlador.patients.Last_name)));
                        $("#dob").html('<b>DOB:</b>&nbsp;'+resultado_controlador.patients.DOB);                    
                    }else{
                        if(modal == 'modalAddPrescription'){
                            $("#patients_name_modal").html(($.trim(resultado_controlador.patients.First_name)+' '+$.trim(resultado_controlador.patients.Last_name)));
                            $("#discipline_modal").html($("#discipline_description").val());                    
                        }else{
                            $("#patients_name_eval_modal").html(($.trim(resultado_controlador.patients.First_name)+' '+$.trim(resultado_controlador.patients.Last_name)));
                            $("#discipline_eval_modal").html($("#discipline_description").val());                    
                        }
                        
                    }                                        
                },
                "json" 
            );

                    return false;                                              
        }
       
        
        function search_result(){
           
            if($("#patients_id").val()!=''){
                get_patients($("#patients_id").val(),'First_name,Last_name,DOB','');
                var datos = 'patient_id='+$.trim($("#patients_id").val());
                datos += '&discipline_id='+$("#discipline_id").val();
                datos += '&from='+$("#from").val();
                datos += '&to='+$("#to").val();                
                result_documentation(datos);
                result_autorizacion($("#patients_id").val());
            }else{
                swal({
                    title: "<h3><b>Message<b></h3>",          
                    type: "info",
                    html: "<h4>Debe Seleccionar un paciente..</h4>",
                    showCancelButton: false,
                    animation: "slide-from-top",
                    closeOnConfirm: true,
                    showLoaderOnConfirm: false,
                  });
            }
            
        }
        
        function search_result_edit(pat_id,disciplina){
             
            if(pat_id !==''){
               
                get_patients(pat_id,'First_name,Last_name,DOB','');
                var datos = 'patient_id='+$.trim(pat_id);
                datos += '&discipline_id='+disciplina;
                datos += '&from='+$("#from").val();
                datos += '&to='+$("#to").val();                
                result_documentation(datos);
                result_autorizacion(pat_id);
            }else{
                swal({
                    title: "<h3><b>Message<b></h3>",          
                    type: "info",
                    html: "<h4>Debe Seleccionar un paciente..</h4>",
                    showCancelButton: false,
                    animation: "slide-from-top",
                    closeOnConfirm: true,
                    showLoaderOnConfirm: false,
                  });
            }
            
        }
        
        //modalAddPrescription
        function openModal(modalId,field,title,modal,document){
            
            //$("#document").val(document);
            $("#documentAdd").val('');
            $("#documentAdd").val(document);
            if($("#"+field).val() == ''){
                swal({
                    title: "<h3><b>Message<b></h3>",          
                    type: "info",
                    html: "<h4>Debe Seleccionar "+title+"..</h4>",
                    showCancelButton: false,
                    animation: "slide-from-top",
                    closeOnConfirm: true,
                    showLoaderOnConfirm: false,
                  });
            }else{
                if(modal == 'prescription'){
                    if($("#discipline_id").val() == ''){
                        swal({
                            title: "<h3><b>Message<b></h3>",          
                            type: "info",
                            html: "<h4>Debe Seleccionar una disciplina..</h4>",
                            showCancelButton: false,
                            animation: "slide-from-top",
                            closeOnConfirm: true,
                            showLoaderOnConfirm: false,
                          });
                    }else{                                                
                        $.post(
                            "../../controlador/gestion_clinic/find_prescription.php",
                            'patients_id='+$("#patients_id").val()+'&discipline_id='+$("#discipline_id").val(),
                            function (resultado_controlador) {
                                if(resultado_controlador.exist ==  1){    
                                    
                                    swal({
                                        title: '<h4><b>You have another RX  In Progress</b></h4>',
                                        text: "Are you sure you want to create a New RX and INACTIVATE the one (IN PROGRESS)?",
                                        type: "error",
                                        showCancelButton: true,   
                                        confirmButtonColor: "#3085d6",   
                                        cancelButtonColor: "#d33",   
                                        confirmButtonText: "Create Prescription",   
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    }).then(function(isConfirm) {
                                          if (isConfirm === true) {                    
                                            $("#"+modalId).modal('show');
                                          }
                                    });
                                    
                                } else {
                                    $("#"+modalId).modal('show');
                                }
                            },
                            "json" 
                        );
                    }                   
                }else{ 
                    
                    if(modal==='poc'){      
                       
                          var eval=  $("#evalSelect").val();
                          window.location="../../../mvc/vista/gestion_clinic?poc=si&id_eval="+eval;
                          
                    }else{
                        $("#"+modalId).modal('show');
                    }
                }                
            }            
        }
        
        function mostrarCkEditor(divCkeditor,template){
            $("#"+divCkeditor).show('slow');
            $("#"+template).hide('slow');
        }
        
        function mostrarTemplate(divCkeditor,template){
            $("#"+template).show('slow');
            $("#"+divCkeditor).hide('slow');
        }
        function mostrarCkEditorAmendment(){
            $("#ckeditorAmendment").show('slow');
            $("#templateAmendment").hide('slow');
        }
        
        function mostrarTemplateAmendment(){
            $("#templateAmendment").show('slow');
            $("#ckeditorAmendment").hide('slow');
        }
        function signature(div,field){
            $("#"+div).show();
            $("#"+field).val('1');
            $("#ruta_"+field).val("signature.png");
            campo=field;
            getLocation();
            
            
        }
        
        //codigo para latitud y longitud capturada desde el navegador        
        function getLocation(){
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }else{ 
                alert("Geolocation is not supported by this browser.");
            }
        }
        function showPosition(position){            
            //latitud
            $("#latitud_"+campo).val(position.coords.latitude);
            //longitud
            $("#longitud_"+campo).val(position.coords.longitude);
        }
       
        
        function mdSignature(element,date,attachment){
            if($(element).prop('checked')){
                $("#"+attachment).show();
                $("#"+date).show();
            }else{
                $("#"+attachment).hide();
                $("#"+date).hide();
            }
        }
        function amendmentShow(element){
            if($(element).prop('checked')){
                $("#elementAmendment").show();
            }else{
                $("#elementAmendment").hide();                
            }
        }

        $(document).ready(function(){
                
                var from_calendar='<?=$_GET['calendar']?>';
                var from_view='<?=$_GET['edit']?>';
                if(from_calendar==='si' || from_view==='si'){
                    
                    $("#modalAddEvaluation").modal('show');
                }
                var pat_id='<?=$_GET['patient_id']?>';
                var disciplinas='<?=$_GET['disciplina']?>';
                   
                if(pat_id!==''){                    
                    search_result_edit(pat_id,disciplinas);  
                }
                //consulto modal poc 
                var poc='<?=$_GET['poc']?>';
                
                if(poc==='si'){
                    
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
    function cancelar(pat_id,disciplina){
    
        window.location="../../../mvc/vista/gestion_clinic?patient_id="+pat_id+"&disciplina="+disciplina;
    }

    function loadDiscipline(field){
        if($("#"+field).val()!= '')
        $("#discipline_description").val($("#"+field+" option:selected").text());
    }

    function agregar_cpt(){        
          $("#modalCpt").modal('show');  
    }
    function modificarCpt(pat_id,disciplina,id,id_note){
        
        window.location="../../../mvc/vista/gestion_clinic?modificar_cpt=si&id="+id+"&patient_id="+pat_id+"&disciplina="+disciplina+"&id_note="+id_note;
    }  
    
    function guardar_cpt(id){
        
        var start_hour=$("#start_note_hour").val();
        var start_minute=$("#start_note_minute").val();
        var start_time=$("#start_note_type").val();
        
        var end_hour=$("#to_note_hour").val();
        var end_minute=$("#to_note_minute").val();
        var end_time=$("#to_note_type").val();
        
        var start=start_hour+":"+start_minute+":"+start_time;
        var end=end_hour+":"+end_minute+":"+end_time;        
        var units=$("#unid_note").val();
        
        if(units===''){
            alert("Please, inicate the units");
            return false;
        }
        if(start===''){
            alert("Please, inicate the start");
            return false;
        }
        if(end===''){
            alert("Please, inicate the end");
            return false;
        }
        $.ajax({
                url: '../../../mvc/controlador/gestion_clinic/add_cpt.php',
                data: {                                                                                        
                    id_note: id,
                    id_cpt: $("#cpt_note").val(),
                    id_diagnosis: $("#diagnostic_id_note").val(),
                    location:$("#location_note").val(),
                    units: $("#unid_note").val(),
                    duration:$("#duration_note").val(),
                    start: start,
                    end:end,

                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success!=='false'){
                       window.location="../../../mvc/vista/gestion_clinic?note=si&id_note="+id;
                    }else{
                        window.location="../../../mvc/vista/gestion_clinic?note=si&id_note="+id;
                    }
                }

        });
    }
    function edit_cpt(id,id_table){
        
        var start_hour=$("#start_note_hour_edit").val();
        var start_minute=$("#start_note_minute_edit").val();
        var start_time=$("#start_note_type_edit").val();
        
        var end_hour=$("#to_note_hour_edit").val();
        var end_minute=$("#to_note_minute_edit").val();
        var end_time=$("#to_note_type_edit").val();
        
        var start=start_hour+":"+start_minute+":"+start_time;
        var end=end_hour+":"+end_minute+":"+end_time;        
        var units=$("#unid_note_edit").val();
        
        if(units===''){
            alert("Please, inicate the units");
            return false;
        }
        
        $.ajax({
                url: '../../../mvc/controlador/gestion_clinic/edit_cpt.php',
                data: {                                                                                        
                    id: id_table,
                    id_cpt: $("#cpt_note_edit").val(),
                    id_diagnosis: $("#diagnostic_id_note_edit").val(),
                    location:$("#location_note_edit").val(),
                    units: $("#unid_note_edit").val(),
                    duration:$("#duration_note_edit").val(),
                    start: start,
                    end:end,

                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success!=='false'){
                       window.location="../../../mvc/vista/gestion_clinic?note=si&id_note="+id;
                    }else{
                        window.location="../../../mvc/vista/gestion_clinic?note=si&id_note="+id;
                    }
                }

        });
    }
    function eliminarCpt(id,id_note){
        
        var confirmar=confirm("¿Are you sure to delete this cpt?");
        
        if(confirmar){
            
            $.ajax({
                url: '../../../mvc/controlador/gestion_clinic/delete_cpt.php',
                data: {                                                                                        
                    id_note: id_note,
                    id: id                   
                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success!=='false'){
                       window.location="../../../mvc/vista/gestion_clinic?note=si&id_note="+id_note;
                    }else{
                        window.location="../../../mvc/vista/gestion_clinic?note=si&id_note="+id_note;
                    }
                }

            });
        }else{
            return false;
        }
        
    }
    function cancelar_note(id){    
        window.location="../../../mvc/vista/gestion_clinic?note=si&id_note="+id;
    }

  </script>
</head>
<body>
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br/>
    <div class="row">
        <div class="col-lg-12" style="padding-top:10px;">            
            <div class="col-lg-3">
                <select id="patients_id" name="patients_id">
                    <option value="">Patients..</option>                                      
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

                     $sqlDiscipline  = "SELECT *,discipline.Name as disciplina FROM employee 
                        left join discipline on employee.discipline_id=discipline.DisciplineID
                        WHERE id = '".$user_id."' AND discipline_id IS NOT NULL"; 
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
   

     <div class="row" >
        <div class="col-lg-12" style="padding-bottom:10px;padding-top:10px;border: 1px solid transparent;border-color: #e7e7e7;">
            <div class="col-lg-1"><button type="button" class="btn" onclick="openModal('modalAddPrescription','patients_id','un Paciente','prescription','Prescription');"><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;RX</button></div>
            <div class="col-lg-1"><button type="button" class="btn btn-warning" disabled="disabled" onclick="openModal('modalAddEvaluation','prescSelect','una Prescription activa','eval','Evaluation');"><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;Eval</button></div>
            <div class="col-lg-1"><button type="button" class="btn" onclick="openModal('modalAddPoc','evalSelect','una Evaluacion activa','poc','POC');" <?php  if($discipline['assistant']!=0 || $discipline['discipline_id']==0 || $discipline['discipline_id']=='' || $discipline['discipline_id']==null){?> disabled=""<?php }?>><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;POC</button></div>              
            <div class="col-lg-1"><button type="button" class="btn btn-warning" disabled="disabled" onclick="openModal('modalAddNote','pocSelect','un POC activa','note','Note');"><img src="../../../images/agregar.png" width="15" height="15"/>&nbsp;Note</button></div>
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
    <!-- /.modales --> 
    
    <div class="modal fade" id="modalAddPrescription" name = "modalAddPrescription" tabindex="-1" role="dialog" aria-labelledby="modalAddPrescription">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
<!--                        <div class="col-md-9">
                            <div class="panel-heading nav nav-tabs text-right">
                                <h5 class="widget-user-username"><strong>Coloca,</strong></h5>
                                <h5 class="widget-user-desc">Un slogan aqui</h5>
                            </div> /.widget-user-image 
                        </div>-->
                    </div>
                </div>

                <div class="modal-header bg-card-header">
                    <h4 class="modal-title"><?='New prescription'?></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <form id="formPrescription" onsubmit="return validar_form();" enctype="multipart/form-data">
                            <div class="row">
                                <label class="col-lg-2">Patients:</label>
                                <div class="col-lg-3">
                                    <input type="hidden" id="patients_id_hidden" name="patients_id">                                    
                                    <input type="hidden" name="discipline_id_hidden" id="discipline_id_hidden">
                                    <div id="patients_name_modal"></div>
                                </div>                         
                                <label class="col-lg-1">Discipline:</label>
                                <div class="col-lg-4">                                    
                                    <div id="discipline_modal"></div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Company:</label>
                                <div class="col-lg-5">
                                    <select id="companies_id" name="companies_id">
                                        <option value=''>Select..</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Diagnostic:</label>
                                <div class="col-lg-5">
                                    <select id="diagnostic_id" name="diagnostic_id"><option value=''>Select..</option></select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">From:</label>
                                <div class="col-lg-5">
                                    <input type="text" name="Issue_date" id="from_hidden" class="form-control" placeholder="From"/>
                                </div>
                            </div>                            
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">To:</label>
                                <div class="col-lg-5">
                                    <input type="text" name="End_date" id="to_hidden" class="form-control" placeholder="To"/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Physician Name:</label>
                                <div class="col-lg-5">
                                    <select id="physician_id" name="physician_id"><option value=''>Select..</option></select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Attachment:</label>
                                <div class="col-lg-5">
                                    <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                                </div>
                            </div>
                            <hr/>
                            <div class="row card_footer">                                                                            
                                    <div class="col-sm-offset-4 col-sm-2 mg-top-sm">                                        
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>                                                                            
                                    <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                                    </div>						
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="modalAddEvaluation" name = "modalAddEvaluation" tabindex="-1" role="dialog" aria-labelledby="modalAddEvaluation" data-backdrop="static">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
                        <div class="col-md-offset-2 col-md-3" >
                            <h4 class="modal-title" style="margin-top: 45px;color: #FFF;"><?php if(isset($_GET['id_eval'])){echo "Edit Evaluation #".$_GET['id_eval'];}else{echo 'New Evaluation';}?></h4>
                        </div>
                    </div>
                </div>

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body" style="padding-top: 0px;">
                    <div class="panel-body">  
                        <form id="formEvaluation" <?php if(isset($_GET['calendar']) || isset($_GET['edit'])){?>onsubmit="return validar_form_eval('edit');"<?php }else{?> onsubmit="return validar_form_eval('insert');"<?php } ?> enctype="multipart/form-data">
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Patients:</label>
                                <div class="col-lg-3">
                                    <input type="hidden" name="prescription_id_eval_hidden" id="prescription_id_eval_hidden" value="<?php if(isset($_GET['id_preescription'])){echo $_GET['id_preescription'];}?>">
                                    <input type="hidden" name="id_eval" id="id_eval" value="<?php if(isset($_GET['id_eval'])){echo $_GET['id_eval'];}?>">
                                    <input type="hidden" name="patients_eval_hidden" id="patients_eval_hidden" value="<?=$data[0]['patient_id']?>">
                                    <input type="hidden" name="discipline_id_eval_hidden" id="discipline_id_eval_hidden" value="<?=$data[0]['discipline_id']?>">
                                    <input type="hidden" name="diagnostic_eval_hidden" id="diagnostic_eval_hidden" value="<?=$data[0]['diagnostic']?>">
                                    <input type="hidden" name="company_id_eval_hidden" id="company_id_eval_hidden" value="<?=$data[0]['company']?>">
                                    <input type="hidden" name="company_id" id="company_id" value="<?=$data[0]['company']?>">
                                    <div id="patients_name_eval_modal"><?php echo $data[0]['name'];?></div>
                                </div>                         
                                <label class="col-lg-1 withoutPadding">Pat ID: </label>
                                <div class="col-lg-2 withoutPadding">
                                    <div id="pat_id_eval_modal"><?php echo $data[0]['Pat_id']?></div>
                                </div>
                                <label class="col-lg-1 withoutPadding">Company:</label>
                                <div class="col-lg-2 withoutPadding">                                    
                                    
                                        <select name='company_id' id='company_id' class="form-control">                                                				
                                            <?php
                                                $sql_companie  = "Select * from companies";
                                                
                                                $resultado_companie = ejecutar($sql_companie,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_companie)) 
                                                {
                                                    if($data[0]['company']==$row["id"]){
                                                        print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["id"]."'>".$row["company_name"]." </option>"); 
                                                    }
                                                }
                                                    
                                            ?>
                                        </select> 
                                    
                                </div><br><hr>
                                
                                
                            </div>
                            


                            <div class="row">

                              <div class="col-lg-9" >

                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Date From:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <input type="text" name="from_eval" id="from_eval" class="form-control" style="width: 180px;" placeholder="From" value="<?=$data[0]['from']?>"/>
                                </div>
                                <label class="col-lg-1 withoutPadding">Eval due:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <input type="text" name="to_eval" id="to_eval" class="form-control" style="width: 180px;" placeholder="To" value="<?=$data[0]['to']?>"/>
                                </div>
                            </div>  <br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Discipline:&nbsp;&nbsp;</label>
                                <div class="col-lg-4 withoutPadding">                                    
                                    <div id="discipline_eval_modal">&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php echo $data[0]['disciplina']?></div>
                                </div>
                                <label class="col-lg-1 withoutPadding">Diag.:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select name='diagnostic_id_eval' id='diagnostic_id_eval' class="">                                                				
                                            <?php
                                                $sql_diagnostic  = "Select DiagCodeId,concat(DiagCodeValue,', ',DiagCodeDescrip) as name from diagnosiscodes where TreatDiscipId=".$data[0]['discipline_id'];
                                                
                                                $resultado_diagnostic = ejecutar($sql_diagnostic,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_diagnostic)) 
                                                {
                                                    if($data[0]['diagnostic']==$row["DiagCodeId"]){
                                                        print("<option value='".$row["DiagCodeId"]."' selected>".$row["name"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                    }
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Therapist:</label>
                                <div class="col-lg-4 withoutPadding">
                                   &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data[0]['employee']?>
                                </div>
                                <label class="col-lg-1 withoutPadding">NPI:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <div id="npi_eval_modal">
                                        <?=$npi?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Date of Eval:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <input type="text" name="date_of_eval" style="width: 200px;" id="date_of_eval" value="<?php echo $data[0]['created']?>" class="form-control" disabled=""led placeholder="Date of Evaluation"/>
                                </div>
                                <label class="col-lg-1 withoutPadding"> Cpt:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select name='cpt' id='cpt' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from cpt where type='EVAL' AND DisciplineId=".$data[0]['discipline_id'];
                                                
                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {
                                                    if($data[0]['cpt']==$row["ID"]){
                                                        print("<option value='".$row["ID"]."' selected>".$row["cpt"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["ID"]."'>".$row["cpt"]." </option>"); 
                                                    }
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Units:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select id="unid_of_eval" name="unid_of_eval" style="width: 100px;">
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data[0]['units']==1){?>selected=""<?php }?>>1</option>
                                        <option value='2' <?php if($data[0]['units']==2){?>selected=""<?php }?>>2</option>
                                        <option value='3' <?php if($data[0]['units']==3){?>selected=""<?php }?>>3</option>
                                        <option value='4' <?php if($data[0]['units']==4){?>selected=""<?php }?>>4</option>
                                    </select>                                    
                                </div>
                                
                                <label class="col-lg-1 withoutPadding">Minutes:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select id="min_of_eval" name="min_of_eval" style="width: 100px;">
                                        <option value=''>Select..</option>
                                        <option value='15' <?php if($data[0]['minutes']==15){?>selected=""<?php }?>>15</option>
                                        <option value='30' <?php if($data[0]['minutes']==30){?>selected=""<?php }?>>30</option>
                                        <option value='45' <?php if($data[0]['minutes']==45){?>selected=""<?php }?>>45</option>
                                        <option value='60' <?php if($data[0]['minutes']==60){?>selected=""<?php }?>>60</option>
                                    </select>                                                                      
                                </div>
                            </div><br>

                           <div class="row">
                                <label class="col-lg-1 withoutPadding">Phy. Referral: </label>
                                <div class="col-lg-4 withoutPadding">
                                    <?php echo $data[0]['ppcp']?>
                                </div>
                                <label class="col-lg-1 withoutPadding">DOB/Age: </label>
                                <div class="col-lg-4 withoutPadding">
                                    <div id="npi_eval_modal" style="font-size:13px">
                                      &nbsp;&nbsp;
                                        <?php echo $data[0]['pdob']?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-2 withoutPadding">status:</label>
                                <div class="col-lg-10 withoutPadding">
                                    <select id="status_eval" name="status_eval" style="width: 100px;" class="form-control">
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data[0]['status_id']==1){?>selected=""<?php }?>>In progress</option>
                                        <option value='2' <?php if($data[0]['status_id']==2){?>selected=""<?php }?>>Active</option>                                        
                                        <option value='3' <?php if($data[0]['status_id']==3){?>selected=""<?php }?>>Inactive</option>
                                    </select>                                                                      
                                </div>
                            </div>
                            
                            </div>
                              <div class="col-lg-3" align="left" style="text-align:left !important">
                     <div align="left" class="col-lg-12 withoutPadding"  style="text-align:left !important;align:left !important ; font-size:18px; font-weight:bold;"> <font color='#1e2c51'>                                                                
                                    <?=$data[0]['company_name']?><br>
                                    <?=$data[0]['facility_address']?><br>
                                    <?=$data[0]['facility_city'].",".$data[0]['facility_state'].",".$data[0]['facility_zip']?><br>
                                (P) <?=$data[0]['facility_phone']?><br>
                                (F) <?=$data[0]['facility_fax']?><br>
                                </font>
                                </div>
                                </div>
                              

                              </div>  
                              <br><hr>

                            
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-lg-12"></div>
                                <div class="col-lg-12">
                                    <input type="radio" name="templateCkEditor" id="templateCkEditor1" value="ckeditor" onclick="mostrarCkEditor('divCkeditor','template');" checked/>&nbsp;CkEditor &nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="templateCkEditor" id="templateCkEditor2" value="template" onclick="mostrarTemplate('divCkeditor','template');"/>&nbsp;Template
                                </div>                                                                    
                            </div>
                            <div class="row" style="margin-top: 15px;" id="divCkeditor" >
                                <div class="col-lg-12"></div>
                                <div class="col-lg-12">
                                    <?php 
                                        if(!isset($_GET['id_eval'])){
                                    ?>                                 
                                        <textarea class="ckeditor" name="editor" id="editor"></textarea>                                            
                                    <?php }else{ ?>                                            
                                        <textarea class="ckeditor" name="editor" id="editor"><?=$data[0]['ckeditor']?></textarea>
                                    <?php } ?>                                   
                                </div>                                                                    
                            </div>
                            <div class="row" style="margin-top: 15px;display:none;" id="template">
                                <div class="col-lg-12"></div>
                                <div class="col-lg-12">
                                    <select id="template_id" name="template_id"><option value=''>Select..</option></select>
                                </div>                                                                
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-lg-offset-2 col-lg-2">

<!--                                    <button type="button" class="btn btn-primary" onclick="signature('signature','signatureEvaluationStatus');"><i class="fa fa-check"></i>&nbsp;Signature</button>-->
                                    <button type="button" class="btn btn-primary" onclick="signature('signature','firma_eval');" <?php if($_SESSION['user_id']!=$data[0]['id_user']){?>disabled=""<?php }?>><i class="fa fa-check"></i>&nbsp;Signature</button>
                                    <input type="hidden" name="signatureEvaluationStatus" id="signatureEvaluationStatus" value="0">

                                </div>
                                <div class="col-lg-3">
                                    <?php 
                                        if(isset($_GET['id_eval']) && $data[0]['therapist_signed']==1){
                                    ?>
                                        <div id="signature">
                                            <?php }else{?>
                                                 <div id="signature" style="display:none">
                                            <?php } ?>
                                                 <?php 
                                                    if(!isset($_GET['id_eval'])){
                                                ?>
                                                        <img src="../../../images/sign/signature.png" style="width: 100px">
                                                    <?php }elseif($data[0]['therapist_signed']==1){?>
                                                        <img src="../../../images/sign/<?=$data[0]['therapist_signature']?>" style="width: 100px">
                                                    <?php }else{ ?>
                                                        <img src="../../../images/sign/<?=$data[0]['therapist_signature']?>" style="width: 100px">
                                                    <?php } ?>
                                            </div>
                                            <input type="hidden" name="firma_eval" id="firma_eval"/>
                                            <input type="hidden" name="ruta_firma_eval" id="ruta_firma_eval"/>
                                            <input type="hidden" name="latitud_firma_eval" id="latitud_firma_eval"/>
                                            <input type="hidden" name="longitud_firma_eval" id="longitud_firma_eval"/>
                                        </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-4"  id="to_sign"></div>                                                                
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">AMENDMENT:</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" value="1" name="amendment" id="amendment" onclick="amendmentShow(this)"/>
                                </div>
                            </div>
                            
                            <div id='elementAmendment' style="display:none;">
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <input type="radio" name="templateCkEditorAmendment" id="templateCkEditorAmendment" onclick="mostrarCkEditorAmendment();" checked/>&nbsp;CkEditor &nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="templateCkEditorAmendment" id="templateCkEditorAmendment" onclick="mostrarTemplateAmendment();"/>&nbsp;Template
                                    </div>                                                                    
                                </div>
                                <div class="row" style="margin-top: 15px;" id="ckeditorAmendment" >
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <textarea class="ckeditor" name="editor_amendment" id="editor_amendment"></textarea>
                                    </div>                                                                    
                                </div>
                                <div class="row" style="margin-top: 15px;display:none;" id="templateAmendment">
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <select id="template_amendment_id" name="template_amendment_id"><option value=''>Select..</option></select>
                                    </div>                                                                
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">MD Signed:</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" value="1" name="md_signed" id="md_signed" onclick="mdSignature(this,'date_signed','attachment')"/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;display:none;" id="date_signed">
                                <label class="col-lg-2">Date of Signed:</label>
                                <div class="col-lg-5">
                                    <input type="text" name="date_of_signed" id="date_of_signed" class="form-control" placeholder="Date of Signed" value="<?=$data[0]['date_signed']?>"/>                                    
                                </div>
                            </div> 
                            <div class="row" style="margin-top: 15px;display:none;" id="current_document">
                                <label class="col-lg-2">Document:</label>
                                <div class="col-lg-10" id="route_document">
                                    
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;display:none;" id="attachment" >
                                <label class="col-lg-2">Attachment:</label>
                                <div class="col-lg-5">
                                    <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                                </div>
                            </div>
                            <hr/>
                            <div class="row card_footer">                                                                            
                                    <div class="col-sm-offset-4 col-sm-2 mg-top-sm">  
                                        <?php if($_SESSION['user_id']==$data[0]['id_user']) {      ?>                        
                                        <button type="submit" class="btn btn-success" <?php if(isset($_GET['id_eval']) && $data[0]['therapist_signed']==1){?>disabled=""<?php }?>><?php if(isset($_GET['id_eval'])){echo "Edit";}else{echo "Save";}?></button>
                                        <?php } ?>
                                    </div>                                                                            
                                    <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                                        <button type="button" class="btn btn-danger" onclick="cancelar(<?=$data[0]['patient_id']?>,<?=$data[0]['discipline_id']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                                    </div>
                                    <a href="pdf/evaluation.php?id=<?=$_GET['id_eval']?>" class="btn btn-default" target="_blank" style="cursor: pointer;" class="ruta"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="modalAddPoc" name = "modalAddPoc" tabindex="-1" role="dialog" aria-labelledby="modalAddPoc" data-backdrop="static">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
<!--                        <div class="col-md-9">
                            <div class="panel-heading nav nav-tabs text-right">
                                <h5 class="widget-user-username"><strong>Coloca,</strong></h5>
                                <h5 class="widget-user-desc">Un slogan aqui</h5>
                            </div> /.widget-user-image 
                        </div>-->
                    </div>
                </div>

                <div class="modal-header bg-card-header">
                    <h4 class="modal-title"><?=$titulo?></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <form id="formPoc" <?php if(!isset($_GET['id_poc'])){?>onsubmit="return validar_form_poc('insertar');"<?php }else{?> onsubmit="return validar_form_poc('edit');"<?php } ?>enctype="multipart/form-data">
                           
                    <div>

                        <div class="col-lg-9">

                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Patients: </label>
                                <div class="col-lg-6 withoutPadding">
                                   &nbsp;&nbsp; <?php echo $data[0]['patient']." <b>(".$data[0]['Pat_id'].")</b>"?>
                                </div>
                                <label class="col-lg-1 withoutPadding">Created: </label>
                                <div class="col-lg-3 withoutPadding">
                                    <input type="text" name="created_poc" id="created_poc" disabled="" class="form-control" value="<?=$data[0]["created"]?>"/>
                                </div>
                                
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-1">From:</label>
                                <div class="col-lg-4">
                                    <input type="text" name="from_poc" id="from_poc" class="form-control" placeholder="From" <?php if(isset($_GET['id_poc'])){?>value="<?=$data_poc[0]['POC_due']?>"<?php } ?>/>
                                </div>
                                
                                <label class="col-lg-1">To:</label>
                                <div class="col-lg-4">
                                    <input type="text" name="to_poc" id="to_poc" class="form-control" placeholder="To" <?php if(isset($_GET['id_poc'])){?>value="<?=$data_poc[0]['Re_Eval_due']?>"<?php } ?>/>
                                </div>
                                
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Therapist:</label>
                                <div class="col-lg-3 withoutPadding">
                                 &nbsp;&nbsp;&nbsp;   <?php echo $discipline['employee']?>
                                </div>
                                
                                <label class="col-lg-1 withoutPadding">Diag.:</label>
                                <div class="col-lg-6 withoutPadding">
                                    <select name='diagnostic_id_poc' id='diagnostic_id_poc' class="">                                                				
                                            <?php
                                                $sql_diagnostic  = "Select DiagCodeId,concat(DiagCodeValue,', ',DiagCodeDescrip) as name from diagnosiscodes where TreatDiscipId=".$data[0]['discipline_id'];
                                                
                                                $resultado_diagnostic = ejecutar($sql_diagnostic,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_diagnostic)) 
                                                {
                                                    if(!isset($_GET['id_poc'])){
                                                        if($data[0]['diagnostic']==$row["DiagCodeId"]){
                                                            print("<option value='".$row["DiagCodeId"]."' selected>".$row["name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                        }
                                                    }else{
                                                        if($data_poc[0]['diagnostic']==$row["DiagCodeId"]){
                                                            print("<option value='".$row["DiagCodeId"]."' selected>".$row["name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                        }
                                                    }
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">DOB:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <?php echo $data[0]['DOB']?>
                                </div>
                                
                                <label class="col-lg-1 withoutPadding">Age:</label>
                                <div class="col-lg-4 withoutPadding" style="font-size:13px">                                    
                                    <?php echo $data[0]['pdob']?>
                                </div>
                            </div>
                           
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Physician:</label>
                                <div class="col-lg-4 withoutPadding">
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo $data[0]['physician']?>
                                </div>
                              
                              </div>
                              <br>  
                              <div class="row"><?=$data[0]['status']?>
                                <label class="col-lg-2 withoutPadding">status:</label>
                                <div class="col-lg-10 withoutPadding">
                                    <select id="status_eval" name="status_eval" style="width: 100px;" class="form-control">
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data_poc[0]['status']==1 || !isset($_GET['id_poc'])){?>selected=""<?php }?>>In progress</option>
                                        <option value='2' <?php if($data_poc[0]['status']==2){?>selected=""<?php }?>>Active</option>                                        
                                        <option value='3' <?php if($data_poc[0]['status']==3){?>selected=""<?php }?>>Inactive</option>
                                    </select>                                                                      
                                </div>
                            </div>
                            
                              <hr>

                              </div>

                              <div class="col-lg-3">

                                <div>

                                <label class="col-lg-12 withoutPadding">Company:</label>
                                <div class="col-lg-12 withoutPadding">                                    
                                    
                                        <select name='company_id' id='company_id' class="form-control">                                                				
                                            <?php
                                                $sql_companie  = "Select * from companies";
                                                
                                                $resultado_companie = ejecutar($sql_companie,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_companie)) 
                                                {
                                                    if(!isset($_GET['id_poc'])){
                                                        if($data[0]['company']==$row["id"]){
                                                            print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["id"]."'>".$row["company_name"]." </option>"); 
                                                        }
                                                    }else{
                                                        
                                                        if($data_poc[0]['Company']==$row["id"]){
                                                            print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["id"]."'>".$row["company_name"]." </option>"); 
                                                        }
                                                    }
                                                }
                                                    
                                            ?>
                                        </select> 
                                    
                                </div>
                                
                                <div class="col-lg-12 withoutPadding"  style="font-size:18px; font-weight:bold;"> <font color='#1e2c51'>                                                                
                                    <?php 
                                        if(!isset($_GET['id_poc'])){
                                    ?>
                                    <?=$data[0]['company_name']?><br>
                                    <?=$data[0]['facility_address']?><br>
                                    <?=$data[0]['facility_city'].",".$data[0]['facility_state'].",".$data[0]['facility_zip']?><br>
                                (P) <?=$data[0]['facility_phone']?><br>
                                (F) <?=$data[0]['facility_fax']?><br>
                                <?php }else{?>
                                    <?=$data_poc[0]['company_name']?><br>
                                    <?=$data_poc[0]['facility_address']?><br>
                                    <?=$data_poc[0]['facility_city'].",".$data[0]['facility_state'].",".$data[0]['facility_zip']?><br>
                                (P) <?=$data_poc[0]['facility_phone']?><br>
                                (F) <?=$data_poc[0]['facility_fax']?><br>
                                <?php } ?>
                                </font>
                                
                                </div>
                            </div>

                          </div>
                        </div>
                          <br>
                            <hr>




                            <div class="row">
                                
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <input type="radio" name="templateCkEditorPoc" id="templateCkEditorPoc" value="ckeditor" onclick="mostrarCkEditor('divCkeditorPoc','templatePoc');" checked/>&nbsp;CkEditor &nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="templateCkEditorPoc" id="templateCkEditorPoc" value="template" onclick="mostrarTemplate('divCkeditorPoc','templatePoc');"/>&nbsp;Template
                                    </div>                                                                    
                                </div>
                                <div class="row" style="margin-top: 15px;" id="divCkeditorPoc" >
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <?php 
                                            if(!isset($_GET['id_poc'])){
                                        ?>
                                        
                                            <textarea class="ckeditor" name="editorPoc" id="editorPoc"></textarea>
                                        <?php }else{ ?>
                                            <textarea class="ckeditor form-control" name="editorPoc" id="editorPoc"><?=$data_poc[0]['ckeditor']?></textarea>
                                        <?php }?>
                                    </div>                                                                    
                                </div>
                                <div class="row" style="margin-top: 15px;display:none;" id="templatePoc">
                                    <div class="col-lg-12"></div>
                                    <div class="col-lg-12">
                                        <select id="template_id_Poc" name="template_id_Poc"><option value=''>Select..</option></select>
                                    </div>                                                                
                                </div>
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-lg-offset-2 col-lg-2">
                                        <button type="button" class="btn btn-primary" onclick="signature('signaturePoc','firma_poc');" <?php if(!isset($_GET['id_poc'])){ if($_SESSION['user_id']!=$data[0]['id_user']){?>disabled=""<?php }}?> <?php if(isset($_GET['id_poc'])){ if($_SESSION['user_id']!=$data_poc[0]['user_id']){?>disabled=""<?php }}?>><i class="fa fa-check"></i>&nbsp;Signature</button>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php 
                                            if(isset($_GET['id_poc']) && $data_poc[0]['therapist_signed']==1){
                                        ?>
                                            <div id="signaturePoc">
                                                <?php }else{?>
                                                     <div id="signaturePoc" style="display:none">
                                                <?php } ?>
                                                     <?php 
                                                        if(!isset($_GET['id_poc'])){
                                                    ?>
                                                            <img src="../../../images/sign/signature.png" style="width: 100px">
                                                        <?php }elseif($data_poc[0]['therapist_signed']==1){?>
                                                            <img src="../../../images/sign/<?=$data_poc[0]['therapist_signature']?>" style="width: 100px">
                                                        <?php }else{ ?>
                                                            <img src="../../../images/sign/signature.png" style="width: 100px">
                                                        <?php } ?>
                                                </div>
                                                <input type="hidden" name="firma_poc" id="firma_poc"/>
                                                <input type="hidden" name="ruta_firma_poc" id="ruta_firma_poc"/>
                                                <input type="hidden" name="latitud_firma_poc" id="latitud_firma_poc"/>
                                                <input type="hidden" name="longitud_firma_poc" id="longitud_firma_poc"/>
                                            </div>
                                    </div>
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-4"  id="to_sign_discharge"></div>                                                                
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input type="hidden" name="patient_id" id="patient_id" value="<?=$data[0]['patient_id']?>">
                                    <input type="hidden" name="id_eval" id="id_eval" value="<?=$_GET['id_eval']?>">
                                    <input type="hidden" name="discipline_id" id="discipline_id" value="<?=$data[0]['discipline_id']?>">
                                    <?php 
                                        if(isset($_GET['id_poc'])){
                                    ?>
                                        <input type="hidden" name="id_careplans_edit" id="id_careplans_edit" value="<?=$_GET['id_poc']?>">
                                    <?php } ?>
                                    <?php
                                        $particion=explode(',',$data[0]['patient']);                                       
                                    ?>
                                    <input type="hidden" name="name_patient" id="name_patient" value="<?=$particion[0]?>">
                                    <input type="hidden" name="last_name_patient" id="last_name_patient" value="<?=$particion[1]?>">
                                </div>                         
                                
                            </div>
                            
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Attachment:</label>
                                <div class="col-lg-5">
                                    <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                                </div>
                            </div>
                            <hr/>
                            <div class="row card_footer">                                                                            
                                    <div class="col-sm-offset-4 col-sm-2 mg-top-sm">   
                                              <?php if($assistant_11==0) {      ?>                              
                                        <button type="submit" class="btn btn-success" <?php if(isset($_GET['id_poc']) && $data_poc[0]['therapist_signed']==1){?>disabled=""<?php }?>><?=$button?></button>
                                        <?php } ?>
                                    </div>                                                                            
                                    <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                                        <button type="button" class="btn btn-danger" onclick="cancelar(<?=$data[0]['patient_id']?>,<?=$data[0]['discipline_id']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                                    </div>
                                    <a href="pdf/poc.php?id=<?=$_GET['id_poc']?>" class="btn btn-default" target="_blank" style="cursor: pointer;" class="ruta"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="modalAddNote" name = "modalAddNote" tabindex="-1" role="dialog" aria-labelledby="modalAddNote" data-backdrop="static">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
<!--                        <div class="col-md-9">
                            <div class="panel-heading nav nav-tabs text-right">
                                <h5 class="widget-user-username"><strong>Coloca,</strong></h5>
                                <h5 class="widget-user-desc">Un slogan aqui</h5>
                            </div> /.widget-user-image 
                        </div>-->
                    </div>
                </div>

                <div class="modal-header bg-card-header">
                    <h4 class="modal-title"><?php if(isset($_GET['note'])){echo "Edit note #".$_GET['id_note'];}else{echo 'New note';}?></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <form id="formNote" <?php if(isset($_GET['note'])){?>onsubmit="return validar_form_note('edit');"<?php }else{?> onsubmit="return validar_form_note('insertar');"<?php } ?> enctype="multipart/form-data">
                            
                            
                        <div>
                        <div >

                          <div class="col-lg-9">


                            <div class="row">
                                <input type="hidden" name="id_notes" id="id_notes" value="<?=$_GET['id_note']?>">
                                <input type="hidden" name="id_careplans" id="id_careplans" value="<?=$note[0]['id_careplans']?>">
                                <input type="hidden" name="patients_id" id="patients_id" value="<?=$note[0]['patient_id']?>">
                                <input type="hidden" name="discipline_id" id="discipline_id" value="<?=$note[0]['discipline_id']?>">   
                            </div>
                                 
                                  <div class="row">                                                                 
                                <label class="col-lg-1  withoutPadding">Patients: </label>
                                <div class="col-lg-4 withoutPadding">
                                  <?php echo $note[0]['patient']."<b> ( ".$note[0]['Pat_id'].")</b>"?>
                                </div>
                                

                               
                                <label class="col-lg-1 withoutPadding">DOB/Age: </label>
                                <div class="col-lg-4 withoutPadding">
                                    <div id="npi_eval_modal" style="font-size:13px">
                                      &nbsp;&nbsp;
                                        <?php echo $note[0]['pdob']?>
                                    </div>
                                </div>
                                


                            </div>

                            <br>
                            
                            <div class="row" style="margin-top: 15px;">
                               
                                <label class="col-lg-1 withoutPadding">Visit Date:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <?php echo $note[0]['visit_date']?>                                
                                </div>
                                


                               
                                <label class="col-lg-1 withoutPadding">Duration:</label>
                                <div class="col-lg-3 withoutPadding">
                                    <select name="duration" id="duration">
                                        <option value="15" <?php if($note[0]['duration']==15){?>selected=""<?php }?>>15</option>
                                        <option value="30" <?php if($note[0]['duration']==30){?>selected=""<?php }?>>30</option>
                                        <option value="45" <?php if($note[0]['duration']==45){?>selected=""<?php }?>>45</option>
                                        <option value="60" <?php if($note[0]['duration']==60){?>selected=""<?php }?>>60</option>
                                        
                                    </select>                                    
                               
                                </div>
                                <br>

                            </div> 
                            <br>
                            <div class="row" >
                                
                              
                                <label  class="col-lg-1 withoutPadding" >Therapist:</label>
                                <div  class="col-lg-4 withoutPadding">
                                    <?php echo $note[0]['employee']." ".$note[0]['NPI']?>
                                </div>
                               


                                
                                
                                     <label class="col-lg-1 withoutPadding">Discipline:</label>
                                <div class="col-lg-4 withoutPadding" >
                                  &nbsp;&nbsp;  <?php echo $note[0]['disciplina']?>                                
                                </div>

                               
                           
                            </div> 



                            <!-- DIV PARA CERRAR LAS DOS PRIMERAS COLUMNAS  -->
                            </div>


                            <div class="col-lg-3">
                             
                              <div>

                                    <label class="col-lg-12 withoutPadding">Company:</label>
                                    <div class="col-lg-12 withoutPadding">                                    

                                            <select name='company_id' id='company_id' class="form-control">                                                       
                                                <?php
                                                    $sql_companie  = "Select * from companies";

                                                    $resultado_companie = ejecutar($sql_companie,$conexion);
                                                    while ($row=mysqli_fetch_array($resultado_companie)) 
                                                    {                                                        

                                                        if($note[0]['company_poc']==$row["id"]){
                                                            print("<option value='".$row["id"]."' selected>".$row["company_name"]." </option>"); 
                                                        }else{
                                                            print("<option value='".$row["id"]."'>".$row["company_name"]." </option>"); 
                                                        }
                                                        
                                                    }

                                                ?>
                                            </select> 
                                    </div>

                                    <div class="col-lg-12 withoutPadding"  style="font-size:18px; font-weight:bold;"> <font color='#1e2c51'>                                                                
                                        
                                        <?=$note[0]['company_name']?><br>
                                        <?=$note[0]['facility_address']?><br>
                                        <?=$note[0]['facility_city'].",".$note[0]['facility_state'].",".$note[0]['facility_zip']?><br>
                                    (P) <?=$note[0]['facility_phone']?><br>
                                    (F) <?=$note[0]['facility_fax']?><br>
                                    
                                    </font>

                                    </div>
                             </div>   
                             
                             <!-- DIV PARA CERRAR LA COLUMNA DE COMPANY -->   
                            </div> 


                    <!-- DIV FINAL ANTES DE LA LINEA  -->        
                   </div>

                   </div>
                            <br>
                            <br><hr>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-lg-10"><button type="button" class="btn" onclick="agregar_cpt();"><img src="../../../images/agregar.png" width="15" height="15"/>Añadir Cpt</button></div><br>
                                <br><hr>
                                <br><table class="table" id="add_cpt" border="0">                                    
                                    <tr class="success">
                                        <td><b>Cpt</b></td>
                                        <td><b>Units</b></td>
                                        <td><b>Location</b></td>
                                        <td><b>Start</b></td>
                                        <td><b>End</b></td>
                                        <td><b>Duration</b></td>
                                        <td><b>Diagnosis</b></td>
                                        <td><b>Actions</b></td>
                                    </tr>
                                    <?php
                                    
                                        $cpt_consult  = "Select N.*,N.start as start_date,N.end as end_date,C.cpt,L.name as location,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnosis from tbl_note_cpt_relation N left join cpt C ON(N.id_cpt=C.ID)"
                                                . "     LEFT JOIN tbl_location_appoiments L ON(N.location=L.id) LEFT JOIN diagnosiscodes D ON(N.id_diagnosis=D.DiagCodeId) WHERE N.id_note=".$_GET['id_note'];

                                        $resultado_cpt = ejecutar($cpt_consult,$conexion);
                                        
                                        while ($row_cpt=mysqli_fetch_array($resultado_cpt)) 
                                        {
                                            $cpt_result[]=$row_cpt;
                                        }
                                        
                                        for($j=0;$j<count($cpt_result);$j++){
                                        
                                    ?>                                    
                                        <tr class="info">
                                            <td><?=$cpt_result[$j]['cpt']?></td>
                                            <td><?=$cpt_result[$j]['units']?></td>
                                            <td><?=$cpt_result[$j]['location']?></td>
                                            <td><?=$cpt_result[$j]['start_date']?></td>
                                            <td><?=$cpt_result[$j]['end_date']?></td>
                                            <td><?=$cpt_result[$j]['duration']?></td>
                                            <td><?=$cpt_result[$j]['diagnosis']?></td>
                                            <td>
                                                <div class="btn-group">            
                                                    <a href="#" data-rel="tooltip" data-original-title="Editar" class="dropdown-toggle btn btn-primary" data-toggle="dropdown"><i class="fa fa-cogs"></i>&nbsp;</a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li align= "center" style="hidden-align: center">                                                    
                                                            <a onclick="modificarCpt(<?=$note[0]['patient_id']?>,<?=$note[0]['discipline_id']?>,'<?=$cpt_result[$j]['id_note_cpt_relation']?>',<?=$_GET['id_note']?>);" style="cursor: pointer;" class="ruta">Edit</a>
                                                        </li>
                                                        <li align= "center" style="hidden-align: center">
                                                            <a onclick="eliminarCpt('<?=$cpt_result[$j]['id_note_cpt_relation']?>',<?=$_GET['id_note']?>);" style="cursor: pointer;" class="ruta">Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    
                                    
                                </table>
                            </div>
                           <hr>
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-12">Subject:</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control" id="snote" name="snote"><?php echo $note[0]['snotes']?></textarea>                                    
                                </div>
                            </div>

                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-12">Objective:</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control" id="onote" name="onote"><?php echo $note[0]['onotes']?> </textarea>                                    
                                </div>
                            </div>                                                        
                                                                                   
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-12">Assessment:</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control" id="anote" name="anote"><?php echo $note[0]['anotes']?> </textarea>                                    
                                </div>
                            </div>

                              <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-12">Plan:</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control" id="pnote" name="pnote"><?php echo $note[0]['pnotes']?> </textarea>                                    
                                </div>
                            </div> 
                            <br><hr>

                           <!--  <div class="row" style="margin-top: 15px;">
                                <label class="col-lg-2">Signed:</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" id="signedNote" name="signedNote" style="width: 15px;height: 15px;" value="1" class="form-control" <?php if($note[0]['user_signed']==1){?>checked=""<?php } ?>>
                                </div>
                                <div class="col-lg-5">
                                    <input type="text" id="dateSigned" name="dateSigned" class="form-control" placeholder="Signed Date" value="<?=$note[0]['user_signed_date']?>">
                                </div>
                            </div> -->
                            
                            <br>


                            
                            <?php if($therapist_type['assistant']==1){
                                 //consulto si tiene la firma agregada para supervisor
                                $firma="SELECT count(*) as contador,therapist_signature   from tbl_signature_note 
                                 WHERE id_note='".$_GET['id_note']."' 
                                #AND actor='SUPERVISOR'
                                ";
                                $resultado_firma = ejecutar($firma,$conexion);                                        
                                while ($row_firma=mysqli_fetch_array($resultado_firma)) 
                                {
                                    $valor_firma['contador']=$row_firma['contador'];
                                    $valor_firma['therapist_signature']=$row_firma['therapist_signature'];
                                }

                                // firma del supervisor 
                                   $firma2="SELECT count(*) as contador,therapist_signature as super  from tbl_signature_note 
                                 WHERE id_note='".$_GET['id_note']."' 
                                AND actor='SUPERVISOR'
                                ";
                                $resultado_firma2 = ejecutar($firma2,$conexion);                                        
                                while ($row_firma2=mysqli_fetch_array($resultado_firma2)) 
                                {
                                   // $valor_firma['contador']=$row_firma['contador'];
                                    $valor_firma2['super']=$row_firma2['super'];
                                }

                                //consulto el supervisor del terapista 
                                $sql_supervisor="SELECT supervisor from employee WHERE id='".$note[0]['user_id']."' ";
                                $resultado_supervisor = ejecutar($sql_supervisor,$conexion);                                        
                                while ($row_supervisor=mysqli_fetch_array($resultado_supervisor)) 
                                {
                                    $valor_supervisor['supervisor']=$row_supervisor['supervisor'];
                                    
                                }
                               
                                
                           }     
                            ?>
                            <div class="row" style="margin-top: 15px;">
                                    <label class="col-lg-1">Supervisor:</label>
                                    <div class="col-lg-offset-2 col-lg-2">
                                        <button type="button" class="btn btn-primary" onclick="signature('signatureNoteSupervisor','firma_note_supervisor');" <?php if($_SESSION['user_id']!=$therapist_type['user2']){?>disabled=""<?php }?>><i class="fa fa-check"></i>&nbsp;Signature</button>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php 
                                            if(isset($_GET['id_note']) && $valor_firma['contador']>0){
                                        ?>
                                            <div id="signatureNote">
                                                <?php }else{?>
                                                     <div id="signatureNoteSupervisor" style="display:none">
                                                <?php } ?>
                                                     <?php 
                                                        if(!isset($_GET['id_note'])){
                                                    ?>
                                                            <img src="../../../images/sign/signature.png" style="width: 100px">
                                                        <?php }elseif($valor_firma['contador']>0){?>
                                                            <img src="../../../images/sign/<?=$valor_firma['therapist_signature']?>" style="width: 100px">
                                                        <?php }else{ ?>
                                                            <img src="../../../images/sign/signature.png" style="width: 100px">
                                                        <?php } ?>
                                                </div>
                                                <input type="hidden" name="firma_note_supervisor" id="firma_note_supervisor"/>
                                                <input type="hidden" name="ruta_firma_note_supervisor" id="ruta_firma_note_supervisor"/>
                                                <input type="hidden" name="latitud_firma_note_supervisor" id="latitud_firma_note_supervisor"/>
                                                <input type="hidden" name="longitud_firma_note_supervisor" id="longitud_firma_note_supervisor"/>
                                            </div>
                                    </div>
                            </div>
                            <?php //} ?>
                            
                            <?php 
                                //consulto si tiene la firma agregada para terapista
                                $firma_terapista="SELECT count(*) as contador,therapist_signature from tbl_signature_note WHERE id_note=".$_GET['id_note']." 
                                AND actor='THERAPIST'
                                ";
                                $resultado_firma_terapista = ejecutar($firma_terapista,$conexion);                                        
                                while ($row_firma_terapista=mysqli_fetch_array($resultado_firma_terapista)) 
                                {
                                    $valor_firma_terapista['contador']=$row_firma_terapista['contador'];
                                    $valor_firma_terapista['therapist_signature']=$row_firma_terapista['therapist_signature'];
                                }
                            ?>
                            <div class="row">
                                    <label class="col-lg-1">Therapist:</label>
                                    <div class="col-lg-offset-2 col-lg-2">
                                        <button type="button" class="btn btn-primary" onclick="signature('signatureNoteTherapist','firma_note_terapist');" <?php if($_SESSION['user_id']!=$therapist_type['user1']){?>disabled=""<?php }?>><i class="fa fa-check"></i>&nbsp;Signature</button>
                                    </div>
                                    <div class="col-lg-3">
                                        <?php 
                                            if(isset($_GET['id_note']) && $valor_firma_terapista['contador']>0){
                                        ?>
                                            <div id="signatureNote">
                                                <?php }else{?>
                                                     <div id="signatureNoteTherapist" style="display:none">
                                                <?php } ?>
                                                     <?php 
                                                        if(!isset($_GET['id_note'])){
                                                    ?>
                                                            <img src="../../../images/sign/signature.png" style="width: 100px">
                                                        <?php }elseif($note[0]['therapist_signed']==1){?>
                                                            <img src="../../../images/sign/<?=$valor_firma_terapista['therapist_signature']?>" style="width: 100px">
                                                        <?php }else{ ?>
                                                            <img src="../../../images/sign/signature.png" style="width: 100px">
                                                        <?php } ?>
                                                </div>
                                                <input type="hidden" name="firma_note_terapist" id="firma_note_terapist"/>
                                                <input type="hidden" name="ruta_firma_note_terapist" id="ruta_firma_note_terapist"/>
                                                <input type="hidden" name="latitud_firma_note_terapist" id="latitud_firma_note_terapist"/>
                                                <input type="hidden" name="longitud_firma_note_terapist" id="longitud_firma_note_terapist"/>
                                            </div>
                                    </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-lg-1">Attachment:</label>
                                <div class="col-lg-5">
                                    <input name="file-1[]" id="file-1[]" type="file" class="file" multiple="true" data-preview-file-type="any" data-allowed-file-extensions='["pdf"]'>
                                </div>
                            </div>
                            <hr/>
                            <div class="row card_footer">                                                                            
                                    <div class="col-sm-offset-4 col-sm-2 mg-top-sm">    
                                        <?php 
                                        if($therapist_type['user1']==$_SESSION['user_id'] || $therapist_type['user2']==$_SESSION['user_id']){


                                            if($therapist_type['assistant']==1 && $valor_firma['contador']>1){
                                        ?>
                        <button type="submit" disabled="" class="btn btn-success"><?php if(isset($_GET['note'])){echo "Edit";}else{echo "Save";}?></button>
                                        <?php }elseif($therapist_type['assistant']==0 && $valor_firma['contador']>0){ ?>
                        <button type="submit" disabled="" class="btn btn-success"><?php if(isset($_GET['note'])){echo "Edit";}else{echo "Save";}?></button>
                                        <?php }else{?>
                                            <button type="submit" class="btn btn-success"><?php if(isset($_GET['note'])){echo "Edit";}else{echo "Save";}?></button>
                                        <?php }
                                          }      ?>
                                    </div>                                                                            
                                    <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                                        <button type="button" class="btn btn-danger" onclick="cancelar(<?=$note[0]['patient_id']?>,<?=$note[0]['discipline_id']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                                    </div>
                                    <a href="pdf/note.php?id=<?=$_GET['id_note']?>" class="btn btn-default" target="_blank" style="cursor: pointer;" class="ruta"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
                            </div>
                            <hr>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="modalEditDocument" name = "modalEditDocument" tabindex="-1" role="dialog" aria-labelledby="modalEditDocument">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
                    </div>
                </div>

                <div class="modal-header bg-card-header">
                    <h4 class="modal-title"><div id="title"></div></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <div id="content_modal">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <div class="modal fade" id="modalCpt" name = "modalCpt" tabindex="-1" role="dialog" aria-labelledby="modalEditDocument" data-backdrop="static">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">                        
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
                    </div>
                </div>

                <div class="modal-header bg-card-header">
                    <h4 class="modal-title"><div id="title"><?php if(!isset($_GET['modificar_cpt'])){?>Añadir Cpt<?php }else{?><?php echo "Edit cpt #".$_GET['id'];}?></div></h4>
                </div> 

                <div class="alert alert-danger error-message error-message"></div>
                <?php if(!isset($_GET['modificar_cpt'])){?>
                <div class="modal-body">
                    <div class="panel-body">  
                        <div id="content_modal">
                          <div class="row">                                
                                <label class="col-lg-1 withoutPadding"> Cpt:</label>
                                <div class="col-lg-4">
                                    <select name='cpt_note' id='cpt_note' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from cpt where type='TX' AND DisciplineId=".$note[0]['discipline_id'];

                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {
                                                    
                                                    print("<option value='".$row["ID"]."'>".$row["cpt"]." </option>"); 
                                                    
                                                }

                                            ?>
                                    </select>
                                </div>
                              
                                <label class="col-lg-1">Units:</label>  
                                <div class="col-lg-4">
                                    <select id="unid_note" name="unid_note" style="width: 100px;">
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data[0]['units']==1){?>selected=""<?php }?>>1</option>
                                        <option value='2' <?php if($data[0]['units']==2){?>selected=""<?php }?>>2</option>
                                        <option value='3' <?php if($data[0]['units']==3){?>selected=""<?php }?>>3</option>
                                        <option value='4' <?php if($data[0]['units']==4){?>selected=""<?php }?>>4</option>
                                    </select>   
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding"> Location:</label>
                                <div class="col-lg-4">
                                    <select name='location_note' id='location_note' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from tbl_location_appoiments";

                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {                                                    
                                                    print("<option value='".$row["id"]."'>".$row["name"]." </option>"); 
                                                    
                                                }

                                            ?>
                                    </select>
                                </div>
                                <label class="col-lg-1">Duration:</label>
                                <div class="col-lg-4">
                                    <select name="duration_note" id="duration_note" class="form-control">
                                    
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                        <option value="60">60</option>
                                    </select>                                    
                                </div>
                             </div>                                                       
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-sm-1">From:</label>
                                <div class="col-sm-5">
                                    <!--<input type="text" name="start_note" id="start_note" class="form-control" placeholder="start"/>-->
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_hour" id="start_note_hour" class="form-control">
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_minute" id="start_note_minute" class="form-control">
                                        <option value="00">00</option>
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                        <option value="60">60</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_type" id="start_note_type" class="form-control">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>                                        
                                    </select>
                                    </font>
                                    </div>
                                </div>
                                </div>
                                 <div class="row">
                                <label class="col-lg-1">To:</label>
                                <div class="col-sm-5">
                                    <!--<input type="text" name="to_note" id="to_note" class="form-control" placeholder="End"/>-->
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_hour" id="to_note_hour" class="form-control">
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_minute" id="to_note_minute" class="form-control">
                                        <option value="00">00</option>
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                        <option value="60">60</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_type" id="to_note_type" class="form-control">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>                                        
                                    </select>
                                    </font>
                                    </div>
                                </div>
                                
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Diag.:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select name='diagnostic_id_note' id='diagnostic_id_note' class="">                                                				
                                            <?php
                                                $sql_diagnostic  = "Select DiagCodeId,concat(DiagCodeValue,', ',DiagCodeDescrip) as name from diagnosiscodes where TreatDiscipId=".$note[0]['discipline_id'];
                                                
                                                $resultado_diagnostic = ejecutar($sql_diagnostic,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_diagnostic)) 
                                                {                                                    
                                                    print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-offset-4 col-sm-2 mg-top-sm">                                        
                            <button type="submit" class="btn btn-success" onclick="guardar_cpt(<?=$_GET['id_note']?>);">Save</button>
                        </div>                                                                            
                        <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                            <button type="button" class="btn btn-danger" onclick="cancelar_note(<?=$_GET['id_note']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                        </div>
                    </div>
                </div>
                <?php
                }else{
                    $sql_cpt_edit="SELECT * FROM tbl_note_cpt_relation WHERE id_note_cpt_relation=".$_GET['id'];
                    $resultados_edit_cpt = ejecutar($sql_cpt_edit,$conexion);   
                    while($row_edit_cpt = mysqli_fetch_assoc($resultados_edit_cpt)) { 

                        $data_edit_cpt[]=$row_edit_cpt;

                    }
                    //para start
                    $variable_start=explode(":",$data_edit_cpt[0]['start']);
                    $start_hour=$variable_start[0];
                    $start_minute=$variable_start[1];
                    $start_type=$variable_start[2];
                    //para end
                    $variable_end=explode(":",$data_edit_cpt[0]['end']);
                    $end_hour=$variable_end[0];
                    $end_minute=$variable_end[1];
                    $end_type=$variable_end[2];
                    
                    
                ?>
                <div class="modal-body">
                    <div class="panel-body">  
                        <div id="content_modal">
                          <div class="row">                                
                                <label class="col-lg-1 withoutPadding"> Cpt:</label>
                                <div class="col-lg-4">
                                    <select name='cpt_note_edit' id='cpt_note_edit' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from cpt where type='TX' AND DisciplineId=".$note[0]['discipline_id'];

                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {
                                                    if($data_edit_cpt[0]['id_cpt']==$row["ID"]){
                                                        print("<option value='".$row["ID"]."' selected>".$row["cpt"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["ID"]."'>".$row["cpt"]." </option>"); 
                                                    }
                                                }

                                            ?>
                                    </select>
                                </div>
                              
                                <label class="col-lg-1">Units:</label>  
                                <div class="col-lg-4">
                                    <select id="unid_note_edit" name="unid_note_edit" style="width: 100px;">
                                        <option value=''>Select..</option>
                                        <option value='1' <?php if($data_edit_cpt[0]['units']==1){?>selected=""<?php }?>>1</option>
                                        <option value='2' <?php if($data_edit_cpt[0]['units']==2){?>selected=""<?php }?>>2</option>
                                        <option value='3' <?php if($data_edit_cpt[0]['units']==3){?>selected=""<?php }?>>3</option>
                                        <option value='4' <?php if($data_edit_cpt[0]['units']==4){?>selected=""<?php }?>>4</option>
                                    </select>   
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding"> Location:</label>
                                <div class="col-lg-4">
                                    <select name='location_note_edit' id='location_note_edit' class="form-control">                                                				
                                            <?php
                                                $sql_cpt  = "Select * from tbl_location_appoiments";

                                                $resultado_cpt = ejecutar($sql_cpt,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_cpt)) 
                                                {      
                                                    if($data_edit_cpt[0]['location']==$row["id"]){
                                                        print("<option value='".$row["id"]."' selected>".$row["name"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["id"]."'>".$row["name"]." </option>"); 
                                                    }
                                                }

                                            ?>
                                    </select>
                                </div>
                                <label class="col-lg-1">Duration:</label>
                                <div class="col-lg-4">
                                    <select name="duration_note_edit" id="duration_note_edit" class="form-control">
                                    
                                        <option value="15" <?php if($data_edit_cpt[0]['duration']==15){?>selected=""<?php }?>>15</option>
                                        <option value="30" <?php if($data_edit_cpt[0]['duration']==30){?>selected=""<?php }?>>30</option>
                                        <option value="45" <?php if($data_edit_cpt[0]['duration']==45){?>selected=""<?php }?>>45</option>
                                        <option value="60" <?php if($data_edit_cpt[0]['duration']==60){?>selected=""<?php }?>>60</option>
                                    </select>                                    
                                </div>
                             </div>                                                       
                            <div class="row" style="margin-top: 15px;">
                                <label class="col-sm-1">From:</label>
                                <div class="col-sm-5">
                                    <!--<input type="text" name="start_note" id="start_note" class="form-control" placeholder="start"/>-->
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_hour_edit" id="start_note_hour_edit" class="form-control">
                                        <option value="01" <?php if($start_hour==01){?>selected=""<?php } ?>>01</option>
                                        <option value="02" <?php if($start_hour==02){?>selected=""<?php } ?>>02</option>
                                        <option value="03" <?php if($start_hour==03){?>selected=""<?php } ?>>03</option>
                                        <option value="04" <?php if($start_hour==04){?>selected=""<?php } ?>>04</option>
                                        <option value="05" <?php if($start_hour==05){?>selected=""<?php } ?>>05</option>
                                        <option value="06" <?php if($start_hour==06){?>selected=""<?php } ?>>06</option>
                                        <option value="07" <?php if($start_hour==07){?>selected=""<?php } ?>>07</option>
                                        <option value="08" <?php if($start_hour==08){?>selected=""<?php } ?>>08</option>
                                        <option value="09" <?php if($start_hour==09){?>selected=""<?php } ?>>09</option>
                                        <option value="10" <?php if($start_hour==10){?>selected=""<?php } ?>>10</option>
                                        <option value="11" <?php if($start_hour==11){?>selected=""<?php } ?>>11</option>
                                        <option value="12" <?php if($start_hour==12){?>selected=""<?php } ?>>12</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_minute_edit" id="start_note_minute_edit" class="form-control">
                                        <option value="00" <?php if($start_minute==00){?>selected=""<?php } ?>>00</option>
                                        <option value="15" <?php if($start_minute==15){?>selected=""<?php } ?>>15</option>
                                        <option value="30" <?php if($start_minute==30){?>selected=""<?php } ?>>30</option>
                                        <option value="45" <?php if($start_minute==45){?>selected=""<?php } ?>>45</option>
                                        <option value="60" <?php if($start_minute==60){?>selected=""<?php } ?>>60</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="start_note_type_edit" id="start_note_type_edit" class="form-control">
                                        <option value="AM" <?php if($start_type=='AM'){?>selected=""<?php } ?>>AM</option>
                                        <option value="PM" <?php if($start_type=='PM'){?>selected=""<?php } ?>>PM</option>                                        
                                    </select>
                                    </font>
                                    </div>
                                </div>
                                </div>
                                 <div class="row">
                                <label class="col-lg-1">To:</label>
                                <div class="col-sm-5">
                                    <!--<input type="text" name="to_note" id="to_note" class="form-control" placeholder="End"/>-->
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_hour_edit" id="to_note_hour_edit" class="form-control">
                                        <option value="01" <?php if($end_hour==01){?>selected=""<?php } ?>>01</option>
                                        <option value="02" <?php if($end_hour==02){?>selected=""<?php } ?>>02</option>
                                        <option value="03" <?php if($end_hour==03){?>selected=""<?php } ?>>03</option>
                                        <option value="04" <?php if($end_hour==04){?>selected=""<?php } ?>>04</option>
                                        <option value="05" <?php if($end_hour==05){?>selected=""<?php } ?>>05</option>
                                        <option value="06" <?php if($end_hour==06){?>selected=""<?php } ?>>06</option>
                                        <option value="07" <?php if($end_hour==07){?>selected=""<?php } ?>>07</option>
                                        <option value="08" <?php if($end_hour==08){?>selected=""<?php } ?>>08</option>
                                        <option value="09" <?php if($end_hour==09){?>selected=""<?php } ?>>09</option>
                                        <option value="10" <?php if($end_hour==10){?>selected=""<?php } ?>>10</option>
                                        <option value="11" <?php if($end_hour==11){?>selected=""<?php } ?>>11</option>
                                        <option value="12" <?php if($end_hour==12){?>selected=""<?php } ?>>12</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_minute_edit" id="to_note_minute_edit" class="form-control">
                                        <option value="00" <?php if($end_minute==00){?>selected=""<?php } ?>>00</option>
                                        <option value="15" <?php if($end_minute==15){?>selected=""<?php } ?>>15</option>
                                        <option value="30" <?php if($end_minute==30){?>selected=""<?php } ?>>30</option>
                                        <option value="45" <?php if($end_minute==45){?>selected=""<?php } ?>>45</option>
                                        <option value="60" <?php if($end_minute==60){?>selected=""<?php } ?>>60</option>
                                    </select>
                                    </font>
                                    </div>
                                    <div class="col-sm-3">
                                    <font size="1">
                                    <select name="to_note_type_edit" id="to_note_type_edit" class="form-control">
                                        <option value="AM" <?php if($end_type=='AM'){?>selected=""<?php } ?>>AM</option>
                                        <option value="PM" <?php if($end_type=='PM'){?>selected=""<?php } ?>>PM</option>                                        
                                    </select>
                                    </font>
                                    </div>
                                </div>
                                
                            </div><br>
                            <div class="row">
                                <label class="col-lg-1 withoutPadding">Diag.:</label>
                                <div class="col-lg-4 withoutPadding">
                                    <select name='diagnostic_id_note_edit' id='diagnostic_id_note_edit' class="">                                                				
                                            <?php
                                                $sql_diagnostic  = "Select DiagCodeId,concat(DiagCodeValue,', ',DiagCodeDescrip) as name from diagnosiscodes where TreatDiscipId=".$note[0]['discipline_id'];
                                                
                                                $resultado_diagnostic = ejecutar($sql_diagnostic,$conexion);
                                                while ($row=mysqli_fetch_array($resultado_diagnostic)) 
                                                {          
                                                    if($data_edit_cpt[0]['id_diagnosis']==$row["DiagCodeId"]){
                                                        print("<option value='".$row["DiagCodeId"]."' selected>".$row["name"]." </option>"); 
                                                    }else{
                                                        print("<option value='".$row["DiagCodeId"]."'>".$row["name"]." </option>"); 
                                                    }
                                                }
                                                    
                                            ?>
                                    </select>                                     
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-offset-4 col-sm-2 mg-top-sm">                                        
                            <button type="submit" class="btn btn-success" onclick="edit_cpt(<?=$_GET['id_note']?>,<?=$_GET['id']?>);">Update</button>
                        </div>                                                                            
                        <div class="col-sm-offset-1 col-sm-2 mg-top-sm">                                        
                            <button type="button" class="btn btn-danger" onclick="cancelar_note(<?=$_GET['id_note']?>)"><i class="fa fa-times"></i>&nbsp;Cancel</button>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modalAddDocument" name = "modalAddDocument" tabindex="-1" role="dialog" aria-labelledby="modalAddDocument">
        <div class="modal-dialog sizeModal" role="document">
            <div class="modal-content">
                <div class="panel" style="margin-bottom: 0px;">                                                        
                    <div class="panel-title nav nav-tabs themed-bg-primary" style="background-color: #8f919a;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="col-md-3">
                            <div class="panel-heading nav nav-tabs">
                                <img class="navbar-avatar" src="../../../images/LOGO_1.png" alt="Therapy A id">
                            </div><!-- /.widget-user-image -->
                        </div>
                        <div class="col-md-offset-2 col-md-3" >
                            <h4 class="modal-title" style="margin-top: 45px;color: #FFF;"><div id="title_add"></div></h4>
                        </div>
                        
                    </div>
                </div>
                <div class="alert alert-danger error-message error-message"></div>
                
                <div class="modal-body">
                    <div class="panel-body">  
                        <div id="content_modal_add">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     <?php
        include "modal_summary.php";
        include "modal_discharge.php";
     ?>
    
    <input type="hidden" id="documentAdd" name="documentAdd" value="" readonly>

    
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


    
    <!-- /.Fin modales --> 
<script type="text/javascript">
   $(document).on('hidden.bs.modal', function (e) {
        $('.error-message').hide();                
    });
    
    $(document).on('show.bs.modal', function (e) {  
        
        if($("#documentAdd").val() == 'Prescription' ) {            
            autocompletar_radio('concat(DiagCodeValue,\', \',DiagCodeDescrip) as texto','DiagCodeId','diagnosiscodes d LEFT JOIN discipline di ON di.DisciplineId = d.TreatDiscipId','selector',null,null,'di.DisciplineID = \''+$("#discipline_id").val()+'\' AND DiagCodeValue <> \'\'','','diagnostic_id');
            get_patients($("#patients_id").val(),'First_name,Last_name,DOB','modalAddPrescription');
            $("#patients_id_hidden").val($("#patients_id").val())
            $("#discipline_id_hidden").val($("#discipline_description").val())            
        }
        
        if ( $("#documentAdd").val() == 'Evaluation' ) {  
            if($("#action").val()!= 'edit'){
                $("#prescription_id_eval_hidden").val($("#prescSelect").val());
                $("#patients_name_eval_modal").html($("#patieNameSelect").val());
                $("#patients_eval_hidden").val($("#patieNameSelect").val());             
                //$("#patients_id_eval_hidden").val($("#patieSelect").val());
                $("#pat_id_eval_modal").html($("#patieLongSelect").val());
                $("#address_eval_modal").html($("#patieAddressSelect").val());
                $("#phone_eval_modal").html($("#patiePhoneSelect").val());            
                $("#discipline_id_eval_hidden").val($("#disciSelect").val());
                $("#discipline_eval_modal").html($("#disciSelect").val());                        
                $("#company_id_eval_hidden").val($("#compaSelect").val()); 
                $("#company_eval_modal").html($("#compaSelect").val());                        
                $("#diagnostic_eval_hidden").val($("#diagnSelect").val());
                $("#diagnostic_eval_modal").html($("#diagnSelect").val());
                autocompletar_radio('name as texto','id','tbl_templates','selector',null,null,null,null,'template_id');   

            }else{
                $.post(
                "../gestion_clinic/data_evaluation.php?id_document="+$("#id_document").val(),
                function (resultado_controlador) {
                    $("#prescription_id_eval_hidden").val(resultado_controlador.evaluation.id_prescription);
                    $("#patients_name_eval_modal").html(resultado_controlador.evaluation.name);
                    $("#patients_eval_hidden").val(resultado_controlador.evaluation.name);
//                    $("#patients_id_eval_hidden").val(resultado_controlador.evaluation.patient_id);
                    $("#pat_id_eval_modal").html(resultado_controlador.evaluation.Pat_id);
                    $("#address_eval_modal").html(resultado_controlador.evaluation.Address);
                    $("#phone_eval_modal").html(resultado_controlador.evaluation.Phone);  
                    $("#discipline_id_eval_hidden").val(resultado_controlador.evaluation.discipline_id);
                    $("#discipline_eval_modal").html(resultado_controlador.evaluation.discipline_id); 
                    $("#company_id_eval_hidden").val(resultado_controlador.evaluation.company); 
                    $("#company_eval_modal").html(resultado_controlador.evaluation.company);  
                    $("#diagnostic_eval_hidden").val(resultado_controlador.evaluation.diagnostic);
                    $("#diagnostic_eval_modal").html(resultado_controlador.evaluation.diagnostic); 
                    
                    $("#unid_of_eval").val(resultado_controlador.evaluation.units).change();
                    $("#min_of_eval").val(resultado_controlador.evaluation.minutes).change();
                    $("#date_of_eval").val(resultado_controlador.evaluation.created);
                    $("#from_eval").val(resultado_controlador.evaluation.from);
                    $("#to_eval").val(resultado_controlador.evaluation.to);
                    $("#id_eval").val(resultado_controlador.evaluation.id_evaluation);
                    $("#signature").show();
                    if(resultado_controlador.evaluation.units.id_template != null){
                        $("#templateCkEditor2").attr('checked', 'checked');
                    }else{
                        $("#editor").val(resultado_controlador.evaluation.ckeditor);
                    }
                    if(resultado_controlador.evaluation.md_signed == 1){                       
                        $("#md_signed").attr('checked', 'checked');
                        $("#date_signed").show();
                        $("#attachment").show();
                        $("#date_of_signed").val(resultado_controlador.evaluation.date_signed_e);
                        $("#current_document").show();
                        $("#route_document").html('<a href="../../../'+resultado_controlador.evaluation.route_document+'" target="_blank">'+resultado_controlador.evaluation.route_document+'</a>'); 
                        
                    }
                },
                "json" 
            );
                //get_data_evaluations($("#id_document").val())
            }
        }        
        
        
        if($("#poc_id_note_hidden").length){            
            $("#poc_id_note_hidden").val($("#pocSelect").val());
            $("#patients_name_note_modal").html($("#pocPatieNameSelect").val());
           
            $("#patients_note_hidden").val($("#pocPatieNameSelect").val());            
            $("#patients_id_note_hidden").val($("#pocPatieSelect").val());
            $("#discipline_id_note_hidden").val($("#pocDisciSelect").val());
            $("#discipline_note_modal").html($("#pocDisciSelect").val());                        
        }
        
        
        
        
        if ( $("#documentAdd").val() == 'Discharge' ) {             
                if($("#action").val()!= 'edit'){
                    $.post(
                    "../gestion_clinic/header_summary_discharge.php?id_evaluations="+$("#evalSelect").val(),
                    function (resultado_controlador) {
                        $("#evaluation_id_discharge_hidden").val(resultado_controlador.summary.id_evaluations);
                        $("#patients_name_discharge_modal").html(resultado_controlador.summary.name);
                        $("#patients_discharge_hidden").val(resultado_controlador.summary.name);             
                        $("#patients_id_discharge_hidden").val(resultado_controlador.summary.patient_id);
                        $("#pat_id_discharge_modal").html(resultado_controlador.summary.Pat_id);
                        $("#patients_dob_discharge_modal").html(resultado_controlador.summary.DOB);                
        //                $("#address_discharge_modal").html($("#evalPatieAddressSelect").val());
                        $("#phone_discharge_modal").html(resultado_controlador.summary.Phone); 
                        $("#doctor_discharge_modal").html(resultado_controlador.summary.PCP); 
                        $("#doctor_npi_discharge_modal").html(resultado_controlador.summary.PCP_NPI); 
                        $("#company_id_discharge_hidden").val(resultado_controlador.summary.company);
                        $("#company_discharge_modal").html(resultado_controlador.summary.company);
                        $("#company_address_discharge_modal").html(resultado_controlador.summary.facility_address);
                        $("#company_phone_discharge_modal").html(resultado_controlador.summary.tin_number);

                        $("#discipline_id_discharge_hidden").val(resultado_controlador.summary.discipline_id);
                        $("#discipline_discharge_modal").html(resultado_controlador.summary.diname);                                                
                        $("#diagnostic_discharge_hidden").val(resultado_controlador.summary.diagnostic);
                        $("#diagnostic_discharge_modal").html(resultado_controlador.summary.diagnostic);                                               
                    },
                    "json" 
                );
                
                autocompletar_radio('t.name as texto','id','tbl_templates t LEFT JOIN discipline di ON di.DisciplineId = t.discipline_id','selector',null,null,'di.Name = \''+$("#evalDisciSelect").val()+'\' AND t.type_document_id = 3','','template_id');
            }
        }
        
        if ( $("#documentAdd").val() == 'Summary' ) {  
            if($("#action").val()!= 'edit'){
                $.post(
                    "../gestion_clinic/header_summary_discharge.php?id_evaluations="+$("#evalSelect").val(),
                    function (resultado_controlador) {
                        $("#evaluation_id_summary_hidden").val(resultado_controlador.summary.id_evaluations);
                        $("#patients_name_summary_modal").html(resultado_controlador.summary.name);
                        $("#patients_summary_hidden").val(resultado_controlador.summary.name);             
                        $("#patients_id_summary_hidden").val(resultado_controlador.summary.patient_id);
                        $("#pat_id_summary_modal").html(resultado_controlador.summary.Pat_id);
                        $("#address_summary_modal").html(resultado_controlador.summary.Address);
                        $("#phone_summary_modal").html(resultado_controlador.summary.Phone);  
                        $("#pdob_summary_modal").html(resultado_controlador.summary.pdob);            
                       
                        $("#company_id_summary_hidden").val(resultado_controlador.summary.company);
                        $("#company_summary_modal").html(resultado_controlador.summary.company);
                        $("#company_summary_name").html(resultado_controlador.summary.cname);
                        $("#company_summary_address").html(resultado_controlador.summary.caddress);
                        $("#company_summary_city").html(resultado_controlador.summary.ccity);
                        $("#company_summary_phone").html(resultado_controlador.summary.cphone);
                        $("#company_summary_fax").html(resultado_controlador.summary.cfax);

                        $("#pcp_summary_modal").html(resultado_controlador.summary.pcp);


                        $("#discipline_id_summary_hidden").val(resultado_controlador.summary.discipline_id);
                        $("#discipline_summary_modal").html(resultado_controlador.summary.diname);                                                
                        $("#diagnostic_summary_hidden").val(resultado_controlador.summary.diagnostic);
                        $("#diagnostic_name_summary_modal").html(resultado_controlador.summary.diagnostic_name);                                               
                    },
                    "json" 
                );
                autocompletar_radio('t.name as texto','id','tbl_templates t LEFT JOIN discipline di ON di.DisciplineId = t.discipline_id','selector',null,null,'di.Name = \''+$("#evalDisciSelect").val()+'\' AND t.type_document_id = 3','','template_id');
            }else{
                $.post(
                    "../gestion_clinic/data_summary.php?id_document="+$("#id_document").val(),
                    function (resultado_controlador) {
                        $("#evaluation_id_summary_hidden").val(resultado_controlador.summary.id_evaluations);
                        $("#id_summary_hidden").val(resultado_controlador.summary.id_summary);                        
                        $("#patients_name_summary_modal").html(resultado_controlador.summary.name);
                        $("#patients_summary_hidden").val(resultado_controlador.summary.name);             
                        $("#patients_id_summary_hidden").val(resultado_controlador.summary.patient_id);
                        $("#pat_id_summary_modal").html(resultado_controlador.summary.Pat_id);
                        $("#address_summary_modal").html(resultado_controlador.summary.Address);
                        $("#phone_summary_modal").html(resultado_controlador.summary.Phone);
                        $("#pdob_summary_modal").html(resultado_controlador.summary.pdob);            
                       
                        $("#company_id_summary_hidden").val(resultado_controlador.summary.company);
                        $("#company_summary_modal").html(resultado_controlador.summary.company);
                        $("#company_summary_name").html(resultado_controlador.summary.cname);
                        $("#company_summary_address").html(resultado_controlador.summary.caddress);
                        $("#company_summary_city").html(resultado_controlador.summary.ccity);
                        $("#company_summary_phone").html(resultado_controlador.summary.cphone);
                        $("#company_summary_fax").html(resultado_controlador.summary.cfax);

                        $("#npi_summary_modal").html(resultado_controlador.summary.npi_therapist);


                        $("#discipline_id_summary_hidden").val(resultado_controlador.summary.discipline_id);
                        $("#discipline_summary_modal").html(resultado_controlador.summary.discipline_id);                                                
                        $("#diagnostic_summary_hidden").val(resultado_controlador.summary.diagnostic);
                        $("#diagnostic_summary_modal").html(resultado_controlador.summary.diagnostic);
                        $("#diagnostic_name_summary_modal").html(resultado_controlador.summary.diagnostic_name);    
                        
                        $("#from_summary").val(resultado_controlador.summary.start_date);
                        $("#to_summary").val(resultado_controlador.summary.end_date);
                        $("#date_of_summary").val(resultado_controlador.summary.created_summary);
                        
                        if(resultado_controlador.summary.signed == 1){
                            $("#md_signed_summary").attr('checked', 'checked');
                            $("#date_signed_summary").show();
                            $("#date_of_signed_summary").val(resultado_controlador.summary.signed_date);
                            $("#current_document_summary").show();
                            $("#route_document_summary").html('<a href="../../../'+resultado_controlador.summary.route_document+'" target="_blank">'+resultado_controlador.summary.route_document+'</a>');
                            $("#attachment_summary").show();
                            
                        }
                    },
                    "json" 
                );
            }
        } 
        if($("#content_modal").length){            
            if($("#documentAdd").val()== 'Prescriptions'){
                $("#title").html('Edit Prescription');
                $("#content_modal").load("../gestion_clinic/form_edit_prescription.php?id_document="+$("#id_document").val());                
            }
            if($("#documentAdd").val()== 'POC'){
                $("#title").html('Edit POC');
                $("#content_modal").load("../gestion_clinic/form_edit_poc.php?id_document="+$("#id_document").val());                
            }  
            if($("#document").val()== 'Note'){
                $("#title").html('Edit POC');
                $("#content_modal").load("../gestion_clinic/form_edit_note.php?id_document="+$("#id_document").val());                
            }
        }
        $('.error-message').hide();
    });
</script>
<script>  
$(".panel-left").resizable({
   handleSelector: ".splitter",
   resizeHeight: false
 });

 $(".panel-top").resizable({
   handleSelector: ".splitter-horizontal",
   resizeWidth: false
 });    
$(document).ready(function() {
        //result_documentation(''); 
        
        
        $('#dateSigned').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	        
        $('#dateSigned').prop('readonly', true);
        
        $('#from').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	        
        $('#from').prop('readonly', true);
        $('#to').datepicker({setDate: new Date(),dateFormat: 'yy-mm-dd'});	
        $('#to').prop('readonly', true);
        
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
        
        LoadSelect2ScriptExt(function(){
            $('#patients_id').select2();
            $('#discipline_id').select2();            
            $('#companies_id').select2();
            $('#diagnostic_id').select2();
            $('#physician_id').select2();  
            $('#template_id').select2();
            $('#template_id_summary').select2();     
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
        autocompletar_radio('name as texto','id','tbl_templates','selector',null,null,null,null,'template_id');   
       
        var poc='<?=$_GET['poc']?>';
        if(poc==='si'){
            autocompletar_radio('name as texto','id','tbl_templates','selector',null,null,null,null,'template_id_Poc');   
        }
        var id_eval='<?=$_GET['id_eval']?>';
        if(id_eval!=''){
            autocompletar_radio('name as texto','id','tbl_templates','selector',null,null,null,null,'template_amendment_id');  
        }
        
});
</script>
</html>