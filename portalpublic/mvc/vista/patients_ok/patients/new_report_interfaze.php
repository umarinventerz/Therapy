<?php 
session_start(); 
require_once("../../../conex.php");
//require_once("conex.php");
if(!isset($_SESSION['user_id'])){ 
    echo '<script>alert(\'Must LOG IN First\')</script>'; 
   echo '<script>window.location="../../../index.php";</script>';
} 

if(isset($_SESSION['name'])){ 
    $_POST['name'] = trim($_SESSION['name']); 
    $_POST['find'] = $_SESSION['find']; 
} 
 
if(isset($_GET['name'])){
	$_POST['name'] = $_GET['name'];
	$_POST['find'] = ' Find ';	
}


	
 if($_POST['find'] == " Find " || $_POST['buttonReload'] == "RECARGADO"){ 


	 
$conexion = conectar(); 
 
 $Pat_id = $_POST['Pat_id']; 
 
 

 
if($_POST['name']==''){ 
    echo 'EMPTY NAME'; 
}else{ 
list($pat_id,$company) = explode('-',$_POST['name']); 



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 
} 
unset($_SESSION['name']); 
unset($_SESSION['find']); 
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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">




 Include all compiled plugins (below), or include individual files as needed 



    <!-- Style Bootstrap-->
    
    <script src="../../../js/jquery.min.js"></script>
    <!--<script src="jquery.min.js"></script>-->
    <script src="../../../js/bootstrap-switch.js" type="text/javascript" language="javascript"></script>
    
<!--
    <script type="text/javascript" language="javascript" src="../../../js/bootstrap-multiselect.js"></script> 
     CSS -->
    
   <link href="../../../css/bootstrap-switch.css" rel="stylesheet" type="text/css">
      <link href="../../../plugins/select2/select2.css" rel="stylesheet">       
  <!--  <link rel="stylesheet" type="text/css" href="../../../css/bootstrap-multiselect.css">

 NEW REPORT TOOGLE SWITCH BUTTONS 
    <link type="text/css" href="../../../css/bootstrap-toggle.min.css" rel="stylesheet">-->
    <script type="text/javascript" language="javascript"  src="../../../js/bootstrap.min.js"></script> 
    <style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
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
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
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
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
       
<!-- NEW REPORT TOOGLE SWITCH BUTTONS           __________        STYLES -->
                  <style>
                .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
                .toggle.ios .toggle-handle { border-radius: 20px; }
                </style>
                <style>
                .toggle.android { border-radius: 0px;}
                .toggle.android .toggle-handle { border-radius: 0px; }
                </style>



<style>

.cmn-toggle {
  position: absolute;
  margin-left: -9999px;
  visibility: hidden;
}
.cmn-toggle + label {
  display: block;
  position: relative;
  cursor: pointer;
  outline: none;
  user-select: none;
}





input.cmn-toggle-round + label {
  padding: 2px;
  width: 120px;
  height: 30px;
  background-color: #dddddd;
  border-radius: 10px;
}
input.cmn-toggle-round + label:before,
input.cmn-toggle-round + label:after {
  display: block;
  position: absolute;
  top: 1px;
  left: 1px;
  bottom: 1px;
  content: "";
}
input.cmn-toggle-round + label:before {
  right: 1px;
  background-color: #f1f1f1;
  border-radius: 10px;
  transition: background 0.6s;
}
input.cmn-toggle-round + label:after {
  width: 58px;
  background-color: #fff;
  border-radius: 100%;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  transition: margin 0.6s;
}
input.cmn-toggle-round:checked + label:before {
  background-color: #8ce196;
}
input.cmn-toggle-round:checked + label:after {
  margin-left: 60px;
}







input.cmn-toggle-round-flat + label {
  padding: 2px;
  width: 120px;
  height: 30px;
  background-color: #d60606;
  border-radius: 60px;
  transition: background 0.12s;
}
input.cmn-toggle-round-flat + label:before,
input.cmn-toggle-round-flat + label:after {
  display: block;
  position: absolute;
  content: "";
}
input.cmn-toggle-round-flat + label:before {
  top: 2px;
  left: 2px;
  bottom: 2px;
  right: 2px;
  background-color: #fff;
  border-radius: 60px;
  transition: background 0.4s;
}
input.cmn-toggle-round-flat + label:after {
  top: 4px;
  left: 4px;
  bottom: 4px;
  width: 52px;
  background-color: #d60606;
  border-radius: 52px;
  transition: margin 0.4s, background 0.4s;
}
input.cmn-toggle-round-flat:checked + label {
  background-color: #8ce196;
}
input.cmn-toggle-round-flat:checked + label:after {
  margin-left: 60px;
  background-color: #8ce196;
}














  </style>              

    <!-- End Style -->
    <script type="text/javascript" language="javascript" class="init">  

 


   

    </script> 
</head> 
 
<body> 
 
    <!-- Navigation --> 
    
    <?php 
    if(!isset($_GET['name']))
    $perfil = $_SESSION['user_type']; include "../../vista/nav_bar/nav_bar.php"; ?>
 
 
    <!-- Page Content --> 
    <div class="container"> 
 
        <!-- Portfolio Item Heading --> 
        <div class="row"> 
            <div class="col-lg-12"> 
               <br>              
            <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div> 
        </div> 
        <!-- /.row --> 
 
        <!-- Portfolio Item Row --> 
        <div class="row"> 
 	<?php if(!isset($_GET['name'])){?>
            <div class="col-md-12"> 
             <form class="form-horizontal" id="myForm" action="new_report_interfaze.php" method="post"> 
            
            <div class="col-lg-12"> 
                <h3 class="page-header">Choose Patient from List</h3> 
            </div> 
            <div class="row">             
            <div class="col-xs-3"> 
                <div class="input-group"> 
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span> 
                <select class="multiple" style="width:250px;" name='name' id='name'  >
                    <option value=''>--- SELECT ---</option>                 
                    <?php 
                    $sql  = "Select Distinct Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name "; 
                    $conexion = conectar(); 
                    $resultado = ejecutar($sql,$conexion); 
                    while ($row=mysqli_fetch_array($resultado))  
                    {     
                        if((trim($row["Pat_id"])) == (trim($patient_id))) 
                            print("<option value='".$row["Pat_id"]."' selected>".$row["Patient_name"] .$row["Table_name"]    ." </option>"); 
                        else 
                            print("<option value='".$row["Pat_id"]."'>".$row["Patient_name"] .$row["Table_name"]    ." </option>"); 
                    } 
             
                    ?> 
                       
                    </select> 
                </div> 









            </div> 
            
            </div> 
           
                <hr> 
            <div class="row"> 
            <div class="col-xs-12"> 
                <div class="input-group"> 
                <input id='find' name='find' type='submit' value=" Find " class="btn btn-success btn-lg" onclick="findData();"> 
        &nbsp&nbsp           <input name='reset' type='button' value=" Reset " onclick= "window.location.href = 'new_report_interfaze.php';" class="btn btn-danger btn-lg">             
                </div> 
            </div>             
            </div> 
        </form>               
            </div>  
            <?php }?>       
        </div> 
        <!-- /.row --> 
    <hr> 
        <!-- Related Projects Row --> 
        <div class="row"> 
 
            <div class="col-lg-12"> 
        <?php    
    
        if($_POST['find'] == ' Find ') { 
             

         
              $conexion = conectar();     


             /////////// PARA AGARRAR EL NOMBRE DEL PACIENTE

              $sql  = "SELECT  CONCAT(Last_name,', ',First_name) as Patient_name from patients WHERE Pat_id='".$_POST['name']."'  "; 
                    $conexion = conectar(); 
                    $resultado = ejecutar($sql,$conexion); 
                    while ($row=mysqli_fetch_array($resultado))  
                    {
                    $patient_n= $row["Patient_name"];  
                    }
                    
             //consulto los datos de patients copy
                    $var  = "SELECT  * from patients_copy WHERE Pat_id='".$_POST['name']."'  "; 
                    $conexion1 = conectar(); 
                    $resultado1 = ejecutar($var,$conexion1); 
                    $i=0;
                    while ($row1=mysqli_fetch_array($resultado1))  
                    {
                       $data[$i]= $row1;  
                       $i++;
                    }
                    
                    //fila 1
                    $rx_request_ot=$data[0]['prescription_ot'];
                    $rx_request_pt=$data[0]['prescription_pt'];
                    $rx_request_st=$data[0]['prescription_st'];                    
                    
                    //fila 2
                    $waiting_rx_ot=$data[0]['waiting_prescription_ot'];
                    $waiting_rx_pt=$data[0]['waiting_prescription_pt'];
                    $waiting_rx_st=$data[0]['waiting_prescription_st'];
                    
                    //fila 3
                    $request_eval_auth_ot=$data[0]['eval_auth_ot'];
                    $request_eval_auth_pt=$data[0]['eval_auth_pt'];
                    $request_eval_auth_st=$data[0]['eval_auth_st'];
                    
                    //fila 4
                    $waiting_eval_auth_ot=$data[0]['waiting_auth_eval_ot'];
                    $waiting_eval_auth_pt=$data[0]['waiting_auth_eval_pt'];
                    $waiting_eval_auth_st=$data[0]['waiting_auth_eval_st'];
                    
                    //fila 5
                    $eval_patient_ot=$data[0]['eval_patient_ot'];
                    $eval_patient_pt=$data[0]['eval_patient_pt'];
                    $eval_patient_st=$data[0]['eval_patient_st'];
                    
                    //fila 6
                    $request_doctor_signature_ot=$data[0]['doctor_signature_ot'];
                    $request_doctor_signature_pt=$data[0]['doctor_signature_pt'];
                    $request_doctor_signature_st=$data[0]['doctor_signature_st'];
                    
                     //fila 7
                    $waiting_doctor_signature_ot=$data[0]['waiting_signature_ot'];
                    $waiting_doctor_signature_pt=$data[0]['waiting_signature_pt'];
                    $waiting_doctor_signature_st=$data[0]['waiting_signature_st'];
                    
                     //fila 8
                    $tx_auth_ot=$data[0]['tx_auth_ot'];
                    $tx_auth_pt=$data[0]['tx_auth_pt'];
                    $tx_auth_st=$data[0]['tx_auth_st'];
                    
                     //fila 9
                    $waiting_tx_auth_ot=$data[0]['waiting_tx_auth_ot'];
                    $waiting_tx_auth_pt=$data[0]['waiting_tx_auth_pt'];
                    $waiting_tx_auth_st=$data[0]['waiting_tx_auth_st'];
                    
                     //fila 10
                    $ready_treatment_ot=$data[0]['ready_treatment_ot'];
                    $ready_treatment_pt=$data[0]['ready_treatment_pt'];
                    $ready_treatment_st=$data[0]['ready_treatment_st'];
                    
                     //fila 11
                    $scheduled_ot=$data[0]['scheduled_ot'];
                    $scheduled_pt=$data[0]['scheduled_pt'];
                    $scheduled_st=$data[0]['scheduled_st'];
                    
                     //fila 12
                    $hold_ot=$data[0]['hold_ot'];
                    $hold_pt=$data[0]['hold_pt'];
                    $hold_st=$data[0]['hold_st'];
                    
                     //fila 13
                    $progress_adults_ot=$data[0]['progress_adults_ot'];
                    $progress_adults_pt=$data[0]['progress_adults_pt'];
                    $progress_adults_st=$data[0]['progress_adults_st'];
                    
                     //fila 14
                    $progress_pedriatics_ot=$data[0]['progress_pedriatics_ot'];
                    $progress_pedriatics_pt=$data[0]['progress_pedriatics_pt'];
                    $progress_pedriatics_st=$data[0]['progress_pedriatics_st'];
                    
                     //fila 15
                    $been_seen_ot=$data[0]['been_seen_ot'];
                    $been_seen_pt=$data[0]['been_seen_pt'];
                    $been_seen_st=$data[0]['been_seen_st'];
                    
                     //fila 16
                    $discharge_ot=$data[0]['discharge_ot'];
                    $discharge_pt=$data[0]['discharge_pt'];
                    $discharge_st=$data[0]['discharge_st'];
                    
                  



        ?> 
             


 <div align="center"><button class="btn btn-warning" id="enviar" onclick="update();">Update</button></div>


    <table class="table table-striped table-bordered" align="center">
       
        <thead>

             <tr>
             <label style="font-size: 17pt;" ><?php echo   $patient_n ;?> </label>   
             </tr>

            <tr>
                <th style="font-size: 17pt; text-align: center;" align="center">TYPE REPORT</th>                
                <th style="font-size: 17pt; text-align: center;" align="center">OT</th>
                <th style="font-size: 17pt; text-align: center;" align="center">PT</th>
                <th style="font-size: 17pt; text-align: center;" align="center">ST</th>
            </tr>
        
        </thead>

 
      <tbody>
            <?php
              $conexion = conectar();     


                ?>
           
            <input type="hidden" name="pat_id" id="pat_id" value="<?=$_POST['name']?>"/>
            <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">RX REQUEST</td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="rx_request_ot" id="rx_request_ot" onchange="validar_ot(1);" <?php if($rx_request_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="rx_request_pt" id="rx_request_pt" onchange="validar_pt(1);" <?php if($rx_request_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="rx_request_st" id="rx_request_st" onchange="validar_st(1);" <?php if($rx_request_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                
                </td>

            </tr>

    <!---            FIN DEL PRIMERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">WAITING RX</td>
               
                 <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_rx_ot" id="waiting_rx_ot" onchange="validar_ot(2);" <?php if($waiting_rx_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_rx_pt" id="waiting_rx_pt" onchange="validar_pt(2);" <?php if($waiting_rx_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_rx_st" id="waiting_rx_st" onchange="validar_st(2);" <?php if($waiting_rx_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>

            <!---            FIN DEL SEGUNDO COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">REQUEST EVAL AUTH</td>
               
                 <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="auth_eval_ot" id="auth_eval_ot" onchange="validar_ot(3);" <?php if($request_eval_auth_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>


                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="auth_eval_pt" id="auth_eval_pt" onchange="validar_pt(3);" <?php if($request_eval_auth_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="auth_eval_st" id="auth_eval_st" onchange="validar_st(3);" <?php if($request_eval_auth_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>

            <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">WAITING EVAL AUTH</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="waiting_eval_ot" id="waiting_eval_ot" onchange="validar_ot(4);" <?php if($waiting_eval_auth_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_eval_pt" id="waiting_eval_pt" onchange="validar_pt(4);" <?php if($waiting_eval_auth_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_eval_st" id="waiting_eval_st" onchange="validar_st(4);" <?php if($waiting_eval_auth_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>

            <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">EVAL PATIENT</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="eval_patient_ot" id="eval_patient_ot" onchange="validar_ot(5);" <?php if($eval_patient_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="eval_patient_pt" id="eval_patient_pt" onchange="validar_pt(5);" <?php if($eval_patient_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="eval_patient_st" id="eval_patient_st" onchange="validar_st(5);" <?php if($eval_patient_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>


             <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">REQUEST DOCTOR SIGNATURE  </td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="request_signature_ot" id="request_signature_ot" onchange="validar_ot(6);" <?php if($request_doctor_signature_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="request_signature_pt" id="request_signature_pt" onchange="validar_pt(6);" <?php if($request_doctor_signature_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="request_signature_st" id="request_signature_st" onchange="validar_st(6);" <?php if($request_doctor_signature_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>



              <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">WAITING DOCTOR SIGNATURE</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="waiting_signature_ot" id="waiting_signature_ot" onchange="validar_ot(7);" <?php if($waiting_doctor_signature_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_signature_pt" id="waiting_signature_pt" onchange="validar_pt(7);" <?php if($waiting_doctor_signature_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_signature_st" id="waiting_signature_st" onchange="validar_st(7);" <?php if($waiting_doctor_signature_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>



              <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">REQUEST AUTH TX</td>
               
                <td align="center"> 
                <label class="switch">
                    <input type="checkbox" name="request_auth_tx_ot" id="request_auth_tx_ot" onchange="validar_grup_ot(1);" <?php if($tx_auth_ot==1){?> checked="" <?php }?>>
                    <div class="slider"></div>
                </label>
                </td>

                <td align="center">
                <label class="switch">
                    <input type="checkbox" name="request_auth_tx_pt" id="request_auth_tx_pt" onchange="validar_grup_pt(1);" <?php if($tx_auth_pt==1){?> checked="" <?php }?>>
                    <div class="slider"></div>
                </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="request_auth_tx_st" id="request_auth_tx_st" onchange="validar_grup_st(1);" <?php if($tx_auth_st==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

            </tr>


             <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">WAITING AUTH TX</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="waiting_auth_tx_ot" id="waiting_auth_tx_ot" onchange="validar_grup_ot(2);" <?php if($waiting_tx_auth_ot==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_auth_tx_pt" id="waiting_auth_tx_pt" onchange="validar_grup_pt(2);" <?php if($waiting_tx_auth_pt==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="waiting_auth_tx_st" id="waiting_auth_tx_st" onchange="validar_grup_st(2);" <?php if($waiting_tx_auth_st==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

            </tr>


            <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">READY FOR TREATMENT</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="ready_for_treatment_ot" id="ready_for_treatment_ot" onchange="validar_grup_ot1(1);" <?php if($ready_treatment_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="ready_for_treatment_pt" id="ready_for_treatment_pt" onchange="validar_grup_pt1(1);" <?php if($ready_treatment_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="ready_for_treatment_st" id="ready_for_treatment_st" onchange="validar_grup_st1(1);" <?php if($ready_treatment_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>



            <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">SCHEDULED FOR TREATMENT</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="scheduled_for_treatment_ot" id="scheduled_for_treatment_ot" onchange="validar_grup_ot1(2);" <?php if($scheduled_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="scheduled_for_treatment_pt" id="scheduled_for_treatment_pt" onchange="validar_grup_pt1(2);" <?php if($scheduled_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="scheduled_for_treatment_st" id="scheduled_for_treatment_st" onchange="validar_grup_st1(2);" <?php if($scheduled_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>


            <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">PATIENTS ON HOLD</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="patients_on_hold_ot" id="patients_on_hold_ot" <?php if($hold_ot==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="patients_on_hold_pt" id="patients_on_hold_pt" <?php if($hold_pt==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="patients_on_hold_st" id="patients_on_hold_st" <?php if($hold_st==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

            </tr>


            



             <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">PROGRESS NOTES ADULTS</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="progress_note_adults_ot" id="progress_note_adults_ot" <?php if($progress_adults_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="progress_note_adults_pt" id="progress_note_adults_pt" <?php if($progress_adults_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="progress_note_adults_st" id="progress_note_adults_st" <?php if($progress_adults_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>



                <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">PROGRESS NOTES PEDIATRIC</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="progress_note_pedriatics_ot" id="progress_note_pedriatics_ot" <?php if($progress_pedriatics_ot==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="progress_note_pedriatics_pt" id="progress_note_pedriatics_pt" <?php if($progress_pedriatics_pt==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="progress_note_pedriatics_st" id="progress_note_pedriatics_st" <?php if($progress_pedriatics_st==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

            </tr>




                <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">ACTIVE PATIENTS NOT BEEN SEEN</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="not_been_seen_ot" id="not_been_seen_ot" <?php if($been_seen_ot==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="not_been_seen_pt" id="not_been_seen_pt" <?php if($been_seen_pt==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="not_been_seen_st" id="not_been_seen_st" <?php if($been_seen_st==1){?> checked="" <?php }?>>
                        <div class="slider round"></div>
                    </label>
                </td>

            </tr>






                <!---            FIN DE TERCERA COLUMNA REPORTE                          -->

             <tr>
                <td  align="center" style="font-size: 12pt;font-weight: 700;">DISCHARGE PATIENTS</td>
               
                <td align="center"> 
                    <label class="switch">
                        <input type="checkbox" name="discharge_ot" id="discharge_ot" <?php if($discharge_ot==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="discharge_pt" id="discharge_pt" <?php if($discharge_pt==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

                <td align="center">
                    <label class="switch">
                        <input type="checkbox" name="discharge_st" id="discharge_st" <?php if($discharge_st==1){?> checked="" <?php }?>>
                        <div class="slider"></div>
                    </label>
                </td>

            </tr>




        </tbody>
    

    </table>



     <div align="center"><button class="btn btn-warning" id="enviar" onclick="update();">Update</button></div>




             
        <?php }?>     
            </div>         
        </div> 

       

        <!-- /.row --> 
        <!-- Footer --> 
        <footer> 
            <div class="row"> 
                <div class="col-lg-12"> 
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
                </div> 
            </div> 
            <!-- /.row --> 
        </footer> 
 
    </div> 
    <!-- /.container --> 
</body> 


<script type="text/javascript">
    
// Run Select2 plugin on elements
function DemoSelect2(){
  $('#name').select2(); 
}
// Run timepicker

$(document).ready(function() {
  // Create Wysiwig editor for textare
  //TinyMCEStart('#wysiwig_simple', null);
  //TinyMCEStart('#wysiwig_full', 'extreme');
  // Add slider for change test input length
  //FormLayoutExampleInputLength($( ".slider-style" ));
  // Initialize datepicker
  //$('#input_date_licence').datepicker({setDate: new Date()});
  //$('#input_date_finger').datepicker({setDate: new Date()});
  //$('#dob').datepicker({setDate: new Date()});
  //$('#hireDate').datepicker({setDate: new Date()});
  //$('#terminationDate').datepicker({setDate: new Date()});
  // Load Timepicker plugin
  //LoadTimePickerScript(DemoTimePicker);
  // Add tooltip to form-controls
  $('.form-control').tooltip();
  LoadSelect2ScriptExt(DemoSelect2);
  // Load example of form validation
  //LoadBootstrapValidatorScript(DemoFormValidator);
  // Add drag-n-drop feature to boxes
  //WinMove();
  //ShowDivEdit();
  //enableField();
});

function update(){
    
    if($('#rx_request_ot').is(':checked')){      
       var fila1_ot=1;
    }else{
        fila1_ot=0;
    }
    if($('#rx_request_pt').is(':checked')){      
       var fila1_pt=1;
    }else{
        fila1_pt=0;
    }
    if($('#rx_request_st').is(':checked')){      
       var fila1_st=1;
    }else{
        fila1_st=0;
    }
    ////////////////////////////////////////
    if($('#waiting_rx_ot').is(':checked')){      
       var fila2_ot=1;
    }else{
        fila2_ot=0;
    }
    if($('#waiting_rx_pt').is(':checked')){      
       var fila2_pt=1;
    }else{
        fila2_pt=0;
    }
    if($('#waiting_rx_st').is(':checked')){      
       var fila2_st=1;
    }else{
        fila2_st=0;
    }
    /////////////////////////////////////////
    if($('#auth_eval_ot').is(':checked')){      
       var fila3_ot=1;
    }else{
        fila3_ot=0;
    }
    if($('#auth_eval_pt').is(':checked')){      
       var fila3_pt=1;
    }else{
        fila3_pt=0;
    }
    if($('#auth_eval_st').is(':checked')){      
       var fila3_st=1;
    }else{
        fila3_st=0;
    }
    /////////////////////////////////////////
    if($('#waiting_eval_ot').is(':checked')){      
       var fila4_ot=1;
    }else{
        fila4_ot=0;
    }
    if($('#waiting_eval_pt').is(':checked')){      
       var fila4_pt=1;
    }else{
        fila4_pt=0;
    }
    if($('#waiting_eval_st').is(':checked')){      
       var fila4_st=1;
    }else{
        fila4_st=0;
    }
    ///////////////////////////////////////////
    if($('#eval_patient_ot').is(':checked')){      
       var fila5_ot=1;
    }else{
        fila5_ot=0;
    }
    if($('#eval_patient_pt').is(':checked')){      
       var fila5_pt=1;
    }else{
        fila5_pt=0;
    }
    if($('#eval_patient_st').is(':checked')){      
       var fila5_st=1;
    }else{
        fila5_st=0;
    }
    /////////////////////////////////////////////
    if($('#request_signature_ot').is(':checked')){      
       var fila6_ot=1;
    }else{
        fila6_ot=0;
    }
    if($('#request_signature_pt').is(':checked')){      
       var fila6_pt=1;
    }else{
        fila6_pt=0;
    }
    if($('#request_signature_st').is(':checked')){      
       var fila6_st=1;
    }else{
        fila6_st=0;
    }
    //////////////////////////////////////////////////
    if($('#waiting_signature_ot').is(':checked')){      
       var fila7_ot=1;
    }else{
        fila7_ot=0;
    }
    if($('#waiting_signature_pt').is(':checked')){      
       var fila7_pt=1;
    }else{
        fila7_pt=0;
    }
    if($('#waiting_signature_st').is(':checked')){      
       var fila7_st=1;
    }else{
        fila7_st=0;
    }
    //////////////////////////////////////////////////
    if($('#request_auth_tx_ot').is(':checked')){              
        var fila8_ot=1;    
    }else{                
        fila8_ot=0;
    }
    if($('#request_auth_tx_pt').is(':checked')){              
        var fila8_pt=1;    
    }else{                
        fila8_pt=0;
    }
    if($('#request_auth_tx_st').is(':checked')){              
        var fila8_st=1;    
    }else{                
        fila8_st=0;
    }
    ////////////////////////////////////////////////////
    if($('#waiting_auth_tx_ot').is(':checked')){              
        var fila9_ot=1;    
    }else{                
        fila9_ot=0;
    }
    if($('#waiting_auth_tx_pt').is(':checked')){              
        var fila9_pt=1;    
    }else{                
        fila9_pt=0;
    }
    if($('#waiting_auth_tx_st').is(':checked')){              
        var fila9_st=1;    
    }else{                
        fila9_st=0;
    }
    //////////////////////////////////////////////////
    if($('#ready_for_treatment_ot').is(':checked')){              
        var fila10_ot=1;    
    }else{                
        fila10_ot=0;
    }
    if($('#ready_for_treatment_pt').is(':checked')){              
        var fila10_pt=1;    
    }else{                
        fila10_pt=0;
    }
    if($('#ready_for_treatment_st').is(':checked')){              
        var fila10_st=1;    
    }else{                
        fila10_st=0;
    }
    //////////////////////////////////////////////////
    if($('#scheduled_for_treatment_ot').is(':checked')){              
        var fila11_ot=1;    
    }else{                
        fila11_ot=0;
    }
    if($('#scheduled_for_treatment_pt').is(':checked')){              
        var fila11_pt=1;    
    }else{                
        fila11_pt=0;
    }
    if($('#scheduled_for_treatment_st').is(':checked')){              
        var fila11_st=1;    
    }else{                
        fila11_st=0;
    }
    //////////////////////////////////////////////////////
    if($('#patients_on_hold_ot').is(':checked')){              
        var fila12_ot=1;    
    }else{                
        fila12_ot=0;
    }
    if($('#patients_on_hold_pt').is(':checked')){              
        var fila12_pt=1;    
    }else{                
        fila12_pt=0;
    }
    if($('#patients_on_hold_st').is(':checked')){              
        var fila12_st=1;    
    }else{                
        fila12_st=0;
    }
    
    //////////////////////////////////////////////////////
    if($('#progress_note_adults_ot').is(':checked')){              
        var fila13_ot=1;    
    }else{                
        fila13_ot=0;
    }
    if($('#progress_note_adults_pt').is(':checked')){              
        var fila13_pt=1;    
    }else{                
        fila13_pt=0;
    }
    if($('#progress_note_adults_st').is(':checked')){              
        var fila13_st=1;    
    }else{                
        fila13_st=0;
    }
    
    //////////////////////////////////////////////////////
    if($('#progress_note_pedriatics_ot').is(':checked')){              
        var fila14_ot=1;    
    }else{                
        fila14_ot=0;
    }
    if($('#progress_note_pedriatics_pt').is(':checked')){              
        var fila14_pt=1;    
    }else{                
        fila14_pt=0;
    }
    if($('#progress_note_pedriatics_st').is(':checked')){              
        var fila14_st=1;    
    }else{                
        fila14_st=0;
    }
    
    //////////////////////////////////////////////////////
    if($('#not_been_seen_ot').is(':checked')){              
        var fila15_ot=1;    
    }else{                
        fila15_ot=0;
    }
    if($('#not_been_seen_pt').is(':checked')){              
        var fila15_pt=1;    
    }else{                
        fila15_pt=0;
    }
    if($('#not_been_seen_st').is(':checked')){              
        var fila15_st=1;    
    }else{                
        fila15_st=0;
    }
    
    //////////////////////////////////////////////////////
    if($('#discharge_ot').is(':checked')){              
        var fila16_ot=1;    
    }else{                
        fila16_ot=0;
    }
    if($('#discharge_pt').is(':checked')){              
        var fila16_pt=1;    
    }else{                
        fila16_pt=0;
    }
    if($('#discharge_st').is(':checked')){              
        var fila16_st=1;    
    }else{                
        fila16_st=0;
    }
    
    
    var pat_id=$("#pat_id").val();
    $.ajax({

	        url: 'save.php',
                method: "POST",
	        data: {'fila1_ot':fila1_ot,'fila1_pt':fila1_pt,'fila1_st':fila1_st,
                       'fila2_ot':fila2_ot,'fila2_pt':fila2_pt,'fila2_st':fila2_st,
                       'fila3_ot':fila3_ot, 'fila3_pt':fila3_pt,'fila3_st':fila3_st,
                       'fila4_ot':fila4_ot, 'fila4_pt':fila4_pt,'fila4_st':fila4_st,
                       'fila5_ot':fila5_ot, 'fila5_pt':fila5_pt,'fila5_st':fila5_st,
                       'fila6_ot':fila6_ot, 'fila6_pt':fila6_pt,'fila6_st':fila6_st,
                       'fila7_ot':fila7_ot, 'fila7_pt':fila7_pt,'fila7_st':fila7_st,
                       'fila8_ot':fila8_ot, 'fila8_pt':fila8_pt,'fila8_st':fila8_st,
                       'fila9_ot':fila9_ot, 'fila9_pt':fila9_pt,'fila9_st':fila9_st,
                       'fila10_ot':fila10_ot, 'fila10_pt':fila10_pt,'fila10_st':fila10_st,
                       'fila11_ot':fila11_ot, 'fila11_pt':fila11_pt,'fila11_st':fila11_st,
                       'fila12_ot':fila12_ot, 'fila12_pt':fila12_pt,'fila12_st':fila12_st,
                       'fila13_ot':fila13_ot, 'fila13_pt':fila13_pt,'fila13_st':fila13_st,
                       'fila14_ot':fila14_ot, 'fila14_pt':fila14_pt,'fila14_st':fila14_st,
                       'fila15_ot':fila15_ot, 'fila15_pt':fila15_pt,'fila15_st':fila15_st,
                       'fila16_ot':fila16_ot, 'fila16_pt':fila16_pt,'fila16_st':fila16_st,'pat_id':pat_id},
	        //async:false,
	        dataType: "json",
                success: function(data){
                   alert(data.succes);
                   
                }
        });
}

////////////////// FUNCION PARA SOLO SELECCIONAR UNO EN REPORTE INTERFAZ OT HASTA FIRMA DEL DOCTOR/////////////////
$('input.example_ot').on('change', function() {
    $('input.example_ot').not(this).prop('checked', false);  
});
////////////////FUNCION PARA SOLO TENER PERDIR TX O ESPERANDO TX --- SOLO UNA DE LAS DOS/////////////////////////
$('input.example_ot_tx').on('change', function() {
    $('input.example_ot_tx').not(this).prop('checked', false);  
});
//grupo 3
function validar_grup_ot(data){
    
    switch(data) {
        case 1:
            if($('#request_auth_tx_ot').is(':checked')){              
            
                $('#waiting_auth_tx_ot').removeAttr('checked');                
                
            }else{
                
                $('#request_auth_tx_ot').removeAttr('checked');
            }
            break;
        case 2:
            if($('#waiting_auth_tx_ot').is(':checked')){               
            
                $('#request_auth_tx_ot').removeAttr('checked');                
                
            }else{
                
                $('#waiting_auth_tx_ot').removeAttr('checked');
            }
            break;
        
            
        
    } 
}
function validar_grup_pt(data){
    
    switch(data) {
        case 1:
            if($('#request_auth_tx_pt').is(':checked')){              
            
                $('#waiting_auth_tx_pt').removeAttr('checked');                
                
            }else{
                
                $('#request_auth_tx_pt').removeAttr('checked');
            }
            break;
        case 2:
            if($('#waiting_auth_tx_pt').is(':checked')){               
            
                $('#request_auth_tx_pt').removeAttr('checked');                
                
            }else{
                
                $('#waiting_auth_tx_pt').removeAttr('checked');
            }
            break;           
        
    } 
}
function validar_grup_st(data){
    
    switch(data) {
        case 1:
            if($('#request_auth_tx_st').is(':checked')){              
            
                $('#waiting_auth_tx_st').removeAttr('checked');                
                
            }else{
                
                $('#request_auth_tx_st').removeAttr('checked');
            }
            break;
        case 2:
            if($('#waiting_auth_tx_st').is(':checked')){               
            
                $('#request_auth_tx_st').removeAttr('checked');                
                
            }else{
                
                $('#waiting_auth_tx_st').removeAttr('checked');
            }
            break;           
        
    } 
}


//grupo 2
function validar_grup_ot1(data){
    
    switch(data) {
        case 1:
            if($('#ready_for_treatment_ot').is(':checked')){              
            
                $('#scheduled_for_treatment_ot').removeAttr('checked');                
                
            }else{
                
                $('#ready_for_treatment_ot').removeAttr('checked');
            }
            break;
        case 2:
            if($('#scheduled_for_treatment_ot').is(':checked')){               
            
                $('#ready_for_treatment_ot').removeAttr('checked');                
                
            }else{
                
                $('#scheduled_for_treatment_ot').removeAttr('checked');
            }
            break;
        
            
        
    } 
}
function validar_grup_pt1(data){
    
    switch(data) {
        case 1:
            if($('#ready_for_treatment_pt').is(':checked')){              
            
                $('#scheduled_for_treatment_pt').removeAttr('checked');                
                
            }else{
                
                $('#ready_for_treatment_pt').removeAttr('checked');
            }
            break;
        case 2:
            if($('#scheduled_for_treatment_pt').is(':checked')){               
            
                $('#ready_for_treatment_pt').removeAttr('checked');                
                
            }else{
                
                $('#scheduled_for_treatment_pt').removeAttr('checked');
            }
            break;           
        
    } 
}
function validar_grup_st1(data){
    
    switch(data) {
        case 1:
            if($('#ready_for_treatment_st').is(':checked')){              
            
                $('#scheduled_for_treatment_st').removeAttr('checked');                
                
            }else{
                
                $('#ready_for_treatment_st').removeAttr('checked');
            }
            break;
        case 2:
            if($('#scheduled_for_treatment_st').is(':checked')){               
            
                $('#ready_for_treatment_st').removeAttr('checked');                
                
            }else{
                
                $('#scheduled_for_treatment_st').removeAttr('checked');
            }
            break;           
        
    } 
}
//grupo 1
function validar_ot(data){
   
    switch(data) {
        case 1:
            if($('#rx_request_ot').is(':checked')){               
            
                $('#waiting_rx_ot').removeAttr('checked');
                $('#auth_eval_ot').removeAttr('checked');
                $('#waiting_eval_ot').removeAttr('checked');
                $('#eval_patient_ot').removeAttr('checked');
                $('#request_signature_ot').removeAttr('checked');
                $('#waiting_signature_ot').removeAttr('checked');
                
            }else{
                
                $('#waiting_rx_ot').removeAttr('checked');
            }
            break;
        case 2:
            if($('#waiting_rx_ot').is(':checked')){               
            
                $('#rx_request_ot').removeAttr('checked');
                $('#auth_eval_ot').removeAttr('checked');
                $('#waiting_eval_ot').removeAttr('checked');
                $('#eval_patient_ot').removeAttr('checked');
                $('#request_signature_ot').removeAttr('checked');
                $('#waiting_signature_ot').removeAttr('checked');
                
            }else{
                
                $('#waiting_rx_ot').removeAttr('checked');
            }
            break;
        case 3:
            if($('#auth_eval_ot').is(':checked')){               
            
                $('#waiting_rx_ot').removeAttr('checked');
                $('#rx_request_ot').removeAttr('checked');
                $('#waiting_eval_ot').removeAttr('checked');
                $('#eval_patient_ot').removeAttr('checked');
                $('#request_signature_ot').removeAttr('checked');
                $('#waiting_signature_ot').removeAttr('checked');
                
            }else{
                
                $('#auth_eval_ot').removeAttr('checked');
            }
            break;
        case 4:
            if($('#waiting_eval_ot').is(':checked')){               
            
                $('#waiting_rx_ot').removeAttr('checked');
                $('#auth_eval_ot').removeAttr('checked');
                $('#rx_request_ot').removeAttr('checked');
                $('#eval_patient_ot').removeAttr('checked');
                $('#request_signature_ot').removeAttr('checked');
                $('#waiting_signature_ot').removeAttr('checked');
                
            }else{
                
                $('#waiting_eval_ot').removeAttr('checked');
            }
            break;
        case 5:
            if($('#eval_patient_ot').is(':checked')){               
            
                $('#waiting_rx_ot').removeAttr('checked');
                $('#auth_eval_ot').removeAttr('checked');
                $('#waiting_eval_ot').removeAttr('checked');
                $('#rx_request_ot').removeAttr('checked');
                $('#request_signature_ot').removeAttr('checked');
                $('#waiting_signature_ot').removeAttr('checked');
                
            }else{
                
                $('#eval_patient_ot').removeAttr('checked');
            }
            break;
        case 6:
            if($('#request_signature_ot').is(':checked')){               
            
                $('#waiting_rx_ot').removeAttr('checked');
                $('#auth_eval_ot').removeAttr('checked');
                $('#waiting_eval_ot').removeAttr('checked');
                $('#eval_patient_ot').removeAttr('checked');
                $('#rx_request_ot').removeAttr('checked');
                $('#waiting_signature_ot').removeAttr('checked');
                
            }else{
                
                $('#request_signature_ot').removeAttr('checked');
            }
            break;
            
        case 7:
            if($('#waiting_signature_ot').is(':checked')){               
            
                $('#waiting_rx_ot').removeAttr('checked');
                $('#auth_eval_ot').removeAttr('checked');
                $('#waiting_eval_ot').removeAttr('checked');
                $('#eval_patient_ot').removeAttr('checked');
                $('#request_signature_ot').removeAttr('checked');
                $('#rx_request_ot').removeAttr('checked');
                
            }else{
                
                $('#waiting_signature_ot').removeAttr('checked');
            }
            break;
            
        
    } 
}

function validar_pt(data){
   
    switch(data) {
        case 1:
            if($('#rx_request_pt').is(':checked')){               
            
                $('#waiting_rx_pt').removeAttr('checked');
                $('#auth_eval_pt').removeAttr('checked');
                $('#waiting_eval_pt').removeAttr('checked');
                $('#eval_patient_pt').removeAttr('checked');
                $('#request_signature_pt').removeAttr('checked');
                $('#waiting_signature_pt').removeAttr('checked');
                
            }else{
                
                $('#waiting_rx_pt').removeAttr('checked');
            }
            break;
        case 2:
            if($('#waiting_rx_pt').is(':checked')){               
            
                $('#rx_request_pt').removeAttr('checked');
                $('#auth_eval_pt').removeAttr('checked');
                $('#waiting_eval_pt').removeAttr('checked');
                $('#eval_patient_pt').removeAttr('checked');
                $('#request_signature_pt').removeAttr('checked');
                $('#waiting_signature_pt').removeAttr('checked');
                
            }else{
                
                $('#waiting_rx_pt').removeAttr('checked');
            }
            break;
        case 3:
            if($('#auth_eval_pt').is(':checked')){               
            
                $('#waiting_rx_pt').removeAttr('checked');
                $('#rx_request_pt').removeAttr('checked');
                $('#waiting_eval_pt').removeAttr('checked');
                $('#eval_patient_pt').removeAttr('checked');
                $('#request_signature_pt').removeAttr('checked');
                $('#waiting_signature_pt').removeAttr('checked');
                
            }else{
                
                $('#auth_eval_pt').removeAttr('checked');
            }
            break;
        case 4:
            if($('#waiting_eval_pt').is(':checked')){               
            
                $('#waiting_rx_pt').removeAttr('checked');
                $('#auth_eval_pt').removeAttr('checked');
                $('#rx_request_pt').removeAttr('checked');
                $('#eval_patient_pt').removeAttr('checked');
                $('#request_signature_pt').removeAttr('checked');
                $('#waiting_signature_pt').removeAttr('checked');
                
            }else{
                
                $('#waiting_eval_pt').removeAttr('checked');
            }
            break;
        case 5:
            if($('#eval_patient_pt').is(':checked')){               
            
                $('#waiting_rx_pt').removeAttr('checked');
                $('#auth_eval_pt').removeAttr('checked');
                $('#waiting_eval_pt').removeAttr('checked');
                $('#rx_request_pt').removeAttr('checked');
                $('#request_signature_pt').removeAttr('checked');
                $('#waiting_signature_pt').removeAttr('checked');
                
            }else{
                
                $('#eval_patient_pt').removeAttr('checked');
            }
            break;
        case 6:
            if($('#request_signature_pt').is(':checked')){               
            
                $('#waiting_rx_pt').removeAttr('checked');
                $('#auth_eval_pt').removeAttr('checked');
                $('#waiting_eval_pt').removeAttr('checked');
                $('#eval_patient_pt').removeAttr('checked');
                $('#rx_request_pt').removeAttr('checked');
                $('#waiting_signature_pt').removeAttr('checked');
                
            }else{
                
                $('#request_signature_pt').removeAttr('checked');
            }
            break;
            
        case 7:
            if($('#waiting_signature_pt').is(':checked')){               
            
                $('#waiting_rx_pt').removeAttr('checked');
                $('#auth_eval_pt').removeAttr('checked');
                $('#waiting_eval_pt').removeAttr('checked');
                $('#eval_patient_pt').removeAttr('checked');
                $('#request_signature_pt').removeAttr('checked');
                $('#rx_request_pt').removeAttr('checked');
                
            }else{
                
                $('#waiting_signature_pt').removeAttr('checked');
            }
            break;
            
        
    } 
}

function validar_st(data){
   
    switch(data) {
        case 1:
            if($('#rx_request_st').is(':checked')){               
            
                $('#waiting_rx_st').removeAttr('checked');
                $('#auth_eval_st').removeAttr('checked');
                $('#waiting_eval_st').removeAttr('checked');
                $('#eval_patient_st').removeAttr('checked');
                $('#request_signature_st').removeAttr('checked');
                $('#waiting_signature_st').removeAttr('checked');
                
            }else{
                
                $('#rx_request_st').removeAttr('checked');
            }
            break;
        case 2:
            if($('#waiting_rx_st').is(':checked')){               
            
                $('#rx_request_st').removeAttr('checked');
                $('#auth_eval_st').removeAttr('checked');
                $('#waiting_eval_st').removeAttr('checked');
                $('#eval_patient_st').removeAttr('checked');
                $('#request_signature_st').removeAttr('checked');
                $('#waiting_signature_st').removeAttr('checked');
                
            }else{
                
                $('#waiting_rx_st').removeAttr('checked');
            }
            break;
        case 3:
            if($('#auth_eval_st').is(':checked')){               
            
                $('#waiting_rx_st').removeAttr('checked');
                $('#rx_request_st').removeAttr('checked');
                $('#waiting_eval_st').removeAttr('checked');
                $('#eval_patient_st').removeAttr('checked');
                $('#request_signature_st').removeAttr('checked');
                $('#waiting_signature_st').removeAttr('checked');
                
            }else{
                
                $('#auth_eval_st').removeAttr('checked');
            }
            break;
        case 4:
            if($('#waiting_eval_st').is(':checked')){               
            
                $('#waiting_rx_st').removeAttr('checked');
                $('#auth_eval_st').removeAttr('checked');
                $('#rx_request_st').removeAttr('checked');
                $('#eval_patient_st').removeAttr('checked');
                $('#request_signature_st').removeAttr('checked');
                $('#waiting_signature_st').removeAttr('checked');
                
            }else{
                
                $('#waiting_eval_st').removeAttr('checked');
            }
            break;
        case 5:
            if($('#eval_patient_st').is(':checked')){               
            
                $('#waiting_rx_st').removeAttr('checked');
                $('#auth_eval_st').removeAttr('checked');
                $('#waiting_eval_st').removeAttr('checked');
                $('#rx_request_st').removeAttr('checked');
                $('#request_signature_st').removeAttr('checked');
                $('#waiting_signature_st').removeAttr('checked');
                
            }else{
                
                $('#eval_patient_st').removeAttr('checked');
            }
            break;
        case 6:
            if($('#request_signature_st').is(':checked')){               
            
                $('#waiting_rx_st').removeAttr('checked');
                $('#auth_eval_st').removeAttr('checked');
                $('#waiting_eval_st').removeAttr('checked');
                $('#eval_patient_st').removeAttr('checked');
                $('#rx_request_st').removeAttr('checked');
                $('#waiting_signature_st').removeAttr('checked');
                
            }else{
                
                $('#request_signature_st').removeAttr('checked');
            }
            break;
            
        case 7:
            if($('#waiting_signature_st').is(':checked')){               
            
                $('#waiting_rx_st').removeAttr('checked');
                $('#auth_eval_st').removeAttr('checked');
                $('#waiting_eval_st').removeAttr('checked');
                $('#eval_patient_st').removeAttr('checked');
                $('#request_signature_st').removeAttr('checked');
                $('#rx_request_st').removeAttr('checked');
                
            }else{
                
                $('#waiting_signature_st').removeAttr('checked');
            }
            break;
            
        
    } 
}

</script>











</html>
