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

$sql_employee_info = "SELECT first_name,last_name,email FROM employee WHERE   id ='".$_SESSION['user_id']."' ;";
$resultado = ejecutar($sql_employee_info,$conexion);
$employee_info=array();
$i=0;
while($row=mysqli_fetch_array($resultado))
{
    $employee_info[$i]=$row;
    $i++;
}

$sql_questions_patients="SELECT id,Last_name,First_name,Pat_id,tipe,send_to,content,created_at,aswer_to FROM comunications_portal_patients WHERE send_to ='".$employee_info[0]['email']."' ORDER BY created_at DESC ;";
$resultado1 = ejecutar($sql_questions_patients,$conexion);
$i=0;
$questions_info= array();
while($row=mysqli_fetch_array($resultado1))
{
    $questions_info[$i]=$row;
    $i++;
}

$sql_aswer_patients="SELECT id,Pat_id,tipe,content,form,send_to,aswer_to,created_at FROM response_to_comunications_portal_patients WHERE form ='".$employee_info[0]['email']."' ;";
$resultado1 = ejecutar($sql_aswer_patients,$conexion);
$i=0;
$aswer_info= array();
while($row=mysqli_fetch_array($resultado1))
{
    $aswer_info[$i]=$row;
    $i++;
}
?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>.: KIDWORKS THERAPY :.</title>

    <link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../../../plugins/select2/select2.css" rel="stylesheet">
    <link href="../../../css/style_v1.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <link href="../../../css/sweetalert2.min.css" rel="stylesheet">
    <link href="../../../css/dataTables/dataTables.bootstrap.css"rel="stylesheet" type="text/css">
    <link href="../../../css/dataTables/buttons.dataTables.css"rel="stylesheet" type="text/css">



    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../../css/css/simple-sidebar.css" rel="stylesheet"><!-- Bootstrap core JavaScript -->

    <script src="../../../js/vendor/jquery/jquery.min.js"></script>
    <script src="../../../js/vendor/popper/popper.min.js"></script>
    <script src="../../../js/vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="../../../js/jquery.min.js" type="text/javascript"></script>
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js/devoops_ext.js" type="text/javascript"></script>
    <script src="../../../js/jquery.min.js" type="text/javascript"></script>
    <script src="../../../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../js/AjaxConn.js" type="text/javascript" ></script>
    <script src="../../../js/listas.js" type="text/javascript" ></script>
    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>
    <script src="../../../js/promise.min.js" type="text/javascript"></script>
    <script src="../../../js/funciones.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../../../js/resources/shCore.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/dataTables.buttons.js" type="text/javascript"></script>
    <script src="../../../js/dataTables/buttons.html5.js" type="text/javascript"></script>

    <!-- ################### PARA EL CALENDARIO EN LAS GRAFICAS######################-->
    <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="ckeditor/ckeditor.js"></script>

    <script type="text/javascript" language="javascript">
        <script type="text/javascript" language="javascript">


    </script>

    <script>
        $(document).ready(function(){

            $('#contenido_correo').on('submit',function(e){

                e.preventDefault(e);  //para no recargarla pagina, muy importante

                var id1 = document.getElementById("questions_id").textContent;
                var actions='enviar_correo';
                var error=0;
                if($('#contenido_correo1').val() == ''){
                    error=1;
                }
                if (error==0) {
                    var email = $('#contenido_correo1').val();
                    $.ajax({
                        url: "../../controlador/comunications/aswer_to_patients.php",
                        method: "POST",
                        data: {action: actions, id: id1,content:email},
                        success: function (data) {

                            swal({
                                title: '<h4><b> Respuesta Enviada  </b></h4 > ',
                                type: "success"
                            })
                            ;
                        }
                    });
                }
                if (error==1){
                    swal({
                        title: '<h4><b> The content is empty  </b></h4 > ',
                        type: "info"
                    });
                }
            });
        });
</script>


    <script type="text/javascript" language="javascript">

    function responder(id) {


        var actions='mostrar_form_correo';
        $.ajax({
            url: "../../controlador/comunications/aswer_to_patients.php",
            method:"POST",
            data:{id:id,action:actions},
            success: function (data) {
                $('#questions_id').html(id);
                $('#correo_pacientes').html(data);
                $('#modal_respuesta').modal('show');
            }
        });


}
function contestar(id) {
   // $('#fullCalModal').modal('show');

        var actions='ver_questions';
        $.ajax({
            url: "../../controlador/comunications/aswer_to_patients.php",
            method:"POST",
            data:{id:id,action:actions},
            success:function (data) {
                $('#question_content').html(data);
                $('#modal_pregunta').modal('show');
            }
        });

//    if (id == 67) {
 //       swal({
  //          title: '<h4><b> No problems  </b></h4 > ',
   //     type: "error"
  //  })
   //     ;
   // }
}


</script>

</head>
<?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
<body>

<br><br>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
        </div>
    </div>
<div class="container">

    <table class="table table-hover">
        <tr>
            <td>Therapist Name</td>
            <td>Aswer</td>
            <td>Questions</td>

        </tr>
        <tr>
            <?php if(count($employee_info)>0){ ?>
            <td><?php echo $employee_info[0]['first_name']; ?> <?php echo $employee_info[0]['last_name'] ?> </td>
            <td><?php echo count($aswer_info);?></td>
            <td><?php echo count($questions_info);?></td>
            <?php } ?>
            <?php if(count($employee_info)==0) {?>
            <td style="text-align: center">NO DATA </td>
            <?php  } ?>
        </tr>
    </table>


</div>

<br>
<br>
<hr>
<section class="main-menu">
    <div class="container">
        <div class="row">



            <div class="col-md-12 col-sm-12 col-xs-12">

                    <div>
                        <?php if(count($questions_info)>0 ){?>
                        <?php for($i=0;$i<count($questions_info);$i++) {?>

                        <div  >

                            <a   onclick="contestar(<?php echo $questions_info[$i]['id']?>)" >
                                <span><h4>Patient: <?php echo $questions_info[$i]['First_name'];?> <?php echo $questions_info[$i]['Last_name'];?> </h4> <br>  <b> Contents:</b> <br><p></p><?php echo $questions_info[$i]['content'];?> </p> <br> Create: <?php echo $questions_info[$i]['created_at']; ?></span>

                                <?php if( $questions_info[$i]['aswer_to'] == 1) { ?> <!--significa que la pregunta fue respondida -->
                               <p><b>RESPONDIDA</b></p>
                                <?php } ?>

                            </a>
                            <?php if( $questions_info[$i]['aswer_to'] == 1) { ?> <!--significa que la pregunta fue respondida -->
                                <button type="button" onclick="responder(<?php echo $questions_info[$i]['id'] ?>);" class="btn-group-sm btn-success ">Responder Mas</button>

                            <?php } ?>
                            <?php if( $questions_info[$i]['aswer_to'] == 0) { ?> <!--significa que la pregunta fue respondida -->
                            <button type="button" onclick="responder(<?php echo $questions_info[$i]['id'] ?>);" class="btn-group-sm btn-success ">Responder</button>
                            <?php } ?>
                     <hr>
                        </div>
                        <?php } ?>
                        <?php } ?>
                        <?php if(count($questions_info)==0 ){?>
                        <?php echo 'NO QUESTIONS FOR YOUR IN THIS MOMENTS' ;}?>

                    </div>

            </div>

</div>
    </div>
</section>

<div id="modal_pregunta" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10"> <p  class="modal-title">Questions the pacients</p></div>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Close</span></button>


            </div>

            <div class="modal-body" id="info_questions">
                <h4 id="patients_name"></h4>
                <h4 id="question_content"></h4>
            </div>

        </div>
    </div>
</div>



<!--  seccion para la respuesta a el paciente  -->




<div id="modal_respuesta" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
<div class="container">
                <div class="col-md-4">
                    <h3  class="modal-title">Send to: </h3>
                </div>
                    <div class="col-md-3">
                    <h4 id="correo_pacientes"></h4>
                </div>


                    <div class="col-md-8">

                </div>
</div>
                </div>
            </div>

            <div class="modal-body" id="info_questions">
                <div clas="row">
                <div class="container">
                <div class="col-md-4">
                    <p id="questions_id"> </p>
                </div>
                </div>

                <form id="contenido_correo">
                    <div class="container">
                    <textarea class="col-sm-6" id="contenido_correo1" arial>

                    </textarea>
                    </div>
                        <div class="form-group">
                        <div class="container">
                      <div class="btn-group">
                            <div class="col-sm-4 ">
                                <button type="submit" class="btn btn-primary text-left">Send</button>

                            </div>
                        <div class="col-sm-4">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                    </div>
                    </div>

                    </div>
                </form>
                </div>
                </div>
            </div>

        </div>
    </div>
</div>



</body>
</html>
