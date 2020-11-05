<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

use Longman\TelegramBot\Request;



$bot_api_key  = '1306244872:AAEcthEZKPgA1tT96DekoZVHQU2td-iMltk';
$bot_username = 'news_caf_bus_bot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Handle telegram webhook request
    $server_response = $telegram->handle();
    $entityBody = file_get_contents('php://input');
    if ($server_response) {

        $send_text = "";
        $this_update = json_decode($entityBody);

        $this_message = (isset($this_update->edited_message) ? $this_update->edited_message : $this_update->message);
        $chat_id = $this_message->chat->id;

        $send_text = $this_message->text;

        $result = Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => $send_text,
        ]);
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e->getMessage();
}