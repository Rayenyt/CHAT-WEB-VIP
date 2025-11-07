<?php
// CONFIGURATION
$admin_telegram_id = '6648990053';
$bot_token = '8415382430:AAFrsSq4qGGs2Ks_cchsvF3uQdxA1cEydGY';
$db_file = __DIR__ . '/chat_web_vip.sqlite';

// INITIALIZE DATABASE
if(!file_exists($db_file)){
    $db = new PDO("sqlite:$db_file");
    $db->exec("CREATE TABLE users(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE,
        password TEXT,
        approved INTEGER DEFAULT 0,
        telegram_id TEXT DEFAULT NULL
    );");
    $db->exec("CREATE TABLE messages(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        message TEXT,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    );");
}else{
    $db = new PDO("sqlite:$db_file");
}

// FUNCTION TO SEND MESSAGE TO TELEGRAM
function send_telegram($chat_id, $text){
    global $bot_token;
    $url = "https://api.telegram.org/bot$bot_token/sendMessage";
    file_get_contents($url."?chat_id=$chat_id&text=".urlencode($text));
}

// SECURITY: LOG IP VISITORS (OPTIONAL)
$ip = $_SERVER['REMOTE_ADDR'];
file_put_contents("ip_logs.txt", date('Y-m-d H:i:s')." - $ip\n", FILE_APPEND);
?>
