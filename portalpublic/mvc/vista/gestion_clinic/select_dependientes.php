<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}

$conexion = conectar();

?>

<?php 

    if($_GET['type']=='si'){
?>
<select style="width:280px; padding: 3px 1px; "  name='type' id='type' class="populate placeholder" required onchange="cpt_select();">

    <option value=''>--- SELECT ---</option>        
    <?php
      $conexion = conectar();
      $sql  = "select ID,type from cpt 
      where DisciplineId='".$_GET['id']."' 
      group by type ";  
      $resultado = ejecutar($sql,$conexion);
      while ($row=mysqli_fetch_array($resultado)) 
      {     

              print("<option value='".$row["type"]."'>".$row["type"]."</option>");

      }
      ?>
</select>
    <?php } ?>

<?php 

    if($_GET['type_update']=='si'){
?>
<select style="width:280px; padding: 3px 1px; "  name='type' id='type' class="populate placeholder" required onchange="cpt_select_update();">

    <option value=''>--- SELECT ---</option>        
    <?php
      $conexion = conectar();
      $sql  = "select ID,type from cpt 
      where DisciplineId='".$_GET['id']."' 
      group by type ";  
      $resultado = ejecutar($sql,$conexion);
      while ($row=mysqli_fetch_array($resultado)) 
      {     

              print("<option value='".$row["type"]."'>".$row["type"]."</option>");

      }
      ?>
</select>
    <?php } ?>
    
    
    <?php if($_GET['cpt']=='si'){?>
        <select style="width:280px; padding: 3px 1px; "  name='cpt_select' id='cpt_select' class="populate placeholder" required>
            <option value=''>--- SELECT ---</option>   
            <?php
              $conexion = conectar();
              $sql  = "select ID,cpt from cpt WHERE DisciplineId=".$_GET['id_discipline']." AND type='".$_GET['id_type']."'";  
              $resultado = ejecutar($sql,$conexion);
              while ($row=mysqli_fetch_array($resultado)) 
              {     

                      print("<option value='".$row["ID"]."'>".$row["cpt"]."</option>");

              }
              ?>
        </select>
    <?php } ?>

<script type="text/javascript">

    $(document).ready(function() {
        $('#insurance').select2();
        $('#discipline').select2();
        $('#type').select2();
        $('#cpt').select2();
        $('#visit').select2();
        
    });

</script>
