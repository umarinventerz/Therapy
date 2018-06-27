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

                if($_POST['accion'] == 'modificar' || $_POST['accion'] == 'eliminar'){    
                                                
                    if($_POST['accion'] == 'modificar'){ $mensaje_resultado = 'Modificación'; }                        
                    if($_POST['accion'] == 'eliminar'){ $mensaje_resultado = 'Eliminación'; }                                        
                    $mensaje_almacenamiento = $mensaje_resultado.' Satisfactoria';                    
                    $where = ' WHERE Phy_id = '.$_POST["Phy_id"];
                
                } else {                                
                
                $Phy_id = null;
                $mensaje_almacenamiento = 'Almacenamiento Satisfactorio';
                
                }
                
                if($_POST['accion'] == 'insertar' || $_POST['accion'] == 'modificar'){
                
                if(isset($_POST["Name"]) && $_POST["Name"] != null){ $Name = $_POST["Name"]; } else { $Name = 'null'; }
                    if(isset($_POST["NPI"]) && $_POST["NPI"] != null){ $NPI = $_POST["NPI"]; } else { $NPI = 'null'; }
                    if(isset($_POST["Taxonomy"]) && $_POST["Taxonomy"] != null){ $Taxonomy = $_POST["Taxonomy"]; } else { $Taxonomy = 'null'; }
                    if(isset($_POST["Organization"]) && $_POST["Organization"] != null){ $Organization = $_POST["Organization"]; } else { $Organization = 'null'; }
                    if(isset($_POST["Address"]) && $_POST["Address"] != null){ $Address = $_POST["Address"]; } else { $Address = 'null'; }
                    if(isset($_POST["City"]) && $_POST["City"] != null){ $City = $_POST["City"]; } else { $City = 'null'; }
                    if(isset($_POST["State"]) && $_POST["State"] != null){ $State = $_POST["State"]; } else { $State = 'null'; }
                    if(isset($_POST["Zip"]) && $_POST["Zip"] != null){ $Zip = $_POST["Zip"]; } else { $Zip = 'null'; }
                    if(isset($_POST["Contact"]) && $_POST["Contact"] != null){ $Contact = $_POST["Contact"]; } else { $Contact = 'null'; }
                    if(isset($_POST["Email"]) && $_POST["Email"] != null){ $Email = $_POST["Email"]; } else { $Email = 'null'; }
                    if(isset($_POST["Office_phone"]) && $_POST["Office_phone"] != null){ $Office_phone = $_POST["Office_phone"]; } else { $Office_phone = 'null'; }
                    if(isset($_POST["Mobile_phone"]) && $_POST["Mobile_phone"] != null){ $Mobile_phone = $_POST["Mobile_phone"]; } else { $Mobile_phone = 'null'; }
                    if(isset($_POST["Fax"]) && $_POST["Fax"] != null){ $Fax = $_POST["Fax"]; } else { $Fax = 'null'; }
                    if(isset($_POST["id_carriers"]) && $_POST["id_carriers"] != null){ $id_carriers = $_POST["id_carriers"]; } else { $id_carriers = 'null'; }
                  
                    
                                }
                                
                                
                                
                                
                                
                if(isset($_POST["accion"]) && $_POST["accion"] != null){ $accion = $_POST["accion"]; } else { $accion = 'null'; }
                
                $tabla = 'physician';
                $json_resultado['repetido'] = 'no';
                if($accion == 'insertar'){
                    
                    $sql = 'SELECT Phy_id FROM physician WHERE NPI = \''.$NPI.'\';';
                    $resultado = ejecutar($sql,$conexion);

                    $i=0;
                    while ($row=mysqli_fetch_array($resultado)) {	

                        $Phy_id = $row['Phy_id'];                                           

                    $i++;        
                    } 

                    if(isset($Phy_id) && $_POST['accion'] == 'insertar'){

                        $json_resultado['repetido'] = 'si';

                    } else {

                            $insert =" INSERT into physician (Name,NPI,Taxonomy,Organization,Address,City,State,Zip,Contact,Email,Office_phone,Mobile_phone,Fax,id_carriers) 
                            values ('".$Name."','".$NPI."','".$Taxonomy."','".$Organization."','".$Address."','".$City."','".$State."','".$Zip."','".$Contact."','".$Email."','".$Office_phone."','".$Mobile_phone."','".$Fax."','".$id_carriers."');";
                            $resultado = ejecutar($insert,$conexion);     
                    }
                    
                }
                
                if($accion == 'modificar'){
                    
                    $update =" UPDATE physician SET Name = '".$Name."', NPI = '".$NPI."', Taxonomy = '".$Taxonomy."', Organization = '".$Organization."', Address = '".$Address."', City = '".$City."', State = '".$State."', Zip = '".$Zip."', Contact = '".$Contact."', Email = '".$Email."', Office_phone = '".$Office_phone."', Mobile_phone = '".$Mobile_phone."', Fax = '".$Fax."', id_carriers = '".$id_carriers."' ".$where;
                    $resultado = ejecutar($update,$conexion);                     
                    
                } 
                
                if($accion == 'eliminar'){
                    
                    $delete = " DELETE FROM physician ".$where;
                    $resultado = ejecutar($delete,$conexion);                     
                    
                }                
                
                
           

                if($json_resultado['repetido'] != 'si'){
                     if($resultado) {
                
                        $json_resultado['resultado'] = '<h3><font color="blue">'.$mensaje_almacenamiento.'</font></h3>';

                    $json_resultado['mensaje'] = $mensaje_almacenamiento;

                    if($_POST['accion'] == 'eliminar'){
                        $json_resultado['mensaje_data_table'] = '<h5><font color="#FE642E"><b>Eliminado</b></font></h5>';
                    }


                    } else {

                    $json_resultado['resultado'] = '<h3><font color="red">Error</font></h3>';

                    } 
                    
                }
                
                 
                 echo json_encode($json_resultado);                                  

?>