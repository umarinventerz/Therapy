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

<link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<link href="../../../css/portfolio-item.css" rel="stylesheet">
<script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>
<script src="../../../js/funciones.js"></script>

<link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
<link href="../../../plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="../../../plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
<link href="../../../plugins/xcharts/xcharts.min.css" rel="stylesheet">
<link href="../../../plugins/select2/select2.css" rel="stylesheet">
<link href="../../../plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
<link href="../../../css/style_v1.css" rel="stylesheet">
<link href="../../../plugins/chartist/chartist.min.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<link href="../../../css/sweetalert2.min.css" rel="stylesheet">

<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
 Include all compiled plugins (below), or include individual files as needed 
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>

<script type="text/javascript" language="javascript">
$.fn.delayPasteKeyUp = function(fn, ms)
 {
	 var timer = 0;
	 $(this).on("propertychange input", function()
	 {
		 clearTimeout(timer);
		 timer = setTimeout(fn, ms);
	 });
 };
$(document).ready(function()
 {
        setInterval(function(){
            $("#ingreso").focus();       
        },500);
 	$("#ingreso").delayPasteKeyUp(function(){
                        
            $.post(
                    "../../controlador/assistance/register_assistance.php",
                    "codigo="+$("#ingreso").val(),
                    function (resultado_controlador) {
                        swal({
                            title: "<h3><b>Message<b></h3>",          
                            type: resultado_controlador.type_message,
                            html: "<h4>"+resultado_controlador.resultado_m+"</h4>",
                            showCancelButton: false,
                            animation: "slide-from-top",
                            closeOnConfirm: true,
                            showLoaderOnConfirm: false,
                          });
                          setTimeout(function(){
                                        window.location.href = "../assistance/register.php";
                                    },5000);
                    },
                    "json" 
                    );           
            //$("#resultado").append("Producto registrado: "+ $("#ingreso").val() +"<br>");
            $("#ingreso").val("");
 }, 200);
 });
</script>
<style>
    #resultado{
        font-family: 'calibri light';
      }
</style>
        
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div>
        </div>
        
        <div class="row">
            <label class="col-md-6 form-control-label text-right">Please Scan the bar code of your card:</label>
            <input type="text" name="delay" id="ingreso" autofocus>
        </div>
        
        
        <div id="resultado"></div>
        <br/>
        <hr/>
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; THERAPY  AID 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

        