
<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}

$conexion = conectar();


    $id_treatments_charge = $_POST['id_treatments_charge'];

    $sql = "SELECT notes, date, concat(Last_name,', ',First_name) as user FROM tbl_treatments_charge_notes left join user_system using(user_id) WHERE id_treatments_charge = ".$id_treatments_charge.";";
    $resultado2 = ejecutar($sql,$conexion);
    
    $reporte = array();

    $y = 0;      
    while($datos = mysqli_fetch_assoc($resultado2)) {            
        $reporte[$y] = $datos;
        $y++;
    }   
    

    $html = null;


 
    $html .= '<img src="../../../images/treatments_image.jpg" width="120" height="90"/><br><br>
          <h2 class="modal-title" id="exampleModalTreatments"><font color="#848484"><b>Treatments Notes!</b></font></h2>
          <br>
          <div class=" row col-lg-12 text-center" style="margin-left: 2px;border:solid 2px black;background-color:#D8D8D8;">
              <div class="col-lg-3 text-center"><label>N°</label></div>
              <div class="col-lg-3 text-center"><label>Notes</label></div>
              <div class="col-lg-3 text-center"><label>Date</label></div>
              <div class="col-lg-3 text-center"><label>User</label></div>
          </div> ';
          
          
          
          $i=0;
          $e=1;
          while (isset($reporte[$i])){
              $html .= '<div class=" row col-lg-12 text-center" style="margin-left: 2px;border:solid 2px black;background-color:#FAFAFA;">';
                $html .= '<div class="col-lg-3 text-center"><b>'.$e.'</b></div>';              
                $html .= '<div class="col-lg-3 text-center">'.$reporte[$i]['notes'].'</div>';
                $html .= '<div class="col-lg-3 text-center">'.$reporte[$i]['date'].'</div>';
                $html .= '<div class="col-lg-3 text-center">'.$reporte[$i]['user'].'</div>';                           
              $html .= '</div>';
              $i++;
              $e++;
          }
          
          
          
      $html .= '</div>';
          

                $reporte['html'] = $html;

                echo json_encode($reporte);   
