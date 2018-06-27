<?php



function queryPOCExpired($cpt,$discipline){
	

$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}
//echo $_GET['typeAge'];
//echo $_GET['typeAge'];

	$queryPOCExpired = "
	select  t.Pat_id as Pat_id,  t.Patient_name as Patient_name, t.Company as Company,
t.POC_due as POC_due, t.Insurance_name as Insurance_name , t.AGE, t.physician as Physician

   			 from
				 
				  ( select  CONCAT(trim(careplans.Last_name),',',trim(careplans.First_name)) as Patient_name,
  careplans.Company,  careplans.POC_due, careplans.Patient_ID as Pat_id,	
  patients.Pri_Ins as Insurance_name, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE, patients.DOB
  ,patients.PCP as physician
   			 
				  from careplans 
	 join patients on careplans.Patient_ID=patients.Pat_id
	 			and patients.active=1



 where 
 			
			careplans.status=1 
			# and patients.active=1
			AND careplans.mail_sent=0
			 and careplans.Discipline like '".$discipline."%' 
			  #and careplans.POC_due>=date(now()) 
			and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
			".$having."
			 and	datediff (careplans.POC_due,date(now()))<=45 
 			and
			(
careplans.Patient_ID not  in (select prescription.Patient_ID from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date)	and
 prescription.Discipline='".$discipline."' 
 and prescription.status=1
 #and prescription.Eval_done=0
 )
) group by careplans.Patient_ID  

order by careplans.Last_name asc

							) t 
							
							order by  t.Patient_name ASC
							
							";
				return $queryPOCExpired;

}


function queryWAITINGPRESCRIPTION($cpt,$discipline){

$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}
//echo $_GET['typeAge'];
//echo $_GET['typeAge'];



	$queryPOCExpired = "
	select  t.Pat_id as Pat_id,  t.Patient_name as Patient_name, t.Company as Company,
t.POC_due as POC_due, t.Insurance_name as Insurance_name , t.Date_sent, t.Days_Waiting, t.AGE, t.physician as Physician

   			 from
				 
				  ( select  CONCAT(trim(careplans.Last_name),',',trim(careplans.First_name)) as Patient_name,
  careplans.Company,  careplans.POC_due, careplans.Patient_ID as Pat_id,	
  patients.Pri_Ins as Insurance_name, mail_sent_time as Date_sent  , DATEDIFF(date(now()),date(mail_sent_time)) as Days_Waiting 
  , TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE
   ,patients.PCP as physician
   			 
				  from careplans 
	 join patients on careplans.Patient_ID=patients.Pat_id
	 				and patients.active=1


 where 
			 careplans.status=1 
			# and patients.active=1
			 AND careplans.mail_sent=1
			 and careplans.Discipline='".$discipline."' 
			  #and careplans.POC_due>=date(now()) 
			and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
			".$having."
			 and	datediff (careplans.POC_due,date(now()))<=45 
 			and
			(
careplans.Patient_ID not  in (select prescription.Patient_ID from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date)	and 
prescription.Discipline='".$discipline."' 
 and prescription.status=1
 #and prescription.Eval_done=0
 )
) group by careplans.Patient_ID 

 order by careplans.Last_name asc

							) t 
							
							order by  t.Patient_name ASC
							
							";
				return $queryPOCExpired;

}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function queryPatientDetail($pat_id,$discipline){
	$queryPatientDetail = "select distinct insurance.CPT, insurance.Auth_Start , insurance.Auth_thru , careplans.POC_due , 
	insurance.Company as CO, insurance.Discipline
	from insurance join careplans on insurance.Pat_id=careplans.Patient_ID
	where insurance.Discipline like '".$discipline."%' and insurance.Pat_id='".$pat_id."'
	and
	 careplans.Discipline='".$discipline."' 
	 group by insurance.CPT

	";
	
	return $queryPatientDetail;
} 



function queryPrescription($cpt,$discipline){
	$queryPrescription = " select  t.Patient_name as Patient_name, t.Company as Company, t.`Auth_#` as Authorization,
t.Auth_thru as Auth_thru, t.Visits_remen as Visit_remen, 
t.POC_due as POC_due, t.Insurance_name as Insurance_name , t.Ref_Physician as Physician, 
t.Office_phone as Office_phone , t.Mobile_phone as Mobile, t.Fax as Fax
   			
				 from ( select  insurance_dlc.Patient_name, insurance_dlc.Company, insurance_dlc.`Auth_#`, 
				 insurance_dlc.Auth_thru ,
   			 insurance_dlc.Visits_remen, careplans_dlc.POC_due, insurance_dlc.Insurance_name,
   			 patients_dlc.Ref_Physician , physician.Office_phone , physician.Mobile_phone, physician.Fax
				 
				  from patients_dlc 
									join insurance_dlc on patients_dlc.Pat_id=insurance_dlc.Pat_id 
									join careplans_dlc on patients_dlc.Pat_id=careplans_dlc.Patient_ID
									join physician on patients_dlc.Phy_NPI=physician.NPI
												left join prescription on patients_dlc.Pat_id=prescription.Patient_ID
												where insurance_dlc.CPT='".$cpt."' and 
												(
												(datediff (insurance_dlc.Auth_thru,now())<7  
												or  insurance_dlc.Visits_Auth - insurance_dlc.Visits_remen<7 )
												and insurance_dlc.Auth_thru>now()
												and datediff(careplans_dlc.POC_due,now())<20
												and careplans_dlc.Discipline like '".$discipline."%'
 												)
												and
 												(
 												insurance_dlc.Pat_id not in (select prescription.Patient_ID from prescription)
                    						 or
                     					now() not between prescription.Issue_date and prescription.End_date	
 												)
 												group by insurance_dlc.`Auth_#`
 												
         			  union 
         			  select    insurance_kqt.Patient_name, insurance_kqt.Company, insurance_kqt.`Auth_#`,
						  insurance_kqt.Auth_thru ,
   			 insurance_kqt.Visits_remen, careplans_kqt.POC_due , insurance_kqt.Insurance_name,
   			 patients_kqt.Ref_Physician, physician.Office_phone , physician.Mobile_phone, physician.Fax
   			 
						   from patients_kqt
									join insurance_kqt on patients_kqt.Pat_id=insurance_kqt.Pat_id 
									join careplans_kqt on patients_kqt.Pat_id=careplans_kqt.Patient_ID
									join physician on patients_kqt.Phy_NPI=physician.NPI
												left join prescription on patients_kqt.Pat_id=prescription.Patient_ID
												where insurance_kqt.CPT='".$cpt."' and 
												(
												(datediff (insurance_kqt.Auth_thru,now())<7  
												or  insurance_kqt.Visits_Auth - insurance_kqt.Visits_remen<7 )
												and insurance_kqt.Auth_thru>now()
												and datediff(careplans_kqt.POC_due,now())<20
												and careplans_kqt.Discipline like '".$discipline."%'
 												)
												and
 												(
 												insurance_kqt.Pat_id not in (select prescription.Patient_ID from prescription)
                    						 or
                     					now() not between prescription.Issue_date and prescription.End_date	
 												)
          		                     group by insurance_kqt.`Auth_#`
							
						union 
							
			select	insurance_dlcquality.Patient_name, insurance_dlcquality.Company, insurance_dlcquality.`Auth_#`,
							insurance_dlcquality.Auth_thru , insurance_dlcquality.Visits_remen, 
							careplans_dlcquality.POC_due , insurance_dlcquality.Insurance_name,
   			 patients_dlcquality.Ref_Physician, physician.Office_phone , physician.Mobile_phone, physician.Fax
				 
				           from patients_dlcquality 
									join insurance_dlcquality on patients_dlcquality.Pat_id=insurance_dlcquality.Pat_id 
									join careplans_dlcquality on patients_dlcquality.Pat_id=careplans_dlcquality.Patient_ID
								   join physician on patients_dlcquality.Phy_NPI=physician.NPI
									left join prescription on patients_dlcquality.Pat_id=prescription.Patient_ID
									where insurance_dlcquality.CPT='".$cpt."' and 
												(
												(datediff (insurance_dlcquality.Auth_thru,now())<7  
												or  insurance_dlcquality.Visits_Auth - insurance_dlcquality.Visits_remen<7 )
												and insurance_dlcquality.Auth_thru>now()
												and datediff(careplans_dlcquality.POC_due,now())<20
												and careplans_dlcquality.Discipline like '".$discipline."%'
												)	
												and
 												(
 												insurance_dlcquality.Pat_id not in (select prescription.Patient_ID from prescription)
                    						 or
                     					now() not between prescription.Issue_date and prescription.End_date	
 												)
												group by insurance_dlcquality.`Auth_#`			
							) t
							";
							
							
							
				return $queryPrescription;

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////

function queryAskForAuthEval($cpt,$discipline){
	
	if($discipline == 'PT'){
		$cpt1 = '97001';
		$cpt2 = '97002';	
	}else{
		if($discipline == 'OT'){
			$cpt1 = '97003';
			$cpt2 = '97004';	
		}else{
			$cpt1 = '92523';
			$cpt2 = '92523';	
		}
	}

		/////////////

	$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}


 $queryAskForAuthEval = "
		select  
 prescription.Patient_ID as Pat_id, CONCAT(trim(patients.Last_name),',',trim(patients.First_name)) as Patient_name, 
 patients.Table_name as Company, 
 #insurance.`Auth_#` as Authorization,
prescription.End_date as Auth_thru,
patients.Pri_Ins as Insurance_name , patients.Ref_Physician as Physician
, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE

 
 from
prescription


 join patients on prescription.Patient_ID=patients.Pat_id
    		and patients.active=1

where (
prescription.Patient_ID not in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='".$discipline."' 
								and cpt.type='EVAL' and insurance.status=1  ) 

and prescription.Discipline='".$discipline."' and prescription.status=1

#and patients.active=1


        

and prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and 
( patients.Pri_Ins='UNITED HEALTHCARE' or
			  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or 
			  patients.Pri_Ins='WELLCARE (STAYWELL)'
			  or patients.Pri_Ins='PRESTIGE'
			 # or  (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			   ) 	) 
		  
			  ) 
		 and prescription.mail_sent_eval=0
		 ".$having."

 	 group by prescription.Patient_ID
		
	";
	return $queryAskForAuthEval;
}


function queryWAITINGEVAL($cpt,$discipline){
	
	if($discipline == 'PT'){
		$cpt1 = '97001';
		$cpt2 = '97002';	
	}else{
		if($discipline == 'OT'){
			$cpt1 = '97003';
			$cpt2 = '97004';	
		}else{
			$cpt1 = '92523';
			$cpt2 = '92523';	
		}
	}


	/////////////

	$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}


 $queryWAITINGEVAL = "
		select  
 prescription.Patient_ID as Pat_id, CONCAT(trim(patients.Last_name),',',trim(patients.First_name)) as Patient_name, 
 patients.Table_name as Company, 
 #insurance.`Auth_#` as Authorization,
prescription.End_date as Auth_thru,
patients.Pri_Ins as Insurance_name , patients.Ref_Physician as Physician
,sent_eval_time as Date_sent  , DATEDIFF(date(now()),date(sent_eval_time)) as Days_Waiting 
, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE
   			 



 
 from
prescription


join patients on prescription.Patient_ID=patients.Pat_id
    		and patients.active=1

where (
prescription.Patient_ID not in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='".$discipline."' 
								and cpt.type='EVAL' and insurance.status=1  ) 

and prescription.Discipline='".$discipline."' and prescription.status=1

#and patients.active=1

and prescription.Patient_ID in (select patients.Pat_id from patients where #patients.active=1 and 
( patients.Pri_Ins='UNITED HEALTHCARE' or
			  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or 
			  patients.Pri_Ins='WELLCARE (STAYWELL)' 
			  or patients.Pri_Ins='PRESTIGE'
			 # or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			   ) 	) 
		  
			  ) 
			  and prescription.mail_sent_eval=1
			  ".$having."


 	 group by prescription.Patient_ID
		
	";
	return $queryWAITINGEVAL;
}




////////////////////////////////////////////////////////////////////////////////////////////////////////



function queryEvaluation($cpt,$discipline){

if($discipline == 'PT'){
		$cpt1 = '97001';
		$cpt2 = '97002';	
	}else{
		if($discipline == 'OT'){
			$cpt1 = '97003';
			$cpt2 = '97004';	
		}else{
			$cpt1 = '92523';
			$cpt2 = '92523';	
	    }
	}

	/////////////

	$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}

   $queryEvaluation = "
  
select  
 trim(patients.Pat_id) as Pat_id, CONCAT(trim(patients.Last_name),',',trim(patients.First_name)) as Patient_name, 
 prescription.Table_name as Company, 
# insurance.`Auth_#` as Authorization,
prescription.End_date as Auth_thru,
#careplans.POC_due,
prescription.Eval_schedule,
prescription.Eval_done,
patients.Pri_Ins as Insurance_name , patients.Ref_Physician as Physician
, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE

 
 from
prescription

#join insurance on patients.Pat_id=insurance.Pat_id
  join patients on prescription.Patient_ID=patients.Pat_id 
  		and patients.active=1

 

where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='".$discipline."' 
								and cpt.type='EVAL' and insurance.status=1  )	
			 and prescription.Discipline='".$discipline."'  and prescription.status=1	
			 #and patients.active=1	  

	 and prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and 
	 ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' 
			  OR   patients.Pri_Ins='PRESTIGE' 
			 # or (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  )  ) 
and prescription.status=1 and prescription.Discipline='".$discipline."' 
			  							  and prescription.Eval_done=0  
			  							  and patients.active=1
			  							  ".$having."


)
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where #patients.active=1 and 
  ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' 
			  AND  patients.Pri_Ins!='PRESTIGE' 
			 # and  (patients.Pri_Ins not like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
			  ) 	)  
			  and 
 prescription.status=1 and prescription.Discipline='".$discipline."' 
			  							  and prescription.Eval_done=0  
			  							  and patients.active=1
			  							  ".$having."
		) 
		
	  group by prescription.Patient_ID
									
							
   ";
return $queryEvaluation;
}





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	



	function queryAskforAuthTX($cpt,$discipline){


		/////////////

	$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}
	
	$queryAskforAuthTX = "
SELECT 
* FROM 
(
select   signed_doctor.Patient_name, signed_doctor.Table_name as Company, careplans.POC_due,
	CASE WHEN patients.Thi_Ins IS NOT NULL 
       THEN patients.Thi_Ins
       ELSE patients.Pri_Ins
END AS Insurance_name ,  patients.Ref_Physician as Physician 
	, signed_doctor.Patient_ID as Pat_id
	, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE


	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
					and signed_doctor.Discipline=careplans.Discipline
	               
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  				and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
										#and signed_doctor.Discipline=authorizations.Discipline
										
		where  signed_doctor.Patient_ID not in (select authorizations.Pat_id from authorizations
								where authorizations.Discipline='".$discipline."' and authorizations.status=1) 

	and signed_doctor.Discipline='".$discipline."' and signed_doctor.status=1 
	
	AND
	(
					(
					 careplans.mail_sent_tx=0
					AND careplans.Discipline='".$discipline."'
			   	 and careplans.status=1
	 				)
	 		OR
	 				(
	 				careplans.Patient_ID is null
	 	and signed_doctor.Patient_ID in (select prescription.Patient_ID from prescription where tx_request_sent=0 and Discipline='".$discipline."' and status=1)
					 )		
	 
	 )
	 and patients.active=1
	  ".$having."
	
	and  patients.Pri_Ins not like 'MOLINA%' 
	and  patients.Pri_Ins not like 'MEDICARE%'
	and  patients.Pri_Ins not like 'SELF PAY%'
	#and (patients.Pri_Ins like 'MOLINA%' and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())>=21)
	
  group by signed_doctor.Patient_ID
	

union distinct


 select  insurance.Patient_name, insurance.Company, careplans.POC_due, 
 	CASE WHEN patients.Thi_Ins IS NOT NULL 
       THEN patients.Thi_Ins
       ELSE patients.Pri_Ins
END AS Insurance_name,  patients.Ref_Physician as Physician
 	, insurance.Pat_id as Pat_id
 	, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE
		 
 			 
				 
		 		 
	 from insurance 
     # join physician on patients.Phy_NPI=physician.NPI
     join patients on insurance.Pat_id=patients.Pat_id
     and patients.active=1
   join careplans on insurance.Pat_id=careplans.Patient_ID
                and insurance.Discipline=careplans.Discipline
       left join cpt on cpt.Discipline=insurance.Discipline
        and cpt.cpt=insurance.CPT
        and   cpt.`type`='TX'
											 
 where 
(

	(#insurance.CPT='".$cpt."' 
		#and 
		 patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
		and  patients.Pri_Ins not like 'SELF PAY%'
		and  patients.Pri_Ins NOT like 'UNITED%'
		and  (patients.Pri_Ins NOT like 'SUNSHINE%' and patients.Pri_Ins NOT like 'ATA%' 
		and  patients.Pri_Ins NOT like 'AMERIGROUP%' and patients.Pri_Ins NOT like 'HUMANA%')
	and insurance.status=1 
	 #and patients.active=1
	and (datediff (insurance.Auth_thru,date(now()))<3  
		or  insurance.Visits_remen<=4 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(#insurance.CPT='".$cpt."' 
		#and 
		 (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
		or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
		##and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	 #and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<1  
		or  insurance.Visits_remen<=2 )
	#and insurance.Auth_thru>date(now())
	)
	
	OR
		(#insurance.CPT='".$cpt."' 
		#and 
		 (patients.Pri_Ins like 'UNITED%' 
		) 
		##and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	and insurance.status=1 
	# and patients.active=1
	and
	 (datediff (insurance.Auth_thru,date(now()))<7  
		or  insurance.Visits_remen<=7 )
	#and insurance.Auth_thru>date(now())
	)
)


														and 
																(
	insurance.Pat_id in (select careplans.Patient_ID from careplans 
	where	careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
	and careplans.Discipline like '".$discipline."%' and careplans.status=1 )
	and careplans.Discipline like '".$discipline."%' 
         			 and careplans.status=1

         			
         			 )
         		 and 
         			 
         			 (
	# patients.Pat_id not in (select prescription.Patient_ID from prescription 
	# where prescription.status=1 and prescription.Discipline='".$discipline."')
	# and
	 patients.Pat_id not in (select signed_doctor.Patient_ID from signed_doctor 
	 where signed_doctor.status=1 and signed_doctor.Discipline='".$discipline."')
						 )  
	
         		AND careplans.mail_sent_tx=0
         		AND careplans.Discipline='".$discipline."'
         		 #and patients.active=1
         		 ".$having."
         		
         	
         		
					group by insurance.Pat_id


union Distinct

select concat(patients.Last_name,', ',patients.First_name) as Patient_name, patients.Table_name, careplans.POC_due, 
 	CASE WHEN patients.Thi_Ins IS NOT NULL 
       THEN patients.Thi_Ins
       ELSE patients.Pri_Ins
END AS Insurance_name,  patients.Ref_Physician as Physician
 	, patients.Pat_id as Pat_id
 	, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE
		 
 			 
				 
		  from patients 
		  join physician on patients.Phy_NPI=physician.NPI
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
	 patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 and insurance.Discipline='".$discipline."'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
   				AND
	 (
	  (patients.Pat_id not in (select prescription.Patient_ID from	 prescription 
											where prescription.status=1 and prescription.Discipline='".$discipline."') )
	 OR
	 (patients.Pat_id in (select prescription.Patient_ID from	 prescription 
	 								left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
	 														and prescription.Discipline=signed_doctor.Discipline
				where 	(prescription.status=1 and prescription.Discipline='".$discipline."' and signed_doctor.Patient_ID is not null
							and signed_doctor.status=1)
								 )
										
		
				 )
	 )
     
)
)
 		and careplans.status=1
		and careplans.mail_sent_tx=0   
		and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 	
		AND careplans.Discipline='".$discipline."'
		 ".$having."
		
		group by patients.Pat_id	

					
)
AS T

GROUP BY T.Pat_id order by Patient_name ASC
	";
	
	return $queryAskforAuthTX;
	
	
	}











	function queryWAITINGTX($cpt,$discipline){


		$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}
	
	$queryWAITINGTX = "
SELECT 
* FROM 
(
select   signed_doctor.Patient_name, signed_doctor.Table_name as Company, careplans.POC_due,
	CASE WHEN patients.Thi_Ins IS NOT NULL 
       THEN patients.Thi_Ins
       ELSE patients.Pri_Ins
END AS Insurance_name ,  patients.Ref_Physician as Physician 
	, signed_doctor.Patient_ID as Pat_id
	, tx_sent_time as Date_sent  , DATEDIFF(date(now()),date(tx_sent_time)) as Days_Waiting 
, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE


	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
					and signed_doctor.Discipline=careplans.Discipline
	               
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  				and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
					#					and signed_doctor.Discipline=authorizations.Discipline
										
		where  signed_doctor.Patient_ID not in (select authorizations.Pat_id from authorizations
								where authorizations.Discipline='".$discipline."' and authorizations.status=1) 


	and signed_doctor.Discipline='".$discipline."' and signed_doctor.status=1 

    and patients.active=1
	AND
	(
					(
					 careplans.mail_sent_tx=1
					AND careplans.Discipline='".$discipline."'
			   	 and careplans.status=1
	 				)
	 		OR
	 				(
	 				careplans.Patient_ID is null
	 				and signed_doctor.Patient_ID in (select prescription.Patient_ID from prescription  where tx_request_sent=1 and Discipline='".$discipline."' and status=1)
					 )		
	 
	 )
	and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	and  patients.Pri_Ins not like 'SELF PAY%'
	 ".$having."
	
  group by signed_doctor.Patient_ID
	

union distinct


 select  insurance.Patient_name, insurance.Company, careplans.POC_due, 
 	CASE WHEN patients.Thi_Ins IS NOT NULL 
       THEN patients.Thi_Ins
       ELSE patients.Pri_Ins
END AS Insurance_name,  patients.Ref_Physician as Physician
 	, insurance.Pat_id as Pat_id
 	, tx_sent_time as Date_sent  , DATEDIFF(date(now()),date(tx_sent_time)) as Days_Waiting 
		 , TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE
 			 
				 
		from insurance 
     # join physician on patients.Phy_NPI=physician.NPI
     join patients on insurance.Pat_id=patients.Pat_id
     and patients.active=1
   join careplans on insurance.Pat_id=careplans.Patient_ID
                and insurance.Discipline=careplans.Discipline

     left join cpt on cpt.Discipline=insurance.Discipline
        and cpt.cpt=insurance.CPT
        and   cpt.`type`='TX'
											 


 where 
(

	(#insurance.CPT='".$cpt."' 
		#and  
		patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
		and  patients.Pri_Ins not like 'SELF PAY%'
		and  patients.Pri_Ins NOT like 'UNITED%'
		and  (patients.Pri_Ins NOT like 'SUNSHINE%' and patients.Pri_Ins NOT like 'ATA%' 
		and patients.Pri_Ins NOT like 'AMERIGROUP%' and patients.Pri_Ins NOT like 'HUMANA%')
	and insurance.status=1 
	 #and patients.active=1
	and (datediff (insurance.Auth_thru,date(now()))<3  
		or  insurance.Visits_remen<=4 )
	#and insurance.Auth_thru>date(now())
	)
	OR
	(#insurance.CPT='".$cpt."' 
		#and  
		(patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
		or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
		and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	 #and patients.active=1
	and insurance.status=1 and
	 (datediff (insurance.Auth_thru,date(now()))<1  
		or  insurance.Visits_remen<=2 )
	#and insurance.Auth_thru>date(now())
	)
	
	OR
		(#insurance.CPT='".$cpt."' 
		#and  
		(patients.Pri_Ins like 'UNITED%' 
		) 
		and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	and insurance.status=1 
	# and patients.active=1
	and
	 (datediff (insurance.Auth_thru,date(now()))<7  
		or  insurance.Visits_remen<=7 )
	#and insurance.Auth_thru>date(now())
	)
)



AND 
																(
	insurance.Pat_id in (select careplans.Patient_ID from careplans 
	where	careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
	and careplans.Discipline='".$discipline."' and careplans.status=1 )
	and careplans.Discipline='".$discipline."' 
         			 and careplans.status=1

         			
         			 )
         		 and 
         			 
         			 (
	# patients.Pat_id not in (select prescription.Patient_ID from prescription 
	# where prescription.status=1 and prescription.Discipline='".$discipline."')
	# and
	 patients.Pat_id not in (select signed_doctor.Patient_ID from signed_doctor 
	 where signed_doctor.status=1 and signed_doctor.Discipline='".$discipline."')
						 )  
	
         		AND careplans.mail_sent_tx=1
         		AND careplans.Discipline='".$discipline."'
         		# and patients.active=1
         		 ".$having."
         		
					group by insurance.Pat_id
				

union Distinct

select concat(patients.Last_name,', ',patients.First_name) as Patient_name, patients.Table_name AS Company, careplans.POC_due, 
 	CASE WHEN patients.Thi_Ins IS NOT NULL 
       THEN patients.Thi_Ins
       ELSE patients.Pri_Ins
END AS Insurance_name,  patients.Ref_Physician as Physician
 	, patients.Pat_id as Pat_id 
 	, tx_sent_time as Date_sent  , DATEDIFF(date(now()),date(tx_sent_time)) as Days_Waiting 
	, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE	 
 			 
				 
		  from patients 
		  join physician on patients.Phy_NPI=physician.NPI
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
	 patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 and insurance.Discipline='".$discipline."'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
   				AND
	 (
	  (patients.Pat_id not in (select prescription.Patient_ID from	 prescription 
											where prescription.status=1 and prescription.Discipline='".$discipline."') )
	 OR
	 (patients.Pat_id in (select prescription.Patient_ID from	 prescription 
	 							left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
	 														and prescription.Discipline=signed_doctor.Discipline
				where  (prescription.status=1 and prescription.Discipline='".$discipline."' and signed_doctor.Patient_ID is not null
							and signed_doctor.status=1)
								 )
										
		
				 )
	 )
     
)
)
 		and careplans.status=1
 		and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
		and careplans.mail_sent_tx=1   	
		AND careplans.Discipline='".$discipline."'
		 ".$having."
		
		group by patients.Pat_id		


)
AS T

GROUP BY T.Pat_id order by Patient_name ASC
	";
	
	return $queryWAITINGTX;
	
	
	}





///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function querySignedDoctor($patient_id,$company){
        $querySignedDoctor = "
								

select @d:=Discipline as Discipline,

(select insurance.CPT FROM insurance                              
                               where insurance.Pat_id='".$patient_id."'    and insurance.Company='DLC' 
										 and insurance.Discipline=@d 
    and (insurance.CPT='97001' or insurance.CPT='97002' or insurance.CPT='97003' 
   or insurance.CPT='97004' or insurance.CPT='92523') order by insurance.Auth_thru DESC LIMIT 1 ) as CPT
,

(select insurance.`Auth_#` FROM insurance                              
                               where insurance.Pat_id='".$patient_id."'    and insurance.Company='DLC' 
										    and insurance.Discipline=@d 
  and (insurance.CPT='97001' or insurance.CPT='97002' or insurance.CPT='97003' 
 or insurance.CPT='97004' or insurance.CPT='92523')  order by insurance.Auth_thru DESC LIMIT 1 ) as `Auth_#`
	 


from prescription where prescription.Patient_ID='".$patient_id."'  and status=1 group by Discipline


  
  ";
        return $querySignedDoctor;
    }



function search_patient($patient_id,$company){
        $search_patient = "
select * 

from  



(Select Distinct  Pat_id, concat(Last_name,', ',First_name) as Patient_name, DOB, Pri_ins from patients_dlc  
where Pat_id='".$patient_id."' and Table_name='".$company."'  

union all
								
Select Distinct Pat_id, concat(Last_name,', ',First_name) as Patient_name , DOB, Pri_ins from patients_kqt  
where Pat_id='".$patient_id."' and Table_name='".$company."' 
								
			union all
							
							
Select Distinct  Pat_id, concat(Last_name,', ',First_name) as Patient_name , DOB, Pri_ins from patients_dlcquality 
where Pat_id='".$patient_id."' and Table_name='".$company."' 

) as r 
								
								
								
					


        ";
        return $search_patient;
    }




function export_excel_csv()
{
   
    
    $sql = "select Pat_id, Patient_name , DOB, Pri_ins 
 
from  



(Select Distinct  Pat_id, concat(Last_name,', ',First_name) as Patient_name, DOB, Pri_ins from patients_dlc  


union
								
Select Distinct Pat_id, concat(Last_name,', ',First_name) as Patient_name , DOB, Pri_ins from patients_kqt  

			union
							
							
Select Distinct  Pat_id, concat(Last_name,', ',First_name) as Patient_name , DOB, Pri_ins from patients_dlcquality 


) as r 
 
order by  Pri_ins, Patient_name";

    $rec = mysql_query($sql) or die (mysql_error());
    
    $num_fields = mysql_num_fields($rec);
    
    for($i = 0; $i < $num_fields; $i++ )
    {
        $header .= mysql_field_name($rec,$i)."\\t";
    }
    
    while($row = mysql_fetch_row($rec))
    {
        $line = '';
        foreach($row as $value)
        {                                            
            if((!isset($value)) || ($value == ""))
            {
                $value = "\\t";
            }
            else
            {
                $value = str_replace( '"' , '""' , $value );
                $value = '"' . $value . '"' . "\\t";
            }
            $line .= $value;
        }
        $data .= trim( $line ) . "\\n";
    }
    
    $data = str_replace("\\r" , "" , $data);
    
    if ($data == "")
    {
        $data = "\\n No Record Found!\n";                        
    }
    
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=reports.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$header\\n$data";


}

/////////////////////////////////// N O T    S I G N E D    D O C T O R /////////////////////////////////////////////////////////////////////////////

function querynotsigned($cpt,$discipline){


	if($discipline == 'PT'){
		$cpt1 = '97001';
		$cpt2 = '97002';	
	}else{
		if($discipline == 'OT'){
			$cpt1 = '97003';
			$cpt2 = '97004';	
		}else{
			$cpt1 = '92523';
			$cpt2 = '92523';	
	    }
	}


	/////////////

	$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}
	
	$querynotsigned = " 
select  
 trim(patients.Pat_id) as Pat_id, CONCAT(trim(patients.Last_name),',',trim(patients.First_name)) as Patient_name, 
 prescription.Table_name as Company, 
# insurance.`Auth_#` as Authorization,
prescription.End_date as Auth_thru,
prescription.Eval_schedule,
prescription.Eval_done,
patients.Pri_Ins as Insurance_name , patients.Ref_Physician as Physician
, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE

 
 from
prescription

#join insurance on patients.Pat_id=insurance.Pat_id
  join patients on prescription.Patient_ID=patients.Pat_id      
 			and patients.active=1

  left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
										and prescription.Discipline=signed_doctor.Discipline
			 			 		      and signed_doctor.status=1

where 
 ( 

prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='".$discipline."' 
								and cpt.type='EVAL' and insurance.status=1  )	
			
			 and prescription.Discipline='".$discipline."'  
			 and prescription.status=1	
			 ".$having."
			 and prescription.Eval_done=1	  
			  and  signed_doctor.Patient_ID is   null 
	
and prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' ) 	) 
			  AND prescription.mail_sent_not_signed=0 
			 and patients.active=1 


 ) 
 OR
(
  prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' ) 	)  
			  and  
 signed_doctor.Patient_ID is   null 	
		and prescription.Discipline='".$discipline."'
		and prescription.status=1 and prescription.Eval_done=1
		AND prescription.mail_sent_not_signed=0
		and patients.active=1
		".$having."
		) 
		
	  group by prescription.Patient_ID
									





	  ";



 return $querynotsigned;
    }




function queryWAITINGDOCTROSIGNED($cpt,$discipline){


	if($discipline == 'PT'){
		$cpt1 = '97001';
		$cpt2 = '97002';	
	}else{
		if($discipline == 'OT'){
			$cpt1 = '97003';
			$cpt2 = '97004';	
		}else{
			$cpt1 = '92523';
			$cpt2 = '92523';	
	    }
	}


	/////////////

	$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}


	
	$queryWAITINGDOCTROSIGNED = " 
select  
 trim(patients.Pat_id) as Pat_id, CONCAT(trim(patients.Last_name),',',trim(patients.First_name)) as Patient_name, 
 prescription.Table_name as Company, 
# insurance.`Auth_#` as Authorization,
prescription.End_date as Auth_thru,
prescription.Eval_schedule,
prescription.Eval_done,
patients.Pri_Ins as Insurance_name , patients.Ref_Physician as Physician
,not_signed_time as Date_sent  , DATEDIFF(date(now()),date(not_signed_time)) as Days_Waiting 
, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE


 
 from
prescription

#join insurance on patients.Pat_id=insurance.Pat_id
 join patients on prescription.Patient_ID=patients.Pat_id      
 		and patients.active=1
left  join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
										and prescription.Discipline=signed_doctor.Discipline
			 			 		     and signed_doctor.status=1

where 
 ( prescription.Patient_ID in (select insurance.Pat_id from insurance 
								join cpt on insurance.Discipline=cpt.Discipline
                                  		 and insurance.CPT=cpt.cpt
								where cpt.Discipline='".$discipline."' 
								and cpt.type='EVAL' and insurance.status=1  )	
			 and prescription.Discipline='".$discipline."'  
			 and prescription.status=1	

			 ".$having."
			 and prescription.Eval_done=1	  
			 and  signed_doctor.Patient_ID is   null 
	 and prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and  ( patients.Pri_Ins='UNITED HEALTHCARE' 
	 or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
			  patients.Pri_Ins='WELLCARE (STAYWELL)' ) 	)  
			  AND prescription.mail_sent_not_signed=1
			  and patients.active=1) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where  patients.active=1 and ( patients.Pri_Ins!='UNITED HEALTHCARE' 
	 and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
			  patients.Pri_Ins!='WELLCARE (STAYWELL)' ) 	)  
			  and 
 signed_doctor.Patient_ID is   null 	
		and prescription.Discipline='".$discipline."'
		and prescription.status=1 and prescription.Eval_done=1
		AND prescription.mail_sent_not_signed=1
		and patients.active=1 
		".$having."


		) 

	
		
	  group by prescription.Patient_ID
									





	  ";



 return $queryWAITINGDOCTROSIGNED;
    }




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function querysignedaskauthotx($cpt,$discipline){


    		$querysignedaskauthotx="  select  t.Patient_name as Patient_name, t.Discipline,
 t.Insurance_name as Insurance_name ,t.New_insurance as New_insurance, t.Company as Company
 			 
from (	select  signed_doctor.Patient_name as Patient_name, signed_doctor.Table_name as Company, 
 patients.Pri_Ins as Insurance_name, patients.Thi_Ins as New_insurance,
 signed_doctor.Discipline
 

	from signed_doctor 
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
										and signed_doctor.Discipline=authorizations.Discipline
										
		where  authorizations.Pat_ID  in (select authorizations.Pat_id from authorizations
													 left join insurance on authorizations.Pat_id=insurance.Pat_id
														and authorizations.`Auth_#`=insurance.`Auth_#`
														where authorizations.status=1 and insurance.Pat_id is null) is null

	and signed_doctor.Discipline='".$discipline."' and signed_doctor.status=1 
	and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
	
	
	) as t group by t.Patient_name order by t.Patient_name ASC
";


    	 return $querysignedaskauthotx;
    }


 function queryreadyforthreatment($cpt,$discipline){



 	/////////////

	$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}



    		$queryreadyforthreatment="  
	 select  t.Pat_id as Pat_id ,t.Patient_name as Patient_name, t.Discipline,
 t.Insurance_name as Insurance_name , t.Company as Company, t.AGE
 			 
from (	select distinct authorizations.Pat_id ,concat(trim(patients.Last_name),', ',trim(patients.First_name)) as Patient_name, authorizations.Company as Company, 
 patients.Pri_Ins as Insurance_name, 
 authorizations.Discipline
 , TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE
 

	from authorizations 
  join patients on authorizations.Pat_ID=patients.Pat_id
  and patients.active=1
  left join insurance on authorizations.Pat_ID=insurance.Pat_id
										and authorizations.`Auth_#`=insurance.`Auth_#`
										and authorizations.Discipline=insurance.Discipline
										#and insurance.status=1

 left join cpt on cpt.Discipline=insurance.Discipline
 				and	cpt.cpt=insurance.CPT
 				and 	cpt.`type`='TX'
										
where (insurance.Pat_id is null and authorizations.Discipline='".$discipline."'  and authorizations.status='1' ".$having.")
										OR
		(insurance.Pat_id  is not null and insurance.status=1 and insurance.Discipline='".$discipline."' 
					and insurance.Visits_Auth=insurance.Visits_remen #and insurance.CPT='".$cpt."'
					and insurance.Auth_thru>=date(now())
	#and insurance.Pat_id in (select careplans.Patient_ID from careplans where careplans.Discipline='".$discipline."' and careplans.`status`=1 		and careplans.POC_due>date(now()))
					".$having."
					)
		


		union distinct

			
select   signed_doctor.Patient_ID as Pat_id ,concat(trim(patients.Last_name),', ',trim(patients.First_name)) as Patient_name,
  signed_doctor.Table_name as Company,
	patients.Pri_Ins as Insurance_name , signed_doctor.Discipline as Discipline
	, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE



	from signed_doctor 
	left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
	and signed_doctor.Discipline=careplans.Discipline
	and signed_doctor.status=careplans.status
	               
 join patients on signed_doctor.Patient_ID=patients.Pat_id
 and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
					#					and signed_doctor.Discipline=authorizations.Discipline
										
		where   (patients.Pri_Ins like 'MOLINA%' OR patients.Pri_Ins like 'MEDICARE%' or  patients.Pri_Ins like 'SELF PAY%')


	and signed_doctor.Discipline='".$discipline."' and signed_doctor.status=1 

	".$having."

   
  group by signed_doctor.Patient_ID		
	
	
	) as t			group by t.Patient_name		order by t.Patient_name ASC				
	
";


    	 return $queryreadyforthreatment;
    }


function querypatientsonhold($cpt,$discipline){


	if($discipline == 'PT'){
		$cpt1 = '97001';
		$cpt2 = '97002';	
	}else{
		if($discipline == 'OT'){
			$cpt1 = '97003';
			$cpt2 = '97004';	
		}else{
			$cpt1 = '92523';
			$cpt2 = '92523';	
	    }
	}

/////////////

	$having = "";
if($_GET['typeAge'] == 'adults'){
     $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 21";
}else{
    if($_GET['typeAge'] == 'pedriatics'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21";
    }
    if($_GET['typeAge'] == 'all'){
        $having = "and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  >= 0";
    }
}




	$querypatientsonhold=" 
select * from (


select distinct insurance.Pat_id, insurance.Patient_name as Patient_name, insurance.Company as Company, 
 patients.Pri_Ins as Insurance_name, patients.Thi_Ins as New_insurance, insurance.Visits_remen,
 insurance.Discipline, insurance.`Auth_#` as Authorization, insurance.Auth_thru, insurance.Visits_remen as Visit_remen , careplans.POC_due
 , 'TX DATE OR VISITS EXPIRED' as STATUS
 , TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE

	from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
  join careplans on insurance.Pat_id=careplans.Patient_ID
  AND insurance.Discipline=careplans.Discipline
										
where (   patients.Pri_Ins!='MOLINA HEALTHCARE OF FLORIDA'  and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins!='SELF PAY'
		and insurance.Discipline='".$discipline."' and insurance.CPT='".$cpt."'  and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru 
	or insurance.Visits_remen<=1
	
	)	
".$having."

	) group by insurance.Pat_id	

 

UNION DISTINCT

select distinct insurance.Pat_id, insurance.Patient_name as Patient_name, insurance.Company as Company, 
 patients.Pri_Ins as Insurance_name, patients.Thi_Ins as New_insurance, insurance.Visits_remen,
 insurance.Discipline, insurance.`Auth_#` as Authorization, insurance.Auth_thru, insurance.Visits_remen as Visit_remen , careplans.POC_due
 , 'EVAL EXPIRED' as STATUS
 , TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE

	from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
  join careplans on insurance.Pat_id=careplans.Patient_ID
  AND insurance.Discipline=careplans.Discipline
										
where (   insurance.Discipline='".$discipline."' and (insurance.CPT='".$cpt1."' or insurance.CPT='".$cpt2."')  and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru )	 
and insurance.Visits_remen=1
".$having."

) 

group by insurance.Pat_id	


UNION DISTINCT

select distinct insurance.Pat_id, insurance.Patient_name as Patient_name, insurance.Company as Company, 
 patients.Pri_Ins as Insurance_name, patients.Thi_Ins as New_insurance, insurance.Visits_remen,
 insurance.Discipline, insurance.`Auth_#` as Authorization, insurance.Auth_thru, insurance.Visits_remen as Visit_remen , careplans.POC_due
 , 'TX EXPIRED' as STATUS
 , TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE

	from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
 join careplans on insurance.Pat_id=careplans.Patient_ID
 AND insurance.Discipline=careplans.Discipline
 
										
where

(
patients.Pat_id in (select  authorizations.Pat_id  from authorizations
								join patients on authorizations.Pat_id=patients.Pat_id
								 left join insurance on insurance.Pat_id=authorizations.Pat_id
								 	                 and insurance.`Auth_#`=authorizations.`Auth_#`
								 	                 and insurance.Discipline=authorizations.Discipline
								 	                 where insurance.Pat_id is null and authorizations.status=1
								 	                 and authorizations.Discipline='".$discipline."' and authorizations.CPT='".$cpt."'
								 	                 and  date(now())>=insurance.Auth_thru 
								 	                 and date(now())<=authorizations.Auth_start  
	) 
".$having."

	)  group by insurance.Pat_id


UNION DISTINCT

select distinct insurance.Pat_id, insurance.Patient_name as Patient_name, insurance.Company as Company, 
 patients.Pri_Ins as Insurance_name, patients.Thi_Ins as New_insurance, insurance.Visits_remen,
careplans.Discipline, insurance.`Auth_#` as Authorization, insurance.Auth_thru, insurance.Visits_remen as Visit_remen , careplans.POC_due
 , 'POC EXPIRED' as STATUS
 , TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE

	from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
  join careplans on insurance.Pat_id=careplans.Patient_ID
  				AND insurance.Discipline=careplans.Discipline
							
where


	patients.Pat_id in (select careplans.Patient_ID from careplans where date(now())>=careplans.POC_due
									 and careplans.status=1 and careplans.Discipline='".$discipline."') 
									 and careplans.Discipline='".$discipline."'  
									".$having."

									 group by careplans.Patient_ID

) as t 
order by t.Patient_name ASC
	";


    	 return $querypatientsonhold;
    }




function queryAdultsprogressnotes($cpt,$discipline){


$queryAdultsprogressnotes="
select  count(distinct campo_3) as number_treatments ,patients.Pat_id,
concat(trim(patients.Last_name),', ',trim(patients.First_name)) as Patient_name,
TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) as AGE
   			  from patients
   			  join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
					and patients.active=1
				join cpt on tbl_treatments.campo_11=cpt.cpt
				and tbl_treatments.campo_10=cpt.Discipline
 where 
 
 TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) >= 18
 
 and type='TX'
 
 and tbl_treatments.campo_10='".$discipline."'
 
 and active=1
 
 and tbl_treatments.adults_progress_notes=0
 
 group by Pat_id
 
 having number_treatments >=10 and number_treatments <50

";


return $queryAdultsprogressnotes;
    }



function queryPedriaticsprogressnotes($cpt,$discipline){


$queryPedriaticsprogressnotes="
SELECT count(distinct campo_3) as number_treatments ,patients.Pat_id,
concat(trim(patients.Last_name),', ',trim(patients.First_name)) as Patient_name,
TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) as AGE
   			  from patients
   			  join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
					and patients.active=1
				join cpt on tbl_treatments.campo_11=cpt.cpt
				and tbl_treatments.campo_10=cpt.Discipline
 where 
 
 TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) < 18
 
 and type='TX'
 
 and tbl_treatments.campo_10='".$discipline."'
 
 and active=1
 
 and tbl_treatments.pedriatics_progress_notes=0
 
 group by Pat_id
 
 having number_treatments >=20 

";


return $queryPedriaticsprogressnotes;
    }




function query_patients_not_been_seen($cpt,$discipline){


$query_patients_not_been_seen="
SELECT
t.id_treatments,
t.campo_5 as Pat_id
, t.campo_6 as Patient_name 
, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS AGE
,  max(STR_TO_DATE(t.campo_1,'%m/%d/%Y')) as DOS
, DATEDIFF(date(now()),max(STR_TO_DATE(t.campo_1,'%m/%d/%Y'))) as Days_WO_therapy
, t.campo_9 as Therapist
#, t.campo_10 as Discipline 
, cpt.`type` as Type_therapy


from tbl_treatments t
 
 #join ( select max(STR_TO_DATE(a.campo_1,'%m/%d/%Y')) as prueba from tbl_treatments a where campo_10='ST'
 #			group by a.campo_5 ) z  on z.prueba=STR_TO_DATE(t.campo_1,'%m/%d/%Y')
 #											and t.campo_10='ST'
 JOIN  patients on patients.Pat_id=t.campo_5
				and patients.active=1
join cpt on t.campo_11=cpt.cpt
      and t.campo_10=cpt.Discipline
left join tbl_audit_discharge_patient v on  v.patient_id=t.campo_5 and v.discipline=t.campo_10 

WHERE
  v.patient_id is null
and t.campo_10='".$discipline."'
group by t.campo_5

having  Days_WO_therapy>15
order by Days_WO_therapy desc

";


return $query_patients_not_been_seen;
    }





?>
