<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();
$diciplina=$_GET['discipline'];
$explode=explode(',',$diciplina);

if(!isset($_GET['all'])){
    
    if(count($explode)==1 && $explode[0]=='office'){
        $sql="SELECT id,concat(Last_name,', ',First_name) as employee FROM employee where discipline_id is null";
    }
    if(count($explode)==1 && $explode[0]=="null"){ 
        
        $sql="SELECT id,concat(Last_name,', ',First_name) as employee FROM employee";
    }
    if(count($explode)==1 && $explode[0]!='office' && $explode[0]!='null'){
       
        $sql="SELECT id,concat(Last_name,', ',First_name) as employee FROM employee where discipline_id IN (".$diciplina.")";
    }
    if(count($explode)>1 && $explode[0]=='office'){
       $diciplinas=str_replace("office", "0", $diciplina);
       $sql="SELECT id,concat(Last_name,', ',First_name) as employee FROM employee where discipline_id IN (".$diciplinas.") OR discipline_id is null";
    }
    if(count($explode)>1 && $explode[0]!='office'){
        $sql="SELECT id,concat(Last_name,', ',First_name) as employee FROM employee where discipline_id IN (".$diciplina.")";
    }
    
    
}else{
    $sql="SELECT id,concat(Last_name,', ',First_name) as employee FROM employee";
}
$json = ejecutar($sql,$conexion);

while ($row=mysqli_fetch_array($json)) {
    $arreglo[] = $row;                    
            
}

?>
<script>
    $(document).ready(function(){
       
       $(".multiple").multiselect({
            buttonWidth: '330%',
            enableCaseInsensitiveFiltering:true,
            includeSelectAllOption: true,
            maxHeight:400
        });
    });

</script>
<b>Users</b><div class="input-group"> 
    <select style='width:1250px' name='users[]' id='users' class="multiple form-control" multiple onchange="consultar_users();">    
    <?php 
            for($i=0;$i<count($arreglo);$i++){

                echo "<option value='".$arreglo[$i]['id']."'>".$arreglo[$i]['employee']."</option>";
            }
    ?>
</select>


