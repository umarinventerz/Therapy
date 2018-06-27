<?php

session_start();

// Set up the ORM library
require_once('conex.php');


?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <title>Pretty Charts with jQuery and AJAX | Tutorialzine Demo</title>
		<link href="assets/css/xcharts.min.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.min.css" type='text/css'/> 
<script src="js/bootstrap.min.js"></script> 

<script src="js/devoops_ext.js"></script>
		<!-- Include bootstrap css -->
		<link href="assets/css/daterangepicker.css" rel="stylesheet">
		<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.2/css/bootstrap.min.css" rel="stylesheet" />

    </head>
    <body>

   <?php $perfil = $_SESSION['user_type']; include "mvc/vista/nav_bar/nav_bar.php"; ?>
   <br>
    <br>
     <br>
     <br>
     <br>
     <br>
     <br>
   <img class="img-responsive portfolio-item" src="images/LOGO_1.png" alt="">

		<div id="content">
			
			<form class="form-horizontal">
			  <fieldset>
		        <div class="input-prepend">
		          <span class="add-on"><i class="icon-calendar"></i></span><input type="text" name="range" id="range" />
		        </div>
			  </fieldset>
			</form>
			
			<div id="placeholder">
				<figure id="chart"></figure>

			</div>

		</div>

		

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

		<!-- xcharts includes -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.js"></script>
		<script src="assets/js/xcharts.min.js"></script>

		<!-- The daterange picker bootstrap plugin -->
		<script src="assets/js/sugar.min.js"></script>
		<script src="assets/js/daterangepicker.js"></script>

		<!-- Our main script file -->
		<script src="assets/js/script.js"></script>

		
    </body>
     <footer  align="center" >
            
                <div class="col-lg-12"  style="position:absolute;
   margin:auto;
   width: 100%;
   bottom:0;">
                    <p>Copyright &copy; THERAPY  AID 2016</p>
                </div>
            
            <!-- /.row -->
        </footer>
</html>

