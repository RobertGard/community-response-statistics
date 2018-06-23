<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MainController
 *
 * @author robert
 */
class MainController extends BaseObject {
    private $access_token = '';


    private $getConversations = 'https://api.vk.com/method/messages.getConversations?v=5.41&access_token=:token&count=200&offset=0';
        
    private $getHistory = 'https://api.vk.com/method/messages.getHistory?v=5.41&peer_id=:id&access_token=:token&count=200&offset=0';
        
    
    public function actionIndex(){
        $this->getConversations = str_replace(':token', $this->access_token, $this->getConversations);
        $this->getHistory = str_replace(':token', $this->access_token, $this->getHistory);
        
        $data = self::decodeData($this->getConversations);
        
        $date = date('Y-m-d', 1529699175);
        
        $array_data ;
        $previous_message = '';
        
        $i = 0;
        foreach ($data->response->items as $items )
        {
            $str = str_replace(':id', $items->conversation->peer->id, $this->getHistory);
            foreach (self::decodeData($str) as $all_message)
            {
                foreach ($all_message->items as $messages){
                    if(date('Y-m-d',$messages->date) == $date && $messages->from_id == 155194754){
                        $answer = $messages;
                        
                        $array_data = ($answer->date - $previous_message->date);
                    }
                    
                    $previous_message = $messages;
                    $i++;
                }
            }
        }
        
        var_dump(date('Y-m-d H:i:s',$array_data)); 
        
//        $this->render('index', compact('array'));
    }
    
    /**
     * Декодирование  ответа из json
     * 
     * @param type $data
     * @return type
     */
    public static function decodeData($data){
        return json_decode(file_get_contents($data));
    }
}
