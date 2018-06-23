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
        // Получение данных из формы
        $postToken = $_POST['token'];
        $postGroup = $_POST['group'];
        $postDate  = $_POST['date'];
        
        // Если одно из полей пустое , то выведится лишь форма
        if (empty($postToken) === TRUE || empty($postGroup) === TRUE || empty($postDate) === TRUE)
        {
            $this->render('index', [
                'displayResult' => FALSE
            ]);
            
            return FALSE;
        }
        
        // Подготовка запросов к api VK
        $this->conversations = str_replace(':token', $postToken, $this->conversations);
        $this->history = str_replace(':token', $postToken, $this->history);
        
        $conversations = self::apiRequest($this->conversations);
    
        // Сообщения с момента которых прошло более 15 минут
        foreach ($conversations->response->items as $conversation)
        {
            $lastMessageTime = time() - $conversation->last_message->date;
    
            // Если последнее время сообщения > 15 минут (900 сек)
            // то заносим данные бесседы в массив more15min для отображения
            if ($lastMessageTime > 900 && $conversation->last_message->out === 0 ) //&& date('Y-m-d', $conversation->last_message->date) === $postDate
            {
                $this->more15min[] = [
                    'lastMessageTime' => $lastMessageTime,
                    'messageText' => $conversation->last_message->text,
                    'peerId' => $conversation->last_message->peer_id
                ];
            }
    
            // Получение всех сообщений по ID беседы
            $historyUrl = str_replace(':peer_id', $conversation->conversation->peer->id, $this->history);
            $history = self::apiRequest($historyUrl);
            
            $adminAnswerTime = $userQuestionTime = NULL;
            
            // Переворачивает массив 
            // дабы прокручивать с момента начала беседы
            foreach (array_reverse($history->response->items) as $message)
            {
                // пропускаем если сообщение не подходит под введенную дату
                if (date('Y-m-d', $message->date) !== $postDate)
                {
                    continue;
                }
                // и если это исходящие сообщение
                // то записываем в ответы, иначе в вопросы
                if ($message->out === 1)
                {
                    $adminAnswerTime = $message->date;
                }
                else
                {
                    $userQuestionTime = $message->date;
                }
                
                // получаем интервал
                if ($adminAnswerTime !== NULL && $userQuestionTime !== NULL)
                {
                    $this->intervals[] = $adminAnswerTime - $userQuestionTime;
    
                    $adminAnswerTime = $userQuestionTime = NULL;
                }
            }
        }
    
        // Среднее время ответа
        $middleTime = array_sum($this->intervals) / count($this->intervals);
        // Максимальное время ответа
        $maxTime = max($this->intervals);
        // Минимальное время ответа
        $minTime = min($this->intervals);
        
        // отображение в виде используя шаблон
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
