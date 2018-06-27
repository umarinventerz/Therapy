<html>
    <style>

#rcorners4 {
    border-radius: 25px;
    border: 2px solid #FFFFFF;
    padding: 20px; 
    width: 100px;
    height: 60px;  
  
    
}      

#rombo {
   width: 0;
   height: 0;
   border: 50px solid transparent; 
   border-bottom-color: #54AFD2; 
   position: relative;
   top: -50px;
} 

#rombo:after { 
   content: '';
   position: absolute; 
   left: -50px; 
   top: 50px; 
   width: 0; 
   height: 0; 
   border: 50px solid transparent; 
   border-top-color: #54AFD2;
}

.arrow_right{
    width: 70px;
    height: 100px;
}
hr{
    
    border-color: #B1B2B1;    
}

#borde_curvo {
border-radius: 25px;
border: 1px solid #819FF7;
padding: 20px;
background-color:#E6E6E6;
}

#borde_curvo2 {
border-radius: 25px;
border: 1px solid #819FF7;
padding: 20px;
background-color:#E0F2F7;
}

#borde_curvo3 {
border-radius: 25px;
border: 1px solid #819FF7;
padding: 20px;
background-color:#EAF5E0;
}

#borde_curvo4 {
border-radius: 25px;
border: 1px solid #FAAC58;
padding: 20px;
background-color:#FFECDF;
} 
        
    </style>
    <script src="js/funciones.js"></script>        
    <script>
        
        function show_options(option){
            
            if(option == 'request'){
                
                $('#request').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED NO PRESCRIPTION\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED NO PRESCRIPTION\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED NO PRESCRIPTION\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }
                                 
            if(option == 'input'){
                
                window.location.href = 'carga.php';
                
            } 
            
            if(option == 'eval_request'){
                
                $('#eval_request').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }   
            
            if(option == 'eval_waiting'){
                
                $('#eval_waiting').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR AUTH EVAL\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR AUTH EVAL\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR AUTH EVAL\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }  
            
            if(option == 'need_evaluation'){
                
                $('#need_evaluation').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'NEED EVALUTATION\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'NEED EVALUTATION\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'NEED EVALUTATION\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }  
            
            if(option == 'doctor_signature'){
                
                $('#doctor_signature').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'NOT SIGNED BY DOCTOR YET\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'NOT SIGNED BY DOCTOR YET\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'NOT SIGNED BY DOCTOR YET\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }              
            
            if(option == 'doctor_waiting'){
                
                $('#doctor_waiting').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR DOCTOR SIGNATURE\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR DOCTOR SIGNATURE\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR DOCTOR SIGNATURE\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }              
            
            if(option == 'input_signed'){
                
                window.location.href = 'signed_doctor1.php';
                
            }  
            
            if(option == 'ask_tx'){
                
                $('#ask_tx').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'ASK FOR AUTHORIZATION FOR TX\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'ASK FOR AUTHORIZATION FOR TX\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'ASK FOR AUTHORIZATION FOR TX\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }     
            
            if(option == 'ask_waiting_tx'){
                
                $('#ask_waiting_tx').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR AUTHORIZATION TX\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR AUTHORIZATION TX\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR AUTHORIZATION TX\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }  
            
            if(option == 'input_authorization'){
                
                window.location.href = 'load_authorizations.php';
                
            }  
            
            if(option == 'ready_treatment'){
                
                $('#ready_treatment').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'READY FOR TREATMENT\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'READY FOR TREATMENT\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'READY FOR TREATMENT\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }  
            
            if(option == 'path_hold'){
                
                $('#path_hold').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'PATIENTS ON HOLD\');"><b>OT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'PATIENTS ON HOLD\');"><b>PT</b></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'PATIENTS ON HOLD\');"><b>ST</b></a>&nbsp;&nbsp;');
                
            }              
        }
       
function q(event) {
    event = event || window.event;

    var canvas = document.getElementById('canvas'),
        x = event.pageX - canvas.offsetLeft,
        y = event.pageY - canvas.offsetTop;

        /*INPUT EVAL (TA)*/
        if((x > 215 && x < 282) && (y > 182 && y < 240)){
             show_options('input_authorization');      
        }
        
        /*WAITING RX*/
        if((x > 330 && x < 364) && (y > 182 && y < 240)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('Waiting RX');
             $('#imagen').html('<img src="images/Waiting_for_prescription.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED NO PRESCRIPTION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED NO PRESCRIPTION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED NO PRESCRIPTION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        } 
        
        /*INPUT RX*/
        if((x > 455 && x < 488) && (y > 182 && y < 240)){
             show_options('input');   
        }   
        
        /*INPUT AUTH (TA)*/
        if((x > 719 && x < 775) && (y > 182 && y < 240)){
             show_options('input_authorization');  
        }  
        
        /*RX REQUEST*/
        if((x > 134 && x < 192) && (y > 369 && y < 442)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('RX REQUEST');
             $('#imagen').html('<img src="images/Ask_for_auth_for_TX.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED NO PRESCRIPTION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED NO PRESCRIPTION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED NO PRESCRIPTION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        } 
        
        /*EVAL REQUEST*/
        if((x > 246 && x < 300) && (y > 369 && y < 442)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('EVAL REQUEST');
             $('#imagen').html('<img src="images/Waiting_on_Dr_Signature.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR DOCTOR SIGNATURE\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR DOCTOR SIGNATURE\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR DOCTOR SIGNATURE\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }  
        
        /*EVAL SIGN REQUEST*/
        if((x > 365 && x < 439) && (y > 369 && y < 442)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('EVAL SIGN REQUEST');
             $('#imagen').html('<img src="images/Ask_For_Dr_Signature.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'NOT SIGNED BY DOCTOR YET\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'NOT SIGNED BY DOCTOR YET\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'NOT SIGNED BY DOCTOR YET\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }    
        
        /*WAITING EVAL AUTH*/
        if((x > 666 && x < 729) && (y > 369 && y < 442)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('WAITING EVAL AUTH');
             $('#imagen').html('<img src="images/Waiting_for_prescription.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR AUTH EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR AUTH EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR AUTH EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }       
        
        /*REQUEST EVAL AUTH*/
        if((x > 776 && x < 850) && (y > 369 && y < 442)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('REQUEST EVAL AUTH');
             $('#imagen').html('<img src="images/Evaluation_Auth_Request.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'POC EXPIRED THAT NEED ASK FOR AUTHORIZATION FOR EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }   
                
        /*REQUEST AUTH TX*/
        if((x > 203 && x < 254) && (y > 558 && y < 615)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('REQUEST AUTH TX');
             $('#imagen').html('<img src="images/Ask_for_auth_for_TX.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'ASK FOR AUTHORIZATION FOR TX\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'ASK FOR AUTHORIZATION FOR TX\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'ASK FOR AUTHORIZATION FOR TX\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }     
        
        /*WAITING AUTH TX*/
        if((x > 323 && x < 367) && (y > 549 && y < 615)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('WAITING AUTH TX');
             $('#imagen').html('<img src="images/Waiting_for_prescription.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'WAITING FOR AUTH EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'WAITING FOR AUTH EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'WAITING FOR AUTH EVAL\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }    
        
        /*PATIENT READY TX*/
        if((x > 192 && x < 242) && (y > 631 && y < 688)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('PATIENT READY TX');
             $('#imagen').html('<img src="images/Ready_For_Treatment.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'READY FOR TREATMENT\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'READY FOR TREATMENT\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'READY FOR TREATMENT\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }   
        
        /*PATIENT ON HOLD*/
        if((x > 286 && x < 343) && (y > 631 && y < 688)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('PATIENT ON HOLD');
             $('#imagen').html('<img src="images/Patient_on_Hold.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'PATIENTS ON HOLD\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'PATIENTS ON HOLD\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'PATIENTS ON HOLD\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }    
        
        /*EVAL PATIENT*/
        if((x > 550 && x < 606) && (y > 546 && y < 628)){
             $('#modal_formulario_wizard').modal('show');  
             $('#nombre_icono').html('EVAL PATIENT');
             $('#imagen').html('<img src="images/Need_Evaluation.png" width="110" height="110"/>');
             $('#contenido').html('<a style="cursor: pointer" onclick="abrirVentana(\'OT\',97530,\'NEED EVALUTATION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>OT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'PT\',97110,\'NEED EVALUTATION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>PT</b></font></a>&nbsp;&nbsp;<a style="cursor: pointer" onclick="abrirVentana(\'ST\',92507,\'NEED EVALUTATION\');$(\'#modal_formulario_wizard\').modal(\'toggle\');$(\'#modal_formulario_wizard\').modal(\'hide\');"><font size="4"><b>ST</b></font></a>');
        }          
                
}       
       $("#canvas").css("cursor","pointer");   
       
    </script>
    
<div class="modal fade" id="modal_formulario_wizard" tabindex="-1" role="dialog" aria-labelledby="Formulario_Wizard">
  <div class="modal-dialog" role="document">
    <div class="modal-content">              
      <div class="modal-body text-center"> 
          <div id="imagen"></div><br>
          <h2 class="modal-title" id="Formulario_Wizard"><font color="#848484"><b><div id="nombre_icono"></div></b></font></h2>
      </div>
      <div class="modal-body text-center">          
          <div id="contenido"></div>                                    
      </div>

    </div>
  </div>
</div>    
    
    
    <body >
        <br>        
       
        
        <div class="row" align="center">
            <div id="canvas" style='width: 1100px; height: 600px; background-repeat: no-repeat; background-image: url("../../../images/imagen_diagrama_completo.png"); background-size: 1100px 600px;' onclick="#"></div>
        </div> 
        
        
        
        
    </body>
</html>
         
          

        <!-- /content -->


        
    
