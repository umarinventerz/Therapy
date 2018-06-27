<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 1 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'ACCESS DENIED\')</script>';
		echo '<script>window.location="../home/home.php";</script>';
	}
}

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

<!-- All functions for this theme + document.ready processing -->
<script src="js/devoops.js"></script>
















<style type="text/css">
    .bs-example{
    	margin: 20px;
    }
</style>
<script>
	function abrirVentana(discipline,cpt,type){		
		window.open('windows_report_amount.php?discipline='+discipline+'&cpt='+cpt+'&type='+type+"&typeAge="+$("#typeAge").val(),'','width=2500px,height=700px,noresize');		
	}
	function filtrarTypeAge(value){
            document.getElementById("myForm").submit();
        }
	
</script>
</head>
<body >

    <!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../../vista/nav_bar/nav_bar.php"; ?>
    <br><br>
<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">

    

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
<tr>
<?php

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


?>
</tr>
            <tr>
                <th>&nbsp;</th>                
                <th style="font-size: 17pt; text-align: center;" align="center">OT</th>
                <th style="font-size: 17pt; text-align: center;" align="center">PT</th>
                <th style="font-size: 17pt; text-align: center;" align="center">ST</th>
            </tr>
        </thead>
        <tbody>
            <?php
            	$conexion = conectar();   	
				
	
$having = "";
if($_POST['typeAge'] == 'adults'){
 $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_POST['typeAge'] == 'pedriatics'){
       $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    	if($_POST['typeAge'] == 'all'){
       	$having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
   				 }
}
	
	
	
		
	 $query3 = " 
select (	select  coaleSce(sum(subcount),0)
   			 from ( select   count(distinct careplans.Patient_ID) as subcount ,patients.DOB
   			  from careplans 
   			  join patients on careplans.Patient_ID=patients.Pat_id
				and patients.active=1
 where 
			 careplans.status=1 
			 and patients.active=1
			 AND careplans.mail_sent=0
			 and careplans.Discipline='OT' 
			  #and careplans.POC_due>=date(now()) 
			 ".$having."
			and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
			 and	datediff (careplans.POC_due,date(now()))<=45 
 			and
			(
careplans.Patient_ID not  in (select prescription.Patient_ID from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date)	and 
prescription.Discipline='OT' 
 and prescription.status=1
 #and prescription.Eval_done=0
 )
) 


							) t	) as OT ,
							
	(	select  coaleSce(sum(subcount),0)
   			 from ( select   count(distinct careplans.Patient_ID) as subcount ,patients.DOB

   			 from careplans 
   			 join patients on careplans.Patient_ID=patients.Pat_id
				  and patients.active=1
	
 where 
			 careplans.status=1 
			 and patients.active=1
			 AND careplans.mail_sent=0
			 and careplans.Discipline='PT' 
			  #and careplans.POC_due>=date(now()) 
			 ".$having."
			and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
			 and	datediff (careplans.POC_due,date(now()))<=45 
 			and
			(
careplans.Patient_ID not  in (select prescription.Patient_ID from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date)	and
prescription.Discipline='PT' 
 and prescription.status=1
 #and prescription.Eval_done=0
 )
) 

							) t	) as PT,
							
		
		(	select  coaleSce(sum(subcount),0)
   			 from ( select   count(distinct careplans.Patient_ID) as subcount ,patients.DOB
   			  from careplans 
   			  join patients on careplans.Patient_ID=patients.Pat_id
				  and patients.active=1
	
 where 
			 careplans.status=1 
			and  patients.active=1
			  AND careplans.mail_sent=0
			 and careplans.Discipline='ST' 
			  #and careplans.POC_due>=date(now()) 
		 and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
		 ".$having."
			 and	datediff (careplans.POC_due,date(now()))<=45 
 			and
			(
careplans.Patient_ID not  in (select prescription.Patient_ID from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date)	
 prescription.Discipline='ST' 
 and prescription.status=1
 #and prescription.Eval_done=0
 )
) 


							) t	)  as ST

		";
						
		$result3 = mysqli_query($conexion, $query3);



$query4 = " 
select (	select  coaleSce(sum(subcount),0)
   			 from ( select   count(distinct careplans.Patient_ID) as subcount ,patients.DOB
   			  from careplans 
   			  join patients on careplans.Patient_ID=patients.Pat_id
				  and patients.active=1
	
 where 
			 careplans.status=1 
			 and patients.active=1
			  AND careplans.mail_sent=1
			 and careplans.Discipline like 'OT%' 
			  #and careplans.POC_due>=date(now()) 
			and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
			".$having."
			 and	datediff (careplans.POC_due,date(now()))<=45 
 			and
			(
careplans.Patient_ID not  in (select prescription.Patient_ID from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date)	
#and 
prescription.Discipline='OT' 
 and prescription.status=1
 #and prescription.Eval_done=0
 )
) 


							) t	) as OT ,
							
	(	select  coaleSce(sum(subcount),0)
   			 from ( select   count(distinct careplans.Patient_ID) as subcount ,patients.DOB
   			 from careplans 
   			 join patients on careplans.Patient_ID=patients.Pat_id
				  and patients.active=1
	
 where 
			 careplans.status=1 
			 and patients.active=1
			  AND careplans.mail_sent=1
			 and careplans.Discipline like 'PT%' 
			  #and careplans.POC_due>=date(now()) 
			and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
			".$having."
			 and	datediff (careplans.POC_due,date(now()))<=45 
 			and
			(
careplans.Patient_ID not  in (select prescription.Patient_ID from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date)	
prescription.Discipline='PT' 
 and prescription.status=1
 #and prescription.Eval_done=0
 )
) 

							) t	) as PT,
							
		
		(	select  coaleSce(sum(subcount),0)
   			 from ( select   count(distinct careplans.Patient_ID) as subcount ,patients.DOB
   			  from careplans 
   			  join patients on careplans.Patient_ID=patients.Pat_id
				  and patients.active=1
	
 where 
			 careplans.status=1 
			 and patients.active=1
			  AND careplans.mail_sent=1
			 and careplans.Discipline like 'ST%' 
			  #and careplans.POC_due>=date(now()) 
			and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
			".$having."
			 and	datediff (careplans.POC_due,date(now()))<=45 
 			and
			(
careplans.Patient_ID not  in (select prescription.Patient_ID from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date)	
prescription.Discipline='ST' 
 and prescription.status=1
 #and prescription.Eval_done=0
 )
) 


							) t	)  as ST

		";
						
		$result4 = mysqli_query($conexion, $query4);




		////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$query5 = " select 

(select  count(distinct prescription.Patient_ID)
 from
prescription


join patients on prescription.Patient_ID=patients.Pat_id
		and patients.active=1
    

where (
prescription.Patient_ID not in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='OT' 
								and cpt.type='EVAL' and insurance.status=1  ) 

and prescription.Discipline='OT' and prescription.status=1

and patients.active=1

and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' or
			  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' 
			  or patients.Pri_Ins='PRESTIGE'
			 # or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  ) 	) 
		   and prescription.mail_sent_eval=0
		   ".$having."
			  )  ) as OT ,
 	 
(select  count(distinct prescription.Patient_ID)
 from
prescription

join patients on prescription.Patient_ID=patients.Pat_id
		and patients.active=1
    

where (
prescription.Patient_ID not in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='PT' 
								and cpt.type='EVAL' and insurance.status=1  ) 

and prescription.Discipline='PT' and prescription.status=1

and patients.active=1
      

and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' or
			  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)'
			  or patients.Pri_Ins='PRESTIGE'
	#or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			   ) 	) 
		   and prescription.mail_sent_eval=0
		   ".$having."
			  )  ) as PT ,
 	 
(select  count(distinct prescription.Patient_ID)
 from
prescription

#left  join insurance on prescription.Patient_ID=insurance.Pat_id
join patients on prescription.Patient_ID=patients.Pat_id
		and patients.active=1
    

where (
prescription.Patient_ID not in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='ST' 
								and cpt.type='EVAL' and insurance.status=1  ) 

and prescription.Discipline='ST' and prescription.status=1

and patients.active=1

and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' or
			  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)'
			  or patients.Pri_Ins='PRESTIGE'
			  #or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			   ) 	) 
		  and prescription.mail_sent_eval=0
		  ".$having."
			  )  ) as ST
		
							
		";
		
		
		$result5 = mysqli_query($conexion, $query5);



$query05 = " select 

(select  count(distinct prescription.Patient_ID)
 from
prescription

 join patients on prescription.Patient_ID=patients.Pat_id
    and patients.active=1

where (

 prescription.Discipline='OT' and prescription.status=1

and patients.active=1

and prescription.Patient_ID not in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='OT' 
								and cpt.type='EVAL' and insurance.status=1  ) 



and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' or
			  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' 
			  or patients.Pri_Ins='PRESTIGE'
			  #or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  ) 	) 
		  and prescription.mail_sent_eval=1
		  ".$having."
			  )  ) as OT ,
 	 
(select  count(distinct prescription.Patient_ID)
 from
prescription


 join patients on prescription.Patient_ID=patients.Pat_id
    and patients.active=1

where (


prescription.Discipline='PT' and prescription.status=1

and patients.active=1

and prescription.Patient_ID not in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='PT' 
								and cpt.type='EVAL' and insurance.status=1  ) 



and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' or
			  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' 
			  or patients.Pri_Ins='PRESTIGE'
			  #or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  ) 	) 
		  and prescription.mail_sent_eval=1
		  ".$having."
			  )  ) as PT ,
 	 
(select  count(distinct prescription.Patient_ID)
 from
prescription


 join patients on prescription.Patient_ID=patients.Pat_id
    and patients.active=1

where (
 

 prescription.Discipline='ST' and prescription.status=1

and patients.active=1

and prescription.Patient_ID not in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='ST' 
								and cpt.type='EVAL' and insurance.status=1  ) 



and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' or
			  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)'
			  or patients.Pri_Ins='PRESTIGE'
			  #or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			   ) 	) 
		  and prescription.mail_sent_eval=1
		  ".$having."
			  )  ) as ST
		
							
		";
		
		
		$result05 = mysqli_query($conexion, $query05);



		
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$query6 = "
			select

( 
select  count(distinct prescription.Patient_ID)
 from
prescription
 join patients on prescription.Patient_ID=patients.Pat_id
 and patients.active=1

   
where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='OT' 
								and cpt.type='EVAL' and insurance.status=1  )		
			 and prescription.Discipline='OT'  and prescription.status=1
			 and patients.active=1		  
			 and prescription.Eval_done=0 
			 ".$having."
			 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where  patients.active=1 and ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN'
	  or  patients.Pri_Ins='WELLCARE (STAYWELL)' 
	  OR  patients.Pri_Ins='PRESTIGE' 
	  #or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)

			  ) 	)  ) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)'
			  AND  patients.Pri_Ins!='PRESTIGE' 
			 # and  (patients.Pri_Ins not like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  ) 	)  
			  and 
 prescription.status=1 and prescription.Discipline='OT' 
			  							  and prescription.Eval_done=0 
			  							  and patients.active=1 
			  							  ".$having."
) 



) as OT  ,			

( 
select  count(distinct prescription.Patient_ID)
 from
prescription
 join patients on prescription.Patient_ID=patients.Pat_id
and patients.active=1
   
where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='PT' 
								and cpt.type='EVAL' and insurance.status=1  )	
			 and patients.active=1 
			 and  prescription.Discipline='PT'  and prescription.status=1		
			 and prescription.Eval_done=0
			 ".$having."   
			 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)'
			  OR  patients.Pri_Ins='PRESTIGE' 
			  #or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			   ) 	)  ) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' 
			  #and (patients.Pri_Ins not like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  ) 	)  
			  and
  prescription.status=1 and prescription.Discipline='PT' 
			  							  and prescription.Eval_done=0 
			  							 and  patients.active=1 
			  							 ".$having." 
		) 


) as PT	,

( 
select  count(distinct prescription.Patient_ID)
 from
prescription
 join patients on prescription.Patient_ID=patients.Pat_id
   and patients.active=1

where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='ST' 
								and cpt.type='EVAL' and insurance.status=1  )	
			 and prescription.Discipline='ST'  and prescription.status=1	
			 and prescription.Eval_done=0
			and  patients.active=1 	 
			".$having." 
			 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' 
			  OR  patients.Pri_Ins='PRESTIGE' 
			 # or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  ) 	)  

) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' 
			  AND  patients.Pri_Ins!='PRESTIGE' 
			 # and (patients.Pri_Ins not like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  ) 	)  
			  and 
 prescription.status=1 and prescription.Discipline='ST' 
			  							  and prescription.Eval_done=0  
			  							  and patients.active=1
			  							  ".$having."
		) 



) as ST			

		
		";
		
		$result6 = mysqli_query($conexion, $query6);
	





/////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
	$query7 = "
		
			SELECT


(
select sum(count)  from 
(	select   count(distinct patients.Pat_id) as count


	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
	               and signed_doctor.Discipline=careplans.Discipline
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
  #left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
						#				and signed_doctor.Discipline=authorizations.Discipline
										
		where  signed_doctor.Patient_ID not in (select authorizations.Pat_id from authorizations
								where authorizations.Discipline='OT' and authorizations.status=1) 

	and signed_doctor.Discipline='OT' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%'
	and  patients.Pri_Ins not like 'SELF PAY%'
	and  patients.Pri_Ins not like 'MEDICARE%'
	 and patients.active=1
	AND
	(
					(
					 careplans.mail_sent_tx=0
					AND careplans.Discipline='OT'
			   	 and careplans.status=1
	 				)
	 		OR
	 				(
	 				careplans.Patient_ID is null
	 				and signed_doctor.Patient_ID in (select prescription.Patient_ID from prescription where tx_request_sent=0 and Discipline='OT' and status=1)
					 )		
	 
	 )
	 ".$having."
 # group by signed_doctor.Patient_ID
	
union distinct

 select  count(distinct patients.Pat_id) as count
		 
 			 
				 
		  from patients 
		  join physician on patients.Phy_NPI=physician.NPI
		  join insurance on patients.Pat_id=insurance.Pat_id
	join careplans on insurance.Pat_id=careplans.Patient_ID
											 
where 
(

	(insurance.CPT='97530' 
		and  patients.Pri_Ins not like 'MOLINA%'
		and  patients.Pri_Ins not like 'MEDICARE%'
		and  patients.Pri_Ins not like 'SELF PAY%'
		and  patients.Pri_Ins NOT like 'UNITED%'
		and  (patients.Pri_Ins NOT like 'SUNSHINE%' and patients.Pri_Ins NOT like 'ATA%' 
		and  patients.Pri_Ins NOT like 'AMERIGROUP%' and patients.Pri_Ins NOT like 'HUMANA%')
	and insurance.status=1 
 and patients.active=1
	and
	 (datediff (insurance.Auth_thru,date(now()))<3  
		or  insurance.Visits_remen<=4 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='97530' 
		and  (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
		or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
	 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<1  
		or  insurance.Visits_remen<1 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='97530' 
		and  (patients.Pri_Ins like 'UNITED%' 
		) 
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<7  
		or  insurance.Visits_remen<=7)
	#and insurance.Auth_thru>date(now())
	)
)




										AND
																(
	insurance.Pat_id in (select careplans.Patient_ID from careplans 
	where	careplans.POC_due>date_add(date(now()), INTERVAL 30 DAY) 
	and careplans.Discipline like 'OT%' and careplans.status=1 )
	and careplans.Discipline like 'OT%'
         			 and careplans.status=1  
	
         			 )
         			 and 
         			 
         			 (
	 patients.Pat_id not in (select prescription.Patient_ID from prescription 
	 where prescription.status=1 and prescription.Discipline='OT')
	 and
	 patients.Pat_id not in (select signed_doctor.Patient_ID from signed_doctor 
	 where signed_doctor.status=1 and signed_doctor.Discipline='OT')
						 )  
	 and patients.active=1
        		AND careplans.mail_sent_tx=0
        		AND careplans.Discipline='OT'
        		 ".$having."
				#	group by insurance.`Auth_#`

union Distinct

SELECT   count(distinct patients.Pat_id) as count
     
       
         
      from patients 
      #join physician on patients.Phy_NPI=physician.NPI
      join insurance on patients.Pat_id=insurance.Pat_id
  join careplans on patients.Pat_id=careplans.Patient_ID
            
                       
 where 
  patients.active=1
 AND   
( 
     (patients.Thi_Ins!='' and patients.Pri_Ins!=patients.Thi_Ins
        and  ( patients.Thi_Ins not like 'MOLINA%' and  patients.Thi_Ins not like 'MEDICARE%' and patients.Thi_Ins not like 'SELF%')
     )
     OR
    (
	 patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 and insurance.Discipline='OT'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
   				AND
	 (
	  (patients.Pat_id not in (select prescription.Patient_ID from	 prescription 
											where prescription.status=1 and prescription.Discipline='OT') )
	 OR
	 (patients.Pat_id in (select prescription.Patient_ID from	 prescription 
	 							left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
	 														and prescription.Discipline=signed_doctor.Discipline
				where (prescription.status=1 and prescription.Discipline='OT' and signed_doctor.Patient_ID is not null
							and signed_doctor.status=1)
								 )
										
		
				 )
	 )
     
)
)
    and careplans.status=1
    and careplans.mail_sent_tx=0    
    and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
    AND careplans.Discipline='OT'
    #AND patients.Pat_id='9526717678'
     ".$having."
    
    
) as t

) as OT		,



(
select sum(count)  from 
(	select   count(distinct patients.Pat_id) as count


	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
				and signed_doctor.Discipline=careplans.Discipline
	               
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
  #left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
									#	and signed_doctor.Discipline=authorizations.Discipline
										
		where  signed_doctor.Patient_ID not in (select authorizations.Pat_id from authorizations
								where authorizations.Discipline='PT' and authorizations.status=1) 


	and signed_doctor.Discipline='PT' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%'
	and  patients.Pri_Ins not like 'MEDICARE%'
	and  patients.Pri_Ins not like 'SELF PAY%'
	 and patients.active=1
	AND
	(
					(
					 careplans.mail_sent_tx=0
					AND careplans.Discipline='PT'
			   	 and careplans.status=1
	 				)
	 		OR
	 				(
	 				careplans.Patient_ID is null
	 				and signed_doctor.Patient_ID in (select prescription.Patient_ID from prescription where tx_request_sent=0 and Discipline='PT' and status=1)
					 )		
	 
	 )
	 ".$having."
 # group by signed_doctor.Patient_ID
	
union distinct

 select  count(distinct patients.Pat_id) as count
		 
 			 
				 
		  from patients 
		  join physician on patients.Phy_NPI=physician.NPI
		  join insurance on patients.Pat_id=insurance.Pat_id
	join careplans on insurance.Pat_id=careplans.Patient_ID
											

		 where 
		(

	(insurance.CPT='97110' 
		and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
		and  patients.Pri_Ins not like 'SELF PAY%'
		and  patients.Pri_Ins NOT like 'UNITED%'
		and  (patients.Pri_Ins NOT like 'SUNSHINE%' and patients.Pri_Ins NOT like 'ATA%' 
		and  patients.Pri_Ins NOT like 'AMERIGROUP%' and patients.Pri_Ins NOT like 'HUMANA%')
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<3  
		or  insurance.Visits_remen<=4 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='97110' 
		and  (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
		or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
	 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<1  
		or  insurance.Visits_remen<1 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='97110' 
		and  (patients.Pri_Ins like 'UNITED%' 
		) 
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<7  
		or  insurance.Visits_remen<=7)
	#and insurance.Auth_thru>date(now())
	)
)

        		    AND 
         			 
         			 (
	 patients.Pat_id not in (select prescription.Patient_ID from prescription 
	 where prescription.status=1 and prescription.Discipline='PT')
	 and
	 patients.Pat_id not in (select signed_doctor.Patient_ID from signed_doctor 
	 where signed_doctor.status=1 and signed_doctor.Discipline='PT')
						 )  
	
						 	 and patients.active=1
				AND careplans.mail_sent_tx=0
				AND careplans.Discipline='PT'
				 ".$having."
				#	group by insurance.`Auth_#`

union Distinct

SELECT   count(distinct patients.Pat_id) as count
     
       
         
      from patients 
      #join physician on patients.Phy_NPI=physician.NPI
      join insurance on patients.Pat_id=insurance.Pat_id
  join careplans on patients.Pat_id=careplans.Patient_ID
            
                       
 where 
  patients.active=1
AND   
( 
     (patients.Thi_Ins!='' and patients.Pri_Ins!=patients.Thi_Ins
        and  ( patients.Thi_Ins not like 'MOLINA%' and  patients.Thi_Ins not like 'MEDICARE%' and patients.Thi_Ins not like 'SELF%')
     )
     OR
    (
	 patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 and insurance.Discipline='PT'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
   				AND
	 (
	  (patients.Pat_id not in (select prescription.Patient_ID from	 prescription 
											where prescription.status=1 and prescription.Discipline='PT') )
	 OR
	 (patients.Pat_id in (select prescription.Patient_ID from	 prescription 
	 								left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
	 														and prescription.Discipline=signed_doctor.Discipline
				where (prescription.status=1 and prescription.Discipline='PT' and signed_doctor.Patient_ID is not null
							and signed_doctor.status=1)
								 )
										
		
				 )
	 )
     
)
)
    and careplans.status=1
    and careplans.mail_sent_tx=0    
    and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
    AND careplans.Discipline='PT'
    #AND patients.Pat_id='9526717678'
     ".$having."

) as t

) as PT			,



(
select sum(count)  from 
(	select   count(distinct patients.Pat_id) as count


	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
	           and signed_doctor.Discipline=careplans.Discipline    
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
  #left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
					#					and signed_doctor.Discipline=authorizations.Discipline
										
		where  signed_doctor.Patient_ID not in (select authorizations.Pat_id from authorizations
								where authorizations.Discipline='ST' and authorizations.status=1) 


	and signed_doctor.Discipline='ST' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	and  patients.Pri_Ins not like 'SELF PAY%'
	
	 and patients.active=1
	AND
	(
					(
					 careplans.mail_sent_tx=0
					AND careplans.Discipline='ST'
			   	 and careplans.status=1
	 				)
	 		OR
	 				(
	 				careplans.Patient_ID is null
	 				and signed_doctor.Patient_ID in (select prescription.Patient_ID from prescription where tx_request_sent=0 and Discipline='ST' and status=1)
					 )		
	 
	 )
	 ".$having."
	
 # group by signed_doctor.Patient_ID
	
union distinct

 select  count(distinct patients.Pat_id) as count
		 
 			 
				 
		  from patients 
		  join physician on patients.Phy_NPI=physician.NPI
		  join insurance on patients.Pat_id=insurance.Pat_id
	join careplans on insurance.Pat_id=careplans.Patient_ID
			
			where 
			
(

	(insurance.CPT='92507' 
		and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
		and  patients.Pri_Ins not like 'SELF PAY%'
		and  patients.Pri_Ins NOT like 'UNITED%'
		and (patients.Pri_Ins NOT like 'SUNSHINE%' and patients.Pri_Ins NOT like 'ATA%' 
		and  patients.Pri_Ins NOT like 'AMERIGROUP%' and patients.Pri_Ins NOT like 'HUMANA%')
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<3  
		or  insurance.Visits_remen<=4 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='92507' 
		and  (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
		or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
	 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<1  
		or  insurance.Visits_remen<1 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='92507' 
		and  (patients.Pri_Ins like 'UNITED%' 
		) 
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<7  
		or  insurance.Visits_remen<=7)
	#and insurance.Auth_thru>date(now())
	)
)

AND
																(
	insurance.Pat_id in (select careplans.Patient_ID from careplans 
	where	careplans.POC_due>date_add(date(now()), INTERVAL 30 DAY) 
	and careplans.Discipline like 'ST%' and careplans.status=1 )
	and careplans.Discipline like 'ST%'
         			 and careplans.status=1  
	
         			 )
        		and 
         			 
         			 (
	 patients.Pat_id not in (select prescription.Patient_ID from prescription 
	 where prescription.status=1 and prescription.Discipline='ST')
	 and
	 patients.Pat_id not in (select signed_doctor.Patient_ID from signed_doctor 
	 where signed_doctor.status=1 and signed_doctor.Discipline='ST')
						 )  
	
	 and patients.active=1
	AND careplans.mail_sent_tx=0
	AND careplans.Discipline='ST'
 ".$having."

				#	group by insurance.`Auth_#`

union Distinct

SELECT   count(distinct patients.Pat_id) as count
     
       
         
      from patients 
      #join physician on patients.Phy_NPI=physician.NPI
      join insurance on patients.Pat_id=insurance.Pat_id
  join careplans on patients.Pat_id=careplans.Patient_ID
            
                       
 where 
  patients.active=1
AND   
( 
     (patients.Thi_Ins!='' and patients.Pri_Ins!=patients.Thi_Ins
        and  ( patients.Thi_Ins not like 'MOLINA%' and  patients.Thi_Ins not like 'MEDICARE%' and patients.Thi_Ins not like 'SELF%')
     )
     OR
    (
	 patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 and insurance.Discipline='ST'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
   				AND
	 (
	  (patients.Pat_id not in (select prescription.Patient_ID from	 prescription 
											where prescription.status=1 and prescription.Discipline='ST') )
	 OR
	 (patients.Pat_id in (select prescription.Patient_ID from	 prescription 
	 								left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
	 														and prescription.Discipline=signed_doctor.Discipline
				where (prescription.status=1 and prescription.Discipline='ST' and signed_doctor.Patient_ID is not null
							and signed_doctor.status=1)
								 )
										
		
				 )
	 )
     
)
)
    and careplans.status=1
    and careplans.mail_sent_tx=0    
    and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
    AND careplans.Discipline='ST'
    #AND patients.Pat_id='9526717678'
     ".$having."
   

) as t

) as ST						
		
		";
		
		$result7 = mysqli_query($conexion, $query7);









		
	$query07 = "
		
			SELECT


(
select sum(count)  from 
(	select   count(distinct patients.Pat_id) as count


	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
	               and signed_doctor.Discipline=careplans.Discipline
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
  #left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
								#		and signed_doctor.Discipline=authorizations.Discipline
										
		where  signed_doctor.Patient_ID not in (select authorizations.Pat_id from authorizations
								where authorizations.Discipline='OT' and authorizations.status=1) 

	and signed_doctor.Discipline='OT' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	and  patients.Pri_Ins not like 'SELF PAY%'
	
	 and patients.active=1
	AND
	(
					(
					 careplans.mail_sent_tx=1
					AND careplans.Discipline='OT'
			   	 and careplans.status=1
	 				)
	 		OR
	 				(
	 				careplans.Patient_ID is null
	 				and signed_doctor.Patient_ID in (select prescription.Patient_ID from prescription where tx_request_sent=1 and Discipline='OT' and status=1)
					 )		
	 
	 )
 ".$having."

 # group by signed_doctor.Patient_ID
	
union distinct

 select  count(distinct patients.Pat_id) as count
		 
 			 
				 
		  from patients 
		  join physician on patients.Phy_NPI=physician.NPI
		  join insurance on patients.Pat_id=insurance.Pat_id
	join careplans on insurance.Pat_id=careplans.Patient_ID
											 

	where
	(

	(insurance.CPT='97530' 
		and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
		and  patients.Pri_Ins not like 'SELF PAY%'
		and  patients.Pri_Ins NOT like 'UNITED%'
		and  (patients.Pri_Ins NOT like 'SUNSHINE%' or patients.Pri_Ins NOT like 'ATA%' 
		or patients.Pri_Ins NOT like 'AMERIGROUP%' OR patients.Pri_Ins NOT like 'HUMANA%')
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<3  
		or  insurance.Visits_remen<=4 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='97530' 
		and  (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
		or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
	 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<1  
		or  insurance.Visits_remen<1 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='97530' 
		and  (patients.Pri_Ins like 'UNITED%' 
		) 
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<7  
		or  insurance.Visits_remen<=7)
	#and insurance.Auth_thru>date(now())
	)
) 
														and 
																(
	insurance.Pat_id in (select careplans.Patient_ID from careplans 
	where	careplans.POC_due>date_add(date(now()), INTERVAL 30 DAY) 
	and careplans.Discipline like 'OT%' and careplans.status=1 )
	and careplans.Discipline like 'OT%'
         			 and careplans.status=1  
	
         			 )
         			 and 
         			 
         			 (
	 patients.Pat_id not in (select prescription.Patient_ID from prescription 
	 where prescription.status=1 and prescription.Discipline='OT')
	 and
	 patients.Pat_id not in (select signed_doctor.Patient_ID from signed_doctor 
	 where signed_doctor.status=1 and signed_doctor.Discipline='OT')
						 )  
	
		 and patients.active=1
        		AND careplans.mail_sent_tx=1
        		AND careplans.Discipline='OT'
        		 ".$having."
				#	group by insurance.`Auth_#`

union Distinct

SELECT   count(distinct patients.Pat_id) as count
     
       
         
      from patients 
      #join physician on patients.Phy_NPI=physician.NPI
      join insurance on patients.Pat_id=insurance.Pat_id
  join careplans on patients.Pat_id=careplans.Patient_ID
            
                       
 where 
  patients.active=1
AND   
( 
     (patients.Thi_Ins!='' and patients.Pri_Ins!=patients.Thi_Ins
        and  ( patients.Thi_Ins not like 'MOLINA%' and  patients.Thi_Ins not like 'MEDICARE%' and patients.Thi_Ins not like 'SELF%')
     )
     OR
    (
	 patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 and insurance.Discipline='OT'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
   				AND
	 (
	  (patients.Pat_id not in (select prescription.Patient_ID from	 prescription 
											where prescription.status=1 and prescription.Discipline='OT') )
	 OR
	 (patients.Pat_id in (select prescription.Patient_ID from	 prescription 
	 							left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
	 														and prescription.Discipline=signed_doctor.Discipline
				where (prescription.status=1 and prescription.Discipline='OT' and signed_doctor.Patient_ID is not null
							and signed_doctor.status=1)
								 )
										
		
				 )
	 )
     
)
)
    and careplans.status=1
    and careplans.mail_sent_tx=1    
    and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
    AND careplans.Discipline='OT'
    #AND patients.Pat_id='9526717678'
     ".$having."


) as t

) as OT		,



(
select sum(count)  from 
(	select   count(distinct patients.Pat_id) as count


	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
	       and signed_doctor.Discipline=careplans.Discipline        
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
		#							and signed_doctor.Discipline=authorizations.Discipline
										
		where signed_doctor.Patient_ID not in (select authorizations.Pat_id from authorizations
								where authorizations.Discipline='PT' and authorizations.status=1) 


	and signed_doctor.Discipline='PT' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	and  patients.Pri_Ins not like 'SELF PAY%'

	 and patients.active=1
	
	AND
	(
					(
					 careplans.mail_sent_tx=1
					AND careplans.Discipline='PT'
			   	 and careplans.status=1
	 				)
	 		OR
	 				(
	 				careplans.Patient_ID is null
	 				and signed_doctor.Patient_ID in (select prescription.Patient_ID from prescription where tx_request_sent=1 and Discipline='PT' and status=1)
					 )		
	 
	 )
 ".$having."

 # group by signed_doctor.Patient_ID
	
union distinct

 select  count(distinct patients.Pat_id) as count
		 
 			 
				 
		  from patients 
		  join physician on patients.Phy_NPI=physician.NPI
		  join insurance on patients.Pat_id=insurance.Pat_id
	join careplans on insurance.Pat_id=careplans.Patient_ID
											 


		where 

(

	(insurance.CPT='97110' 
		and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
		and  patients.Pri_Ins not like 'SELF PAY%'
		and  patients.Pri_Ins NOT like 'UNITED%'
		and  (patients.Pri_Ins NOT like 'SUNSHINE%' or patients.Pri_Ins NOT like 'ATA%' 
		or patients.Pri_Ins NOT like 'AMERIGROUP%' OR patients.Pri_Ins NOT like 'HUMANA%')
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<3  
		or  insurance.Visits_remen<=4 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='97110' 
		and  (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
		or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
	 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<1  
		or  insurance.Visits_remen<1 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='97110' 
		and  (patients.Pri_Ins like 'UNITED%' 
		) 
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<7  
		or  insurance.Visits_remen<=7)
	#and insurance.Auth_thru>date(now())
	)
)

							AND 
																(
	insurance.Pat_id in (select careplans.Patient_ID from careplans 
	where	careplans.POC_due>date_add(date(now()), INTERVAL 30 DAY) 
	and careplans.Discipline like 'PT%' and careplans.status=1 )
	and careplans.Discipline like 'PT%'
         			 and careplans.status=1  
	
         			 )
        		    and 
         			 
         			 (
	 patients.Pat_id not in (select prescription.Patient_ID from prescription 
	 where prescription.status=1 and prescription.Discipline='PT')
	 and
	 patients.Pat_id not in (select signed_doctor.Patient_ID from signed_doctor 
	 where signed_doctor.status=1 and signed_doctor.Discipline='PT')
						 )  

						  and patients.active=1
				
				AND careplans.mail_sent_tx=1
				AND careplans.Discipline='PT'
 ".$having."

				#	group by insurance.`Auth_#`


union Distinct

SELECT   count(distinct patients.Pat_id) as count
     
       
         
      from patients 
      #join physician on patients.Phy_NPI=physician.NPI
      join insurance on patients.Pat_id=insurance.Pat_id
  join careplans on patients.Pat_id=careplans.Patient_ID
            
                       
 where 
  patients.active=1
  AND   
( 
     (patients.Thi_Ins!='' and patients.Pri_Ins!=patients.Thi_Ins
        and  ( patients.Thi_Ins not like 'MOLINA%' and  patients.Thi_Ins not like 'MEDICARE%' and patients.Thi_Ins not like 'SELF%')
     )
     OR
    (
	 patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 and insurance.Discipline='PT'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
   				AND
	 (
	  (patients.Pat_id not in (select prescription.Patient_ID from	 prescription 
											where prescription.status=1 and prescription.Discipline='PT') )
	 OR
	 (patients.Pat_id in (select prescription.Patient_ID from	 prescription 
	 							left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
	 														and prescription.Discipline=signed_doctor.Discipline
				where (prescription.status=1 and prescription.Discipline='PT' and signed_doctor.Patient_ID is not null
							and signed_doctor.status=1)
								 )
										
		
				 )
	 )
     
)
)
    and careplans.status=1
    and careplans.mail_sent_tx=1    
    and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
    AND careplans.Discipline='PT'
    #AND patients.Pat_id='9526717678'
     ".$having."
    

) as t

) as PT			,



(
select sum(count)  from 
(	select   count(distinct patients.Pat_id) as count


	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
					and signed_doctor.Discipline=careplans.Discipline
	               
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
			#							and signed_doctor.Discipline=authorizations.Discipline
										
		where  signed_doctor.Patient_ID not in (select authorizations.Pat_id from authorizations
								where authorizations.Discipline='ST' and authorizations.status=1) 


	and signed_doctor.Discipline='ST' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	and  patients.Pri_Ins not like 'SELF PAY%'
	
	 and patients.active=1
	AND
	(
					(
					 careplans.mail_sent_tx=1
					AND careplans.Discipline='ST'
			   	 and careplans.status=1
	 				)
	 		OR
	 				(
	 				careplans.Patient_ID is null
	 				and signed_doctor.Patient_ID in (select prescription.Patient_ID from prescription where tx_request_sent=1 and Discipline='ST' and status=1)
					 )		
	 
	 )
 ".$having."

 # group by signed_doctor.Patient_ID
	
union distinct

 select  count(distinct patients.Pat_id) as count
		 
 			 
				 
		  from patients 
		  join physician on patients.Phy_NPI=physician.NPI
		  join insurance on patients.Pat_id=insurance.Pat_id
	join careplans on insurance.Pat_id=careplans.Patient_ID
			

			where 

(

	(insurance.CPT='92507' 
		and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
		and  patients.Pri_Ins not like 'SELF PAY%'
		and  patients.Pri_Ins NOT like 'UNITED%'
		and  (patients.Pri_Ins NOT like 'SUNSHINE%' or patients.Pri_Ins NOT like 'ATA%' 
		or patients.Pri_Ins NOT like 'AMERIGROUP%' OR patients.Pri_Ins NOT like 'HUMANA%')
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<3  
		or  insurance.Visits_remen<=4 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='92507' 
		and  (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
		or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
	 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<1  
		or  insurance.Visits_remen<1 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(insurance.CPT='92507' 
		and  (patients.Pri_Ins like 'UNITED%' 
		) 
		 and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<7  
		or  insurance.Visits_remen<=7)
	#and insurance.Auth_thru>date(now())
	)
)


AND 
																(
	insurance.Pat_id in (select careplans.Patient_ID from careplans 
	where	careplans.POC_due>date_add(date(now()), INTERVAL 30 DAY) 
	and careplans.Discipline like 'ST%' and careplans.status=1 )
	and careplans.Discipline like 'ST%'
         			 and careplans.status=1  
	
         			 )
        		and 
         			 
         			 (
	 patients.Pat_id not in (select prescription.Patient_ID from prescription 
	 where prescription.status=1 and prescription.Discipline='ST')
	 and
	 patients.Pat_id not in (select signed_doctor.Patient_ID from signed_doctor 
	 where signed_doctor.status=1 and signed_doctor.Discipline='ST')
						 )  
				
						  and patients.active=1
				AND careplans.mail_sent_tx=1
				AND careplans.Discipline='ST'
 ".$having."

				#	group by insurance.`Auth_#`

union Distinct

SELECT   count(distinct patients.Pat_id) as count
     
       
         
      from patients 
      #join physician on patients.Phy_NPI=physician.NPI
      join insurance on patients.Pat_id=insurance.Pat_id
  join careplans on patients.Pat_id=careplans.Patient_ID
            
                       
 where 
  patients.active=1
     				AND   
( 
     (patients.Thi_Ins!='' and patients.Pri_Ins!=patients.Thi_Ins
        and  ( patients.Thi_Ins not like 'MOLINA%' and  patients.Thi_Ins not like 'MEDICARE%' and patients.Thi_Ins not like 'SELF%')
     )
     OR
    (
	 patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 and insurance.Discipline='ST'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
   				AND
	 (
	  (patients.Pat_id not in (select prescription.Patient_ID from	 prescription 
											where prescription.status=1 and prescription.Discipline='ST') )
	 OR
	 (patients.Pat_id in (select prescription.Patient_ID from	 prescription 
	 								left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
	 														and prescription.Discipline=signed_doctor.Discipline
				where (prescription.status=1 and prescription.Discipline='ST' and signed_doctor.Patient_ID is not null
							and signed_doctor.status=1)
								 )
										
		
				 )
	 )
     
)
)
    and careplans.status=1
    and careplans.mail_sent_tx=1    
    and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
    AND careplans.Discipline='ST'
    #AND patients.Pat_id='9526717678'
     ".$having."
   
) as t

) as ST						
		
		";
		
		$result07 = mysqli_query($conexion, $query07);





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		$query8 = " 	
		SELECT 
( select  count(distinct prescription.Patient_ID) 			    			
				from prescription
				 												
					 left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
										and prescription.Discipline=signed_doctor.Discipline
			 			 		      and signed_doctor.status=1

			 		 join patients on prescription.Patient_ID=patients.Pat_id
			 		 and patients.active=1
			 			 				
			
where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='OT' 
								and cpt.type='EVAL' and insurance.status=1  )		
			 and prescription.Discipline='OT'  
			 and prescription.status=1	
			  ".$having."
			 and prescription.Eval_done=1
			 and patients.active=1	  
			  and  signed_doctor.Patient_ID is   null 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' ) 	) 
			  AND prescription.mail_sent_not_signed=0 ) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' ) 	)  
			  and 
 signed_doctor.Patient_ID is   null 	
		and prescription.Discipline='OT'
		and prescription.status=1 and prescription.Eval_done=1
		AND prescription.mail_sent_not_signed=0
		and patients.active=1
		 ".$having."
		)  ) AS OT
	,
	
	( select  count(distinct prescription.Patient_ID) 			    			
				from prescription
				 												
					 left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
										and prescription.Discipline=signed_doctor.Discipline
			 			 		      and signed_doctor.status=1

			 			 join patients on prescription.Patient_ID=patients.Pat_id 				
								and patients.active=1
where 
( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='PT' 
								and cpt.type='EVAL' and insurance.status=1  )
			 and prescription.Discipline='PT'  
			 and prescription.status=1
			  ".$having."
			 and prescription.Eval_done=1
			 and patients.active=1		  
			  and  signed_doctor.Patient_ID is   null 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' ) and patients.active=1 	) AND prescription.mail_sent_not_signed=0 ) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' ) and patients.active=1	)  
			  and 
 signed_doctor.Patient_ID is   null 	
		and prescription.Discipline='PT'
		and prescription.status=1 and prescription.Eval_done=1
		AND prescription.mail_sent_not_signed=0
		and patients.active=1
		 ".$having."
		)  ) AS PT  ,
			 
	( select  count(distinct prescription.Patient_ID) 			    			
				from prescription

				 join patients on prescription.Patient_ID=patients.Pat_id
				and patients.active=1
				 												
					 left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
										and prescription.Discipline=signed_doctor.Discipline
			 			 		      and signed_doctor.status=1
			 			 				
			 
where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='ST' 
								and cpt.type='EVAL' and insurance.status=1  )
			 and prescription.Discipline='ST' 
			  and prescription.status=1
			   ".$having."
			 and prescription.Eval_done=1	
			 and patients.active=1	  
			  and  signed_doctor.Patient_ID is   null 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' ) and patients.active=1	) AND prescription.mail_sent_not_signed=0 ) 
 OR
 (
 prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' ) and patients.active=1	)  
			  and
  signed_doctor.Patient_ID is   null 	
		and prescription.Discipline='ST'
		and prescription.status=1 and prescription.Eval_done=1
		AND prescription.mail_sent_not_signed=0
		and patients.active=1
		 ".$having."
		)  ) AS ST
 		  

		";

		$result8 = mysqli_query($conexion, $query8);






		$query9 = " 	
		SELECT 
( select  count(distinct prescription.Patient_ID) 			    			
				from prescription

				 join patients on prescription.Patient_ID=patients.Pat_id
				and patients.active=1
				 												
					 left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
										and prescription.Discipline=signed_doctor.Discipline
			 			 		      and prescription.status=signed_doctor.status
			 			 				
			
where 
( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='OT' 
								and cpt.type='EVAL' and insurance.status=1  )
			 and prescription.Discipline='OT'  
			 and prescription.status=1
			  ".$having."
			 and prescription.Eval_done=1	
			 and patients.active=1	  
			  and  signed_doctor.Patient_ID is   null 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' ) and patients.active=1	) AND prescription.mail_sent_not_signed=1 ) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' ) and patients.active=1	)  
			  and 
 signed_doctor.Patient_ID is   null 	
		and prescription.Discipline='OT'
		and prescription.status=1 and prescription.Eval_done=1
		AND prescription.mail_sent_not_signed=1
		and patients.active=1
		 ".$having."
		) 

		 ) AS OT
	,
	
	( select  count(distinct prescription.Patient_ID) 			    			
				from prescription

				 join patients on prescription.Patient_ID=patients.Pat_id
				 and patients.active=1
				 												
					 left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
										and prescription.Discipline=signed_doctor.Discipline
			 			 		      and signed_doctor.status=1
			 			 				
			
where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='PT' 
								and cpt.type='EVAL' and insurance.status=1  )
			 and prescription.Discipline='PT'  and prescription.status=1	
			 and prescription.Eval_done=1
			  ".$having."
			 and patients.active=1	  
			  and  signed_doctor.Patient_ID is   null 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' ) and patients.active=1 	) AND prescription.mail_sent_not_signed=1 ) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' ) and patients.active=1	)  
			  and 
 signed_doctor.Patient_ID is   null 	
		and prescription.Discipline='PT'
		and prescription.status=1 and prescription.Eval_done=1
		AND prescription.mail_sent_not_signed=1
		and patients.active=1
		 ".$having."
		) 

		 ) AS PT  ,
			 
	( select  count(distinct prescription.Patient_ID) 			    			
				from prescription
				 					
				 join patients on prescription.Patient_ID=patients.Pat_id
				 and patients.active=1

					 left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
										and prescription.Discipline=signed_doctor.Discipline
			 			 		      and signed_doctor.status=1
			 			 				
			 
where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='ST' 
								and cpt.type='EVAL' and insurance.status=1  )
			 and prescription.Discipline='ST'  and prescription.status=1	
			 and prescription.Eval_done=1
			 and patients.active=1	  
			  ".$having."
			  and  signed_doctor.Patient_ID is   null 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' ) and patients.active=1	) AND prescription.mail_sent_not_signed=1 ) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' ) and patients.active=1	)  
			  and 
 signed_doctor.Patient_ID is   null 	
		and prescription.Discipline='ST'
		and prescription.status=1 and prescription.Eval_done=1
		AND prescription.mail_sent_not_signed=1
		and patients.active=1
		 ".$having."
		) 

#AND prescription.Patient_ID not in (SELECT authorizations.Pat_id from authorizations where 
#									authorizations.Discipline='ST' and authorizations.status=1 
#									and  authorizations.CPT='92523')
		 ) AS ST
 		  

		";

		$result9 = mysqli_query($conexion, $query9);








///////////////////////////////////////////////////////////////////////////////////////


		$query10 = "select

	(select count( distinct signed_doctor.Patient_ID) as count
 

	from signed_doctor 
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
  left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
										and signed_doctor.Discipline=authorizations.Discipline
										
		where  authorizations.Pat_ID  in (select authorizations.Pat_id from authorizations
													 left join insurance on authorizations.Pat_id=insurance.Pat_id
														and authorizations.`Auth_#`=insurance.`Auth_#`
														where authorizations.status=1 and insurance.Pat_id is null) is null

		and signed_doctor.Discipline='OT' and signed_doctor.status=1
		and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%') as OT ,

	
	(select count( distinct signed_doctor.Patient_ID) as count
 

	from signed_doctor 
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
  left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
										and signed_doctor.Discipline=authorizations.Discipline
										
		where  authorizations.Pat_ID  in (select authorizations.Pat_id from authorizations
													 left join insurance on authorizations.Pat_id=insurance.Pat_id
														and authorizations.`Auth_#`=insurance.`Auth_#`
														where authorizations.status=1 and insurance.Pat_id is null) is null

	and signed_doctor.Discipline='PT' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%') as PT,
	
	(select count( distinct signed_doctor.Patient_ID) as count
 

	from signed_doctor 
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
  left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
										and signed_doctor.Discipline=authorizations.Discipline
										
		where  authorizations.Pat_ID  in (select authorizations.Pat_id from authorizations
													 left join insurance on authorizations.Pat_id=insurance.Pat_id
														and authorizations.`Auth_#`=insurance.`Auth_#`
														where authorizations.status=1 and insurance.Pat_id is null) is null

	and signed_doctor.Discipline='ST' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%') as ST
	
	";
	$result10 = mysqli_query($conexion, $query10);

  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$query11 = "
select

	(SELECT SUM(count) FROM 
(select count( distinct patients.Pat_id) as count
 

from authorizations 
  join patients on authorizations.Pat_ID=patients.Pat_id
  and patients.active=1
  left join insurance on authorizations.Pat_ID=insurance.Pat_ID
										and authorizations.`Auth_#`=insurance.`Auth_#`
										and authorizations.Discipline=insurance.Discipline
										#and insurance.status=1
										
where (insurance.Pat_id is null and authorizations.Discipline='OT'  and authorizations.status='1' ".$having.")

										OR

		(insurance.Pat_id  is not null and insurance.status=1 and insurance.Discipline='OT' 
					and insurance.Visits_Auth=insurance.Visits_remen
					and insurance.CPT='97530'
					and insurance.Auth_thru>=date(now())
	#and insurance.Pat_id in (select careplans.Patient_ID from careplans where careplans.Discipline='OT' and careplans.`status`=1 	and careplans.POC_due>date(now()))
					".$having."
					)
					
					
union all

   select count(distinct patients.Pat_id) as count

	from signed_doctor 
	               
 join patients on signed_doctor.Patient_ID=patients.Pat_id
 and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
					#					and signed_doctor.Discipline=authorizations.Discipline										
		where  ( patients.Pri_Ins like 'MOLINA%' OR patients.Pri_Ins like 'MEDICARE%' OR patients.Pri_Ins like 'SELF%' )

	and signed_doctor.Discipline='OT' and signed_doctor.status=1 
	".$having."
    					
) AS T) as OT ,
	
	(SELECT SUM(count) FROM 
(select count( distinct patients.Pat_id) as count
 

from authorizations 
  join patients on authorizations.Pat_ID=patients.Pat_id
  and patients.active=1
  left join insurance on authorizations.Pat_ID=insurance.Pat_ID
										and authorizations.`Auth_#`=insurance.`Auth_#`
										and authorizations.Discipline=insurance.Discipline
										#and insurance.status=1

where (insurance.Pat_id is null and authorizations.Discipline='PT'  and authorizations.status='1' ".$having.")

										OR

		(insurance.Pat_id  is not null and insurance.status=1 and insurance.Discipline='PT' 
					and insurance.Visits_Auth=insurance.Visits_remen
					and insurance.CPT='97110'
					and insurance.Auth_thru>=date(now())
#and insurance.Pat_id in (select careplans.Patient_ID from careplans where careplans.Discipline='PT' and careplans.`status`=1 	and careplans.POC_due>date(now()))
					".$having."
					)
					
					
union all

   select count(distinct patients.Pat_id) as count

	from signed_doctor 
	               
 join patients on signed_doctor.Patient_ID=patients.Pat_id
 and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
					#					and signed_doctor.Discipline=authorizations.Discipline										
		where   ( patients.Pri_Ins like 'Molina%' or patients.Pri_Ins like 'MEDICARE%' or patients.Pri_Ins like 'SELF%' )

	and signed_doctor.Discipline='PT' and signed_doctor.status=1 
	".$having."
    					
) AS T) as PT ,
	
	(SELECT SUM(count) FROM 
(select count( distinct patients.Pat_id) as count
 

from authorizations 
  join patients on authorizations.Pat_ID=patients.Pat_id
  and patients.active=1
  left join insurance on authorizations.Pat_ID=insurance.Pat_id
										and authorizations.`Auth_#`=insurance.`Auth_#`
										and authorizations.Discipline=insurance.Discipline
										#and insurance.status=1
										
where (insurance.Pat_id is null and authorizations.Discipline='ST'  and authorizations.status='1' ".$having.")

										OR

		(insurance.Pat_id  is not null and insurance.status=1 and insurance.Discipline='ST' 
					and insurance.Visits_Auth=insurance.Visits_remen
					and insurance.CPT='92507'
					and insurance.Auth_thru>=date(now())
#and insurance.Pat_id in (select careplans.Patient_ID from careplans where careplans.Discipline='ST' and careplans.`status`=1 	and careplans.POC_due>date(now()))
					".$having."
					)
					
					
union all

   select count(distinct patients.Pat_id) as count

	from signed_doctor 
	               
 join patients on signed_doctor.Patient_ID=patients.Pat_id
 and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
					#					and signed_doctor.Discipline=authorizations.Discipline										
		where   ( patients.Pri_Ins like 'MOLINA%' OR patients.Pri_Ins like 'MEDICARE%' or  patients.Pri_Ins like 'SELF%' )

	and signed_doctor.Discipline='ST' and signed_doctor.status=1 
	".$having."
    					
) AS T) as ST
	
	

	
	";
	$result11 = mysqli_query($conexion, $query11);



$query12 = "
select

	(select count( distinct insurance.Pat_id) as count
 

	from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
 
										
where (   patients.Pri_Ins NOT LIKE 'MOLINA%'  and  patients.Pri_Ins not like 'MEDICARE%'
		and  insurance.Discipline='OT' and insurance.CPT='97530'  and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru 	or insurance.Visits_remen<=1)	)

or   

(  insurance.Visits_remen=1 and insurance.Discipline='OT' and (insurance.CPT='97003' or insurance.CPT='97004')  and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru )	)

 
or
(
patients.Pat_id in (select  authorizations.Pat_id  from authorizations
								join patients on authorizations.Pat_id=patients.Pat_id
								and patients.active=1
								 left join insurance on insurance.Pat_id=authorizations.Pat_id
								 	                 #and insurance.`Auth_#`=authorizations.`Auth_#`
								 	                 and insurance.Discipline=authorizations.Discipline
								 	                 where insurance.Pat_id is null and authorizations.status=1
								 	                 and authorizations.Discipline='OT' and authorizations.CPT='97530'
								 	                 and  date(now())>=insurance.Auth_thru 
								 	                 and date(now())<=authorizations.Auth_start  
	)
	OR
	patients.Pat_id in (select careplans.Patient_ID from careplans where date(now())>=careplans.POC_due
									 and careplans.`status`=1 and careplans.Discipline like 'OT%')
	
	)
".$having."
	) as OT ,
	
	(select count( distinct insurance.Pat_id) as count
 

from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
 
										
where (  patients.Pri_Ins!='MOLINA HEALTHCARE OF FLORIDA' and  patients.Pri_Ins not like 'MEDICARE%'
		and  insurance.Discipline='PT' and insurance.CPT='97110'  and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru 	or insurance.Visits_remen<=1)	)

or   

( insurance.Visits_remen=1 and   insurance.Discipline='PT' and (insurance.CPT='97001' or insurance.CPT='97002')  and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru )	)

or
(
patients.Pat_id in (select  authorizations.Pat_id  from authorizations
								join patients on authorizations.Pat_id=patients.Pat_id
								and patients.active=1
								 left join insurance on insurance.Pat_id=authorizations.Pat_id
								 	                 and insurance.`Auth_#`=authorizations.`Auth_#`
								 	                 and insurance.Discipline=authorizations.Discipline
								 	                 where insurance.Pat_id is null and authorizations.status=1
								 	                 and authorizations.Discipline='PT' and authorizations.CPT='97110'
								 	                 and  date(now())>=insurance.Auth_thru 
								 	                 and date(now())<=authorizations.Auth_start  
	)
	OR
	patients.Pat_id in (select careplans.Patient_ID from careplans where date(now())>=careplans.POC_due
									 and careplans.`status`=1 and careplans.Discipline like 'PT%')
	
	)
".$having."
	) as PT ,
	


	(select count( distinct insurance.Pat_ID) as count
 

from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
 
										
where (   patients.Pri_Ins!='MOLINA HEALTHCARE OF FLORIDA'  and  patients.Pri_Ins not like 'MEDICARE%'
		and  insurance.Discipline='ST' and insurance.CPT='92507'  and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru 	or insurance.Visits_remen<=1)	)

or   

(  insurance.Visits_remen=1 and  insurance.Discipline='ST' and insurance.CPT='92523'  and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru )	)

 
or
(
patients.Pat_id in (select  authorizations.Pat_id  from authorizations
								join patients on authorizations.Pat_id=patients.Pat_id
								and patients.active=1
								 left join insurance on insurance.Pat_id=authorizations.Pat_id
								 	                 and insurance.`Auth_#`=authorizations.`Auth_#`
								 	                 and insurance.Discipline=authorizations.Discipline
								 	                 where insurance.Pat_id is null and authorizations.status=1
								 	                 and authorizations.Discipline='ST' and authorizations.CPT='92507'
								 	                 and  date(now())>=insurance.Auth_thru 
								 	                 and date(now())<=authorizations.Auth_start  
	)
	OR
	patients.Pat_id in (select careplans.Patient_ID from careplans where date(now())>=careplans.POC_due
									 and careplans.`status`=1 and careplans.Discipline like 'ST%')
	
	)
".$having."
	) as ST
	
	

";


$result12 = mysqli_query($conexion, $query12);




$query13 = "
select
(select  coaleSce(count(subcount),0)
   			 from (

select  count(distinct campo_3) as subcount ,patients.Pat_id
   			  from patients
   			  join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
					and patients.active=1
					join cpt on tbl_treatments.campo_11=cpt.cpt
					and tbl_treatments.campo_10=cpt.Discipline
 where 
 TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 18
 
 and type='TX'
 
 and tbl_treatments.campo_10='OT'

 and tbl_treatments.adults_progress_notes=0
 
 group by Pat_id
 having subcount >=10  and subcount <50      ) as OT) as OT,
 
 (select  coaleSce(count(subcount),0)
   			 from (

select  count(distinct campo_3) as subcount ,patients.Pat_id
   			  from patients
   			  join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
					and patients.active=1
					join cpt on tbl_treatments.campo_11=cpt.cpt
					and tbl_treatments.campo_10=cpt.Discipline
 where 
 TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 18
 
 and type='TX'
 
 and tbl_treatments.campo_10='PT'

 and tbl_treatments.adults_progress_notes=0
 
 group by Pat_id
 having subcount >=10    and subcount <50           ) as PT) as PT,
 
 (select  coaleSce(count(subcount),0)
   			 from (

select  count(distinct campo_3) as subcount ,patients.Pat_id
   			  from patients
   			  join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
					and patients.active=1
					join cpt on tbl_treatments.campo_11=cpt.cpt
					and tbl_treatments.campo_10=cpt.Discipline
 where 
 TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 18
 
 and type='TX'
 
 and tbl_treatments.campo_10='ST'

 and tbl_treatments.adults_progress_notes=0
 
 group by Pat_id
 having subcount >=10    and  subcount <50            ) as ST) as ST

";

$result13 = mysqli_query($conexion, $query13);



$query14 = "
select
(select  coaleSce(count(subcount),0)
   			 from (

select  count(distinct campo_3) as subcount ,patients.Pat_id
   			  from patients
   			  join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
					and patients.active=1
					join cpt on tbl_treatments.campo_11=cpt.cpt
					and tbl_treatments.campo_10=cpt.Discipline
 where 
 TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 18
 
 and type='TX'
 
 and tbl_treatments.campo_10='OT'

 and tbl_treatments.pedriatics_progress_notes=0
 
 group by Pat_id
 having subcount >=20       ) as OT) as OT,
 
 (select  coaleSce(count(subcount),0)
   			 from (

select  count(distinct campo_3) as subcount ,patients.Pat_id
   			  from patients
   			  join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
					and patients.active=1
					join cpt on tbl_treatments.campo_11=cpt.cpt
					and tbl_treatments.campo_10=cpt.Discipline
 where 
 TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 18
 
 and type='TX'
 
 and tbl_treatments.campo_10='PT'

 and tbl_treatments.pedriatics_progress_notes=0
 
 group by Pat_id
 having subcount >=20            ) as PT) as PT,
 
 (select  coaleSce(count(subcount),0)
   			 from (

select  count(distinct campo_3) as subcount ,patients.Pat_id
   			  from patients
   			  join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
					and patients.active=1
					join cpt on tbl_treatments.campo_11=cpt.cpt
					and tbl_treatments.campo_10=cpt.Discipline
 where 
 TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 18
 
 and type='TX'
 
 and tbl_treatments.campo_10='ST'

 and tbl_treatments.pedriatics_progress_notes=0
 
 group by Pat_id
 having subcount >=20               ) as ST) as ST

";

$result14 = mysqli_query($conexion, $query14);




///////////////////////////////  15 //////////////////////////////////////////////////////////////////////////////////


$query15 = "
select 'OT' as OT , 'PT' as PT, 'ST' as ST 

";

$result15 = mysqli_query($conexion, $query15);





            ?>
           
			
			
            <tr>
                <td style="font-size: 12pt;font-weight: 700;">RX REQUEST</td>
                <?php
                	while($row = mysqli_fetch_array($result3,MYSQLI_ASSOC)){
	   			
	   			if($row['OT']>='15')  		
	   	echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED NO PRESCRIPTION\')" target="_blank">'.$row['OT'].'</a></td>';
	   			else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED NO PRESCRIPTION\')" target="_blank">'.$row['OT'].'</a></td>';

	   			if($row['PT']>='15') 
	    echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED NO PRESCRIPTION\')" target="_blank">'.$row['PT'].'</a></td>';
				else echo '  <td align="center"><a onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED NO PRESCRIPTION\')" target="_blank">'.$row['PT'].'</a></td>';


					  if($row['ST']>='15') 
	    echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED NO PRESCRIPTION\')" target="_blank">'.$row['ST'].'</a></td>  ';
	else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED NO PRESCRIPTION\')" target="_blank">'.$row['ST'].'</a></td>  ';
					}
			    ?>
			</tr>

			<tr>
                <td style="font-size: 12pt;font-weight: 700;">WAITING RX</td>

                <?php
               	while($row = mysqli_fetch_array($result4,MYSQLI_ASSOC)){
	   			
               	if($row['OT']>='15')  		
	   	echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR PRESCRIPTION\')" target="_blank">'.$row['OT'].'</a></td>';
	   else  	echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'WAITING FOR PRESCRIPTION\')" target="_blank">'.$row['OT'].'</a></td>';

				      if($row['PT']>='15')  		
	   	echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR PRESCRIPTION\')" target="_blank">'.$row['PT'].'</a></td>';
	   else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'WAITING FOR PRESCRIPTION\')" target="_blank">'.$row['PT'].'</a></td>';
		
					  if($row['ST']>='15')  		
	   	echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;"  onclick="abrirVentana(\'ST\',92507,\'WAITING FOR PRESCRIPTION\')" target="_blank">'.$row['ST'].'</a></td>  ';
	   else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'WAITING FOR PRESCRIPTION\')" target="_blank">'.$row['ST'].'</a></td>  ';
					}
			    ?>
			</tr>

     
            <tr>
                <td style="font-size: 12pt;font-weight: 700;">REQUEST EVAL AUTH</td>
                 <?php
                	while($row = mysqli_fetch_array($result5,MYSQLI_ASSOC)){

if($row['OT']>='15')  		
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\')" target="_blank">'.$row['OT'].'</a></td>';
	   else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\')" target="_blank">'.$row['OT'].'</a></td>';

if($row['PT']>='15')  		
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\')" target="_blank">'.$row['PT'].'</a></td>';
	   else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\')" target="_blank">'.$row['PT'].'</a></td>';

	if($row['ST']>='15')  		
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\')" target="_blank">'.$row['ST'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\')" target="_blank">'.$row['ST'].'</a></td>  ';

					}
			    ?>
            </tr>

            <tr>
                <td style="font-size: 12pt;font-weight: 700;">WAITING EVAL AUTH</td>


             
                <?php
              	while($row = mysqli_fetch_array($result05,MYSQLI_ASSOC)){
	   			
if($row['OT']>='15')  		
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR AUTH EVAL\')" target="_blank">'.$row['OT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'WAITING FOR AUTH EVAL\')" target="_blank">'.$row['OT'].'</a></td>';
				      
if($row['PT']>='15')  		
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR AUTH EVAL\')" target="_blank">'.$row['PT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'WAITING FOR AUTH EVAL\')" target="_blank">'.$row['PT'].'</a></td>';	

if($row['ST']>='15')  		
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR AUTH EVAL\')" target="_blank">'.$row['ST'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'WAITING FOR AUTH EVAL\')" target="_blank">'.$row['ST'].'</a></td>';

					}
			    ?>
			</tr>


			 <tr>
                <td style="font-size: 12pt;font-weight: 700;">EVAL PATIENT</td> 
                <?php   //todavia no le han realizado la evaluacion
                	while($row = mysqli_fetch_array($result6,MYSQLI_ASSOC)){

               if($row['OT']>='15')  		
	   			echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'NEED EVALUTATION\')" target="_blank">'.$row['OT'].'</a></td>';
	   		else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'NEED EVALUTATION\')" target="_blank">'.$row['OT'].'</a></td>';

	   		if($row['PT']>='15') 
	   echo '    <td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'NEED EVALUTATION\')" target="_blank">'.$row['PT'].'</a></td>';
	else 	echo '    <td align="center"><a onclick="abrirVentana(\'PT\',97110,\'NEED EVALUTATION\')" target="_blank">'.$row['PT'].'</a></td>';

					if($row['ST']>='15') 
	   echo '  <td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'ST\',92507,\'NEED EVALUTATION\')" target="_blank">'.$row['ST'].'</a></td>  ';
	else  echo '  <td align="center"><a onclick="abrirVentana(\'ST\',92507,\'NEED EVALUTATION\')" target="_blank">'.$row['ST'].'</a></td>  ';
					}
			    ?>
            </tr>

			<tr>
                <td style="font-size: 12pt;font-weight: 700;">REQUEST DOCTOR SIGNATURE</td> 
                <?php   //todavia no le han realizado la evaluacion
                	while($row = mysqli_fetch_array($result8,MYSQLI_ASSOC)){
	   			if($row['OT']>='15') 
	   	echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'NOT SIGNED BY DOCTOR YET\')" target="_blank">'.$row['OT'].'</a></td>';
				      else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'NOT SIGNED BY DOCTOR YET\')" target="_blank">'.$row['OT'].'</a></td>';
	   					
	   					if($row['PT']>='15') 
	   echo ' <td align="center"><a style="font-size: 12	pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'NOT SIGNED BY DOCTOR YET\')" target="_blank">'.$row['PT'].'</a></td>';
		else echo ' <td align="center"><a onclick="abrirVentana(\'PT\',97110,\'NOT SIGNED BY DOCTOR YET\')" target="_blank">'.$row['PT'].'</a></td>';

					  	if($row['ST']>='15') 
	   echo ' <td align="center"><a style="font-size: 12pt;font-weight: 900; onclick="abrirVentana(\'ST\',92507,\'NOT SIGNED BY DOCTOR YET\')" target="_blank">'.$row['ST'].'</a></td>  ';
	   		else echo ' <td align="center"><a onclick="abrirVentana(\'ST\',92507,\'NOT SIGNED BY DOCTOR YET\')" target="_blank">'.$row['ST'].'</a></td>  ';
					}
			    ?>
            </tr>

			<tr>
                <td style="font-size: 12pt;font-weight: 700;">WAITING DOCTOR SIGNATURE</td> 


                 <?php   //todavia no le han realizado la evaluacion
                 	while($row = mysqli_fetch_array($result9,MYSQLI_ASSOC)){
 

 	if($row['OT']>='15') 
	   	echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR DOCTOR SIGNATURE\')" target="_blank">'.$row['OT'].'</a></td>';
	     else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'WAITING FOR DOCTOR SIGNATURE\')" target="_blank">'.$row['OT'].'</a></td>';
 
  	if($row['PT']>='15') 
	   echo ' <td align="center"><a style="font-size: 12pt;font-weight: 900;"  onclick="abrirVentana(\'PT\',97110,\'WAITING FOR DOCTOR SIGNATURE\')" target="_blank">'.$row['PT'].'</a></td>';
  		 else echo 	'<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'WAITING FOR DOCTOR SIGNATURE\')" target="_blank">'.$row['PT'].'</a></td>';

    if($row['ST']>='15') 
	   echo ' <td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR DOCTOR SIGNATURE\')" target="_blank">'.$row['ST'].'</a></td>  ';
		 else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'WAITING FOR DOCTOR SIGNATURE\')" target="_blank">'.$row['ST'].'</a></td>  ';
					}
			    ?> 
            </tr> 

			<tr>
                <td style="font-size: 12pt;font-weight: 700;">REQUEST AUTH TX</td>


                 <?php
                	while($row = mysqli_fetch_array($result7,MYSQLI_ASSOC)){
	   			
		if($row['OT']>='15') 
	   	echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'ASK FOR AUTHORIZATION FOR TX\')" target="_blank">'.$row['OT'].'</a></td>';
	  else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'ASK FOR AUTHORIZATION FOR TX\')" target="_blank">'.$row['OT'].'</a></td>';
				     
		 if($row['PT']>='15') 
	   echo ' <td align="center"><a style="font-size: 12pt;font-weight: 900;"  onclick="abrirVentana(\'PT\',97110,\'ASK FOR AUTHORIZATION FOR TX\')" target="_blank">'.$row['PT'].'</a></td>';
		else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'ASK FOR AUTHORIZATION FOR TX\')" target="_blank">'.$row['PT'].'</a></td>';

		if($row['ST']>='15') 
	   echo ' <td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'ST\',92507,\'ASK FOR AUTHORIZATION FOR TX\')" target="_blank">'.$row['ST'].'</a></td> ';
		else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'ASK FOR AUTHORIZATION FOR TX\')" target="_blank">'.$row['ST'].'</a></td> ';

					}
			    ?>
            </tr>


            <tr>
                <td style="font-size: 12pt;font-weight: 700;">WAITING AUTH TX</td> 



                 <?php   //todavia no le han realizado la evaluacion
                	while($row = mysqli_fetch_array($result07,MYSQLI_ASSOC)){

if($row['OT']>='15') 
 echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR AUTHORIZATION TX\')" target="_blank">'.$row['OT'].'</a></td>';
else  echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'WAITING FOR AUTHORIZATION TX\')" target="_blank">'.$row['OT'].'</a></td>';

if($row['PT']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR AUTHORIZATION TX\')" target="_blank">'.$row['PT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'WAITING FOR AUTHORIZATION TX\')" target="_blank">'.$row['PT'].'</a></td>';

if($row['ST']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR AUTHORIZATION TX\')" target="_blank">'.$row['ST'].'</a></td> ';
else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'WAITING FOR AUTHORIZATION TX\')" target="_blank">'.$row['ST'].'</a></td> ';

				
				}
			    ?> 
            </tr> 

           
           <tr>
                <td style="font-size: 12pt;font-weight: 700;">READY FOR TREATMENT</td>
                  <?php
               	while($row = mysqli_fetch_array($result11,MYSQLI_ASSOC)){

if($row['OT']>='15') 	   	
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'READY FOR TREATMENT\')" target="_blank">'.$row['OT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'READY FOR TREATMENT\')" target="_blank">'.$row['OT'].'</a></td>';

if($row['PT']>='15') 	   	
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'READY FOR TREATMENT\')" target="_blank">'.$row['PT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'READY FOR TREATMENT\')" target="_blank">'.$row['PT'].'</a></td>';
		

if($row['ST']>='15') 	   	
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'ST\',92507,\'READY FOR TREATMENT\')" target="_blank">'.$row['ST'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'READY FOR TREATMENT\')" target="_blank">'.$row['ST'].'</a></td>';
				
				}
			    ?> 
            </tr>

             <tr>
                <td style="font-size: 12pt;font-weight: 700;">PATIENTS ON HOLD</td>
                  <?php
               	while($row = mysqli_fetch_array($result12,MYSQLI_ASSOC)){

if($row['OT']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'PATIENTS ON HOLD\')" target="_blank">'.$row['OT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'PATIENTS ON HOLD\')" target="_blank">'.$row['OT'].'</a></td>';
			 
if($row['PT']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'PATIENTS ON HOLD\')" target="_blank">'.$row['PT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'PATIENTS ON HOLD\')" target="_blank">'.$row['PT'].'</a></td>';		 

if($row['ST']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 800;" onclick="abrirVentana(\'ST\',92507,\'PATIENTS ON HOLD\')" target="_blank">'.$row['ST'].'</a></td>  ';
else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'PATIENTS ON HOLD\')" target="_blank">'.$row['ST'].'</a></td>  ';	
				}
			    ?> 
            </tr>

           
            

             <tr>
                <td style="font-size: 12pt;font-weight: 700;">PROGRESS NOTES ADULTS</td>
                  <?php
               	while($row = mysqli_fetch_array($result13,MYSQLI_ASSOC)){

if($row['OT']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'PROGRESS NOTES ADULTS\')" target="_blank">'.$row['OT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'PROGRESS NOTES ADULTS\')" target="_blank">'.$row['OT'].'</a></td>';
			 
if($row['PT']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'PROGRESS NOTES ADULTS\')" target="_blank">'.$row['PT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'PROGRESS NOTES ADULTS\')" target="_blank">'.$row['PT'].'</a></td>';		 

if($row['ST']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 800;" onclick="abrirVentana(\'ST\',92507,\'PROGRESS NOTES ADULTS\')" target="_blank">'.$row['ST'].'</a></td>  ';
else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'PROGRESS NOTES ADULTS\')" target="_blank">'.$row['ST'].'</a></td>  ';	
				}
			    ?> 
            </tr>



             <tr>
                <td style="font-size: 12pt;font-weight: 700;">PROGRESS NOTES PEDRIATICS</td>
                  <?php
               	while($row = mysqli_fetch_array($result14,MYSQLI_ASSOC)){

if($row['OT']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'PROGRESS NOTES PEDRIATICS\')" target="_blank">'.$row['OT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'PROGRESS NOTES PEDRIATICS\')" target="_blank">'.$row['OT'].'</a></td>';
			 
if($row['PT']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'PROGRESS NOTES PEDRIATICS\')" target="_blank">'.$row['PT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'PROGRESS NOTES PEDRIATICS\')" target="_blank">'.$row['PT'].'</a></td>';		 

if($row['ST']>='15') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 800;" onclick="abrirVentana(\'ST\',92507,\'PROGRESS NOTES PEDRIATICS\')" target="_blank">'.$row['ST'].'</a></td>  ';
else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'PROGRESS NOTES PEDRIATICS\')" target="_blank">'.$row['ST'].'</a></td>  ';	
				}
			    ?> 
            </tr>



             <tr>
                <td style="font-size: 12pt;font-weight: 700;">ACTIVE PATIENTS NOT BEEN SEEN</td>
                  <?php
               	while($row = mysqli_fetch_array($result15,MYSQLI_ASSOC)){

if($row['OT']>='100') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'OT\',97530,\'ACTIVE PATIENTS NOT BEEN SEEN\')" target="_blank">'.$row['OT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'OT\',97530,\'ACTIVE PATIENTS NOT BEEN SEEN\')" target="_blank">'.$row['OT'].'</a></td>';
			 
if($row['PT']>='100') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 900;" onclick="abrirVentana(\'PT\',97110,\'ACTIVE PATIENTS NOT BEEN SEEN\')" target="_blank">'.$row['PT'].'</a></td>';
else echo '<td align="center"><a onclick="abrirVentana(\'PT\',97110,\'ACTIVE PATIENTS NOT BEEN SEEN\')" target="_blank">'.$row['PT'].'</a></td>';		 

if($row['ST']>='100') 
echo '<td align="center"><a style="font-size: 12pt;font-weight: 800;" onclick="abrirVentana(\'ST\',92507,\'ACTIVE PATIENTS NOT BEEN SEEN\')" target="_blank">'.$row['ST'].'</a></td>  ';
else echo '<td align="center"><a onclick="abrirVentana(\'ST\',92507,\'ACTIVE PATIENTS NOT BEEN SEEN\')" target="_blank">'.$row['ST'].'</a></td>  ';	
				}
			    ?> 
            </tr>



        </tbody>
    </table>
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
