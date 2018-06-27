<?php

class PushNotification{

    public function send_push_notification($player,$mensaje){
       
        $content = array(
            "en" => $mensaje
            );

        $fields = array(
                'app_id' => "9ed6a56a-cdff-4223-a4d3-7b86e88fd487",
                'include_player_ids' => array($player),
                'data' => array("foo" => "bar"),
                'contents' => $content
        );

        $fields = json_encode($fields);       

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic N2U0MTU1YzEtZDc0YS00N2ViLTg3ODAtMzk4Y2EwOWVlZGRl'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
    
    public function consultar_usuarios(){
        
            
            $app_id = "9ed6a56a-cdff-4223-a4d3-7b86e88fd487";
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players?app_id=" . $app_id); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 
                                                       'Authorization: Basic N2U0MTU1YzEtZDc0YS00N2ViLTg3ODAtMzk4Y2EwOWVlZGRl')); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($ch); 
            curl_close($ch); 
            return $response;
    }
    
    
}