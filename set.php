<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '1306244872:AAEcthEZKPgA1tT96DekoZVHQU2td-iMltk';
$bot_username = 'news_caf_bus_bot';
$hook_url     = 'https://newscbi.herokuapp.com/index.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
   //  echo $e->getMessage();
}