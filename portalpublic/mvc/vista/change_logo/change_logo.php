<?php

session_start();
require_once '../../../conex.php';

require_once '../../controlador/comunications/pagination_functions.php'; 

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
//





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

    <script src="../../../js/sweetalert2.min.js" type="text/javascript"></script>
    <script src="../../../js/promise.min.js" type="text/javascript"></script>

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

            $('#form_to_picture').on('submit',function(e){


                e.preventDefault(e);




                var extension = $('#avatar').val().split('.').pop().toLowerCase();
                if ($.inArray(extension, ['jpg', 'png','jpeg']) == -1) {
                    swal("Sorry", " Format no valido", "info");
                    $("#file").val('') ;

                }

                else {
                    var image = $('#avatar')[0].files[0];
                    var actions = 'cambiar_logo';
                    // var file_data = $('').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('avatar', image);
                    //form_data.append('_token', $('meta[name="_token"]').attr('content'));
                    form_data.append('actions', actions);

                    $.ajax({
                        type: "POST",

                        url: '../../controlador/change_logo/change_logo.php',
                        data: form_data,

                        contentType: false,
                        processData: false,

                        dataType: 'JSON',
                        cache: false,
                        success: function (data) {

                            swal("Good Jobs", " new picture for you ", "success");
                            $('#fullCalModal').modal('hide');
                            // $('#avatar_image').attr('src', '{{asset('avatar')}}/' + data);
                            // $('#avatar_image1').attr('src', '{{asset('avatar')}}/' + data);
                        },

                        error: function (data) {
                            swal("Error!", " Contact with administrator!", "error");
                        }
                    })
                }

            });
        });

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


<br>
<br>
<hr>
<section class="main-menu">
    <div class="container">
        <div class="row">



            <div class="col-md-12 col-sm-12 col-xs-12">

                <form  enctype="multipart/form-data" class="form-horizontal form-label-left" id="form_to_picture">
                    <div class="form-group ">
                        <label>Change the logo of the site</label>
                        <input type="file"  required="" class="form-control" id="avatar" name="avatar">
                    </div>

                    <div class="form-group formField">
                        <div class="col-sm-10" align="left"> <button type="submit" class="btn btn-primary text-left">Aceptar</button> </div>

                    </div>

                </form>

            </div>

</div>
    </div>


</section>





<!--  seccion para la respuesta a el paciente  -->





</div>



</body>
</html>
