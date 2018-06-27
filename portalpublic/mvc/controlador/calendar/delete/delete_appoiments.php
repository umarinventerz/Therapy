<?php
session_start();
require_once("../../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();

$id_calendar=$_GET['id_calendar'];
$id_date=$_GET['id_date'];
$type=$_GET['type_appoiments'];

if($type==1){
    
    $delete_date = "DELETE FROM calendar_appoiment_date WHERE calendar_appoiment_id=".$id_calendar;
    $resultado_date = ejecutar($delete_date,$conexion);
    
    $delete = "DELETE FROM calendar_appointments WHERE id=".$id_calendar;
    $resultado = ejecutar($delete,$conexion);   
    
    
}

if($type==2){
        $cuantos=$_GET['cuantos'];
    
        if($cuantos=='is'){
            $sql_date_existente="select start from calendar_appoiment_date where id=".$id_date;
            $ejecutar = ejecutar($sql_date_existente,$conexion);

            while ($row=mysqli_fetch_array($ejecutar)){
                $date_existente['start']= $row['start'];       
            }


            $dia= date('w', strtotime($date_existente['start'])); 
            switch ($dia){
                case 1:
                    $day='monday';
                break;

                case 2:
                    $day='tuesday';
                break;

                case 3:
                    $day='wednesday';
                break;

                case 4:
                    $day='thursday';
                break;

                case 5:
                    $day='friday';
                break;

                case 6:
                    $day='saturday';
                break;

                case 7:
                    $day='sunday';
                break;
            }

            $update = "UPDATE calendar_appoiment_recurring SET ".$day."=' ' WHERE calendar_appoiment_id=".$id_calendar;
            $resultado_update = ejecutar($update,$conexion);    

            $delete_date = "DELETE FROM calendar_appoiment_date WHERE id=".$id_date;
            $resultado_date = ejecutar($delete_date,$conexion);

            $sql_recurrin="select * from calendar_appoiment_recurring where calendar_appoiment_id=".$id_calendar;
            $ejecutar = ejecutar($sql_recurrin,$conexion);
            $k=0;
            while ($row=mysqli_fetch_array($ejecutar)){
                $day_recurrin['monday'] = $row['monday'];  
                $day_recurrin['tuesday'] = $row['tuesday'];  
                $day_recurrin['wednesday'] = $row['wednesday'];  
                $day_recurrin['thursday'] = $row['thursday'];  
                $day_recurrin['friday'] = $row['friday'];  
                $day_recurrin['saturday'] = $row['saturday'];  
                $day_recurrin['sunday'] = $row['sunday'];  
                $k++;        
            }
            if($day_recurrin['monday']!=' '){
                $day_recurrin_seteado[]='monday';
            }
            if($day_recurrin['tuesday']!=' '){
                $day_recurrin_seteado[]='tuesday';
            }
            if($day_recurrin['wednesday']!=' '){
                $day_recurrin_seteado[]='wednesday';
            }
            if($day_recurrin['thursday']!=' '){
                $day_recurrin_seteado[]='thursday';
            }
            if($day_recurrin['friday']!=' '){
                $day_recurrin_seteado[]='friday';
            }
            if($day_recurrin['saturday']!=' '){
                $day_recurrin_seteado[]='saturday';
            }
            if($day_recurrin['sunday']!=' '){
                $day_recurrin_seteado[]='sunday';
            }

            if(count($day_recurrin_seteado)==0){

                $delete_calendar = "DELETE FROM calendar_appointments WHERE id=".$id_calendar;
                $resultado_calendar = ejecutar($delete_calendar,$conexion); 

                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_appoiment_id=".$id_calendar;
                $resultado_recurring = ejecutar($delete_recurring,$conexion); 
            }
        }
        if($cuantos=='all'){            
                
                $delete_calendar = "DELETE FROM calendar_appointments WHERE id=".$id_calendar;
                $resultado_calendar = ejecutar($delete_calendar,$conexion); 

                $delete_recurring = "DELETE FROM calendar_appoiment_recurring WHERE calendar_appoiment_id=".$id_calendar;
                $resultado_recurring = ejecutar($delete_recurring,$conexion); 
                
                $delete_calendar_date = "DELETE FROM calendar_appoiment_date WHERE calendar_appoiment_id=".$id_calendar;
                $resultado_calendar_date = ejecutar($delete_calendar_date,$conexion);
        }
}
if($type==1 && $resultado && $resultado_date){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}

if($type==2 && $resultado_update && $resultado_date && $day_recurrin_seteado>0 && $cuantos=='is'){
    $array=array('success'=>true);
}elseif($type==2 && $resultado_update && $resultado_date && $day_recurrin_seteado==0 && $cuantos=='is'){
    $array=array('success'=>true);
}elseif($type==2 && $resultado_calendar && $resultado_recurring && $resultado_calendar_date && $cuantos=='all'){
    $array=array('success'=>true);
}else{
    $array=array('success'=>false);
}
echo json_encode($array);
?>


