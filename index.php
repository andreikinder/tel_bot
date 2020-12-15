<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

use Longman\TelegramBot\Request;

use Longman\TelegramBot\Entities\Keyboard;

$bot_api_key  = '1306244872:AAEcthEZKPgA1tT96DekoZVHQU2td-iMltk';
$bot_username = 'news_caf_bus_bot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Handle telegram webhook request
    $server_response = $telegram->handle();
    $entityBody = file_get_contents('php://input');
    if ($server_response) {

        $keybord = new Keyboard( [['Вконтакте' => 'vk'], ['Сайт' => 'site']]);

        file_put_contents('server_response.txt', $server_response);
        file_put_contents('entityBody.txt', $entityBody);
        $send_text = "";
        $this_update = json_decode($entityBody);

        $this_message = (isset($this_update->edited_message) ? $this_update->edited_message : $this_update->message);
        $chat_id = $this_message->chat->id;

       // $send_text = $this_message->text;

        $text = strtolower($this_message->text);


        if($text== 'vk'){
            //header('Content-type: text/html; charset=utf-8');
            $wall_id="-69632488"; // Положительное число: пользователь. Отрицательное: группа.
            $count="1"; // Количество записей, которое необходимо получить. Максимальное значение: 100.
            $ACCESS_TOKEN ='1397aac61397aac61397aac6c213e2ab25113971397aac64c22dbfee61060bf0a30780f';
            $api = file_get_contents("http://api.vk.com/method/wall.get?owner_id={$wall_id}&count={$count}&access_token={$ACCESS_TOKEN}&v=5.62");
            $wall = json_decode($api,true);
            $arr = $wall['response']['items'];
            foreach ($arr as $item) {
                $send_text = '<a href="https://vk.com/wall-69632488_'.$item['id'].'">Читать далее</a>';
                file_put_contents('send.txt', $send_text);
            }



        } elseif ($text == 'site') {
            $url = "https://magtu.ru/?format=feed&type=rss";
            $xml = simplexml_load_file($url);

            $item = $xml->channel->item[0];
            $itm_title = $item->title;
            $itm_url = $item->link;
            $send_text = '<p>'. $itm_title. '"<p/>';
            $send_text .= '<a href="'.$itm_url.'"> Читать далее </a>';


        } else {
            $send_text = '<p>Нет такого варианта ответа.<p/>';
        }

        $result = Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => $send_text,
            'parse_mode' => 'HTML',
            'reply_markup' => $keybord
        ]);

    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e->getMessage();
}