<?php
include 'config.php';

if(isset($_POST['message']) && isset($_POST['user_id'])){
    $msg = trim($_POST['message']);
    $uid = intval($_POST['user_id']);
    $stmt = $db->prepare("INSERT INTO messages(user_id,message) VALUES (?,?)");
    $stmt->execute([$uid,$msg]);
    $stmt2 = $db->prepare("SELECT username FROM users WHERE id=?");
    $stmt2->execute([$uid]);
    $username = $stmt2->fetchColumn();
    send_telegram($admin_telegram_id, "رسالة جديدة من $username: $msg");
    echo "ok";
    exit;
}

if(isset($_GET['get']) && isset($_GET['user_id'])){
    $uid = intval($_GET['user_id']);
    $stmt = $db->prepare("SELECT * FROM messages WHERE user_id=? ORDER BY id ASC");
    $stmt->execute([$uid]);
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr=[];
    foreach($res as $r){
        $arr[]= ['text'=>$r['message'],'self'=>true];
    }
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}
?>
