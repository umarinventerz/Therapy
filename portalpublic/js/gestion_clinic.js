/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var campo;

function loadStringGoals(){
    var cadena = '';
    $("#goalsString").val(''); 
    
    $('input:checkbox:checked').map(function() {         
        if($(this).attr("name").substr(0,15) == 'goal_library_id'){
            if($(this).is(":checked")){
                if(cadena != ''){
                    cadena =  cadena + "," + $(this).val();
                }else{
                    cadena = $(this).val();
                }
            }
        }
    });

    $("#goalsString").val(cadena);
}

function selectetByTerm(){
    var term = '';
    $('input:radio:checked').map(function() { 
        if($(this).attr("name") == 'term'){                
            term = $(this).val();
        }
    });
    var discipline_id;
    if($("#documentDiscipline").val() == ''){
        discipline_id = $("#discipline_id").val();
    }else{
        discipline_id = $("#documentDiscipline").val();
    }
    $("#goaldsPoc").load("../../../mvc/controlador/gestion_clinic/getGoladsByDiscipline.php?discipline_id="+$("#documentDiscipline").val()+"&term="+term+"&id_careplans_edit="+discipline_id);                
}

function addGoalds(){
    var term = '';
    $('input:radio:checked').map(function() { 
        if($(this).attr("name") == 'term'){                
            term = $(this).val();
        }
    });
    if(term == ''){
        swal({
            title: "<h4><b>Error, Debe selecionar un term</h4>",          
            type: "error",              
            showCancelButton: false,              
            closeOnConfirm: true,
            showLoaderOnConfirm: false,
          }); 
    }else{
        if($("#goalsString").val() == ''){
            swal({
                title: "<h4><b>Error, Debe selecionar alguna libreria</h4>",          
                type: "error",              
                showCancelButton: false,              
                closeOnConfirm: true,
                showLoaderOnConfirm: false,
              }); 
        }else{
            var div = 'goaldsPocAdd'+term;
            $("#"+div).load("../../../mvc/controlador/gestion_clinic/addGoladsSelected.php?cadena="+$("#goalsString").val()+"&term="+term);
        }
    }
}
function eliminarDiv(div){
    $("#"+div).remove();
}
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
            var components_select=guardarComponentes('newTemplateDischarge');
            var html_select=guardarHtml('newTemplateDischarge');   
            var editor= CKEDITOR.instances['editorDischarge'].getData();
            data.append('componentes',components_select);
            data.append('html_select',html_select);
            data.append('ckeditorDischarge',editor);        
            data.append('campos_formulario',campos_formulario);                   
            $.ajax({
                url: url,
                type: "POST",
                data: data, 
                dataType: "json",
                processData: false,
                contentType: false,                                 
                success : function(resultado_controlador_archivo){                                                     
                   if(resultado_controlador_archivo.mensaje == 'ok'){                
                        swal({
                          type: 'success',                          
                          html: '<h3><b>Record saved successfully</b></h3>',
                          timer: 3000    
                        }
                        ); 
                        $('#formDischarge')[0].reset();
                        $("#modalAddDischarge").modal('hide');
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
          $('#fileSummary').each(function(){              
              data.append(this.name,$(this)[0].files[0]);                  
          });
          var url;
          if($('#action').val() == 'edit'){
              url = "../../controlador/gestion_clinic/edit_summary.php";
          }else{
              url = "../../controlador/gestion_clinic/insert_summary.php";
          }
          var components_select=guardarComponentes('newTemplateSummary');
          var html_select=guardarHtml('newTemplateSummary');  
          var editor= CKEDITOR.instances['editorSummary'].getData();
         
          data.append('componentes',components_select);
          data.append('html_select',html_select);
          data.append('campos_formulario',campos_formulario);     
          data.append('ckeditorSummary',editor)
          $.ajax({
              url: url,
              type: "POST",
              data: data, 
              dataType: "json",
              processData: false,
              contentType: false,                                 
              success : function(resultado_controlador_archivo){               
                  //alert(resultado_controlador_archivo);                 
                 if(resultado_controlador_archivo.mensaje == 'ok'){              
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
          var components_select=guardarComponentesPoc();
          var html_select=guardarHtmlPoc();
          var editor= CKEDITOR.instances['editorPoc'].getData();          
          data.append('campos_formulario',campos_formulario);
          data.append('editor_poc',editor);
          data.append('componentes',components_select);
          data.append('html_select',html_select);
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
      
    function validar_form_eval(actionss){
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

          var campos_formulario = $("#formEvaluation").serialize();
          var data = new FormData(form);
          $('#fileEvaluation').each(function(){
              data.append(this.name,$(this)[0].files[0]);
          });

       //   if($("#action").val()== 'edit'){
            //  variable = "edit"
      //    }

      //    var editor= CKEDITOR.instances['editor'].getData();
       //   var editor_amendment= CKEDITOR.instances['editor_amendment'].getData();
        //  var components_select=guardarComponentes('newTemplate');
        //  var html_select=guardarHtml('newTemplate');
        //  var components_select_amend=guardarComponentesAmend();
    //      var html_select_amend=guardarHtmlAmend();
          data.append('campos_formulario',campos_formulario);
   //       data.append('editor_evaluaciones',editor);
     //     data.append('componentes',components_select);
     //     data.append('html_select',html_select);
    //      data.append('componentes_amend',components_select_amend);
  //        data.append('html_select_amend',html_select_amend);
   //       data.append('editor_amendment_evaluaciones',editor_amendment);
          if(actionss==='insert'){

              $.ajax({
                  url: "../../controlador/evaluations/insert_evaluations.php",
                  type: "POST",
                  data: data,
                  processData: false,
                  dataType: "json",
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
          }if(actionss==='edit'){

              $.ajax({
                  url: "../../controlador/evaluations/edit_evaluations.php",
                  type: "POST",
                  async:false,
                  dataType: "json",
                  data: data,
                  processData: false,
                  contentType: false,

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

                      }
                      else if(data.mensaje ==='duplicated'){
                          swal({
                              title: "<h4><b>There is another Evaluation with that Status, Check Documentation of the Patient</h4>",
                              type: "error",
                              showCancelButton: false,
                              closeOnConfirm: true,
                              showLoaderOnConfirm: false,
                          });
                      }
                      else{
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
                  nombres_campos += '* The field Diagnostic is form hidde required';
                  error = 1;
      }
      if($('#to_hidden').val() == '' && error == 0){
                  nombres_campos += '* The field Diagnostic is to hidde required';
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

            if($("#discipline_id").val()==''){
                swal({
                    title: "<h3><b>Message<b></h3>",
                    type: "info",
                    html: "<h4>Debe Seleccionar una disciplina" +
                    "</h4>",
                    showCancelButton: false,
                    animation: "slide-from-top",
                    closeOnConfirm: true,
                    showLoaderOnConfirm: false,
                });

            }

            else {
                  get_patients($("#patients_id").val(),'First_name,Last_name,DOB','');
                var datos = 'patient_id='+$.trim($("#patients_id").val());
                datos += '&discipline_id='+$("#discipline_id").val();
                datos += '&from='+$("#from").val();
                datos += '&to='+$("#to").val();
                result_documentation(datos);
                result_autorizacion($("#patients_id").val());
            }


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
    
    function openModal(modalId,field,title,modal,document){
            
        $("#action").val('insert');
        $("#documentAdd").val('');
        $("#documentAdd").val(document);
        if($("#"+field).val() == ''){
            swal({
                title: "<h3><b>Message<b></h3>",          
                type: "info",
                html: "<h4>Debe Selecciona "+title+"..</h4>",
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
                    var documentDiscipline=  $("#discipline_id").val();
                      window.location="../../../mvc/vista/gestion_clinic?poc=si&id_eval="+eval+"&documentDiscipline="+$("#discipline_id").val();

                }

                if(modal==='eval'){



                    var prescriptions_id_select=  $("#prescSelect").val();



                    $('#modal').load("../../../mvc/vista/gestion_clinic/modales/modalAddEvaluation.php?prescriptions_id_select="+prescriptions_id_select+"",function(){
                        $("#"+modalId).modal({show:true});
                        //$('#myModal').modal({show:true});
                    });

                 //   var prescriptions_id_select=  $("#prescSelect").val();

               //     window.location="../../../mvc/vista/gestion_clinic?eval_insert=si&modal_eval_jorgito=si&id_prescr="+prescriptions_id_select+"&documentDiscipline="+$("#discipline_id").val();


                    // $("#modal").load("../../../mvc/vista/gestion_clinic/modales/modalAddEvaluation.php?prescriptions_id_select="+prescriptions_id_select+"");
               //     window.location="../../../mvc/vista/gestion_clinic?prescriptions_id_select="+prescriptions_id_select+"";
                   // $("#modal").modal('show');
                }

                if(modal==='note'){
                    var poc=  $("#pocSelect").val();
                    window.location="../../../mvc/vista/gestion_clinic?note_insert=si&modal_note_jorgito=si&id_poc="+poc+"&documentDiscipline="+$("#discipline_id").val();
                  //$("#"+modalId).modal('show');
                }

                else{
                   $("#"+modalId).modal('show');
                }
            }
        }            
    }
    
    function modificarCkeditor(elemento,ckeditor,disciplina,document){
        
        if(elemento.value != ''){
            if(ckeditor==='editor'){
                var editor_eval='editor';
            }
            if(ckeditor==='editor_amendment'){                
                editor_eval='editor_amendment';
            }
            if(ckeditor==='editor_poc'){
                editor_eval='editor_poc';
            }
            if(ckeditor==='editor_Summary'){
                editor_eval='editor_Summary';
            }
            if(ckeditor==='editor_Discharge'){
                editor_eval='editor_Discharge';
            }
            $.post(
                "../../controlador/gestion_clinic/getContentTemplate.php",
                "template_id="+elemento.value+"&document="+document+"&discipline="+disciplina+"&editor_eval="+editor_eval,
                function (resultado_controlador){
                    if(ckeditor==='editor'){
                        $("#template_panel").show();
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
                        
                    }
                    if(ckeditor==='editor_amendment'){
                        $("#template_panel_amendment").show();
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
                    }
                    if(ckeditor==='editor_poc'){
                        $("#template_panel_poc").show();
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
                    }
                    if(ckeditor==='editor_Summary'){
                        $("#template_panel_summary").show();
                        $("#components_id_summary").show();                                       
                        $("#components_id_summary").html(resultado_controlador.contentComponents);
                        $("#newTemplateSummary").html(resultado_controlador.contentTemplate);
                        for(i=0;i<=resultado_controlador.total_category;i++){
                            $('#category_id'+i).DataTable({
                                pageLength: 5,                    
                                "scrollX": true,
                                "pagingType": "full_numbers",
                                "lengthMenu": [ 5, 25, 50, 75, 100 ],
                            });
                        }
                    }
                    if(ckeditor==='editor_Discharge'){
                        $("#template_panel_discharge").show();
                        $("#components_id_discharge").show();                                       
                        $("#components_id_discharge").html(resultado_controlador.contentComponents);
                        $("#newTemplateDischarge").html(resultado_controlador.contentTemplate);
                        for(i=0;i<=resultado_controlador.total_category;i++){
                            $('#category_id'+i).DataTable({
                                pageLength: 5,                    
                                "scrollX": true,
                                "pagingType": "full_numbers",
                                "lengthMenu": [ 5, 25, 50, 75, 100 ],
                            });
                        }
                    }
                    $("#titulos").html("<h2><div align='center'><u>Components Select</u></div></h2>");
                    //CKEDITOR.instances[ckeditor].setData(resultado_controlador.contentTemplate);
                    CKEDITOR.instances[ckeditor].setReadOnly(false);
                },
                "json" 
            );
        }else{
            CKEDITOR.instances[ckeditor].setData("");
            //$("#components_id").hide();                                       
            //$("#components_id").html("");
        }                
        
    }
    function llenarDiv(divClonar,check,editor){ 
        if($("#"+check.id).is(':checked')) {                
            $('#cadenaComponents').val($('#cadenaComponents').val()+$("#"+check.id).val()+',');
            if(editor==='editor'){
                $( "#"+divClonar ).clone().prop('id', 'clone'+divClonar ).appendTo("#newTemplate");
            }
            if(editor==='editor_poc'){
                $( "#"+divClonar ).clone().prop('id', 'clone'+divClonar ).appendTo("#newTemplatePoc");
            }
            if(editor==='editor_amend'){                
                $( "#"+divClonar ).clone().prop('id', 'clone'+divClonar ).appendTo("#newTemplateAmendment");
            }
            if(editor==='editor_Summary'){                
                $( "#"+divClonar ).clone().prop('id', 'clone'+divClonar ).appendTo("#newTemplateSummary");
            }
            if(editor==='editor_Discharge'){                
                $( "#"+divClonar ).clone().prop('id', 'clone'+divClonar ).appendTo("#newTemplateDischarge");
            }
        }else{           
            var valor = $('#cadenaComponents').val().replace($("#"+check.id).val()+',','');                
            $('#cadenaComponents').val(valor);
            $( "#clone"+divClonar ).remove();
        }
        var campos=guardarComponentes();        
        if(campos.length>=1){
            $("#titulos").html('');
            $("#titulos").html("<h2><div align='center'><u>Components Select</u></div></h2>");
        }else{
           $("#titulos").html(''); 
        }
        
    }
    function guardarComponentes(template){
        var array=[];
        $('#'+template).children().each(function (i){
            array[i]=this.id
            
        });
        return array; 
    }
    function guardarHtml(template){
        var array=[];       
        $('#'+template).children().each(function (i){
            var id;            
            $("#"+this.id+" :input").each(function(e){                
                if(this.type==='radio'){
                    id = this.name;                    
                     if($('input[name='+id+']').is(':checked')){  
                        $('input[name='+id+']').attr('checked',true); 
                    } 
                }
                if(this.type==='text'){
                    id = this.name;
                    $('input[name='+id+']').attr('value',this.value);
                }
                if(this.type==='checkbox'){
                    id = this.name;
                     if($('input[name='+id+']').is(':checked')){  
                        $('input[name='+id+']').attr('checked',true); 
                    }else{
                        $('input[name='+id+']').removeAttr('checked');
                    } 
                }
            });
            array[i]=$("#"+this.id+"").html();

        });
        return array; 
    }
    function guardarComponentesAmend(){
        var array=[];
        $('#newTemplateAmendment').children().each(function (i){
            array[i]=this.id
            
        });
        return array; 
    }
    function guardarHtmlAmend(){
        var array=[];       
        $('#newTemplateAmendment').children().each(function (i){
            var id;            
            $("#"+this.id+" :input").each(function(e){                
                if(this.type==='radio'){
                    id = this.name;                    
                     if($('input[name='+id+']').is(':checked')){  
                        $('input[name='+id+']').attr('checked',true); 
                    } 
                }
                if(this.type==='text'){
                    id = this.name;
                    $('input[name='+id+']').attr('value',this.value);
                }
                if(this.type==='checkbox'){
                    id = this.name;
                     if($('input[name='+id+']').is(':checked')){  
                        $('input[name='+id+']').attr('checked',true); 
                    }else{
                        $('input[name='+id+']').removeAttr('checked');
                    } 
                }
            });
            array[i]=$("#"+this.id+"").html();

        });
        return array; 
    }
    function guardarComponentesPoc(){
        var array=[];
        $('#newTemplatePoc').children().each(function (i){
            array[i]=this.id
            
        });
        return array; 
    }
    function guardarHtmlPoc(){
        var array=[];       
        $('#newTemplatePoc').children().each(function (i){
            var id;            
            $("#"+this.id+" :input").each(function(e){                
                if(this.type==='radio'){
                    id = this.name;                    
                     if($('input[name='+id+']').is(':checked')){  
                        $('input[name='+id+']').attr('checked',true); 
                    } 
                }
                if(this.type==='text'){
                    id = this.name;
                    $('input[name='+id+']').attr('value',this.value);
                }
                if(this.type==='checkbox'){
                    id = this.name;
                     if($('input[name='+id+']').is(':checked')){  
                        $('input[name='+id+']').attr('checked',true); 
                    }else{
                        $('input[name='+id+']').removeAttr('checked');
                    } 
                }
            });
            array[i]=$("#"+this.id+"").html();

        });
        return array; 
    }
    //guardar html para summary 
    function mostrarCkEditor(template,ckeditor,amendment){        
        $("#"+template).hide('slow');
        if(ckeditor==='editor'){
            $("#template_panel").hide('slow');
            $("#components_id").hide('slow');
            $("#cked").show();
        }
        if(ckeditor==='editor_amendment'){
            $("#template_panel_amendment").hide('slow');
            $("#components_id_amendment").hide('slow');
            $("#cked_amendment").show();
        }
        if(ckeditor==='templatePoc'){
            $("#template_panel_poc").hide('slow');
            $("#components_id_poc").hide('slow');
            $("#cke_poc").show();
        }
        if(ckeditor==='editorSummary'){
            $("#template_panel_summary").hide('slow');
            $("#components_id_summary").hide('slow');
            $("#divCkeditorSummary").show();
        }
        if(ckeditor==='templateDischarge'){
            $("#template_panel_discharge").hide('slow');
            $("#components_id_discharge").hide('slow');
            $("#divCkeditorDischarge").show();
        }
        $.post(
            "../../controlador/gestion_clinic/getContentCkeditor.php",
            "id_eval="+$('#id_eval').val()+"&amendment="+amendment,
            function (resultado_controlador) {
                CKEDITOR.instances[ckeditor].setData(resultado_controlador.ckeditor)
                CKEDITOR.instances[ckeditor].setReadOnly(false);
            },
            "json" 
        );

    }

    function mostrarTemplate(template,ckeditor){        
        if(ckeditor==='editor'){
            $("#cked").hide();
        }
        if(ckeditor==='editor_amendment'){
            $("#cked_amendment").hide();
        }  
        if(template==='templateSummary'){
            $("#divCkeditorSummary").hide();            
            $.post(
                "../gestion_clinic/select_summary_discharge.php?modal=summary&discipline="+$("#discipline_id_summary_hidden").val(),
                function (resultado_controlador) {                    
                    $("#mostrar_select").html(resultado_controlador.summary);
                    $('#template_id_Summ').select2(); 
                },
                "json" 
            ); 
            
        }
        if(ckeditor==='templateDischarge'){
            $("#divCkeditorDischarge").hide();            
            $.post(
                "../gestion_clinic/select_summary_discharge.php?modal=discharge&discipline="+$("#discipline_id_discharge_hidden").val(),
                function (resultado_controlador) {                    
                    $("#mostrar_select_discharge").html(resultado_controlador.summary);
                    $('#template_id_discharge').select2(); 
                },
                "json" 
            );
        }
        $("#"+template).show('slow');
        $("#cke_poc").hide('slow');
        
        CKEDITOR.instances[ckeditor].setData('');
        CKEDITOR.instances[ckeditor].setReadOnly(false);
    }


    function signature(div,field){

       swal({
            title: "Firmar evaluacion",
            text: "Esta seguro que desea firmar la Evaluation... Esta accion no podra ser revertida",                          
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Signature",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(function(isConfirm) {
            if (isConfirm === true) {        
                $("#"+div).show();
                $("#"+field).val('1');
                $("#ruta_"+field).val("signature.png");
                campo=field;
                getLocation();
                /* Bloquear campos*/
                // CKEDITOR.instances['editor'].setReadOnly(true);
                // $('#templateCkEditor1').prop('disabled', 'disabled');
                // $('#templateCkEditor2').prop('disabled', 'disabled');
                // $('#unid_of_eval').prop('disabled', 'disabled');
                // $('#min_of_eval').prop('disabled', 'disabled');
                // $('#status_eval').prop('disabled', 'disabled');
                // $('#cpt').prop('disabled', 'disabled');
                // $('#diagnostic_id_eval').prop('disabled', 'disabled');
                // $('#company_id').prop('disabled', 'disabled');
                /* Fin de bloquear campos*/
            }
        });
        
        
    }

    function loadCptH(value){
        $('#cpt_h').val(value);
    }
    
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


    function mdSignature(element,date,attachment,attachment_register){
        if($(element).prop('checked')){
            $("#"+attachment).show();
            $("#"+attachment_register).show();
            $("#"+date).show();
        }else{
            $("#"+attachment).hide();
            $("#"+attachment_register).hide();
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

    function cancelar_goal(id,discipline){    
        window.location="../../../mvc/vista/gestion_clinic?poc=si&id_poc="+id+"&documentDiscipline="+discipline;
    }
    function agregar_goals(){        
          $("#modalGoals").modal('show');  
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
    
    function guardar_goal(poc_get,discipline){
        
        var goal_text =$("#goal_text").val();
        var goal_area =$("#goal_area").val();
        var goal_discipline_id=$("#goal_discipline_id").val();
        var goal_term_id=$("#goal_term").val();
        
        if(goal_text===''){
            alert("Please, inicate the goal");
            return false;
        }
        if(goal_area===''){
            alert("Please, inicate the goal area");
            return false;
        }
        if(goal_discipline_id===''){
            alert("Please, inicate the discipline");
            return false;
        }
        if(goal_term_id===''){
            alert("Please, inicate the term");
            return false;
        }
        $.ajax({
                url: '../../../mvc/controlador/gestion_clinic/add_goal.php',
                data: {                                                                                                                                
                    goal_text: goal_text,
                    goal_area:goal_area,
                    goal_discipline_id: goal_discipline_id,
                    goal_term_id:goal_term_id,

                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success!=='false'){
                       window.location="../../../mvc/vista/gestion_clinic?poc=si&id_poc="+poc_get+"&documentDiscipline="+discipline;
                    }else{
                        var discipline=  $("#discipline_id").val();
                        window.location="../../../mvc/vista/gestion_clinic?poc=si&id_poc="+poc_get+"&documentDiscipline="+discipline;
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
    
    
    $(document).on('hidden.bs.modal', function (e) {
        $('.error-message').hide();                
    });
    
    $(document).on('show.bs.modal', function (e) {  
        
        if($("#documentAdd").val() == 'Prescription' ) {            
          //  autocompletar_radio('concat(DiagCodeValue,\', \',DiagCodeDescrip) as texto','DiagCodeId','diagnosiscodes d LEFT JOIN discipline di ON di.DisciplineId = d.TreatDiscipId','selector',null,null,'di.DisciplineID = \''+$("#discipline_id").val()+'\' AND DiagCodeValue <> \'\'','','diagnostic_id');
            get_patients($("#patients_id").val(),'First_name,Last_name,DOB','modalAddPrescription');
            $("#patients_id_hidden").val($("#patients_id").val())
            $("#discipline_id_hidden").val($("#discipline_description").val())            
        }
        
        if ( $("#documentAdd").val() == 'Evaluation' ) {  
            CKEDITOR.replace('editor1');  //un camnio aqui
            if($("#action").val()!= 'edit'){
                $("#prescription_id_eval_hidden").val($("#prescSelect").val());
                $("#patients_name_eval_modal").html($("#patieNameSelect").val());
                $("#patients_eval_hidden").val($("#patieNameSelect").val());             
                $("#patients_id_eval_hidden").val($("#patieSelect").val());
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
                        $("#attachment_register").show();
                        $("#date_of_signed").val(resultado_controlador.evaluation.date_signed_e);                        
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
                        
                        $("#data_discharge_goals").load("../../../mvc/controlador/gestion_clinic/get_data_goals.php?evaluation_id="+resultado_controlador.summary.id_evaluations);                
                    },
                    "json" 
                );
                
                autocompletar_radio('t.name as texto','id','tbl_templates t LEFT JOIN discipline di ON di.DisciplineId = t.discipline_id','selector',null,null,'di.Name = \''+$("#evalDisciSelect").val()+'\' AND t.type_document_id = 3','','template_id');
            }else{
                $.post(
                    "../gestion_clinic/data_discharge.php?id_discharge="+$("#id_document").val(),
                    function (resultado_controlador) {
                        
                        $("#id_discharge_hidden").val($("#id_document").val());
                        $("#evaluation_id_discharge_hidden").val(resultado_controlador.discharge.id_evaluation);
                        $("#patients_name_discharge_modal").html(resultado_controlador.discharge.name);
                        $("#patients_discharge_hidden").val(resultado_controlador.discharge.name);             
                        $("#patients_id_discharge_hidden").val(resultado_controlador.discharge.patient_id);
                        $("#pat_id_discharge_modal").html(resultado_controlador.discharge.Pat_id);
                        $("#patients_dob_discharge_modal").html(resultado_controlador.discharge.DOB);    
                        $("#from_discharge").val(resultado_controlador.discharge.start_date);                        
                        $("#to_discharge").val(resultado_controlador.discharge.end_date);
                        CKEDITOR.instances['editorDischarge'].setData(resultado_controlador.discharge.ckeditor_discharge);
                        $("#document_route").html("<a href=\"../../../"+resultado_controlador.discharge.route_document+"\" target=\"_blank\">"+resultado_controlador.discharge.route_document+"</a>"); 
                        
                        if(resultado_controlador.discharge.signature_discharge == 0){
                            $("#signatureDischarge").hide();
                        }else{
                            $("#signatureDischarge").show();
                        }                                               
                        
        //                $("#address_discharge_modal").html($("#evalPatieAddressSelect").val());
                        $("#phone_discharge_modal").html(resultado_controlador.discharge.Phone); 
                        $("#doctor_discharge_modal").html(resultado_controlador.discharge.PCP); 
                        $("#doctor_npi_discharge_modal").html(resultado_controlador.discharge.PCP_NPI); 
                        $("#company_id_discharge_hidden").val(resultado_controlador.discharge.company);
                        $("#company_discharge_modal").html(resultado_controlador.discharge.company);
                        $("#company_address_discharge_modal").html(resultado_controlador.discharge.facility_address);
                        $("#company_phone_discharge_modal").html(resultado_controlador.discharge.tin_number);

                        $("#discipline_id_discharge_hidden").val(resultado_controlador.discharge.discipline_id);
                        $("#discipline_discharge_modal").html(resultado_controlador.discharge.diname);                                                
                        $("#diagnostic_discharge_hidden").val(resultado_controlador.discharge.diagnostic);
                        $("#diagnostic_discharge_modal").html(resultado_controlador.discharge.diagnostic);   
                        if(resultado_controlador.discharge.id_tem==='1'){                             
                            $("#template_discharge").val('si');
                            $("#templateDischarge").show();
                            $("#templateCkEditorDischarge").attr("checked",true);
                            $("#templateCkEditorDischarge").attr('disabled',true);
                            $("#templateCkEditorDischarge1").attr("checked",false);
                            $("#templateCkEditorDischarge1").attr('disabled',true);
                            $("#template_panel_discharge").show();
                            $.post(
                                "../../controlador/gestion_clinic/getContentTemplate.php",
                                "template_id=si&document=4&discipline="+resultado_controlador.discharge.discipline_id+"&id_modal="+$("#id_document").val()+"&editor_eval=editor_Discharge",
                                function (resultado_controlador){
                                    $("#template_id_discharge").attr('disabled',true);
                                    $("#components_id_discharge").show();                            
                                    $("#components_id_discharge").html(resultado_controlador.contentComponents);
                                    $("#newTemplateDischarge").html(resultado_controlador.contentTemplate);
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
                            $("#divCkeditorDischarge").hide();
                        }                        
                        $("#data_discharge_goals").load("../../../mvc/controlador/gestion_clinic/get_data_goals.php?action=edit&document=discharge&evaluation_id="+resultado_controlador.discharge.id_evaluation+"&id_discharge="+$("#id_document").val());                
                    },
                    "json" 
                );
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
                        $("#data_goals").load("../../../mvc/controlador/gestion_clinic/get_data_goals.php?evaluation_id="+resultado_controlador.summary.id_evaluations);                
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
                        $("#pcp_summary_modal").html(resultado_controlador.summary.pcp);
                        CKEDITOR.instances['editorSummary'].setData(resultado_controlador.summary.ckeditorsummary);
                        
                        $("#discipline_id_summary_hidden").val(resultado_controlador.summary.discipline_id);
                        $("#discipline_summary_modal").html(resultado_controlador.summary.diname);                                                
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
                        
                        if(resultado_controlador.summary.id_tem==='1'){                             
                            $("#template_summary").val('si');
                            $("#templateSummary").show();
                            $("#CkEditorSummary").attr("checked",true);
                            $("#CkEditorSummary").attr('disabled',true);
                            $("#CkEditorSummary1").attr("checked",false);
                            $("#CkEditorSummary1").attr('disabled',true);
                            $("#template_panel_summary").show();
                            $.post(
                                "../../controlador/gestion_clinic/getContentTemplate.php",
                                "template_id=si&document=3&discipline="+resultado_controlador.summary.discipline_id+"&id_modal="+resultado_controlador.summary.id_summary+"&editor_eval=editor_Summary",
                                function (resultado_controlador){
                                    $("#template_id_Summ").attr('disabled',true);
                                    $("#components_id_summary").show();                            
                                    $("#components_id_summary").html(resultado_controlador.contentComponents);
                                    $("#newTemplateSummary").html(resultado_controlador.contentTemplate);
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
                            $("#divCkeditorSummary").hide();
                        }
                        $("#data_goals").load("../../../mvc/controlador/gestion_clinic/get_data_goals.php?action=edit&document=summary&evaluation_id="+resultado_controlador.summary.id_evaluations+"&id_summary="+resultado_controlador.summary.id_summary);                
                    },
                    "json" 
                );
                
            }
        } 
        
        if($("#documentAdd").val()== 'POC'){                  
            var discipline_id;
            if($("#documentDiscipline").val() == ''){
                discipline_id = $("#discipline_id").val();
            }else{
                discipline_id = $("#documentDiscipline").val();
            }           
            $("#goaldsPoc").load("../../../mvc/controlador/gestion_clinic/getGoladsByDiscipline.php?discipline_id="+discipline_id);                
        }  
            
        if($("#content_modal").length){            
            if($("#documentAdd").val()== 'Prescriptions'){
                $("#title").html('Edit Prescription');
                $("#content_modal").load("../gestion_clinic/form_edit_prescription.php?id_document="+$("#id_document").val());                
            }
            
            if($("#document").val()== 'Note'){
                $("#title").html('Edit POC');
                $("#content_modal").load("../gestion_clinic/form_edit_note.php?id_document="+$("#id_document").val());                
            }
        }
        $('.error-message').hide();
    });
    
    $(".panel-left").resizable({
    handleSelector: ".splitter",
    resizeHeight: false
  });

  $(".panel-top").resizable({
    handleSelector: ".splitter-horizontal",
    resizeWidth: false
  });