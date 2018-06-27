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

                                                           
   include $_SERVER['DOCUMENT_ROOT'].'/KIDWORKS/mvc/vista/generate_reports/modal_reports.php';

    $conexion = conectar();
    $sql_1  = " SELECT * FROM tbl_menu_reportes ";
        
    $resultado_query_1 = mysqli_query ($conexion, $sql_1); 

if($resultado_query_1) {    
            
    $resultado_1 = ejecutar($sql_1,$conexion);

    $reporte_1 = array();

    $r = 0;      
    while($datos_1 = mysqli_fetch_assoc($resultado_1)) {            
        $reporte_1[$r] = $datos_1;
        $r++;
   } 
    
}

//////////////////////////

 $sql_2  = " SELECT * FROM tbl_menu_reportes where id_menu_reportes!='6' ";
        
    $resultado_query_2 = mysqli_query ($conexion, $sql_2); 

if($resultado_query_2) {    
            
    $resultado_2 = ejecutar($sql_2,$conexion);

    $reporte_2 = array();

    $t = 0;      
    while($datos_2 = mysqli_fetch_assoc($resultado_2)) {            
        $reporte_2[$t] = $datos_2;
        $t++;
   } 
    
}






?>
<style>
    a{        
        cursor: pointer;
    }
</style>
<nav style="font-size: 13px;" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../../vista/home/home.php">THERAPY AID</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
		

<?php
   if($perfil > '1'){
    echo ' 
    	<li><a href="../../vista/home/home.php"><span class="opcion_menu">HOME</span></a></li>'; } ?>





<?php
   if($perfil >= '4'){
    echo '  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">INPUT DATA<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="../prescriptions/carga.php">PRESCRIPTION</a></li>
            <li><a href="../../vista/signed_doctor/signed_doctor1.php">EVAL SIGNED</a></li>
            <li><a href="../../vista/authorizations/load_authorizations.php">AUTHORIZATIONS  TX</a></li> 


                  <li><a href="../../vista/documents_business/documents_business.php">GENERAL DOCUMENTS</a></li>
                  <li><a href="../../vista/documents/documents.php">PATIENTS/EMPLOYEE DOCS</a></li>
                  <li><a href="../../vista/documents/show_document_form.php">SEARCH DOCUMENT</a></li>

          
        </ul>
      </li>'; } ?>
      


<?php
   if($perfil == '4' OR $perfil == '10'){
         echo ' <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">DOCTORS MD<span class="caret"></span></a>
                         <ul class="dropdown-menu">
                      <li><a href="../../vista/physician/registrar_physician.php"><span class="opcion_menu">REGISTER</span></a></li> 
                      <li><a href="../../vista/physician/consultar_physician.php"><span class="opcion_menu">SEARCH</span></a></li>  

                         </ul>
           </li>'; } ?>      


<?php
   if($perfil >= '2'){
echo '

			<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">REPORTS<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="../../vista/report_amount/report_amount.php">REPORT OVER_ALL</a></li>
                  <li><a href="../../vista/patients/report_patients.php">PATIENTS</a></li>
                  <li><a href="../../vista/patients/report_change_insurance.php">PATIENTS WITH NEW INSURANCE</a></li>
                  <li><a href="../../vista/authorizations/authorizations_report.php">AUTHORIZATIONS BY DATE</a></li>';}
                  

                if($perfil == '11'){
     echo  '     
                  <li><a data-toggle="modal" data-target="#exampleModal" style="cursor:pointer">GENERATE REPORTS</a></li>';  }
                        
                                 
                       
                         
                           $r=0;
                        while (isset($reporte_1[$r])){    
                        if($perfil >= '10'){                       
                            echo '<li><a href="'.$reporte_1[$r]['ruta_carpeta'].'">'.strtoupper($reporte_1[$r]['nombre_reporte']).'</a></li>';}
                            $r++;

                        }  
        
                          $t=0;
                        while (isset($reporte_2[$t])){  
                          if($_SESSION['user_id'] == '11'){ 
                            echo '<li><a href="'.$reporte_2[$t]['ruta_carpeta'].'">'.strtoupper($reporte_2[$t]['nombre_reporte']).'</a></li>';}
                            $t++;
                           
                      }


   if($perfil >= '2'){                       
               echo ' </ul>
              </li>                   
 ' ;
} ?>




 <?php
   if($perfil >= '8'){
	   echo  ' <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">HR<span class="caret"></span></a>
		<ul class="dropdown-menu">
                   
                    
                  
		            <li><a href="../../vista/employee/load_employee.php">REGISTER EMPLOYEE</a></li>
                <li><a href="../../vista/employee/edit_employee.php">EDIT EMPLOYEE</a></li> 
                   
		            <li><a href="../../vista/payroll/find_date_csv.php">RUN PAYROLL</a></li>
                <li><a href="../../vista/payroll/find_date_pre_check.php">PAY EMPLOYEE</a></li>';}
                

                 if($perfil == '11'){
     echo  '
		            <li><a href="../../vista/load_csv/form_load_csv_payroll.php">LOAD TREATMENTS CSV</a></li>';
}
                


                   
       if($perfil >= '8'){
     echo  '              
		</ul>
	      </li>';
}

   if($perfil == '1' OR $perfil == '11' OR $_SESSION['user_id'] == '7'){
	   echo  ' <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">THERAPIES SUMMARY<span class="caret"></span></a>
		<ul class="dropdown-menu">                                                         
		            <li><a href="../../vista/payroll/find_date_pre_check.php">TREATMENTS</a></li>		         
		</ul>
	      </li>';}
 ?>
         
         <?php
   if($perfil >= '3'){
  echo ' <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">PATIENTS<span class="caret"></span></a>
                <ul class="dropdown-menu">
               <li><a href="../../vista/patients/search.php"><span class="opcion_menu">PATIENT SEARCH</span></a></li> 
               <li><a href="../../vista/patients/search_patient.php"><span class="opcion_menu">EDIT PATIENT INS</span></a></li>  

            </ul>
              </li> ' ;
} ?>
            

 <?php
   if($perfil == '1' OR $_SESSION['user_id'] == '7' ){
  echo ' <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">DOCUMENTS<span class="caret"></span></a>
                <ul class="dropdown-menu">
               <li><a href="../../vista/documents_business/documents_business.php"><span class="opcion_menu">GENERAL DOCUMENTS</span></a></li> 
               
            </ul>
              </li> ' ;
} ?>



<?php
   if($perfil == '11'){
     echo  '
			<li><a href="../../vista/load_csv/load_csv_form.php"><span class="opcion_menu">LOAD CSV</span></a></li>     '; }  ?>

<?php
   if($perfil == '11' OR $_SESSION['user_id'] == '45' ){
     echo  '
      <li><a href="../../vista/load_csv/load_csv_form_1.php"><span class="opcion_menu">TREATMENTS</span></a></li>     '; }  ?>



			<li><a href="#"><span class="opcion_menu">CONTACT</span></a></li> 
                        <li><a href="../../../exit.php"><span class="opcion_menu">EXIT</span></a></li>                         
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
