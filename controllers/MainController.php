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
class MainController extends BaseObject
{
    private $intervals = [];
    private $more15min = [];
    
    private $conversations = 'https://api.vk.com/method/messages.getConversations?v=5.41&access_token=:token&count=200&offset=0';
    private $history = 'https://api.vk.com/method/messages.getHistory?v=5.41&peer_id=:peer_id&access_token=:token&count=200&offset=0';
    
    public function actionIndex()
    {
        $postToken = $_POST['token'];
        $postGroup = $_POST['group'];
        $postDate  = $_POST['date'];
        
        if (empty($postToken) === TRUE || empty($postGroup) === TRUE || empty($postDate) === TRUE)
        {
            $this->render('index', [
                'displayResult' => FALSE
            ]);
            
            return FALSE;
        }
        
        $this->conversations = str_replace(':token', $postToken, $this->conversations);
        $this->history = str_replace(':token', $postToken, $this->history);
        
        $conversations = self::apiRequest($this->conversations);
    
        foreach ($conversations->response->items as $conversation)
        {
            $lastMessageTime = time() - $conversation->last_message->date;
    
            if ($lastMessageTime > 900 && $conversation->last_message->out === 0 && date('Y-m-d', $conversation->last_message->date) === $postDate)
            {
                $this->more15min[] = [
                    'lastMessageTime' => $lastMessageTime,
                    'messageText' => $conversation->last_message->text,
                    'peerId' => $conversation->last_message->peer_id
                ];
            }
    
            $historyUrl = str_replace(':peer_id', $conversation->conversation->peer->id, $this->history);
            $history = self::apiRequest($historyUrl);
            
            $adminAnswerTime = $userQuestionTime = NULL;
            
            foreach (array_reverse($history->response->items) as $message)
            {
                if (date('Y-m-d', $message->date) !== $postDate)
                {
                    continue;
                }
    
                if ($message->out === 1)
                {
                    $adminAnswerTime = $message->date;
                }
                else
                {
                    $userQuestionTime = $message->date;
                }
                
                if ($adminAnswerTime !== NULL && $userQuestionTime !== NULL)
                {
                    $this->intervals[] = $adminAnswerTime - $userQuestionTime;
    
                    $adminAnswerTime = $userQuestionTime = NULL;
                }
            }
        }
    
        $middleTime = array_sum($this->intervals) / count($this->intervals);
        $maxTime = max($this->intervals);
        $minTime = min($this->intervals);
        
        $this->render('index', [
            'more15min' => $this->more15min,
            'middleTime' => $middleTime,
            'maxTime' => $maxTime,
            'minTime' => $minTime,
            'groupId' => $postGroup,
            'displayResult' => TRUE
        ]);
    }
    
    /**
     * Декодирование  ответа из json
     * 
     * @param type $data
     * @return type
     */
    public static function apiRequest($url)
    {
        $data = file_get_contents($url);
        
        return json_decode($data);
    }
}
