<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
?>
<div class="col-lg-12">  
        <!-- Portfolio Item Heading -->
        <div class="row">
            <br>
            <div class="col-lg-6">               
                <img src="../../../images/LOGO_usar.png" alt="">
            </div>            
            <div class="col-lg-6">			   
		<h4><?php echo '<b>User:</b> '.$_SESSION['first_name'].' '.$_SESSION['last_name']?></h4>
            </div>
        </div>        
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">         
            
            <div class="col-md-12 text-left" >
                
                <?php include 'diagrama.php'; ?>                
            </div>


        </div>
       
        <!-- /.row -->

        <!-- Related Projects Row -->
      
        <!-- /.row -->

        <hr>

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