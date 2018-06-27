<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="../../../index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="../home/home.php";</script>';
    }
}

?>



<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>.: THERAPY  AID Code Diagnostic</title>

    <!--<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">-->
    <!--<link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">-->
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet" type='text/css'/>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <link href="../../../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />

    <!--<script src="plugins/jquery-ui/jquery-ui.min.js"></script>-->
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/devoops_ext.js"></script>
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

    <script src="../../../js/fileinput.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>
    <script>
        function adddiagnosticcode(){

            $('#fullCalModal').modal();


        }
        function validar_form_note(){


            var nombres_campos = '';
            var error = 0;
            if($("#diagnostic_code").val() == ''){
                nombres_campos += ' * The field Diagnostic Code is empty';
                error = 1;


            }
            if($('#description_code').val() == '' ){
                nombres_campos += '* The field Descriptions Code is empty';
                error = 1;
            }
            if(error != 0) {
                swal({
                    title: "<h4><b>Complety the field of email</h4>",
                    type: "info",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    showLoaderOnConfirm: false,
                });
            }

            if(error == 0){

                var campos_formulario = $("#formPrescription").serialize();
                var diagnostic_code = $('#diagnostic_code').val();
                var description_code = $('#description_code').val();
                var discipline = $('#discipline').val();
                var data = new FormData();
                data.append('campos_formulario',campos_formulario);
                data.append('diagnostic_code',diagnostic_code);
                data.append('description_code',description_code);
                data.append('discipline',discipline);


                $.ajax({
                    url: "../../controlador/diagnosticcode/diagnostic_code.php",
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success : function(resultado_controlador){
                        //alert(resultado_controlador_archivo);


                            swal({
                                    type: 'success',
                                    html: '<h3><b>Record edited      successfully</b></h3>',
                                    timer: 3000
                                }
                            );
                            $("#fullCalModal").modal('hide');


                    },
                    error: function (data) {
                        swal("Error!", " Contact with administrator!", "error");
                    }
                });


            }
            return false;
        }
</script>


<!-- fin de la  -->
</head>

<body>


<div class="container">

    <div class="row">
        <div class="col-lg-12">


            <br>

    <button type="button" class="btn btn-danger btn-lg" onclick="adddiagnosticcode()">Add New Diagnostic Code</button>
        </div>
    </div>
    <br>
    <br>

    <table   class="table" width="60px">
        <thead>
        <tr>
            <th>Diagnostic Code</th>
            <th>Diagnostic Descrptions</th>
            <th>Discipline</th>
            <th>Descriptions</th>



        </tr>
        </thead>


        <tbody>
        <?php
        $conexion = conectar();

        $query01 = " select DiagCodeValue , DiagCodeDescrip,DiagCodeId,TreatDiscipId,Name,Description  from diagnosiscodes d left join discipline di on di.DisciplineId = d.TreatDiscipId order by DiagCodeId ASC 
					";

        $result01 = mysqli_query($conexion, $query01);

        while($row = mysqli_fetch_array($result01,MYSQLI_ASSOC)){
            echo '<tr>	 
						
						<td>'.$row['DiagCodeValue'].'</td>
					  <td>'.$row['DiagCodeDescrip'].'</td>
					  <td>'.$row['Name'].'</td>
					  <td>'.$row['Description'].'</td>
					  
					  
					 
				<!--	 <td><div id="result_u'.$row['DiagCodeId'].'"><button type="button" class="btn btn-info btn-lg" onclick="showUpdatePrescription(\''.$row['DiagCodeValue'].'\',\''.$row['DiagCodeDescrip'].'\');">Update</button></div></td>
					 <td><div id="result_d'.$row['DiagCodeId'].'"><button type="button" class="btn btn-danger btn-lg" onclick="deletePrescription(\''.$row['DiagCodeValue'].'\',\''.$row['DiagCodeDescrip'].'\');">Delete</button></div></td> 			 			
					
					-->
					</tr>';
        }

        ?>

        <tbody/>

    </table>

        <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="panel panel-default formPanel">
                    <div class="panel-heading ">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                        <div class="row" >
                            <div class="col-md-8"> <h4  class="modal-title">Add new Diagnostic Code</h4></div>

                            <div class="col-md-4">
                                <h5 id="modalTitle" class="modal-title"></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="modal-body">
                    <form id="formPrescription" onsubmit="return validar_form_note();" enctype="multipart/form-data" > <!--formulario para adicionar codigo -->
                      <div class="form-group">
                        <div class="row" style="margin-top: 15px;">
                            <label class="col-sm-3">Diagnostic Code:</label>
                            <div class="col-lg-6">
                                <input type="text" name="diagnostic_code" id="diagnostic_code" class="form-control" placeholder="Code"/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <label class="col-sm-3">Description Code:</label>
                            <div class="col-lg-6">
                                <input type="textarea" name="description_code" id="description_code" class="form-control" placeholder="Descriptions"/>
                            </div>
                        </div>
                          <div class="row" style="margin-top: 15px;">
                              <label class="col-sm-3">Discipline</label>
                              <div class="col-lg-6">
                                  <select name='discipline'  id='discipline' >
                                  <option value=''>--- SELECT ---</option>
                                      <option value='1'>Speech Therapy</option>
                                      <option value='2'>Occupational Therapy</option>
                                      <option value='3'>Physical Therapy</option>
                                      <option value='4'>Mental Health</option>
                                      <option value='99'>Early Intervention</option>
                                  </select>
                              </div>
                          </div>
                      </div>
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

            </div></div>
    </div>


    <br>
    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; THERAPY  AID 2016</p>
            </div>
        </div>
        <!-- /.row -->
    </footer>

    </div>

    <!-- /.container -->

</body>




















