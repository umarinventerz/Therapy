<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();
//consulto si el appoiments enviado tiene alguna evaluacion asociada

$appoiment="SELECT COUNT(*) as contador,E.id FROM tbl_visits V LEFT JOIN tbl_evaluations E ON (E.visit_id=V.id)
            WHERE (E.deleted = 0 OR E.deleted IS NULL) AND  V.app_id=".$_POST['id_date'];

$active_appoiment = ejecutar($appoiment,$conexion);
while ($row_appoiment=mysqli_fetch_array($active_appoiment)) {
    $valor_appoiment['contador'] = $row_appoiment['contador'];
    $valor_appoiment['id'] = $row_appoiment['id'];
}

if($valor_appoiment['contador']>0){
    
    $mensaje="<b>This appointment already have an associated evaluation</b>";
    $success='reenvio';
    $id_appoiment=$_POST['id_date'];
    $pat_id=$_POST['pat_id'];
    $discipline=$_POST['diciplina'];
    $id=$valor_appoiment['id'];
  
    
    $arreglo=array('mensaje'=>$mensaje,'success'=>$success,'id_appoiment'=>$id_appoiment,'pat_id'=>$pat_id,'disciplina'=>$discipline,'id'=>$id);
    echo json_encode($arreglo); 
    
}
else
    {


        if($_POST['insertar']=='no') {



            $info_pat="SELECT * FROM patients
                    WHERE  id =".$_POST['pat_id'];
            $info_pat_id = ejecutar($info_pat,$conexion);
            while ($row=mysqli_fetch_array($info_pat_id)) {
                $salida['Pat_id'] = $row['Pat_id'];
            }


            $info_disciplina="SELECT * FROM discipline
                    WHERE  DisciplineId =".$_POST['diciplina'];
            $info_disciplina1 = ejecutar($info_disciplina,$conexion);
            while ($row=mysqli_fetch_array($info_disciplina1)) {
                $salida2['Name'] = $row['Name'];
            }

            /*EVALUALITON Los Eval son hijos del RX Prescription . Osea que se tienen que guardar debajo
                 * de alguna RX prescription. Para saber con que RX prescription relacionar esta EVAL*/

            //Paso 1 Primero consultar si existe alguna  RX prescription con status 2  -(ACTIVE )

            $contador_sql="SELECT count(*) as contador,P.id_prescription,P.Table_name as company,PA.Pat_id,C.company_id,C.company_name,C.facility_phone,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation,D.DiagCodeId FROM prescription P"
                . " LEFT JOIN patients PA ON(P.Patient_ID=PA.id) LEFT JOIN companies C ON(P.Table_name=C.company_name)"
                . " LEFT JOIN diagnosiscodes D ON(P.Diagnostic=D.DiagCodeId) 
                        WHERE  (P.deleted = 0 OR P.deleted IS NULL)
                        AND P.status=2 AND P.Patient_ID=".$salida['Pat_id'];
            $active = ejecutar($contador_sql,$conexion);
            while ($row=mysqli_fetch_array($active)) {
                $valor_contador['contador'] = $row['contador'];
                $valor_contador['id_prescription'] = $row['id_prescription'];
                $valor_contador['diagnostic_relation'] = $row['diagnostic_relation'];
                $valor_contador['diagnostic_id'] = $row['DiagCodeId'];
                $valor_contador['Pat_id'] = $row['Pat_id'];
                $valor_contador['company_id'] = $row['company_id'];
                $valor_contador['company_name'] = $row['company_name'];
                $valor_contador['facility_phone'] = $row['facility_phone'];
            }

            if($valor_contador['contador']>0){

 #ienen prescriptions asociada pero tienes que ver si tiene alguna evaluacion asocada para estar seguro del proceso


                //paso 2 Consultar si esa RX Prescription  tiene alguna Evaluation asociada  ,TABLA tbl_evaluations
                $eval_sql="SELECT count(*) as contador FROM tbl_evaluations e 
                    WHERE  (e.deleted = 0 OR e.deleted IS NULL) and e.id_prescription=".$valor_contador['id_prescription'];
                $active_eval = ejecutar($eval_sql,$conexion);
                while ($row=mysqli_fetch_array($active_eval)) {
                    $valor_eval['contador'] = $row['contador'];
                }

                if($valor_eval['contador']>0){

                    #ya esa prescriptions tiene asociada una evaluacion por lo que no es valida

                }
                else{

                    #aqui en este paso debo devolver las cosas al sistema para que se cree la evaluacion


                    $mensaje="<b>¿Are you sure you want to create This EVAL?</b>";
                    $success=true;
                    $diagnostic=$valor_contador['diagnostic_relation'];
                    $diagnostic_id=$valor_contador['diagnostic_id'];
                    $id_preescription=$valor_contador['id_prescription'];
                    $patient_id=$valor_contador['Pat_id'];
                    $company_id=$valor_contador['company_id'];
                    $company_name=$valor_contador['company_name'];
                    $facility_phone=$valor_contador['facility_phone'];

                  //  $arreglo=array('mensaje'=>$mensaje,'success'=>$success,'id_preescription'=>$id_preescription,'diagnostic'=>$diagnostic,'patient_id'=>$patient_id,'company_id'=>$company_id,'company_name'=>$company_name,'facility_phone'=>$facility_phone,'diagnostic_id'=>$diagnostic_id);
                  //  echo json_encode($arreglo);

                }




            }


            else{

                #no tienen prescription asocioada
                $mensaje="<b>There is no ACTIVE  OR IN PROGRESS RX (Prescription) to assign this EVAL . Please Contact your Supervisor</b>";
                $success=false;


            }

            $arreglo=array('mensaje'=>$mensaje,'success'=>$success,'id_preescription'=>$id_preescription,'diagnostic'=>$diagnostic,'patient_id'=>$patient_id,'company_id'=>$company_id,'company_name'=>$company_name,'facility_phone'=>$facility_phone,'diagnostic_id'=>$diagnostic_id);
            echo json_encode($arreglo);

        }




            if($_POST['autorizacion']=='si'){ 

            }



        if($_POST['insertar']=='si'){

            //paso 7

            $insert_visit="INSERT INTO tbl_visits(pat_id,user_id,visit_date,visit_discip_id,id_visit_type,visit_loc_id,app_id,auth_id) "
                    . "VALUES('".$_POST['pat_id']."','".$_POST['therapista_id']."','".$_POST['star_time']."','".$_POST['diciplina']."','1','".$_POST['location']."','".$_POST['id_date']."','".$_POST['aut_id']."')";

            $resultado = ejecutar($insert_visit,$conexion);

            //consulto el ultimo id de la tabla visitas
            $last_visit  = "SELECT max(id) as identificador FROM tbl_visits;";
            $resultado_visit = ejecutar($last_visit,$conexion); 

            while($datos = mysqli_fetch_assoc($resultado_visit)) {            
                $id_visit = $datos['identificador'];

            }
            //inserto la evaluacion            
            $insert_eval="INSERT INTO tbl_evaluations(name,discipline_id,id_user,created,diagnostic,id_prescription,patient_id,company,visit_id)"
                    . "VALUES('".$_POST['name_patients']."','".$_POST['diciplina']."','".$_POST['therapista_id']."','".$_POST['star_time']."','".$_POST['diagnostic_id']."','".$_POST['id_preescription']."','".$_POST['pat_id']."','".$_POST['company_id']."','".$id_visit."')";

            $insertar_dat=ejecutar($insert_eval,$conexion); 
            if($insertar_dat){
                $mensaje="<b>Information was loaded correctly</b>";
                $success=true;

                //consulto el ultimo id de la tabla evaluaciones
                $last_eval  = "SELECT max(id) as identificador FROM tbl_evaluations;";
                $resultado_eval = ejecutar($last_eval,$conexion); 

                while($datos_eval = mysqli_fetch_assoc($resultado_eval)){            
                    $id_eval = $datos_eval['identificador'];
                }

            }else{
                $mensaje="<b>An error has occurred, please try again</b>";
                $success=false;
            }
            $arreglo=array('mensaje'=>$mensaje,'success'=>$success, 'id_eval'=>$id_eval);
            echo json_encode($arreglo);


        }       
        
}