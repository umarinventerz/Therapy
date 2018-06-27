<?php
session_start();
require_once("conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must Log IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" type='text/css'/>
    <link href="css/portfolio-item.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body>

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "nav_bar.php"; ?>


    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
               <br>

			 <!--   <br>
<img class="img-responsive portfolio-item" src="images/LOGO_1.png" alt="">
				   <li><a href="report_patients.php">PATIENTS</a></li>
 <li><a href="report_change_insurance.php">PATIENTS WITH NEW INSURANCE</a></li>				  
				</ul>
		      </li>		    -->

<img class="img-responsive portfolio-item" src="images/LOGO_1.png" alt="">


			   
		<h3><?php echo '<b>User:</b> '.$_SESSION['first_name'].' '.$_SESSION['last_name']?></h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
       <br><br> 
        <div class="row">

            <div class="col-md-8">
                
       <li><a href="carga.php"><button type="button" class="btn btn-primary active btn-lg">PRESCRIPTIONS</button></a></li>
            <hr>

        <li><a href="signed_doctor1.php"><button type="button" class="btn btn-primary active btn-lg">EVALUATION SIGNED BY DOCTOR</button></a></li>


            </div>

            <div class="col-md-4">                
            </div>

        </div>
        <!-- /.row -->

        <!-- Related Projects Row -->
      
        <!-- /.row -->

        <hr>
</div>
        <!-- Footer -->
        <footer  align="center" >
            
                <div class="col-lg-12"  style="position:absolute;
   margin:auto;
   width: 100%;
   bottom:0;">
                    <p>Copyright &copy; THERAPY  AID 2016</p>
                </div>
            
            <!-- /.row -->
        </footer>

    
    <!-- /.container -->
</body>

</html>