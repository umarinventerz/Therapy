<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="../../home/home.php";</script>';
    }
}

$conexion = conectar();
if(isset($_POST)){
 //   if(isset($_POST["Pat_id"]) && $_POST["Pat_id"] != null){ $Pat_id = $_POST["Pat_id"]; } else { $Pat_id = 'null'; }
 //   if(isset($_POST["email"]) && $_POST["email"] != null){ $email_to = $_POST["email"]; } else { $email_to = 'null'; }
 //   if(isset($_POST["content"]) && $_POST["content"] != null){ $content = $_POST["Pat_id"]; } else { $Pat_id = 'null'; }
if(isset($_POST["actions"]) && isset($_FILES["avatar"])){

    if($_POST["actions"]=='cambiar_logo'){
        $target_dir = "../../../";
        $target_file = $target_dir.'images/LOGO_1.png';
        $target_file_uno = $target_dir.'images/LOGO_usar.png';


        $uploadOk = 1;
        $out='';

        $avatar= $_FILES["avatar"]['tmp_name'];
        $avatar1=basename($_FILES["avatar"]["name"]);

        $check = getimagesize($_FILES["avatar"]["tmp_name"]);


        if($check !== false) {
            $out= "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $out="File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["avatar"]["size"] > 500000) {
            $out="Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $out= "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        }
        if ($uploadOk == 1) {

$file=$_FILES["avatar"]["tmp_name"];

            //$source=move_uploaded_file($file,$target_file_uno);

                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {



                  shell_exec('cp -R $target_file $target_file1');
                    $out1=  copy ($target_file,"$target_file_uno");
                   $out= "The file ". basename($_FILES["avatar"]["name"]). " has been uploaded.";
                    echo json_encode($out1);


                }else {
                    echo "Sorry, there was an error uploading your file.";

                }


        }








        }

       // echo json_encode('bad');
    }






}


##$json_resultado['correo_paciente'] = 'bien';
#$json_resultado['contenido'] = 'vamos a ver';
#$json_resultado['forma'] = 'error';



?>