<?php
error_reporting(0);
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}/*else{
	if($_SESSION['user_type'] == 1 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'ACCESS DENIED\')</script>';
		echo '<script>window.location="../home/home.php";</script>';
	}
}*/

if(isset($_POST['typeAge'])){
    $typeAge = $_POST['typeAge'];    
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
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
    <link rel="stylesheet" href="../../../css/bootstrap-theme.min.css" type='text/css'>
    <link rel="stylesheet" href="../../../css/animate.min.css" type='text/css'>
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/devoops_ext.js"></script>





<link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
<link href="../../../css/portfolio-item.css" rel="stylesheet">
<script language="JavaScript" type="text/javascript" src="../../../js/AjaxConn.js"></script>

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


<!-- Style Bootstrap
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>

    <!-- CSS 
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css"> 

<!-- All functions for this theme + document.ready processing -->
<script src="js/devoops.js"></script>

<script  language="JavaScript" type="text/javascript">
                                      
                $(document).ready(function() {
                         $('#report_amount').dataTable( {                               
                                 dom: 'Bfrtip',
                                 "scrollX": true,
                                
                         } );
                 } );           
    
        </script>









<style>
 table {
 border-spacing: .9rem;
}
td {
 font-size: 12pt;
 font-weight: bold;
 padding: 0.4rem;
}

table , td, th {
  border: 3.6px solid #FF8C00;
  padding: 0.3rem;
  font-weight: bold;
 height: 0px;
}

tr {
    height: 0px;
}



td:nth-child(1) { height: 0px; font-size: 10pt; color:#000000; background: hsl(170, 70%, 50%); }
td:nth-child(2) { height: 0px; background: hsl(180, 80%, 50%); }
td:nth-child(3) { height: 0px; background: hsl(190, 90%, 50%); }
td:nth-child(4) { height: 0px; background: hsl(200, 99%, 50%); }
</style>




<style type="text/css">
    .bs-example{
    	margin: 13px;
    }
</style>
<script>
	function abrirVentana(discipline,cpt,type){		
		window.open('windows_report_amount.php?discipline='+discipline+'&cpt='+cpt+'&type='+type+);		
	}
	function filtrarTypeAge(value){
            document.getElementById("myForm").submit();
        }
	
</script>
</head>
<body >

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../../vista/nav_bar/nav_bar.php"; ?>
    <br>
<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">

    
<!-- 
   <div  >

        

            <div class="col-md-8" align="center"  >

                
    <table class="table table-striped table-bordered" align="center">
        <thead>
            <tr>
               <td colspan="5" align="center">
                    <form id="myForm" action="report_amount.php" method="post">
                    <label style="font-size: 17pt;" >Type Age:</label>                    
                    <select id="typeAge" name="typeAge" class="populate placeholder" onchange="filtrarTypeAge(this.value);">
                        <option value="all" <?php echo ($typeAge=='all')?'selected':''; ?>>---ALL---</option>
                        <option value="adults" <?php echo ($typeAge=='adults')?'selected':''; ?>>ADULTS</option>
                        <option value="pedriatics" <?php echo ($typeAge=='pedriatics')?'selected':''; ?>>PEDRIATICS</option>
                    </select>
                    </form>
                </td>
            </tr>
<tr>-->
<?php
/*
if($_POST['typeAge'] == 'adults'){
 echo '<h1 align="center" style="font-family:Comic Sans MS;color:#000033;" class="animated wobble">ADULTS</h1>';
}else{
    if($_POST['typeAge'] == 'pedriatics'){
      echo '<h1 align="center" style="font-family:Comic Sans MS;color:#000033;" class="animated flip">PEDRIATICS</h1>';
    }
    	if($_POST['typeAge'] == 'all'){
       	echo '<h1 align="center" style="font-family:Comic Sans MS;color:#000033;" class="animated infinite bounce">ALL</h1>';
   				 }
}
*/

?><!--
</tr>
</thead>
</table>
</div> -->


 <div     align="center"> 
<table  id="table"  class="table table-hovered" style="width: 50%;" align="center">
        <thead>
            <tr>
                <th style="font-size: 17pt; text-align: center;" align="center">R E P O R T</th>                
                <th style="font-size: 17pt; text-align: center;" align="center">OT</th>
                <th style="font-size: 17pt; text-align: center;" align="center">PT</th>
                <th style="font-size: 17pt; text-align: center;" align="center">ST</th>
            </tr>
        </thead>
        <tbody>
            
            
            <!--  LAS COSAS QUE EN PANTALLA APARECEN UNA VEZ NO DEBEN ESTAR DENTRO DE UN WHILE -->
            
            <?php            	  	
		$conexion = conectar();     
                $while_reportes = "SELECT * FROM tbl_type_report WHERE field_patient_copy IS NOT NULL order by `order`  ;";
                $result_while_reportes = ejecutar($while_reportes,$conexion);                
                $b = 0;      
                while($datos_reportes = mysqli_fetch_assoc($result_while_reportes)) {
                    echo '<tr style="height:0px;" ><td>'.$datos_reportes['name_view_report_amount'].'</td>';
                    $query_disciplines = " SELECT Name ,discipline.`order` FROM discipline where DisciplineId='2' or DisciplineId='3' or DisciplineId='1' order by `order` asc   ;";
                    $result_discipline = ejecutar($query_disciplines,$conexion);                                       
                    while($datos_discipline = mysqli_fetch_assoc($result_discipline)) {                        
                        if($datos_reportes['field_patient_copy'] == 'prescription_'){
                            $resultado_update = update_report($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion);
                            $resultado_update_quitar = update_report_quitar($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion);
                        }
                        if($datos_reportes['field_patient_copy'] == 'hold_'){
                            $resultado_update_put_hold  = update_poner_hold($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion);
                            $resultado_update_quit_hold = update_quitar_hold($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion);
                        }
                        if($datos_reportes['field_patient_copy'] == 'progress_adults_'){
                            $resultado_update_notes_adults_put = update_notes_adults_put($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion); 
                            $resultado_update_notes_adults_quitar = update_notes_adults_quitar($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion); 
                        }
                        if($datos_reportes['field_patient_copy'] == 'progress_pedriatics_'){
                            $resultado_update_notes_ninos_put = update_notes_ninos_put($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion); 
                            $resultado_update_notes_ninos_quitar = update_notes_ninos_quitar($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion); 
                        }
                        if($datos_reportes['field_patient_copy'] == 'tx_auth_'){
                            $resultado_update_auth_tx = update_auth_tx($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion); 
                            $resultado_update_auth_tx_quitar = update_auth_tx_quitar($datos_reportes['field_patient_copy'],$datos_discipline['Name'],$conexion);
                        }


             $query_count= " SELECT COUNT(patients_copy.id) AS ".$datos_discipline['Name']." 
                            from patients_copy 
                            join patients on patients.id=patients_copy.id and patients.active='1'
                            where ".$datos_reportes['field_patient_copy'].$datos_discipline['Name']."='1';";
                      $result_count = ejecutar($query_count,$conexion);				                       
                        while($datos_count = mysqli_fetch_assoc($result_count)) { 
                            if($datos_discipline['Name'] == 'OT')$cpt_code = '97530';
                            if($datos_discipline['Name'] == 'PT')$cpt_code = '97110';
                            if($datos_discipline['Name'] == 'ST')$cpt_code = '92507';
                          
                         
                        echo '<td style="font-weight: bold; height: 0px;color:#000000" align="center"><a style="font-weight: bold;height: 0px; color:#000000" onclick="abrirVentana(\''.$datos_discipline['Name'].'\','.$cpt_code.',\''.$datos_reportes['name'].'\')" target="_blank">'.$datos_count[$datos_discipline['Name']].'</a></td>';
                        }
                    }
                    echo '<tr>';
                }
                
                
                
                function update_report($type_report, $discipline,$conexion){
                   $query_update_report_prescription = " 
                        SELECT patients.id 
                        from  patients
                        join seguros on seguros.insurance=patients.Pri_Ins
                        join tbl_seguros_table st on seguros.ID=st.id_seguros
                            and st.id_seguros_type_person=patients.id_seguros_type_person
                            and st.id_seguros_type_task='1'
                            and st.discipline='".$discipline."'
                        join tbl_seguros_prescription_cp_days cp on cp.id_seguros_table=st.id_seguros_table
                        join careplans on patients.Pat_id=careplans.Patient_ID
                            and	careplans.`status`=1
                            and careplans.Discipline='".$discipline."'				
                        join patients_copy pc on pc.Pat_id=patients.Pat_id
                        LEFT  JOIN prescription p ON p.Patient_ID=patients.Pat_id
                        AND p.Discipline='".$discipline."'
                        and p.`status`='1'
                        where true
                        and patients.active=1
                        and p.Patient_ID is null
                        and datediff (careplans.POC_due,date(now())) < cp.cp_days_left
                       # and pc.prescription_".$discipline."=0
                        and pc.waiting_prescription_".$discipline."=0
                        and pc.eval_auth_".$discipline."=0
                        and pc.waiting_auth_eval_".$discipline."=0
                        and pc.eval_patient_".$discipline."=0
                        and pc.doctor_signature_".$discipline."=0
                        and pc.waiting_signature_".$discipline."=0
                        and ready_treatment_".$discipline."=0 ;"
                        ;
						
                    $resultado = ejecutar($query_update_report_prescription,$conexion);
                    $reporte = array();
                    $i = 0;      
                    while($datos = mysqli_fetch_assoc($resultado)) {            
                        $reporte[$i] = $datos;
                        $update_reportes = "UPDATE patients_copy SET ".$type_report.$discipline." = '1' where  patients_copy.id= '".$reporte[$i]['id']."' ;";
                        $resultado_update= ejecutar($update_reportes,$conexion);
                        $i++;
                    }
                    return $resultado_update;
                }





   function update_report_quitar($type_report, $discipline,$conexion){
                   $query_update_report_prescription_quitar = " 
                        SELECT patients.id 
                        from  patients
                        join seguros on seguros.insurance=patients.Pri_Ins
                        join tbl_seguros_table st on seguros.ID=st.id_seguros
                            and st.id_seguros_type_person=patients.id_seguros_type_person
                            and st.id_seguros_type_task='1'
                            and st.discipline='".$discipline."'
                        join tbl_seguros_prescription_cp_days cp on cp.id_seguros_table=st.id_seguros_table
                        join careplans on patients.Pat_id=careplans.Patient_ID
                            and careplans.`status`=1
                            and careplans.Discipline='".$discipline."'              
                        join patients_copy pc on pc.Pat_id=patients.Pat_id
                        where true
                        and patients.active=1
                       # and p.Patient_ID is null
                        AND (datediff (careplans.POC_due,date(now())) > cp.cp_days_left
                        OR pc.prescription_".$discipline."=1
                        OR pc.waiting_prescription_".$discipline."=1
                        OR pc.eval_auth_".$discipline."=1
                        OR pc.waiting_auth_eval_".$discipline."=1
                        OR pc.eval_patient_".$discipline."=1
                        OR pc.doctor_signature_".$discipline."=1
                        OR pc.waiting_signature_".$discipline."=1
                        OR patients.Pat_id IN (SELECT p.Patient_ID from prescription p WHERE TRUE AND p.Discipline='".$discipline."' AND  p.`status`='1')
                          )     ;
                       " ;
                        
                    $resultado_quitar = ejecutar($query_update_report_prescription_quitar,$conexion);
                    $reporte_quitar = array();
                    $l = 0;      
                    while($datos_quitar = mysqli_fetch_assoc($resultado_quitar)) {            
                        $reporte_quitar[$i] = $datos_quitar;
                        $update_reportes_quitar = "UPDATE patients_copy SET ".$type_report.$discipline." = '0' where  patients_copy.id= '".$reporte_quitar[$l]['id']."' ;";
                        $resultado_update_quitar= ejecutar($update_reportes_quitar,$conexion);
                        $l++;
                    }
                    return $resultado_update_quitar;
                }



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////             QUERYS ON HOLD REPORT                     ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function update_poner_hold($type_report, $discipline,$conexion){
$poner_hold = "
SELECT patients.id
         FROM patients
left join insurance i on i.Pat_id=patients.Pat_id
       and patients.active=1        
join seguros on seguros.insurance=patients.Pri_Ins

join tbl_seguros_table st on seguros.ID=st.id_seguros
      and st.id_seguros_type_person=patients.id_seguros_type_person
      and st.discipline='".$discipline."'
JOIN patients_copy pc on pc.Pat_id=patients.Pat_id
LEFT JOIN careplans c on patients.Pat_id=c.Patient_ID
            and patients.active=1
LEFT JOIN cpt on i.CPT=cpt.cpt
                    and i.Discipline=cpt.Discipline
                                 
where 
(     st.id_seguros_type_task='4' and i.Discipline='".$discipline."' and 
        cpt.type='TX'  and i.status=1 
      and  (date(now())>i.Auth_thru   or i.Visits_remen<1   )                   )
OR
(date(now())>c.POC_due  and c.Discipline='".$discipline."'       and c.status='1'
     )  
     
   group by patients.id                 ;
";

        
         $result_poner_hold = ejecutar($poner_hold,$conexion);
                    $reporte_poner_hold = array();
                    $a = 0;      
                    while($datos_poner_hold = mysqli_fetch_assoc($result_poner_hold)) {            
                        $reporte_poner_hold[$a] = $datos_poner_hold;
                       $update_put_hold = "UPDATE patients_copy SET ".$type_report.$discipline." = '1', scheduled_".$discipline."='0' where  patients_copy.id= '".$reporte_poner_hold[$a]['id']."' ;";
                        $resultado_update_put_hold= ejecutar($update_put_hold,$conexion);
                        $a++;
                    }
                    return $resultado_update_put_hold;

    }

function update_quitar_hold($type_report, $discipline,$conexion){
$quitar_hold = "
SELECT patients.id
         FROM patients
left join insurance i on i.Pat_id=patients.Pat_id
       and patients.active=1        
join seguros on seguros.insurance=patients.Pri_Ins

join tbl_seguros_table st on seguros.ID=st.id_seguros
      and st.id_seguros_type_person=patients.id_seguros_type_person
      and st.discipline='".$discipline."'
JOIN patients_copy pc on pc.Pat_id=patients.Pat_id
LEFT JOIN careplans c on patients.Pat_id=c.Patient_ID
            and patients.active=1
LEFT JOIN cpt on i.CPT=cpt.cpt
                    and i.Discipline=cpt.Discipline
                                 
where 
(     st.id_seguros_type_task='4' and i.Discipline='".$discipline."' and 
        cpt.type='TX'  and i.status=1 
      and  date(now())<=i.Auth_thru   
      and  i.Visits_remen>0                      )
AND
(date(now())<=c.POC_due  and c.Discipline='".$discipline."'       and c.status='1'
     )  
     
   group by patients.id             ;
";

        
         $result_quitar_hold = ejecutar($quitar_hold,$conexion);
                    $reporte_quitar_hold = array();
                    $b = 0;      
                    while($datos_quitar_hold = mysqli_fetch_assoc($result_quitar_hold)) {            
                        $reporte_quitar_hold[$b] = $datos_quitar_hold;
                        $update_quit_hold = "UPDATE patients_copy SET ".$type_report.$discipline." = '0' where  patients_copy.id= '".$reporte_quitar_hold[$b]['id']."' ;";
                        $resultado_update_quit_hold= ejecutar($update_quit_hold,$conexion);
                        $b++;
                    }
                    return $resultado_update_quit_hold;

    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////        QUERYS   PROGRESS NOTES ADULTS                 ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function update_notes_adults_put($type_report, $discipline,$conexion){
   $notes_adults_put = "
    SELECT  count(distinct campo_3) as subcount, patients.id, sp.visits 
                        FROM patients
        JOIN tbl_treatments on patients.Pat_id=tbl_treatments.campo_5       
            JOIN cpt on tbl_treatments.campo_11=cpt.cpt
                and tbl_treatments.campo_10=cpt.Discipline
                JOIN seguros on seguros.insurance=patients.Pri_Ins
                    JOIN tbl_seguros_table st on seguros.ID=st.id_seguros
                        and st.id_seguros_type_person=patients.id_seguros_type_person
                           
                                JOIN tbl_seguros_progress sp ON     sp.id_seguros_table=st.id_seguros_table
        WHERE
 patients.active=1
 and patients.id_seguros_type_person='2'
 and patients.active=1
 and type='TX'
 and tbl_treatments.adults_progress_notes=0
  and st.discipline='".$discipline."' 
  and tbl_treatments.campo_10='".$discipline."'
 group by Pat_id
 having subcount >=visits                 ;
";
       
         $result_notes_adult = ejecutar($notes_adults_put,$conexion);
                    $reporte_notes_adults_put = array();
                    $c = 0;      
                    while($datos_notes_adults_put = mysqli_fetch_assoc($result_notes_adult)) {            
                        $reporte_notes_adults_put[$c] = $datos_notes_adults_put;
                   $update_notes_adults_put  = "UPDATE patients_copy SET ".$type_report.$discipline." = '1' where  patients_copy.id= '".$reporte_notes_adults_put[$c]['id']."' ;";
                        $resultado_update_notes_adults_put = ejecutar($update_notes_adults_put ,$conexion);
                        $c++;
                    }
                    return $resultado_update_notes_adults_put ;

    }



function update_notes_adults_quitar($type_report, $discipline,$conexion){
  $notes_adults_quitar = "
    SELECT  count(distinct campo_3) as subcount, patients.id, sp.visits 
                        FROM patients
        JOIN tbl_treatments on patients.Pat_id=tbl_treatments.campo_5       
            JOIN cpt on tbl_treatments.campo_11=cpt.cpt
                and tbl_treatments.campo_10=cpt.Discipline
                JOIN seguros on seguros.insurance=patients.Pri_Ins
                    JOIN tbl_seguros_table st on seguros.ID=st.id_seguros
                        and st.id_seguros_type_person=patients.id_seguros_type_person
                           
                                JOIN tbl_seguros_progress sp ON     sp.id_seguros_table=st.id_seguros_table
        WHERE
 patients.active=1
 and patients.id_seguros_type_person='2'
 and patients.active=1
 and type='TX'
 and tbl_treatments.adults_progress_notes=0
  and st.discipline='".$discipline."' 
  and tbl_treatments.campo_10='".$discipline."'
 group by Pat_id
 having subcount <visits      ;
";
       
         $result_notes_adult = ejecutar($notes_adults_quitar,$conexion);
                    $reporte_notes_adults_quitar = array();
                    $e = 0;      
                    while($datos_notes_adults_quitar = mysqli_fetch_assoc($result_notes_adult)) {            
                        $reporte_notes_adults_quitar[$e] = $datos_notes_adults_quitar;
                    $update_notes_adults_quitar  = "UPDATE patients_copy SET ".$type_report.$discipline." = '0' where  patients_copy.id= '".$reporte_notes_adults_quitar[$e]['id']."' ;";
                        $resultado_update_notes_adults_quitar = ejecutar($update_notes_adults_quitar ,$conexion);
                        $e++;
                    }
                    return $resultado_update_notes_adults_quitar ;

    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////        QUERYS   PROGRESS NOTES PEDRIATICS NINOS              ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function update_notes_ninos_put($type_report, $discipline,$conexion){
  $notes_ninos_put = "
    SELECT  count(distinct campo_3) as subcount, patients.id, sp.visits 
                        FROM patients
        JOIN tbl_treatments on patients.Pat_id=tbl_treatments.campo_5       
            JOIN cpt on tbl_treatments.campo_11=cpt.cpt
                and tbl_treatments.campo_10=cpt.Discipline
                JOIN seguros on seguros.insurance=patients.Pri_Ins
                    JOIN tbl_seguros_table st on seguros.ID=st.id_seguros
                        and st.id_seguros_type_person=patients.id_seguros_type_person
                           
                                JOIN tbl_seguros_progress sp ON     sp.id_seguros_table=st.id_seguros_table
        WHERE
 patients.active=1
 and patients.id_seguros_type_person='1'
 and patients.active=1
 and type='TX'
 and tbl_treatments.pedriatics_progress_notes=0
  and st.discipline='".$discipline."' 
  and tbl_treatments.campo_10='".$discipline."'
 group by Pat_id
 having subcount >=visits     ;
";
       
         $result_notes_ninos = ejecutar($notes_ninos_put,$conexion);
                    $reporte_notes_ninos_put = array();
                    $d = 0;      
                    while($datos_notes_ninos_put = mysqli_fetch_assoc($result_notes_ninos)) {            
                        $reporte_notes_ninos_put[$d] = $datos_notes_ninos_put;
                      $update_notes_ninos_put  = "UPDATE patients_copy SET ".$type_report.$discipline." = '1' where  patients_copy.id= '".$reporte_notes_ninos_put[$d]['id']."' ;";
                       $resultado_update_notes_ninos_put = ejecutar($update_notes_ninos_put ,$conexion);
                        $d++;
                    }
                    return $resultado_update_notes_ninos_put ;

    }


function update_notes_ninos_quitar($type_report, $discipline,$conexion){
  $notes_ninos_quitar = "
    SELECT  count(distinct campo_3) as subcount, patients.id, sp.visits 
                        FROM patients
        JOIN tbl_treatments on patients.Pat_id=tbl_treatments.campo_5       
            JOIN cpt on tbl_treatments.campo_11=cpt.cpt
                and tbl_treatments.campo_10=cpt.Discipline
                JOIN seguros on seguros.insurance=patients.Pri_Ins
                    JOIN tbl_seguros_table st on seguros.ID=st.id_seguros
                        and st.id_seguros_type_person=patients.id_seguros_type_person
                           
                                JOIN tbl_seguros_progress sp ON     sp.id_seguros_table=st.id_seguros_table
        WHERE
 patients.active=1
 and patients.id_seguros_type_person='1'
 and patients.active=1
 and type='TX'
 and tbl_treatments.pedriatics_progress_notes=0
  and st.discipline='".$discipline."' 
  and tbl_treatments.campo_10='".$discipline."'
 group by Pat_id
 having subcount < visits      ;
";
       
         $result_notes_ninos = ejecutar($notes_ninos_quitar,$conexion);
                    $reporte_notes_ninos_quitar = array();
                    $f = 0;      
                    while($datos_notes_ninos_quitar = mysqli_fetch_assoc($result_notes_ninos)) {            
                        $reporte_notes_ninos_quitar[$f] = $datos_notes_ninos_quitar;
                      $update_notes_ninos_quitar  = "UPDATE patients_copy SET ".$type_report.$discipline." = '0' where  patients_copy.id= '".$reporte_notes_ninos_quitar[$f]['id']."' ;";
                        $resultado_update_notes_ninos_quitar = ejecutar($update_notes_ninos_quitar ,$conexion);
                        $f++;
                    }
                    return $resultado_update_notes_ninos_quitar ;

    }




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////        QUERYS   AUTH TX  WITH VALID POC , UPDATE REUQEST TX =1           ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function update_auth_tx($type_report, $discipline,$conexion){

 $update_auth_tx = "
 SELECT patients.id, patients.Last_name,patients.First_name, patients.Pat_id , careplans.POC_due,
    insurance.CPT , insurance.Discipline, insurance.Auth_thru, insurance.Visits_remen
         FROM  patients
      JOIN patients_copy pc ON patients.id = pc.id
      JOIN seguros on seguros.insurance=patients.Pri_Ins
      JOIN tbl_seguros_table st on seguros.ID=st.id_seguros
         and st.id_seguros_type_person=patients.id_seguros_type_person
         and st.id_seguros_type_task='4'
         and st.discipline='".$discipline."'
      JOIN tbl_seguros_auth_treat_auth_days tad ON st.id_seguros_table=tad.id_seguros_table
      JOIN tbl_seguros_auth_treat_visit_remain tvr ON st.id_seguros_table=tvr.id_seguros_table
      JOIN careplans on patients.Pat_id=careplans.Patient_ID
         and    careplans.status=1
         and careplans.Discipline='".$discipline."'          
        JOIN insurance ON insurance.Pat_id=patients.Pat_id
            and insurance.`status`=1
            and insurance.Discipline='".$discipline."'   
        JOIN cpt ON cpt.cpt=insurance.CPT
            and cpt.Discipline='".$discipline."'
            and cpt.`type`='TX'       
WHERE true
   AND patients.active=1
   AND pc.tx_auth_".$discipline."=0
        AND pc.waiting_tx_auth_".$discipline."=0
            AND   DATEDIFF(careplans.POC_due,date(now())) > '20'
                        AND  (insurance.Visits_remen <= tvr.visit_remain
                     OR DATEDIFF(insurance.Auth_thru,date(now())) <= tad.auth_days_left   )
GROUP BY patients.id    ; ";

                    $result_auth_tx = ejecutar($update_auth_tx,$conexion);
                    $reporte_auth_tx = array();
                    $g = 0;      
                    while($datos_auth_tx = mysqli_fetch_assoc($result_auth_tx)) {            
                        $reporte_auth_tx[$g] = $datos_auth_tx;
                     $update_auth_tx_poner  = "UPDATE patients_copy SET ".$type_report.$discipline." = '1' where  patients_copy.id= '".$reporte_auth_tx[$g]['id']."' ;";
                        $resultado_update_auth_tx_poner = ejecutar($update_auth_tx_poner ,$conexion);
                        $g++;
                    }
                    return $resultado_update_auth_tx_poner ;

    }


function update_auth_tx_quitar($type_report, $discipline,$conexion){

 $update_auth_tx_quitar = "
 SELECT patients.id, patients.Last_name,patients.First_name, patients.Pat_id , careplans.POC_due,
    insurance.CPT , insurance.Discipline, insurance.Auth_thru, insurance.Visits_remen
         FROM  patients
      JOIN patients_copy pc ON patients.id = pc.id
      JOIN seguros on seguros.insurance=patients.Pri_Ins
      JOIN tbl_seguros_table st on seguros.ID=st.id_seguros
         and st.id_seguros_type_person=patients.id_seguros_type_person
         and st.id_seguros_type_task='4'
         and st.discipline='".$discipline."'
      JOIN tbl_seguros_auth_treat_auth_days tad ON st.id_seguros_table=tad.id_seguros_table
      JOIN tbl_seguros_auth_treat_visit_remain tvr ON st.id_seguros_table=tvr.id_seguros_table
      JOIN careplans on patients.Pat_id=careplans.Patient_ID
         and    careplans.status=1
         and careplans.Discipline='".$discipline."'          
        JOIN insurance ON insurance.Pat_id=patients.Pat_id
            and insurance.`status`=1
            and insurance.Discipline='".$discipline."'   
        JOIN cpt ON cpt.cpt=insurance.CPT
            and cpt.Discipline='".$discipline."'
            and cpt.`type`='TX'       
WHERE true
   AND patients.active=1
   #AND pc.tx_auth_".$discipline."=0
       # AND pc.waiting_tx_auth_".$discipline."=0
            AND   DATEDIFF(careplans.POC_due,date(now())) > '20'
                        AND  (insurance.Visits_remen > tvr.visit_remain
                     AND DATEDIFF(insurance.Auth_thru,date(now())) > tad.auth_days_left   )
GROUP BY patients.id   ;  ";

$result_auth_tx_quitar = ejecutar($update_auth_tx_quitar,$conexion);
                    $reporte_auth_tx_quitar = array();
                    $g = 0;      
                    while($datos_auth_tx_quitar = mysqli_fetch_assoc($result_auth_tx_quitar)) {            
                        $reporte_auth_tx_quitar[$g] = $datos_auth_tx_quitar;
                     $update_auth_tx_quitar  = "UPDATE patients_copy SET ".$type_report.$discipline." = '0', waiting_old_tx_auth_".$discipline."='0' where  patients_copy.id= '".$reporte_auth_tx_quitar[$g]['id']."' ;";
                        $resultado_update_auth_tx_quitar = ejecutar($update_auth_tx_quitar ,$conexion);
                        $g++;
                    }
                    return $resultado_update_auth_tx_quitar ;

    }





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////   /////// FIN DE LOS QUERYS   //////////      ///////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    


            ?>
           





        </tbody>
    </table>
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

   
    <!-- /.container -->
</body>

<script type="text/javascript">
// Run Select2 plugin on elements
function DemoSelect2(){
	$('#typeAge').select2();
	
}
// Run timepicker

$(document).ready(function() {	
	$('.form-control').tooltip();
	LoadSelect2ScriptExt(DemoSelect2);
	
});
</script>

</html>
