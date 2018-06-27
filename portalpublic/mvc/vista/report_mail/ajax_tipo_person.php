<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}

    $conexion = conectar();
    if($_GET['variable']==1){
        $type_person='Patients';
        $sql  = "Select Distinct Pat_id as id, concat(Last_name,', ',First_name) as full_name from patients where active=1 order by id";
    }
    if($_GET['variable']==2){
        $type_person='Employee';
        $sql  = "Select Distinct id as id, concat(first_name,' ',last_name) as full_name from employee where status='1' order by full_name";
    }
    if($_GET['variable']==3){
        $type_person='Insurance';
        $sql  = "SELECT Distinct id as id,insurance as full_name FROM seguros order by full_name";
    }
    if($_GET['variable']==4){
        $type_person='Physician';
        $sql  = "Select Distinct Phy_id as id, Name as full_name from physician order by full_name";
    }
    if($_GET['variable']==5){
        $type_person='Referral';
        $sql  = "Select id_referral as id, First_name as full_name from tbl_referral order by full_name";
    }
    if($_GET['variable']==6){
        $type_person='Contact';
        $sql  = "Select id_persona_contacto as id, persona_contacto as full_name from tbl_contacto_persona order by full_name";
    }
    $resultado = ejecutar($sql,$conexion); 
    $j = 0;      
    while($datos = mysqli_fetch_assoc($resultado)){            
        $reporte[$j] = $datos;
        $j++;
    } 
    
    
    
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
    
</head>
        <div class="form-group">
            <label><font color="#585858"><?=$type_person?></font></label>
            <div class="row">
                <div class="col-sm-10" id="tipo_persona_select">
                    <select name="id[]" id="id" class="multiple" multiple>                    
                        <?php 
                            $i=0;
                            while(isset($reporte[$i])!=null){
                        ?>
                            <option value="<?=$reporte[$i]['id']?>"><?=$reporte[$i]['full_name']?></option>
                        <?php
                            $i++;
                            }
                        ?>
                    </select>
                </div>
            </div>
            <input type="hidden" name="tipo_persona" id="tipo_persona" value="<?=$_GET['variable']?>"/>
        </div>
            

			    
       
        
<script>
    $(".multiple").multiselect({
	 buttonWidth: '100%',
	 enableCaseInsensitiveFiltering:true,
	 includeSelectAllOption: true,
	 maxHeight:400,
	 nonSelectedText: 'Seleccione',
	 selectAllText: 'Seleccionar todos'
    });
</script>
</html>
