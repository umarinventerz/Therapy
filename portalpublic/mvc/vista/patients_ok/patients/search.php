<?php 
session_start(); 
require_once("../../../conex.php");
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
 
echo '
  <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css"/>        
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<script>

setTimeout(function() {
        swal({
            title: "NAME MISSING!",
            text: " CHOOSE A PHYSICIAN!",
            closeOnConfirm: true,
            type: "warning"
        }, function() {
            window.location.href = "search.php";
        });
    }, 400);
</script>';
header("Refresh:2");
exit; 




}else{ 
list($pat_id,$company) = explode('-',$_POST['name']); 


//echo '<h1>'.$pat_id.'</h1>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
$sql00 = "




select  Status

         from
(

################################# POC EXPIRED ASK PRESCRIPTION ###########################################         
           select  (case 
                  when careplans.Discipline='OT' then 'ASKING PRESCRIPTION FOR OT'
                  when careplans.Discipline='PT' then 'ASKING PRESCRIPTION FOR PT'
                when careplans.Discipline='ST' then 'ASKING PRESCRIPTION FOR ST'
                else 1
                end) as Status
          from careplans 
   join patients on careplans.Patient_ID=patients.Pat_id
   and patients.active=1



 where 

       careplans.status=1 
       and patients.active=1
      AND careplans.mail_sent=0
       #and careplans.Discipline='OT' 
        #and careplans.POC_due>=date(now()) 
      AND CAREPLANS.Patient_ID='".$pat_id."'
       and  datediff (careplans.POC_due,date(now()))<=45 
       and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
      and
      (
careplans.Discipline not in (select prescription.Discipline from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date) 
#and prescription.Discipline='OT' 
#and 
prescription.Patient_ID='".$pat_id."'
 and prescription.status=1
#and prescription.Eval_done=0
  group by prescription.Discipline)
) 

group by careplans.Discipline 
              
                
UNION ################################# WAITING PRESCRIPTION ###########################################
          
              
      select  (case 
                  when careplans.Discipline='OT' then 'WAITING PRESCRIPTION FOR OT'
                  when careplans.Discipline='PT' then 'WAITING PRESCRIPTION FOR PT'
                when careplans.Discipline='ST' then 'WAITING PRESCRIPTION FOR ST'
                else 1
                end) as Status
          from careplans 
   join patients on careplans.Patient_ID=patients.Pat_id
   and patients.active=1



 where 

   
       careplans.status=1 
       and patients.active=1
      AND careplans.mail_sent=1
       #and careplans.Discipline='OT' 
        #and careplans.POC_due>=date(now()) 
      AND CAREPLANS.Patient_ID='".$pat_id."'
       and  datediff (careplans.POC_due,date(now()))<=45 
       and TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE())  < 21
      and
      (
careplans.Discipline not in (select prescription.Discipline from prescription 
where #(date(now()) between prescription.Issue_date and prescription.End_date) 
#and prescription.Discipline='OT' 
#and 
prescription.Patient_ID='".$pat_id."'
 and prescription.status=1 
#and prescription.Eval_done=0
 group by prescription.Discipline)
) 

group by careplans.Discipline 
              
        

UNION ################################# ASK AUTH FOR EVAL ###########################################


select  
 (case 
                  when prescription.Discipline='OT' then 'ASK EVAL FOR OT'
                  when prescription.Discipline='PT' then 'ASK EVAL FOR PT'
                when prescription.Discipline='ST' then 'ASK EVAL FOR ST'
                else 1
                end) as Status
 
 from
prescription

#left  join insurance on prescription.Patient_ID=insurance.Pat_id
  join patients on prescription.Patient_ID=patients.Pat_id
  and patients.active=1
    

where 

 (
prescription.Discipline not in (select insurance.Discipline from insurance
                                  join cpt on insurance.Discipline=cpt.Discipline
                                    and insurance.CPT=cpt.cpt
                                  where #insurance.Discipline='".$discipline."' and 
                                  cpt.type='EVAL' and insurance.status=1  
                                AND insurance.Pat_id='".$pat_id."') 

#and prescription.Discipline='".$discipline."'
 and prescription.status=1
 and patients.active=1



and prescription.Patient_ID in (select patients.Pat_id from patients where  patients.Pat_id='".$pat_id."' and patients.active=1 and ( patients.Pri_Ins='UNITED HEALTHCARE' or
 patients.Pri_Ins='PRESTIGE' or
        patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or 
        patients.Pri_Ins='WELLCARE (STAYWELL)' ) 
         ) 
      
        ) 
     and prescription.mail_sent_eval=0 
     AND prescription.Patient_ID='".$pat_id."'

   group by prescription.Discipline







UNION ################################# WAITING AUTH FOR EVAL ###########################################



select  
 (case 
                  when prescription.Discipline='OT' then 'WAITING EVAL FOR OT'
                  when prescription.Discipline='PT' then 'WAITING EVAL FOR PT'
                when prescription.Discipline='ST' then 'WAITING EVAL FOR ST'
                else 1
                end) as Status
 
 from
prescription

#left  join insurance on prescription.Patient_ID=insurance.Pat_id
  join patients on prescription.Patient_ID=patients.Pat_id
  and patients.active=1
    

where (
prescription.Discipline not in (select insurance.Discipline from insurance
                                  join cpt on insurance.Discipline=cpt.Discipline
                                    and insurance.CPT=cpt.cpt
                                  where #discipline='".$discipline."' and 
                                  cpt.type='EVAL' and insurance.status=1  
                                AND insurance.Pat_id='".$pat_id."')  

#and prescription.Discipline='".$discipline."'
 and prescription.status=1
and patients.active=1 



and prescription.Patient_ID in (select patients.Pat_id from patients where
patients.Pat_id='".$pat_id."' and patients.active=1 and
       ( patients.Pri_Ins='UNITED HEALTHCARE' or
        patients.Pri_Ins='PRESTIGE' or
        patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or 
        patients.Pri_Ins='WELLCARE (STAYWELL)' )  ) 
      
        ) 
     and prescription.mail_sent_eval=1 
     AND prescription.Patient_ID='".$pat_id."'

   group by prescription.Discipline


UNION ################################# SIGUIENTE QUERY ##### NEED EVALUATION ###########################


select  
 (case 
                  when prescription.Discipline='OT' then 'NEED EVAL FOR OT'
                  when prescription.Discipline='PT' then 'NEED EVAL FOR PT'
                when prescription.Discipline='ST' then 'NEED EVAL FOR ST'
                else 1
                end) as Status
 
 
 from
prescription

#join insurance on patients.Pat_id=insurance.Pat_id
  join patients on prescription.Patient_ID=patients.Pat_id 
  and patients.active=1
# join careplans on prescription.Patient_ID=careplans.Patient_ID and prescription.Discipline=careplans.Discipline  and careplans.status=1   

where 
 ( prescription.Discipline in (select insurance.Discipline from insurance 
                join cpt on insurance.Discipline=cpt.Discipline
                                       and insurance.CPT=cpt.cpt
                where 
                cpt.type='EVAL' and insurance.status=1  and insurance.Pat_id='".$pat_id."') 
       #and prescription.Discipline='".$discipline."'  
       and prescription.status=1      
      and patients.active=1
      and prescription.Eval_done=0  
   and prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins='UNITED HEALTHCARE' 
   or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
     patients.Pri_Ins='PRESTIGE' or
        patients.Pri_Ins='WELLCARE (STAYWELL)' )  ) 
 AND prescription.Patient_ID='".$pat_id."'     
         ) 
 OR
 (
  prescription.Patient_ID in (select patients.Pat_id from patients where patients.active=1 and ( patients.Pri_Ins!='UNITED HEALTHCARE' 
   and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
    patients.Pri_Ins!='PRESTIGE' AND
        patients.Pri_Ins!='WELLCARE (STAYWELL)' )   )  
        and  
 prescription.status=1 #and prescription.Discipline='".$discipline."' 
                        and prescription.Eval_done=0  
                    AND prescription.Patient_ID='".$pat_id."'   
                    and patients.active=1 
    ) 
    
    group by prescription.Discipline
                  



UNION ############################### ASK DOCTOR SIGNATURE #############################


select  
 (case 
                  when prescription.Discipline='OT' then 'ASK DOCTOR SIGNATURE FOR OT'
                  when prescription.Discipline='PT' then 'ASK DOCTOR SIGNATURE FOR PT'
                when prescription.Discipline='ST' then 'ASK DOCTOR SIGNATURE FOR ST'
                else 1
                end) as Status

 
 from
prescription

#join insurance on patients.Pat_id=insurance.Pat_id
  join patients on prescription.Patient_ID=patients.Pat_id      
  and patients.active=1
 
  left join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
                    and prescription.Discipline=signed_doctor.Discipline
                     and signed_doctor.status=1

where 
 ( prescription.Discipline in (select insurance.Discipline from insurance 
                  join cpt on insurance.Discipline=cpt.Discipline
                                       and insurance.CPT=cpt.cpt
                where
               insurance.Pat_id='".$pat_id."'
               and insurance.status=1 #and insurance.Visits_remen=0
     and cpt.type='EVAL' group by insurance.Discipline )  
       #and prescription.Discipline='".$discipline."'  
        and prescription.status=1  
        AND prescription.Patient_ID='".$pat_id."'
        and prescription.Eval_done=1
    AND prescription.mail_sent_not_signed=0    
       and  signed_doctor.Patient_ID is   null 
   and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' 
   or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
        patients.Pri_Ins='WELLCARE (STAYWELL)' ) and patients.Pat_id='".$pat_id."' and patients.active=1 ) AND prescription.mail_sent_not_signed=0
        AND prescription.Patient_ID='".$pat_id."'
           ) 
 OR
 (
 prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins!='UNITED HEALTHCARE' 
  and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
        patients.Pri_Ins!='WELLCARE (STAYWELL)' ) and patients.Pat_id='".$pat_id."'  and patients.active=1 )  
        and  
 signed_doctor.Patient_ID is   null   
  # and prescription.Discipline='".$discipline."'
    and prescription.status=1 
    and prescription.Eval_done=1
    AND prescription.mail_sent_not_signed=0
      AND prescription.Patient_ID='".$pat_id."'
    ) 
    
    group by prescription.Discipline



UNION ############################### WAITING FOR DOCTOR SIGNATURE #############################

select  
 (case 
                  when prescription.Discipline='OT' then 'WAITING DOCTOR SIGNATURE FOR OT'
                  when prescription.Discipline='PT' then 'WAITING DOCTOR SIGNATURE FOR PT'
                when prescription.Discipline='ST' then 'WAITING DOCTOR SIGNATURE FOR ST'
                else 1
                end) as Status

 
 from
prescription

#join insurance on patients.Pat_id=insurance.Pat_id
 join patients on prescription.Patient_ID=patients.Pat_id     
 and patients.active=1 
 
 left  join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
                    and prescription.Discipline=signed_doctor.Discipline
                     and  signed_doctor.status=1

where 
 ( prescription.Discipline in (select insurance.Discipline from insurance 
                  join cpt on insurance.Discipline=cpt.Discipline
                                       and insurance.CPT=cpt.cpt
                where
               insurance.Pat_id='".$pat_id."'
               and insurance.status=1 #and insurance.Visits_remen=0
     and cpt.type='EVAL' group by insurance.Discipline ) 
       #and prescription.Discipline='".$discipline."'  
     and prescription.Eval_done=1
       and prescription.status=1      
        and  signed_doctor.Patient_ID is   null 
   and prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins='UNITED HEALTHCARE' 
   or  patients.Pri_Ins='SIMPLY HEALTHCARE PLAN' or
        patients.Pri_Ins='WELLCARE (STAYWELL)' ) and patients.Pat_id='".$pat_id."' and patients.active=1 ) AND prescription.mail_sent_not_signed=1
        AND prescription.Patient_ID='".$pat_id."'
           ) 
 OR
 (
 prescription.Patient_ID in (select patients.Pat_id from patients where ( patients.Pri_Ins!='UNITED HEALTHCARE' 
   and  patients.Pri_Ins!='SIMPLY HEALTHCARE PLAN' and
        patients.Pri_Ins!='WELLCARE (STAYWELL)' ) and patients.Pat_id='".$pat_id."' and patients.active=1  )  
        and 
     signed_doctor.Patient_ID is   null   
   #and prescription.Discipline='".$discipline."'
    and prescription.status=1 
    and prescription.Eval_done=1
    AND prescription.mail_sent_not_signed=1
      AND prescription.Patient_ID='".$pat_id."'
    ) 
    
    group by prescription.Discipline



UNION #####################  ASK FOR AUTHORIZATION FOR TX  ##################################

SELECT 
* FROM 
(
select    (case 
                  when signed_doctor.Discipline='OT' then 'ASK TX FOR OT'
                  when signed_doctor.Discipline='PT' then 'ASK TX FOR PT'
                when signed_doctor.Discipline='ST' then 'ASK TX FOR ST'
                else 1
                end) as Status


  from signed_doctor 
  left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
  					and signed_doctor.Discipline=careplans.Discipline
                 
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
                    #and signed_doctor.Discipline=authorizations.Discipline
                    
    where  signed_doctor.Discipline not in (select authorizations.Discipline from authorizations
                where authorizations.Pat_id='".$pat_id."' and authorizations.status=1) 

  and signed_doctor.Patient_ID='".$pat_id."' and signed_doctor.status=1 
  
  AND
  (
          (
           careplans.mail_sent_tx=0
          AND careplans.Patient_ID='".$pat_id."'
           and careplans.status=1
          )
      OR
          (
          careplans.Patient_ID is null
          and signed_doctor.Discipline in (select prescription.Discipline from prescription where tx_request_sent=0 and Patient_ID='".$pat_id."' and status=1)
           )    
   
   )
    and patients.active=1
  
  and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
  and  patients.Pri_Ins not like 'SELF PAY%'
  
  group by signed_doctor.Discipline
  

union

SELECT
  (case 
                  when insurance.Discipline='OT' then 'ASK TX FOR OT'
                  when insurance.Discipline='PT' then 'ASK TX FOR PT'
                when insurance.Discipline='ST' then 'ASK TX FOR ST'
                else 1
                end) as Status
     
       
         
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

  (#(insurance.CPT='92507' or insurance.CPT='97110'   or insurance.CPT='97530') 
    #and  
    patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
    and  patients.Pri_Ins not like 'SELF PAY%'
    and  patients.Pri_Ins NOT like 'UNITED%'
    and  (patients.Pri_Ins NOT like 'SUNSHINE%' and patients.Pri_Ins NOT like 'ATA%' 
   and patients.Pri_Ins NOT like 'AMERIGROUP%' and patients.Pri_Ins NOT like 'HUMANA%')
     and patients.active=1
  and insurance.status=1 
  and (datediff (insurance.Auth_thru,date(now()))<3  
    or  insurance.Visits_remen<=4 )
  #and insurance.Auth_thru>date(now())
  )
  OR
  (#(insurance.CPT='92507' or insurance.CPT='97110'   or insurance.CPT='97530') 
    #and  
    (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
    or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
    and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
   and patients.active=1
  and insurance.status=1 and
   (datediff (insurance.Auth_thru,date(now()))<1  
    or  insurance.Visits_remen<=2 )
  #and insurance.Auth_thru>date(now())
  )
  
  OR
    (#(insurance.CPT='92507' or insurance.CPT='97110'   or insurance.CPT='97530') 
   # and  
    (patients.Pri_Ins like 'UNITED%' 
    ) 
     and patients.active=1
    and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
  and insurance.status=1 and
   (datediff (insurance.Auth_thru,date(now()))<7  
    or  insurance.Visits_remen<=7 )
  #and insurance.Auth_thru>date(now())
  )
)




AND
                                (
  insurance.Discipline in (select careplans.Discipline from careplans 
  where careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
  and careplans.Patient_ID='".$pat_id."' and careplans.status=1 )
  and careplans.Patient_ID='".$pat_id."' 
               and careplans.status=1

              
               )
             and 
               
               (
   # insurance.Discipline not in (select prescription.Discipline from prescription 
   # where prescription.status=1 and prescription.Patient_ID='".$pat_id."')
   # and
   insurance.Discipline not in (select signed_doctor.Discipline from signed_doctor 
   where signed_doctor.status=1 and signed_doctor.Patient_ID='".$pat_id."')
             )  
  
   and patients.active=1
            AND careplans.mail_sent_tx=0
            AND careplans.Patient_ID='".$pat_id."'
            
          
            
          group by insurance.Discipline

union Distinct

SELECT
  (case 
                  when careplans.Discipline='OT' then 'ASK TX FOR OT'
                  when careplans.Discipline='PT' then 'ASK TX FOR PT'
                when careplans.Discipline='ST' then 'ASK TX FOR ST'
                else 1
                end) as Status
     
       
         
      from patients 
      #join physician on patients.Phy_NPI=physician.NPI
      join insurance on patients.Pat_id=insurance.Pat_id
  join careplans on patients.Pat_id=careplans.Patient_ID
            
                       
 where 
  patients.active=1
 
      AND   
( 
     (patients.Thi_Ins!='' and patients.Pri_Ins!=patients.Thi_Ins AND patients.Pat_id='".$pat_id."'
        and  ( patients.Thi_Ins not like 'MOLINA%' and  patients.Thi_Ins not like 'MEDICARE%' and patients.Thi_Ins not like 'SELF%')
     )
     OR
    (
   patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 AND patients.Pat_id='".$pat_id."'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
          AND
   (
    (careplans.Discipline not in (select prescription.Discipline from  prescription 
                      where prescription.status=1 AND patients.Pat_id='".$pat_id."' ) )
   OR
   (careplans.Discipline in (select prescription.Discipline from   prescription 
                left  join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
                              and prescription.Discipline=signed_doctor.Discipline
        where 
            (prescription.status=1 AND prescription.Patient_ID='".$pat_id."'  and signed_doctor.Patient_ID is not null
              and signed_doctor.status=1)
                 )
                    
    
         )
   )
     
)
)
    and careplans.status=1
    and careplans.mail_sent_tx=0    
    and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
    #AND careplans.Discipline='".$discipline."'
    AND patients.Pat_id='".$pat_id."'
  
    group by careplans.Discipline
          
)
AS T



UNION ##################### waiting auth  FOR TX  ##################################33####### #33## ###3###3333

SELECT 
* FROM 
(
select    (case 
                  when signed_doctor.Discipline='OT' then 'WAITING TX FOR OT'
                  when signed_doctor.Discipline='PT' then 'WAITING TX FOR PT'
                when signed_doctor.Discipline='ST' then 'WAITING TX FOR ST'
                else 1
                end) as Status


  from signed_doctor 
 left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
      and   signed_doctor.Discipline=careplans.Discipline
                 
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
                    #and signed_doctor.Discipline=authorizations.Discipline
                    
    where  signed_doctor.Discipline not in (select authorizations.Discipline from authorizations
                where authorizations.Pat_id='".$pat_id."' and authorizations.status=1) 

  and signed_doctor.Patient_ID='".$pat_id."' and signed_doctor.status=1 
  
 AND
  (
          (
           careplans.mail_sent_tx=1
          AND careplans.Patient_ID='".$pat_id."'
           and careplans.status=1
          )
      OR
          (
          careplans.Patient_ID is null
          and signed_doctor.Discipline in (select prescription.Discipline from prescription where tx_request_sent=1 and Patient_ID='".$pat_id."' and status=1)
           )    
   
   )
  
   and patients.active=1
  and patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
  and  patients.Pri_Ins not like 'SELF PAY%'
  
  group by signed_doctor.Discipline
  

union distinct

SELECT
  (case 
                  when insurance.Discipline='OT' then 'WAITING TX FOR OT'
                  when insurance.Discipline='PT' then 'WAITING TX FOR PT'
                when insurance.Discipline='ST' then 'WAITING TX FOR ST'
                else 1
                end) as Status
     
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

  (#(insurance.CPT='92507' or insurance.CPT='97110'   or insurance.CPT='97530') 
   # and  
    patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
    and  patients.Pri_Ins not like 'SELF PAY%'
    and  patients.Pri_Ins NOT like 'UNITED%'
    and  (patients.Pri_Ins NOT like 'SUNSHINE%' AND patients.Pri_Ins NOT like 'ATA%' 
    and patients.Pri_Ins NOT like 'AMERIGROUP%' and patients.Pri_Ins NOT like 'HUMANA%')
     and patients.active=1
  and insurance.status=1 
  and (datediff (insurance.Auth_thru,date(now()))<3  
    or  insurance.Visits_remen<=4 )
  #and insurance.Auth_thru>date(now())
  )
  OR
  (#(insurance.CPT='92507' or insurance.CPT='97110'   or insurance.CPT='97530') 
    #and  
    (patients.Pri_Ins like 'SUNSHINE%' or patients.Pri_Ins like 'ATA%' 
    or patients.Pri_Ins like 'AMERIGROUP%' OR patients.Pri_Ins like 'HUMANA%')
    and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
   and patients.active=1
  and insurance.status=1 and
   (datediff (insurance.Auth_thru,date(now()))<1  
    or  insurance.Visits_remen<=2 )
  #and insurance.Auth_thru>date(now())
  )
  
  OR
    (#(insurance.CPT='92507' or insurance.CPT='97110'   or insurance.CPT='97530') 
  #  and 
     (patients.Pri_Ins like 'UNITED%' 
    ) 
     and patients.active=1
    and  patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%'
  and insurance.status=1 and
   (datediff (insurance.Auth_thru,date(now()))<7  
    or  insurance.Visits_remen<=7 )
  #and insurance.Auth_thru>date(now())
  )
)


AND


                                (
  insurance.Discipline in (select careplans.Discipline from careplans 
  where careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY) 
  and careplans.Patient_ID='".$pat_id."' and careplans.status=1 )
  and careplans.Patient_ID='".$pat_id."' 
               and careplans.status=1

              
               )
             and 
               
               (
  # insurance.Discipline not in (select prescription.Discipline from prescription 
  # where prescription.status=1 and prescription.Patient_ID='".$pat_id."')
  # and
   insurance.Discipline not in (select signed_doctor.Discipline from signed_doctor 
   where signed_doctor.status=1 and signed_doctor.Patient_ID='".$pat_id."')
             )  
  
   and patients.active=1
            AND careplans.mail_sent_tx=1
            AND careplans.Patient_ID='".$pat_id."'
            
          
            
          group by insurance.Discipline

union 

SELECT
  (case 
                  when careplans.Discipline='OT' then 'WAITING TX FOR OT'
                  when careplans.Discipline='PT' then 'WAITING TX FOR PT'
                when careplans.Discipline='ST' then 'WAITING TX FOR ST'
                else 1
                end) as Status
     
       
         
      from patients 
      #join physician on patients.Phy_NPI=physician.NPI
      join insurance on patients.Pat_id=insurance.Pat_id
  join careplans on patients.Pat_id=careplans.Patient_ID
            
                       
 where 
  patients.active=1
  
      AND   
( 
     (patients.Thi_Ins!='' and patients.Pri_Ins!=patients.Thi_Ins AND patients.Pat_id='".$pat_id."'
        and  ( patients.Thi_Ins not like 'MOLINA%' and  patients.Thi_Ins not like 'MEDICARE%' and patients.Thi_Ins not like 'SELF%')
     )
     OR
    (
   patients.Pri_Ins!=insurance.Insurance_name and insurance.`status`=1 AND patients.Pat_id='".$pat_id."'
      and (patients.Pri_Ins not like 'MOLINA%' and  patients.Pri_Ins not like 'MEDICARE%' and patients.Pri_Ins not like 'SELF%')
          AND
   (
    (careplans.Discipline not in (select prescription.Discipline from  prescription 
                      where prescription.status=1 AND patients.Pat_id='".$pat_id."' ) )
   OR
   (careplans.Discipline in (select prescription.Discipline from   prescription 
                left  join signed_doctor on prescription.Patient_ID=signed_doctor.Patient_ID
                              and prescription.Discipline=signed_doctor.Discipline
        where 
            (prescription.status=1 AND prescription.Patient_ID='".$pat_id."'  and signed_doctor.Patient_ID is not null
              and signed_doctor.status=1)
                 )
                    
    
         )
   )
     
)
)
    and careplans.status=1
    and careplans.mail_sent_tx=1
    and careplans.POC_due>date_add(date(now()), INTERVAL 15 DAY)     
    #AND careplans.Discipline='".$discipline."'
    AND patients.Pat_id='".$pat_id."'
   
    group by careplans.Discipline
          
)
AS T



UNION #####################  READY FOR TREATMENT   #########################################################

select * from  (
select (case 
                  when authorizations.Discipline='OT' then 'READY FOR TREATMENT FOR OT'
                  when authorizations.Discipline='PT' then 'READY FOR TREATMENT FOR PT'
                when authorizations.Discipline='ST' then 'READY FOR TREATMENT FOR ST'
                else 1
                end) as Status
     
 

  from authorizations 
  join patients on authorizations.Pat_ID=patients.Pat_id
  and patients.active=1
  left join insurance on authorizations.Pat_ID=insurance.Pat_ID
                    and authorizations.`Auth_#`=insurance.`Auth_#`
                    and authorizations.Discipline=insurance.Discipline
                    #and insurance.status=1

   left join cpt on cpt.Discipline=insurance.Discipline
        and cpt.cpt=insurance.CPT
        and   cpt.`type`='TX'
                    
where (insurance.Pat_id is null and authorizations.Pat_id='".$pat_id."'  and authorizations.status='1')
                    OR
    (insurance.Pat_id  is not null and insurance.status=1 and insurance.Pat_id='".$pat_id."' 
          and insurance.Visits_Auth=insurance.Visits_remen 
          #and (insurance.CPT='97530' or insurance.CPT='97110' or insurance.CPT='92507') 
          and insurance.Auth_thru>=date(now())   
 #and insurance.Discipline in (select careplans.Discipline from careplans where #careplans.Discipline='".$discipline."' careplans.Patient_ID='".$pat_id."'
   #                       and careplans.`status`=1
       #                   and careplans.POC_due>date(now()))
          )
  
  
  GROUP BY authorizations.Discipline


      UNION 

      select (case 
                  when signed_doctor.Discipline='OT' then 'READY FOR TREATMENT FOR OT'
                  when signed_doctor.Discipline='PT' then 'READY FOR TREATMENT FOR PT'
                when signed_doctor.Discipline='ST' then 'READY FOR TREATMENT FOR ST'
                else 1
                end) as Status


  from signed_doctor 
  left join careplans on signed_doctor.Patient_ID=careplans.Patient_ID
  and signed_doctor.Discipline=careplans.Discipline
  and signed_doctor.status=careplans.status
                 
  join patients on signed_doctor.Patient_ID=patients.Pat_id
  and patients.active=1
 # left join authorizations on signed_doctor.Patient_ID=authorizations.Pat_ID
          #         and signed_doctor.Discipline=authorizations.Discipline
                    
    where  ( patients.Pri_Ins like 'MOLINA%' OR patients.Pri_Ins like 'MEDICARE%' OR patients.Pri_Ins like 'SELF%')


  and signed_doctor.Patient_ID='".$pat_id."' and signed_doctor.status=1 

   
  group by signed_doctor.Discipline   

  ) as t


UNION #####################  ON HOLD    #########################################################


select * from (


select  (case 
                  when insurance.Discipline='OT' then 'ON HOLD BECAUSE -OT- TX DATE OR VISITS EXPIRED'
                  when insurance.Discipline='PT' then 'ON HOLD BECAUSE -PT- TX DATE OR VISITS EXPIRED'
                when insurance.Discipline='ST' then 'ON HOLD BECAUSE -ST- TX DATE OR VISITS EXPIRED'
                else 1
                end) as Status



  from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
 left join careplans on insurance.Pat_id=careplans.Patient_ID
                    
where (   patients.Pri_Ins!='MOLINA HEALTHCARE OF FLORIDA'  and  patients.Pri_Ins not like 'MEDICARE%'
    and insurance.Pat_id='".$pat_id."' 
    and (insurance.CPT='92507' or insurance.CPT='97110'   or insurance.CPT='97530') 
     and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru 
  or insurance.Visits_remen<=1
  
  ) ) group by insurance.Discipline

 

UNION

select  (case 
                  when insurance.Discipline='OT' then 'ON HOLD BECAUSE -OT- EVAL EXPIRED'
                  when insurance.Discipline='PT' then 'ON HOLD BECAUSE -PT- EVAL EXPIRED'
                when insurance.Discipline='ST' then 'ON HOLD BECAUSE -ST- EVAL EXPIRED'
                else 1
                end) as Status

  from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
 left join careplans on insurance.Pat_id=careplans.Patient_ID
                    
where (   insurance.Pat_id='".$pat_id."' 
      and  (insurance.CPT='97001' or insurance.CPT='97002' or insurance.CPT='97003' or insurance.CPT='97004'
     or insurance.CPT='92523') 
     and insurance.status=1 
and  
(date(now())>=insurance.Auth_thru )  and insurance.Visits_remen=1) group by insurance.Discipline


UNION

SELECT
 (case 
                  when insurance.Discipline='OT' then 'ON HOLD BECAUSE -OT- TX EXPIRED'
                  when insurance.Discipline='PT' then 'ON HOLD BECAUSE -PT- TX EXPIRED'
                when insurance.Discipline='ST' then 'ON HOLD BECAUSE -ST- TX EXPIRED'
                else 1
                end) as Status

  from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
 left join careplans on insurance.Pat_id=careplans.Patient_ID
                    
where

(
insurance.Discipline in (select  authorizations.Discipline  from authorizations
                join patients on authorizations.Pat_id=patients.Pat_id
                and patients.active=1
                 left join insurance on insurance.Pat_id=authorizations.Pat_id
                                   and insurance.`Auth_#`=authorizations.`Auth_#`
                                   and insurance.Discipline=authorizations.Discipline
                                   where insurance.Pat_id is null and authorizations.status=1
                                   and authorizations.Pat_id='".$pat_id."'
       and (insurance.CPT='97001' or insurance.CPT='97002' or insurance.CPT='97003' or insurance.CPT='97004'
     or insurance.CPT='92523')
                                   and  date(now())>=insurance.Auth_thru 
                                   and date(now())<=authorizations.Auth_start  
  ) )  group by insurance.Discipline


UNION 

select  (case 
                  when insurance.Discipline='OT' then 'ON HOLD BECAUSE -OT- POC EXPIRED'
                  when insurance.Discipline='PT' then 'ON HOLD BECAUSE -PT- POC EXPIRED'
                when insurance.Discipline='ST' then 'ON HOLD BECAUSE -ST- POC EXPIRED'
                else 1
                end) as Status

  from insurance
  join patients on insurance.Pat_id=patients.Pat_id
  and patients.active=1
  join careplans on insurance.Pat_id=careplans.Patient_ID
              
where


  insurance.Discipline in (select careplans.Discipline from careplans where date(now())>=careplans.POC_due
                   and careplans.status=1 and careplans.Patient_ID='".$pat_id."') 
                   and careplans.Patient_ID='".$pat_id."'  group by careplans.Discipline

) as t 



) Q


  ";


*/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    





$sql1="SELECT Last_name,First_name,Pat_id,Sex,DOB,Guardian,E_mail, 
            Phone,barcode,Ref_Physician,Pri_Ins,Table_name as Company,Thi_Ins 
            from patients 
            LEFT JOIN tbl_barcodes on patients.id=tbl_barcodes.id_relation
            where tbl_barcodes.id_type_person = 1 AND Pat_id= '".$pat_id."' "; 
 

$sql2=" Select Insurance_name,Patient_name,Pat_id,`Auth_#`,CPT,Discipline,Auth_Start,Auth_thru,Visits_Auth,Visits_remen,Company
, CASE WHEN status='1'  THEN 'Active'  ELSE 'Inactive' END AS status 
from insurance where Pat_id= '".$pat_id."' order by status ASC  "; 


$sql3=" Select Last_name,First_name,Patient_ID,Discipline,Completed,MD_signed,POC_due,Re_Eval_due,MD_Eval_signed, Company
, CASE WHEN status='1'  THEN 'Active'  ELSE 'Inactive' END AS status 
from careplans where Patient_ID= '".$pat_id."' order by status ASC ";

$sql_notes_general="SELECT NG.id_notes_general,NG.notes_general,NG.date_notes,NG.id_person,DTP.type_persons,US.Last_name,US.First_name,P.Last_name as last_name_patient, P.First_name as first_name_patient FROM tbl_notes_general NG LEFT JOIN tbl_doc_type_persons DTP ON (DTP.id_type_persons = NG.id_type_person)
                    LEFT JOIN patients P ON (P.pat_id=NG.id_person)
                    LEFT JOIN user_system US ON (US.user_id = NG.id_user) WHERE NG.table_reference='patients' AND NG.id_person=".$pat_id." ORDER BY NG.id_notes_general ASC";




$sql4=" Select Patient_ID,Patient_name,Discipline,Diagnostic,Issue_date,End_date,Physician_name,Physician_NPI,Table_name,TIMESTAMP
, CASE WHEN status='1'  THEN 'Active'  ELSE 'Inactive' END AS status 
from prescription where Patient_ID= '".$pat_id."' order by status ASC "; 


$sql5=" Select Patient_ID,Patient_name,Discipline,CPT,Authorization,Physician_name,Physician_NPI,Table_name,TIMESTAMP, Route_file as File

,CASE WHEN Route_file IS NOT NULL  THEN 'FILE_LINK'  ELSE 'NONE' END AS Route_file

, CASE WHEN status='1'  THEN 'Active'  ELSE 'Inactive' END AS status 
from signed_doctor where Patient_ID= '".$pat_id."' order by status ASC"; 



$sql6=" Select *, CASE WHEN status='1'  THEN 'Active'  ELSE 'Inactive' END AS status
, Route_file as File

,CASE WHEN Route_file IS NOT NULL  THEN 'FILE_LINK'  ELSE 'NONE' END AS Route_file
 from authorizations where Pat_id= '".$pat_id."' order by status ASC"; 


$sql7=" select *,trim(Last_name),trim(First_name)
, TIMESTAMPDIFF(YEAR,patients.DOB,CURDATE()) AS Age
, STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y') as Date
, campo_9 as therapist
, campo_10 as Discipline
, cpt.`type`
 
 
 from patients 
join tbl_treatments on patients.Pat_id=tbl_treatments.campo_5
join cpt on tbl_treatments.campo_11=cpt.cpt
      and tbl_treatments.campo_10=cpt.Discipline
where patients.Pat_id='".$pat_id."'
order by date desc
limit 5             

";


  $sql_contacts  = "
 select 
 tbl_contacto_persona.id_persona_contacto,
tbl_contacto_persona.genero,
tbl_contacto_persona.relacion,
tbl_contacto_persona.descripcion,
tbl_contacto_persona.direccion,
tbl_contacto_persona.fecha_nacimiento,
tbl_contacto_persona.persona_contacto,
tbl_contacto_persona.cargo_persona_contacto,
tbl_contacto_persona.email,
tbl_contacto_persona.telefono,
tbl_contacto_persona.fax,

(case 
    when tbl_contactos.tabla_ref='physician' then physician.Name
    when tbl_contactos.tabla_ref='patients' then concat(patients.Last_name,patients.First_name)
    when tbl_contactos.tabla_ref='seguros' then seguros.insurance
                else tbl_contacto_persona.id_contactos
            end) as Associate_To


 from tbl_contacto_persona 
left join tbl_contactos using(id_contactos)

left join physician on tbl_contactos.id_tabla_ref=physician.Phy_id
left join patients on tbl_contactos.id_tabla_ref=patients.Pat_id
left join seguros on tbl_contactos.id_tabla_ref=seguros.ID


 WHERE
      tbl_contactos.id_tabla_ref='".$pat_id."'

   
 "; 

         //  $resultado_contacts = ejecutar($sql_contacts,$conexion);

       //    $reporte_contacts = array();

        //    $i = 0;      
       //    while($datos_contacts = mysqli_fetch_assoc($resultado_contacts)) {            
        //       $reporte_contacts[$i] = $datos_contacts;
         //       $i++;
       //    }   






$sqlNotes  = "
select 
*
from tbl_notes n 
left join discipline d on d.DisciplineId = n.discipline
left join careplans c on c.id_careplans = n.id_careplans
 WHERE
     pat_id = '".$pat_id."';";    




  $sqlDocuments = "
  SELECT *,trim(route_document),trim(type_documents), trim(type_persons) 
  FROM tbl_documents ct 
  LEFT JOIN tbl_doc_type_persons tp ON tp.id_type_persons = ct.id_type_person 
  LEFT JOIN tbl_doc_type_documents td ON td.id_type_documents = ct.id_type_document 
  LEFT JOIN patients ON ct.id_person = patients.Pat_id 
  WHERE true AND id_type_person = 1 AND id_person = '".$pat_id."';

  ";
$resultadoDocuments = ejecutar($sqlDocuments,$conexion);

        $reporteDocuments = array();
        
        $t = 0;      
        while($datosDocuments = mysqli_fetch_assoc($resultadoDocuments)) {            
            $reporteDocuments[$t] = $datosDocuments;
            $t++;
        }
        

       
                    
    // $resultadopatientslist = ejecutar($sqlpatientslist,$conexion);

         




 
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
 
<script src="../../../plugins/bootstrap/bootstrap.min.js"></script>
<script src="../../../plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="../../../plugins/tinymce/tinymce.min.js"></script>
<script src="../../../plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../js/promise.min.js" type="text/javascript"></script> 
<script src="../../../js/funciones.js" type="text/javascript"></script>    
<script src="../../../js/listas.js" type="text/javascript" ></script>
<script src="../../../js/devoops_ext.js"></script>


    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css"> 
       
    <!-- End Style -->
    <script type="text/javascript" language="javascript" class="init">  
         
    $(document).ready(function() { 
        $('#patientslist').DataTable({ 
            dom: 'Bfrtip', 
            buttons: [ 
                'copyHtml5', 
                'excelHtml5', 
                'csvHtml5', 
                'pdfHtml5' 
            ] 
        } ); 
    } ); 


    $(document).ready(function() { 
        $('#therapistlist').DataTable({ 
            dom: 'Bfrtip', 
            buttons: [ 
                'copyHtml5', 
                'excelHtml5', 
                'csvHtml5', 
                'pdfHtml5' 
            ] 
        } ); 
    } ); 
 
    </script> 



    <script> 
 



    function selectAllFields(){                                    
                    $("input:checkbox").each(function(){ 
                        if($("input[name=allFields]:checked").length == 1){ 
                            this.checked = true; 
                        }else{ 
                            this.checked = false; 
                        }             
                    }); 
           } 
     
    function findData(){         
        document.getElementById("myForm").submit(); 
    } 
    function loadOrderFieldsShow(valor){ 
        $("input:checkbox[name="+valor+"]").each(function(){             
            if(this.checked == true){ 
                if($("#fieldsShow").val() == '') 
                    $("#fieldsShow").val(valor);                     
                else 
                    $("#fieldsShow").val($("#fieldsShow").val()+','+valor); 
            }else{ 
                var str = $("#fieldsShow").val();                 
                var res = str.replace(valor,""); 
                res = res.replace(",,", ","); 
                $("#fieldsShow").val(res); 
            } 
            });         
    } 
        function blockCheckBox(){ 
     
        if($("#name").val() != ''){ 
            $("input:checkbox").each(function(){                                 
                $(this).attr('disabled','disabled'); 
                    });     
        }else{ 
            $("input:checkbox").each(function(){                                 
                this.disabled = false; 
                    }); 
        } 
    } 
 
    function blockCheckBox1(){ 
        if($("#patient_id").val() != ''){ 
            $("input:checkbox").each(function(){                                 
                $(this).attr('disabled','disabled');                 
                    });     
        }else{ 
            $("input:checkbox").each(function(){                                                     
                this.disabled = false; 
                    }); 
        } 
    }    
    function updatePatient(pat_id,company,patient_name){         
        $("#result_u"+pat_id).load("update_patients.php","&Patient_id="+pat_id+"&Company="+company+"&newInsurance="+$("#newInsurance_"+pat_id).val()+"&patient_name="+patient_name); 
        alert("Update Succefull"); 
        window.location="search.php"; 
    } 



function eliminar_persona_contacto(id_persona_contacto,id_contactos){
        
                                        swal({
          title: "Confirmaci&oacute;n",
          text: "Desea Eliminar la Persona Seleccionada?",
          type: "warning",
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {        
        
        $.post(
                "../../controlador/contacts/eliminar_persona_contacto.php",
                '&id_persona_contacto='+id_persona_contacto+'&id_contactos='+id_contactos,
                function (resultado_controlador) {
                    
                    swal({
                        title: resultado_controlador.mensaje,                      
                        type: "success"
                        });                    
                        setTimeout(function(){$("#buttonReload").val("RECARGADO");findData()},1500);
                    

                },
                "json"
        );

        return false;
          
          }
        });          
          
    }
    
    function guardar_cambios_modificacion(id_contacto_tipo_contacto,posicion){
        
        var id_tipo_contacto = $('#tipo_contacto'+posicion).val();
        var descripcion_contacto = $('#descripcion_contacto'+posicion).val();        
                
        
        
        $.post(
                "../../controlador/contacts/modificar_datos_contacto.php",
                '&id_tipo_contacto='+id_tipo_contacto+'&descripcion_contacto='+descripcion_contacto+'&id_contacto_tipo_contacto='+id_contacto_tipo_contacto,
                function (resultado_controlador) {
                    
                    swal({
                        title: resultado_controlador.mensaje,                      
                        type: "success"
                        }); 
                        
        $('#id_tipo_contacto_div'+posicion).html('<font size="2">'+resultado_controlador.tipo_contacto+'</font>');
        $('#descripcion_contacto_div'+posicion).html('<font size="2">'+descripcion_contacto+'</font>');
       

                },
                "json"
        );

        return false;                
        
    }
    
    
    
    function modificar_datos_contacto(id_contacto_tipo_contacto,id_tipo_contacto,descripcion_contacto,posicion){
        

        
        $('#botones_modificacion_div'+posicion).html('&nbsp;&nbsp;&nbsp;<a onclick="guardar_cambios_modificacion('+id_contacto_tipo_contacto+',\''+posicion+'\');"style="cursor:pointer"><img src="../../../images/save_2.png" width="20px"></a>')
        
        $('#id_tipo_contacto_div'+posicion).html('<select id="tipo_contacto'+posicion+'" name="tipo_contacto'+posicion+'" class="form-control"></select>');
        $('#descripcion_contacto_div'+posicion).html('<input type="text" class="form-control" id="descripcion_contacto'+posicion+'" name="descripcion_contacto'+posicion+'" value="'+descripcion_contacto+'">');
        
         autocompletar_radio('tipo_contacto','id_tipo_contacto','tbl_tipo_contacto','selector',id_tipo_contacto,null,null,null,'tipo_contacto'+posicion);
           
        
        
        
    }


    function showPatient(name_patient){
    window.open('../patients/search.php?name='+name_patient,'w','width=1300px,height=1000px,noresize');
    }

    
    function eliminar_datos_contacto(id_contacto_tipo_contacto,posicion){
        
 swal({
          title: "Confirmaci&oacute;n",
          text: "Desea Eliminar los datos de Contacto Seleccionados?",
          type: "warning",
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {        
        
        $.post(
                "../../controlador/contacts/eliminar_datos_contacto.php",
                '&id_contacto_tipo_contacto='+id_contacto_tipo_contacto,
                function (resultado_controlador) {
                    
                    swal({
                        title: resultado_controlador.mensaje,                      
                        type: "success"
                        });                    
                    
                    

                },
                "json"
        );

        return false;
          
          }
        });                        
        
    }
    
    
    function agregar_nuevo_contacto(identificador_patient){
        
 swal({
          title: "Nueva Persona de Contacto",
          text: "Seleccione la Persona de Contacto",                    
          imageUrl: "../../../images/agregar.png",
          html: '<select id="id_persona_contacto" name="id_persona_contacto" class="form-control"><option value="">Seleccione..</option></select>',
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Agregar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {        
        
        
        if($('#id_persona_contacto').val() == ''){
            
        swal({
          title: "<h3>Seleccione una Persona de Contacto</h3>",      
          type: 'warning'          
        });                        
            
        } else {
       
        
        $.post(
                "../../controlador/contacts/agregar_nueva_persona_contacto.php",
                '&identificador_patient='+identificador_patient+'&id_persona_contacto='+$('#id_persona_contacto').val(),
                function (resultado_controlador) {
                    
                    swal({
                        title: resultado_controlador.mensaje,                      
                        type: "success"
                        });    
                        setTimeout(function(){$("#buttonReload").val("RECARGADO");findData()},1500);
                                        

                },
                "json"
        );

        return false;
          
          }
          
          }
        });  
        
        autocompletar_radio('persona_contacto','id_persona_contacto','tbl_persona_contacto','selector',null,null,null,null,'id_persona_contacto');
        
    }
    
    function agregar_datos_nuevo_contacto(id_persona_contacto,pat_id){
    
 swal({
          title: "Nuevos Datos de Contacto",
          text: "Introduzca los datos solicitados",                    
          imageUrl: "../../../images/agregar.png",
          html: '<select id="id_tipo_contacto" name="id_tipo_contacto" class="form-control"><option value="">Seleccione..</option></select>&nbsp;<input class="form-control" placeholder="Datos del Tipo de Contacto" name="descripcion_contacto" id="descripcion_contacto">',
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Insertar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {        
        
        
        if($('#id_tipo_contacto').val() == ''){
            
        swal({
          title: "<h3>Seleccione una Persona de Contacto</h3>",      
          type: 'warning'          
        });                        
            
        } else {
            
            if($('#descripcion_contacto').val() == ''){

            swal({
              title: "<h3>Introduzca los Datos del Tipo de Contacto</h3>",      
              type: 'warning'          
            });                        

            } else {            
       
        
        $.post(
                "../../controlador/contacts/agregar_nuevos_datos_contacto.php",
                '&id_tipo_contacto='+$('#id_tipo_contacto').val()+'&descripcion_contacto='+$('#descripcion_contacto').val()+'&id_persona_contacto='+id_persona_contacto+'&id_tabla_ref='+pat_id,
                function (resultado_controlador) {
                    
                    swal({
                        title: resultado_controlador.mensaje,                      
                        type: "success"
                        });                    
                                        

                },
                "json"
        );

        return false;
          
          }
                  }
          
          }
        });  
        
        autocompletar_radio('tipo_contacto','id_tipo_contacto','tbl_tipo_contacto','selector',null,null,null,null,'id_tipo_contacto');
    }
      





    </script> 
</head> 
 
<body> 
 
    <!-- Navigation --> 
    
    <?php 
    if(!isset($_GET['name']))
    $perfil = $_SESSION['user_type']; include "../../vista/nav_bar/nav_bar.php"; ?>
 
 
    <!-- Page Content 
    <div class="container"> --> 
 
        <!-- Portfolio Item Heading --> 
        <div class="row"> 
<div class="col-lg-1">
            </div>

 <div class="col-lg-10"> 
               <br>              
            <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
            </div> 
        </div> 
        <!-- /.row --> 
 
        <!-- Portfolio Item Row --> 
        <div class="row"> 
 	<?php if(!isset($_GET['name'])){?>

            <div class="col-lg-1">
            </div>

           
 <div class="col-md-10"> 
             <form class="form-horizontal" id="myForm" action="search.php" method="post"  onSubmit="return Validar_patient_vacio('myForm');"> 
          <div class="col-lg-10"> 
                <h3 class="page-header">Choose Patient from List</h3> <input type="hidden" value="" id="buttonReload" name="buttonReload">
            </div> 
            <div class="row">             
            <div class="col-xs-3"> 
                <div class="input-group"> 
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span> 
                <select style="width:250px;" name='name' id='name'  onchange="blockCheckBox();">
                    <option value=''>--- SELECT ---</option>                 
                    <?php 
                    $sql  = "Select Distinct Table_name, Pat_id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name "; 
                    $conexion = conectar(); 
                    $resultado = ejecutar($sql,$conexion); 
                    while ($row=mysqli_fetch_array($resultado))  
                    {     
                        if((trim($row["Pat_id"])."-".$row["Table_name"]) == ($_POST['name'])) 
                            print("<option value='".trim($row["Pat_id"])."-".$row["Table_name"]."' selected>".$row["Patient_name"] .$row["Table_name"]    ." </option>"); 
                        else 
                            print("<option value='".trim($row["Pat_id"])."-".$row["Table_name"]."'>".$row["Patient_name"] .$row["Table_name"]    ." </option>"); 
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
        &nbsp&nbsp           <input name='reset' type='button' value=" Reset " onclick= "window.location.href = 'search.php';" class="btn btn-danger btn-lg">             
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
  <div class="col-lg-1">
</div>

            <div class="col-lg-10"> 
        <?php    
    
        if($_POST['find'] == ' Find ' || $_POST['buttonReload'] == "RECARGADO") { 
             
        ?> 
                
         
 
<div class="panel-group" id="" role="tablist" aria-multiselectable="true"> 
  <div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingOne"> 
      <h4 class="panel-title"> 
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> 
          PATIENT INFORMATION 
        </a> 
      </h4> 
    </div> 
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne"> 
      <div class="panel-body"> 
         <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                      <th>LAST_NAME</th> 
                      <th>First_name</th> 
                      <th>Pat_id</th> 
                      <th>Sex</th> 
                      <th>DOB</th> 
                      <th>Guardian</th>  
                          
            </tr>
                  </thead>



<tbody>
         <?php 
      $conexion = conectar(); 
        $result1 = mysqli_query($conexion, $sql1); 
 
             
      $row1 = mysqli_fetch_array($result1);





                        if ($_POST['name'] != '') {

                            echo '

        <tr>
 
                         <td>' . $row1['Last_name'] . '</td> 
                      <td>' . $row1['First_name'] . '</td> 
                      <td>' . $row1['Pat_id'] . '</td> 
                      <td>' . $row1['Sex'] . '</td> 
                      <td>' . $row1['DOB'] . '</td> 
                      <td>' . $row1['Guardian'] . '</td>  
                      
                     </tr>
  
                      ';            

$i=1;                       
                    $arregloPatient[$i] = $row1['Pat_id']; 
                   }
                   
 
                
     ?>  
          </tbody></table> 

 <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                       
                       
                      <th>Phone</th> 
                      <th>Barcode</th> 
                      <th>Ref_Physician</th> 
                      <th>Pri_Ins</th> 
                      <th>Company</th> 
                      <th>Thi_Ins</th>      
            </tr>
                  </thead>



<tbody>
         <?php 
      $conexion = conectar(); 
        $result1 = mysqli_query($conexion, $sql1); 
 
            $i = 1; 
      #              while($row = mysqli_fetch_array($result1,MYSQLI_ASSOC)){

       #                 $arregloPatient[$i] = $row['Pat_id'];
        #                $i++;

         #       }


         $row1 = mysqli_fetch_array($result1);
         if($_POST['name'] != ''){

             echo '

        <tr>
 
                    
                      
                      <td>'.$row1['Phone'].'</td> 
                      <td>'.$row1['barcode'].'</td> 
                      <td>'.$row1['Ref_Physician'].'</td> 
                      <td>'.$row1['Pri_Ins'].'</td> 
                      <td>'.$row1['Company'].'</td> 
                      <td>'.$row1['Thi_Ins'].'</td> 
                     </tr>
  
                      ';



         }

         ?>  
          </tbody></table> 




      </div> 
    </div> 
  </div> 
   


<div class="panel-group" id="" role="tablist" aria-multiselectable="true"> 
  <div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingTwo"> 
      <h4 class="panel-title"> 
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">  
          PATIENT STATUS
        </a> 
      </h4> 
    </div> 
    <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo"> 
      <div class="panel-body"> 
         <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                      <th align="center">OT</th> 
                      <th align="center">PT</th> 
                      <th align="center">ST</th> 
            </tr>
                  </thead>



<tbody>
        <?php 
      $conexion = conectar();
echo $Pat_id = $_POST['Pat_id']; 
echo $Pat_id;
      	

  if($_POST['name'] != ''){ 



                      $query_disciplines = " SELECT Name ,discipline.`order` FROM discipline where DisciplineId='2' or DisciplineId='3' or DisciplineId='1' order by `order` asc";
                    $result_discipline = ejecutar($query_disciplines,$conexion); 
                    
                    $columna = array();
                    $maxFil = 0;
                    $arrayDiscipline = array();
          while($datos_discipline = mysqli_fetch_assoc($result_discipline)) {  
              $arrayDiscipline[] = $datos_discipline;
            $query_count=  "SELECT  coalesce(".$datos_discipline['Name'].") as ".$datos_discipline['Name']." from (

          SELECT (case WHEN prescription_".$datos_discipline['Name']."=1  then 'REQUEST PRESCRIPTION'  else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT 
              (case WHEN     waiting_prescription_".$datos_discipline['Name']."=1 then 'WAITING FOR PRESCRIPTION' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT (case WHEN    eval_auth_".$datos_discipline['Name']."=1 then 'REQUEST AUTH EVAL' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT (case WHEN  waiting_auth_eval_".$datos_discipline['Name']."=1   then 'WAITING AUTH EVAL' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT (case WHEN  eval_patient_".$datos_discipline['Name']."=1  then 'EVALUATE PATIENT' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT (case WHEN  doctor_signature_".$datos_discipline['Name']."=1 then 'REQUEST DOCTOR SIGNATURE' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT (case WHEN  waiting_signature_".$datos_discipline['Name']."=1   then 'WAITING DOCTOR SIGNATURE' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT  (case WHEN  tx_auth_".$datos_discipline['Name']."=1 then 'REQUEST AUTH TREATMENT ' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT (case WHEN  waiting_tx_auth_".$datos_discipline['Name']."=1 then 'WAITING AUTH TREATMENT' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT (case WHEN   ready_treatment_".$datos_discipline['Name']."=1 then 'READY FOR TREATMENT' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
              SELECT (case WHEN   hold_".$datos_discipline['Name']."=1 then 'ON HOLD' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'
        UNION
               SELECT (case WHEN discharge_".$datos_discipline['Name']."=1 then 'DISCHARGING PATIENT' else NULL
                end) as ".$datos_discipline['Name']."
              FROM patients  
              JOIN patients_copy on patients.id=patients_copy.id AND patients.active=1
              WHERE patients.Pat_id= '".$pat_id."'

               ) t
 where ".$datos_discipline['Name']." is not null
              ;
      
    
";
                      
                        $result_count = ejecutar($query_count,$conexion);                                                                                                                             
                        $fil = 0;                        
                        while($datos_count = mysqli_fetch_assoc($result_count)) {                             
                            $columna[$datos_discipline['Name']][$fil] = $datos_count[$datos_discipline['Name']];                            
                            $fil++;                                                        
                        }   
                        if($fil > $maxFil){
                            $maxFil = $fil;
                        }

                }
                
                $c = 0;
                $valoresTabla = array();
                //foreach ($columna as $key => $valor){                
                $t = 0;
                while(isset($arrayDiscipline[$t])) {
                    
                    $fil = 0;                    
                    while($fil < $maxFil){
                        if(!isset($columna[$arrayDiscipline[$t]['Name']][$fil])){
                            $valoresTabla[$fil][$c] = '-';                            
                        }else{
                            $valoresTabla[$fil][$c] = $columna[$arrayDiscipline[$t]['Name']][$fil];    
                        }                                                 
                        $fil++;
                    }
                    $c++;
                    $t++;
                }       

                $x = 0;
                while(isset($valoresTabla[$x])){
                    $t=0;
                    echo '<tr>';
                    while(isset($valoresTabla[$x][$t])){
                        echo '<td>'.$valoresTabla[$x][$t].'</td>';
                        $t++;
                    }
                    echo '</tr>';
                    $x++;
                }
                    
}
   
                
     ?>  
          </tbody></table> 


      </div> 
    </div> 
  </div> 


  
 
  <div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingThree"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> 
          PATIENT AUTHORIZATIONS CLINICSOURCE 
        </a> 
      </h4> 
    </div> 
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree"> 
      <div class="panel-body"> 


    <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                       <th>Insurance</th> 
                      <th>Name</th> 
                      <th>Pat_id</th> 
                      <th>Authorization</th> 
                      <th>CPT</th> 
                      <th>Discipline</th>  
                      <th>Auth_Start</th> 
                      <th>Auth_thru</th> 
                      <th>Visits_Auth</th> 
                      <th>Visits_remen</th> 
                      <th>Company</th> 
                      <th>STATUS</th> 
            </tr>
                  </thead>



<tbody>
         <?php 
      $conexion = conectar(); 
        $result2 = mysqli_query($conexion, $sql2); 
 
            $i = 1; 
                    while($row = mysqli_fetch_array($result2,MYSQLI_ASSOC)){ 
                     
 
                        if($_POST['name'] != ''){ 
 
echo '

        <tr>
 
                         <td>'.$row['Insurance_name'].'</td> 
                      <td>'.$row['Patient_name'].'</td> 
                      <td>'.$row['Pat_id'].'</td> 
                      <td>'.$row['Auth_#'].'</td> 
                      <td>'.$row['CPT'].'</td> 
                      <td>'.$row['Discipline'].'</td>  
                      <td>'.$row['Auth_Start'].'</td> 
                      <td>'.$row['Auth_thru'].'</td> 
                      <td>'.$row['Visits_Auth'].'</td> 
                      <td>'.$row['Visits_remen'].'</td> 
                      <td>'.$row['Company'].'</td> 
                      <td>'.$row['status'].'</td> 
                     </tr>
  
                      '; 
                       
                       
                    $arregloPatient[$i] = $row['Pat_id']; 
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table> 

      </div> 
    </div> 
  </div> 
   
 
 
  <div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingFour"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour"> 
          PATIENT POC INFORMATION 
        </a> 
      </h4> 
    </div> 
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour"> 
      <div class="panel-body"> 
        


    <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                       <th>Last_name</th> 
                      <th>First_name</th> 
                      <th>Patient_ID</th> 
                      <th>Discipline</th> 
                      <th>Completed</th> 
                      <th>MD_signed</th>  
                      <th>POC_due</th> 
                      <th>Re_Eval_due</th> 
                      <th>MD_Eval_signed</th> 
                      <th>Company</th> 
                      <th>Status</th> 
            </tr>
                  </thead>
<tbody>
         <?php 
      $conexion = conectar(); 
        $result3 = mysqli_query($conexion, $sql3); 
 
            $i = 1; 
                    while($row = mysqli_fetch_array($result3,MYSQLI_ASSOC)){ 
                     
                        if($_POST['name'] != ''){  
echo '
        <tr>
 
                         <td>'.$row['Last_name'].'</td> 
                      <td>'.$row['First_name'].'</td> 
                      <td>'.$row['Patient_ID'].'</td> 
                      <td>'.$row['Discipline'].'</td> 
                      <td>'.$row['Completed'].'</td> 
                      <td>'.$row['MD_signed'].'</td>  
                      <td>'.$row['POC_due'].'</td> 
                      <td>'.$row['Re_Eval_due'].'</td> 
                      <td>'.$row['MD_Eval_signed'].'</td> 
                      <td>'.$row['Company'].'</td> 
                      <td>'.$row['status'].'</td> 
                     </tr>
  
                      '; 
                       
                       
                    $arregloPatient[$i] = $row['Pat_id']; 
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table> 



      </div> 
    </div> 
  </div> 
    <!--AGREGANDO GENERAL NOTES-->
    <div class="panel panel-default"> 
        <div class="panel-heading" role="tab" id="headingGeneral"> 
          <h4 class="panel-title"> 
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseGeneral" aria-expanded="false" aria-controls="collapseGeneral"> 
              GENERAL NOTES
            </a> 
          </h4> 
        </div> 
        <div id="collapseGeneral" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingGeneral"> 
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <thead>
                            <tr>  
                                    <th>ID NOTES GENERAL </th>
                                    <th>TYPE PERSON </th>
                                    <th>PERSON </th>
                                    <th>USER</th>
                                    <th>NOTES GENERAL</th>
                                    <th>DATE NOTES</th>
                                  
                            </tr>
                   </thead>
                    <tbody>
                         <?php 
                         
                            $conexion = conectar(); 
                            $result3 = mysqli_query($conexion, $sql_notes_general);                            
                            $i = 1; 
                                    while($row = mysqli_fetch_array($result3,MYSQLI_ASSOC)){ 

                                        if($_POST['name'] != ''){  
                                            echo '
                                                    <tr>
                                                       <td>'.$row['id_notes_general'].'</td>                                                       
                                                       <td>'.$row['type_persons'].'</td>
                                                       <td><a onclick="showPatient(\''.$row['id_person'].'\')">'.strtoupper($row['last_name_patient'].', '.$row['first_name_patient']).'</a></td>
                                                       <td>'.strtoupper($row['Last_name'].', '.$row['First_name']).'</td>
                                                       <td>'.$row['notes_general'].'</td>
                                                       <td>'.$row['date_notes'].'</td>
                                                       
                                                       
                                                    </tr>'; 


                                            $arregloPatient[$i] = $row['Pat_id']; 
                                            $i++; 
                                    } 
                                } 
                        ?>  
                    </tbody>
                </table>
            </div> 
        </div> 
  </div> 
 
  <div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingFive"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive"> 
          PATIENT PRESCRIPTIONS 
        </a> 
      </h4> 
    </div> 
    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive"> 
      <div class="panel-body"> 
       


    <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                       <th>Patient_ID</th> 
                      <th>Patient_name</th> 
                      <th>Discipline</th> 
                      <th>Diagnostic</th> 
                      <th>Issue_date</th>  
                      <th>End_date</th> 
                      <th>Physician_name</th> 
                      <th>Physician_NPI</th> 
                      <th>Company</th> 
                      <th>Status</th> 
            </tr>
                  </thead>
<tbody>
         <?php 
      $conexion = conectar(); 
        $result4 = mysqli_query($conexion, $sql4); 
 
            $i = 1; 
                    while($row = mysqli_fetch_array($result4,MYSQLI_ASSOC)){ 
                     
                        if($_POST['name'] != ''){  
echo '
                    <tr>
                      <td>'.$row['Patient_ID'].'</td> 
                      <td>'.$row['Patient_name'].'</td> 
                      <td>'.$row['Discipline'].'</td> 
                      <td>'.$row['Diagnostic'].'</td> 
                      <td>'.$row['Issue_date'].'</td> 
                      <td>'.$row['End_date'].'</td>  
                      <td>'.$row['Physician_name'].'</td> 
                      <td>'.$row['Physician_NPI'].'</td> 
                      <td>'.$row['Company'].'</td> 
                      <td>'.$row['status'].'</td> 
                    </tr>
  
                      '; 
                       
                       
                    $arregloPatient[$i] = $row['Pat_id']; 
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table> 


      </div> 
    </div> 
  </div> 
 
 
  <div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingSix"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix"> 
          PATIENT EVALUATIONS SIGNED BY DOCTOR 
          </a> 
      </h4> 
    </div> 
    <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix"> 
      <div class="panel-body"> 
       <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                      <th>Patient_ID</th> 
                      <th>Patient_name</th> 
                      <th>Discipline</th> 
                      <th>CPT</th> 
                      <th>Authorization</th>  
                      <th>Physician_name</th> 
                      <th>Physician_NPI</th> 
                      <th>Company</th> 
                      <th>File</th>
                      <th>Status</th> 
            </tr>
                  </thead>
<tbody>
         <?php 
      $conexion = conectar(); 
        $result5 = mysqli_query($conexion, $sql5); 
 
            $i = 1; 
                    while($row = mysqli_fetch_array($result5,MYSQLI_ASSOC)){ 
                     
                        if($_POST['name'] != ''){  
echo '
        <tr>
 
                      <td>'.$row['Patient_ID'].'</td> 
                      <td>'.$row['Patient_name'].'</td> 
                      <td>'.$row['Discipline'].'</td> 
                      <td>'.$row['CPT'].'</td> 
                      <td>'.$row['Authorization'].'</td> 
                      <td>'.$row['Physician_name'].'</td>   
                      <td>'.$row['Physician_NPI'].'</td> 
                      <td>'.$row['Table_name'].'</td>                           
                      <td><a onclick="window.open(\''.$row['File'].'\',\'\',\'width=900px,height=700px,noresize\');">'.$row['Route_file'].'</a></td>
                      <td>'.$row['status'].'</td> 
                     </tr>
  
                      '; 
                       
                       
                    $arregloPatient[$i] = $row['Pat_id']; 
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table>  
      </div> 
    </div> 
  </div> 
 
 
<div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingSeven"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven"> 
          PATIENT AUTHORIZATIONS THERAPY AID 
        </a> 
      </h4> 
    </div> 
    <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven"> 
      <div class="panel-body"> 
        <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                      <th>Patient_name</th> 
                      <th>Pat_id</th> 
                      <th>Discipline</th> 
                      <th>CPT</th> 
                      <th>Insurance</th>
                      <th>Authorization</th>  
                      <th>Auth_start</th>
                      <th>Auth_thru</th> 
                      <th>Received_by</th> 
                      <th>Company</th> 
                      <th>File</th>
                      <th>Status</th> 
            </tr>
                  </thead>
<tbody>
         <?php 
      $conexion = conectar(); 
        $result6 = mysqli_query($conexion, $sql6); 
 
            $i = 1; 
                    while($row = mysqli_fetch_array($result6,MYSQLI_ASSOC)){ 
                     
                        if($_POST['name'] != ''){  
echo '
        <tr>
 
                      <td>'.$row['Patient_name'].'</td> 
                      <td>'.$row['Pat_id'].'</td> 
                      <td>'.$row['Discipline'].'</td> 
                      <td>'.$row['CPT'].'</td> 
                      <td>'.$row['Insurance_name'].'</td> 
                      <td>'.$row['Auth_#'].'</td> 
                      <td>'.$row['Auth_start'].'</td> 
                      <td>'.$row['Auth_thru'].'</td>   
                      <td>'.$row['Received_by'].'</td> 
                      <td>'.$row['Company'].'</td> 
                      <td><a onclick="window.open(\''.$row['File'].'\',\'\',\'width=900px,height=700px,noresize\');">'.$row['Route_file'].'</a></td> 
                      <td>'.$row['status'].'</td> 
                     </tr>
  
                      '; 
                       
                       
                    $arregloPatient[$i] = $row['Pat_id']; 
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table>  
      </div> 
    </div> 
  </div> 
 

<div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingEight"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight"> 
          LAST TREATMETNS
        </a> 
      </h4> 
    </div> 
    <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight"> 
      <div class="panel-body"> 
        <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                      <th>Last Name</th> 
                      <th>First Name</th> 
                      <th>Age</th> 
                      <th>Date</th> 
                      <th>Therapist</th>
                      <th>Discipline</th>  
                    <th>Type</th> 
            </tr>
                  </thead>
<tbody>
         <?php 
      $conexion = conectar(); 
        $result7 = mysqli_query($conexion, $sql7); 
 
            $i = 1; 
                    while($row = mysqli_fetch_array($result7,MYSQLI_ASSOC)){ 
                     
                        if($_POST['name'] != ''){  
echo '
        <tr>
 
                      <td>'.$row['Last_name'].'</td> 
                      <td>'.$row['First_name'].'</td> 
                      <td>'.$row['Age'].'</td> 
                      <td>'.$row['Date'].'</td> 
                      <td>'.$row['therapist'].'</td> 
                      <td>'.$row['Discipline'].'</td> 
                      <td>'.$row['type'].'</td> 
                      
                     </tr>
  
                      '; 
                       
                       
                    $arregloPatient[$i] = $row['Pat_id']; 
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table>  
      </div> 
    </div> 
  </div> 





<div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingNine"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine"> 
          CONTACTS
        </a>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <a onclick="agregar_nuevo_contacto(<?php echo $pat_id;?>)" style="cursor:pointer"> 
              <img src="../../../images/agregar.png" width="20px">
        </a>           
          
      </h4> 
</div> 
    <div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNine"> 
      <div class="panel-body"> 

<!-- TABLA MIENTRAS -->

 <table   class="table table-striped table-bordered"> 
                    <thead>
                        <tr>

                                <th>PERSONA CONTACTO</th>
                                <th>CARGO</th>
                                <th>GENERO</th>
                                <th>RELACION</th>
                                <th>DESCRICION</th>
                                <th>EMAIL</th>
                                <th>TELEFONO</th>
                                <th>FAX</th>   
                                <th>Associate To</th>                                                                                                                            
                               <!-- <th>ACCION</th>-->

                        </tr>
                    </thead>

                    <tbody>
                         <?php 

 

      $conexion = conectar(); 
        $resultado_contacts = mysqli_query($conexion, $sql_contacts); 
 
            $i = 0; 
                    while($row = mysqli_fetch_array($resultado_contacts,MYSQLI_ASSOC)){ 
 
                        if($_POST['name'] != ''){  
echo '
        <tr>
                 
                     <td>'.$row['persona_contacto'].'</td>
                        
                <td>'.$row['cargo_persona_contacto'].'</td>
               <td>'.$row['genero'].'</td>
                <td>'.$row['relacion'].'</td>
               <td>'.$row['descripcion'].'</td>
                <td>'.$row['email'].'</td>
                <td>'.$row['telefono'].'</td>
             <td>'.$row['fax'].'</td>
               <td>'.$row['Associate_To'].'</td>
                     </tr>
  
                      ';                        
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table>  
                   

     <!--   <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                <th style="width:10px;" >PERSONA CONTACTO</th>
                <th>DOB</th>                                
                <th>GENERO</th>  
                <th>CARGO</th>
                <th>DATOS DE CONTACTO</th>
                <th align="center">RELACI&Oacute;N<BR><BR>DESCRIPCION</th>
                <th>ADDRESS</th>
                <th>ACTION</th>
            </tr>
                  </thead>
<tbody>   -->
         <?php
         
             
//                     $i = 0;
 //                       $color = '<tr class="odd_gradeX">';
//                        while (isset($reporte_contacts[$i])) {

 //                   echo $color;
                                                    
 //                       echo '<td align="center"><font size="2"><b>'.$reporte_contacts[$i]['persona_contacto'].'</b></font></td>';
 //                       echo '<td align="center"><font size="2">'.$reporte_contacts[$i]['fecha_nacimiento'].'</font></td>';
 //                       echo '<td align="center"><font size="2">'.strtoupper($reporte_contacts[$i]['genero']).'</font></td>';
 //                       echo '<td align="center"><font size="2">'.utf8_decode($reporte_contacts[$i]['cargo_persona_contacto']).'</font></td>';
 //                       echo '<td align="center">';
                        
                                                   
  //                      $tipo_contacto_array = explode('|',str_replace(',','',$reporte_contacts[$i]['tipo_contacto']));
  //                      $descripcion_contacto_array = explode('|',str_replace(',','',$reporte_contacts[$i]['descripcion_contacto']));
 //                       $id_contacto_tipo_contacto_array = explode('|',str_replace(',','',$reporte_contacts[$i]['id_contacto_tipo_contacto']));
 //                       $id_tipo_contacto_array = explode('|',str_replace(',','',$reporte_contacts[$i]['id_tipo_contacto']));
                        
 //                           $r=0; 
 //                           $f=1; 
 //                           while (isset($tipo_contacto_array[$r])){
                            
 //                           if($r==0){
  //                          echo '<table border="0" width="400px">';

  //                              echo '<tr>';                                
 //                               echo '<td align="left" width="40%"><font color="#585858" size="2"><b>Tipo de Contacto</b></font><td>';
 //                               echo '<td align="left" width="40%"><font color="#585858" size="2"><b>Descripci&oacute;n Contacto</b></font><td>';
 //                               echo '<td align="left" width="20%"><font color="#585858" size="2"><b>Acci&oacute;n</b></font><td>';
 //                               echo '</tr>';
 //                               echo '<tr>';
 //                               echo '<td align="left" colspan="5"><hr><td>';
 //                               echo '</tr>'; 
                                
                                
 //                           }                                
                            
                            
  //                          if($tipo_contacto_array[$r] != null && $descripcion_contacto_array[$r] != null) {
  //                              echo '<tr>';
 //                               echo '<td align="left"><div id="id_tipo_contacto_div'.$i.'_'.$r.'"><font size="2">'.$f.'&nbsp;&nbsp;&nbsp;'.$tipo_contacto_array[$r].'</font></div><td>';
  //                              echo '<td align="left"><div id="descripcion_contacto_div'.$i.'_'.$r.'"><font size="2">'.$descripcion_contacto_array[$r].'</font></div><td>';
 //                               echo '<td align="left"><div id="botones_modificacion_div'.$i.'_'.$r.'">&nbsp;<a onclick="agregar_datos_nuevo_contacto(\''.$reporte_contacts[$i]['id_persona_contacto'].'\',\''.$pat_id.'\');"style="cursor:pointer"><img src="../../../images/agregar.png" alt="Add Contact"  title="Add Contact" style="height: 15px; width: 15px" border="0" align="absmiddle"></a>&nbsp;&nbsp;<a onclick="modificar_datos_contacto(\''.$id_contacto_tipo_contacto_array[$r].'\',\''.$id_tipo_contacto_array[$r].'\',\''.$descripcion_contacto_array[$r].'\',\''.$i.'_'.$r.'\');"style="cursor:pointer"><img src="../../../images/sign-up.png" alt="Modify Contact"  title="Modify Contact" style="height: 15px; width: 15px" border="0" align="absmiddle"></a>&nbsp;&nbsp;<a onclick="eliminar_datos_contacto(\''.$id_contacto_tipo_contacto_array[$r].'\',\''.$i.'_'.$r.'\');"style="cursor:pointer"><img src="../../../images/papelera.png" alt="Modify Contact"  title="Modify Contact" style="height: 15px; width: 15px" border="0" align="absmiddle"></a></div><td>';                                
  //                              echo '</tr>';                                                                
                                
   //                         }
                            
   //                         if($tipo_contacto_array[$r] == null){
    //                            echo '</table><br>';                            
     //                       }                            
                            
                            
     //                           $r++;
     //                           $f++;
       //                     }
                        
                        
       //                 echo '</td>';
                        
      //                  echo '<td align="center"><font size="2">'.utf8_decode($reporte_contacts[$i]['relacion']).'<br><br>'.utf8_decode($reporte_contacts[$i]['descripcion']).'</font></td>';
      //                  echo '<td align="center"><font size="2">'.utf8_decode($reporte_contacts[$i]['direccion']).'</font></td>';                        
                        


        //              echo '<td align="center"><br>'
       //               . '<div id="resultado_delete'.$i.'">'
//. '<a onclick="eliminar_persona_contacto(\''.$reporte_contacts[$i]['id_persona_contacto'].'\',\''.$reporte_contacts[$i]['id_contactos'].'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Delete Contact Person"  title="Delete Contact Person" style="height: 25px; width: 25px" border="0" align="absmiddle"></a>';
  //          echo '</div></td>';
    //        $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

      //                      echo '</tr>';

        //                    $i++;
          //              }
                        ?>
       <!--   </tbody></table>  -->
      </div> 
    </div> 
  </div> 



<div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingTen"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen"> 
          NOTES
        </a> 
      </h4> 
</div> 
    <div id="collapseTen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTen"> 
      <div class="panel-body"> 
        <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                      <th>Discipline</th> 
                      <th>Notes</th> 
                      <th>Type Report</th> 
                      <th>Date</th>                       
            </tr>
                  </thead>
<tbody>
         <?php 
      $conexion = conectar(); 
        $resultNotes = mysqli_query($conexion, $sqlNotes); 
 
            $i = 0; 
                    while($row = mysqli_fetch_array($resultNotes,MYSQLI_ASSOC)){ 
 
                        if($_POST['name'] != ''){  
echo '
        <tr>
                 
                      <td>'.$row['discipline'].'</td> 
                      <td>'.$row['notes'].'</td> 
                      <td>'.$row['type_report'].'</td> 
                      <td>'.$row['date'].'</td> 
                      
                     </tr>
  
                      ';                        
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table>  
      </div> 
    </div> 
  </div>




<div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingEleven"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven"> 
          DOCUMENTS
        </a> 
      </h4> 
    </div> 
    <div id="collapseEleven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEleven"> 
      <div class="panel-body"> 
        <table   class="table table-striped table-bordered">
                  <?php                         
        $conexion = conectar();
            $datos_result = '';
            $datos_result .= '
                    <thead>
                        <tr>
                            <th>TYPE DOCUMENT</th>
                            <th>TYPE PERSON</th>
                            <th>NAME</th>
                            <th>ROUTE DOCUMENT</th>
                            <th>DATE</th> 
                            <th>ACTION</th>
                        </tr>
                    </thead>

            <tbody>';
      
            $t=0;   
            $sum_total_pay_treatment = 0;
            $total_dur = 0;
            while (isset($reporteDocuments[$t])){ 

                    $datos_result .= '<tr>';        
                    $datos_result .= '<td>'.$reporteDocuments[$t]['type_documents'].'</td>';
                    $datos_result .= '<td>'.$reporteDocuments[$t]['type_persons'].'</td>';
                    $datos_result .= '<td>'.strtoupper($reporteDocuments[$t]['Last_name'].', '.$reporteDocuments[$t]['First_name']).'</td>';                  
                    $datos_result .= '<td><a href="../../../'.$reporteDocuments[$t]['route_document'].'" target="_blank">'.$reporteDocuments[$t]['route_document'].'</a></td>';                    
                    $datos_result .= '<td>'.$reporteDocuments[$t]['date'].'</td>';                    
                    $datos_result .= '<td align="center">';
                    $datos_result .= '<a onclick="eliminar_registro_document(\''.$reporteDocuments[$t]['id_document'].'\',\''.$reporteDocuments[$t]['route_document'].'\')" style="cursor: pointer"><img style="width:30px" src="../../../images/papelera.png"></a>';
                    
                    $datos_result .= '</td>';
                    $datos_result .= '</tr>';
                    $sum_total_pay_treatment += $total_pay_treatment;
                    $t++;   
            }     
            
            $datos_result .= '</tbody>';                         
                        
                        echo $datos_result;                                                
                        
                        ?></table>  
      </div> 
    </div> 
  </div> 






 
</div> 
 
 
                 
            <tbody/> 
             
        <?php } else{?>     
            </div>         

        </div> 
        <!-- /.row --> 

<!-- ########################################################################################################## --> 
<!-- ###############  TABLA DE PACIENTES ABAJO                                      ########################### --> 
<!-- ########################################################################################################## --> 
 

 <div class="row">
            <div class="col-lg-12 text-center"><b><h4>PATIENTS LIST</h4></b></div>    
        </div>
<br>
<div class="row">
<div class="col-lg-1">
 </div>
            
<div class="col-lg-10">

    <table id="therapistlist" class="table table-striped table-bordered" cellspacing="0" width="100%">
           <thead>
            <tr>  
                      <th>Patient ID</th> 
                      <th>Name</th> 
                      <th>DOB</th> 
                      <th>Physician</th>     
                      <th>Insurance</th>                   
            </tr>
                  </thead>
<tbody>
         <?php 
     
      $conexion = conectar(); 

      $sqlpatientslist = " SELECT Pat_id, CONCAT(TRIM(Last_name),',',TRIM(First_name)) as Name,DOB,PCP,Pri_Ins
FROM patients  ";
       
        $resultpatientslist = mysqli_query($conexion, $sqlpatientslist); 
 
            $i = 0; 
                    while($row = mysqli_fetch_array($resultpatientslist,MYSQLI_ASSOC)){ 
 
                       // if($_POST['name'] != ''){  
echo '
        <tr>
                 
                      <td><a onclick="showPatient(\''.$row['Pat_id'].'\')">'.$row['Pat_id'].'</td> 
                      <td><a onclick="showPatient(\''.$row['Pat_id'].'\')">'.$row['Name'].'</td> 
                      <td>'.$row['DOB'].'</td> 
                      <td>'.$row['PCP'].'</td> 
                      <td>'.$row['Pri_Ins'].'</td> 
                      
                     </tr>
  
                      ';                        
                    $i++; 
                   // } 
                } 
     ?>  
          </tbody>
          </table>  

</div></div>

    <?php }  ?>            



        <!-- Footer --> 
        <footer> 
            <div class="row"> 
 <div class="col-lg-1">
 </div>
                
<div class="col-lg-10"> 
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
                </div> 
            </div> 
            <!-- /.row --> 
        </footer> 
 
   <!--  </div> 
    /.container --> 
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


</script>


</html>
